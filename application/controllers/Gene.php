<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gene extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('gene_model');
		$this->load->model('motif_model');
		$this->load->model('expression_model');
	}

	public function details ($orf_id) {
		$gene_data = $this->gene_model->get_orf_gene($orf_id);
		if (!$gene_data) {
			$gene_data = $this->gene_model->get_accession_gene($orf_id);
		}

		$motif_data = $this->motif_model->get_gene_motifs($gene_data['id']);

		$gene_data['motifs'] = $motif_data;
		$gene_data['promoter_sequence'] = $this->gene_model->get_promoter_seq($gene_data['id']);
		$gene_data['cds'] = $this->gene_model->get_cds($gene_data['id']);
		$gene_data['protein'] = $this->gene_model->get_protein($gene_data['id']);

		if ($this->session->userdata('basket')) {
			$gene_data['in_basket'] = in_array(intval($gene_data['id']), $this->session->userdata('basket'));
		} else {
			$gene_data['in_basket'] = FALSE;
		}

		$gene_data['expression_plot'] = $this->load->view('expressionplot_view', NULL, TRUE);

		$this->load->view('base/header', $this->get_head_data('', $orf_id,
			array(
				base_url(array('assets', 'css', 'gene.css')),
				base_url(array('assets', 'css', 'datatables', 'jquery.dataTables.css'))
			)
		));
		$this->load->view('gene_details', $gene_data);
		$this->load->view('base/footer', $this->get_foot_data(
			array(
				base_url(array('assets', 'js', 'jquery.download.js')),
				base_url(array('assets', 'js', 'jquery.dataTables.min.js')),
				base_url(array('assets', 'js', 'jquery.dataTables.sorting.js')),
				base_url(array('assets', 'js', 'jquery.flot.js')),
				base_url(array('assets', 'js', 'jquery.flot.selection.js')),
				base_url(array('assets', 'js', 'jquery.flot.resize.js')),
				base_url(array('assets', 'js', 'jquery.flot.axislabels.js')),
				base_url(array('assets', 'js', 'gene.js'))
			)
		));
	}
}
