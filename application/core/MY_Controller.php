<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	protected function render_page($view, $page_data, $render_to_var = FALSE)
	{
		$this->data['content'] = $this->load->view($view, $page_data, TRUE);
		return $this->load->view('default', $this->data, $render_to_var);
	}
	
}
