<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends Role_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	# -------------------------------------------------------------------------
	# Dashboard
	# -------------------------------------------------------------------------
	public function index()
	{
		redirect(site_url('manage/colors'));
	}
	
	# -------------------------------------------------------------------------
	# Manage Colors Functions
	# -------------------------------------------------------------------------
	public function colors()
	{
		if ($this->check_role('sys.manage'))
		{
			$this->active = "manage.colors";
			$this->load->model('color_model');
			$page_data['colors'] = $this->color_model->get_manage_list();
			$this->render_page('manage/color_list', $page_data);
		}
	}
	
	public function toggle_color($id)
	{
		if ($this->check_role('sys.manage'))
		{
			$this->load->model('color_model');
			$this->color_model->toggle_color($id);
			redirect(site_url('manage/colors'));
		}
	}
	
	# -------------------------------------------------------------------------
	# Manage Applications Functions
	# -------------------------------------------------------------------------
	public function applications()
	{
		if ($this->check_role('sys.manage'))
		{
			$this->active = "manage.applications";
			$this->load->model('application_model');
			$page_data['applications'] = $this->application_model->get_manage_list();
			$this->render_page('manage/application_list', $page_data);
		}
	}
	
	public function toggle_application($id)
	{
		if ($this->check_role('sys.manage'))
		{
			$this->load->model('application_model');
			$this->application_model->toggle_application($id);
			redirect(site_url('manage/applications'));
		}
	}
	
	# -------------------------------------------------------------------------
	# Manage Users Functions
	# -------------------------------------------------------------------------
	public function users()
	{
		if ($this->check_role('sys.roles.admin'))
		{
			$this->active = "manage.users";
			$page_data['users'] = $this->user_model->get_users();
			$this->render_page('manage/user_list', $page_data);
		}
	}
	
	public function toggle_user($id)
	{
		if ($this->check_role('sys.roles.admin'))
		{
			$this->user_model->toggle_user($id);
			redirect(site_url('manage/users'));
		}
	}
	
	/*
	public function reset_password($id)
	{
		if ($this->check_role('sys.roles.admin'))
		{
			$this->user_model->reset_password($id);
			// TODO Set flashdata message
			redirect(site_url('manage/users'));
		}
	}
	*/
	
}
