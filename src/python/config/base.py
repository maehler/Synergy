#!/usr/bin/python26

# Constants specific to the site

import os

_base_dir = os.path.join(os.path.abspath(os.path.dirname(os.path.dirname(os.path.dirname(os.path.dirname(__file__))))))
_meme_bin = os.path.join('/usr/local/Cellar/meme/4.9.0-p4/bin/')
_db_dir = os.path.join(_base_dir, 'data/motif_db/')
_blast_db_dir = os.path.join(_base_dir, 'data/blastdb/')
_fasta_dir = os.path.join(_base_dir, 'data/fasta/')
site_tmp = 'data/tmp'
tmp_dir = os.path.join(_base_dir, site_tmp)

# Database definition. Should be set in a local.py.
DATABASE = {
    'user': '',
    'host': '',
    'pass': '',
    'database': ''
}

python = os.path.join(_base_dir, 'python/')

# Motif databases
prodoric = os.path.join(_db_dir, 'prodoric.meme')
regtransbase = os.path.join(_db_dir, 'regtransbase.meme')
prodoric_regtransbase = os.path.join(_db_dir, 'prodoric_rtb.meme')
all_motifs = os.path.join(_db_dir, 'all_motifs_meme.meme')

# GO definitions
# Old definitions without inferred inheritence
#go_def = os.path.join(python, 'syn_goterm.txt')
# New definition with inheritence
go_def = os.path.join(_base_dir, 'data/goterm_tab_inherited.txt')

# MEME programs
meme = os.path.join(_meme_bin, 'meme')
tomtom = os.path.join(_meme_bin, 'tomtom')
iupac2meme = os.path.join(_meme_bin, 'iupac2meme')
meme2images = os.path.join(_meme_bin, 'meme2images')

# Perl libraries
perl_lib = os.path.join(_base_dir, 'meme/lib/perl/')

# BLAST programs
blast = '/usr/bin/blastall'

# Sequence files
prodoric_regtransbase_fasta = os.path.join(_db_dir,
    'prodoric_rtb.faa')
prodoric_fasta = os.path.join(_fasta_dir, 
    'prodoric.faa')
regtransbase_fasta = os.path.join(_fasta_dir, 
    'regtransbase.faa')
syn_prot_fasta = os.path.join(_fasta_dir, 'syn_all.faa')

# BLAST databases
blast_syn = os.path.join(_blast_db_dir,
    'Synechocystis_PCC_6803_uid57659_proteome.faa')
blast_prodoric = os.path.join(_blast_db_dir,
    'prodoric.prot')
blast_regtransbase = os.path.join(_blast_db_dir,
    'regtransbase.prot')
blast_prodoric_regtransbase = os.path.join(_blast_db_dir,
    'prodoric_rtb.prot')
