<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $title;
	protected $extra_js;

	protected $system_roles = array('sys.roles.admin', 'sys.roles.mod');
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->title = "";
		$this->extra_js = array();
		$this->extra_js_file = array();
		$this->userid = $this->session->userdata('user_id');
		$this->role_map = array();
		if ($this->userid !== FALSE)
		{
			$this->user = $this->user_model->get_user($this->userid);
			foreach ($this->system_roles as $role)
			{
				$this->role_map[$role] = $this->user_model->has_role($this->userid, $role);
			}
		}
	}
	
	protected function render_page($view, $page_data)
	{
		$this->data['title'] = $page_data['title'] = $this->title;
		$this->data['content'] = $this->load_view($view, $page_data);
		$this->data['extra_js'] = $this->extra_js;
		$this->data['extra_js_file'] = $this->extra_js_file;
		$this->data['user'] = isset($this->user) ? $this->user : NULL;
		$this->role_map['sys.manage'] = $this->has_any_roles(array('sys.roles.admin', 'sys.roles.mod'));
		$this->data['role_map'] = $this->role_map;
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
	
	protected function has_role($role)
	{
		return isset($this->role_map[$role]) && $this->role_map[$role] === TRUE;
	}
	
	protected function has_any_roles($roles)
	{
		foreach ($roles as $role)
		{
			if ($this->has_role($role)) return true;
		}
		return false;
	}
	
	protected function has_all_roles($roles)
	{
		foreach ($roles as $role)
		{
			if ($this->has_role($userid, $role) == false) return false;
		}
		return true;
	}

}
