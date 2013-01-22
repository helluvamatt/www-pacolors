<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DB_Model extends CI_Model
{
	public function __construct()
	{
		super::__construct();
		$this->load->database();
	}
}
