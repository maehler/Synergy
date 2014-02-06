import argparse
from subprocess import Popen, PIPE
import urllib2
import json
from time import time
import os

import config

def create_meme(pspm, f_prefix):
	meme_header = [
		'MEME version 4.4','',
		'ALPHABET= ACGT','',
		'strands: + -','',
		'Background letter frequencies (from uniform background):',
		'A 0.25000 C 0.25000 G 0.25000 T 0.25000','',
		'MOTIF 1','',
	]

	width = len(pspm)
	meme_header.append('letter-probability matrix: alength= 4 w= %d E= 0' % (width))

	outmeme = os.path.join(config.tmp_dir, '%s.motif.meme' % (f_prefix))

	str_matrix = '\n'.join(' '.join(map(str, row)) for row in pspm)

	with open(outmeme, 'w') as f:
		f.write('\n'.join(meme_header) + '\n' + str_matrix)
	os.chmod(outmeme, 0o664)

	return outmeme

def run_tomtom(meme, out_dir):
	args = [
		config.tomtom,
		'-oc', out_dir,
		'-png',
		meme, config.prodoric_regtransbase
	]

	p = Popen(args, shell=False, stdout=PIPE, stderr=PIPE)

	p_stdout, p_stderr = p.communicate()

	if not os.path.exists(out_dir):
		os.mkdir(out_dir)
	os.chmod(out_dir, 0o775)

	with open(os.path.join(out_dir, 'tomtom.err'), 'w') as err:
		with open(os.path.join(out_dir, 'tomtom.out'), 'w') as out:
			err.write(p_stderr)
			out.write(p_stdout)

	for root, dirs, files in os.walk(out_dir, topdown=False):
		for d in dirs:
			os.chmod(os.path.join(root, d), 0o775)
		for f in files:
			os.chmod(os.path.join(root, f), 0o664)

	return p.returncode

def parse_args():
	parser = argparse.ArgumentParser()

	parser.add_argument('matrix', help='JSON encoded PSPM')
	parser.add_argument('unique_id', help='a unique identifier for saving tmp files')

	return parser.parse_args()

def main():
	args = parse_args()

	pspm = json.loads(urllib2.unquote(args.matrix))

	f_prefix = '%s_%d' % (args.unique_id, int(time()))

	meme_fname = create_meme(pspm, f_prefix)

	out_dir = os.path.join(config.tmp_dir, '%s.tomtom' % (f_prefix))
	exit_status = run_tomtom(meme_fname, out_dir)

	if exit_status == 0:
		print json.dumps({'name': 'TOMTOM results', 'file': os.path.join(config.site_tmp, '%s.tomtom' % (f_prefix), 'tomtom.html')})
	else:
		print json.dumps({'name': 'An error occured (status: %d)' % exit_status, 'file': None})

if __name__ == '__main__':
	main()
