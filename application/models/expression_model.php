<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Expression_model extends CI_Model {

	function get_multi_flot_expression($gene_ids) {
		$this->db->select('sg.exp, s.name, s.descr, e.title, g.orf_id')
			->from('sample_gene AS sg')
			->join('gene AS g', 'g.id = sg.gene_id', 'left')
			->join('sample AS s', 's.id = sg.sample_id', 'left')
			->join('experiment_sample AS es', 'es.sample_id = s.id', 'left')
			->join('experiment AS e', 'e.id = es.experiment_id')
			->where_in('g.id', $gene_ids)
			->order_by('g.id, s.id');

		$query = $this->db->get();
		$res = $query->result_array();

		$annot = array();
		$series = array();

		$sample_index = 0;
		$first_sample = $res[0]['name'];
		for ($i = 0; $i < count($res); $i++) {
			if ($res[$i]['name'] == $first_sample) {
				$sample_index = 0;
				$series[$res[$i]['orf_id']] = array('label' => $res[$i]['orf_id'], 'data' => array());
			}
			$annot[$sample_index] = array($res[$i]['title'], $res[$i]['name'], $res[$i]['descr']);

			if ($res[$i]['exp'] !== NULL) {
				$series[$res[$i]['orf_id']]['data'][$sample_index] = array($sample_index, floatval($res[$i]['exp']));
			} else {
				$series[$res[$i]['orf_id']]['data'][$sample_index] = array($sample_index, NULL);
			}

			$sample_index++;
		}

		return array('annot' => $annot, 'data' => array_values($series));
	}
}
