<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Network extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('gene_model');
		$this->load->model('network_model');
		$this->load->helper('form');
	}

	function index() {
		$basket = $this->session->userdata('basket');
		if (!$basket) {
			$basket = FALSE;
		}
		
		// Set up the network type and threshold values
		$ntype = $this->input->post('network-type');
		$th = $this->input->post('network-threshold');
		$expand = $this->input->post('expand-network');
		if (!$ntype) {
			$ntype = 'clr_complete'; // Default
		}
		if (!$th) {
			$th = 5; // Default
		}
		$expand = $expand == NULL ? FALSE : TRUE;

		$network = $this->network_model->get_network($basket, $ntype, $th, $expand, TRUE);

		// Load views
		$this->load->view('base/header', $this->get_head_data('network', 'Network',
			array(
				base_url(array('assets', 'css', 'font-awesome-4.0.3', 'css', 'font-awesome.min.css')),
				base_url(array('assets', 'js', 'cytoscape-panzoom', 'jquery.cytoscape.js-panzoom.css')),
				base_url(array('assets', 'css', 'network.css'))
			)
		));
		$this->load->view('network_view', array(
			'basket' => $basket,
			'network' => $network,
			'settings' => array(
				'network_type' => $ntype,
				'network_threshold' => $th,
				'expand' => $expand
			)
		));
		$this->load->view('base/footer', $this->get_foot_data(
			array(
				base_url(array('assets', 'js', 'cytoscape', 'arbor.js')),
				base_url(array('assets', 'js', 'cytoscape-panzoom', 'jquery.cytoscape.js-panzoom.js')),
				base_url(array('assets', 'js', 'cytoscape', 'cytoscape.min.js')),
				base_url(array('assets', 'js', 'network.js'))
			)
		));
	}
}