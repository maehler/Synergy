<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('gene_model');
	}

	public function index() {
		$basket = $this->session->userdata('basket');
		if (!$basket) {
			$basket = array();
		}

		$this->load->view('base/header', $this->get_head_data(
			"search", "Gene search",
			array(base_url(array('assets', 'css', 'datatables', 'jquery.dataTables.css')))
		));
		$this->load->view('search_view', array('basket' => $basket));
		$this->load->view('base/footer', $this->get_foot_data(
			array(
				base_url(array('assets', 'js', 'search.js')),
				base_url(array('assets', 'js', 'jquery.dataTables.min.js'))
			)
		));
	}
}