<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Colors extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('color');
		$this->data['title'] = "";
		$this->data['active'] = "colors";
	}
	
	public function index()
	{
		$this->page_data['color_list'] = $this->color->get_list();
		$this->render_page('colors/list', $this->page_data);
	}
	
	public function edit($id = 0)
	{
		$this->load->library('form_validation');
		
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
			$this->page_data['title'] = "Edit Color Setting";
			$this->page_data['color'] = $this->color->get_color($id);
		}
		else
		{
			$this->page_data['title'] = "Add Color Setting";
		}
		
		$this->render_page('colors/edit', $this->page_data);
	}
	
	public function view($id)
	{
		$this->page_data['title'] = "View Color Details";
		$this->page_data['color'] = $this->color->get_color($id);
		if (isset($this->page_data['color']))
		{
			$this->render_page('colors/view', $this->page_data);
		}
		else
		{
			$this->page_data['error_title'] = "Color Setting Not Found";
			$this->page_data['error_message'] = "That color setting was not found.";
			$this->render_page('error', $this->page_data);
		}
		
	}
	
	public function paprefs()
	{
	
	}

}
