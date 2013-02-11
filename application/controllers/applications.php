<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applications extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('application_model');
		$this->title = "Applications";
		$this->active = "applications";
	}
	
	public function index()
	{
		// TODO Pagination
		$page_data['application_list'] = $this->application_model->get_list();
		$this->render_page('applications/list', $page_data);
	}
	
	public function view($id)
	{
		$this->title = "Application Details";
		$page_data['application'] = $this->application_model->get_application_by_id($id);
		if (isset($page_data['application']))
		{
			$this->load->model('color_model');
			$this->title .= ' | ' . $page_data['application']->display_name;
			$list_data['color_list'] = $this->color_model->get_list_for_application($id);
			$list_data['hide_app_col'] = true;
			$page_data['colorsetting_list'] = $this->load_view('colors/table', $list_data);
			$this->render_page('applications/view', $page_data);
		}
		else
		{
			$page_data['error_title'] = "Application Not Found";
			$page_data['error_message'] = "That application was not found in the database.";
			$this->render_page('error', $page_data);
		}
	}

}
