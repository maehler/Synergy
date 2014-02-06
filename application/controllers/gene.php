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
		$expression_data = $this->expression_model->get_flot_expression($gene_data['id']);

		$gene_data['motifs'] = $motif_data;
		$gene_data['expression'] = $expression_data;

		$gene_data['expression_plot'] = $this->load->view('expressionplot_view', NULL, TRUE);

		$this->load->view('base/header', $this->get_head_data('', $orf_id, 
			array(
				base_url(array('assets', 'css', 'datatables', 'jquery.dataTables.css'))
			)
		));
		$this->load->view('gene_details', $gene_data);
		$this->load->view('base/footer', $this->get_foot_data(
			array(
				base_url(array('assets', 'js', 'jquery.dataTables.min.js')),
				base_url(array('assets', 'js', 'jquery.dataTables.sorting.js')),
				base_url(array('assets', 'js', 'jquery.flot.js')),
				base_url(array('assets', 'js', 'jquery.flot.selection.js')),
				base_url(array('assets', 'js', 'jquery.flot.resize.js')),
				base_url(array('assets', 'js', 'gene.js'))
			)
		));
	}
}
