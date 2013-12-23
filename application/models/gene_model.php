<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gene_model extends CI_Model {

	function get_datatable() {
		$aoColumns = array('id', 'orf_id', 'refseq_id',
			'symbol', 'category', 'definition', 'tf',
			'operon_id');
		$taColumns = array('g.id', 'g.orf_id', 'g.refseq_id',
			'g.symbol', 'g.category', 'g.definition', 'g.tf',
			'og.operon_id');

		$this->db->select("SQL_CALC_FOUND_ROWS CONCAT('<input type=\"checkbox\" id=\"',g.id,'\" />') AS `checkbox`,
						   g.orf_id, g.refseq_id, g.symbol, g.category, g.definition, g.tf, og.operon_id", FALSE)
			->from('gene AS g')
			->join('operon_gene AS og', 'og.gene_id = g.id', 'left');

		// Filtering
		$sSearch = $this->input->post('sSearch');
		if ($sSearch && $sSearch != '') {
			for ($i = 0; $i < count($taColumns); $i++) {
				if ($i == 0) {
					$this->db->like($taColumns[$i], $sSearch);
				} else {
					$this->db->or_like($taColumns[$i], $sSearch);
				}
			}
		}

		// Ordering
		$sOrder = $this->input->post('iSortCol_0');
		if ($sOrder) {
			for ($i = 0; $i < intval($this->input->post('iSortingCols')); $i++) {
				if ($this->input->post('bSortable_' . intval($this->input->post('iSortCol_' . $i))) == 'true') {
					$this->db->order_by($taColumns[intval($this->input->post('iSortCol_'.$i))], 
						$this->input->post('sSortDir_'.$i));
				}
			}
		}

		// Pagination
		$sLimit = $this->input->post('iDisplayStart');
		$sLength = $this->input->post('iDisplayLength');
		if ($sLimit !== FALSE && $sLength != '-1') {
			$this->db->limit($sLength, $sLimit);
		}

		// Get the results
		$query = $this->db->get();
		$res = $query->result_array();

		// Get the counts
		$query = $this->db->select('FOUND_ROWS() as `found_rows`', FALSE)->get();
		$iFilteredTotal = $query->row_array()['found_rows'];
		$iTotal = $this->db->count_all_results('gene');

		$output = array(
			'sEcho' => intval($this->input->post('sEcho')),
			'iTotalRecords' => $iTotal,
			'iTotalDisplayRecords' => $iFilteredTotal,
			'aaData' => array()
		);
		foreach ($res as $row) {
			$output['aaData'][] = array_values($row);
		}
		return $output;
	}

	function get_all() {
		$this->db->select('id', 'orf_id', 'refseq_id', 'symbol', 'category', 'definition', 'tf')
			->from('gene');
		$query = $this->db->get();
		$genes = $query->result_array();
		return $genes;
	}
}