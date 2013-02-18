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
		if (isset($this->user))		// Is user logged in?
		{
			$this->load->library('form_validation');
			$this->load->helper('form');
			$this->load->helper('url');
			
			$color = new Color_Object();
		
			// Validation rules
			$this->form_validation->set_rules('app_name', 'Application Name', 'required');
			$this->form_validation->set_rules('app_package', 'Application Package', 'required');
			$this->form_validation->set_rules('color_navbar_bg', '', 'required');
			$this->form_validation->set_rules('color_navbar_fg', '', 'required');
			$this->form_validation->set_rules('color_navbar_gl', '', 'required');
			$this->form_validation->set_rules('color_status_bg', '', 'required');
			$this->form_validation->set_rules('color_status_fg', '', 'required');
			
			// Run validation
			if ($this->form_validation->run())
			{
			
				// Build color object from post-data
				$color->app_package = $this->input->post('app_package');
				$color->app_name = $this->input->post('app_name');
				$color->color_navbar_bg = Color_Object::parse_color_string($this->input->post('color_navbar_bg'));
				$color->color_navbar_fg = Color_Object::parse_color_string($this->input->post('color_navbar_fg'));
				$color->color_navbar_gl = Color_Object::parse_color_string($this->input->post('color_navbar_gl'));
				$color->color_status_bg = Color_Object::parse_color_string($this->input->post('color_status_bg'));
				$color->color_status_fg = Color_Object::parse_color_string($this->input->post('color_status_fg'));
				$color->userid = $this->user->id;
				
				// Owner sanity checks
				$ex_id = filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT);
				$ex_color = $this->color_model->get_setting_by_id($ex_id);
				if (isset($ex_color)							// Is there even an existing color?
					&& $ex_color->userid == $this->user->id)	// Is the existing color owned by the logged in user?
				{
					$color->id = $ex_id;
				}
				else
				{
					// Anonymous or different user, only do an insert
					$color->id = 0;
				}
			
				// Save application
				$this->load->model('application_model');
				$color->appid = $this->application_model->get_or_create_application($color->app_package, $color->app_name);
			
				// Save to database
				$id = $this->color_model->save_color($color);
				
				// Set flashdata message
				$this->set_flashdata_message('success', "Color setting saved!");
				
			}
			
			if ($id > 0) $color = $this->color_model->get_setting_by_id($id);
			
			$this->title = ($id > 0) ? "Edit Color Setting" : "Add Color Setting";
			
			$page_data = array();
			$page_data['save_as'] = ( $id > 0 && $color->userid != $this->user->id );
			$page_data['color'] = $color;
			$this->render_page('colors/edit', $page_data);
		}
		else
		{
			$this->set_flashdata_message('error', "You do not have permission to do that.", 'colors');
		}
	}
	
	public function delete($id)
	{
		$color = $this->color_model->get_setting_by_id($id);
		if (isset($color))
		{
			if (isset($this->user) && $color->userid == $this->user->id)
			{
				$this->color_model->hide_color($id);
				$this->set_flashdata_message('success', "The color setting was deleted.", 'colors');
			}
			else
			{
				// No permissions
				$this->set_flashdata_message('error', "You do not have permission to delete color settings.", 'colors');
			}
		}
		else
		{
			// Invalid color setting
			$this->set_flashdata_message('error', "Invalid color setting.", 'colors');
		}
	}
	
	public function view($id)
	{
		$this->title = "View Color Details";
		$page_data['color'] = $this->color_model->get_setting_by_id($id);
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
