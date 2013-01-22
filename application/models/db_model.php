<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DB_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/* Inspired (copied and adapted) from CodeIgniter active record */
	protected function process_where($where_cond, $and = true, $escape = true, $escape_like = false)
	{
		if (is_array($where_cond))
		{
			$where = '';
			foreach ($where_cond as $condition => $value)
			{
				// Add connector 
				if (strlen($where) > 0)
				{
					$where .= ($and ? ' AND ' : ' OR ');
				}
				else
				{
					$where .= 'WHERE ';
				}
				
				if ( ! $this->_has_operator($condition) )
				{
					$condition .= ' = ';
				}
				
				if ($escape === true)
				{
					$value = $this->db->escape($value);
				}
				else if ($escape_like === true)
				{
					$value = $this->db->escape_like_str($value);
				}
				
				$where .= $condition . $value;
			}
			return $where;
		}
		else
		{
			// Assume string, just return
			return $where_cond;
		}
	}
	
	/**
	 * FROM CODEIGNITER
	 *
	 * Tests whether the string has an SQL operator
	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */
	protected function _has_operator($str)
	{
		$str = trim($str);
		if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
		{
			return FALSE;
		}

		return TRUE;
	}
}

