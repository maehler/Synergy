<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Changelog extends MY_Controller {

    public function index()	{
        $this->load->view('base/header', $this->get_head_data());
        $this->load->view('changelog_view');
        $this->load->view('base/footer');
    }
}

/* End of file Changelog.php */
/* Location: ./application/controllers/Changelog.php */
