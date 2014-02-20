import argparse
from subprocess import Popen, PIPE
import urllib2
import json
from time import time
import os

import config
from meme_util import create_meme

def run_tomtom(meme, outdir, db):
	args = [
		config.tomtom,
		'-oc', outdir,
		'-png',
		'-no-ssc',
		'-mi', '1',
		'-min-overlap', '5',
		'-dist', 'pearson',
		'-evalue',
		'-thresh', '10',
		meme, db
	]

	p = Popen(args, shell=False, stdout=PIPE, stderr=PIPE)

	p_stdout, p_stderr = p.communicate()

	with open(os.path.join(outdir, 'tomtom.err'), 'w') as err:
		with open(os.path.join(outdir, 'tomtom.out'), 'w') as out:
			err.write(p_stderr)
			out.write(p_stdout)

	for root, dirs, files in os.walk(outdir, topdown=False):
		for d in dirs:
			os.chmod(os.path.join(root, d), 0o775)
		for f in files:
			os.chmod(os.path.join(root, f), 0o664)

	return p.returncode

def parse_args():
	parser = argparse.ArgumentParser()

	parser.add_argument('matrix', help='JSON encoded PSPM')
	parser.add_argument('outdir', help='output directory')

	parser.add_argument('--db', help='motif database to use (default: prodoric)',
		choices=('prodoric', 'regtransbase'), default='prodoric')

	args = parser.parse_args()

	if args.db == 'prodoric':
		args.db = config.prodoric
	elif args.db == 'regtransbase':
		args.db = config.regtransbase

	return args

def main():
	args = parse_args()

	pspm = json.loads(urllib2.unquote(args.matrix))

	if not os.path.exists(args.outdir):
		os.mkdir(args.outdir)
	os.chmod(args.outdir, 0o775)

	meme_fname = create_meme(pspm, os.path.join(args.outdir, 'input'))

	exit_status = run_tomtom(meme_fname, args.outdir, args.db)

	if exit_status == 0:
		print json.dumps({'name': 'TOMTOM results',
			'file': os.path.relpath(os.path.join(args.outdir, 'tomtom.html'), config.base_path)})
	else:
		print json.dumps({'name': ('An error occured (ERROR %d)'
			'. Please report this error with the following code: <code>%s</code>') % \
			(exit_status, os.path.basename(args.outdir)), 'file': None})

if __name__ == '__main__':
	main()
