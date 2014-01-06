<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Network extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('gene_model');
	}

	function index() {
		$basket = $this->session->userdata('basket');
		if (!$basket) {
			$basket = FALSE;
		}

		$this->load->view('base/header', $this->get_head_data('network', 'Network'));
		$this->load->view('base/footer');
	}
}