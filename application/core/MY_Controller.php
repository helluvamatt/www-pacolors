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
		$this->load->helper('permissions');
		$this->title = "";
		$this->active = "";
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
			$this->role_map['sys.manage'] = has_any_roles($this->role_map, array('sys.roles.admin', 'sys.roles.mod'));
		}
	}
	
	protected function render_page($view, $page_data)
	{
		$this->data['title'] = $page_data['title'] = $this->title;
		$this->data['active'] = $this->active;
		$this->data['user'] = $page_data['user'] = isset($this->user) ? $this->user : NULL;
		$this->data['role_map'] = $page_data['role_map'] = $this->role_map;
		$this->data['content'] = $this->load_view($view, $page_data);
		$this->data['extra_js'] = $this->extra_js;
		$this->data['extra_js_file'] = $this->extra_js_file;
		$this->data['flashdata_message'] = $this->get_flashdata_message();
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
	
	protected function get_flashdata_message()
	{
		$msg = $this->session->flashdata('message');
		return isset($msg) ? json_decode($msg) : NULL;
	}
	
	protected function set_flashdata_message($css_class, $message, $redirect = NULL)
	{
		$this->session->set_flashdata('message', json_encode(array('css_class' => $css_class, 'message' => $message)));
		if (isset($redirect)) redirect(site_url($redirect));
	}

}

class Role_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function check_role($role = 'sys.manage')
	{
		if (has_role($this->role_map, $role) == false)
		{
			$this->no_permission();
			return false;
		}
		else
		{
			return true;
		}
	}

	private function no_permission()
	{
		$page_data['error_title'] = "Not Authorized";
		$page_data['error_message'] = "You do not have permission to do that.";
		$this->render_page('error', $page_data);
	}
}
