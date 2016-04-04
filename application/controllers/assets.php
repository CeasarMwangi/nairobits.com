
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assets extends CI_Controller {

	/**
	 * 
	 */
	public function index()
	{
		$this->load->view('partials/header');
		$this->load->view('assets');
		$this->load->view('partials/footer');
	}
}
