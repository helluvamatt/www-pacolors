<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('TILE_BG', 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAAAAAA6mKC9AAAAGElEQVQYV2N4DwX/oYBhgARgDJjEAAkAAEC99wFuu0VFAAAAAElFTkSuQmCC');

class Colors extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('color_model');
		$this->title = "";
		$this->data['active'] = "colors";
	}
	
	public function index()
	{
		$page_data['color_list'] = $this->color_model->get_list();
		$this->render_page('colors/list', $page_data);
	}
	
	public function edit($id = 0)
	{
		$this->load->library('form_validation');
		$this->load->helper('form');
		$page_data = array();
		
		// Get list of color types
		$page_data['color_types'] = $this->color_model->get_color_types();
		
		// TODO Validation rules
		
		if ($this->form_validation->run())
		{
			// TODO Save to database
		}
		else
		{
			// TODO Error?
		}
		
		if ($id > 0)
		{
			$this->title = "Edit Color Setting";
			$page_data['color'] = $this->color_model->get_color($id);
		}
		else
		{
			$this->title = "Add Color Setting";
		}
		
		$this->render_page('colors/edit', $page_data);
	}
	
	public function view($id)
	{
		$this->title = "View Color Details";
		$page_data['color'] = $this->color_model->get_setting($id);
		if (isset($page_data['color']))
		{
			$this->render_page('colors/view', $page_data);
		}
		else
		{
			$page_data['error_title'] = "Color Setting Not Found";
			$page_data['error_message'] = "That color setting was not found.";
			$this->render_page('error', $page_data);
		}
		
	}
	
	public function paprefs()
	{
	
	}

}
