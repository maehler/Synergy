import sys
import argparse
import networkx as nx
import json
import urllib2
from matplotlib import pyplot as plt
from matplotlib.colors import ColorConverter
from collections import defaultdict

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
					'type': 'rectangle' if 'tf' in n['classes'].split() else 'circle',
				}
			}
		)
	if 'edges' in d:
		for e in d['edges']:
			G.add_edge(e['data']['source'], e['data']['target'],
				{
					'graphics': {
						'width': int(e['data']['weight'])
					}
				}
			)

	return G

def get_positions(G):
	ids = []
	x = []
	y = []
	for n in G.nodes(data=True):
		ids.append(n[0])
		x.append(n[1]['graphics']['x'])
		y.append(n[1]['graphics']['y'])
	ymax = max(y)
	ry = [abs(yi - ymax) for yi in y]
	return {nid: (xi, yi) for nid, xi, yi in zip(ids, x, ry)}

def get_edge_widths(G):
	w = []
	for e in G.edges(data=True):
		w.append(e[2]['graphics']['width'])
	wmax = max(w)
	wmin = min(w)
	return [x * (2 - 0.1) / (wmax - wmin) for x in w]

def get_node_types(G):
	types = defaultdict(list)
	for n in G.nodes(data=True):
		if n[1]['graphics']['type'] == 'rectangle':
			types['s'].append(n[0])
		else:
			types['o'].append(n[0])
	return types

def parse_args():
	parser = argparse.ArgumentParser()

	parser.add_argument('json', help='JSON string of the network')
	parser.add_argument('--type', help='filetype of output',
		choices=('gml', 'png', 'pdf'), default='gml')

	args = parser.parse_args()
	return args

def main():
	args = parse_args()

	network = json.loads(urllib2.unquote(args.json.encode('utf8')))

	G = as_network(network)

	if args.type == 'gml':
		nx.write_gml(G, sys.stdout)
	elif args.type == 'png' or args.type == 'pdf':
		ax = plt.axes(frameon=False)
		ax.get_yaxis().set_visible(False)
		ax.get_xaxis().set_visible(False)
		nodepos = get_positions(G)
		if 'edges' in network:
			nx.draw_networkx_edges(G,
				pos=nodepos,
				edge_color='0.6',
				width=get_edge_widths(G))
		for shape, nodes in get_node_types(G).iteritems():
			nx.draw_networkx_nodes(G,
				pos=nodepos,
				nodelist=nodes,
				node_color='#219D1A',
				ax=ax,
				linewidths=0.5,
				node_size=100,
				node_shape=shape)
		nx.draw_networkx_labels(G,
			pos={n: (x, y + 17) for n, (x, y) in nodepos.iteritems()},
			labels=nx.get_node_attributes(G, 'label'),
			font_size=6)
		bbox = None if G.number_of_nodes() == 1 else 'tight'
		plt.savefig(sys.stdout, dpi=300, bbox_inches=bbox, format=args.type)

if __name__ == '__main__':
	main()
