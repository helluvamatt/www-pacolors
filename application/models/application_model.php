<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'db_model.php';

define('SQL_APPS', 'SELECT apps.id, apps.package_name, apps.display_name, CASE WHEN apps.enabled THEN 1 ELSE 0 END AS enabled, (SELECT COUNT(*) FROM colors AS cs WHERE cs.appid = apps.id AND cs.enabled) AS cs_count FROM applications AS apps');
define('SQL_APPS_DEFAULT_WHERE', 'WHERE apps.enabled');
define('SQL_APPS_ORDERBY', 'ORDER BY apps.display_name');

define('OPTIONS_APPS_NO_WILDCARD_AFTER', 1);
define('OPTIONS_APPS_NO_WILDCARD_BEFORE', 2);

class Application_model extends DB_Model
{
	public function get_application_by_id($id)
	{
		return $this->get_application(array('apps.id' => $id));
	}
	
	public function get_application_by_package($package)
	{
		return $this->get_application(array('apps.package_name' => $package));
	}
	
	private function get_application($params)
	{
		$sql = SQL_APPS . ' ' . SQL_APPS_DEFAULT_WHERE;
		$parms = array();
		foreach ($params as $param => $value)
		{
			$sql .= " AND " . $param . " = ?";
			$parms[] = $value;
		}
		$result = $this->db->query($sql, $parms);
		return $result->num_rows() > 0 ? $result->row(0, 'Application_Object') : null;
	}
	
	public function get_list($count = 0, $start = 0, $where = SQL_APPS_DEFAULT_WHERE, $args = array())
	{
		$sql = SQL_APPS . ' ' . $where . ' ' . SQL_APPS_ORDERBY;
		if ($count > 0)
		{
			$sql .= ' LIMIT ' . $count . ' OFFSET ' . $start;
		}
		$result = $this->db->query($sql, $args);
		return $result->result('Application_Object');
	}

	public function search($q, $options = 0, $count = 0, $start = 0)
	{
		$prefix = ($options & OPTIONS_APPS_NO_WILDCARD_BEFORE == OPTIONS_APPS_NO_WILDCARD_BEFORE) ? '' : '%';
		$suffix = ($options & OPTIONS_APPS_NO_WILDCARD_AFTER == OPTIONS_APPS_NO_WILDCARD_AFTER) ? '' : '%';
		$where = SQL_APPS_DEFAULT_WHERE . ' AND LOWER(apps.display_name) LIKE ?';
		return $this->get_list($count, $start, $where, array($prefix . $q . $suffix));
	}
	
	// If $id is NULL, create a new application record, and return it's ID, otherwise, attempt to update the existing record, which will return the same id on success, NULL on failure
	/*
	public function save_application($package_name, $display_name, $id = NULL)
	{
		// TODO
	}
	*/

	/* --------------------------------------------------------------------- */
	/* Application Management Functions                                      */
	/* --------------------------------------------------------------------- */
	public function get_manage_list($count = 0, $start = 0)
	{
		return $this->get_list($count, $start, '', array());
	}
	
	public function toggle_application($id)
	{
		$query = "UPDATE applications SET enabled = NOT enabled WHERE id = ?";
		return $this->db->query($query, array($id));
	}
	
}

class Application_Object extends DB_Object
{
	public $display_name;
	public $package_name;
	public $cs_count;
}