<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'models/db_model.php';

define('SQL_TABLE_USER', 'SELECT id, username, email, realname_first, realname_last, created FROM users');

class User_model extends DB_Model
{

	public function get_user($id)
	{
		$result = $this->db->query(SQL_TABLE_USER . ' WHERE id = ?', array($id));
		return ($result->num_rows() > 0) ? $result->row(0, 'User_Object') : null;
	}
	
	public function has_any_permissions($userid, $permissions)
	{
		foreach ($permissions as $perm)
		{
			if ($this->has_permission($userid, $perm)) return true;
		}
		return false;
	}
	
	public function has_all_permissions($userid, $permissions)
	{
		foreach ($permissions as $perm)
		{
			if ($this->has_permission($userid, $perm) == false) return false;
		}
		return true;
	}
	
	public function has_permission($userid, $permission)
	{
		$result = $this->db->query("SELECT (map.userid, '_', perms.id) AS authid FROM permissions_map AS map INNER JOIN permissions AS perms ON perms.id = map.permissionid WHERE map.userid = ? AND perms.permissionname = ?", array($userid, $permission));
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
		if (isset($this->realname_first) && isset($this->realname_last))
		{
			return $this->realname_first . ' ' . $this->realname_last;
		}
		else
		{
			return $this->username;
		}
	}
}
