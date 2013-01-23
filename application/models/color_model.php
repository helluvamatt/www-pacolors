<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'db_model.php';

define('SQL_APP_LIST', 'SELECT colors.*, applications.display_name AS app_name, applications.package_name AS app_package FROM colors LEFT JOIN applications ON applications.id = colors.appid');

class Color_model extends DB_Model
{

	public function get_setting($id)
	{
		// TODO Get details for color setting
	}
	
	public function get_list()
	{
		$result = $this->db->query(SQL_APP_LIST);
		return $result->result();
	}
	
	public function get_list_for_application($appid)
	{
		$result = $this->db->query(SQL_APP_LIST . ' WHERE appid = ?', array($appid));
		return $result->result();
	}
}
