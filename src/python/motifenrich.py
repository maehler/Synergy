import sys
import MySQLdb
import numpy as np
from scipy.stats import fisher_exact
import itertools
import argparse
import json

import padjust
import config

def query_database(gene_set, cursor, central=False, getall=False, fimoq=0.125):
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
        ON pm.motif_id = m.id
    WHERE pm.q < %.3f''' % (fimoq)
    if not getall:
        query += ' AND g.id IN %s' % (gene_array)
    if central:
        query += ' AND m.central = 1'

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
                    m.name,
                    m.central
                FROM promoter_motif AS pm
                LEFT OUTER JOIN motif AS m
                    ON pm.motif_id = m.id
                LEFT OUTER JOIN promoter as p
                    ON pm.promoter_id = p.id
                WHERE pm.q < %.3f''' % (fimoq)
    if central:
        query += ' AND m.central = 1'

    result = cursor.execute(query)

    if result == 0:
        print 'No motifs in database...'
        sys.exit(1)

    allrows = cursor.fetchall()
    # create a dictionary with motifs as keys and arrays of genes as values
    # also, create a dictionary that maps motif ids to motif names
    mgdict = {}
    mnamedict = {}
    for gid, mid, mname, is_central in allrows:
        if mid not in mgdict:
            mgdict[mid] = []
            mnamedict[mid] = [mname, is_central]
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
        if odds > 1:
            enrich.append([mid, p, odds, fentry])

    # Adjust p-values
    if adj_p:
        padj = padjust.fdr([x[1] for x in enrich])

        for i, q in zip(xrange(len(enrich)), padj):
            enrich[i][1] = q

    enrich = sorted(enrich, key=lambda x: x[1])

    return enrich

def parse_args():
    parser = argparse.ArgumentParser()

    parser.add_argument('genes', metavar='gene',
        nargs='+', type=int, help='gene ids to test for enrichment')

    parser.add_argument('--central', help='only use central motifs',
        action='store_true')
    parser.add_argument('-p', dest='pth', metavar='dec',
        help='p-value threshold', type=float, default=0.05)
    parser.add_argument('--fimoq', dest='fimoq', metavar='dec',
        help='FIMO q-value threshold', type=float, default=0.125)

    args = parser.parse_args()

    return args

def main():
    args = parse_args()

    db = MySQLdb.connect(config.DATABASE['host'],
                     config.DATABASE['user'],
                     config.DATABASE['pass'],
                     config.DATABASE['database'])
    cursor = db.cursor()

    mgdict, mnamedict, genes = query_database(set(args.genes), 
        cursor, central=args.central, fimoq=args.fimoq)

    enrich = motif_enrich(mgdict, genes)

    cursor.close()
    db.close()

    result = []
    for mid, p, odds, stats in enrich:
        if p > args.pth:
            break
        result.append([mnamedict[mid][0], 10, '&#x2713;' if mnamedict[mid][1] else '',
            '%d/%d:%d/%d' % (stats[0,0], stats[0,0] + stats[1,0],
                stats[0,1], stats[0,1] + stats[1,1]), p])

    print json.dumps(result)

if __name__ == '__main__':
    main()
