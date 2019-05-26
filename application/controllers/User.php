<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller 
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('user_model');
		$action = '';
		if(!empty($this->uri->segment(2)))
		{
			$action = $this->uri->segment(2);
		}
		$fields = array('hb_id', 'hb_name');
		$cond = array('hb_status'=>1);
		switch($action)
		{
			case "create":
				$this->data['title'] = 'Create an User';
				$this->data['action'] = base_url()."user/saveuser";
				$this->data['op'] = 'add';
				$this->defaultdata->setTable('hobby');
				$this->data['hobbylist'] = $this->defaultdata->selectdata($fields, $cond);
				$this->defaultdata->unsetTable();
			break;

			case "edit":
				if(empty($this->uri->segment(3)))
				{
					redirect('user/list');
				}
				$user_id = (int)$this->uri->segment(3);

				$this->data['user_id'] = $user_id;
				$this->data['action'] = base_url()."user/updateuser/".$user_id;
				$this->data['title'] = 'Edit an User';
				$this->data['op'] = 'edit';
				$this->defaultdata->setTable('users');
				$this->data['uinfo'] = $this->defaultdata->fetchsinglerecord(array(), array('u_id'=>$user_id));
				$this->defaultdata->unsetTable();

				$this->defaultdata->setTable('hobby');
				$this->data['hobbylist'] = $this->defaultdata->selectdata($fields, $cond);
				$this->defaultdata->unsetTable();
			break;

			case "list":
				$this->data['title'] = 'List of Users';
				//LP stands for list page
				$this->data['show'] = 'LP';
			break;

			case "trash":
				$this->data['title'] = 'List of deleted Users';
				//TP stands for trash page
				$this->data['show'] = 'TP';
			break;

			case "search":
				$this->data['title'] = 'Search Page';
				$this->data['show'] = 'LP';
			break;

			default:
				$this->data['title'] = 'List of users';
				$this->data['show'] = 'LP';
		}

		$this->data['header'] = $this->load->view('templates/header', $this->data, true);
		$this->data['footer'] = $this->load->view('templates/footer', '', true);
	}

	public function index()
	{
		
		//$this->data['content'] = 'Welcome to index page';
		$page_config = array();
		//If LP is specified then active users will be displayed. Otherwise inactive users will be displayed
		$status = ($this->data['show'] == 'LP') ? '1' : '0';
		$cond['u_status'] = $status;

		$this->defaultdata->setTable('users');

		$total_rows = $this->defaultdata->countrecords($cond);
		$base_url = ($this->data['show'] == 'LP') ? base_url().'user/list' : base_url().'user/trash';


		//All pagination config details

		$page_config["base_url"] = $base_url;
		
		$page_config["total_rows"] = $total_rows;
		$page_config["per_page"] = RECORDPERPAGE;
		$page_config['use_page_numbers'] = TRUE;
		$page_config['num_links'] = 5;
		$page_config['cur_tag_open'] = '<b>';
		$page_config['cur_tag_close'] = '</b>';
		$page_config['next_link'] = 'Next';
		$page_config['prev_link'] = 'Previous';

		$this->pagination->initialize($page_config);

		if($this->uri->segment(3)){
			$page = $this->uri->segment(3) ;
		}
		else{
			$page = 1;
		}

		$offset = ($page - 1)*RECORDPERPAGE;

		$this->data['users'] = $this->defaultdata->selectdata(array(), array('u_status'=>$status), array('column'=>'u_id', 'type'=>'DESC'), array('offset'=>$offset, 'count'=>RECORDPERPAGE));

		$str_links = $this->pagination->create_links();
		//$this->data["links"] = explode('&nbsp;',$str_links );
		$this->data["links"] = $str_links;

		$this->defaultdata->unsetTable();

		$this->data['startindex'] = $offset + 1;

		$this->load->view('users/list', $this->data);
	}

	public function adduser()
	{
		$this->load->view('users/addedit', $this->data);
	}

	public function saveuser()
	{
		if(empty($_POST))
		{
			redirect('user/index');
		}

		$input_data = $this->input->post();
		$user_hobby = array();

		if(array_key_exists("hobby", $input_data))
		{
			$user_hobby = implode(",", $input_data['hobby']);
			unset($input_data['hobby']);
		}
		if(array_key_exists("u_conf_pswd", $input_data))
		{
			unset($input_data['u_conf_pswd']);
		}
		if(array_key_exists("u_id", $input_data))
		{
			unset($input_data['u_id']);
		}
		if(array_key_exists("u_dob", $input_data))
		{
			$dobinfo = explode("/", $input_data['u_dob']);
			$input_data['u_dob'] = $dobinfo[2]."-".$dobinfo[1]."-".$dobinfo[0];
		}

		$userdata = $this->defaultdata->secureInput($input_data);

		$userdata['u_hobby'] = $user_hobby;

		if($input_data['u_food'] == '')
		{
			$userdata['u_food'] = 'NSP';
		}
		if($input_data['u_smoking'] == '')
		{
			$userdata['u_smoking'] = 'NSP';
		}
		if($input_data['u_drink'] == '')
		{
			$userdata['u_drink'] = 'NSP';
		}

		if(!empty($_FILES['u_image']['name']))
		{

			$file_info = pathinfo($_FILES['u_image']['name']);
			$ext = strtolower($file_info['extension']);
			$newfilename = time().".".$ext;

			$config['upload_path']          = './uploads/large/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 500;
            $config['max_width']            = 1024;
            $config['max_height']           = 1024;
            $config['file_name'] = $newfilename;
            $config['file_ext_tolower'] = true;

            $this->load->library('upload', $config);

            if($this->upload->do_upload('u_image'))
            {
            	$userdata['u_image'] = $newfilename;

            	$config_thumb['image_library'] = 'gd2';
				$config_thumb['source_image'] = './uploads/large/'.$newfilename;
				$config_thumb['create_thumb'] = TRUE;
				$config_thumb['maintain_ratio'] = TRUE;
				$config_thumb['width']         = 100;
				$config_thumb['height']       = 80;
				$config_thumb['new_image'] = './uploads/thumb/';

				$this->load->library('image_lib', $config_thumb);

				if($this->image_lib->resize())
				{
					$large_img_info = explode(".", $newfilename);
					$thumb_img_name = $large_img_info[0]."_thumb".".".$ext;
					$userdata['u_image_thumb'] = $thumb_img_name;
				}
            }
		}

		$userdata['created_at'] = time();

		$this->defaultdata->setTable('users');
		$this->defaultdata->insertdata($userdata);
		$this->defaultdata->unsetTable();

		$this->session->set_flashdata('msg', 'User created successfully.');

		redirect('user/list');
	}

	public function edituser()
	{
		$this->load->view('users/addedit', $this->data);
	}

	public function updateuser($user_id = 0)
	{
		if(empty($_POST))
		{
			redirect('user/index');
		}

		$input_data = $this->input->post();
		$user_hobby = array();

		$lastupdated = '';

		if(array_key_exists("hobby", $input_data))
		{
			$user_hobby = implode(",", $input_data['hobby']);
			unset($input_data['hobby']);
		}
		
		if(array_key_exists("u_id", $input_data))
		{
			unset($input_data['u_id']);
		}
		if(array_key_exists("u_dob", $input_data))
		{
			$dobinfo = explode("/", $input_data['u_dob']);
			$input_data['u_dob'] = $dobinfo[2]."-".$dobinfo[1]."-".$dobinfo[0];
		}
		if(array_key_exists("last_updated", $input_data))
		{
			$lastupdated = $input_data['last_updated'];
			unset($input_data['last_updated']);
		}


		$userdata = $this->defaultdata->secureInput($input_data);

		$userdata['u_hobby'] = $user_hobby;

		if($input_data['u_food'] == '')
		{
			$userdata['u_food'] = 'NSP';
		}
		if($input_data['u_smoking'] == '')
		{
			$userdata['u_smoking'] = 'NSP';
		}
		if($input_data['u_drink'] == '')
		{
			$userdata['u_drink'] = 'NSP';
		}

		if(!empty($_FILES['u_image']['name']))
		{

			$file_info = pathinfo($_FILES['u_image']['name']);
			$ext = strtolower($file_info['extension']);
			$newfilename = time().".".$ext;

			$config['upload_path']          = './uploads/large/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 500;
            $config['max_width']            = 1024;
            $config['max_height']           = 1024;
            $config['file_name'] = $newfilename;
            $config['file_ext_tolower'] = true;

            $this->load->library('upload', $config);

            if($this->upload->do_upload('u_image'))
            {
            	//removing old images
            	$old_image = "./uploads/large/".$userdata['u_image'];
            	$old_image_thumb = "./uploads/thumb/".$userdata['u_image_thumb'];

            	@unlink($old_image);
            	@unlink($old_image_thumb);


            	$userdata['u_image'] = $newfilename;

            	$config_thumb['image_library'] = 'gd2';
				$config_thumb['source_image'] = './uploads/large/'.$newfilename;
				$config_thumb['create_thumb'] = TRUE;
				$config_thumb['maintain_ratio'] = TRUE;
				$config_thumb['width']         = 100;
				$config_thumb['height']       = 80;
				$config_thumb['new_image'] = './uploads/thumb/';

				$this->load->library('image_lib', $config_thumb);

				if($this->image_lib->resize())
				{
					$large_img_info = explode(".", $newfilename);
					$thumb_img_name = $large_img_info[0]."_thumb".".".$ext;
					$userdata['u_image_thumb'] = $thumb_img_name;
				}
            }
		}


		$this->defaultdata->setTable('users');
		$this->defaultdata->updatedata($userdata, array('u_id'=>$user_id));
		$userinfo = $this->defaultdata->fetchsinglerecord(array('updated_at'), array('u_id'=>$user_id));
		$this->defaultdata->unsetTable();

		if(strtotime($userinfo->updated_at) == $lastupdated)
		{
			$msg = 'No record is updated.';
		}
		else
		{
			$msg = 'User updated successfully.';
		}

		$this->session->set_flashdata('msg', $msg);
		redirect(base_url('user/list'));
	}

	public function change_user_status($u_id = 0, $status = 0)
	{
		if($u_id == 0)
		{
			redirect(base_url('user/list'));
		}
		$info = array();
		$cond = array();
		$msg = '';
		$cond['u_id'] = $u_id;
		$url = base_url('user/list');

		//Send user to trash
		if($status == 0)
		{
			$info['u_status'] = '0';
			$msg = 'User deleted successfully.';
		}

		//Restore the product
		elseif($status == 1)
		{
			$info['u_status'] = '1';
			$msg = 'User restored successfully.';
			$url = base_url('user/trash');
		}

		$this->defaultdata->setTable('users');
		$this->defaultdata->updatedata($info, $cond);
		$this->defaultdata->unsetTable();

		$this->session->set_flashdata('msg', $msg);
		redirect($url);

	}

	public function search()
	{
		$keyword = $this->uri->segment(3);
		$gender = $this->uri->segment(4);
		$cond = array();
		//$keyword_new = str_replace('%20', ' ', $keyword);
		$keyword_new  = urldecode($keyword);
		if($keyword_new == 'NA')
		{
			$cond['ignore'] = 'NA';
		}

		//Check whether it is an email
		elseif(filter_var($keyword_new, FILTER_VALIDATE_EMAIL))
		{
			$cond['email'] = $keyword_new;
		}
		//Check whether it is a name
		elseif (preg_match("/^[a-zA-Z ]*$/",$keyword_new))
		{
			$cond['name'] = ucwords(strtolower($keyword_new));
		}
		//Check whether it is a phone no
		elseif(preg_match("/^(((\+|00){1}91(\s|\-){0,1})?([0-9]{0,6}(\s|\-){0,1})?)?([0-9]{8,10})$/", $keyword_new))
		{
			$cond['contact'] = $keyword_new;
		}

		if($gender != 'NA')
		{
			$cond['gender'] = $gender;
		}

		$result = $this->user_model->search_user($cond);

		$total_rows = count($result);

		$base_url = base_url().'user/search/'.$keyword.'/'.$gender;


		//All pagination config details

		$page_config["base_url"] = $base_url;
		
		$page_config["total_rows"] = $total_rows;
		$page_config["per_page"] = RECORDPERPAGE;
		$page_config['use_page_numbers'] = TRUE;
		$page_config['num_links'] = 5;
		$page_config['cur_tag_open'] = '<b>';
		$page_config['cur_tag_close'] = '</b>';
		$page_config['next_link'] = 'Next';
		$page_config['prev_link'] = 'Previous';

		$this->pagination->initialize($page_config);

		if($this->uri->segment(5)){
			$page = $this->uri->segment(5) ;
		}
		else{
			$page = 1;
		}

		$offset = ($page - 1)*RECORDPERPAGE;

		$this->data['users'] =  $this->user_model->search_user($cond, array('offset'=>$offset, 'count'=>RECORDPERPAGE));

		$str_links = $this->pagination->create_links();

		$this->data["links"] = $str_links;

		$this->data['startindex'] = $offset + 1;

		$this->data['keyword'] = $keyword_new;

		$this->data['gender'] = $gender;

		$this->load->view('users/list', $this->data);

	}

	
}
