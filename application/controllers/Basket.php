<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Basket extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('gene_model');
	}

	public function index() {
		$basket = $this->session->userdata('basket');

		if ($basket) {
			$genes = $this->gene_model->get_genes($basket);
		} else {
			$genes = FALSE;
		}

		$enrichment_view = $this->load->view('enrichment_view', NULL, TRUE);
		$plot_view = $this->load->view('expressionplot_view', NULL, TRUE);

		$this->load->view('base/header', $this->get_head_data('basket', 'Basket',
			array(base_url(array('assets', 'css', 'datatables', 'jquery.dataTables.css')))
		));
		$this->load->view('basket_view', 
			array(
				'genes' => $genes, 
				'enrichment_view' => $enrichment_view,
				'plot_view' => $plot_view
			)
		);
		$this->load->view('base/footer', $this->get_foot_data(
			array(
				base_url(array('assets', 'js', 'jquery.flot.js')),
				base_url(array('assets', 'js', 'jquery.flot.resize.js')),
				base_url(array('assets', 'js', 'jquery.flot.selection.js')),
				base_url(array('assets', 'js', 'jquery.flot.axislabels.js')),
				base_url(array('assets', 'js', 'jquery.download.js')),
				base_url(array('assets', 'js', 'jquery.dataTables.min.js')),
				base_url(array('assets', 'js', 'jquery.dataTables.sorting.js')),
				base_url(array('assets', 'js', 'basket.js'))
			)
		));
	}
}
