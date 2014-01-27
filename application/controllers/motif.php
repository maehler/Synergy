<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Motif extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('motif_model');
	}

	function details($motif) {
		$motif_data = $this->motif_model->get($motif);

		$this->load->view('base/header', $this->get_head_data(''));
		$this->load->view('motif_details', $motif_data);
		$this->load->view('base/footer', $this->get_foot_data());
	}
}