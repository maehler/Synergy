<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index()	{
		$this->load->view('base/header', $this->get_head_data("home"));
		$this->load->view('home_view');
		$this->load->view('base/footer');
	}

	public function accept_cookies($redir='') {
		$this->session->set_userdata('cookies_accepted', TRUE);
		redirect($redir, 'refresh');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
