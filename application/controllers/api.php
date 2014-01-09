<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	function genetable ($datatable=TRUE) {
		$this->load->model('gene_model');
		$genes = $this->gene_model->get_datatable();

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($genes));
	}

	function update_basket() {
		$gene_id = $this->input->get('gene');

		if (!$this->session->userdata('basket')) {
			$this->session->set_userdata(array('basket' => array()));
		}

		$basket = $this->session->userdata('basket');
		$idx = array_search($gene_id, $basket);
		if ($idx !== FALSE) {
			// Remove gene id from basket
			unset($basket[$idx]);
			$this->session->set_userdata(array('basket' => array_values($basket)));
		} else {
			// Add gene id to basket
			$basket[] = $gene_id;
			$this->session->set_userdata(array('basket' => $basket));
		}
	}

	function empty_basket() {
		$this->session->set_userdata(array('basket' => array()));
	}

	function replace_basket() {
		$genes = $this->input->post('genes');
		$this->session->set_userdata(array('basket' => $genes));
	}
}