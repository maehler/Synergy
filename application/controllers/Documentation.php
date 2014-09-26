<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Documentation extends MY_Controller {

	public function index() {
		$this->load->view('base/header',
			$this->get_head_data('documentation', 'Documentation',
				array(base_url(array('assets', 'css', 'documentation.css')))
			)
		);
		$this->load->view('documentation_view');
		$this->load->view('base/footer', $this->get_foot_data());
	}
}
