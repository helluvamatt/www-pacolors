<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'db_model.php';

define('DEFAULT_COLOR_NAVBAR_BG', 0xFF000000);
define('DEFAULT_COLOR_NAVBAR_FG', 0xB2FFFFFF);
define('DEFAULT_COLOR_NAVBAR_GL', 0xFFFFFFFF);
define('DEFAULT_COLOR_STATUS_BG', 0xFF000000);
define('DEFAULT_COLOR_STATUS_FG', 0xFF33B5E5);

define('SQL_COLORS', 'SELECT colors.*, applications.display_name AS app_name, applications.package_name AS app_package FROM colors LEFT JOIN applications ON applications.id = colors.appid WHERE colors.enabled');

class Color_model extends Db_model
{

	public function get_setting($id)
	{
		$result = $this->db->query(SQL_COLORS . ' AND colors.id = ?', array($id));
		return $result->num_rows() > 0 ? $result->row(0, 'Color_Object') : null;
	}
	
	public function get_list($and_where = '', $parms = null)
	{
		$result = $this->db->query(SQL_COLORS . $and_where, $parms);
		return $result->result('Color_Object');
	}
	
	public function get_list_for_application($appid)
	{
		return $this->get_list(' AND colors.appid = ?', array($appid));
	}
	
	public function get_list_for_user($userid)
	{
		return $this->get_list(' AND colors.userid = ?', array($userid));
	}

}

class Color_Object extends DB_Object
{
	public $userid;
	public $appid;
	public $app_package;
	public $app_name;
	public $color_navbar_bg;
	public $color_navbar_fg;
	public $color_navbar_gl;
	public $color_status_bg;
	public $color_status_fg;
	
	public function get_color_navbar_bg() {return isset($this->color_navbar_bg) ? $this->color_navbar_bg : DEFAULT_COLOR_NAVBAR_BG;}
	public function get_color_navbar_fg() {return isset($this->color_navbar_fg) ? $this->color_navbar_fg : DEFAULT_COLOR_NAVBAR_FG;}
	public function get_color_navbar_gl() {return isset($this->color_navbar_gl) ? $this->color_navbar_gl : DEFAULT_COLOR_NAVBAR_GL;}
	public function get_color_status_bg() {return isset($this->color_status_bg) ? $this->color_status_bg : DEFAULT_COLOR_STATUS_BG;}
	public function get_color_status_fg() {return isset($this->color_status_fg) ? $this->color_status_fg : DEFAULT_COLOR_STATUS_FG;}
	
	public static function format_color_string($color, $include_hash = TRUE)
	{
		return ( ($include_hash ? '#' : '') . sprintf('%08X', $color) );
	}
	
	public static function parse_color_string($color_str)
	{
		return hexdec($color_str);
	}
	
	/**
	 * Get the components of a color into an array:
	 *  Alpha, Red, Green, Blue
	 *
	 * Usage:
	 *    list($alpha, $red, $green, $blue) = Color_Object::components($obj->color);
	 */
	public static function components($color, $assoc = false)
	{
		return
			(isset($assoc) && $assoc) ? array(
				'a' => ($color >> 24) & 0xFF, // Alpha (I'm going to hell for this)
				'r' => ($color >> 16) & 0xFF, // Red
				'g' => ($color >> 8 ) & 0xFF, // Green
				'b' => ($color      ) & 0xFF  // Blue
			) : array(
				($color >> 24) & 0xFF, // Alpha (I'm going to hell for this)
				($color >> 16) & 0xFF, // Red
				($color >> 8 ) & 0xFF, // Green
				($color      ) & 0xFF  // Blue
			);
	}
	
	public static function alpha_to_gd($alpha)
	{
		return 0x7F - ($alpha >> 1) & 0x7F;
	}
	
	public static function build_color($red, $green, $blue, $alpha = 0xFF)
	{
		$alpha = $alpha << 24;
		$red = $red << 16;
		$green = $green << 8;
		return $alpha | $red | $green | $blue;
	}
	
}


