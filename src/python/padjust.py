#!/bin/env python

def fdr(p):
    tmp = sorted(enumerate(p), key=lambda x: x[1], reverse=True)
    psort = [x[1] for x in tmp]
    o = [x[0] for x in tmp] # Original ordering
    ro = [i[0] for i in sorted(enumerate(o), key=lambda x: x[1])]

    n = len(p)
    pos = range(1, n + 1)[::-1]

    padj = cummin([min(1, (float(n) / float(i)) * p) for p, i in zip(psort, pos)])

    return [i[0] for i in sorted(zip(padj, o), key=lambda x: x[1])]

def cummin(seq):
    y = []
    for x in seq:
        y.append(min(y[-1] if len(y) > 0 else float('inf'), x))

    return y

if __name__ == '__main__':
    p = [0.05, 0.003, 0.0001, 0.05, 0.001, 0.0001, 0.02, 0.03]
    print ' '.join('%.2e' % x for x in p)
    
    padj = fdr(p)
    print ' '.join('%.2e' % x for x in padj)

    cummin([7,2,5,1,3,1,4,5])
