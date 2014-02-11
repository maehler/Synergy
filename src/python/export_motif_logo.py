import sys
import argparse
import urllib2
import json
from time import time
import os
from subprocess import Popen, PIPE

import config
from meme_util import create_meme

def run_meme2images(meme, format, out_dir):
	args = [
		config.meme2images,
		'-%s' % (format),
		meme, out_dir
	]

	p = Popen(args, shell=False)
	p.communicate()

	if p.returncode == 0:
		return os.path.join(out_dir, 'logo1.%s' % format)
	else:
		return False

def parse_args():
	parser = argparse.ArgumentParser()

	parser.add_argument('--format', help='output format',
		choices=('png', 'eps'), default='png')
	parser.add_argument('--id', help='unique id', default='')

	return parser.parse_args()

def main():
	args = parse_args()

	pspm = json.loads(urllib2.unquote(''.join(sys.stdin).decode('utf-8')))

	meme = create_meme(pspm, '%s_%d' % (args.id, time()))

	out_dir = os.path.join(config.tmp_dir, '%s_%d' % (args.id, time()))
	if not os.path.exists(out_dir):
		os.mkdir(out_dir)
	os.chmod(out_dir, 0o775)

	with open(run_meme2images(meme, args.format, out_dir)) as f:
		print f.read()

if __name__ == '__main__':
	main()
