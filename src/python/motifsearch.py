import argparse
import sys
from subprocess import Popen, PIPE
from time import time
import os
import fileinput
import json
import urllib2

import config
import meme_util

def run_tomtom(meme, outdir, central=True, th=0.05, minovlp=1, evalue=False):
	args = [
		config.tomtom,
		'-oc', outdir,
		'-min-overlap', str(minovlp),
		'-thresh', str(th),
		meme,
		config.central_motifs if central else config.all_motifs
	]

	if evalue:
		args.insert(1, '-evalue')

	p = Popen(args, shell=False, stdout=PIPE, stderr=PIPE)
	tt_stdout, tt_stderr = p.communicate()

def parse_iupac(iupac, outdir):
	p = Popen([
		config.iupac2meme,
		'-alpha', 'DNA',
		iupac
	], shell=False, stdout=PIPE)

	meme = p.communicate()[0]

	outfname = os.path.join(outdir, 'input.meme')

	with open(outfname, 'w') as f:
		f.write(meme)

	os.chmod(outfname, 0o664)

	return outfname

def parse_args():
	parser = argparse.ArgumentParser()

	parser.add_argument('outdir', help='output directory')
	parser.add_argument('motif', help='motif in plain text format')

	parser.add_argument('--type', help='input motif type',
		choices=('iupac', 'matrix'))
	parser.add_argument('--central', help='only search central motifs',
		action='store_true')

	parser.add_argument('--thresh', type=float, default=0.5,
		help='TOMTOM: significance threshold (default: 0.5)')
	parser.add_argument('--evalue', action='store_true',
		help='TOMTOM: use E-value threshold (default: q-value)')
	parser.add_argument('--min-overlap', type=int, default=1,
		help='minimum overlap between query and target (default: 1)')

	args = parser.parse_args()

	if args.motif == '-':
		args.motif = []

	return args

def main():
	start_time = time()
	args = parse_args()

	if not os.path.exists(args.outdir):
		os.mkdir(args.outdir)
	os.chmod(args.outdir, 0o775)

	print 'Converting motifs...'
	sys.stdout.write('Motif is ')
	if args.type == 'iupac':
		print 'IUPAC'
		meme = parse_iupac(args.motif, args.outdir)
	elif args.type == 'matrix':
		print 'matrix'
		matrix = [map(float, x.split()) for x in args.motif.splitlines()]
		meme = meme_util.create_meme(matrix, os.path.join(args.outdir, 'input'))
	print 'Running TOMTOM...'
	print 'Parameters:'
	print '  -thresh %.2f' % (args.thresh)
	if args.evalue:
		print '  -evalue'
	print '  -min-overlap %d' % (args.min_overlap)
	print 'Looking among %s motifs' % ('central' if args.central else 'all')
	run_tomtom(meme, args.outdir, args.central, args.thresh, args.min_overlap, args.evalue)
	print 'Done in %.2fs' % (time() - start_time)

if __name__ == '__main__':
	main()
