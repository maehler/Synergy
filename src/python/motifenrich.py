#!/usr/bin/python26

import sys
from pprint import pprint
import MySQLdb
import numpy as np
from scipy.stats import fisher_exact
import itertools

import padjust
import config

def query_database(gene_set, operon_set, cursor, getall=False, fimoq=0.125):
    # Find the genes associated to the operons and add them to the gene set.
    for operon in operon_set:
        opquery = '''
        
        SELECT
            og.gene_id
        FROM operon_gene AS og
        LEFT OUTER JOIN operon AS o
            ON o.id = og.operon_id
        WHERE o.name IN %s''' % operon_array

        opresult = cursor.execute(opquery)
        oprows = cursor.fetchall()
        for row in oprows:
            gene_set.add(str(row[0]))

    try:
        gene_array = ''.join(['(', 
            ','.join(['%s' % x for x in gene_set if x[-2:] != 'op']), ')'])
    except TypeError:
        # Guess that the ids are numbers
        gene_array = '(' + ', '.join([str(x) for x in gene_set]) + ')'
    
    # Get the motifs associated with each gene
    query = ''' 

    SELECT 
        g.orf_id AS 'orf',
        g.id AS 'geneid',
        m.name AS 'motif',
        m.id AS 'motifid'
    FROM gene AS g
    LEFT OUTER JOIN promoter AS p
        ON p.gene_id = g.id
    LEFT OUTER JOIN promoter_motif AS pm
        ON pm.promoter_id = p.id
    LEFT OUTER JOIN motif AS m
        ON pm.motif_id = m.id '''
    if not getall:
        query += '''WHERE g.id IN %s''' % (gene_array)

    result = cursor.execute(query)

    if result == 0:
        print 'No motifs found'
        cursor.close()
        db.close()
        sys.exit(1)
    allrows = cursor.fetchall()
    motif_ids = set([x[3] for x in allrows])
    genes = set([x[1] for x in allrows])

    # Get all mapped motifs
    query = ''' SELECT
                    p.gene_id,
                    pm.motif_id,
                    m.name
                FROM promoter_motif AS pm
                LEFT OUTER JOIN motif AS m
                    ON pm.motif_id = m.id
                LEFT OUTER JOIN promoter as p
                    ON pm.promoter_id = p.id
                WHERE pm.q < %.2f''' % fimoq

    result = cursor.execute(query)

    if result == 0:
        print 'No motifs in database...'
        sys.exit(1)

    allrows = cursor.fetchall()
    # create a dictionary with motifs as keys and arrays of genes as values
    # also, create a dictionary that maps motif ids to motif names
    mgdict = {}
    mnamedict = {}
    for gid, mid, mname in allrows:
        if mid not in mgdict:
            mgdict[mid] = []
            mnamedict[mid] = mname
        mgdict[mid].append(gid)

    # return the motif-genes dictionary, motif-name dictionary, and the gene ids
    return mgdict, mnamedict, genes

def motif_enrich(mgdict, genes, adj_p=True):
    # Number of genes with motifs mapped
    M = len(set(itertools.chain.from_iterable(mgdict.values())))

    enrich = []
    run_count = 0
    # For each motif...
    for mid in mgdict.iterkeys():
        m = set(mgdict[mid])
        
        a = len(m.intersection(genes))
        b = len(m - genes)
        c = len(genes - m)
        d = M - len(m.union(genes))

        if a < 2: continue
        run_count += 1

        fentry = np.array([[a, b], [c, d]])
        try:
            odds, p = fisher_exact(fentry)
        except ValueError:
            print '<h3>ValueError</h3>'
            print '<p>An error in the motif enrichment function has occured</p>'
            print '<p>Please <a href="mailto:niklas.mahler@umb.no">notify the administrator</a>.</p>'
            print M, len(m), len(genes), a, d
            sys.exit(1)
        
        #TODO: Only overrepresentation at the moment
        if b != 0 and d != 0 and float(a) / b > float(c) / d:
            enrich.append([mid, p, odds, fentry])
        elif b == 0 and d != 0:
            # All of the genes contain this motif
            enrich.append([mid, p, odds, fentry])

    # Correct p-values
    if adj_p:
        padj = padjust.fdr([x[1] for x in enrich])

        for i, q in zip(xrange(len(enrich)), padj):
            enrich[i][1] = q

    enrich = sorted(enrich, key=lambda x: x[1])

    return enrich

def print_html(enrich, mnamedict):
    print '''
    <a href="?p=doc#motifenrichment" target="_blank">Explanation</a>
    <table class="small datatable" id="enrichtable">
        <thead>
            <tr>
                <th>Motif</th>
                <th>q-value</th>
                <th>m &cap; G</th>
                <th>m - G</th>
                <th>G - m</th>
                <th>M - m &cup; G</th>
                <th></th>
            </tr>
        </thead>
        <tbody>'''
    for line in enrich:
        if line[1] > 0.3: break
        print '''
            <tr>
                <td><a href="?p=motif&id=%d">%s</a></td>
                <td>%.2e</td>
                <td>%d</td>
                <td>%d</td>
                <td>%d</td>
                <td>%d</td>
                <td><button class="nHighlight motifHighlight" 
                    data-name="%s" title="Highlight nodes in network">Highlight</button></td>
            </tr>''' % \
            (line[0], mnamedict[line[0]], line[1], 
                line[3][0][0], line[3][1][0], line[3][0][1], line[3][1][1],
                mnamedict[line[0]])
    print '''
        </tbody>
        <tfoot>
            <th>Motif</th>
            <th>q-value</th>
            <th>m &cap; G</th>
            <th>m - G</th>
            <th>G - m</th>
            <th>M - m &cup; G</th>
            <th></th>
        </tfoot>
    </table>
    <div class="clearfix"></div>'''

if __name__ == '__main__':
    try:
        gene_set = set([x.strip() for x in sys.argv[1].strip().split(',')])
    except IndexError:
        print 'No genes submitted...'
        sys.exit(1)

    operon_set = []
    gene_motifs = []
    db = MySQLdb.connect(config.DATABASE['host'],
                         config.DATABASE['user'],
                         config.DATABASE['pass'],
                         config.DATABASE['database'])
    cursor = db.cursor()

    # Extract the operons if there are any
    operon_set = [x for x in gene_set if x[-2:] == 'op']
    operon_array = ''.join(['(', ','.join(['"%s"' % x for x in operon_set]), ')'])

    mgdict, mnamedict, genes = query_database(gene_set, operon_set, cursor)

    enrich = motif_enrich(mgdict, genes)
    print_html(enrich, mnamedict)

    cursor.close()
    db.close()
