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
