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
		$eth = $this->input->post('expand-threshold');
		$expand = $this->input->post('expand-network');
		$animate = $this->input->post('expand-animate');
		$node_labels = $this->input->post('node-labels');
		// If no post values are given, try to take them from the session
		if (!$this->session->userdata('network')) {
			$this->session->set_userdata(array('network' => array()));
		}
		$s_options = $this->session->userdata('network');
		if (!$ntype) {
			$ntype = isset($s_options['ntype']) ? $s_options['ntype'] : 'clr_complete';
		}
		if (!$th) {
			$th = isset($s_options['th']) ? $s_options['th'] : 5;
		}
		if (!$eth) {
			$eth = isset($s_options['eth']) ? $s_options['eth'] : 6;
		}
		if (!$node_labels) {
			$node_labels = isset($s_options['node_labels']) ? $s_options['node_labels'] : 'data(orf)';
		}
		$animate = $animate === FALSE && isset($s_options['animate']) ? $s_options['animate'] : $animate === 'on';
		$expand = $expand == NULL ? FALSE : TRUE;

		// Update the session
		$this->session->set_userdata(array('network' => array(
			'ntype' => $ntype,
			'th' => $th,
			'eth' => $eth,
			'animate' => $animate
		)));

		$network = $this->network_model->get_network($basket, $ntype, $th, $expand, TRUE);

		// Load views
		$this->load->view('base/header', $this->get_head_data('network', 'Network',
			array(
				base_url(array('assets', 'css', 'font-awesome-4.0.3', 'css', 'font-awesome.min.css')),
				base_url(array('assets', 'js', 'cytoscape-panzoom', 'jquery.cytoscape.js-panzoom.css')),
				base_url(array('assets', 'css', 'datatables', 'jquery.dataTables.css')),
				base_url(array('assets', 'css', 'network.css'))
			)
		));
		$this->load->view('network_view', array(
			'basket' => $basket,
			'network' => $network,
			'settings' => array(
				'network_type' => $ntype,
				'network_threshold' => $th,
				'expand-threshold' => $eth,
				'expand' => $expand,
				'animate' => $animate,
				'node_labels' => $node_labels
			),
			'enrichment_view' => $this->load->view('enrichment_view', NULL, TRUE),
			'plot_view' => $this->load->view('expressionplot_view', NULL, TRUE)
		));
		$this->load->view('base/footer', $this->get_foot_data(
			array(
				base_url(array('assets', 'js', 'jquery.download.js')),
				base_url(array('assets', 'js', 'qtip', 'jquery.qtip.min.js')),
				base_url(array('assets', 'js', 'cytoscape', 'arbor.js')),
				base_url(array('assets', 'js', 'cytoscape-panzoom', 'jquery.cytoscape.js-panzoom.js')),
				base_url(array('assets', 'js', 'cytoscape-cxtmenu', 'jquery.cytoscape.js-cxtmenu.js')),
				base_url(array('assets', 'js', 'cytoscape', 'cytoscape.min.js')),
				base_url(array('assets', 'js', 'jquery.cytoscape.js-qtip.js')),
				base_url(array('assets', 'js', 'jquery.dataTables.min.js')),
				base_url(array('assets', 'js', 'jquery.dataTables.sorting.js')),
				base_url(array('assets', 'js', 'jquery.flot.js')),
				base_url(array('assets', 'js', 'jquery.flot.resize.js')),
				base_url(array('assets', 'js', 'jquery.flot.selection.js')),
				base_url(array('assets', 'js', 'jquery.flot.axislabels.js')),
				base_url(array('assets', 'js', 'network.js'))
			)
		));
	}
}
