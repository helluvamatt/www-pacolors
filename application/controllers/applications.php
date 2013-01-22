<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applications extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('application_model');
		$this->data['title'] = "Applications";
		$this->data['active'] = "applications";
	}
	
	public function index()
	{
		// TODO Pagination
		$this->page_data['application_list'] = $this->application_model->get_list();
		$this->render_page('applications/list', $this->page_data);
	}
	
	public function view($id)
	{
		$this->data['title'] = "Application Details";
		$this->page_data['application'] = $this->color_model->get_color($id);
		if (isset($this->page_data['application']))
		{
			$this->load->model('color_model');
			$this->data['title'] .= ' | ' . $this->page_data['application']['display_name'];
			$this->list_data['color_list'] = $this->color_model->get_list_for_application($id);
			$this->page_data['colorsetting_list'] = $this->render_page('colors/table', $list_data, TRUE);
			$this->render_page('applications/view', $this->page_data);
		}
		else
		{
			$this->page_data['error_title'] = "Application Not Found";
			$this->page_data['error_message'] = "That application was not found in the database.";
			$this->render_page('error', $this->page_data);
		}
	}
	
	public function paprefs()
	{
	
	}

}
