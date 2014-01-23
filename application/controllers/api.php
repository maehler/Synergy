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

		if (gettype($gene_id) == 'array') {
			foreach ($gene_id as $gid) {
				$idx = array_search($gid, $basket);
				if ($idx === FALSE) {
					// Just add to the basket, don't remove if it already exists
					$basket[] = $gid;
				}
			}
			$this->session->set_userdata(array('basket' => array_values($basket)));
		} else {
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
	}

	function remove_from_basket() {
		$gene_ids = $this->input->get('genes');

		if (!$this->session->userdata('basket')) {
			$this->session->set_userdata(array('basket' => array()));
		}

		$basket = $this->session->userdata('basket');

		foreach ($gene_ids as $gid) {
			$idx = array_search($gid, $basket);
			if ($idx !== FALSE) {
				unset($basket[$idx]);
			}
		}
		$this->session->set_userdata(array('basket' => $basket));
	}

	function empty_basket() {
		$this->session->set_userdata(array('basket' => array()));
	}

	function replace_basket() {
		$genes = $this->input->post('genes');
		$this->session->set_userdata(array('basket' => $genes));
	}

	function network_neighbors($orf, $th=5, $ntype='clr_complete') {
		$this->load->model('network_model');

		$network = $this->network_model->get_neighbors($orf, floatval($th), $ntype);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($network));
	}

	function network_edges() {
		$this->load->model('network_model');

		$genes = $this->input->post('genes');
		$th = $this->input->post('th');
		$ntype = $this->input->post('ntype');

		$edges = $this->network_model->get_edges($genes, $th, $ntype);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($edges));
	}
}
