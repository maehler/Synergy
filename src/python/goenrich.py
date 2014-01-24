import sys
#print sys.path
import time
import numpy as np
from scipy import stats
import MySQLdb
import json
import argparse

import config
import padjust

def read_go():
    d = {}
    set_p = []
    set_m = []
    set_c = []
    descr = {}
    with open(config.go_def) as f:
        for line in f:
            line = line.strip().split('\t')
            
            if line[0].startswith('0'):
                return read_old_go()

            gset = set(x.lower() for x in line[3:])
            if len(gset) == 0: continue
            
            go = line[0]
            category = line[1]
            description = line[2]

            d[go] = (gset, category)
            descr[go] = description

            if category.lower() == 'p':
                set_p += [x for x in gset]
            elif category.lower() == 'm':
                set_m += [x for x in gset]
            elif category.lower() == 'c':
                set_c += [x for x in gset]
    return (d, set(set_p), set(set_m), set(set_c), descr)

def read_db_go():
    go_g = {}
    set_p = set()
    set_m = set()
    set_c = set()
    descr = {}

    db = MySQLdb.connect(config.DATABASE['host'], 
                         config.DATABASE['user'], 
                         config.DATABASE['pass'], 
                         config.DATABASE['database'])

    c = db.cursor()

    query = '''
    SELECT id, go, category, description
    FROM go
    '''

    query = '''
    SELECT g.id AS gid, go.go, go.category, go.description
    FROM go_gene
    LEFT OUTER JOIN gene AS g ON g.id = go_gene.gene_id
    LEFT OUTER JOIN go ON go.id = go_gene.go_id
    '''

    c.execute(query)
    db_go = c.fetchall()

    for gene_go in db_go:
        gid, go, category, description = gene_go
        if go not in go_g:
            go_g[go] = [set([gid]), category]
        else:
            go_g[go][0].add(gid)

        descr[go] = description

        if category.lower() == 'biological process':
            set_p.add(gid)
        elif category.lower() == 'molecular function':
            set_m.add(gid)
        elif category.lower() == 'cellular component':
            set_c.add(gid)

    c.close()
    db.close()

    return go_g, set_p, set_m, set_c, descr

def calc_enrichment(genes, go, set_p, set_m, set_c, underrep=False, adj_p=True):
    goenrich = []
    np.seterr(all='ignore')
    run_count = 0
    for go, v in go.iteritems():
        GOS = set()

        if v[1].lower() == 'biological process': GOS = set_p
        elif v[1].lower() == 'molecular function': GOS = set_m
        elif v[1].lower() == 'cellular component': GOS = set_c

        # The set of genes annotated to the specific GO term
        X = v[0]
        # The GO-annotated part of the gene set for which we want enrichment
        Sgo = genes & GOS

        a = len(Sgo & X) # # of our genes in this term
        b = len(Sgo - X) # # of our genes NOT in this term
        c = len(X - Sgo) # # of genes in the annotated set NOT part of our genes
        # of genes in GO category not in our genes or in the specific category
        d = len(GOS - X - Sgo)

        if a < 2: continue
        run_count += 1

        fentry = np.array([[a, c], [b, d]], dtype='float64')

        odds, p = stats.fisher_exact(fentry)

        if underrep:
            if b != 0 and d != 0 and float(a) / b < float(c) / d:
                goenrich.append([go, p, odds, fentry, v[1]])
            if b == 0 and d != 0:
                goenrich.append([go, p, odds, fentry, v[1]])
        else:
            if b != 0 and d != 0 and float(a) / b > float(c) / d:
                goenrich.append([go, p, odds, fentry, v[1]])
    
    if adj_p:
        padj = padjust.fdr([x[1] for x in goenrich])

        for i, q in zip(xrange(len(goenrich)), padj):
            goenrich[i][1] = q

    return sorted(goenrich, key=lambda x: x[1])

def parse_args():
    parser = argparse.ArgumentParser()

    parser.add_argument('pth', help='p-value threshold', type=float)
    parser.add_argument('genes', metavar='gene',
        nargs='+', type=int, help='gene ids to test for enrichment')

    args = parser.parse_args()

    return args

def main():
    args = parse_args()

    go_terms = read_db_go()
    enrichment = calc_enrichment(set(args.genes), *go_terms[:-1])

    # print set(map(int, args.genes))

    # if len(enrichment) == 0:
    #     print 'found nothing'
    #     json.dumps('no results')
    # else:
    #     print 'found something'
    #     json.dumps(enrichment)

    results = []
    for go, p, odds, stats, category in enrichment:
        if p > args.pth:
            break
        results.append([go, category, go_terms[-1][go], p])

    # print results

    print json.dumps(results)

if __name__ == '__main__':
    main()

# if __name__ == '__main__':
#     try:
#         genes = set([x.strip() for x in sys.argv[1].strip().split(',')])
#     except IndexError:
#         print 'No genes selected'
#         sys.exit(1)

#     operons = [x for x in genes if x[-2:] == 'op']
#     if len(operons) > 0:
#         db = MySQLdb.connect(config.DATABASE['host'],
#                              config.DATABASE['user'],
#                              config.DATABASE['pass'],
#                              config.DATABASE['database'])
#         cursor = db.cursor()

#         operon_array = ''.join(['(', ','.join(['"%s"' % x for x in operons]), ')'])

#         query = '''
            
#             SELECT
#                 g.orf_id
#             FROM gene AS g
#             LEFT OUTER JOIN operon_gene AS og
#                 ON og.gene_id = g.id
#             LEFT OUTER JOIN operon AS o
#                 ON o.id = og.operon_id
#             WHERE o.name IN %s''' % operon_array

#         result = cursor.execute(query)
#         allrows = cursor.fetchall()
#         for row in allrows:
#             genes.add(row[0])

#         cursor.close()
#         db.close()

#     go_terms = read_go()
#     enrichment = calc_enrichment(genes, *go_terms[:-1])

#     if len(enrichment) == 0:
#         print 'No results found.'
#         sys.exit(0)

#     print '''
#     <p><a href="?p=doc#goenrichment" target="_blank">Explanation</a></p>
#     <table class="small datatable" id="enrichtable">
#         <thead>
#             <tr>
#                 <th>GO</th>
#                 <th>q-value</th>
#                 <th>Description</th>
#                 <th>S &cap; GO</th>
#                 <th>GO - S</th>
#                 <th>S &cap; A - GO</th>
#                 <th>A - S &cup; GO</th>
#                 <th></th>
#             </tr>
#         </thead>
#         <tbody>
#     '''
#     for line in enrichment:
#         if line[1] > 0.3: break
#         print '''
#             <tr>
#                 <td><a
#                 class="external" href="http://amigo.geneontology.org/cgi-bin/amigo/term_details?term=%s" target="_blank">%s</a></td>
#                 <td>%.2e</td>
#                 <td>%s</td>
#                 <td>%d</td>
#                 <td>%d</td>
#                 <td>%d</td>
#                 <td>%d</td>
#                 <td><button class="nHighlight goHighlight" data-name="%s" title="Highlight nodes in network">Highlight</button></td>
#             </tr>
#         ''' % (line[0], line[0], line[1], go_terms[-1][line[0]],
#                 line[3][0][0], line[3][1][0], line[3][0][1], line[3][1][1],
#                 line[0])
# #    print '''
# #        <p>
# #            <span class="tooltip">
# #                %s:
# #                <span class="box small">
# #                    %s
# #                </span>
# #            </span>p=%.2e, %d, %d, %d, %d, category: %s
# #        </p>''' % (line[0], go_terms[-1][line[0]], line[1],
# #                   line[3][0][0], line[3][1][0], line[3][0][1], line[3][1][1],
# #                   line[-1])
#     print '''
#         </tbody>
#         <tfoot>
#             <th>GO</th>
#             <th>q-value</th>
#             <th>Description</th>
#             <th>S &cap; GO</th>
#             <th>GO - S</th>
#             <th>S &cap; A - GO</th>
#             <th>A - S &cup; GO</th>
#             <th></th>
#         </tfoot>
#     </table>
#     '''
