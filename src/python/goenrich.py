import numpy as np
from scipy import stats
import MySQLdb
import json
import argparse

import config
import padjust

def read_go():
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

        if underrep and odds < 1:
            goenrich.append([go, p, odds, fentry, v[1]])
        elif not underrep and odds > 1:
            goenrich.append([go, p, odds, fentry, v[1]])
    
    if adj_p:
        padj = padjust.fdr([x[1] for x in goenrich])

        for i, q in zip(xrange(len(goenrich)), padj):
            goenrich[i][1] = q

    return sorted(goenrich, key=lambda x: x[1])

def parse_args():
    parser = argparse.ArgumentParser()

    parser.add_argument('genes', metavar='gene',
        nargs='+', type=int, help='gene ids to test for enrichment')

    parser.add_argument('-p', dest='pth', metavar='dec',
        help='p-value threshold', type=float, default=0.05)

    args = parser.parse_args()

    return args

def main():
    args = parse_args()

    go_terms = read_go()
    enrichment = calc_enrichment(set(args.genes), *go_terms[:-1])

    results = []
    for go, p, odds, stats, category in enrichment:
        if p > args.pth:
            break
        results.append([go, category, go_terms[-1][go], '%d/%d:%d/%d' % \
            (stats[0,0], stats[0,0] + stats[1,0],
                stats[0,1], stats[0,1] + stats[1,1]), p])

    print json.dumps(results)

if __name__ == '__main__':
    main()
