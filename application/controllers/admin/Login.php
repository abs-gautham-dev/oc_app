<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin/login_model');
		$this->load->model('admin/Common_model');
		$this->load->helper('common_helper');
		$this->load->library('session');

	}
	
	public function index() {
		$data['title']="Admin Login | ".SITE_TITLE;
		if($this->session->userdata('admin_id')!= FALSE) 	{
			redirect(base_url()."admin/dashboard/");
		}
		$error='';
		if($this->input->post('login')) {
			$where=array(
				'username'=>$this->input->post('username'),
				'password'=>base64_encode($this->input->post('password'))
			);
	
			if($admin_data = $this->Common_model->getRecords('admin','doctor_id,admin_id,user_type,username,profile_pic,status',$where,'',true)) {
				//echo "<pre>";print_r($admin_data); exit;
                if($admin_data['status'] == 'Active'){
                $login_session=array( 	
					'admin_id'=>$admin_data['admin_id'],
					'user_type'=> $admin_data['user_type'],
					'user_name'=>$admin_data['username'],
					'profile_pic'=>$admin_data['profile_pic'],
					'doctor_id'=>$admin_data['doctor_id']
				);
				$this->session->set_userdata($login_session);
				$this->session->set_flashdata('success', 'Logged In Successfully.');
				redirect("admin/dashboard");	
                }
                else
				{
					$this->session->set_flashdata('error', 'Acount Not Active.');
				}
				
			}
			else
			{
				$this->session->set_flashdata('error', 'Incorrect Username or Password.');
			}
		}
		
		$this->load->view('admin/login',$data);
	}
	// logout for admin user
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url()."admin/login");
	}
	

	public function change_password()
	{
		$this->Common_model->check_login();
		$data['title']="Change Password | ".SITE_TITLE;
		$data['page_title']="Change Password";
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
			'title' => 'Change Password',
			'link' => ""
		);
		$admin_id = $this->session->userdata('admin_id');
		
		$current_password = base64_decode($this->Common_model->getFieldValue('admin', 'password', array('admin_id'=>$admin_id)));
		$data['current_password'] = $current_password;
		if($this->input->post()) 
		{	
			
			$this->form_validation->set_rules('old_password','Old Password','required|trim');
			$this->form_validation->set_rules('new_password','New Password','trim|required|min_length[6]|max_length[12]');
			$this->form_validation->set_rules('confirm_password','Confirm Password','trim|required|matches[new_password]');

			if ($this->form_validation->run() == FALSE)
			{	
				$this->form_validation->set_error_delimiters('<div class="parsley-errors-list">', '</div>');
				//$this->load->view('admin/change_password',$data);
			} 
		 	else 
		 	{
		 		if($current_password == $this->input->post('old_password')) {
			 		$new_password = base64_encode($this->input->post('new_password'));
		 			$where = array('admin_id'=>$admin_id);
		 			$date = date("Y-m-d H:i:s");
	 				$update_data = array(
	 					'password' => $new_password,
						'modified'=>$date
					);
						
			 		if(!$this->Common_model->addEditRecords('admin', $update_data, $where)) {
						$this->session->set_flashdata('error', 'Some error occured! Please try again.');
					} else {
						$this->session->set_flashdata('success', 'Password Changed Successfully.');
					}
				} else {
					$this->session->set_flashdata('error', 'Current password is incorrect.');
				}
				redirect('admin/change_password');
		 	}
		} 

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/change_password');
		$this->load->view('admin/include/footer');
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
				//echo "<pre>";print_r($this->input->post()); exit;
				$this->form_validation->set_rules('fullname', 'Fullname', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|required');
				$this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required');
				$this->form_validation->set_rules('address', 'Address', 'trim');
				$this->form_validation->set_rules('country', 'Country', 'trim');
				$this->form_validation->set_rules('state', 'State', 'trim');
				$this->form_validation->set_rules('city', 'City', 'trim');
				$this->form_validation->set_rules('zip_code', 'Zip Code', 'trim');
				
				
				if ($this->form_validation->run() == FALSE) 
				{	
					$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
				} else {
					$update_data = array(
                      	'fullname' => $this->input->post('fullname'),
						'email' => $this->input->post('email'),
						'mobile' => $this->input->post('mobile'),
						'address' => $this->input->post('address'),
						'country' => $this->input->post('country'),
						'state' => $this->input->post('state'),
						'city' => $this->input->post('city'),
						'zipcode' => $this->input->post('zip_code'),
						'dr_price' => $this->input->post('dr_price'),
						'acount_info' => $this->input->post('acount_info'),
						'patient_price' => $this->input->post('patient_price'),
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
			//echo "<pre>"; print_r($data); exit;
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

	public function forgot_password()
	{	
		$data['title']="Forgot Password | ".SITE_TITLE;
		$data['page_title']="Forgot Password";
		if($this->input->post('email')) 
		{
			// echo $this->input->post('email');exit;
			$this->form_validation->set_rules('email', 'Email', 'trim|required');
			if ($this->form_validation->run() == FALSE) {	
				$this->form_validation->set_error_delimiters('<div class="parsley-errors-list">', '</div>');
			} else {
				if(!$user_data= $this->Common_model->getRecords('admin','fullname,email',array('email'=> $this->input->post('email')),'',true)) {
					if(!$user_data= $this->Common_model->getRecords('admin','fullname,email',array('username'=> $this->input->post('email')),'',true)) {
						$this->session->set_flashdata('error', 'Please enter registered email or username.');
						redirect('admin/forgot_password');
					}
				}
				// echo "<pre>"; print_r($user_data); exit;
				$token = md5(uniqid(rand(), true));
				// $from_email = getAdminEmail(); 
				$to_email = $user_data['email']; 
				
				$subject = "Reset Password Link";
				$data['reset_password_url'] = base_url().'admin/reset_password?token='.$token;
				$data['name']= ucwords($user_data['fullname']);
				$data['type'] = 'admin';	
				$from_email = 'info@follup.online'; 
					$this->Common_model->setMailConfig();
				
				$body = $this->load->view('template/forgot_password', $data,TRUE);


				if($this->Common_model->sendEmail($to_email,$subject,$body,$from_email)) 
				{
					$reset_token_date = date("Y-m-d H:i:s");
					$where = array('email'=> $user_data['email']); 
	 				$update_data = array(
	 					'reset_token'=>$token, 
	 					'reset_token_date'=>$reset_token_date
	 				);
	 			 
					$this->Common_model->addEditRecords('admin', $update_data, $where);
					$this->session->set_flashdata('success', 'Reset password link sent on your email address, Please check your inbox and spam.');
				} else {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				}
			}
		} 
		$this->load->view('admin/forgot_password',$data);	
		
	}

	public function reset_password()
	{
		$data['title'] = "Reset Password | ".SITE_TITLE;
		$data['page_title']="Reset Password";
		if($this->input->get('token')) 
		{
			$data['token'] = $this->input->get('token');
			if($user_data = $this->Common_model->getRecords('admin','admin_id,reset_token_date,password',array('reset_token'=> $this->input->get('token')),'',true)) 
			{
				if($this->input->post()) 
				{
					$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|max_length[20]');
					$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');

					if ($this->form_validation->run() == FALSE) {	
						$this->form_validation->set_error_delimiters('<div class="parsley-errors-list">', '</div>');
						$this->load->view('admin/reset_password',$data);
					} 
				 	else {
				 		
			 			if($user_data['password'] != base64_encode($this->input->post('new_password')) )
			 			{
				 			$where = array('admin_id' => $user_data['admin_id']);
					 		$update_data = array(
					 			'password' => base64_encode($this->input->post('new_password')), 
					 			'reset_token' =>'',
					 			'reset_token_date' =>''
					 		);
							if(!$this->Common_model->addEditRecords('admin', $update_data, $where)) {
								$this->session->set_flashdata('error', 'Some error occured. Please try again.');
								redirect('admin/reset_password');
							} else {
								$this->session->set_flashdata('success', 'Password Changed Successfully.');
						 		redirect('admin');
							}
						} else {
							$this->session->set_flashdata('error', "Password Can't be same as old password !!");
						 	redirect($_SERVER['HTTP_REFERER']);
						}
				 	}
				} else {
					$token_date = strtotime($user_data['reset_token_date']);
					$current_date=strtotime(date("Y-m-d H:i:s"));
					$diff=$current_date-$token_date;
					if($diff > 86400) {
						$this->session->set_flashdata('error', 'Reset password link has been Experied !!');
						redirect('admin/forgot_password');
					} else {
						$this->load->view('admin/reset_password',$data);
					}
				}
			} else {
				$this->session->set_flashdata('error', 'Invalid reset password link !!');
				redirect('admin/forgot_password');
			}	
		} else {
			$this->session->set_flashdata('error', 'Invalid reset password link !!');
			redirect('admin/forgot_password');
		}
		
	}

	
}
