<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function checkuseremail($data)
	{
		$email = $data['email'];
		$id = $data['u_id'];

		$this->db->where('u_email', $email);
		if($id > 0)
		{
			$this->db->where('u_id != ', $id);
		}
		$this->db->from('users');

		return $this->db->count_all_results();
	}

	public function search_user($cond = array(), $limit = array())
	{
		$flag = 0;
		$data = array();
		$sql = "SELECT * FROM users WHERE ";
		if(array_key_exists('name', $cond))
		{
			$sql.=" u_name LIKE '%".$cond['name']."%' ";
			$flag = 1;
		}
		elseif(array_key_exists('email', $cond))
		{
			$sql.=" u_email = '".$cond['email']."' ";
			$flag = 1;
		}
		elseif(array_key_exists('contact', $cond))
		{
			$sql.=" u_contact = '".$cond['contact']."' ";
			$flag = 1;
		}

		if(array_key_exists('gender', $cond))
		{
			if($flag == 1)
			{
				$sql.=" AND ";
			}
			$sql.=" u_gender = '".$cond['gender']."' ";
			$flag = 1;
		}

		if(count($limit) > 0)
		{
			$sql.=" LIMIT ".$limit['offset'].", ".$limit['count'];
		}


		if($flag == 1)
		{
			$query = $this->db->query($sql);
			$data = $query->result();
		}

		return $data;
	}
}