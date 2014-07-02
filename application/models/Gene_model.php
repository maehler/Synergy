<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gene_model extends CI_Model {

	function get_datatable() {
		$aoColumns = array('id', 'orf_id', 'refseq_id',
			'symbol', 'definition', 'category', 'tf');
		$taColumns = array('g.id', 'g.orf_id', 'g.refseq_id',
			'g.symbol', 'g.definition', 'g.category', 'g.tf');

		// If the user only wants the filtered results
		if ($this->input->post('selgenes') == 'all') {
			$this->db->select('g.id')
				->from('gene AS g')
				->join('operon_gene AS og', 'og.gene_id = g.id', 'left');

			$sQuery = $this->input->post('sQuery');

			if ($sQuery !== FALSE) {
				for ($i = 0; $i < count($taColumns); $i++) {
					if ($i == 0) {
						$this->db->like($taColumns[$i], $sQuery);
					} else {
						$this->db->or_like($taColumns[$i], $sQuery);
					}
				}
			}

			$query = $this->db->get();
			$res = $query->result_array();

			$selgenes = array();
			foreach ($res as $row) {
				$selgenes[] = $row['id'];
			}

			return array('selected_genes' => array_values($selgenes));
		}

		// Continue with the standard datatable
		$this->db->select("SQL_CALC_FOUND_ROWS
						   CONCAT('<input type=\"checkbox\" id=\"',g.id,'\" />') AS `checkbox`,
						   CONCAT('<a href=\"gene/details/', g.orf_id, '\">', g.orf_id, '</a>'),
						   g.refseq_id, CONCAT('<i>', g.symbol, '</i>'), g.definition, g.category,
						   (case when g.tf = 1 then \"&#x2713;\" else \"\" end)",
						   FALSE)
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
		$iFilteredTotalTmp = $query->row_array();
		$iFilteredTotal = $iFilteredTotalTmp['found_rows'];
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

	function get_genes($gene_ids) {
		$this->db->select('id, orf_id, refseq_id, symbol, category, definition, tf')
			->from('gene')
			->where_in('id', $gene_ids);
		$query = $this->db->get();
		$genes = $query->result_array();
		return $genes;
	}

	function get_go_genes($go, $gene_ids=array()) {
		$this->db->select('gene_id')
			->from('go_gene')
			->join('go', 'go.id = go_gene.go_id');
		if (!empty($gene_ids)) {
			$this->db->where_in('gene_id', $gene_ids);
		}
		$this->db->where('go.go', $go);
		$query = $this->db->get();
		$genes = array();
		foreach ($query->result_array() as $row) {
			$genes[] = intval($row['gene_id']);
		}
		return array_values($genes);
	}

	function get_motif_genes($motif, $gene_ids=array()) {
		$this->db->select("g.id")
			->from("gene AS g")
			->join("promoter AS p", "p.gene_id = g.id")
			->join("promoter_motif AS pm", "pm.promoter_id = p.id")
			->join("motif AS m", "m.id = pm.motif_id");
		if (!empty($gene_ids)) {
			$this->db->where_in("g.id", $gene_ids);
		}
		$this->db->where("m.name", $motif);
		$query = $this->db->get();
		$genes = array();
		foreach ($query->result_array() as $row) {
			$genes[] = intval($row["id"]);
		}
		return array_values($genes);
	}

	function get_orf_gene($orf_id) {
		$this->db->select('id, orf_id, refseq_id, symbol, category, definition, tf')
			->from('gene')
			->where('orf_id', $orf_id)
			->limit(1);
		$query = $this->db->get();
		$gene = $query->result_array();
		if ($query->num_rows() == 0) {
			return FALSE;
		}
		return $gene[0];
	}

	function get_accession_gene($acc) {
		$this->db->select('id, orf_id, refseq_id, symbol, category, definition, tf')
			->from('gene')
			->where('refseq_id', $acc)
			->limit(1);
		$query = $this->db->get();
		$gene = $query->result_array();
		if ($query->num_rows() == 0) {
			return FALSE;
		}
		return $gene[0];
	}

	function get_promoter_seq($id) {
		$this->db->select('sequence')
			->from('promoter')
			->where('gene_id', $id);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return FALSE;
		}
		$res = $query->row_array();
		return $res['sequence'];
	}

	function get_cds($id) {
		$this->db->select('cds')
			->from('gene')
			->where('id', $id);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return FALSE;
		}
		$res = $query->row_array();
		return $res['cds'];
	}

	function get_protein($id) {
		$this->db->select('protein')
			->from('gene')
			->where('id', $id);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return FALSE;
		}
		$res = $query->row_array();
		return $res['protein'];
	}

	function get_all() {
		$this->db->select('id', 'orf_id', 'refseq_id', 'symbol', 'category', 'definition', 'tf')
			->from('gene');
		$query = $this->db->get();
		$genes = $query->result_array();
		return $genes;
	}
}
