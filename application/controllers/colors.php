<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
	
	public function render($id)
	{
		$color = $this->color_model->get_setting($id);
		if (isset($color))
		{
			$image = imagecreatefrompng('');
			imagesavealpha($image, true);
			
			
			
			header('Content-Type: image/png');
			imagepng($image);
			imagedestroy($image);
		}
		else
		{
			header('HTTP/1.0 404 Not Found');
		}
		die();
		exit;
	}
	
	public function test_color_model()
	{
		echo "<pre>\n";
		
		// Color model
		echo "Testing Color_model...\n";
		
		// No tests
		echo "   No tests.\n";
		
		// Color_Object
		echo "Testing Color_Object...\n";
		
		$color_str = "#AF92A59B";
		var_dump($color_str);
		
		$color = Color_Object::parse_color_string($color_str);
		var_dump($color);
		
		$comp = Color_Object::components($color, true);
		var_dump($comp);
		
		$back_to_color = Color_Object::build_color($comp['r'], $comp['g'], $comp['b'], $comp['a']);
		var_dump($back_to_color);
		
		$back_to_string = Color_Object::format_color_string($back_to_color);
		var_dump($back_to_string);
		
		echo "</pre>\n";
		
		echo '<div style="background-color: ' . $back_to_string . '; padding: 5px;">' . $back_to_string . '</div>' . "\n";
	}
	
	public function paprefs()
	{
	
	}

}
