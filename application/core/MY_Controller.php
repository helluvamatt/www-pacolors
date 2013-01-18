<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	protected function render_page($view, $page_data)
	{
		$this->data['content'] = $this->load->view($view, $page_data, TRUE);
		$this->load->view('default', $this->data);
	}
	
}
