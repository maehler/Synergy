<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Motif extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('motif_model');
	}

	function details($motif="") {
		$motif_data = $this->motif_model->get($motif);
		if (!isset($motif_data['motif_name'])) {
			$motif_data['motif_name'] = NULL;
		}

		$motif_data['genes'] = $this->motif_model->get_motif_genes($motif);

		$gene_ids = array();
		foreach ($motif_data['genes'] as $gene) {
			$gene_ids[] = $gene['id'];
		}

		$motif_data['unique_genes'] = count(array_unique($gene_ids));

		$this->load->view('base/header', $this->get_head_data('', 
			$motif_data['motif_name'], array(
				base_url(array('assets', 'css', 'datatables', 'jquery.dataTables.css'))
			)
		));
		$this->load->view('motif_details', $motif_data);
		$this->load->view('base/footer', $this->get_foot_data(array(
			base_url(array('assets', 'js', 'jquery.download.js')),
			base_url(array('assets', 'js', 'jquery.dataTables.min.js')),
			base_url(array('assets', 'js', 'jquery.dataTables.sorting.js')),
			base_url(array('assets', 'js', 'isblogo.js')),
			base_url(array('assets', 'js', 'motif.js'))
		)));
	}
}
