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

		$this->load->helper('form');

		$this->load->view('base/header', $this->get_head_data(
			"search", "Gene search", array(
				base_url(array('assets', 'css', 'datatables', 'jquery.dataTables.css')),
				base_url(array('assets', 'css', 'jquery-ui-1.10.4.custom.css'))
			)
		));
		$this->load->view('search_view', array('basket' => $basket));
		$this->load->view('base/footer', $this->get_foot_data(
			array(
				base_url(array('assets', 'js', 'jquery-ui-1.10.4.custom.min.js')),
				base_url(array('assets', 'js', 'search.js')),
				base_url(array('assets', 'js', 'jquery.dataTables.min.js'))
			)
		));
	}

	public function upload() {
		$this->load->helper('python_helper');

		$parse_string = run_python('parse_genelist.py', array($_FILES['gene-file']['tmp_name']));

		$ids = json_decode($parse_string);

		$this->session->set_userdata(array('basket' => $ids->success));

		if (count($ids->fail) > 0) {
			$message = 'The following terms could not be found: ';
			$message .= implode(', ', $ids->fail);
			$this->session->set_flashdata('errormessage', $message);
		}

		redirect(base_url('basket'), 'refresh');
	}
}
