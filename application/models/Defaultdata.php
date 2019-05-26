<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Defaultdata extends CI_Model {

	private $data=array();
	private $table_name;
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function secureInput($data)
	{
		$return_data = array();
		foreach($data as $field => $inp_data)
		{
			//$return_data[$field]=$this->db->escape_str($inp_data);
			if($field == 'u_pswd')
			{
				$return_data[$field] = md5($this->security->xss_clean($inp_data));
			}
			else 
			{
				$return_data[$field] = $this->security->xss_clean(trim($inp_data));
			}
			
		}
		return $return_data;
	}
	
	
	public function getGplusLoginUrl()
	{
		require_once APPPATH .'libraries/google-api-php-client-master/src/Google/autoload.php';
		$client_id = $this->config->item('client_id','googleplus');
		$client_secret = $this->config->item('client_secret','googleplus');
		$redirect_uri = $this->config->item('redirect_uri','googleplus');
		$simple_api_key = $this->config->item('api_key','googleplus');
		
		// Create Client Request to access Google API
		$client = new Google_Client();
		$client->setApplicationName("PHP Google OAuth Login Example");
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->setDeveloperKey($simple_api_key);
		$client->addScope("https://www.googleapis.com/auth/userinfo.email");
		$authUrl = $client->createAuthUrl();
		return $authUrl;
	}
	
	public function getGeneratedPassword( $length = 6 ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$password = substr( str_shuffle( $chars ), 0, $length );	
		return $password;
	}

	public function setTable($table)
	{
		$this->table_name = $table;
	}

	public function unsetTable()
	{
		$this->table_name = '';
	}
	
	public function insertdata($data = array())
	{
		$this->db->insert($this->table_name, $data);
		return $this->db->insert_id();
	}

	public function updatedata($data = array(), $cond = array())
	{

		$this->db->update($this->table_name, $data, $cond);
	}

	public function removedata($cond = array())
	{
		$this->db->delete($this->table_name, $cond);
	}

	public function selectdata($fields = array(), $cond = array(), $orderby = array(), $limit = array())
	{
		$fields_str = '';
		$field_name = '';
		$sort_by = 'ASC';
		if(count($fields) > 0)
		{
			$fields_str = implode(",", $fields);
		}
		else 
		{
			$fields_str = '*';
		}


		$this->db->select($fields_str);

		$this->db->from($this->table_name);
		
		if(count($cond) > 0)
		{
			$this->db->where($cond);
		}

		if(count($orderby) > 0)
		{
			$field_name = $orderby['column'];
			if(array_key_exists('type', $orderby))
			{
				$sort_by = $orderby['type'];
			}

			$this->db->order_by($field_name, $sort_by);
			
		}

		if(count($limit) > 0)
		{
			$this->db->limit($limit['count'], $limit['offset']);
		}

		$query = $this->db->get();

		return $query->result();
	}

	public function fetchsinglerecord($fields = array(), $cond = array())
	{
		$fields_str = '';

		if(count($fields) > 0)
		{
			$fields_str = implode(",", $fields);
		}
		else 
		{
			$fields_str = '*';
		}

		$this->db->select($fields_str);

		$this->db->from($this->table_name);
		
		if(count($cond) > 0)
		{
			$this->db->where($cond);
		}

		$query = $this->db->get();

		return $query->row();
	}

	public function countrecords($cond = array())
	{
		if(count($cond) > 0)
		{
			$this->db->where($cond);
		}
		$this->db->from($this->table_name);

		return $this->db->count_all_results();
	}

}
?>