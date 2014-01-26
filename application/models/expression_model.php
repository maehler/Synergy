<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Expression_model extends CI_Model {

	function get_flot_expression($gene_id) {
		$this->db->select('sg.exp, s.name, s.descr')
			->from('gene AS g')
			->join('sample_gene AS sg', 'sg.gene_id = g.id', 'left')
			->join('sample AS s', 's.id = sg.sample_id', 'left')
			->where('g.id', $gene_id)
			->order_by('s.id');

		$query = $this->db->get();
		$res = $query->result_array();

		$annot = array();
		$data = array();
		for ($i = 0; $i < count($res); $i++) {
			$annot[] = array($res[$i]['name'], $res[$i]['descr']);
			$data[] = array($i, floatval($res[$i]['exp']));
		}

		return array('data' => $data, 'annotation' => $annot);
	}
}
