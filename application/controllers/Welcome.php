<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct() {
        parent::__construct();
        error_reporting(0);
        //  ini_set('display_errors', TRUE);
        $this->load->helper(array('email','common_helper'));
        $this->load->library(array('form_validation'));
        $this->load->model('App_model');
        $this->load->model('admin/Common_model');
	
    }
	public function index()
	{
		$data['title']="Login | ".SITE_TITLE;
		$data['page_title']="Login";
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
			'title' => 'Login',
			'link' => ""
		);

		/*$user_id = $this->session->userdata('user_id');
		$user_type = $this->session->userdata('user_type');
		if(!empty($user_id)){
			if($user_type=='doctor'){
				redirect(base_url().'upload_media');
			}else{
				redirect(base_url().'doctor/list/4');
			}
		}*/

		$data['from_action'] =base_url(); 

		if($this->input->post()){

			$email 	     =  $this->input->post('email');
			$password    =  $this->input->post('password');

			$password=base64_encode($password);
	        $tableName="users";
			$where = array('email' => $email,'password' => $password);
			$res=$this->Common_model->getRecords($tableName,'*',$where,'',true);

		    if(!empty($res))  
		    {

		    	if($res['status']=="Inactive")
		    	{
		    		$err = array('data'=> array('status'=>'0','msg'=>$this->lang->line('profile_deactive')));
					echo json_encode($err);
					exit;
		    	}

		    	if($res['is_verified']==0){
		    		//$code = $this->sendotp($res['mobile']);
		    		if(!empty($code)){

		    			if($this->Common_model->addEditRecords('users',array('token'=>$code),array('user_id'=>$res['user_id']))) {
		    				$err = array('data'=> array('status'=>'2','msg'=>$this->lang->line('verification_send')),'user_id'=>$res['user_id']) ;
							echo json_encode($err);
							exit;
		    			}
		    		}
		    	}

		    	$where=array('user_id'=>$res['user_id']);
		    	$date = date('Y-m-d H:i:s');
		    	$mobile_auth_token = base64_encode(rand());
	          	$update_data = array('device_id'=>'','device_type'=>'','created'=>$date,'mobile_auth_token'=>'');
				if($resdevice=$this->Common_model->addEditRecords('users',$update_data,array('user_id'=>$res['user_id']))) {

					$res=$this->Common_model->getRecords($tableName,'*',$where,'',true);
				 	
					 $login_session=array( 	
						'user_id'=>$res['user_id'],
						'user_type'=> $res['user_type'],
						'full_name'=>$res['full_name'],
						'profile_pic'=>$res['profile_pic'],
					);
					$this->session->set_userdata($login_session); 
					$response = array('data'=> array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong>'.$this->lang->line('login_msg').'</div>'));		 
				  	echo json_encode($response);
				  	exit;	
			    } else {
			    	$err = array('data' =>array('status' => '0', 'msg' => $this->lang->line('error_message')));
					echo json_encode($err);
					exit;
			    }
			}
			else
			{

				$where = array('mobile' => $email,'password' => $password);
				$res=$this->Common_model->getRecords($tableName,'*',$where,'',true);
			  	if(!empty($res))  
			    {

			    	if($res['status']=="Inactive")
			    	{
			    		$err = array('data'=> array('status'=>'0','msg'=>$this->lang->line('profile_deactive')));
						echo json_encode($err);
						exit;
			    	}

			    	if($res['is_verified']==0){
			    		$code = $this->sendotp($res['mobile']);
			    		if(!empty($code)){

			    			if($this->Common_model->addEditRecords('users',array('token'=>$code),array('user_id'=>$res['user_id']))) {
			    				$err = array('data'=> array('status'=>'2','msg'=>$this->lang->line('verification_send'),'user_id'=>$res['user_id']));
								echo json_encode($err);
								exit;
			    			}
			    		}
		    		}

			    	$where=array('user_id'=>$res['user_id']);
			    	$date = date('Y-m-d H:i:s');
			    	$mobile_auth_token = base64_encode(rand());
		            $update_data = array('device_id'=>'','device_type'=>'','created'=>$date,'mobile_auth_token'=>'');
					if($resdevice=$this->Common_model->addEditRecords('users',$update_data,array('user_id'=>$res['user_id']))) {

						$res=$this->Common_model->getRecords($tableName,'*',$where,'',true);
					 	 $login_session=array( 	
						'user_id'=>$res['user_id'],
						'user_type'=> $res['user_type'],
						'full_name'=>$res['full_name'],
						'profile_pic'=>$res['profile_pic'],
					);
					$this->session->set_userdata($login_session); 

 
						$response = array('data'=> array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong>'.$this->lang->line('login_msg').'</div>'));		
						 
					  	echo json_encode($response);
					  	exit;	
				    } else {
				    	$err = array('data' =>array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>'.$this->lang->line('error_message').'</div>'));
						echo json_encode($err);
						exit;
				    }
				}
				else
				{

					$where = array('mobile2' => $email,'password' => $password);
					$res=$this->Common_model->getRecords($tableName,'*',$where,'',true);
					   if(!empty($res))  
					    {
					    	//echo $this->db->last_query();
					    	if($res['status']=="Inactive")
					    	{
					    		$err = array('data'=> array('status'=>'0','msg'=>$this->lang->line('profile_deactive')));
								echo json_encode($err);
								exit;
					    	}

					    	if($res['is_verified']==0){
					    		$code = $this->sendotp($res['mobile']);
					    		if(!empty($code)){

					    			if($this->Common_model->addEditRecords('users',array('token'=>$code),array('user_id'=>$res['user_id']))) {
					    				$err = array('data'=> array('status'=>'2','msg'=>$this->lang->line('verification_send'),'user_id'=>$res['user_id']));
										echo json_encode($err);
										exit;
					    			}
					    		}
					    	}

					    	$where=array('user_id'=>$res['user_id']);
					    	$date = date('Y-m-d H:i:s');
					    	$mobile_auth_token = base64_encode(rand());
				           $update_data = array('device_id'=>'','device_type'=>'','created'=>$date,'mobile_auth_token'=>'');
							if($resdevice=$this->Common_model->addEditRecords('users',$update_data,array('user_id'=>$res['user_id']))) {

								$res=$this->Common_model->getRecords($tableName,'*',$where,'',true);
					 	 		$login_session=array( 	
									'user_id'=>$res['user_id'],
									'user_type'=> $res['user_type'],
									'full_name'=>$res['full_name'],
									'profile_pic'=>$res['profile_pic'],
								);
								$this->session->set_userdata($login_session); 

								$response = array('data'=> array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong>'.$this->lang->line('login_msg').'</div>'));		
							  	echo json_encode($response);
							  	exit;	
						    } else {
						    	$err = array('data' =>array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>'.$this->lang->line('error_message').'</div>'));
								echo json_encode($err);
								exit;
						    }
						}
						else
						{

						
							
							$err = array('data' =>array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>'.$this->lang->line('worng_password').'</div>'));
							echo json_encode($err);exit;
						}
				}
			}
  
		}
 
		$this->load->view('frontend/include/header',$data);
		$this->load->view('frontend/login');
		$this->load->view('frontend/include/footer');
	}


	public function forgot_password()
	{
		$data['title']="Forgot Password | ".SITE_TITLE;
		$data['page_title']="Forgot Password";
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
			'title' => 'Forgot Password',
			'link' => ""
		);

		$user_id = $this->session->userdata('user_id');
		$user_type = $this->session->userdata('user_type');
		if(!empty($user_id)){
			if($user_type=='doctor'){
				redirect(base_url().'upload_media');
			}else{
				redirect(base_url().'doctor/list/4');
			}
		}

		$data['from_action'] =base_url(); 

		if($this->input->post()){

			$email 	     =  $this->input->post('email');
		
		
	        $tableName="users";
			$where = array('email' => $email);
			$user_data=$this->Common_model->getRecords($tableName,'*',$where,'',true);

		    if(!empty($user_data))  
		    {
	
		    	if(!empty($user_data['full_name'])) {
					$user_name = $user_data['full_name'];
				}
			 
				$subject = SITE_TITLE." Your Login Password";
			 
				$data['title']=  'Your Login Password';
				$data['username']= $user_name;
				$data['message']= '<b>Your login Password is: </b>'.base64_decode($user_data['password']);
				$body = $this->load->view('template/common', $data,TRUE);
				$to_email = $email;
				$from_email = 'info@follup.online'; 
				$this->Common_model->setMailConfig();

			 // echo $this->Common_model->sendEmail($to_email,$subject,$body,$from_email);die;
				//Send mail 
				if($this->Common_model->sendEmail($to_email,$subject,$body,$from_email)) 
				{
						$response = array('data'=> array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong>'.$this->lang->line('forgot_message').'</div>'));		
								  	echo json_encode($response);
								  	exit;	

				}else {
				    	$err = array('data' =>array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>'.$this->lang->line('error_message').'</div>'));
						echo json_encode($err);
						exit;
				    }
				
  
			}else{
				$err = array('data' =>array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>'.$this->lang->line('email_not_found').'</div>'));
							echo json_encode($err);
							exit;
			}
		}
 
		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/forgot_password');
		$this->load->view('frontend/footer');
	}


	public function signup()
	{
		$data['title']="Sign Up | ".SITE_TITLE;
		$data['page_title']="Sign Up";
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
			'title' => 'Sign Up',
			'link' => ""
		);

		$user_id = $this->session->userdata('user_id');
		$user_type = $this->session->userdata('user_type');
		if(!empty($user_id)){
			if($user_type=='doctor'){
				redirect(base_url().'upload_media');
			}else{
				redirect(base_url().'doctor/list/4');
			}
		}



		$data['from_action'] =base_url().'signup'; 
		$data['countries'] = $this->Common_model->getRecords('countries','*');
		$data['category_list'] = $this->Common_model->getRecords('categories','*',array('status'=>'Active'),'',false);

		if($this->input->post()){

				$user_type			=	$this->input->post('user_type');
			  	$full_name			=	$this->input->post('name');
			  	$email				=	$this->input->post('email');
			  	$password			=	$this->input->post('password');
			  	$mobile				=	$this->input->post('mobile');
			  	$address			=	$this->input->post('address');
			  	$pic_code			=	$this->input->post('pin_code');
			  	//$about			=	$this->input->post('about');
			  	
			  	if($user_type=='Doctor'){
			  		$category_id		=	$this->input->post('category_id');
			  	}else{
			  		$category_id		=	'';
			  	}

			  	$password= base64_encode($password);
			    $date= date('Y-m-d H:i:s');

			    if(!empty($_FILES['profile_pic']['name'])){
		     //  echo "<pre>";print_r($_FILES);exit;
		       		$newFileName = $_FILES['profile_pic']['name'];
		            $fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
		            $filename = uniqid(time()).".".$fileExt;
			        $config['upload_path'] = 'resources/images/profile/';
			        $config['file_name'] = $filename;
					$config['allowed_types'] = '*';
		            $this->load->library('upload', $config);
					$this->upload->initialize($config);
					if (!$this->upload->do_upload('profile_pic')) 
					{
						$err = array('data' =>array('status' => '0', 'msg' =>strip_tags($this->upload->display_errors())));
			            echo json_encode($err); exit; 		
					}
					else
					{
						$upload_data=$this->upload->data();
						$imagess = 'resources/images/profile/'.$upload_data['file_name'];
						
		            } 
		        }else{
		        	$imagess = 'l60Hf.png';
		        }

		        /*if (strstr( $country_code, '+') ) {
				  $mobile = $country_code.$mobile;
				} else {
				  $mobile = '+'.$country_code.$mobile;
				}

		        $code = $this->sendotp($mobile);*/
				 

		        $mobile_auth_token = base64_encode(rand());
			    $insert_data = array( 
			    	'user_type'=>$user_type,
			    	'full_name'=>$full_name,
			    	'email' => $email, 
			    	'password' => $password,
			    	'mobile' => $mobile,
			    	'payment_status' => 'Paid',
			    	'profile_pic' =>$imagess,
			    	'address' => $address,
			    	'zip_code' => $pic_code,
			    	'created'=>date('Y-m-d h:i:s'),
			    );
				
				if($user_id = $this->Common_model->addEditRecords('users',$insert_data)) {
			
					  
					/*if(!empty($email)){		 
						$subject = SITE_TITLE.': Registered successfully';
					 
						$data['title']=  'Registered successfully';
						$data['username']= $full_name;
						$data['message'] = 'You have been registered successfully.';
						$body = $this->load->view('template/common', $data,TRUE);
						$to_email = $email;
						$from_email = 'info@follup.online'; 
						$this->Common_model->setMailConfig();
		 
						//Send mail 
				 		$this->Common_model->sendEmail($to_email,$subject,$body,$from_email); 
				 	}*/

				 	
					$response = array('data'=> array('status'=>'2','msg'=>"<div class='alert alert-success'>".$this->lang->line('sign_up_success')."</div>",'user_id'=>$user_id));
				    echo json_encode($response); exit;
 
				 }else{
				 	$response = array('data'=> array('status'=>'0','msg'=>$this->lang->line('error_message')));
				    echo json_encode($response); exit;

				 }
  
		}

		$this->load->view('frontend/include/header',$data);
		$this->load->view('frontend/register');
		$this->load->view('frontend/include/footer');
	}


	public function privacy_policy(){

		$data['page_data'] = $this->Common_model->getRecords('pages','*',array('page_id'=>1),'',true);

		
		$this->load->view('admin/page_show',$data);
	 
	}

	public function privacy_policy_web(){

		$data['title']="Privacy Policy | ".SITE_TITLE;
		$data['page_title']="Privacy Policy";
		$data['page_data'] = $this->Common_model->getRecords('pages','*',array('page_id'=>2),'',true);
		$data['first'] = $this->lang->line('privacy');
		$data['second'] = $this->lang->line('policy');

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/page_show_web');
		$this->load->view('frontend/footer');	 
	}

	public function about_us(){

		$data['title']="About Us | ".SITE_TITLE;
		$data['page_title']="About Us";
		$data['page_data'] = $this->Common_model->getRecords('pages','*',array('page_id'=>1),'',true);
		$data['first'] = $this->lang->line('about');
		$data['second'] = $this->lang->line('us');

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/page_show_web');
		$this->load->view('frontend/footer');	 
	}

	public function term_and_condition(){

		$data['title']="Term and condition | ".SITE_TITLE;
		$data['page_title']="Term and condition";
		$data['page_data'] = $this->Common_model->getRecords('pages','*',array('page_id'=>1),'',true);
		$data['first'] = $this->lang->line('term');
		$data['second'] = $this->lang->line('and_condition');

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/page_show_web');
		$this->load->view('frontend/footer');	 
	}


	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}


	function sendotp($to_number){
 	 $to_number;
		$sid    = SK_KEY;
		$token  = TOKEN;
		$twilio = new Client($sid, $token);
		$code = rand(1111,9999);
		$code = 1234;
		// $message = $twilio->messages
		//                   ->create($to_number, // to
		//                            [
		//                                "body" => "Your Varfication code is : ".$code,
		//                                "from" => FROM_NUMBER
		//                            ]
		//                   );

		return $code;

	}

	 function check_user_mobile(){
        //get main CodeIgniter object

        $mobile = $this->input->post('mobile');
        $ci =& get_instance();
        if($ci->Common_model->getRecords('users', 'user_id', array('mobile2'=>$mobile), '', true)) {
        	 $this->db->last_query();
            echo  1;exit;
        } else {
        	//echo $this->db->last_query();
            echo  0;exit;
        }
    }


	public function verify_account() 
	{
		$tableName="users";	
		
        $user_id   =$this->input->post('user_id');
        $code   =	$this->input->post('code');
		 
			$where = array('user_id' => $user_id);

			$res=$this->Common_model->getRecords($tableName,'token,user_id,is_verified,user_type,profile_pic,full_name',$where,'',true);

		    if(!empty($res))  
		    {
		    	if($res['is_verified']==0){
		    		 
		    		if(!empty($code)){

		    			if($code == $res['token']){
			    			if($this->Common_model->addEditRecords('users',array('is_verified'=>1,'token'=>''),array('user_id'=>$res['user_id']))) {

			    				 $login_session=array( 	
										'user_id'=>$res['user_id'],
										'user_type'=> $res['user_type'],
										'full_name'=>$res['full_name'],
										'profile_pic'=>$res['profile_pic'],
									);
									$this->session->set_userdata($login_session);

			    				$err = array('data'=> array('status'=>'1','msg'=>'Mobile Number Verified Successfully.')) ;
								echo json_encode($err);
								exit;
			    			}
		    			}else{

		    				$err = array('data'=> array('status'=>'2','msg'=>'Verification Code Not Match.')) ;
							echo json_encode($err);
							exit;

		    			}
		    		}
		    	}else{

		    		$err = array('data'=> array('status'=>'2','msg'=>'Already Verified.')) ;
					echo json_encode($err);

		    	}
	    	}

    	 
		
	}



	public function resend_code() 
	{
		$tableName="users";	
		
        $user_id   =	$this->test_input($this->input->post('user_id'));
       
		if(empty($user_id )) {
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter user_id.'));
			echo json_encode($err);exit;
		}
			$where = array('user_id' => $user_id);

			$res=$this->Common_model->getRecords($tableName,'token,user_id,is_verified,mobile',$where,'',true);

		    if(!empty($res))  {
			    	
		    	if($res['is_verified']==0){
		    		$code = $this->sendotp($res['mobile']);
		    		if(!empty($code)){

		    			if($this->Common_model->addEditRecords('users',array('token'=>$code),array('user_id'=>$res['user_id']))) {
		    				$err = array('data'=> array('status'=>'2','msg'=>'Verification code has been sent to your phone number.')) ;
							echo json_encode($err);
							exit;
		    			}
		    		}
		    	}
	    		
	    	}else{

	    		$err = array('data'=> array('status'=>'2','msg'=>'Already Verified.')) ;
				echo json_encode($err);

	    	}
	} 





	public function contact_us(){

			if($this->input->post()){
					
				$first_name = $this->input->post('first_name');
				$last_name = $this->input->post('last_name');
				$email = $this->input->post('email');
				$phone = $this->input->post('phone');
				$message = $this->input->post('message');
					 

				$subject = SITE_TITLE.': Contact Information Recived';
			 
				$data['title']=  'Contact Information';
				$data['username']='Admin';
				$data['message'] = '<b>Name: </b>'.$first_name.' '.$last_name.'<br><b>Email: </b>'.$email.'<br><b>Phone: </b>'.$phone.'<br><b>Message: </b>'.$message;
				$body = $this->load->view('template/common', $data,TRUE);
				$to_email = getAdminEmail();
				$to_email = 'wabawaleed@gmail.com';
				$from_email = $email; 
				// $this->Common_model->setMailConfig();
		
				//Send mail  
					if(!$this->Common_model->defaultEmailSend($to_email,$subject,$body,$from_email)) {
						$this->session->set_flashdata('error', 'Some error occured! Please try again.');
						redirect(base_url().'contact_us');
					} else {
						$this->session->set_flashdata('success', 'Your enquiry has been successfully sent.');
						redirect(base_url().'contact_us');
					}

		 }

			$this->load->view('mobile/contact_us',$data);
	 
	}



	

}
