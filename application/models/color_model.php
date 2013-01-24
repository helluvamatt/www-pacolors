<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'db_model.php';

define('SQL_COLORS', 'SELECT * FROM colors WHERE enabled');

class Color_model extends DB_Model
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

}

class Color_Object extends DB_Object
{
	public $userid;
	public $appid;
	public $color_navbar_bg;
	public $color_navbar_fg;
	public $color_navbar_gl;
	public $color_status_bg;
	public $color_status_fg;
	
	public static function format_color_string($color)
	{
		return '#' . dechex($color);
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
				'a' => ($color & 0xFF000000) >> 24, // Alpha
				'r' => ($color & 0x00FF0000) >> 16, // Red
				'g' => ($color & 0x0000FF00) >> 8,  // Green
				'b' => ($color & 0x000000FF)        // Blue
			) : array(
				($color & 0xFF000000) >> 24, // Alpha
				($color & 0x00FF0000) >> 16, // Red
				($color & 0x0000FF00) >> 8,  // Green
				($color & 0x000000FF)        // Blue
			);
	}
	
	public static function build_color($red, $green, $blue, $alpha = 255)
	{
		$alpha = $alpha << 24;
		$red = $red << 16;
		$green = $green << 8;
		return $alpha | $red | $green | $blue;
	}
	
}


