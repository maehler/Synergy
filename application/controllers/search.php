<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('gene_model');
	}

	public function index() {
		$this->load->view('base/header', $this->get_head_data("search"));
		$this->load->view('search_view');
		$this->load->view('base/footer');
	}
}