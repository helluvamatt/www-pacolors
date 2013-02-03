<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (has_role($this->role_map, 'sys.manage') == false)
		{
			$this->no_permission();
		}
	}
	
	# -------------------------------------------------------------------------
	# Dashboard
	# -------------------------------------------------------------------------
	public function index()
	{
		
	}
	
	# -------------------------------------------------------------------------
	# Manage Colors Functions
	# -------------------------------------------------------------------------
	public function colors()
	{
		
	}
	
	# -------------------------------------------------------------------------
	# Manage Applications Functions
	# -------------------------------------------------------------------------
	public function applications()
	{
		
	}
	
	# -------------------------------------------------------------------------
	# Manage Users Functions
	# -------------------------------------------------------------------------
	public function users()
	{
		
	}
	
	# -------------------------------------------------------------------------
	# Private Functions
	# -------------------------------------------------------------------------
	private function no_permission()
	{
		$page_data['error_title'] = "Not Authorized";
		$page_data['error_message'] = "You do not have permission to do that.";
		$this->render_page('error', $page_data);
	}
	
}
