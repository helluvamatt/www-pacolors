<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'db_model.php';

class Application_model extends DB_Model
{
	public function get_application_by_id($id)
	{
		return $this->get_application(array('id' => $id, 'enabled' => 'true'));
	}
	
	public function get_application_by_package($package)
	{
		return $this->get_application(array('package_name' => $package, 'enabled' => 'true'));
	}
	
	public function get_list($count = -1, $start = 0)
	{
		$start = filter_var($start, FILTER_SANITIZE_NUMBER_INT);
		$count = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
		$sql = 'SELECT apps.id, apps.package_name, apps.display_name, count(cs.id) AS cs_count FROM applications AS apps LEFT JOIN colors AS cs ON cs.appid = apps.id GROUP BY apps.id ORDER BY apps.display_name';
		if ($count > 0)
		{
			$sql .= 'LIMIT ' . $count . ' OFFSET ' . $start;
		}
		return $this->get_application_list($sql);
	}
	
	public function search($q)
	{
		// TODO Not implemented yet
	}
	
	// If $id is NULL, create a new application record, and return it's ID, otherwise, attempt to update the existing record, which will return the same id on success, NULL on failure
	public function save_application($package_name, $display_name, $id = NULL)
	{
		// TODO
	}
	
	private function get_application($where)
	{
		$where = $this->process_where($where);
		$result = $this->db->query('SELECT id, package_name, display_name FROM applications ' . $where);
		if ($result->num_rows() > 0)
		{
			return $result->row();
		}
		else
		{
			return null;
		}
	}
	
	private function get_application_list($sql, $args = NULL)
	{
		$result = $this->db->query($sql, $args);
		return $result->result();
	}

}
