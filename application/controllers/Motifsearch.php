<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Motifsearch extends MY_Controller {

	function index() {
		$this->load->library('form_validation');

		$data['pane'] = 'iupac';
		$pane = $this->input->post('motif-search-radio');

		if ($pane == 'search-iupac-pane') {
			$this->form_validation->set_rules('motif-iupac', 'IUPAC motif', 'trim|required');
			$this->form_validation->set_rules('sign-thresh', 'Significance threshold', 'required');
			$this->form_validation->set_rules('thresh-type', 'Threshold type', 'required');
			$this->form_validation->set_rules('min-overlap', 'Minimum overlap', 'required');
			if ($this->form_validation->run() === FALSE) {
				$data['pane'] = 'iupac';
			} else {
				$this->iupac_search();
			}
		} else if ($pane == 'search-matrix-pane') {
			$this->form_validation->set_rules('motif-matrix', 'matrix motif', 'trim|required');
			$this->form_validation->set_rules('sign-thresh', 'Significance threshold', 'required');
			$this->form_validation->set_rules('thresh-type', 'Threshold type', 'required');
			$this->form_validation->set_rules('min-overlap', 'Minimum overlap', 'required');
			if ($this->form_validation->run() === FALSE) {
				$data['pane'] = 'matrix';
			} else {
				$this->matrix_search();
			}
		} else if ($pane == 'search-id-pane') {
			$this->form_validation->set_rules('motif-name', 'Motif name', 'trim|required|callback_motif_check');
			if ($this->form_validation->run() === FALSE) {
				$data['pane'] = 'id';
			} else {
				redirect('motif/details/'.$this->input->post('motif-name'));
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

		$iupac = $this->input->post('motif-iupac');
		$th = $this->input->post('sign-thresh');
		$thtype = $this->input->post('thresh-type');
		$minovlp = $this->input->post('min-overlap');
		$central = $this->input->post('central-motifs') !== NULL ? TRUE : FALSE;

		$unique_id = $this->session->userdata('session_id') . time();
		$outdir = TMP . $unique_id . '.motifsearch';

		if (!file_exists($outdir)) {
			mkdir($outdir, 0775);
		}

		$output = run_python('motifsearch.py', array(
			'--type', 'iupac',
			$central ? '--central' : NULL,
			$thtype == 'evalue' ? '--evalue' : NULL,
			'--thresh', $th,
			'--min-overlap', $minovlp,
			$outdir,
			$iupac,
		), ' ', $outdir);

		redirect('motifsearch/results/'.$unique_id);
	}

	function matrix_search() {
		$this->load->helper('python_helper');

		$matrix = $this->input->post('motif-matrix');
		$th = $this->input->post('sign-thresh');
		$thtype = $this->input->post('thresh-type');
		$minovlp = $this->input->post('min-overlap');
		$central = $this->input->post('central-motifs') !== NULL ? TRUE : FALSE;

		$unique_id = $this->session->userdata('session_id') . time();
		$outdir = TMP . $unique_id . '.motifsearch';

		if (!file_exists($outdir)) {
			mkdir($outdir, 0775);
		}

		run_python('motifsearch.py', array(
			'--type', 'matrix',
			$central ? '--central' : NULL,
			$thtype == 'evalue' ? '--evalue' : NULL,
			'--thresh', $th,
			'--min-overlap', $minovlp,
			$outdir,
			$matrix
		), ' ', $outdir);

		redirect('motifsearch/results/'.$unique_id);
	}

	function motif_check($name) {
		$this->load->model('motif_model');
		if ($this->motif_model->get($name) === FALSE) {
			$this->form_validation->set_message('motif_check',
				'The motif "' . $name . '" could not be found.');
			return FALSE;
		} else {
			return TRUE;
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
