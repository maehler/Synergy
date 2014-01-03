<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gene extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('gene_model');
	}

	public function details ($orf_id) {
		$data = $this->gene_model->get_orf_gene($orf_id);
		if (!$data) {
			$data = $this->gene_model->get_accession_gene($orf_id);
		}

		$this->load->view('base/header', $this->get_head_data('', $orf_id));
		$this->load->view('gene_details', $data);
		$this->load->view('base/footer');
	}
}
