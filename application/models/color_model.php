<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'db_model.php';

define('DEFAULT_COLOR_NAVBAR_BG', 0xFF000000);
define('DEFAULT_COLOR_NAVBAR_FG', 0xB2FFFFFF);
define('DEFAULT_COLOR_NAVBAR_GL', 0xFFFFFFFF);
define('DEFAULT_COLOR_STATUS_BG', 0xFF000000);
define('DEFAULT_COLOR_STATUS_FG', 0xFF33B5E5);

define('SQL_COLORS', 'SELECT
colors.id,
colors.userid,
colors.appid,
colors.color_navbar_bg,
colors.color_navbar_fg,
colors.color_navbar_gl,
colors.color_status_bg,
colors.color_status_fg,
CASE WHEN colors.enabled THEN 1 ELSE 0 END AS enabled,
applications.display_name AS app_name,
applications.package_name AS app_package,
CASE WHEN applications.enabled THEN 1 ELSE 0 END AS app_enabled,
users.username AS user_name,
CASE WHEN users.enabled THEN 1 ELSE 0 END AS user_enabled
FROM colors
LEFT JOIN applications ON applications.id = colors.appid
LEFT JOIN users ON users.id = colors.userid
');
define('SQL_COLORS_DEFAULT_WHERE', 'WHERE colors.enabled AND applications.enabled AND users.enabled');
define('SQL_COLORS_ORDERBY', 'ORDER BY colors.created, colors.id');

class Color_model extends Db_model
{

	public function get_setting_by_id($id)
	{
		return $this->get_setting(array('colors.id' => $id));
	}
	
	private function get_setting($params)
	{
		$sql = SQL_COLORS . ' ' . SQL_COLORS_DEFAULT_WHERE;
		$parms = array();
		foreach ($params as $param => $value)
		{
			$sql .= " AND " . $param . " = ?";
			$parms[] = $value;
		}
		$result = $this->db->query($sql, $parms);
		return $result->num_rows() > 0 ? $result->row(0, 'Color_Object') : null;
	}

	public function get_list($count = 0, $page = 0, $where = SQL_COLORS_DEFAULT_WHERE, $params = array())
	{
		$sql = SQL_COLORS . ' ' . $where;
		if ($count > 0)
		{
			$sql .= " LIMIT ? OFFSET ?";
			$params[] = $count; // LIMIT $count
			$params[] = $page * $count; // OFFSET $page*$count
		}
		$sql .= ' ' . SQL_COLORS_ORDERBY;
		$result = $this->db->query($sql, $params);
		return $result->result('Color_Object');
	}
	
	public function get_list_for_application($appid, $count = 0, $page = 0)
	{
		return $this->get_list($count, $page, SQL_COLORS_DEFAULT_WHERE . ' AND colors.appid = ?', array($appid));
	}
	
	public function get_list_for_user($userid, $count = 0, $page = 0)
	{
		return $this->get_list($count, $page, SQL_COLORS_DEFAULT_WHERE . ' AND colors.userid = ?', array($userid));
	}
	
	/* --------------------------------------------------------------------- */
	/* Color Settings Management Functions                                   */
	/* --------------------------------------------------------------------- */
	public function get_manage_list($count = 0, $page = 0)
	{
		return $this->get_list($count, $page, '', array());
	}
	
	public function toggle_color($id)
	{
		$query = "UPDATE colors SET enabled = NOT enabled WHERE id = ?";
		return $this->db->query($query, array($id));
	}

}

class Color_Object extends DB_Object
{
	public $userid;
	public $user_name;
	public $user_enabled;
	
	public $appid;
	public $app_package;
	public $app_name;
	public $app_enabled;
	
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
	
	public function is_enabled()
	{
		return $this->enabled && $this->user_enabled && $this->app_enabled;
	}
	
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


