<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'models/db_model.php';

define('SQL_USER', 'SELECT id, username, email, realname_first, realname_last, created, CASE WHEN enabled THEN 1 ELSE 0 END AS enabled FROM users');

class User_model extends DB_Model
{

	public function get_user($id)
	{
		$result = $this->db->query(SQL_USER . ' WHERE id = ?', array($id));
		return ($result->num_rows() > 0) ? $result->row(0, 'User_Object') : null;
	}
	
	public function has_role($userid, $role)
	{
		$result = $this->db->query("SELECT (map.userid, '_', roles.id) AS authid FROM role_map AS map INNER JOIN roles ON roles.id = map.role_id WHERE map.userid = ? AND roles.rolename = ?", array($userid, $role));
		return $result->num_rows() == 1;
	}

	// OnLogin: Returns user id ( > 0) on success, 0 = invalid user, bad password or user not enabled
	public function verify($username, $password)
	{
		$result = $this->db->query("SELECT id, password, enabled FROM users WHERE username = ?", array($username));
		if ($result->num_rows() != 1)
		{
			return 0;
		}
		
		$user = $result->row();
		// Check
		if ($user->enabled && crypt($password, $user->password) == $user->password)
		{
			return $user->id;
		}

		return 0;
	}
	
	public function add_user($password)
	{
		return $this->create_hash($password);
	}

	private function create_hash($password)
	{
		$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
		$length = 16;
		$alg = '$6$rounds=100000$'; # SHA-512 with 100,000 rounds
		
		$salt = '';
		for($i = 0; $i <= $length; $i ++)
		{
			$alphabet = str_shuffle ( $alphabet );
			$salt .= $alphabet[openssl_random_pseudo_bytes(1) % 64];
		}
		return crypt($password, $alg . $salt . '$');
	}
	
	/* ---------------------------------------------------------------------- */
	/* User Management Functions                                              */
	/* ---------------------------------------------------------------------- */
	
	public function get_users($count = 0, $page = 0)
	{
		$query = SQL_USER . " ORDER BY username";
		$args = array();
		if ($count > 0)
		{
			$offset = $page * $count;
			$args = array($count, $offset);
			$query .= " LIMIT ? OFFSET ?";
		}
		$result = $this->db->query($query, $args);
		return $result->result('User_Object');
	}
	
	public function toggle_user($id)
	{
		$query = "UPDATE users SET enabled = NOT enabled WHERE id = ?";
		return $this->db->query($query, array($id));
	}
}

class User_Object extends DB_Object
{
	public $username;
	public $email;
	public $realname_first;
	public $realname_last;
	public $created;
	
	public function get_display_name()
	{
		if (isset($this->realname_first) && $this->realname_first != '' && isset($this->realname_last) && $this->realname_last != '')
		{
			return $this->realname_first . ' ' . $this->realname_last;
		}
		else
		{
			return $this->username;
		}
	}
}
