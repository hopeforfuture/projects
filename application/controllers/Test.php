<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		/*if($this->session->has_userdata('fruitscopy'))
		{
			$this->session->unset_userdata('fruitscopy');
		}*/
		$this->data['title'] = 'Ajax request test';
		$this->data['header'] = $this->load->view('templates/header', $this->data, true);
		$this->data['footer'] = $this->load->view('templates/footer', '', true);
	}

	public function index()
	{
		
		$input = array(
			'a'=>'Apple',
			'b'=>'Banana',
			'c'=>'Chocolate',
			'd'=>'Watermelon',
			'e'=>'Guava',
			'f'=>'Mango',
			'g'=>'Potato',
			'h'=>'abc',
			'i'=>'pqr',
			'j'=>'Onion',
			'k'=>'Koko',
			'l'=>'Litchi'
		);
		shuffle($input);
		$this->data['fruits'] = $input;

		if(!$this->session->has_userdata('fruitscopy'))
		{
			$this->session->set_userdata('fruitscopy', $this->data['fruits']);
		}
			
		$this->load->view('test/index', $this->data);
		
	}

	public function loaddata()
	{
		if (!$this->input->is_ajax_request()) 
		{
   			exit('No direct script access allowed');
		}

		$fruits = $this->session->userdata('fruitscopy');
		$keys = array_keys($fruits);
		$info['count'] = count($fruits);
		$info['first'] = ($keys[0]);
		echo json_encode($info);

		die;
	}
}