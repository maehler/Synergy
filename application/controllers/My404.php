<?php defined('BASEPATH') OR exit('No direct script access allowed');

class My404 extends MY_Controller {

	function index() {
		$this->output->set_status_header('404');
		$this->load->view('base/header', $this->get_head_data('', 'Page not found'));
		$this->load->view('base/404.php');
		$this->load->view('base/footer', $this->get_foot_data());
	}
}
