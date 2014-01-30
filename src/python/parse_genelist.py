import argparse
import MySQLdb
import json

import config

def get_ids(genes):
	db = MySQLdb.connect(
		config.DATABASE['host'],
		config.DATABASE['user'],
		config.DATABASE['pass'],
		config.DATABASE['database']
	)

	c = db.cursor()

	# query = '''
	# 	SELECT `id` FROM `gene`
	# 	WHERE `orf_id` IN (%s) OR
	# 		`refseq_id` IN (%s)
	# '''

	# query = query % (','.join(map(lambda x: '%s', genes)), ','.join(map(lambda x: '%s', genes)))

	# c.execute(query, genes + genes)

	# res = [x[0] for x in c.fetchall()]

	res = {
		'success': [],
		'fail': []
	}

	query = '''
		SELECT id FROM gene
		WHERE orf_id LIKE %s OR
			  refseq_id LIKE %s
		LIMIT 1
	'''

	for g in genes:
		n = c.execute(query, (g, g))

		if n == 0:
			res['fail'].append(g)
		else:
			res['success'].append(c.fetchone()[0])
	
	c.close()
	db.close()

	return res

def parse_file(fname):
	genes = []
	with open(fname) as f:
		for line in f:
			g = line.strip().split()
			if len(g) > 0 and len(g[0]) > 0:
				genes.append(g[0])
	return genes

def parse_args():
	parser = argparse.ArgumentParser()

	parser.add_argument('file', help='path of file to parse')

	args = parser.parse_args()

	return args

def main():
	args = parse_args()

	genes = parse_file(args.file)

	gids = get_ids(genes);

	print json.dumps(gids)


if __name__ == '__main__':
	main()
