<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Motifsearch extends MY_Controller {

	function index() {
		$this->load->library('form_validation');

		$data['pane'] = 'iupac';
		$pane = $this->input->post('motif-search-radio');

		if ($pane == 'search-iupac-pane') {
			$this->form_validation->set_rules('motif-iupac', 'IUPAC motif', 'required');
			if ($this->form_validation->run() === FALSE) {
				$data['pane'] = 'iupac';
			} else {
				$this->iupac_search();
			}
		} else if ($pane == 'search-matrix-pane') {
			$this->form_validation->set_rules('motif-matrix', 'matrix motif', 'required');
			if ($this->form_validation->run() === FALSE) {
				$data['pane'] = 'matrix';
			} else {
				$this->matrix_search();
			}
		} else if ($pane == 'search-id-pane') {
			$this->form_validation->set_rules('motif-name', 'Motif name', 'required');
			if ($this->form_validation->run() === FALSE) {
				$data['pane'] = 'id';
			} else {
				$this->name_search();
			}
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

		redirect('motifsearch/results/'.$unique_id);
	}

	function matrix_search() {
		$this->load->helper('python_helper');

		$matrix = $this->input->post('motif-matrix');

		$unique_id = $this->session->userdata('session_id') . time();
		$outdir = TMP . $unique_id . '.motifsearch';

		if (!file_exists($outdir)) {
			mkdir($outdir, 0775);
		}

		run_python('motifsearch.py', array(
			'--type', 'matrix',
			$outdir,
			$matrix
		), ' ', $outdir);

		redirect('motifsearch/results/'.$unique_id);
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
