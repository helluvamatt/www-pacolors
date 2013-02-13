<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('TILE_BG', 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAAAAAA6mKC9AAAAGElEQVQYV2N4DwX/oYBhgARgDJjEAAkAAEC99wFuu0VFAAAAAElFTkSuQmCC');

class Render extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('color_model');
	}
	
	public function color($color)
	{
		$image = imagecreatetruecolor(32, 32);
		imagesavealpha($image, true);
		list($alpha, $red, $green, $blue) = Color_Object::components($color);
		
		if ($alpha < 0xFF)
		{
			$bg = imagecreatefromstring(base64_decode(TILE_BG));
			imagesettile($image, $bg);
			imagefill($image, 0, 0, IMG_COLOR_TILED);
		}
		
		$fg = imagecolorallocatealpha($image, $red, $green, $blue, Color_Object::alpha_to_gd($alpha));
		imagefilledrectangle($image, 0, 0, 31, 31, $fg);
		
		header('Content-Type: image/png');
		imagepng($image);
		imagedestroy($image);
	}
	
	public function preview($id)
	{
		$color = $this->color_model->get_setting_by_id($id);
		if (isset($color))
		{
			$this->render($color);
		}
		else
		{
			header('HTTP/1.0 404 Not Found');
		}
		die();
		exit;
	}

	public function live()
	{
		$color_obj = new Color_Object();
		$color_obj->color_navbar_bg = isset($_REQUEST['navbar_bg']) ? hexdec($_REQUEST['navbar_bg']) : NULL;
		$color_obj->color_navbar_fg = isset($_REQUEST['navbar_fg']) ? hexdec($_REQUEST['navbar_fg']) : NULL;
		$color_obj->color_navbar_gl = isset($_REQUEST['navbar_gl']) ? hexdec($_REQUEST['navbar_gl']) : NULL;
		$color_obj->color_status_bg = isset($_REQUEST['status_bg']) ? hexdec($_REQUEST['status_bg']) : NULL;
		$color_obj->color_status_fg = isset($_REQUEST['status_fg']) ? hexdec($_REQUEST['status_fg']) : NULL;
		$this->render($color_obj);
	}
	
	private function render($color_obj)
	{
		$image = imagecreatetruecolor(720, 240);
		imagesavealpha($image, true);
	
		$bg = imagecreatefromstring(base64_decode(TILE_BG));
		imagesettile($image, $bg);
		imagefill($image, 0, 0, IMG_COLOR_TILED);
		
		$this->render_statusbar($image, $color_obj);
		
		$this->render_navbar($image, $color_obj);
		
		header('Content-Type: image/png');
		imagepng($image);
		imagedestroy($image);
	}
	
	private function render_statusbar($image, $colors_obj)
	{
		// Some dimens
		$sx = imagesx($image);
		$sy = imagesy($image);
	
		// Draw status bar with correct color
		$image_color_status_bg = $this->create_gd_color($image, $colors_obj->get_color_status_bg());
		imagefilledrectangle($image, 0, 0, $sx - 1, 42, $image_color_status_bg);
		
		// Draw icons on status bar with correct color
		$sgnl_icon = imagecreatefrompng(APPPATH . 'assets/stat_sys_sgnl.png');
		$this->apply_color($sgnl_icon, $colors_obj->get_color_status_fg());
		imagecopy($image, $sgnl_icon, $sx - 180, 3, 0, 0, imagesx($sgnl_icon), imagesy($sgnl_icon));
		
		$data_icon = imagecreatefrompng(APPPATH . 'assets/stat_sys_data_4g.png');
		$this->apply_color($data_icon, $colors_obj->get_color_status_fg());
		imagecopy($image, $data_icon, $sx - 180, 3, 0, 0, imagesx($data_icon), imagesy($data_icon));
		
		$batt_icon = imagecreatefrompng(APPPATH . 'assets/stat_sys_batt.png');
		$this->apply_color($batt_icon, $colors_obj->get_color_status_fg());
		imagecopy($image, $batt_icon, $sx - 135, 3, 0, 0, imagesx($batt_icon), imagesy($batt_icon));
		
		// Draw clock
		$image_color_status_fg = $this->create_gd_color($image, $colors_obj->get_color_status_fg());
		imagettftext($image, 24, 0, $sx - 100, 34, $image_color_status_fg, APPPATH . 'assets/droidsans.ttf', date("H:i", time()));
	}
	
	private function apply_color($icon, $color)
	{		
		// We have to simulate porter duff SRC_IN mode:
		// SrcAlpha * DestAlpha
		// SrcAlpha * DestColor
		
		$num_colors = imagecolorstotal($icon);
		$dest_color = Color_Object::components($color, TRUE);
		
		for ($i = 0; $i < $num_colors; $i++)
		{
			$existing = imagecolorsforindex($icon, $i);
			
			// Get range from 0.0 (transparent) to 1.0 (opaque)
			$src_alpha = (0x7f - $existing['alpha']) / 0x7F;
			
			$red   = $dest_color['r'] * $src_alpha;
			$green = $dest_color['g'] * $src_alpha;
			$blue  = $dest_color['b'] * $src_alpha;
			$alpha = $dest_color['a'] * $src_alpha;
			
			imagecolorset($icon, $i, $red, $green, $blue, Color_Object::alpha_to_gd($alpha));
		}
		
	}
	
	private function render_navbar($image, $colors_obj)
	{
		// Some dimens
		$sx = imagesx($image);
		$sy = imagesy($image);
		$centre = $sx / 2;
	
		// Draw navbar with correct color
		$image_color_navbar_bg = $this->create_gd_color($image, $colors_obj->get_color_navbar_bg());
		imagefilledrectangle($image, 0, $sy - 84, $sx - 1, $sy - 1, $image_color_navbar_bg);
		
		// Draw nav icons with correct color
		$nav_home_icon = imagecreatefrompng(APPPATH . 'assets/ic_sysbar_home.png');
		$this->apply_navbar_color($nav_home_icon, $colors_obj->get_color_navbar_fg());
		$home_icon_sx = imagesx($nav_home_icon);
		$home_icon_sy = imagesy($nav_home_icon);
		imagecopy(
				$image, $nav_home_icon,				// image destination and source
				$centre - ($home_icon_sx / 2),		// destination x
				$sy - ($home_icon_sy - 24),			// destination y
				0,									// source x
				9,									// source y
				$home_icon_sx,						// source width (sx)
				$home_icon_sy - 24					// source height (sy)
		);
		
		$nav_back_icon = imagecreatefrompng(APPPATH . 'assets/ic_sysbar_back.png');
		$this->apply_navbar_color($nav_back_icon, $colors_obj->get_color_navbar_fg());
		$back_icon_sx = imagesx($nav_back_icon);
		$back_icon_sy = imagesy($nav_back_icon);
		imagecopy(
				$image, $nav_back_icon,				// image destination and source
				$centre - $back_icon_sx - ($back_icon_sx / 2),	// destination x
				$sy - ($back_icon_sy - 24),			// destination y
				0,									// source x
				9,									// source y
				$back_icon_sx,						// source width (sx)
				$back_icon_sy - 24					// source height (sy)
		);
		
		$nav_rcnt_icon = imagecreatefrompng(APPPATH . 'assets/ic_sysbar_recent.png');
		$this->apply_navbar_color($nav_rcnt_icon, $colors_obj->get_color_navbar_fg());
		$rcnt_icon_sx = imagesx($nav_rcnt_icon);
		$rcnt_icon_sy = imagesy($nav_rcnt_icon);
		imagecopy(
				$image, $nav_rcnt_icon,				// image destination and source
				$centre + $rcnt_icon_sx - ($rcnt_icon_sx / 2),	// destination x
				$sy - ($rcnt_icon_sy - 24),			// destination y
				0,									// source x
				9,									// source y
				$rcnt_icon_sx,						// source width (sx)
				$rcnt_icon_sy - 24					// source height (sy)
		);
		
		$nav_highlight = imagecreatefrompng(APPPATH . 'assets/ic_sysbar_highlight.png');
		$this->apply_navbar_color($nav_highlight, $colors_obj->get_color_navbar_gl());
		$nav_highlight_sx = imagesx($nav_highlight);
		$nav_highlight_sy = imagesy($nav_highlight);
		imagecopyresampled(
				$image, $nav_highlight,				// image destination and source
				$centre - ($nav_highlight_sx / 2),	// destination x
				$sy - ($nav_highlight_sy - 86),		// destination y
				0,									// source x
				43,									// source y
				$nav_highlight_sx,					// destination width sx
				$nav_highlight_sy,					// destination height sy
				$nav_highlight_sx,					// source width (sx)
				$nav_highlight_sy					// source height (sy)
		);
		
	}
	
	private function apply_navbar_color($icon, $color)
	{
		$num_colors = imagecolorstotal($icon);
		$comp = Color_Object::components($color, TRUE);
		for ($i = 0; $i < $num_colors; $i++)
		{
			$existing = imagecolorsforindex($icon, $i);
			$red   = $existing['red']   & $comp['r'];
			$green = $existing['green'] & $comp['g'];
			$blue  = $existing['blue']  & $comp['b'];
			$factor = $comp['a'] / 0xFF;
			$alpha = (127 - $existing['alpha']) * $factor;
			imagecolorset($icon, $i, $red, $green, $blue, 127 - $alpha);
		}
	}
	
	private function create_gd_color($image, $color)
	{
		$comp = Color_Object::components($color, TRUE);
		return imagecolorallocatealpha($image, $comp['r'], $comp['g'], $comp['b'], Color_Object::alpha_to_gd($comp['a']));
	}
	
	/*****************************************************************************************
	 * TESTING FUNCTIONS
	 *****************************************************************************************/
	
	/* Nothing */
	
}