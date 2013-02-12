<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('TILE_BG', 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAAAAAA6mKC9AAAAGElEQVQYV2N4DwX/oYBhgARgDJjEAAkAAEC99wFuu0VFAAAAAElFTkSuQmCC');

class Colors extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('color_model');
		$this->title = "";
		$this->active = "colors";
	}
	
	public function index()
	{
		$page_data['color_list'] = $this->color_model->get_list();
		$this->render_page('colors/list', $page_data);
	}
	
	public function edit($id = 0)
	{
		$this->load->helper('form');
		$page_data = array();
		
		if ($id > 0)
		{
			$this->title = "Edit Color Setting";
			$page_data['color'] = $this->color_model->get_setting_by_id($id);
		}
		else
		{
			$this->title = "Add Color Setting";
			$page_data['color'] = new Color_Object();
		}
		
		$this->render_page('colors/edit', $page_data);
	}
	
	public function save()
	{
		$this->load->library('form_validation');
		$this->load->helper('url');
	
		// Validation rules
		$this->form_validation->set_rules('color_navbar_bg', '', 'required');
		$this->form_validation->set_rules('color_navbar_fg', '', 'required');
		$this->form_validation->set_rules('color_navbar_gl', '', 'required');
		$this->form_validation->set_rules('color_status_bg', '', 'required');
		$this->form_validation->set_rules('color_status_fg', '', 'required');
		
		if ($this->form_validation->run())
		{
			// TODO Save to database
			echo "<pre>\n";
			var_dump($_POST);
			echo "</pre>\n";
		}
		
		$id = $_POST['id'];
		redirect('/colors/edit/' . $id);
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
