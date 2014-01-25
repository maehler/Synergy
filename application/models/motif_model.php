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
}
