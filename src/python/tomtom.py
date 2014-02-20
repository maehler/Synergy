import argparse
from subprocess import Popen, PIPE
import urllib2
import json
from time import time
import os

import config
from meme_util import create_meme

def run_tomtom(meme, outdir):
	args = [
		config.tomtom,
		'-oc', outdir,
		'-png',
		meme, config.prodoric_regtransbase
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

	return parser.parse_args()

def main():
	args = parse_args()

	pspm = json.loads(urllib2.unquote(args.matrix))

	if not os.path.exists(args.outdir):
		os.mkdir(args.outdir)
	os.chmod(args.outdir, 0o775)

	meme_fname = create_meme(pspm, os.path.join(args.outdir, 'input'))

	exit_status = run_tomtom(meme_fname, args.outdir)

	if exit_status == 0:
		print json.dumps({'name': 'TOMTOM results',
			'file': os.path.relpath(os.path.join(args.outdir, 'tomtom.html'), config.base_path)})
	else:
		print json.dumps({'name': 'An error occured (ERROR %d)' % exit_status, 'file': None})

if __name__ == '__main__':
	main()
