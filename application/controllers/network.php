<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Network extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('gene_model');
		$this->load->model('network_model');
	}

	function index() {
		$basket = $this->session->userdata('basket');
		if (!$basket) {
			$basket = FALSE;
		}

		$this->load->view('base/header', $this->get_head_data('network', 'Network',
			array(
				base_url(array('assets', 'css', 'network.css'))
			)
		));
		$this->load->view('network_view');
		$this->load->view('base/footer', $this->get_foot_data(
			array(
				base_url(array('assets', 'js', 'cytoscape', 'arbor.js')),
				base_url(array('assets', 'js', 'cytoscape', 'cytoscape.min.js')),
				base_url(array('assets', 'js', 'cytoscape', 'jquery.cytoscape-panzoom.min.js')),
				base_url(array('assets', 'js', 'cytoscape', 'cytoscape.min.js'))
			)
		));
	}
}