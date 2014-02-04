<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Regtools extends MY_Controller {

	function index() {
		$this->load->view('base/header', $this->get_head_data('regtools', 'Regulation tools'));
		$this->load->view('regtools_view.php');
		$this->load->view('base/footer', $this->get_foot_data());
	}
}
