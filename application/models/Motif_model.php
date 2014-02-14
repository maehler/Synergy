<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Motif_model extends CI_Model {

	function get_gene_motifs($gene_id) {
		$this->db->select('pm.motif_id, m.name, pm.q, pm.startpos, pm.stoppos, pm.score')
			->from('promoter_motif AS pm')
			->join('promoter AS p', 'p.id = pm.promoter_id', 'left')
			->join('motif AS m', 'm.id = pm.motif_id', 'left')
			->where('p.gene_id', $gene_id);

		$query = $this->db->get();
		$res = $query->result_array();

		return $res;
	}

	function get($motif) {
		$this->db->select('id, name AS motif_name, length, pspm, regex, central')
			->from('motif')
			->where('name', $motif)
			->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() === 0) {
			return FALSE;
		}

		$res = $query->row_array();

		$res['pspm'] = $this->format_matrix($res['pspm']);

		return $res;
	}

	function get_motif_genes($motif) {
		$this->db->select('g.id, g.orf_id, pm.startpos, pm.stoppos, pm.score, pm.q')
			->from('promoter_motif AS pm')
			->join('motif AS m', 'm.id = pm.motif_id', 'left')
			->join('promoter AS p', 'p.id = pm.promoter_id')
			->join('gene AS g', 'g.id = p.gene_id')
			->where('m.name', $motif);

		$query = $this->db->get();
		$res = $query->result_array();

		return $res;
	}

	private function format_matrix($matstring) {
		$array_matrix = array();
		$rows = array();
		$tok = strtok($matstring, ';');
		while ($tok !== FALSE) {
			$rows[] = $tok;
			$tok = strtok(';');
		}
		foreach($rows as $k => $v) {
			$array_matrix[$k] = array();
			$tok = strtok($v, ',');
			while($tok !== FALSE) {
				$array_matrix[$k][] = floatval($tok);
				$tok = strtok(',');
			} 
		}
		return $array_matrix;
	}
}
