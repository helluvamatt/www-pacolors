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
	
	public function render($id)
	{
		$color = $this->color_model->get_setting($id);
		if (isset($color))
		{
			$image = imagecreatetruecolor(320, 120);
			imagesavealpha($image, true);
		
			$bg = imagecreatefromstring(base64_decode(TILE_BG));
			imagesettile($image, $bg);
			imagefill($image, 0, 0, IMG_COLOR_TILED);
			
			//$back_icon = imagecreatefrompng(APPPATH . 'assets/ic_back.png');
			
			// Draw status bar with correct color
			$image_color_status_bg = $this->create_gd_color($image, $color->get_color_status_bg(), 1);
			imagefilledrectangle($image, 0, 0, 319, 24, $image_color_status_bg);
			
			// TODO Draw icons on status bar with correct color
			
			
			// Draw navbar with correct color
			$image_color_navbar_bg = $this->create_gd_color($image, $color->get_color_navbar_bg(), 2);
			imagefilledrectangle($image, 0, 71, 319, 119, $image_color_navbar_bg);
			
			// TODO Draw nav icons with correct color
			
			// TODO Draw touch glow on home icon with correct color
			
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
	
	private function create_gd_color($image, $color, $debug_slot = 0)
	{
		$comp = Color_Object::components($color, TRUE);
		return imagecolorallocatealpha($image, $comp['r'], $comp['g'], $comp['b'], Color_Object::alpha_to_gd($comp['a']));
	}
	
	public function integer_test()
	{
		echo "<pre>\n";
		$red = rand(0, 0xFF);
		$green = rand(0, 0xFF);
		$blue = rand(0, 0xFF);
		var_dump(array('r' => $red, 'g' => $green, 'b' => $blue));
		
		echo "\n\n";
		
		for($i = 0; $i <= 0xFF; $i++)
		{
			$this->color_dump(Color_Object::build_color($red, $green, $blue, $i));
		}
		
		echo "</pre>\n";
	}
	
	private function color_dump($color)
	{
		$comp = Color_Object::components($color, TRUE);
		printf("%08X | %02X %02X %02X %02X\n", $color, $comp['a'], $comp['r'], $comp['g'], $comp['b']);
	}
	
	public function test_color($id)
	{
		echo "<pre>\n";
		$color = $this->color_model->get_setting($id);
		if (isset($color))
		{
			echo "Navbar BG:\n";
			$comp = Color_Object::components($color->color_navbar_bg, true);
			var_dump($comp);
			
			echo "Navbar FG:\n";
			$comp1 = Color_Object::components($color->color_navbar_fg, true);
			var_dump($comp1);
			
			echo "Navbar Glow:\n";
			$comp2 = Color_Object::components($color->color_navbar_gl, true);
			var_dump($comp2);
			
			echo "Statusbar BG:\n";
			$comp3 = Color_Object::components($color->color_status_bg, true);
			var_dump($comp3);
			
			echo "Statusbar FG:\n";
			$comp4 = Color_Object::components($color->color_status_fg, true);
			var_dump($comp4);
		}
		else
		{
			echo "Not found.\n";
		}
		echo "</pre>\n";
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
