<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require BASEPATH . '/models/db_model.php';

define('SQL_TABLE_USER', 'SELECT id, username, email, realname_first, realname_last, created FROM users WHERE enabled');

class User_model extends DB_Model
{

	public function get_user($id)
	{
		$result = $this->db->query(SQ_TABLE_USER . ' AND id = ?', array($id));
		return ($result->count_rows() > 0) ? $result->row(0, 'User_Object') : null;
	}

	public function verify($username, $password)
	{
		
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
		$display_name = '';
		if (isset($this->realname_first))
		{
			$display_name = $this->realname_first;
		}
		if (isset($this->realname_last))
		{
			$display_name .= ((strlen($display_name) > 0) ? ' ' : '') . $this->realname_last;
		}
		if (strlen($display_name) > 0)
		{
			$display_name = $this->username;
		}
		return $display_name;
	}
}
