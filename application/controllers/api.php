<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	function genetable ($datatable=TRUE) {
		$this->load->model('gene_model');
		$genes = $this->gene_model->get_datatable();

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($genes));
	}

}