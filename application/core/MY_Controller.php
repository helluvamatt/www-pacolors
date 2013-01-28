<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $title;
	protected $extra_js;

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->title = "";
		$this->extra_js = array();
		$this->extra_js_file = array();
		$this->userid = $this->session->userdata('user_id');
		if ($this->userid !== FALSE) $this->user = $this->user_model->get_user($this->userid);
	}
	
	protected function render_page($view, $page_data)
	{
		$this->data['title'] = $page_data['title'] = $this->title;
		$this->data['content'] = $this->load_view($view, $page_data);
		$this->data['extra_js'] = $this->extra_js;
		$this->data['extra_js_file'] = $this->extra_js_file;
		$this->data['user'] = isset($this->user) ? $this->user : NULL;
		$this->load->view('default', $this->data);
	}
	
	protected function load_view($view, $page_data)
	{
		return $this->load->view($view, $page_data, TRUE);
	}
	
	protected function register_js_file($name, $script_file)
	{
		$this->extra_js_file[$name] = $script_file;
	}
	
	protected function register_js($name, $script)
	{
		$this->extra_js[$name] = $script;
	}

}
