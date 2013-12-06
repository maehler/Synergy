<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function get_head_data($current_pane, $title_prefix='', $stylesheets=NULL) {
    	$data['current_pane'] = $current_pane;
    	$data['title_prefix'] = $title_prefix;
    	if ($stylesheets !== NULL) {
    		$data['ssheets'] = $stylesheets;
    	}
    	return $data;
    }
}