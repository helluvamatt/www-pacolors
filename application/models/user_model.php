<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require BASEPATH . '/models/db_model.php';

class User_model extends DB_Model
{

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
		$alg = '$6$rounds=100000$'; # SHA-512
		
		$salt = '';
		for($i = 0; $i <= $length; $i ++)
		{
			$alphabet = str_shuffle ( $alphabet );
			$salt .= $alphabet[openssl_random_pseudo_bytes(1) % 64];
		}
		return crypt($password, $alg . $salt . '$');
	}
}
