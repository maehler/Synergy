import argparse
import MySQLdb

import config

def get_gene_info(ids):
	db = MySQLdb.connect(
		config.DATABASE['host'],
		config.DATABASE['user'],
		config.DATABASE['pass'],
		config.DATABASE['database']
	)
	c = db.cursor()

	query = '''
		SELECT orf_id, symbol, category, definition
		FROM gene
		WHERE id IN (%s)
	''' % (', '.join(map(lambda x: '%s', ids)))

	c.execute(query, ids)
	res = c.fetchall()

	c.close()
	db.close()

	return res

def parse_args():
	parser = argparse.ArgumentParser()

	parser.add_argument('geneid', nargs='+', help='genes to export', type=int)

	args = parser.parse_args()

	return args

def main():
	args = parse_args()

	for row in get_gene_info(args.geneid):
		print '\t'.join(row)

if __name__ == '__main__':
	main()