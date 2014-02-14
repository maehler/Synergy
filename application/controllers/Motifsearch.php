<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Motifsearch extends MY_Controller {

	function index() {
		$req_pane = $this->input->get('pane');
		if ($req_pane !== NULL) {
			$data['pane'] = $req_pane;
		} else {
			$data['pane'] = 'iupac';
		}

		$this->load->view('base/header', $this->get_head_data('motifsearch', 'Motif search'));
		$this->load->view('motifsearch_view', $data);
		$this->load->view('base/footer', $this->get_foot_data(array(
			base_url(array('assets', 'js', 'motifsearch.js'))
		)));
	}

	function iupac_search() {
		$this->load->helper('python_helper');

		$iupac = trim($this->input->post('motif-iupac'));
		echo $iupac;

		$unique_id = $this->session->userdata('session_id') . time();
		$outdir = TMP . $unique_id . '.motifsearch';

		if (!file_exists($outdir)) {
			mkdir($outdir, 0775);
		}

		$output = run_python('motifsearch.py', array(
			'--type', 'iupac',
			$outdir,
			$iupac
		), ' ', $outdir);

		echo $output;
		redirect('motifsearch/results/'.$unique_id);
	}

	function matrix_search() {
		redirect('motifsearch?pane=matrix');
	}

	function name_search() {
		$motif_name = trim($this->input->post('motif-name'));

		$this->load->model('motif_model');
		if ($this->motif_model->get($motif_name)) {
			redirect('motif/details/'.$motif_name);
		} else {
			$this->session->set_flashdata('errormessage', 'No motif found.');
			redirect('motifsearch?pane=id');
		}
	}

	function results($id) {
		$res = TMP . $id . '.motifsearch/';
		$url = base_url(array('data', 'tmp', $id . '.motifsearch'));

		$data['url'] = $url;
		$data['is_ready'] = FALSE;
		$data['is_gone'] = TRUE;

		if (file_exists($res)) {
			$data['is_gone'] = FALSE;
		}
		if (file_exists($res . 'tomtom.html')) {
			$data['is_ready'] = TRUE;
		}

		$this->load->view('base/header', $this->get_head_data('motifsearch', 
			'Search results', NULL, $data['is_ready'] ? NULL : 30));
		$this->load->view('motifsearch_results', $data);
		$this->load->view('base/footer', $this->get_foot_data());
	}
}
