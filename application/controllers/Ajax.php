<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//$response['msg'] = 'Executed';
		$this->load->model('user_model');
	}

	public function checkduplicateemail()
	{
		if (!$this->input->is_ajax_request()) 
		{
   			exit('No direct script access allowed');
		}

		$email = $this->input->post('email');
		$u_id = $this->input->post('u_id');
		$response = array();

		$count = $this->user_model->checkuseremail(array('email'=>$email, 'u_id'=>$u_id));

		if($count > 0)
		{
			$response['status'] = false;
		}
		else 
		{
			$response['status'] = true;
		}

		echo json_encode($response);
		die;
	}

	public function userdetail()
	{
		if (!$this->input->is_ajax_request()) 
		{
   			exit('No direct script access allowed');
		}

		$imgdir = base_url()."uploads/thumb/";
		$imgdefaultdir = base_url()."uploads/misc/";

		$user_id = $this->input->post('uid');

		$this->defaultdata->setTable('users');
		$userinfo = $this->defaultdata->fetchsinglerecord(array(), array('u_id'=>$user_id));
		$this->defaultdata->unsetTable();

		$hobbyarr = fetchallhobby();
		$hobbystr = '';

		$userdetail['name'] = ucwords($userinfo->u_name);
		$userdetail['email'] = $userinfo->u_email;
		$userdetail['address'] = $userinfo->u_address;
		$userdetail['contact'] = $userinfo->u_contact;
		$userdetail['thumb'] = empty($userinfo->u_image_thumb) ? $imgdefaultdir."default.jpg" : $imgdir.$userinfo->u_image_thumb ;
		$userdetail['gender'] = ($userinfo->u_gender == 'M') ? 'Male' : 'Female';
		$userdetail['dob'] = date('F j, Y',strtotime($userinfo->u_dob));
		$userdetail['married'] = ($userinfo->u_married == 'M') ? 'Married' : 'Unmarried';


		switch($userinfo->u_food)
		{
			case 'V':
				$userdetail['food'] = 'Vegeterian';
			break;

			case 'NV':
				$userdetail['food'] = 'Non Vegeterian';
			break;

			case 'E':
				$userdetail['food'] = 'Eggiterian';
			break;

			case 'NSP':
				$userdetail['food'] = 'Not Specified';
			break;
		}

		switch($userinfo->u_smoking)
		{
			case 'NS':
				$userdetail['smoking'] = 'Non Smoker';
			break;

			case 'RS':
				$userdetail['smoking'] = 'Regular Smoker';
			break;

			case 'OS':
				$userdetail['smoking'] = 'Occational Smoker';
			break;

			case 'NSP':
				$userdetail['smoking'] = 'Not Specified';
			break;
		}

		switch($userinfo->u_drink)
		{
			case 'ND':
				$userdetail['drinking'] = 'Non Drinker';
			break;

			case 'RD':
				$userdetail['drinking'] = 'Regular Drinker';
			break;

			case 'OD':
				$userdetail['drinking'] = 'Occational Drinker';
			break;

			case 'NSP':
				$userdetail['drinking'] = 'Not Specified';
			break;
		}

		$u_hobby_info = explode(",", $userinfo->u_hobby);

		foreach($u_hobby_info as $uh)
		{
			$hobbystr.=$hobbyarr[$uh].",";
		}

		$userdetail['hobby'] = rtrim($hobbystr, ",");

		echo json_encode($userdetail);

		die;
	}
}