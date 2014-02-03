<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	// Search functions
	function genetable ($datatable=TRUE) {
		$this->load->model('gene_model');
		$genes = $this->gene_model->get_datatable();

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($genes));
	}

	// Basket functions
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
		$this->session->set_userdata(array('basket' => array_values($basket)));
	}

	function empty_basket() {
		$this->session->set_userdata(array('basket' => array()));
	}

	function replace_basket($genes=NULL) {
		if ($genes === NULL) {
			$genes = $this->input->post('genes');
		}
		$this->session->set_userdata(array('basket' => $genes));
	}

	function export_selection() {
		$genes = $this->input->post('genes');
		$this->load->helper('python_helper');
		$this->load->helper('download');

		$data = run_python('export_genes.py', $genes);

		force_download('gene_list.txt', $data);
	}

	// Network functions
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

	function export_gml_network() {
		$jsonNetwork = $this->input->post('json');
		$this->load->helper('python_helper');
		$this->load->helper('download');

		$data = run_python('export_network.py',
			array(
				$jsonNetwork,
				'--type', 'gml'
			)
		);

		force_download('network.gml', $data);
	}

	function export_png_network() {
		$jsonNetwork = $this->input->post('json');
		$this->load->helper('python_helper');
		$this->load->helper('download');

		$data = run_python('export_network.py',
			array(
				$jsonNetwork,
				'--type', 'png'
			)
		);

		force_download('network.png', $data);
	}

	function export_pdf_network() {
		$jsonNetwork = $this->input->post('json');
		$this->load->helper('python_helper');
		$this->load->helper('download');

		$data = run_python('export_network.py',
			array(
				$jsonNetwork,
				'--type', 'pdf'
			)
		);

		force_download('network.pdf', $data);
	}

	// Enrichment functions
	function goenrichment() {
		$this->load->helper('python_helper');

		$genes = $this->input->post('genes');
		$pth = $this->input->post('pth');

		$output = run_python('goenrich.py', array(
			'-p', $pth,
			$genes
		));

		$this->output
			->set_content_type('application/json')
			->set_output($output);
	}

	function motifenrichment() {
		$this->load->helper('python_helper');

		$genes = $this->input->post('genes');
		$pth = $this->input->post('pth');
		$central = $this->input->post('central') === 'true' ? '--central' : '';
		$qth = $this->input->post('qth');

		$output = run_python('motifenrich.py', array(
			$central,
			'-p', $pth,
			'--fimoq', $qth,
			$genes
		));

		$this->output
			->set_content_type('application/json')
			->set_output($output);
	}

	// Gene lists
	function genelist($type) {
		$this->load->model('genelist_model');

		switch ($type) {
			case 'go':
				$data = $this->genelist_model->get_datatable('golist_view');
				break;
			case 'motif':
				$data = $this->genelist_model->get_datatable('motiflist_view');
				break;
			case 'coexp':
				$data = $this->genelist_model->get_datatable('coexplist_view');
				break;
			case 'regulatory':
				$data = $this->genelist_model->get_datatable('tflist_view');
				break;
			
			default:
				# code...
				break;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}
}
