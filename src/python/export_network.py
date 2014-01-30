import sys
import argparse
import networkx as nx
import json
import urllib2

def as_network(d):
	G = nx.Graph()
	for n in d['nodes']:
		G.add_node(n['data']['id'], 
			{
				'name': n['data']['orf'],
				'label': n['data']['orf'],
				'graphics': {
					'x': n['position']['x'],
					'y': n['position']['y'],
					'type': 'rectangle' if 'tf' in n['classes'] else 'circle',
				}
			}
		)
	for e in d['edges']:
		G.add_edge(e['data']['source'], e['data']['target'],
			{
				'graphics': {
					'width': int(e['data']['weight'])
				}
			}
		)

	return G

def parse_args():
	parser = argparse.ArgumentParser()

	parser.add_argument('json', help='JSON string of the network')
	parser.add_argument('--type', help='filetype of output',
		choices=('gml'), default='gml')

	args = parser.parse_args()
	return args

def main():
	args = parse_args()

	network = json.loads(urllib2.unquote(args.json.encode('utf8')))

	G = as_network(network)

	nx.write_gml(G, sys.stdout)

if __name__ == '__main__':
	main()