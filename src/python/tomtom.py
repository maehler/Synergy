import argparse
from subprocess import Popen, PIPE
import urllib2
import json
from time import time
import os
import sys

import config
from meme_util import create_meme

class ThrowingArgumentParser(argparse.ArgumentParser):
    def _get_action_from_name(self, name):
        """Given a name, get the Action instance registered with this parser.
        If only it were made available in the ArgumentError object. It is 
        passed as it's first arg...
        """
        container = self._actions
        if name is None:
            return None
        for action in container:
            if '/'.join(action.option_strings) == name:
                return action
            elif action.metavar == name:
                return action
            elif action.dest == name:
                return action

    def error(self, message):
        exc = sys.exc_info()[1]
        if exc:
            exc.argument = self._get_action_from_name(exc.argument_name)
            raise exc
        super(ThrowingArgumentParser, self).error(message)

def run_tomtom(meme, outdir, db, th=10, evalue=True, minovlp=5):
	args = [
		config.tomtom,
		'-oc', outdir,
		'-png',
		'-no-ssc',
		'-min-overlap', str(minovlp),
		'-dist', 'pearson',
		'-thresh', str(th),
		meme, db
	]

	if evalue:
		args.insert(-2, '-evalue')

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
	parser = ThrowingArgumentParser()

	parser.add_argument('matrix', help='JSON encoded PSPM')
	parser.add_argument('outdir', help='output directory')

	parser.add_argument('--db', help='motif database to use (default: prodoric)',
		choices=('prodoric', 'regtransbase'), default='prodoric')
	parser.add_argument('--th', help='significance threshold (default: 10)',
		type=float, default=10)
	parser.add_argument('--evalue', action='store_true',
		help='use E-value threshold instead of q-value')
	parser.add_argument('--min-overlap', default=5, type=int,
		help='minimum overlap between query and target motif (default: 5)')

	args = parser.parse_args()

	if args.db == 'prodoric':
		args.db = config.prodoric
	elif args.db == 'regtransbase':
		args.db = config.regtransbase

	if args.min_overlap < 1:
		parser.exit(json.dumps({'name': 'The minimum overlap must be larger than 0.'}))
	if args.th < 0:
		parser.exit(json.dumps({'name': 'The significance threshold cannot be'
			' smaller than 0.'}))
	if not args.evalue and args.th > 1:
		parser.exit(json.dumps({'name': 'If q-value is chosen, the threshold must'
			' be in the range 0 to 1.'}))

	return args

def main():
	try:
		args = parse_args()
	except argparse.ArgumentError as ae:
		print json.dumps({'name': ae.message.capitalize(), 'file': None})
		sys.exit(1)

	pspm = json.loads(urllib2.unquote(args.matrix))

	if not os.path.exists(args.outdir):
		os.mkdir(args.outdir)
	os.chmod(args.outdir, 0o775)

	meme_fname = create_meme(pspm, os.path.join(args.outdir, 'input'))

	exit_status = run_tomtom(meme_fname, args.outdir, 
		args.db, args.th, args.evalue, args.min_overlap)

	if exit_status == 0:
		print json.dumps({'name': 'TOMTOM results',
			'file': os.path.relpath(os.path.join(args.outdir, 'tomtom.html'), config.base_path)})
	else:
		print json.dumps({'name': ('An error occured (ERROR %d)'
			'. Please report this error with the following code: <code>%s</code>') % \
			(exit_status, os.path.basename(args.outdir)), 'file': None})

if __name__ == '__main__':
	main()
