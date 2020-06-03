<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class User extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin/Common_model');
		$this->load->model('admin/Other_model');
		$this->load->model('App_model');
		$this->load->model('admin/Admin_model');
		$this->load->helper('Common_helper'); 
		
	}

	public function index()	{
		$this->Common_model->check_login();
		$this->fabric_list();
	}

	public function access($section_id,$type)
	{
		$admin_id = $this->session->userdata('admin_id');
		
	}



	public function users_list()
	{
        $id = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        if($id==1){
        	$listname =  'Sales List';
        }elseif($id==2){
        	$listname =  'Companies List';
        }else{
        	$listname =  'Inspectors';
        }
        $data['doctor_type'] =  $id;
		$this->Common_model->check_login();
		$this->access('9','view');
		$data['title']= $listname." | ".SITE_TITLE;
		$data['page_title']=$listname;
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => $listname,
			'link' => ""
		);
		$where2 =''; 
		$admin_id = $this->session->userdata('admin_id');
		$doctor_id = $this->session->userdata('doctor_id');
        $user_type = $this->session->userdata('user_type');

        if($user_type=='Super Admin'){
        	if($id=='1'){
	        	$where =array('is_deleted'=>0,'user_type'=>'Sales');	
        	}elseif($id=='2'){
        		$where =array('is_deleted'=>0,'user_type'=>'Companies');	
        	}else{
        		$where =array('is_deleted'=>0,'user_type'=>'Inspectors');
        	}
        	
        }else{

        	if(empty($doctor_id)){
        		//Sub Admin
        		if($id=='1'){
	        	$where =array('created_by'=>$admin_id,'is_deleted'=>0,'user_type'=>'doctor');	
	        	}else{
	        		$where =array('created_by'=>$admin_id,'is_deleted'=>0,'user_type'=>'patient');	
	        	} 
        	}else{ 
        		//Sub Sub admin
        		if($id=='1'){
	        		$where =array('user_id'=>$doctor_id,'is_deleted'=>0,'user_type'=>'doctor');	
	        	}else{
	        		$where =array('is_deleted'=>0,'user_type'=>'patient');	
	        		$where2 =array('user_id'=>$doctor_id,'dr_id'=>0);	
	        	}
        	}
        } 
		$data['records_result']=$this->Common_model->getRecords('users', '*',$where,"user_id Desc", false,'',$where2);
 		// echo $this->db->last_query();die;
		$index=0;
	 
	 
		
		$data['add_action']=site_url('admin/user/add');
		$data['edit_action']=site_url('admin/user/edit');
		$data['view_action']=site_url('admin/user/view');

		$admin_id = $this->session->userdata('admin_id');
		$data['add']=site_url('admin/user/edit');
		$data['view']=site_url('admin/user/edit');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/users_list');	
		$this->load->view('admin/include/footer');
	} 

	public function add_user() 
	{
		$this->Common_model->check_login();
		$data['title']="Add User | ".SITE_TITLE;
		$data['page_title']="Add";

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => ' Dashboard',
			'link' => site_url('admin/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'',
			'title' => 'Users List',
			'link' => site_url('admin/user/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add User',
			'link' => ""
		);	

		$data['category_ids']=$this->Common_model->getRecords('categories','*',array('status'=>'Active'),'',false);
		$data['dr_list']=$this->Common_model->getRecords('users','*',array('status'=>'Active','user_type'=>'Sales'),'',false);

		if($this->input->post()) {
		
				$table='users';
			 
					if($_FILES['image']['name'] =='') { 

						$this->session->set_flashdata('error', "Please upload image.");
					}else{

						if($_FILES['image']['error']==0) {
							$image_path = 'resources/images/profile/';
							$allowed_types = 'jpg|jpeg|png|JPG|JPEG|PNG';
							$file='image';
							$height = 100;
							$width = 100;
							$responce = commonImageUpload($image_path,$allowed_types,$file,$width,$height);
						
							if($responce['status']==0){
								$data['upload_error'] = $responce['msg'];	
							} else {
							
									$insert_data = array( 
					                  	'full_name' => $this->input->post('fullname'),
					                  	'profile_pic'=> $responce['image_path'],
										'email' => $this->input->post('email'),
										'password'=> base64_encode($this->input->post('password')),
										'about' => $this->input->post('about'),
										'mobile' => $this->input->post('mobile'), 
										'address' => $this->input->post('address'),
										'country_id' => $this->input->post('country'),
										'country_code' => $this->input->post('country_code'),
										'state_id' => $this->input->post('state'),
										'city_id' => $this->input->post('city'), 
										'user_type' => $this->input->post('user_type'),
										'created' => date("Y-m-d H:i:s"),
										'created_by' =>$this->session->userdata('admin_id'),
									);
								 //echo "<pre>"; print_r($insert_data);exit;
						 		if(!$id=$this->Common_model->addEditRecords('users', $insert_data)) {
									$this->session->set_flashdata('error', 'Some error occured! Please try again.');
								} else {

									// $from_email = getAdminEmail(); 
									// $to_email =  $this->input->post('email'); 
									
									// $subject = "Registration";
								 
									// $data['name'] = ucfirst($this->input->post('username')); 
									// $data['message']= 'Your username and passowrd is below for foodcard <br>.'; 
								 	
									// $body = $this->load->view('admin/template/common', $data,TRUE);

									// //Send mail 
									// if($this->Common_model->defaultEmailSend($to_email,$subject,$body,$from_email)) 
									// {
									// 	add_log($id,'Add','users','New User Added'); //log
										$this->session->set_flashdata('success', 'User Added Successfully.');
										if($this->input->post('user_type')=='Sales'){
											redirect('admin/user/list/1');
										}elseif($this->input->post('user_type')=='Companies'){
											redirect('admin/user/list/2');
										}elseif($this->input->post('user_type')=='Inspectors'){
											redirect('admin/user/list/3');
										}
									// }  
									
								}
							}
						}else{
							$this->session->set_flashdata('error', "Invalid image, Please try again.");
						}
				} 
		}

		$data['countries']=$this->Common_model->getDropdownList('countries','id','name','Country');
		$data['from_action']=site_url('admin/user/add');
		$data['back_action']=site_url('admin/user/list');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_user');
		$this->load->view('admin/include/footer');
	}
	
	public function edit_user($id) 
	{
		$this->Common_model->check_login();
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Edit User | ".SITE_TITLE;
		$data['page_title']="Edit User";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'',
			'title' => 'Users list',
			'link' => site_url('admin/user/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit User',
			'link' => ""
		);	
		if(!$data['user']=$this->Common_model->getRecords('users','*',array('user_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}
		$data['category_ids']=$this->Common_model->getRecords('categories','*',array('status'=>'Active'),'',false);
		$data['dr_list']=$this->Common_model->getRecords('users','*',array('status'=>'Active','user_type'=>'Doctor'),'',false);

	
		$data['countries']=$this->Common_model->getDropdownList('countries','id','name','Country');
        if($this->input->post()) {
				//echo "<pre>";print_r($this->input->post()); exit;
			
			$this->form_validation->set_rules('email', 'Email', 'trim|required|callback_check_is_email_unique['.$id.']');
			if ($this->form_validation->run() == FALSE) 
			{	
				$this->form_validation->set_error_delimiters('', '');
			} else {
				$update_data = array( 
					'dr_id' => $this->input->post('dr_id'),
					'category_id' => $this->input->post('category_id'),
                  	'full_name' => $this->input->post('fullname'),
					'email' => $this->input->post('email'),
					'about' => $this->input->post('about'),
					'mobile' => $this->input->post('mobile'), 
					'address' => $this->input->post('address'),
					'country_id' => $this->input->post('country'), 
					'state_id' => $this->input->post('state'),
					'city_id' => $this->input->post('city'), 
					'payment_status' => $this->input->post('payment_status'),
					'modified' => date("Y-m-d H:i:s"),
				);
				if(!$this->Common_model->addEditRecords('users', $update_data,array('user_id'=>$id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->session->set_flashdata('success', 'Profile updated successfully.');
					//redirect('admin/user/edit/'.$id);
					if($this->input->post('user_type')=='Sales'){
						redirect('admin/user/list/1');
					}elseif($this->input->post('user_type')=='Companies'){
						redirect('admin/user/list/2');
					}elseif($this->input->post('user_type')=='Inspectors'){
						redirect('admin/user/list/3');
					}
					
				}
			}
		}
	
		$data['from_action']=site_url('admin/user/edit/'.$id);
		$data['back_action']=site_url('admin/user/list');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/edit_user');
		$this->load->view('admin/include/footer');

	}


	public function view_user($id) 
	{
		$this->Common_model->check_login();
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="User | ".SITE_TITLE;
		$data['page_title']="User";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'',
			'title' => 'User',
			'link' => site_url('admin/user/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'User',
			'link' => ""
		);	
		if(!$data['user']=$this->Common_model->getRecords('users','*',array('user_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}

		$data['interest_list']=$this->Common_model->getDropdownList('interest','interest_id','name','Interest',array('status'=>'Active'));
		$data['countries']=$this->Common_model->getDropdownList('countries','id','name','Country');
		$data['countries_code']=$this->Common_model->getDropdownList('countries','phonecode','phonecode','Country Code','','phonecode');
      
		$data['back_action']=site_url('admin/user/list');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/user');
		$this->load->view('admin/include/footer');

	}

	public function check_is_email_unique($new_email, $id){
        $resp = user_email($id,$new_email);
            if ($resp=='1') {
                $this->form_validation->set_message('check_is_email_unique', 'Email you are choosing already exist.');
                return FALSE;
            }else{
                return TRUE;
        }
    }

    public function edit_profile() {
		$this->Common_model->check_login();
		$data['title']="Edit Profile | ".SITE_TITLE;
		$data['page_title']="Edit Profile";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Profile',
			'link' => ""
		);
		$admin_id = $this->session->userdata('admin_id');
		if($data['admin_data'] = $this->Common_model->getRecords('admin','*',array('admin_id'=>$admin_id),'',true))
		{
			$data['countries'] = $this->Common_model->getRecords('countries','*');
			if($this->input->post()) {
				$this->form_validation->set_rules('fullname', 'Fullname', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|required');
				$this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required');
				
				
				if ($this->form_validation->run() == FALSE) 
				{	
					$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
				} else {
					$update_data = array(
                      	'fullname' => $this->input->post('fullname'),
						'email' => $this->input->post('email'),
						'mobile' => $this->input->post('mobile'),
						// 'zipcode' => $this->input->post('zip_code'),
						'modified' => date("Y-m-d H:i:s"),
					);
					if(!$this->Common_model->addEditRecords('admin', $update_data,array('admin_id'=>$admin_id))) {
						$this->session->set_flashdata('error', 'Some error occured! Please try again.');
					} else {
						$this->session->set_flashdata('success', 'Profile updated successfully.');
						redirect('admin/edit_profile');
					}
				}
			}
			$this->load->view('admin/include/header',$data);
			$this->load->view('admin/include/sidebar');
			$this->load->view('admin/edit_profile');
			$this->load->view('admin/include/footer');
		}
		else
		{
			$this->session->set_flashdata('error', 'Some error occured! Please try again.');
			redirect('login/user_list', 'refresh');
		}
	
	}

	public function report_users()
	{
		$this->Common_model->check_login();
		$data['title']="Report Users | ".SITE_TITLE;
		$data['page_title']="Report Users";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Report Users',
			'link' => ""
		);
		
	 	$user_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['records_result']=$this->Other_model->getReportUser($user_id);
		$data['user']=$this->Common_model->getRecords('users','status',array('user_id'=>$user_id),'',true);
	 	$data['block_user'] = $user_id;
	 	$data['status'] = 	$data['user']['status']; 
		$data['edit_action']=site_url('admin/user/edit');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/report_users_list');	
		$this->load->view('admin/include/footer');
	} 


	public function page_list()
	{ 
		$this->Common_model->check_login();
		$data['title']="Page List | ".SITE_TITLE;
		$data['page_title']="Page List";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Page List',
			'link' => ""
		);
		
		$id = $this->uri->segment(4); 
		$where = array('user_id'=>$id,'is_deleted'=>'0');
		$recordss=$this->Common_model->getRecords('business_page', '*',$where,"business_page_id Desc", false);
		$data['user_free_page']=$this->Common_model->getRecords('users', 'free_page,username',$where,"", ture);
	
		$index=0;
		foreach ($recordss as $key) {
			$data['records_result'][$index] = $key;
			$where_page = array('page_id'=>$key['business_page_id']);
			$recordss=$this->Common_model->getRecords('report_page', '*',$where_page,"", true);
			if(!empty($recordss))
			{
				$data['records_result'][$index]['is_report'] = 'yes';
			}else
			{
				$data['records_result'][$index]['is_report'] = 'no';
			}

			$subscription_user=$this->Common_model->getRecords('subscription_user', 'subscription_user_id',$where_page,"", true);
			if(!empty($subscription_user))
			{
				$data['records_result'][$index]['is_subscription'] = 'yes';
				$data['records_result'][$index]['mem_id'] = $subscription_user['subscription_user_id'];
			}else
			{
				$data['records_result'][$index]['is_subscription'] = 'no';
			}

			$is_offers=$this->Common_model->getRecords('business_offers', 'business_offers',array('business_page_id'=>$key['business_page_id']),"", true);

			if(!empty($is_offers))
			{
				$data['records_result'][$index]['is_offers'] = 'yes'; 
			}else
			{
				$data['records_result'][$index]['is_offers'] = 'no';
			}
			
	 		$data['records_result'][$index]['user_name'] =$data['user_free_page']['username'];
		
		$index++;
		}
	
		$data['user_id'] = $id;
		$data['add_action']=site_url('admin/user/add');
		$data['edit_action']=site_url('admin/user/edit');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/page_list');	
		$this->load->view('admin/include/footer');

	}

	 
	public function page_list_with_search()
	{ 
		$this->Common_model->check_login();
		$this->access('17','view');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date'); 
	 	
	 	if(empty($start_date) || empty( $end_date))
	 	{
	 		redirect('/admin/dashboard', 'refresh');
	 	}else
	 	{
			$start_date =  $start_date.' 00:00:00';
			$end_date =  $end_date.' 23:59:59';
	 	}


		$data['title']="Page List | ".SITE_TITLE;
		$data['page_title']="Page List";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Page List',
			'link' => ""
		);
		
		$id = $this->uri->segment(4); 
		$where = array('status'=>'verified','is_deleted'=>'0','created >='=>$start_date,'created <='=>$end_date);
		$recordss=$this->Common_model->getRecords('business_page', '*',$where,"business_page_id Desc", false);
	 
		$index=0;
		foreach ($recordss as $key) {
			$data['records_result'][$index] = $key;
			$where_page = array('business_page_id'=>$key['business_page_id'],'is_deleted'=>'0');
			$recordss=$this->Common_model->getRecords('business_offers', 'business_page_id',$where_page,"", false);
			if(!empty($recordss))
			{

				$data['records_result'][$index]['total_offer'] = count($recordss);
				$data['records_result'][$index]['is_offers'] = 'yes';
			}else
			{
				$data['records_result'][$index]['total_offer'] = '0';
				$data['records_result'][$index]['is_offers'] = 'No';
			}


			$purchase = array('page_id'=>$key['business_page_id'],'user_id'=>$key['user_id']);
			$purchase_count=$this->Common_model->getRecords('subscription_user', 'user_id',$purchase,"", false);
			if(!empty($purchase_count))
			{

				$data['records_result'][$index]['total_subscribe'] = count($purchase_count);
				$data['records_result'][$index]['is_subscribe'] = 'Yes';
			}else
			{
				$data['records_result'][$index]['total_subscribe'] = '0';
				$data['records_result'][$index]['is_subscribe'] = 'No';
			}

		
			$index++;
		}

		$data['user_id'] = $id;
		$data['add_action']=site_url('admin/user/add');
		$data['edit_action']=site_url('admin/user/edit');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/page_list_with_search');	
		$this->load->view('admin/include/footer');

	}
	
	public function page_details() 
	{
		$this->Common_model->check_login();
		$id = $this->uri->segment(4);
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Page view | ".SITE_TITLE;
		$data['page_title']="Page view";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'',
			'title' => 'Page details',
			'link' => site_url('admin/user/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Page details',
			'link' => ""
		);	
	 
		if(!$data['page']=$this->Common_model->getRecords('business_page','*',array('business_page_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}
	  	$page = $data['page'];

 		if(!empty($page['type']))
 		{
 			$ty = str_replace('_',' ',$page['type']);
 			  $data['page']['type'] = ucfirst($ty);
 		}
 		if(!empty($page['disply_rating']))
 		{
 			$ty = str_replace('_',' ',$page['disply_rating']);
 			  $data['page']['disply_rating'] = ucfirst($ty);
 		}
			if(empty($data['page']['email']))
		{
			$data['page']['email'] = 'N/A';
		}
		if(empty($data['page']['mobile']))
		{
			$data['page']['mobile'] = 'N/A';
		}
		if(empty($data['page']['address_1']))
		{
			$data['page']['address_1'] = 'N/A';
		}
		if(empty($data['page']['address_2']))
		{
			$data['page']['address_2'] = 'N/A';
		}
	 	$username=$this->Common_model->getRecords('users','username,full_name',array('user_id'=>$page['user_id']),'',true);
	 	if(!empty($username))
	 	{

		  	$data['page']['user_name']=$username['username'];  
		 	$data['page']['full_name']=$username['full_name'];  
		}else
		{
			$data['page']['user_name']='N/A';  
		 	$data['page']['full_name']='N/A'; 
		}

	 	$category=$this->Common_model->getRecords('categories','name as category_name',array('category_id'=>$page['category_id']),'',true);
	 	if(!empty($username))
	 	{
		 	$data['page']['category_name']=$category['category_name']; 
	 	}else{
	 		$data['page']['category_name'] = 'N/A';
	 	} 

 		$sub_category=$this->Common_model->getRecords('sub_categories','name as sub_categories_name',array('sub_category_id'=>$page['sub_category_id']),'',true);
	 	if(!empty($sub_category))
	 	{
		 	$data['page']['sub_category_name']=$sub_category['sub_categories_name']; 
	 	}else{
	 		$data['page']['sub_category_name'] = 'N/A';
	 	} 
	 	$country=$this->Common_model->getRecords('countries','name as country_name',array('id'=>$page['country_id']),'',true);
	 	if(!empty($country))
	 	{
		 	$data['page']['country_name']=$country['country_name']; 
	 	}else{
	 		$data['page']['country_name'] = 'N/A';
	 	} 
	 	$state=$this->Common_model->getRecords('states','name as state_name',array('id'=>$page['state_id']),'',true);
	 	if(!empty($state))
	 	{
		 	$data['page']['state_name']=$state['state_name']; 
	 	}else{
	 		$data['page']['state_name'] = 'N/A';
	 	} 
	 	
	 	$city=$this->Common_model->getRecords('cities','name as city_name',array('id'=>$page['city_id']),'',true);
	 	if(!empty($city))
	 	{
	 	 /*echo $page['city_id'];
	 		echo $city['city_name'];*/
		 	$data['page']['city_name']=$city['city_name']; 
	 	}else{
	 		$data['page']['city_name'] = 'N/A';
	 	} 
	 	$images_star=$this->Common_model->getRecords('business_img','file_path',array('business_page_id'=>$id,'is_star'=>'Yes'),'',false);
	 	if(!empty($images_star))
	 	{
		 	$data['page']['star_images']=$images_star; 
	 	}else{
	 		$data['page']['star_images'] = 'N/A';
	 	} 

	 	$images=$this->Common_model->getRecords('business_img','file_path',array('business_page_id'=>$id,'is_star'=>'No'),'',false);
	 	if(!empty($images))
	 	{
		 	$data['page']['images']=$images; 
	 	}else{
	 		$data['page']['images'] = 'N/A';
	 	} 

		$subscription_user=$this->Common_model->getRecords('subscription_user', 'subscription_user_id',array('page_id'=>$id),"", true);
		if(!empty($subscription_user))
		{
			$data['page']['is_subscription'] = 'yes';
			$data['page']['mem_id'] = $subscription_user['subscription_user_id'];
		}else
		{
			$data['page']['is_subscription'] = 'no';
		}

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/page_details');
		$this->load->view('admin/include/footer');

	}




	public function edit_page()
	{

		$this->Common_model->check_login();
		$data['title']="Edit Page | ".SITE_TITLE;
		$data['page_title']="Edit Page";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Page',
			'link' => ""
		);
		$id = $this->uri->segment(4);
		$data['countries'] = $this->Common_model->getRecords('countries','*');
		$data['categories'] = $this->Common_model->getRecords('categories','*');
		$data['sub_categories'] = $this->Common_model->getRecords('sub_categories','*');
		$data['page_id'] = $this->uri->segment(4);
	 	$data['page_details']=$this->Common_model->getRecords('business_page','*',array('business_page_id'=>$id),'',true);
		if(!empty($data['page_details'])) 	
		{
			$data['working_hours'] = $this->Common_model->getRecords('working_hours','*',array('business_page_id'=>$id),'',ture);
		}
 	
		if($this->input->post()) {
			 // echo "<pre>";print_r($this->input->post());die;
			$page_id =   $this->input->post('page_id');
			$latitude =   $this->input->post('latitude');
			$longitude =   $this->input->post('longitude');
			$data['page_details']=$this->Common_model->getRecords('business_page','*',array('business_page_id'=>$page_id ),'',true);

			if(empty($latitude) && empty($longitude))
			{
				$address =  $this->input->post('address').' '.$this->input->post('address_2');
				$url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
				$responseJson = curl_exec($ch);
				curl_close($ch);

				$response = json_decode($responseJson);
				// echo $response->status;
				// echo $latitude = $response->results[0]->geometry->location->lat;
				// echo $latitude = $response->results[0]->geometry->location->lng;
				// die;

				if ($response->status == 'OK') {

				    $latitude = $response->results[0]->geometry->location->lat;
				    $longitude = $response->results[0]->geometry->location->lng;
				} else
				{
					$address =  $this->input->post('address').' '.$this->input->post('address_2');
					$url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
					$responseJson = curl_exec($ch);
					curl_close($ch);

					$response = json_decode($responseJson);

					if ($response->status == 'OK') {
					    $latitude = $response->results[0]->geometry->location->lat;
					    $longitude = $response->results[0]->geometry->location->lng;
					}else
					{
						$latitude = '';
			    		$longitude ='';

					}
				} 
			}

			
			$newFileName = $_FILES['page_image']['name'];

		 	if(!empty($newFileName ))
		 	{
				$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
				// $fileExt = array_pop(explode(".", $newFileName));
				$filename = uniqid(time()).".".$fileExt;
				//set filename in config for upload

				$config['upload_path'] = 'resources/images/profile/';
				$config['allowed_types'] = 'jpg|jpeg|png';
				$config['file_name'] = $filename;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				// $this->upload->set_allowed_types('*');
				
				if (!$this->upload->do_upload('page_image')) 
				{
					echo $this->upload->display_errors();		
				}
				else
				{
					$upload_data=$this->upload->data();
					if($upload_data['file_type']!='image/svg+xml'){
						$config['image_library'] = 'gd2';
						$config['source_image'] = $upload_data['full_path'];
						$config['new_image'] = $filename;
						$config['quality'] = 100;
						$config['maintain_ratio'] = FALSE;
						$config['width']         = 150;
						$config['height']       = 150;

						$this->load->library('image_lib', $config);

						$this->image_lib->resize();
						$this->image_lib->clear();
						//unlink($upload_data['full_path']);
					}
				  	$image = 'resources/images/profile/'.$upload_data['file_name'];
				  		
			  		if(!empty($data['page_details']['business_image']))
			  		{
			  			unlink($data['page_details']['business_image']);
			  		}
				}
			} else {
				if(empty($data['page_details']['business_image']))
				{
					$image='';
				}else
				{
					$image=$data['page_details']['business_image'];
				}
			}
				// echo $data['page_details']['category_id'];
				// echo  $this->input->post('category1');die;
			if($data['page_details']['category_id'] != $this->input->post('category1'))
			{
				$category_id =$this->input->post('category1');
				// foreach ($this->input->post('category') as $key => $category_id) {
					$wh = array('category_id' => $category_id);
					$category=$this->Common_model->getRecords('categories','name',$wh,'',true);
					$categoryname = $category['name'];
				// }
				$sub_categoriesname = '';
				foreach ($this->input->post('sub_category') as $key => $sub_category_id) {
					$whe = array('sub_category_id' => $sub_category_id);
					$sub_categories=$this->Common_model->getRecords('sub_categories','name',$whe,'',true);
					$sub_categoriesname .= ' '.$sub_categories['name'];
				}	
				$where456 = array('business_page_id' => $page_id);
				$resiver=$this->Common_model->getRecords('business_page','user_id,push_notification,business_name',$where456,'',true);
				$business_name = $resiver['business_name'];

				$where11 = array('user_id' => $resiver['user_id']);
				if($resiver['push_notification']=='Yes'){
				    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where11,'',false);
				    	$count=$this->Common_model->getRecords('users','badge_count',$where11,'',true); 
				      	$iosarray = array(
		                    'alert' => 'Category  of your '.$business_name.'page is updated to '.$categoryname,
		                    'type'  => 'category',
		                   	'page_id'=> $page_id,
		                   
		                    'badge' => $count['badge_count'],
		                    'sound' => 'default',
		       			);

						$andarray = array(
			                'message'   =>  'Category of your '.$business_name.' page is updated to '.$categoryname,
			                'type'      =>'category',
			               	'page_id'=> $page_id,
			                'title'     => 'Notification',
		            	);
						$savearray = 'page_id-'.$page_id;

				    if(!empty($log)){
				    	foreach ($log as $key) {
				    		
				    		if($key['device_type']=='Android'){
								$referrer = androidNotification($key['device_id'],$andarray);
							}

				    		if($key['device_type']=='IOS'){
		                   		$referrer = iosNotification($key['device_id'],$iosarray);
				    		}
				    	}
				    }
				   
				    $add_data =array('user_id' => $resiver['user_id'],'page_id'=>$page_id,'created_by' =>$resiver['user_id'],'type'=>'category', 'notification_title'=>'category', 'notification_description'=>  'Category & Sub - category of your '.$business_name.' is updated to '.$categoryname.' & '.$sub_categoriesname, 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
		    		$this->Common_model->addEditRecords('notifications',$add_data); 

				}
				
			}
			// if($data['page_details']['category_id'] == $this->input->post('category')){
			// 	if($data['page_details']['sub_category_id'] != $this->input->post('sub_category')){

			// 		$wh = array('category_id' => $this->input->post('category'));
			// 		$category=$this->Common_model->getRecords('categories','name',$wh,'',true);
			// 		$categoryname = $category['name'];
			// 		$whe = array('sub_category_id' => $this->input->post('sub_category'));
			// 		$sub_categories=$this->Common_model->getRecords('sub_categories','name',$whe,'',true);
			// 		$sub_categoriesname = $sub_categories['name'];
			// 		$where456 = array('business_page_id' => $page_id);
			// 		$resiver=$this->Common_model->getRecords('business_page','user_id,push_notification,business_name',$where456,'',true);
			// 		$business_name = $resiver['business_name'];

			// 		$where11 = array('user_id' => $resiver['user_id']);
			// 		if($resiver['push_notification']=='Yes'){
			// 		    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where11,'',false);
			// 		    	$count=$this->Common_model->getRecords('users','badge_count',$where11,'',true); 
			// 		      	$iosarray = array(
			//                     'alert' => 'Sub-category of your '.$business_name.' is updated to '.$sub_categoriesname,
			//                     'type'  => 'category',
			//                    	'page_id'=> $page_id,
			                   
			//                     'badge' => $count['badge_count'],
			//                     'sound' => 'default',
			//        			);

			// 				$andarray = array(
			// 	                'message'   =>  'Sub-category of your '.$business_name.' is updated to '.$sub_categoriesname,
			// 	                'type'      =>'category',
			// 	               	'page_id'=> $page_id,
			// 	                'title'     => 'Notification',
			//             	);
			// 				$savearray = 'page_id-'.$page_id;

			// 		    if(!empty($log)){
			// 		    	foreach ($log as $key) {
					    		
			// 		    		if($key['device_type']=='Android'){
			// 						$referrer = androidNotification($key['device_id'],$andarray);
			// 					}

			// 		    		if($key['device_type']=='IOS'){
			//                    		$referrer = iosNotification($key['device_id'],$iosarray);
			// 		    		}
			// 		    	}
			// 		    }
					   
			// 		    $add_data =array('user_id' => $resiver['user_id'],'page_id'=>$page_id,'created_by' =>$resiver['user_id'],'type'=>'category', 'notification_title'=>'category', 'notification_description'=>  'Sub-category of your '.$business_name.' is updated to '.$sub_categoriesname, 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
			//     		$this->Common_model->addEditRecords('notifications',$add_data); 

			// 		}
					
			// 	}
			// }
			if($this->input->post('from_price') && $this->input->post('to_price')) {
		  		if($this->input->post('from_price')>$this->input->post('to_price')) {
		  			$this->session->set_flashdata('error', 'To Price must be greater than or equal to From Price .');
					redirect('admin/user/edit_page/'.$page_id);
		  		}
		  	}
		  	// echo  implode(',',$this->input->post('category'));die;
			$update_data = array(
              	'business_name' => $this->input->post('business_name'), 
				'business_full_name' => $this->input->post('business_full_name'),
				'business_image' =>$image,
				'category_id' => $this->input->post('category1'),
				'sub_category_id' => implode(',',$this->input->post('sub_category1')), 

				'category_id2' => $this->input->post('category2'),
				'sub_category_id2' => implode(',',$this->input->post('sub_category2')), 

				'category_id3' => $this->input->post('category3'),
				'sub_category_id3' => implode(',',$this->input->post('sub_category3')), 


				'verification' => $this->input->post('type'),
				'description' => $this->input->post('description'),
				'email' => $this->input->post('email'),
				'zipcode' => $this->input->post('zipcode'),
				'mobile' => $this->input->post('mobile'),
				'address_1' => $this->input->post('address'),
				'address_2' => $this->input->post('address_2'),
				'latitude' => $latitude,
				'longitude' =>$longitude,
				'city_id' => $this->input->post('city'),
				'country_id' => $this->input->post('country'),
				'state_id' => $this->input->post('state'),
				'website' => $this->input->post('website'),
				'status' => $this->input->post('status'),
				'sponsored' => $this->input->post('sponsored'),
				'type' => $this->input->post('type'),
				'page_pin' => $this->input->post('pin'), 
				'from_price' => number_format($this->input->post('from_price'),2),
				'to_price' => number_format($this->input->post('to_price'),2), 
				'modified' => date("Y-m-d H:i:s"),
			);
				// echo $id;
			if(!$this->Common_model->addEditRecords('business_page', $update_data,array('business_page_id'=>$page_id))) {
				$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				redirect('admin/user/edit_page/'.$page_id);
			} else {

					/************************ Reviews  *******************************/

				$page_detail=$this->Common_model->getRecords('business_page','*',array('business_page_id'=>$page_id),'',true);
				
				if($page_detail['business_page_type']=='google')	
				{
					$google_page_rating =$this->Common_model->getRecords('google_page_rating','*',array('page_id'=>$page_detail['google_place_id']),"", true); 
				 		if(!empty($google_page_rating))
				 		{
				 			if(empty($google_page_rating['title']))
					 		{
					 			$google_page_rating['title'] = '';
					 		}	
					 		if(empty($google_page_rating['description']))
					 		{
					 			$google_page_rating['description'] = '';
					 		}
				 			$data = array(
				                  	'page_id' => $page_id, 
				                  	'title' => $google_page_rating['title'], 
				                  	'description' => $google_page_rating['description'], 
				                  	'status' => 'Active', 
									'verified_rating' => 'not_verified',  
									'created' => date("Y-m-d H:i:s"),
									'user_id' => $page_detail['user_id'],
								);
								// echo "<pre>";print_r($data);exit;
							$review_id = $this->Common_model->addEditRecords('review', $data);  
							if(!empty($review_id))
							{ 
							 	$google_page_rating_Cate =$this->Common_model->getRecords('rating_categories','*',array('category_id'=>$this->input->post('category1')),"", false); 

							 	if(!empty($google_page_rating_Cate))
							 	{
							 		foreach ($google_page_rating_Cate as $listing) { 
							 				$data = array(
							                  	'rating_categories_id' => $listing['rating_categories_id'], 
							                  	'review_id' =>$review_id, 
												'page_id' =>$page_id,
												'user_id' => $page_detail['user_id'], 
												'verified_rating' => 'not_verified', 
												'status' => 'Active', 
												'rating' => $google_page_rating['rating'],  
												'created' => date("Y-m-d H:i:s"),
											);
											// echo "<pre>";print_r($data);exit;
											$idd = $this->Common_model->addEditRecords('rating_page', $data);  
							 		}
							 	}
						 	 }  

						 	 /************ Delete Google ************ */
						 	 $this->Common_model->deleteRecords('google_page_rating',array('page_id'=>$page_detail['google_place_id']));


			 			} 

		 			}
		  			$sunday_from = implode(',',$this->input->post('sunday_from')); 
		  			if(empty($sunday_from))
		  			{
		  				$sunday_from = '';
		  			}
	                $sunday_to = implode(',',$this->input->post('sunday_to'));
	                if(empty($sunday_to))
		  			{
		  				$sunday_to = '';
		  			}
	                $sunday24hours = $this->input->post('sunday24hours');
	                if(empty($sunday24hours))
		  			{
		  				$sunday24hours = 0;
		  			}
	                $sundayWorking = $this->input->post('sundayWorking');
	                  if(empty($sundayWorking))
		  			{
		  				$sundayWorking = 0;
		  			}
	                 
	                $monday_from = implode(',',$this->input->post('monday_from'));
	                if(empty($monday_from))
		  			{
		  				$monday_from = '';
		  			}
	                $monday_to = implode(',',$this->input->post('monday_to'));
	                if(empty($monday_to))
		  			{
		  				$monday_to = '';
		  			}
	                $monday24hours = $this->input->post('monday24hours');
	                  if(empty($monday24hours))
		  			{
		  				$monday24hours = 0;
		  			}
	                $mondayWorking = $this->input->post('mondayWorking');
	                  if(empty($mondayWorking))
		  			{
		  				$mondayWorking = 0;
		  			}
	                 
	                $tuesday_from = implode(',',$this->input->post('tuesday_from'));
	                if(empty($tuesday_from))
		  			{
		  				$tuesday_from = '';
		  			}
	                $tuesday_to = implode(',',$this->input->post('tuesday_to'));
	                if(empty($tuesday_to))
		  			{
		  				$tuesday_to = '';
		  			}
	                $tuesday24hours = $this->input->post('tuesday24hours');
	                  if(empty($tuesday24hours))
		  			{
		  				$tuesday24hours = 0;
		  			}
	                $tuesdayWorking = $this->input->post('tuesdayWorking');
	                  if(empty($tuesdayWorking))
		  			{
		  				$tuesdayWorking = 0;
		  			}
	                 
	                $wednesday_from = implode(',',$this->input->post('wednesday_from'));
	                if(empty($wednesday_from))
		  			{
		  				$wednesday_from = '';
		  			}
	                $wednesday_to = implode(',',$this->input->post('wednesday_to'));
	                if(empty($wednesday_to))
		  			{
		  				$wednesday_to = '';
		  			}
	                $wednesday24hours = $this->input->post('wednesday24hours');
	                  if(empty($wednesday24hours))
		  			{
		  				$wednesday24hours = 0;
		  			}
	                $wednesdayWorking = $this->input->post('wednesdayWorking');
	                  if(empty($wednesdayWorking))
		  			{
		  				$wednesdayWorking = 0;
		  			}
	                 
	                $thursday_from = implode(',',$this->input->post('thursday_from'));
	                if(empty($thursday_from))
		  			{
		  				$thursday_from = '';
		  			}
	                $thursday_to = implode(',',$this->input->post('thursday_to'));
	                if(empty($thursday_to))
		  			{
		  				$thursday_to = '';
		  			}
	                $thursday24hours = $this->input->post('thursday24hours');
	                  if(empty($thursday24hours))
		  			{
		  				$thursday24hours = 0;
		  			}
	                $thursdayWorking = $this->input->post('thursdayWorking');
	                  if(empty($thursdayWorking))
		  			{
		  				$thursdayWorking = 0;
		  			}
	                 
	                $friday_from = implode(',',$this->input->post('friday_from'));
	                if(empty($friday_from))
		  			{
		  				$friday_from = '';
		  			}
	                $friday_to = implode(',',$this->input->post('friday_to'));
	                if(empty($friday_to))
		  			{
		  				$friday_to = '';
		  			}
	                $friday24hours = $this->input->post('friday24hours');
	                  if(empty($friday24hours))
		  			{
		  				$friday24hours = 0;
		  			}
	                $fridayWorking = $this->input->post('fridayWorking');
	                  if(empty($fridayWorking))
		  			{
		  				$fridayWorking = 0;
		  			}

	                $saturday_from = implode(',',$this->input->post('saturday_from'));
	                if(empty($saturday_from))
		  			{
		  				$saturday_from = '';
		  			}
	                $saturday_to = implode(',',$this->input->post('saturday_to'));
	                if(empty($saturday_to))
		  			{
		  				$saturday_to = '';
		  			}
	                $saturday24hours = $this->input->post('saturday24hours');
	                  if(empty($saturday24hours))
		  			{
		  				$saturday24hours = 0;
		  			}
	                $saturdayWorking = $this->input->post('saturdayWorking');
	                  if(empty($saturdayWorking))
		  			{
		  				$saturdayWorking = 0;
		  			}


	            	$data = array( 
	            		'sunday_from'=> $sunday_from,
	            		'sunday_to'=> $sunday_to,
	            		'sunday24hours'=> $sunday24hours,
	            		'sundayWorking'=> $sundayWorking,
	            		'monday_from'=> $monday_from,
	            		'monday_to'=> $monday_to,
	            		'monday24hours'=> $monday24hours,
	            		'mondayWorking'=> $mondayWorking,
	            		'tuesday_from'=> $tuesday_from,
	            		'tuesday_to'=> $tuesday_to,
	            		'tuesday24hours'=> $tuesday24hours,
	            		'tuesdayWorking'=> $tuesdayWorking,
	            		'wednesday_from'=> $wednesday_from,
	            		'wednesday_to'=> $wednesday_to,
	            		'wednesday24hours'=> $wednesday24hours,
	            		'wednesdayWorking'=> $wednesdayWorking,
	            		'thursday_from'=> $thursday_from,
	            		'thursday_to'=> $thursday_to,
	            		'thursday24hours'=> $thursday24hours,
	            		'thursdayWorking'=> $thursdayWorking,
	            		'friday_from'=> $friday_from,
	            		'friday_to'=> $friday_to,
	            		'friday24hours'=> $friday24hours,
	            		'fridayWorking'=> $fridayWorking,
	            		'saturday_from'=> $saturday_from,
	            		'saturday_to'=> $saturday_to,
	            		'saturday24hours'=> $saturday24hours,
	            		'saturdayWorking'=> $saturdayWorking, 
	            		);

                   	$this->Common_model->addEditRecords('working_hours',$data,array('business_page_id'=>$page_id));




				$this->session->set_flashdata('success', 'Pages updated successfully.');
				redirect('admin/user/page_list/'.$this->input->post('user_idd'));
			}
		}
		//	echo "<pre>";print_R($data);die;
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/edit_business_page');
		$this->load->view('admin/include/footer');

	}


	public function add_page()
	{
		
		$this->Common_model->check_login();
		$data['title']="Add Page | ".SITE_TITLE;
		$data['page_title']="Add Page";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Page',
			'link' => ""
		);
		$data['countries'] = $this->Common_model->getRecords('countries','*');
		$data['categories'] = $this->Common_model->getRecords('categories','*');
		$data['sub_categories'] = $this->Common_model->getRecords('sub_categories','*');
		$data['user_idd'] =$this->uri->segment(4);;
		

		if($this->input->post()) 
		{ 




			$user_id = $this->input->post('user_idd');
			// echo "<pre>";print_r($this->input->post());die;
			$count = $this->Common_model->getRecords('business_page','user_id',array('user_id' =>$this->input->post('user_idd'),'is_deleted'=>'0'));
			$count_ar =count($count);
		 
			if($user_id!=1 && $count_ar > 9)
			{
				$this->session->set_flashdata('error', 'This User Already Created 10 Pages .');
				redirect('admin/user/page_list/'.$this->input->post('user_idd'));
			}else{


			 	$newFileName = $_FILES['page_image']['name'];

			 	$address =  $this->input->post('address').' '.$this->input->post('address_2');
				$url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
				$responseJson = curl_exec($ch);
				curl_close($ch);

				$response = json_decode($responseJson);

				if ($response->status == 'OK') {

				    $latitude = $response->results[0]->geometry->location->lat;
				    $longitude = $response->results[0]->geometry->location->lng;
				} else
				{
					$address =  $this->input->post('address').' '.$this->input->post('address_2');
					$url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
					$responseJson = curl_exec($ch);
					curl_close($ch);

					$response = json_decode($responseJson);

					if ($response->status == 'OK') {

					    $latitude = $response->results[0]->geometry->location->lat;
					    $longitude = $response->results[0]->geometry->location->lng;
					} else
					{
					 	$latitude = '';
				    	$longitude ='';
					}   
				} 

			 if(!empty($newFileName ))
			 {
				$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
				// $fileExt = array_pop(explode(".", $newFileName));
				$filename = uniqid(time()).".".$fileExt;
				//set filename in config for upload

				$config['upload_path'] = 'resources/images/profile/';
				$config['allowed_types'] = 'jpg|jpeg|png';
				$config['file_name'] = $filename;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				// $this->upload->set_allowed_types('*');
				
				if (!$this->upload->do_upload('page_image')) 
				{
					echo $this->upload->display_errors();		
				}
				else
				{
					$upload_data=$this->upload->data();
					if($upload_data['file_type']!='image/svg+xml'){
						$config['image_library'] = 'gd2';
						$config['source_image'] = $upload_data['full_path'];
						$config['new_image'] = $filename;
						$config['quality'] = 100;
						$config['maintain_ratio'] = FALSE;
						$config['width']         = 150;
						$config['height']       = 150;

						$this->load->library('image_lib', $config);

						$this->image_lib->resize();
						$this->image_lib->clear();
						//unlink($upload_data['full_path']);
					}
				  	$image = 'resources/images/profile/'.$upload_data['file_name'];
				}
			}else
			{
				$image='';
			}
				if($this->input->post('sub_category2')!='')
				{
					$sub_category2 =  implode(',',$this->input->post('sub_category2'));
				}else
				{
					$sub_category2 ='';
				}
				if($this->input->post('sub_category3')!='')
				{
					$sub_category3 =  implode(',',$this->input->post('sub_category3'));
				}else
				{
					$sub_category3 ='';
				}



 				$business_name = str_replace(' ', '', $this->input->post('business_name'));
				$data = array(
                  	'business_name' => $business_name, 
					'business_full_name' => $this->input->post('business_full_name'),
					'user_id' => $this->input->post('user_idd'),
					'business_image' =>$image, 
					'category_id' => $this->input->post('category1'),
					'sub_category_id' => implode(',',$this->input->post('sub_category1')), 
					'category_id2' => $this->input->post('category2'),
					'sub_category_id2' =>$sub_category2, 
					'category_id3' => $this->input->post('category3'),
					'sub_category_id3' => $sub_category3, 
					'verification' => $this->input->post('type'),
					'zipcode' => $this->input->post('zipcode'),
					'description' => $this->input->post('description'),
					'email' => $this->input->post('email'),
					'mobile' => $this->input->post('mobile'),
					'address_1' => $this->input->post('address'),
					'address_2' => $this->input->post('address_2'),
					'latitude' => $latitude,
					'longitude' =>$longitude,
					'city_id' => $this->input->post('city'),
					'country_id' => $this->input->post('country'),
					'state_id' => $this->input->post('state'),
					'website' => $this->input->post('website'),
					'status' => $this->input->post('status'),
					'sponsored' => $this->input->post('sponsored'),
					'type' => $this->input->post('type'),
					'page_pin' => $this->input->post('pin'), 
					'created' => date("Y-m-d H:i:s"),
					'status' => 'verified',
				);
				if(!$page_id = $this->Common_model->addEditRecords('business_page', $data)) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {

					//  Check welcome offer 
					
						$welcom_offer=$this->Common_model->getRecords('subscription_plan', 'last_date_welcome_offer,subscription_plan_id,offers',array('is_welcome_offer'=>'1'),"", true);
						$time = strtotime(date("Y-m-d"));
						if(strtotime($welcom_offer['last_date_welcome_offer']) >=$time)
						{ 
							$update = array(
								'user_id' => $this->input->post('user_idd'),
								'page_id' => $page_id,
								'subscription_type' => 'welcome_offer',
								'subscription_plan_id' =>$welcom_offer['subscription_plan_id'],
								'start_date' => date("Y-m-d"),
								'end_date' => $welcom_offer['last_date_welcome_offer'],
								'offers' => $welcom_offer['offers'],
								'created'  =>  date("Y-m-d H:i:s"),
							);
							$this->Common_model->addEditRecords('subscription_user', $update);
						}else
						{ 
							$business_plan=$this->Common_model->getRecords('subscription_plan', 'month,subscription_plan_id,offers',array('subscription_plan_id'=>'1'),"", true);
						
							$add_month = '+'.$business_plan['month'].' month';
							$final = date("Y-m-d", strtotime($add_month,$time));
							$update = array(
								'user_id' => $this->input->post('user_idd'),
								'page_id' => $page_id,
								'subscription_type' => 'free_trial',
								'subscription_plan_id' =>'1',
								'start_date' => date("Y-m-d"),
								'end_date' => $final,
								'offers' => $business_plan['offers'],
								'created'  =>  date("Y-m-d H:i:s"),
							);
							$this->Common_model->addEditRecords('subscription_user',$update);
						}

 



							$sunday_from = implode(',',$this->input->post('sunday_from')); 
							// echo $sunday_from;die;
				  			if(empty($sunday_from))
				  			{
				  				$sunday_from = '';
				  			}
                            $sunday_to = implode(',',$this->input->post('sunday_to'));
                            if(empty($sunday_to))
				  			{
				  				$sunday_to = '';
				  			}
                            $sunday24hours = $this->input->post('sunday24hours');
                            if(empty($sunday24hours))
				  			{
				  				$sunday24hours = 0;
				  			}
                            $sundayWorking = $this->input->post('sundayWorking');
                            if(empty($sundayWorking))
				  			{
				  				$sundayWorking = 0;
				  			} 
                            $monday_from = implode(',',$this->input->post('monday_from'));
                            if(empty($monday_from))
				  			{
				  				$monday_from = '';
				  			}
                            $monday_to = implode(',',$this->input->post('monday_to'));
                            if(empty($monday_to))
				  			{
				  				$monday_to = '';
				  			}
                            $monday24hours = $this->input->post('monday24hours');
                            if(empty($monday24hours))
				  			{
				  				$monday24hours = 0;
				  			}
                            $mondayWorking = $this->input->post('mondayWorking');
                            if(empty($mondayWorking))
				  			{
				  				$mondayWorking = 0;
				  			}
                             
                            $tuesday_from = implode(',',$this->input->post('tuesday_from'));
                            if(empty($tuesday_from))
				  			{
				  				$tuesday_from = '';
				  			}
                            $tuesday_to = implode(',',$this->input->post('tuesday_to'));
                            if(empty($tuesday_to))
				  			{
				  				$tuesday_to = '';
				  			}
                            $tuesday24hours = $this->input->post('tuesday24hours');
                              if(empty($tuesday24hours))
				  			{
				  				$tuesday24hours = 0;
				  			}
                            $tuesdayWorking = $this->input->post('tuesdayWorking');
                              if(empty($tuesdayWorking))
				  			{
				  				$tuesdayWorking = 0;
				  			}
                             
                            $wednesday_from = implode(',',$this->input->post('wednesday_from'));
                            if(empty($wednesday_from))
				  			{
				  				$wednesday_from = '';
				  			}
                            $wednesday_to = implode(',',$this->input->post('wednesday_to'));
                            if(empty($wednesday_to))
				  			{
				  				$wednesday_to = '';
				  			}
                            $wednesday24hours = $this->input->post('wednesday24hours');
                              if(empty($wednesday24hours))
				  			{
				  				$wednesday24hours = 0;
				  			}
                            $wednesdayWorking = $this->input->post('wednesdayWorking');
                              if(empty($wednesdayWorking))
				  			{
				  				$wednesdayWorking = 0;
				  			}
                             
                            $thursday_from = implode(',',$this->input->post('thursday_from'));
                            if(empty($thursday_from))
				  			{
				  				$thursday_from = '';
				  			}
                            $thursday_to = implode(',',$this->input->post('thursday_to'));
                            if(empty($thursday_to))
				  			{
				  				$thursday_to = '';
				  			}
                            $thursday24hours = $this->input->post('thursday24hours');
                              if(empty($thursday24hours))
				  			{
				  				$thursday24hours = 0;
				  			}
                            $thursdayWorking = $this->input->post('thursdayWorking');
                              if(empty($thursdayWorking))
				  			{
				  				$thursdayWorking = 0;
				  			}
                             
                            $friday_from = implode(',',$this->input->post('friday_from'));
                            if(empty($friday_from))
				  			{
				  				$friday_from = '';
				  			}
                            $friday_to = implode(',',$this->input->post('friday_to'));
                            if(empty($friday_to))
				  			{
				  				$friday_to = '';
				  			}
                            $friday24hours = $this->input->post('friday24hours');
                              if(empty($friday24hours))
				  			{
				  				$friday24hours = 0;
				  			}
                            $fridayWorking = $this->input->post('fridayWorking');
                              if(empty($fridayWorking))
				  			{
				  				$fridayWorking = 0;
				  			}

                            $saturday_from = implode(',',$this->input->post('saturday_from'));
                            if(empty($saturday_from))
				  			{
				  				$saturday_from = '';
				  			}
                            $saturday_to = implode(',',$this->input->post('saturday_to'));
                            if(empty($saturday_to))
				  			{
				  				$saturday_to = '';
				  			}
                            $saturday24hours = $this->input->post('saturday24hours');
                              if(empty($saturday24hours))
				  			{
				  				$saturday24hours = 0;
				  			}
                            $saturdayWorking = $this->input->post('saturdayWorking');
                            if(empty($saturdayWorking))
				  			{
				  				$saturdayWorking = 0;
				  			}

                        	$data = array( 
                        		'business_page_id'=> $page_id,
                        		'sunday_from'=> $sunday_from,
                        		'sunday_to'=> $sunday_to,
                        		'sunday24hours'=> $sunday24hours,
                        		'sundayWorking'=> $sundayWorking,
                        		'monday_from'=> $monday_from,
                        		'monday_to'=> $monday_to,
                        		'monday24hours'=> $monday24hours,
                        		'mondayWorking'=> $mondayWorking,
                        		'tuesday_from'=> $tuesday_from,
                        		'tuesday_to'=> $tuesday_to,
                        		'tuesday24hours'=> $tuesday24hours,
                        		'tuesdayWorking'=> $tuesdayWorking,
                        		'wednesday_from'=> $wednesday_from,
                        		'wednesday_to'=> $wednesday_to,
                        		'wednesday24hours'=> $wednesday24hours,
                        		'wednesdayWorking'=> $wednesdayWorking,
                        		'thursday_from'=> $thursday_from,
                        		'thursday_to'=> $thursday_to,
                        		'thursday24hours'=> $thursday24hours,
                        		'thursdayWorking'=> $thursdayWorking,
                        		'friday_from'=> $friday_from,
                        		'friday_to'=> $friday_to,
                        		'friday24hours'=> $friday24hours,
                        		'fridayWorking'=> $fridayWorking,
                        		'saturday_from'=> $saturday_from,
                        		'saturday_to'=> $saturday_to,
                        		'saturday24hours'=> $saturday24hours,
                        		'saturdayWorking'=> $saturdayWorking, 
                        		);

                   	$this->Common_model->addEditRecords('working_hours',$data);   
					$this->session->set_flashdata('success', 'Pages added successfully.');
					redirect('admin/user/page_list/'.$this->input->post('user_idd'));
				}
			}
		}
	 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_business_page');
		$this->load->view('admin/include/footer');
	}

 
	public function report_page()
	{
		$this->Common_model->check_login();
		$data['title']="Report Page | ".SITE_TITLE;
		$data['page_title']="Report Page";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Report Page',
			'link' => ""
		);
		
	 	$page_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['records_result']=$this->Other_model->getReportPage($page_id);

		$data['business_page']=$this->Common_model->getRecords('business_page','is_deleted',array('business_page_id'=>$page_id),'',true);
	 	$data['block_page'] = $page_id;
	 	$data['is_deleted'] = 	$data['business_page']['is_deleted']; 
		$data['edit_action']=site_url('admin/user/edit');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/report_page_list');	
		$this->load->view('admin/include/footer');
	} 

	public function report_post()
	{
		$this->Common_model->check_login();
		$data['title']="Post  | ".SITE_TITLE;
		$data['page_title']="Post";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Post',
			'link' => ""
		);
		
	 	$page_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['records_result']=$this->Other_model->getReportPage($page_id);

		$data['business_page']=$this->Common_model->getRecords('business_page','is_deleted',array('business_page_id'=>$page_id),'',true);
	 	$data['block_page'] = $page_id;
	 	$data['is_deleted'] = 	$data['business_page']['is_deleted']; 
		$data['edit_action']=site_url('admin/user/edit');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/report_post_list');	
		$this->load->view('admin/include/footer');
	} 

	public function posts()
	{
		$this->Common_model->check_login();
		$data['title']="Post  | ".SITE_TITLE;
		$data['page_title']="Post";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Post',
			'link' => ""
		);
		
	 	$page_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['records_result']=$this->Other_model->getReportPage($page_id);

		$data['business_page']=$this->Common_model->getRecords('business_page','is_deleted',array('business_page_id'=>$page_id),'',true);
	 	$data['block_page'] = $page_id;
	 	$data['is_deleted'] = 	$data['business_page']['is_deleted']; 
		$data['edit_action']=site_url('admin/user/edit');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/report_post_list');	
		$this->load->view('admin/include/footer');
	} 

	public function admin_list()
	{
		$this->Common_model->check_login();
		$this->access('10','view');
		$admin_id = $this->session->userdata('admin_id');
		$where_page = array('admin_id'=>$admin_id,'section_id'=>'10');
		

		$data['title']="Subadmin List | ".SITE_TITLE;
		$data['page_title']="Subadmin List";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Subadmin List',
			'link' => ""
		);
		
		$dr_id = ($this->uri->segment(4)) ? $this->uri->segment(4) :'';
		
		if(empty($dr_id)){
			$where = array('user_type!='=>'Super Admin','doctor_id'=>'');
			 $user_type = $this->session->userdata('user_type');
        	if($user_type!='Super Admin'){
        		redirect(base_url('admin/dashboard'));
        	}
		}else{
			$where = array('user_type!='=>'Super Admin','doctor_id'=>$dr_id);
		}
		$data['records_results']=$this->Common_model->getRecords('admin', '*',$where,"admin_id Desc", false);
			//	$data['pagination']=$this->Common_model->paginate(site_url('admin/user/list'),$this->Common_model->getNumRecords('users','user_id'));
		
		$data['add_action']=site_url('admin/subadmin/add/'.$dr_id);
		$data['edit_action']=site_url('admin/subadmin/edit');
		$data['delete_action']=site_url('admin');
		
		

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/admin_list');	
		$this->load->view('admin/include/footer');
	}

	public function edit_admin($admin_id) {
		$this->access('10','edit');
		$this->Common_model->check_login();
		$data['title']="Edit Admin | ".SITE_TITLE;
		$data['page_title']="Edit Admin";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Admin',
			'link' => ""
		);
		$data['irg_sections'] = $this->Common_model->getRecords('irg_sections','*','','',false);
		if($data['admin_data'] = $this->Common_model->getRecords('admin','*',array('admin_id'=>$admin_id),'',true))
		{
			$data['countries'] = $this->Common_model->getRecords('countries','*');
			$data['permission'] = $this->Common_model->getPermissions($admin_id);
			if($this->input->post()) {
				$this->form_validation->set_rules('fullname', 'Fullname', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|required');
				$this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
				
				if ($this->form_validation->run() == FALSE) 
				{	
					$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
				} else {
					$update_data = array(
                      	'fullname' => $this->input->post('fullname'),
						'email' => $this->input->post('email'),
						'mobile' => $this->input->post('mobile'),
						'password' => base64_encode($this->input->post('password')),
						'modified' => date("Y-m-d H:i:s"),
					);
					if(!$this->Common_model->addEditRecords('admin', $update_data,array('admin_id'=>$admin_id))) {
						$this->session->set_flashdata('error', 'Some error occured! Please try again.');
					} else {
						$this->session->set_flashdata('success', 'updated  Admin successfully.');
						redirect('admin/subadmin/list');
					}
				}
			}
			$this->load->view('admin/include/header',$data);
			$this->load->view('admin/include/sidebar');
			$this->load->view('admin/edit_admin');
			$this->load->view('admin/include/footer');
		}
		else
		{
			$this->session->set_flashdata('error', 'Some error occured! Please try again.');
			redirect('admin/subadmin/list', 'refresh');
		}
	
	} 


	public function add_admin() {
		$this->access('10','add');
		$this->Common_model->check_login();
		$data['title']="Add Admin | ".SITE_TITLE;
		$data['page_title']="Add Admin";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Admin',
			'link' => ""
		);
	
		$dr_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;	
		$data['countries'] = $this->Common_model->getRecords('countries','*');
		$data['permission'] = $this->Common_model->getPermissionsadd();
		if($this->input->post()) {
			$this->form_validation->set_rules('fullname', 'Fullname', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required');
			$this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
				
				
			if ($this->form_validation->run() == FALSE) 
			{	
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
			} else {

				if($_FILES['image']['name'] =='') { 
					$this->session->set_flashdata('error', "Please upload image.");
				} else {
					if($_FILES['image']['error']==0) {
						$image_path = 'resources/images/profile/';
						$allowed_types = 'jpg|jpeg|png|svg';
						$file='image';
						$height = 150;
						$width = 150;
						$responce = commonImageUpload($image_path,$allowed_types,$file,$width,$height);
					
						if($responce['status']==0){
							$data['upload_error'] = $responce['msg'];	
						} else {
							$update_data = array(
		                      	'doctor_id' => $dr_id,
		                      	'fullname' => $this->input->post('fullname'),
								'email' => $this->input->post('email'),
								'username' => $this->input->post('username'),
								'password' => base64_encode($this->input->post('password')),
								'mobile' => $this->input->post('mobile'),
								'status' => 'Active',
								'user_type' => 'Admin',
								'profile_pic'  => $responce['image_path'],
								'created' => date("Y-m-d H:i:s"),
							); 
							if(!$admin_id=$this->Common_model->addEditRecords('admin', $update_data)) {
								$this->session->set_flashdata('error', 'Some error occured! Please try again.');
								redirect('admin/subadmin/list');
							} else { 
								$this->session->set_flashdata('success', 'Add Admin successfully.');
								// echo $this->db->last_query();die;

									redirect('admin/subadmin/list');
							}
						}
					}			
					redirect('admin/subadmin/list');
				}
			}
		}
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_admin');
		$this->load->view('admin/include/footer');
	} 
	

	public function user_access($admin_id) 
	{
		$this->Common_model->check_login();
		
		$sections = $this->input->post('sections');
		$permission = $this->input->post('permission');
		
		foreach($sections as $section_id) {
			if(isset($permission[$section_id])) {
				if(isset($permission[$section_id]['view'])) {
					$view = 1;
				} else {
					$view = 0;
				}

				if(isset($permission[$section_id]['add'])) {
					$add = 1;
				} else {
					$add = 0;
				}

				if(isset($permission[$section_id]['edit'])) {
					$edit = 1;
				} else {
					$edit = 0;
				}

				if(isset($permission[$section_id]['delete'])) {
					$delete = 1;
				} else {
					$delete = 0;
				}
			} else {
				$view = 0;
				$add = 0;
				$edit = 0;
				$delete = 0;
			}


			$date = date("Y-m-d H:i:s");
			
		
			$update_data = array(
				'add'=>$add,
				'edit'=>$edit,
				'delete'=>$delete,
				'view'=>$view,
				'modified'=>$date
			);
			$where = array('admin_id'=>$admin_id,'section_id'=>$section_id);

			$this->Common_model->addEditRecords('irg_user_access', $update_data,$where);
		}
		redirect($_SERVER['HTTP_REFERER']);
	}



	public function documents()
	{ 
		$this->Common_model->check_login();
		$data['title']="Documents List | ".SITE_TITLE;
		$data['page_title']="Documents List";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Documents List',
			'link' => ""
		);
		
	 	$page_id = $this->uri->segment(4); 
		$where = array('page_id'=>$page_id);
		$data['records_result']=$this->Common_model->getRecords('user_document', '*',$where,"document_id Desc", false);

		$where_user  = array('business_page_id'=>$page_id);
		$business_user_id=$this->Common_model->getRecords('business_page', 'user_id',$where_user,"", true);

		$data['page_id'] = $page_id;
		$data['user_id'] = $business_user_id['user_id'];
		$data['add_action']=site_url('admin/user/add');
		$data['edit_action']=site_url('admin/user/edit');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/documents_list');	
		$this->load->view('admin/include/footer');

	}

	public function add_document() 
	{
		$this->Common_model->check_login();
		$data['title']="Add Document | ".SITE_TITLE;
		$data['page_title']="Add Document";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Document',
			'link' => ""
		);	
		$page_id = $this->uri->segment(4); 
		$where_user  = array('business_page_id'=>$page_id);
		$business_user_id=$this->Common_model->getRecords('business_page', 'user_id',$where_user,"", true);

		$data['page_id'] = $page_id;
		$data['user_id'] = $business_user_id['user_id'];
		if($this->input->post()) {
			$user_id =  $this->input->post('user_id');
			$page_id =  $this->input->post('page_id');
			$coun_pr_doc=$this->Common_model->getRecords('user_document', 'document_id',array('user_id'=>$user_id,'page_id'=>$page_id),"document_id Desc", false);
			$counts = count($_FILES['doc']['name']);
			if(empty($coun_pr_doc))
			{
				$count = $counts;
			}else
			{
				$count = $counts+count($coun_pr_doc);
			}
			if($count < 6 ){
		    	$photo = 0;
				$allowed =  array('jpg','png','jpeg','JPG','PNG','JPEG','xlsx','csv','xls','pdf','doc','docx');
				$filesCount = count($_FILES['doc']['name']);
		        for($i = 0; $i <$filesCount; $i++){
					$filename = $_FILES['doc']['name'][$i];
				    $ext = pathinfo($filename, PATHINFO_EXTENSION);
				    if(!in_array($ext,$allowed) ) {
					 
					   	$this->session->set_flashdata('error', "Only Doc file / PDF file / Excel file / JPG JPEG PNG Images types allowed..");	 
				   	 	$error = 1;  
			    }
			}
		    }else
		    {
				 $this->session->set_flashdata('error', 'Please Upload max 5 Document.');
				 $error = 1; 
		    }

	    		if($error!= 1){
			  		if(!empty($_FILES['doc'])) {
						$filesCount = count($_FILES['doc']['name']);
						for($i = 0; $i <$filesCount; $i++){
							$_FILES['page_img']['name'] = $_FILES['doc']['name'][$i];
							$_FILES['page_img']['type'] = $_FILES['doc']['type'][$i];
							$_FILES['page_img']['tmp_name'] = $_FILES['doc']['tmp_name'][$i];
							$_FILES['page_img']['error'] = $_FILES['doc']['error'][$i];
							$_FILES['page_img']['size'] = $_FILES['doc']['size'][$i];
							
							//Rename image name 
							$img = time().'_'.rand();

							$config['upload_path'] = 'resources/documents/';
							//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
							$config['allowed_types'] = 'jpg|png|jpeg|JPG|PNG|JPEG|xlsx|csv|xls|pdf|doc|docx';
							$config['file_name'] =  $img;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload('page_img')){
								 
								$fileData = $this->upload->data();
								$uploadData = array();
								$uploadData= array(
									'image' => 'resources/documents/'.$config['file_name'].$fileData['file_ext'],
									'document_name' => $this->input->post('name'),
									'user_id' => $user_id,
									'page_id' => $page_id,
									'created' => date("Y-m-d H:i:s"),
									);
							 	$this->Common_model->addEditRecords('user_document', $uploadData);
							 
							} else {
							 	$this->session->set_flashdata('error', $this->upload->display_errors());
							}
						} 
							$this->session->set_flashdata('success', 'Document uploaded');
						 	redirect('admin/user/documents/'.$page_id, 'refresh');
					}
				}else
				{
					redirect('admin/user/add_document/'.$page_id, 'refresh');
				} 

		}
	 
		$data['from_action']=site_url('admin/user/add_document');
		$data['back_action']=site_url('admin/user/documents/'.$page_id);

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_document');
		$this->load->view('admin/include/footer');
	}

	public function block_reason()
	{

	 	$this->Common_model->check_login();
		$data['title']="Block Reason | ".SITE_TITLE;
		$data['page_title']="Block Reason";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Block Reason',
			'link' => ""
		);
		$data['user_id'] = $this->uri->segment(4);
		$data['block_reason']=$this->Common_model->getRecords('block_reason', '*',array('user_id'=>$data['user_id']) ,"id Desc", false);
		
		$data['add_action']=site_url('admin/user/add_reason');
		$data['edit_action']=site_url('admin/user/block_reason');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/block_reason');	
		$this->load->view('admin/include/footer');
	 
	}



	public function add_reason() 
	{
		$this->Common_model->check_login();
		$data['title']="Add Block Reason | ".SITE_TITLE;
		$data['page_title']="Add Block Reason";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Block Reason',
			'link' => ""
		);	
		$user_id = $this->uri->segment(4); 
		$data['user_id'] = $user_id;
		
		if($this->input->post()) {
			$user_id =  $this->input->post('user_id');
			$reason =  $this->input->post('reason');

			$uploadData= array(
			
				'user_id' => $user_id,
				'block_reason' => $reason,
				'created' => date('Y-m-d H:i:s'),
				);
							
			if($this->Common_model->addEditRecords('block_reason', $uploadData))
			{
				$user_data=$this->Common_model->getRecords('users', '*',array('user_id'=>$user_id) ,"", true);
				 
			  	$from_email = getAdminEmail(); 
			  	$to_email = $user_data['email']; 
				
				$subject = "Account Deactivated";
				  $data['username']= ucwords($user_data['username']);
				   $data['message'] = 'Your account has been deactivated.<br> <b>Reason :</b>'.$reason.'<br>Please contact us for more details. ';	
				
				  $body = $this->load->view('template/common', $data,TRUE); 
				$mail = $this->Common_model->defaultEmailSend($to_email,$subject,$body,$from_email);
				 
				$this->session->set_flashdata('success', 'Reason sent');
			 	redirect('admin/user/block_reason/'.$user_id, 'refresh');
			}

		}
	 
		$data['from_action']=site_url('admin/user/add_reason');
		$data['back_action']=site_url('admin/user/block_reason/'.$user_id);

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_reason.php');
		$this->load->view('admin/include/footer');
	}

	public function free_subscription($id){
			$where_page = array('business_page_id'=>$id);
			$recordss=$this->Common_model->getRecords('business_page', 'user_id,business_page_id',$where_page,"", true);
			$business_plan=$this->Common_model->getRecords('subscription_plan', 'subscription_plan_id,offers',array('subscription_plan_id'=>'1'),"", true);
			$time = strtotime(date("Y-m-d"));
			$final = date("Y-m-d", strtotime("+1 month",$time));
			$update = array(
				'user_id' => $recordss['user_id'],
				'page_id' => $recordss['business_page_id'],
				'subscription_plan_id' =>'1',
				'start_date' => date("Y-m-d"),
				'end_date' => $final,
				'offers' => $business_plan['offers'],
				'created'  =>  date("Y-m-d H:i:s"),
			);
			$this->Common_model->addEditRecords('subscription_user', $update);
			$update_data = array('free_page' => '1');
			$this->Common_model->addEditRecords('users', $update_data,array('user_id'=>$recordss['user_id']));
			$this->session->set_flashdata('success', '1 Month Free Subscription ADD successfully.');
			redirect($_SERVER['HTTP_REFERER']);
			
	}


	public function offers_list()
	{ 
		$this->Common_model->check_login();
		$data['title']="Offers List | ".SITE_TITLE;
		$data['page_title']="Offers List";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Offers List',
			'link' => ""
		);
		
		$id = $this->uri->segment(4); 
		$where = array('business_page_id'=>$id,'is_deleted'=>'0'); 
		$offers_list=$this->Common_model->getRecords('business_offers', '*',$where,"business_offers Desc", false);

		$index=0;
		foreach ($offers_list as $key) {
			$data['records_result'][$index] = $key;
		 

			$page_name=$this->Common_model->getRecords('business_page', 'business_name',array('business_page_id'=>$key['business_page_id']),"", true);

			if(!empty($page_name))
			{
				$data['records_result'][$index]['page_name'] = $page_name['business_name']; 
			}else
			{
				$data['records_result'][$index]['page_name'] = 'N/A';
			}

			$user_name=$this->Common_model->getRecords('users', 'username',array('user_id'=>$key['created_by_user']),"", true);

			if(!empty($user_name))
			{

				$data['records_result'][$index]['username'] = $user_name['username']; 
			}else
			{
				$data['records_result'][$index]['username'] = 'N/A';
			}

			if($key['offers_type']=='multi_buy')
			{
				$data['records_result'][$index]['offers_type'] ='Multi Buy';
			}

			if($key['offers_type']=='standard_discount')
			{
				$data['records_result'][$index]['offers_type'] ='Standard Discount';
			}
			

			$redeem_offers=$this->Common_model->getRecords('redeem_offers', 'id',array('offer_id'=>$key['business_offers']),"", false);

			if(!empty($redeem_offers))
			{
				$data['records_result'][$index]['redeem_offers'] = 'yes'; 
			}else
			{
				$data['records_result'][$index]['redeem_offers'] = 'no';
			}
			$index++;
		}

		$data['user_id'] = $id;
		$data['add_action']=site_url('admin/user/add');
		$data['edit_action']=site_url('admin/user/edit');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/offer_list');	
		$this->load->view('admin/include/footer');

	}


	public function offer_details() 
	{
		$this->Common_model->check_login();
		$id = $this->uri->segment(4);
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Offer view | ".SITE_TITLE;
		$data['page_title']="Offer view";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'',
			'title' => 'Offer details',
			'link' => site_url('admin/user/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Page details',
			'link' => ""
		);	
	 	
		if(!$offer=$this->Common_model->getRecords('business_offers','*',array('business_offers'=>$id),'',true)) {
			redirect('pages/page_not_found');
		} 


 		if($offer['offers_type']=='multi_buy')
 		{

 		 $offer['offers_type'] = 'Multi Buy';

 		 	$multi_buy=$this->Common_model->getRecords('multi_buy', '*',array('business_offers_id'=>$offer['business_offers']),"", true);

 		 	 $offer['discount_type'] = $multi_buy['discount_type'];
 		 	 $offer['note'] = $multi_buy['note'];
 		 	 $offer['tandc'] = $multi_buy['tandc'];
    
 		}
 		if($offer['offers_type']=='standard_discount')
 		{
			$offer['offers_type'] = 'Standard Discount';
			$standard_discount=$this->Common_model->getRecords('standard_discount', '*',array('business_offers_id'=>$offer['business_offers']),"", true);
			$offer['discount_type'] = $standard_discount['discount_type'];
			$offer['note'] = $standard_discount['product_note'];
			$offer['discount_value'] = $standard_discount['discount_value'];
			$offer['tandc'] = $standard_discount['tandc'];
			$offer['product_name'] = $standard_discount['product_name'];
			$offer['product_description'] = $standard_discount['product_description'];
 		 
 		}
 	 
 		$page_name=$this->Common_model->getRecords('business_page', 'business_name',array('business_page_id'=>$offer['business_page_id']),"", true);

		if(!empty($page_name))
		{
			$offer['page_name'] = $page_name['business_name']; 
		}else
		{
			$data['page_name'] = 'N/A';
		}

		$user_name=$this->Common_model->getRecords('users', 'username',array('user_id'=>$offer['created_by_user']),"", true);

		if(!empty($user_name))
		{

			$offer['username']  = $user_name['username']; 
		}else
		{
			$offer['username']  = 'N/A';
		}
	
		if(!empty($offer['city_id']))
		{

			$city =	explode(',', $offer['city_id']);
			foreach ($city as $value) {

				$city_name=$this->Common_model->getRecords('cities ', 'name',array('id'=>$value),"", true);
			 	$city_names[] = $city_name['name'];
			}
			$offer['city_name'] = implode(', ',$city_names);

		}else
		{
			$offer['city_name'] ='N/A';
		}
		if(!empty($offer['state_id']))
		{
			$state =	explode(',', $offer['state_id']);
			foreach ($state as $value) {
				$states_name=$this->Common_model->getRecords('states ', 'name',array('id'=>$value),"", true);
			 	$states_names[] = $states_name['name'];
			}
			$offer['states_names'] = implode(', ',$states_names);
		}else
		{
			$offer['states_names'] ='N/A';
		}

		if(!empty($offer['country_id']))
		{
			$countries =	explode(',', $offer['country_id']);
			foreach ($countries as $value) {
				$countries_name=$this->Common_model->getRecords('countries ', 'name',array('id'=>$value),"", true);
			 	$countries_na[] = $countries_name['name'];
			}
			$offer['country_names'] = implode(', ',$countries_na);
		}else
		{
			$offer['country_names'] ='N/A';
		}
		if(empty($offer['exp_date']))
		{
			$offer['exp_date'] ='N/A';	
		}

		$offer['offer_image']=$this->Common_model->getRecords('offers_images','*',array('offer_id'=>$id),'',false);
		$data['page'] =$offer;
		
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/offer_details');
		$this->load->view('admin/include/footer');

	}



	public function redeem_offers()
	{ 
		$this->Common_model->check_login();
		$data['title']="Offer Redeem | ".SITE_TITLE;
		$data['page_title']="Offer Redeem";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Offers Redeem',
			'link' => ""
		);
		
		$id = $this->uri->segment(4); 
		$page = $this->uri->segment(5); 
		if(empty($page))
		{
			$where = array('offer_id'=>$id); 
		}else
		{
			$where = array('page_id'=>$id); 
		}
	
		$offers_list=$this->Common_model->getRecords('redeem_offers', '*',$where,"id Desc", false);
 
		$index=0;
		foreach ($offers_list as $key) {
			$data['records_result'][$index] = $key;
		 

			$user_name=$this->Common_model->getRecords('users', 'username',array('user_id'=>$key['user_id']),"", true);
 
			$data['records_result'][$index]['user_name'] = $user_name['username']; 
		
 
			$geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?Key='.GOOGLE_API_KEY.'&latlng='.$key['latitude'].','.$key['longitude'].'&sensor=false');

		        $output= json_decode($geocode);
		 
		    for($j=0;$j<count($output->results[0]->address_components);$j++){
               	$cn=array($output->results[0]->address_components[$j]->types[0]);
				if(in_array("locality", $cn))
				{
					 $city_name= $output->results[0]->address_components[$j]->long_name;
				}
				if(in_array("administrative_area_level_1", $cn))
				{
					$state_name= $output->results[0]->address_components[$j]->long_name;
				}	if(in_array("country", $cn))
				{
				  	$country_name=  $output->results[0]->address_components[$j]->long_name;
				}
            }
        	if(!empty( $city_name))
        	{	
				$data['records_result'][$index]['city_name'] = $city_name;  
			}else
			{
				$data['records_result'][$index]['city_name'] = 'N/A';  
			}
			if(!empty( $state_name))
        	{	
				$data['records_result'][$index]['state_name'] = $state_name;  
			}else
			{
				$data['records_result'][$index]['state_name'] = 'N/A';  
			}
			if(!empty( $country_name))
        	{	
				$data['records_result'][$index]['country_name'] = $country_name;  
			}else
			{
				$data['records_result'][$index]['country_name'] = 'N/A';  
			}

		 
			$index++;
		}
		$data['user_id'] = $id;
		$data['add_action']=site_url('admin/user/add');
		$data['edit_action']=site_url('admin/user/edit');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/offer_redeem.php');	
		$this->load->view('admin/include/footer');

	}

	public function review($page_id) 
	{	$this->Common_model->check_login();
		$data['title']="Review Rating| ".SITE_TITLE;
		$data['page_title']="Review Rating";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Review Rating',
			'link' => ""
		);	

		$where = array('business_page_id' => $page_id);

		$redeem=$this->Common_model->getRecords('business_page','business_name,disply_rating,category_id',$where,'',true);
		$tableName = 'rating_categories';
		$where = array('category_id' => $redeem['category_id']);
		$disply_rating ='';
		$rat = $this->Common_model->getRecords($tableName,'rating_categories_id,name',$where,'',false);
		
		for($i=0;$i<count($rat);$i++){
			$rat[$i]['rating'] =  number_format($this->Admin_model->business_rating_one($page_id,$disply_rating,$rat[$i]['rating_categories_id']),2); 

		}
	    $data['rating'] =  $rat;
		$data['totleRating'] =  number_format($this->Admin_model->business_rating($page_id,$disply_rating),2);
		$data['count'] = $this->Admin_model->business_rating_count($page_id,$disply_rating);
		$data['review'] = $this->Admin_model->business_review($page_id,$disply_rating);
 		$data['business_name'] =$redeem['business_name'];
 		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/review.php');
		$this->load->view('admin/include/footer');
	//	redirect($_SERVER['HTTP_REFERER']);
	}



	public function redeem_offers_time()
	{ 
		$this->Common_model->check_login();
		$data['title']="Offer Redeem | ".SITE_TITLE;
		$data['page_title']="Offer Redeem";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Offers Redeem',
			'link' => ""
		);
		
		$id = $this->uri->segment(4); 
		$where = array('page_id'=>$id); 
 		
 	 	$time = array( 	
			'09:00:00-11:59:59',
			'12:00:00-14:59:59',
			'15:00:00-17:59:59',
			'18:00:00-20:59:59',
			'21:00:00-23:59:59',
			'00:00:01-02:59:59',
			'03:00:00-05:59:59',
			'06:00:00-08:59:59'
		);
      	$count =0;
      	$total_per=0;
      	$index=0;
      	$time_record = array();
      	$all_records= array();
      	foreach ($time as $times) {
	        $explode = explode('-',$times);
	        if($time_record = $this->Common_model->analytics($id,$explode[0],$explode[1])) {
	        	foreach($time_record as $list){
	        		$list['start_time']=$explode[0]; 
	        		$list['end_time']=$explode[1]; 
	        		$list['time_slot']=$times; 
					$all_records[$index] = $list;
					$index++;
		        }
	        }
		}
		  
		$index=0;
		foreach ($all_records as $key) {
			$data['records_result'][$index] = $key;
			$user_name=$this->Common_model->getRecords('users', 'username',array('user_id'=>$key['user_id']),"", true);
			$data['records_result'][$index]['user_name'] = $user_name['username']; 
			$geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?Key='.GOOGLE_API_KEY.'&latlng='.$key['latitude'].','.$key['longitude'].'&sensor=false');
		    $output= json_decode($geocode);
		 
		    for($j=0;$j<count($output->results[0]->address_components);$j++){
               	$cn=array($output->results[0]->address_components[$j]->types[0]);
				if(in_array("locality", $cn))
				{
					 $city_name= $output->results[0]->address_components[$j]->long_name;
				}
				if(in_array("administrative_area_level_1", $cn))
				{
					$state_name= $output->results[0]->address_components[$j]->long_name;
				}	if(in_array("country", $cn))
				{
				  	$country_name=  $output->results[0]->address_components[$j]->long_name;
				}
            }
        	if(!empty( $city_name))
        	{	
				$data['records_result'][$index]['city_name'] = $city_name;  
			}else
			{
				$data['records_result'][$index]['city_name'] = 'N/A';  
			}
			if(!empty( $state_name))
        	{	
				$data['records_result'][$index]['state_name'] = $state_name;  
			}else
			{
				$data['records_result'][$index]['state_name'] = 'N/A';  
			}
			if(!empty( $country_name))
        	{	
				$data['records_result'][$index]['country_name'] = $country_name;  
			}else
			{
				$data['records_result'][$index]['country_name'] = 'N/A';  
			}
	
			$index++;
		}
		$data['user_id'] = $id;
		$data['add_action']=site_url('admin/user/add');
		$data['edit_action']=site_url('admin/user/edit');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/offer_redeem_time.php');	
		$this->load->view('admin/include/footer');

	}

	public function template()
	{
		$this->Common_model->check_login();
		$this->access('16','view');
		$data['title']="Template List | ".SITE_TITLE;
		$data['page_title']="Template List";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Template List',
			'link' => ""
		);
		 
		$data['records_results']=$this->Common_model->getRecords('mail_templete', '*', "","id Desc", false);
  		$admin_id = $this->session->userdata('admin_id');
		$data['edit_action']=site_url('admin/edit_template');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/templete_list');	
		$this->load->view('admin/include/footer');
	} 


	public function edit_template() 
	{
		$this->Common_model->check_login();
		$this->access('16','edit');
		$id = $this->uri->segment(3);

		$data['title']="Edit Template | ".SITE_TITLE;
		$data['page_title']="Edit Template";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		);
	 	 
		 
        if($this->input->post()) {  
			$update_data = array( 
				'message' => $this->input->post('message'), 
				'title' => $this->input->post('title'), 
				'subject' => $this->input->post('subject'), 
			);
			$this->Common_model->addEditRecords('mail_templete', $update_data,array('id'=>$this->input->post('temp_id')));
			$this->session->set_flashdata('success', 'Template updated successfully.');
			redirect('admin/template');
		}
		if(!$data['templete']=$this->Common_model->getRecords('mail_templete','*',array('id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}
		$data['from_action']=site_url('admin/edit_template/'.$id);
		$data['back_action']=site_url('admin/template');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/edit_templete');
		$this->load->view('admin/include/footer');

	}



	public function appointment() 
	{
		$this->Common_model->check_login();
		$data['title']="Appointment | ".SITE_TITLE;
		$data['page_title']="Appointment";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		); 
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Appointment List',
			'link' => ""
		);	
		$admin_id =$this->session->userdata('admin_id');
		$dr_id = $this->uri->segment('4');
		$data['records_results'] = $this->Common_model->appointmentList($dr_id);
		// $data['add_action'] = base_url().'admin/appointment/add';
		// echo $this->db->last_query();
		// echo "<pre>";print_r($data['records_results']);die;
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/appointment_list');
		$this->load->view('admin/include/footer');
	}	

	public function feedback() 
	{
		$this->Common_model->check_login();
		$data['title']="Feedback | ".SITE_TITLE;
		$data['page_title']="Feedback";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		); 
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Feedback List',
			'link' => ""
		);	
		$admin_id =$this->session->userdata('admin_id');
		$dr_id = $this->uri->segment('4');
		$data['records_results'] = $this->Common_model->feedbackList($dr_id);
		// $data['add_action'] = base_url().'admin/appointment/add';
		// echo $this->db->last_query();
		// echo "<pre>";print_r($data['records_results']);die;
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/feedback_list');
		$this->load->view('admin/include/footer');
	}	
	public function feedback_view() 
	{
		$this->Common_model->check_login();
		$data['title']="Feedback Detail | ".SITE_TITLE;
		$data['page_title']="Feedback Detail";
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'icon'=>'<i class="fa fa-dashboard"></i>',
			'class'=>'',
			'title' => 'Dashboard',
			'link' => site_url('admin/dashboard')
		); 
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Feedback Detail',
			'link' => ""
		);	
		$admin_id =$this->session->userdata('admin_id');
		$id = $this->uri->segment('5');
		$data['records_result']=$this->Common_model->getRecords('feedback', '*',array('id'=>$id),"id Desc", true);
		// echo $this->db->last_query();
		// echo "<pre>";print_r($data['records_result']);die;
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/feedback_view');
		$this->load->view('admin/include/footer');
	}	

	function add_doctor()
	{
	 	$patient_id =$this->uri->segment(4);
		$dr_id = $this->session->userdata('doctor_id');
			$insert_data = array( 
              	'dr_id' => $dr_id, 
				'created' => date("Y-m-d H:i:s"),
				'created_by' =>$this->session->userdata('admin_id'),
			);
			 //echo "<pre>"; print_r($insert_data);exit;
	 		if(!$id=$this->Common_model->addEditRecords('users', $insert_data,array('user_id' => $patient_id))) {
				$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				redirect('admin/user/users_list/2');
			} else { 
				$this->session->set_flashdata('success', 'User Added Successfully.');
				redirect('admin/user/users_list/2');		
		    }

	}


} // class end