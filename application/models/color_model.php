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
CASE WHEN users.enabled THEN 1 ELSE 0 END AS user_enabled,
(SELECT count(*) FROM votes WHERE votes.enabled AND votes.colorid = colors.id) AS votes,
(SELECT count(*) FROM votes WHERE votes.enabled AND votes.colorid = colors.id AND votes.userid = ?) AS user_voted
FROM colors
LEFT JOIN applications ON applications.id = colors.appid
LEFT JOIN users ON users.id = colors.userid');
define('SQL_COLORS_DEFAULT_WHERE', 'WHERE colors.enabled AND applications.enabled AND users.enabled');
define('SQL_COLORS_ORDERBY', 'ORDER BY colors.created, colors.id');

class Color_model extends Db_model
{

	public function get_setting_by_id($userid, $id)
	{
		return $this->get_setting($userid, array('colors.id' => $id));
	}
	
	private function get_setting($userid, $params)
	{
		$sql = SQL_COLORS . ' ' . SQL_COLORS_DEFAULT_WHERE;
		$parms = array();
		$parms[] = $userid;
		foreach ($params as $param => $value)
		{
			$sql .= " AND " . $param . " = ?";
			$parms[] = $value;
		}
		$result = $this->db->query($sql, $parms);
		return $result->num_rows() > 0 ? $result->row(0, 'Color_Object') : null;
	}

	public function get_list($userid, $count = 0, $page = 0, $where = SQL_COLORS_DEFAULT_WHERE, $parms = array())
	{
		$sql = SQL_COLORS . ' ' . $where;
		$params = array();
		$params[] = $userid;
		if ($count > 0)
		{
			$sql .= " LIMIT ? OFFSET ?";
			$params[] = $count; // LIMIT $count
			$params[] = $page * $count; // OFFSET $page*$count
		}
		$sql .= ' ' . SQL_COLORS_ORDERBY;
		$result = $this->db->query($sql, array_merge($params, $parms));
		return $result->result('Color_Object');
	}
	
	public function get_list_for_application($userid, $appid, $count = 0, $page = 0)
	{
		return $this->get_list($userid, $count, $page, SQL_COLORS_DEFAULT_WHERE . ' AND colors.appid = ?', array($appid));
	}
	
	public function get_list_for_user($userid, $id, $count = 0, $page = 0)
	{
		return $this->get_list($userid, $count, $page, SQL_COLORS_DEFAULT_WHERE . ' AND colors.userid = ?', array($id));
	}
	
	public function get_list_favorites($userid, $count = 0, $page = 0)
	{
		return $this->get_list($userid, $count, $page, SQL_COLORS_DEFAULT_WHERE . ' AND colors.id IN (SELECT votes.colorid FROM votes WHERE votes.enabled AND votes.userid = ? )', array($userid));
	}

	/* --------------------------------------------------------------------- */
	/* Voting System Functions                                               */
	/* --------------------------------------------------------------------- */
	public function cast_vote($userid, $colorid)
	{
		$query = $this->db->query("SELECT cast_vote(?, ?)", array($userid, $colorid));
		$result = $query->row_array();
		return $result['cast_vote'] == 't';
	}
	
	public function check_vote($userid, $colorid)
	{
		$this->db->where('userid', $userid);
		$this->db->where('colorid', $colorid);
		$this->db->where('enabled', TRUE);
		return $this->db->count_all_results('votes') == 1;
	}
	
	public function count_votes_for_setting($colorid)
	{
		$this->db->where(array('enabled' => 'TRUE', 'colorid' => $colorid));
		return $this->db->count_all_results('votes');
	}
	
	public function get_votes_for_setting($colorid)
	{
		$this->db->where(array('colorid' => $colorid));
		return $this->db->get('votes');
	}
	
	public function get_votes_by_user($userid)
	{
		$this->db->where('userid', $userid);
		return $this->db->get('votes');
	}
	
	/* --------------------------------------------------------------------- */
	/* Color Settings Management Functions                                   */
	/* --------------------------------------------------------------------- */
	public function get_manage_list($count = 0, $page = 0)
	{
		return $this->get_list(0, $count, $page, '', array());
	}
	
	public function toggle_color($id)
	{
		$query = "UPDATE colors SET enabled = NOT enabled WHERE id = ?";
		return $this->db->query($query, array($id));
	}
	
	/* --------------------------------------------------------------------- */
	/* Color Settings Save and Delete Functions                              */
	/* --------------------------------------------------------------------- */
	public function hide_color($id)
	{
		$query = "UPDATE colors SET enabled = FALSE WHERE id = ?";
		return $this->db->query($query, array($id));
	}
	
	public function save_color($color_obj)
	{
		$this->db->set('userid', $color_obj->userid);
		$this->db->set('appid', $color_obj->appid);
		// XXX This is PostgreSQL specific!!
		if (isset($color_obj->color_navbar_bg)) $this->db->set('color_navbar_bg', "x'" . dechex($color_obj->color_navbar_bg) . "'::int", FALSE);
		if (isset($color_obj->color_navbar_fg)) $this->db->set('color_navbar_fg', "x'" . dechex($color_obj->color_navbar_fg) . "'::int", FALSE);
		if (isset($color_obj->color_navbar_gl)) $this->db->set('color_navbar_gl', "x'" . dechex($color_obj->color_navbar_gl) . "'::int", FALSE);
		if (isset($color_obj->color_status_bg)) $this->db->set('color_status_bg', "x'" . dechex($color_obj->color_status_bg) . "'::int", FALSE);
		if (isset($color_obj->color_status_fg)) $this->db->set('color_status_fg', "x'" . dechex($color_obj->color_status_fg) . "'::int", FALSE);
		if ($color_obj->id > 0)
		{
			// Active record update
			$this->db->where('id', $color_obj->id);
			$this->db->update('colors');
			return $color_obj->id;
		}
		else
		{
			// Active record insert 
			$this->db->insert('colors');
			return $this->db->insert_id();
		}
	}

}

class Color_Object extends DB_Object
{
	public function __construct()
	{
		$this->id = 0;
	}

	public $userid;
	public $user_name;
	public $user_enabled;
	
	public $appid;
	public $app_package;
	public $app_name;
	public $app_enabled;
	
	public $votes;
	public $user_voted;
	
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
		if ($color_str === FALSE) return NULL;
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
	
	public static function color_to_css($color, $include_alpha = TRUE)
	{
		list($alpha, $red, $green, $blue) = Color_Object::components($color);
		return $include_alpha ? ("rgba(" . $red . "," . $green . "," . $blue . "," . ($alpha / 0xFF) . ")") : sprintf("#%02x%02x%02x", $red, $green, $blue);
	}
	
}


