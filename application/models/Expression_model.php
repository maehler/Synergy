<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Expression_model extends CI_Model {

	function get_multi_flot_expression($gene_ids) {
		// Get annotation
		$this->db->select('s.name AS name, s.descr AS descr, e.title AS title')
			->from('experiment_sample AS es')
			->join('sample AS s', 's.id = es.sample_id', 'left')
			->join('experiment AS e', 'e.id = es.experiment_id', 'left')
			->order_by('s.id');

		$annot_query = $this->db->get();
		$annot = $annot_query->result_array();

		// Get expression
		$this->db->select('sg.exp, g.orf_id')
			->from('sample_gene AS sg')
			->join('gene AS g', 'g.id = sg.gene_id', 'left')
			->where_in('g.id', $gene_ids)
			->order_by('g.id, sg.sample_id');

		$exp_query = $this->db->get();

		$series = array();

		$sample_index = 0;
		$previous_gene = '';
		while ($row = $exp_query->unbuffered_row()) {
			if ($row->orf_id !== $previous_gene) {
				$sample_index = 0;
				$series[$row->orf_id] = array('label' => $row->orf_id, 'data' => array());
			}

			if ($row->exp !== NULL) {
				$series[$row->orf_id]['data'][$sample_index] = array($sample_index, floatval($row->exp));
			} else {
				$series[$row->orf_id]['data'][$sample_index] = array($sample_index, NULL);
			}

			$previous_gene = $row->orf_id;
			$sample_index++;
		}

		return array('annot' => $annot, 'data' => array_values($series));
	}
}
