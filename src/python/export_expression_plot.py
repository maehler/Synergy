import sys
import argparse
import json
import urllib2
import re
from matplotlib import pyplot as plt

def plot_data(data, format, output):
	rgb_pattern = re.compile('rgb\((\d+),(\d+),(\d+)\)')
	legend = []
	for series in data:
		legend.append(series['label'])
		color = tuple(int(x) / 255.0 for x in rgb_pattern.match(series['color']).groups())
		x = [xy[0] for xy in series['data']]
		y = [xy[1] for xy in series['data']]
		plt.plot(x, y, color=color, marker='.', markersize=5)

	plt.legend(legend, bbox_to_anchor=(0.5, -0.17), loc='lower center',
		columnspacing=1.0, labelspacing=0.0, ncol=10, fontsize=7)

	plt.subplots_adjust(bottom=0.15)

	plt.xlabel('Sample')
	plt.ylabel(r'log$_2$ expression')

	plt.savefig(output, format=format)

def parse_args():
	parser = argparse.ArgumentParser()

	# parser.add_argument('plot_data', help='URI encoded JSON plot data')

	parser.add_argument('--format', help='plot format', choices=('png', 'pdf'),
		default='png')
	parser.add_argument('-o', help='ouput to file')

	return parser.parse_args()

def main():
	args = parse_args()

	strdata = ''.join(sys.stdin)
	
	data = json.loads(urllib2.unquote(strdata.decode('utf-8')))

	plot_data(data, args.format, sys.stdout if args.o is None else args.o)

if __name__ == '__main__':
	main()
