<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function get_head_data($current_pane='', $title_prefix='', $stylesheets=NULL) {
    	$data['current_pane'] = $current_pane;
    	$data['title_prefix'] = $title_prefix;
    	if ($stylesheets !== NULL) {
    		$data['ssheets'] = $stylesheets;
    	}
        $error_message = $this->session->flashdata('errormessage');
        if ($error_message) {
            $data['error_message'] = $error_message;
        }

        // Check if the user has accepted cookies
        if (!$this->session->userdata('cookies_accepted')) {
            $data['cookie_disclaimer'] = TRUE;
        } else {
            $data['cookie_disclaimer'] = FALSE;
        }

    	return $data;
    }

    public function get_foot_data($scripts=NULL, $inlinejs="") {
    	$data['inlinejs'] = $inlinejs;
    	$data['scripts'] = $scripts;
        return $data;
    }
}
