<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Genelist_model extends CI_Model {

	function get_datatable($view) {
		if ($view == 'golist_view') {
			$aoColumns = array('name', 'description', 'count', 'gobelowalpha', 
				'mingop', 'motifbelowalpha', 'minmotifp');
			$taColumns = $aoColumns;
		} else {
			$aoColumns = array('name', 'count', 'gobelowalpha', 'mingop', 
				'motifbelowalpha', 'minmotifp');
			$taColumns = $aoColumns;
		}

		// Continue with the standard datatable
		$this->db->select("SQL_CALC_FOUND_ROWS " . implode(", ", $aoColumns) . 
				", CONCAT('<button id=\"', name, '\">Load</button>')", FALSE)
			->from($view);

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
		$iTotal = $this->db->count_all_results($view);

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
}
