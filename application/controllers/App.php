<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class App extends CI_Controller {
	 private $language;


	function __construct() {
	//echo phpinfo();die;	
        parent::__construct();
        error_reporting(0);
        //  ini_set('display_errors', TRUE);
        $this->load->helper(array('email','common_helper'));
        $this->load->library(array('form_validation'));
        $this->load->model('App_model');
        $this->load->model('admin/Common_model');
        $this->count=0;
        $this->language ='';
        
		$h_key= getallheaders();

        if(isset($h_key['Apikey']))
		{
			$h_key['Apikey']=$h_key['Apikey'];
		}
		if(isset($h_key['apikey']))
		{
			$h_key['Apikey']=$h_key['apikey'];
		}
        if(APP_KEY !== $h_key['Apikey'])  //check header key for authorizetion 
		{
			echo json_encode(array('data'=> array('status' =>'0' ,'message'=>"Error Invalid Api Key")));
			header('HTTP/1.0 401 Unauthorized');
			die;
		}
	
		
		if(isset($h_key['Language'])){
			 $app_language = $h_key['Language'];
			if($app_language=='english'){
				$this->language = 'english';		
			}elseif ($app_language=='arabic') {
				$this->language = 'arabic';	
			}elseif ($app_language=='french') {
				$this->language = 'french';	
			}	
		}else{
			$this->language = 'english';	
		}

		// $err = array('data'=> array('status'=>'0','message'=>$h_key));
		// 		echo json_encode($err);exit; 
		// echo $this->language ;die;
		
    }

 
	public function testing()
	{
		 $username = $this->input->post('username');
	   if(preg_match('/^(_?[a-zA-Z0-9]+.?[a-zA-Z0-9_]+[A-Z-z0-9]+)+$/i',$username))
		{  			
		 
		  if(preg_match('/[^a-z_\-0-9.]/i',$username))
			{
				$err = array('data'=> array('status'=>'0','message'=>'Please use combination of Alphabets or underscore or point '.' to enter username.'));
				echo json_encode($err);exit; 
			} 



		}else
		{
			$err = array('data'=> array('status'=>'0','message'=>'Please use combination of Alphabets or underscore or point '.' to enter username.'));
			echo json_encode($err);exit;  
		}
		
		   
	}
 
	public function valid_username($username)
	{
		 
	   if(preg_match('/^(_?[a-zA-Z0-9]+.?[a-zA-Z0-9_]+[A-Z-z0-9]+)+$/i',$username))
		{  		
			 if(preg_match('/[^a-z_\-0-9.]/i',$username))
			{
				$err = array('data'=> array('status'=>'0','message'=>'Please use combination of Alphabets or underscore or point '.' to enter username.'));
				echo json_encode($err);exit; 
			} 	
		 
		}else
		{
			$err = array('data'=> array('status'=>'0','message'=>'Please use combination of Alphabets or underscore or point '.' to enter username.'));
			echo json_encode($err);exit;  
		}
		
		   
	}


	public function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		//$data = htmlspecialchars($data);
		return $data;
	}

	public function just_clean($str) {
	    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
	    $clean = strtolower(trim($clean, '-'));
	    $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
	    return $clean;
	}

	private function check_login() 
	{
		$tableName="users";	
		
        $user_id   =	$this->test_input($this->input->post('user_id'));
        $mobile_auth_token   =	$this->input->post('mobile_auth_token');
       
		if(empty($user_id )) {
			$err =array('status'=>'0','message'=>'Please enter the User Id.');
			echo json_encode($err);exit;
		}
		if(empty($mobile_auth_token )) {
			$err = array('status'=>'0','message'=>'Please enter mobile_auth_token.');
			echo json_encode($err);exit;
		}
		$where = array('user_id' => $user_id);




		// $this->Common_model->addEditRecords('users_chat',array('on_call'=>0),array('sender_id'=>$user_id));
		// $this->Common_model->addEditRecords('users_chat',array('on_call'=>0),array('receiver_id'=>$user_id));






		$resuser=$this->Common_model->getRecords('users','*',$where,'',true);
		$this->Common_model->addEditRecords('users',array('is_verified'=>1),array('user_id'=>$user_id));
		if(empty($resuser))
    	{
    		$err = array('status'=>'4','message'=>'Oops! Logged in is not found. Please if you can try Logging again.');
			echo json_encode($err);
			exit;
    	}

    	if($resuser['is_deleted']=='1')
    	{
    		$err = array('status'=>'4','message'=>'Sorry, Logged in user is deleted. Please try Logging again.');
			echo json_encode($err);
			exit;
    	}

    	if($resuser['mobile_auth_token']!=$mobile_auth_token)
    	{
    		$err = array('status'=>'4','message'=>'You are logged in other devices that\'s you logout from here.');
			echo json_encode($err);
			exit;
    	}
		
	}

	public function verify_account() 
	{
		$tableName="users";	
		
        $user_id   =	$this->test_input($this->input->post('user_id'));
        $code   =	$this->input->post('code');
       
		if(empty($user_id )) {
			$err = array('data'=> array('status'=>'0','message'=>'Please enter user_id.'));
			echo json_encode($err);exit;
		}
		if(empty($code )) {
			$err = array('data'=> array('status'=>'0','message'=>'Please enter code.'));
			echo json_encode($err);exit;
		}
			$where = array('user_id' => $user_id);

			$res=$this->Common_model->getRecords($tableName,'token,user_id,is_verified',$where,'',true);

		    if(!empty($res))  
		    {
		    	if($res['is_verified']==0){
		    		 
		    		if(!empty($code)){

		    			if($code == $res['token']){
			    			if($this->Common_model->addEditRecords('users',array('is_verified'=>1,'token'=>''),array('user_id'=>$res['user_id']))) {
			    				$err = array('data'=> array('status'=>'1','message'=>'Mobile Number Verified Successfully.')) ;
								echo json_encode($err);
								exit;
			    			}
		    			}else{

		    				$err = array('data'=> array('status'=>'2','message'=>'Verification Code Not Match.')) ;
							echo json_encode($err);
							exit;

		    			}
		    		}
		    	}else{

		    		$err = array('data'=> array('status'=>'2','message'=>'Already Verified.')) ;
					echo json_encode($err);

		    	}
	    	}

    	 
		
	}

	
	/*=============================username===========================================*/

	public function username($username) {
		$this->count++;
		$tableName="users";
		$where = array('username' => $username);
		$res = $this->Common_model->getRecords($tableName,'user_id',$where,'',true);
		if(empty($res)){
			$tableNameB="business_page";
			$whereB = array('business_name' => $username);
			
			$res = $this->Common_model->getRecords($tableNameB,'business_page_id',$whereB,'',true);
		}
		
		if($res){
		 	$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $string = '';
            $max = strlen($characters) - 1;
			for ($i = 0; $i < 4; $i++) {
				$string .= $characters[mt_rand(0, $max)];
			}
			if($this->count>1) {
				$username_array = explode("_",$username);
		 		$username = $username_array[0]."_".$this->count;
			} else {
		 		$username = $username."_".$this->count;
		 	}

		} else {
			return $username;
		}
		
		return $this->username($username);
	}

	public function login()
	{
	    $email 	     =   $this->test_input($this->input->post('email'));
		$password    =   $this->test_input($this->input->post('password'));
		$device_id   =   $this->test_input($this->input->post('device_id'));
		$device_type =   $this->test_input($this->input->post('device_type')); 
	    
       
		 if(empty($device_type))
			{
				$err =  array('status'=>'0','message'=>'Please enter device type!');
				echo json_encode($err);
				exit;
			}else if($device_type !='Android' && $device_type !='IOS' ){
			$err =  array('status' => '0', 'message' => 'Device type must be either Android or IOS');
			echo json_encode($err); exit;
		    }

			$tableName="users"; 
		 
		    if(empty($email))
			{
				$err =  array('status'=>'0','message'=>'Please enter the email.');
				echo json_encode($err);
				exit;
			}


			if(empty($password))
			{
				$err =  array('status'=>'0','message'=>'Please enter the Password.');
				echo json_encode($err);
				exit;
			}

			
		   if(empty($device_type))
			{
				$err =  array('status'=>'0','message'=>'Please enter device type!');
				echo json_encode($err);
				exit;
			}
					
		    $password=base64_encode($password);
	        $tableName="users";
			$where = array('email' => $email,'password' => $password);
			$res=$this->Common_model->getRecords($tableName,'*',$where,'',true);

		    if(!empty($res))  
		    {
		    	if($res['status']=="Inactive")
		    	{
		    		$err =  array('status'=>'0','message'=>'Your account is Inactive, Please contact us.');
					echo json_encode($err);
					exit;
		    	}


		    	$where=array('user_id'=>$res['user_id']);
		    	$date = date('Y-m-d H:i:s');
		    	$mobile_auth_token = base64_encode(rand());
	          	$update_data = array('device_id'=>$device_id,'device_type'=>$device_type,'created'=>$date,'mobile_auth_token'=>$mobile_auth_token);
				if($resdevice=$this->Common_model->addEditRecords('users',$update_data,array('user_id'=>$res['user_id']))) {

					$res=$this->Common_model->getRecords($tableName,'*',$where,'',true);
					
					$response =  array('status'=>'1','message'=>'Login Successfully','details'=>$res);		
					
				  	echo json_encode($response);
				  	exit;	
			    } else {
			    	$err =  array('status' => '0', 'message' => 'Some error occured Please try again !!');
					echo json_encode($err);
					exit;
			    }
			}else{

				$err =  array('status' => '0', 'message' => 'Incorrect email or password');
				echo json_encode($err);
			}
			
	    
	} 


    /*=============================logout===========================================*/
	public function logout()
	{
		$device_id =    $this->test_input($this->input->post('device_id'));
        $user_id   =	$this->test_input($this->input->post('user_id'));
      
		if(empty($user_id))
		{
			$err = array('data'=> array('status'=>'0','message'=>'Please enter user id '));
			echo json_encode($err);
			exit;
		}
		if(empty($device_id))
		{
			$err = array('data'=> array('status'=>'0','message'=>'Please enter device id '));
			echo json_encode($err);
			exit;
		}

    
       	$this->Common_model->addEditRecords('users',array('device_id'=>''),array('user_id'=>$user_id));

       	if($this->language=='english'){
       		$response = array('data'=> array('status'=>'4','message'=>'Logout successful.'));
		}elseif ($this->language=='arabic') {
			$response = array('data'=> array('status'=>'4','message'=>'تم الخروج بنجاح‎'  ));
		}elseif ($this->language=='french') {
			$response = array('data'=> array('status'=>'4','message'=>'déconnexion réussie'));
		} 

		  	echo json_encode($response);	
		  	exit;
	}



	/*=============================Signup===========================================*/
	public function signup()
	{
	  	// $username			=	$this->test_input($this->input->post('username'));
	  	$user_type			=	$this->test_input($this->input->post('user_type'));
	  	$full_name			=	$this->test_input($this->input->post('full_name'));
	  	$email				=	$this->test_input($this->input->post('email'));
	  	$password			=	$this->test_input($this->input->post('password'));
	  	$mobile			=	$this->test_input($this->input->post('mobile'));
	  	$address			=	$this->test_input($this->input->post('address'));
	  	$pincode			=	$this->test_input($this->input->post('pincode'));
	  	$device_id			=	$this->test_input($this->input->post('device_id'));
	  	$device_type		=	$this->input->post('device_type');


		if(empty($user_type)){
			$err = array('status' => '0', 'message' => 'Please select user type.');
			echo json_encode($err); exit;
		}else
		{
			if($user_type!='Sales' && $user_type!='Companies' && $user_type!='Inspectors')
			{
				$err = array('status' => '0', 'message' => 'User type must be Sales or Companies Or Inspectors.');
				echo json_encode($err); exit;	
			}
		} 


		if(empty($full_name)){
			$err = array('status' => '0', 'message' => 'Please enter full_name.');
			echo json_encode($err); exit;
		} 
		
		if(empty($email)){
			$err = array('status' => '0', 'message' => 'Please enter your email.');
			echo json_encode($err); exit;
		}else{

			if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				$err = array('status' => '0', 'message' => 'Please enter a valid email.');
				echo json_encode($err); exit;
			}
	        $where1 = array('email' => $email,'is_deleted'=>0);
			if($this->Common_model->getRecords('users','user_id',$where1,'',true)) {
				$err = array('status' => '0', 'message' => 'An account already exist with similar Email ID. Please try login with another.');
				echo json_encode($err); exit;
			} 
		} 
	
		if(empty($mobile)){
			$err = array('status' => '0', 'message' => 'Please enter mobile.');
			echo json_encode($err); exit;
		}else{

			$mobile = str_replace('-','',$mobile);
			   $where1 = array('mobile' => $mobile,'is_deleted'=>0);
				if($this->Common_model->getRecords('users','user_id',$where1,'',true)) {
					$err = array('status' => '0', 'message' => 'An account already exist with similar Mobile number. Please try login with another.');
					echo json_encode($err); exit;
				}  
		} 
 
		if(empty($password)){
			$err = array('status' => '0', 'message' => 'Please enter your password.');
			echo json_encode($err); exit;
		} 
		if(empty($pincode)){
			$err = array('status' => '0', 'message' => 'Please enter your pincode.');
			echo json_encode($err); exit;
		} 
 
		
		if(empty($device_id)){
			$err = array('status' => '0', 'message' => 'Please enter device id');
			echo json_encode($err); exit;
		} 
		if(empty($device_type)){
			$err = array('status' => '0', 'message' => 'Please enter device type');
			echo json_encode($err); exit;
		} 
		else if($device_type !='Android' && $device_type !='IOS' ){
			$err = array('status' => '0', 'message' => 'Device type must be either Andriod or IOS');
			echo json_encode($err); exit;
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
				$err = array('status' => '0', 'message' =>strip_tags($this->upload->display_errors()));
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

 

        $mobile_auth_token = base64_encode(rand());
	    $insert_data = array( 
	    	'user_type'=>$user_type,
	    	'full_name'=>$full_name,
	    	'email' => $email, 
	    	'password' => $password,
	    	'mobile' => $mobile,
	    	'profile_pic' =>$imagess,
	    	'address' => $address,
	    	'zipcode' => $pincode,
	    	'mobile_auth_token' => $mobile_auth_token,
	    	'device_id'=>$device_id,
	    	'device_type'=>$device_type, 
	    	'created'=>date('Y-m-d h:i:s'),
	    );

	    if($user_type=='Inspectors'){
	    	$insert_data['status']= 'Inactive';
	    }
		
		if($user_id = $this->Common_model->addEditRecords('users',$insert_data)) {
	
			  
			if(!empty($email)){		 
				$subject = SITE_TITLE.': Registered successfully';
			 
				$data['title']=  'Registered successfully';
				$data['username']= $full_name;
				$data['message'] = 'You have been registered successfully.';
				$body = $this->load->view('template/common', $data,TRUE);
				$to_email = $email;
				$from_email = 'info@officechecker'; 
				$this->Common_model->setMailConfig();
 
		 
			//Send mail 
		 		//$this->Common_model->sendEmail($to_email,$subject,$body,$from_email); 
		 	}

				$user_data = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id),'',true);
 
					$response = array('status'=>'1','message'=>'Signup Successfully','details'=>$user_data);

				    echo json_encode($response); exit;
			} else {
				$err = array('status' => '0', 'message' => 'Server not responding. Please try again !!');
				echo json_encode($err); exit;
			}
	 
	}





    public function send_order() {

       $this->check_login();
       $user_id	=	$this->test_input($this->input->post('user_id'));
       $contact_name	=	$this->test_input($this->input->post('contact_name'));
       $address	=	$this->test_input($this->input->post('address'));
       $email	=	$this->test_input($this->input->post('email'));
       $term_and_condtion	=	$this->test_input($this->input->post('term_and_condtion'));
       $company_id		=	$this->test_input($this->input->post('company_id'));


       if(empty($user_id)){
			$err = array('status' => '0', 'message' => 'Please enter your user id.');
			echo json_encode($err); exit;
		} 
       if(empty($contact_name)){
			$err = array('status' => '0', 'message' => 'Please enter contact_name.');
			echo json_encode($err); exit;
		} 
       if(empty($address)){
			$err = array('status' => '0', 'message' => 'Please enter address.');
			echo json_encode($err); exit;
		} 
       if(empty($email)){
			$err = array('status' => '0', 'message' => 'Please enter email.');
			echo json_encode($err); exit;
		} 
       	if(empty($company_id)){
			$err = array('status' => '0', 'message' => 'Please enter company_id.');
			echo json_encode($err); exit;
		} 
       	if(empty($term_and_condtion)){
			$err = array('status' => '0', 'message' => 'Please enter term and condtion.');
			echo json_encode($err); exit;
		} 


      		$update_data = array(
                'sender_id'  =>  $user_id,
                'contact_name'  =>  $contact_name,
                'address'  =>  $address,
                'email'  =>  $email,
                'company_id'  =>  $company_id,
                'agree_term_and_condition'  =>  $term_and_condtion,
                'created'  =>  date("Y-m-d H:i:s"),
		    ); 

		    if(!$order_id = $this->Common_model->addEditRecords('sales_order', $update_data)) {
			    $err = array('status' => '0', 'message' => 'Some error occured! Please try again.');
	            echo json_encode($err); exit;
			} else {


					$second_user=$this->Common_model->getRecords('users','user_id,full_name,device_id,device_type,badge_count',array('user_id'=>$company_id),'',true);

					// echo "<pre>";print_r($second_user);die;
					$current_user=$this->Common_model->getRecords('users','full_name',array('user_id'=>$user_id),'',true);

		  
					      	$iosarray = array(
			                    'alert' => 'Received Order Detail From ' .$current_user['full_name'],
			                    'type'  => 'sales_order',
			                    'order_id'  => $order_id,
			                   	'badge' => $second_user['badge_count'],
			                    'sound' => 'default',
			                    'user_id' => $user_id,
			       			);
  
							$andarray = array(
				                'message'   => 'Received Order Detail From ' .$current_user['full_name'],
				               	'type'  => 'sales_order',
				               	'order_id'  =>$order_id,
			                   	'user_id' => $user_id,
				                'title'     => 'Received Order Detail From ' .$current_user['full_name'],
			            	);
							

					    		if($second_user['device_type']=='Android'){
									$referrer = androidNotification($second_user['device_id'],$andarray);
								}

					    		if($second_user['device_type']=='IOS'){

			                   		 $referrer = iosNotification($second_user['device_id'],$iosarray);
			                   		
					    		}
					    
				 
					    $add_data =array('user_id' => $second_user['user_id'],'order_id'=>$order_id,'created_by' =>$user_id,'type'=>'sales_order', 'notification_title'=> 'Received Order Detail From ' .$current_user['full_name'], 'notification_description'=>  'Received Order Detail From ' .$current_user['full_name'],'created'=>date('Y-m-d H:i:s'));
			    		$this->Common_model->addEditRecords('notifications',$add_data); 

				$suc = array('status' => '1', 'message' => 'Order Send Successfully.');
	            echo json_encode($suc); exit;
			} 

    }  

    public function send_job_request() {

       $this->check_login();
       $user_id	=	$this->test_input($this->input->post('user_id'));
       $sales_order_id	=	$this->test_input($this->input->post('sales_order_id'));
       $number_of_job	=	$this->test_input($this->input->post('number_of_job'));


       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter your user id.'));
			echo json_encode($err); exit;
		} 
       if(empty($sales_order_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter order_id.'));
			echo json_encode($err); exit;
		} 
       if(empty($number_of_job)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter number of job.'));
			echo json_encode($err); exit;
		} 


      		$update_data = array(
                'company_id'  =>  $user_id,
                'number_of_job'  =>  $number_of_job,
                'sales_order_id'  =>  $sales_order_id, 
                'created'  =>  date("Y-m-d H:i:s"),
		    ); 

		    if(!$order_id = $this->Common_model->addEditRecords('booking_request', $update_data)) {
			    $err = array('data' =>array('status' => '0', 'message' => 'Some error occured! Please try again.'));
	            echo json_encode($err); exit;
			} else {


					// $second_user=$this->Common_model->getRecords('users','user_id,full_name,device_id,device_type,badge_count',array('user_id'=>$user_id),'',true);


					// if(!empty($second_user['full_name'])) {
					// 	$user_name = $second_user['full_name'];
					// }
					 
					// $subject = SITE_TITLE." Received Job Request";
				 
					// $data['title']=  'Received Job Request';
					// $data['username']= 'Admin';
					// $data['message']= 'You Received A Job Request From '.$user_name;
					// $body = $this->load->view('template/common', $data,TRUE);
					// $to_email = 'parmar.jay350@gmail.com';
					// $from_email = 'info@officechecker'; 
					// $this->Common_model->setMailConfig();

				 // 	//Send mail 
					// if($this->Common_model->sendEmail($to_email,$subject,$body,$from_email)) 
					// {

						$suc = array('data' =>array('status' => '1', 'message' => 'Job Request Sent Successfully.'));
			            echo json_encode($suc); exit;

					// } else {
					// 	$err = array('data' =>array('status' => '0', 'message' => 'Some error occured. Please try again !!.'));
					// 	echo json_encode($err); exit;
					// }

			} 

    }  
    public function add_job() {
       $this->check_login();
       $user_id	=	$this->test_input($this->input->post('user_id'));
       $name		=	$this->test_input($this->input->post('name'));
       $address	=	$this->test_input($this->input->post('address'));
       $post_code	=	$this->test_input($this->input->post('post_code'));
       $phone_number	=	$this->test_input($this->input->post('phone_number'));
       $comments	=	$this->test_input($this->input->post('comments'));


       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter your user id.'));
			echo json_encode($err); exit;
		} 
       if(empty($name)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter name.'));
			echo json_encode($err); exit;
		} 
       if(empty($address)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter address.'));
			echo json_encode($err); exit;
		} 
       if(empty($post_code)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter post code.'));
			echo json_encode($err); exit;
		} 
       if(empty($phone_number)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter phone number.'));
			echo json_encode($err); exit;
		} 


		if($this->Common_model->getRecords('job_in_account','count',array('company_id'=>$user_id,'count >'=>0),'',true))
      	{

      		$old_job_id = $this->Common_model->getRecords('jobs','id','','id DESC',true);

      		$job_number = str_pad($old_job_id['id']+1,5,"0",STR_PAD_LEFT );

      		$update_data = array(
                'user_id'  =>  $user_id,
                'name'  =>  $name,
                'address'  =>  $address,
                'post_code'  =>  $post_code,
                'job_number'  =>  $job_number,
                'phone_number'  =>  $phone_number,
                'comments'  =>  $comments,
                'created'  =>  date("Y-m-d H:i:s"),
		    ); 

		    if(!$this->Common_model->addEditRecords('jobs', $update_data)) {
			    $err = array('status' => '0', 'message' => 'Some error occured! Please try again.');
	            echo json_encode($err); exit;
			} else {

				$old_job = $this->Common_model->getRecords('job_in_account','count',array('company_id'=>$user_id,'count >'=>0),'',true);
				$number_of_job = 
				$new_number = $old_job['count']-1;
				$this->Common_model->addEditRecords('job_in_account',array('count'=>$new_number),array('company_id'=>$user_id));

				$suc = array('status' => '1', 'message' => 'Job Create Successfully.');
	            echo json_encode($suc); exit;
			} 

      	}else{
      		$err = array('status' => '0', 'message' => 'You dont have pemission to post job please contact to sales team.');
            echo json_encode($err); exit;
      	}
		    
        
	
    }





 
	public function payment_save()
	{
	  	// $username			=	$this->test_input($this->input->post('username'));
	  	$user_id			=	$this->test_input($this->input->post('user_id'));
	  	$transaction_id			=	$this->test_input($this->input->post('transaction_id'));
	  	$amount			=	$this->test_input($this->input->post('amount'));
	    
		$this->check_login();
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter user id.'));
			echo json_encode($err); exit;
		} 
		if(empty($transaction_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter transaction id.'));
			echo json_encode($err); exit;
		} 
		if(empty($amount)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter amount.'));
			echo json_encode($err); exit;
		} 
		
		  
	    $insert_data = array( 
	    	'user_id'=>$user_id,
	    	'transaction_id'=>$transaction_id,
	    	'amount' => $amount, 
	    	'created'=>date('Y-m-d h:i:s'),
	    );
		
		if( $this->Common_model->addEditRecords('payment_history',$insert_data)) {
		
			$this->Common_model->addEditRecords('users',array('payment_status'=>'Paid'),array('user_id'=>$user_id));
			$response = array('data'=> array('status'=>'1','message'=>'Payment received successfully'));
				    echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'message' => 'Server not responding. Please try again !!'));
				echo json_encode($err); exit;
			}
	 
	}

	public function editProfile()
	{
	  	// $username			=	$this->test_input($this->input->post('username'));
	  	$user_id			=	$this->test_input($this->input->post('user_id'));
	  	$full_name			=	$this->test_input($this->input->post('full_name'));
	  	$email				=	$this->test_input($this->input->post('email'));
	  	$country_id			=	$this->test_input($this->input->post('country_id'));
	  	$state_id			=	$this->test_input($this->input->post('state_id'));
	  	$city_id			=	$this->test_input($this->input->post('city_id'));
	  	$mobile			=	$this->test_input($this->input->post('mobile'));
	  	$address			=	$this->test_input($this->input->post('address'));
	  	$latitude			=	$this->test_input($this->input->post('latitude'));
	  	$longitude			=	$this->test_input($this->input->post('longitude'));
	  	$about			=	$this->test_input($this->input->post('about'));
	  	$category_id			=	$this->test_input($this->input->post('category_id'));
	  	$user_type			=	$this->test_input($this->input->post('user_type'));

	 
		if(empty($full_name)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter full_name.'));
			echo json_encode($err); exit;
		} 
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter user id.'));
			echo json_encode($err); exit;
		} 

		if($user_type!='doctor' && $user_type!='patient')
		{
			$err = array('data' =>array('status' => '0', 'message' => 'User type must be doctor or patient.'));
			echo json_encode($err); exit;	
		}elseif($user_type=='doctor' && empty($category_id))
		{
			$err = array('data' =>array('status' => '0', 'message' => 'Please select category.'));
			echo json_encode($err); exit;
		}
		
		if(empty($email)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter your email.'));
			echo json_encode($err); exit;
		} 

		if(empty($country_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter country.'));
			echo json_encode($err); exit;
		} 

		if(empty($state_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter state.'));
			echo json_encode($err); exit;
		} 

		if(empty($city_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter city.'));
			echo json_encode($err); exit;
		} 
		if(empty($mobile)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter mobile.'));
			echo json_encode($err); exit;
		} 

		if(empty($latitude)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter latitude.'));
			echo json_encode($err); exit;
		}
		if(empty($longitude)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter longitude.'));
			echo json_encode($err); exit;
		}  

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter a valid email.'));
			echo json_encode($err); exit;
		}
        $where1 = array('email' => $email,'user_id!='=>$user_id);
		if($this->Common_model->getRecords('users','user_id',$where1,'',true)) {
			$err = array('data' =>array('status' => '0', 'message' => 'An account already exist with similar Email ID. Please try login with another.'));
			echo json_encode($err); exit;
		} 
  
		$this->check_login();
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
				$err = array('data' =>array('status' => '0', 'message' =>strip_tags($this->upload->display_errors())));
	            echo json_encode($err); exit; 		
			}
			else
			{
				$upload_data=$this->upload->data();
				$imgg = $upload_data['file_name'];	
            } 
        }else{
        	$imgg = '';
        }

	    $insert_data = array(  
	    	'user_type'=>$user_type,
	    	'category_id'=>$category_id,
	    	'full_name'=>$full_name,
	    	'email' => $email,  
	    	'country_id' => $country_id,
	    	'state_id' => $state_id,
	    	'city_id' => $city_id,
	    	'mobile' => $mobile,
	    	
	    	'address' => $address,
	    	'latitude' => $latitude,
	    	'longitude' => $longitude,
	    	'about' => $about,  
	    	'modified'=>date('Y-m-d h:i:s'),
	    );
		if(!empty($imgg)){
			$insert_data['profile_pic'] ='resources/images/profile/'.$imgg;
		}		
		if($this->Common_model->addEditRecords('users',$insert_data,array('user_id'=>$user_id))) {
	
				//send welcome email to use
				$user_data = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id),'',true);

				if($this->language=='english'){
				 
					$response = array('data'=> array('status'=>'1','message'=>'Profile Update Successfully','details'=>$user_data));

				}elseif ($this->language=='arabic') {
				 
					$response = array('data'=> array('status'=>'1','message'=>' تم تحديث الملف ', 'details'=>$user_data));

				}elseif ($this->language=='french') {
				 
					$response = array('data'=> array('status'=>'1','message'=>'Mise à jour du profil réussie','details'=>$user_data));

				}

				    echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'message' => 'Server not responding. Please try again !!'));
				echo json_encode($err); exit;
			}
	 
	}
 
	public function get_countries(){
	
		$data['countries'] = array();
		if($data['countries']=getCountriesList()) {
			$response = array('data'=> array('status'=>'1','message'=>'Country list found' ,'details'=>$data['countries']));
		} else {
			$response = array('data'=> array('status'=>'0','message'=>'Countries not found.'));
		}
		echo json_encode($response);  exit;

	}

	public function get_states(){
		// $this->check_login();
        $country_id	=	$this->test_input($this->input->post('country_id'));

        if(empty($country_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter country id.'));
			echo json_encode($err); exit;
		}

		$data['states'] = array();
		if($data['states']=getStatesList($country_id)) {
			$response = array('data'=> array('status'=>'1','message'=>'State found' ,'details'=>$data['states']));
		} else {
			$response = array('data'=> array('status'=>'0','message'=>'states not found.'));
		}
		echo json_encode($response);  exit;

	}

	public function get_cities(){
		// $this->check_login();
        $state_id	=	$this->test_input($this->input->post('state_id'));

        if(empty($state_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter state id.'));
			echo json_encode($err); exit;
		}

		$data['cities'] = array();
		if($data['cities']=getCitiesList($state_id)) {
			$response = array('data'=> array('status'=>'1','message'=>'City found' ,'details'=>$data['cities']));
		} else {
			$response = array('data'=> array('status'=>'0','message'=>'Cities not found.'));
		}
		echo json_encode($response);  exit;

	}


	public function resize_image($image_data,$width,$height) {
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $image_data['full_path'];
		$config['new_image'] = $image_data['full_path'];
		$config['width'] = $width;
		$config['height'] = $height;
		//send config array to image_lib's  initialize function
		$this->image_lib->initialize($config);
	}

	/*=============================editProfile===========================================*/

	// public function editProfile() {
	// 	$this->check_login();
 //        $username				=	strtolower(str_replace(" ","",$this->test_input($this->input->post('username'))));
 //        $full_name				=	$this->test_input($this->input->post('full_name'));
	//   	$email					=	$this->test_input($this->input->post('email'));
	//   	$user_id				=	$this->test_input($this->input->post('user_id'));
	//   	$user_interest			=	$this->test_input($this->input->post('user_interest'));
	//   	$mobile             	=   $this->test_input($this->input->post('mobile'));


	// 	if(empty($username)){
	// 		$err = array('data' =>array('status' => '0', 'message' => 'Please enter username.'));
	// 		echo json_encode($err); exit;
	// 	}else
	// 	{
	// 		$this->valid_username($username);
	// 	}

	// 	if(empty($full_name)){
	// 		$err = array('data' =>array('status' => '0', 'message' => 'Please enter username.'));
	// 		echo json_encode($err); exit;
	// 	} 
		
	// 	if(empty($email)){
	// 		$err = array('data' =>array('status' => '0', 'message' => 'Please enter your email.'));
	// 		echo json_encode($err); exit;
	// 	} 

	// 	if(empty($user_id)){
	// 		$err = array('data' =>array('status' => '0', 'message' => 'Please enter your user id.'));
	// 		echo json_encode($err); exit;
	// 	} 

	// 	if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
	// 		$err = array('data' =>array('status' => '0', 'message' => 'Please enter a valid email.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	if(user_username($user_id,$username)=='1') {
	// 		$err = array('data' =>array('status' => '0', 'message' => 'Username already used.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	$tableName1="business_page";
	// 	$where = array('business_name' => $username);
	// 	if($this->Common_model->getRecords($tableName1,'business_page_id',$where,'',true)) {
	// 		$err = array('data' =>array('status' => '0', 'message' => 'Username already used.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	if(user_email($user_id,$email)=='1') {
	// 		$err = array('data' =>array('status' => '0', 'message' => 'An account already exist with similar Email ID. Please try login with another.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	if(firebase_email($user_id,$email)=='1') {
	// 		$err = array('data' =>array('status' => '0', 'message' => 'An account already exist with similar Email ID. Please try login with another.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	if(!empty($mobile)) {
	// 		if(user_mobile($user_id,$mobile)=='1') {
	// 			$err = array('data' =>array('status' => '0', 'message' => 'mobile already used.'));
	// 			echo json_encode($err); exit;
	// 		}
	//     }
	//     $username = strtolower(str_replace(" ","",$username));

	//     $userInterest = $this->App_model->userInterest($user_id);
		
	// 	if($user_interest) {
	// 		$user_interest_data = explode(",",$user_interest);
	// 		$reserved = array();
	//    	    if(!empty($userInterest)) {
	// 	   	    foreach ($userInterest as $list) {
	// 	   	    	if(in_array($list['interest_id'], $user_interest_data)){
	// 	            	$reserved[] = $list['interest_id'];
	// 	   	    	} else {
	// 	         		//Delete tagged user
	// 	         		$where = array('user_interest_id'=>$list['user_interest_id']);
	// 					$this->Common_model->deleteRecords('user_interest',$where);
	// 	   	    	}
	// 	   	    }
	// 	   	}

	//    	    foreach ($user_interest_data as $interest_id) {
	//    	    	if(!in_array($interest_id, $reserved)){
	//             	$addtag = array(
	// 					'interest_id' => $interest_id,
	// 					'user_id' => $user_id,
	// 					'created' => date("Y-m-d H:i:s")
	// 				); 
	// 	            $this->Common_model->addEditRecords('user_interest', $addtag);
	//    	    	} 
	//    	    }
	// 	} else {
	// 		if(!empty($userInterest)) {
	// 	   	    foreach ($userInterest as $list) {
	// 	   	    	$where = array('user_interest_id'=>$list['user_interest_id']);
	// 				$this->Common_model->deleteRecords('user_interest',$where);
	// 	   	    }
	// 	   	}
	// 	}

	

	// 	$a1= array(
 //          	'username' => $username,
 //          	'full_name' =>$full_name,
	// 		'email' => $email,
	// 		'about' => $this->input->post('about'),
	// 		'mobile' => $mobile,
	// 		'address' => $this->input->post('address'),
	// 		'country_id' => $this->input->post('country'),
	// 		'state_id' => $this->input->post('state'),
	// 		'city_id' => $this->input->post('city'),
	// 		'country_id_home' => $this->input->post('country_home'),
	// 		'state_id_home' => $this->input->post('state_home'),
	// 		'city_id_home' => $this->input->post('city_home'),
	// 		'country_code' => $this->input->post('country_code'),
	// 		// 'zipcode' => $this->input->post('zip_code'),
	// 		'age' => $this->input->post('age'),
	// 		'modified' => date("Y-m-d H:i:s"),
	// 	);
 //       if(!empty($_FILES['profile_pic']['name'])){
 //     //  echo "<pre>";print_r($_FILES);exit;
 //       		$newFileName = $_FILES['profile_pic']['name'];
 //            $fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
 //            $filename = uniqid(time()).".".$fileExt;
	//         $config['upload_path'] = 'resources/images/profile/';
	//         $config['file_name'] = $filename;
	// 		$config['allowed_types'] = '*';
 //            $this->load->library('upload', $config);
	// 		$this->upload->initialize($config);
	// 		if (!$this->upload->do_upload('profile_pic')) 
	// 		{
	// 			$err = array('data' =>array('status' => '0', 'message' =>strip_tags($this->upload->display_errors())));
	//             echo json_encode($err); exit; 		
	// 		}
	// 		else
	// 		{
	// 			$upload_data=$this->upload->data();
	// 			$a2 =array('profile_pic' =>'resources/images/profile/'.$upload_data['file_name']);
 //            }
 //        }else{
 //        	$a2 =array();
	// 	}
       

	// 	if(!$this->Common_model->addEditRecords('users',array_merge($a1,$a2),array('user_id'=>$user_id))) {
	// 	    $err = array('data' =>array('status' => '0', 'message' => 'Some error occured! Please try again.'));
 //            echo json_encode($err); exit;
	// 	} else {
	// 		$getPost =  	$this->App_model->getProfile($user_id);
 //        	$interested =  	$this->App_model->user_interested($user_id);
	// 		$suc = array('data' =>array('status' => '1', 'message' => 'Profile updated successfully.','details'=>$getPost,'interested'=>$interested));
 //            echo json_encode($suc); exit;
	// 	}
	// }



	public function profile()
	{   
		$this->check_login();
		$auth_key =    	$this->test_input($this->input->post('auth_key'));
        $user_id  =	$this->test_input($this->input->post('user_id'));
        $second_user_id = $this->test_input($this->input->post('owner_user_id'));
        
        if(empty($second_user_id )){
  		 	$getPost =  	$this->App_model->getProfile($user_id);
  		 	
  		
	 			 if(!empty($getPost['country_id_home']))
	 			 {
			 	 	$country_home = $this->Common_model->getRecords('countries','name',array('id'=>$getPost['country_id_home']),'',true);
			 	 	
	 			 	$getPost['country_home']= $country_home['name']; 

	 			 }else
	 			 {
	 			 	$getPost['country_home']=''; 
	 			 }
	 			
	 			 if(!empty($getPost['state_id_home']))
	 			 {
			 	 	$states_home = $this->Common_model->getRecords('states','name',array('id'=>$getPost['state_id_home']),'',true);

	 			 	$getPost['state_home'] = $states_home['name'];  
	 			 }else
	 			 {
	 			 	$getPost['state_home']=''; 
	 			 }

	 			 if(!empty($getPost['city_id_home']))
	 			 {
			 	 	$city_home = $this->Common_model->getRecords('cities','name',array('id'=>$getPost['city_id_home']),'',true);
	 			 	$getPost['city_home']= $city_home['name'];

	 			 }else
	 			 {
	 			 	$getPost['city_home']= '';
	 			 }
	 			 

        	$interested =  	$this->App_model->user_interested($user_id);
	  	} else {
  			$getPost =  	$this->App_model->getProfile($second_user_id,$user_id);
  			if($isFollow = $this->App_model->isFollow($second_user_id,$user_id)) {
			    if($isFollow[0]['status']=='Follow'){
					$getPost['isFollow']  = '1';	
				} else {
					$getPost['isFollow']  = '2';
				}
			}else {
				$getPost['isFollow'] = '0';
			}
        	$interested =  	$this->App_model->user_interested($second_user_id);
	  	}
       
       		
   	 	$data['profile_data'] =$getPost;
   	 	$data['interested'] =$interested;
        if($getPost){
        	$response = array('data'=> array('status'=>'1','message'=>'Profile','details'=>$data));
			echo json_encode($response); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'message' => 'Record not found.'));
			echo json_encode($err); exit;
		}
	} 
 	

	function getProfilePostList()
	{
		$this->check_login();
		$user_id = $this->test_input($this->input->post('user_id'));
		$second_user_id = $this->test_input($this->input->post('owner_user_id'));
        $sort  =	$this->test_input($this->input->post('sort'));
        
        if(empty($second_user_id )){
  			$getPost = $this->App_model->get_user_post($sort,$user_id,'0','yes');
	  	} else {
  			$getPost = $this->App_model->get_user_post($sort,$second_user_id,$user_id,'no');
	  	}

        if($getPost) {
		    $index=0;
			foreach ($getPost as $get) {
				
				if($isLike = $this->App_model->isLike($get['post_id'],$user_id)) {
					$getPost[$index]['isLike'] = $isLike->isLike;
				} else {
					$getPost[$index]['isLike'] = '0';
				}

				if($isFollow = $this->App_model->isFollow($get['user_id'],$user_id)) {
				
				   $getPost[$index]['isFollow'] = '1';
				} else {
					$getPost[$index]['isFollow'] = '0';
				}


	            $post_images = array();
				$where = array('post_id' => $get['post_id']);
				if($post_images = $this->Common_model->getRecords('post_img','file_path,file_type,video_path',$where,'',false)) {
					$getPost[$index]['post_media'] = $post_images;
				}else{
				$getPost[$index]['post_media'] = $post_images;	
				}
	            $index++;
	            
			} 
			$response = array('data'=> array('status'=>'1','message'=>'PostList','details'=>$getPost));
			echo json_encode($response); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'message' => 'Result not found'));
			echo json_encode($err); exit;	
		}
 
	}
 
    /*=============================editProfile and View===========================================*/
 

    public function notification_list() {
 
       $user_id			=	$this->test_input($this->input->post('user_id')); 

       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter your user id.'));
			echo json_encode($err); exit;
		}   
		$this->check_login();
		$update_data = array(
            'badge_count' =>0,
            'modified' => date("Y-m-d H:i:s"),
	    );
		if(!$this->Common_model->addEditRecords('users', $update_data,array('user_id'=>$user_id))) {
		    $err = array('data' =>array('status' => '0', 'message' => 'Some error occured! Please try again.'));
            echo json_encode($err); exit;
		} else {
			$notification = $this->Common_model->getRecords('notifications','*',array('user_id'=>$user_id),'notification_id Desc',false);
			$suc = array('data' =>array('status' => '1', 'message' => 'Notification list.','list'=>$notification));
            echo json_encode($suc); exit;
		} 
    }

      public function advertisement_list() {
 
       $user_id			=	$this->test_input($this->input->post('user_id')); 

       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter your user id.'));
			echo json_encode($err); exit;
		}   
		$this->check_login(); 
			$advertisement = $this->Common_model->getRecords('advertisement','*',array('user_id'=>$user_id),'notification_id Desc',false);
			if(!empty($advertisement )){
				$suc = array('data' =>array('status' => '1', 'message' => 'Advertisement list.','list'=>$advertisement));
            	echo json_encode($suc); exit;
			}else{
				$err = array('data' =>array('status' => '0', 'message' => 'No record found.'));
				echo json_encode($err); exit;
			} 	 
		 
    }
    public function advNotification() {
       $this->check_login();
       $adv_notification	=	$this->test_input($this->input->post('adv_notification'));
       $user_id			    =	$this->test_input($this->input->post('user_id'));


       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter your user id.'));
			echo json_encode($err); exit;
		} 

       if(empty($adv_notification)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Adv Notification Type.'));
			echo json_encode($err); exit;
		} else if($adv_notification != 'Yes' && $adv_notification != 'No') {
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter exact Adv Notification Type.'));
			echo json_encode($err); exit;
		}
            
		$update_data = array(
            'adv_notification' => $this->input->post('adv_notification'),
            'modified' => date("Y-m-d H:i:s"),
	    );
		if(!$this->Common_model->addEditRecords('users', $update_data,array('user_id'=>$user_id))) {
		    $err = array('data' =>array('status' => '0', 'message' => 'Some error occured! Please try again.'));
            echo json_encode($err); exit;
		} else {
			$suc = array('data' =>array('status' => '1', 'message' => 'Adv Notification updated successfully.'));
            echo json_encode($suc); exit;
		} 
    }

    /*===========================================change password=========================================*/
	public function change_password()
	{
	   	$this->check_login();
		$user_id 			=	$this->test_input($this->input->post('user_id'));
		$current_password	=	$this->test_input($this->input->post('current_password'));
		$new_password		=	$this->test_input($this->input->post('new_password'));

		$tableName = 'users';
		$where = array('user_id' => $user_id);

		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please send user id'));
			echo json_encode($err); exit;
		}

		if(empty($current_password)) {
			$err = array('data'=> array('status'=>'0','message'=>'Please enter current password'));
			echo json_encode($err); exit;
		}


		if(empty($new_password)){
			$err = array('data'=> array('status'=>'0','message'=>'Please enter new Password'));
			echo json_encode($err); exit;

		}
		
		$current_password=base64_encode($current_password);
		$new_password=base64_encode($new_password);
		
		if(!$user_data = $this->Common_model->getRecords($tableName,'*',array('user_id' => $user_id,'status'=>'Active'),'',true)) {
			$err = array('data'=> array('status'=>'5','message'=>'Your profile has been Inactive by admin.'));
			echo json_encode($err); exit;
		} else {
			if($user_data['password'] != $current_password) {
				// $err = array('data'=> array('status'=>'0','message'=>'Incorrect current password'));

					if($this->language=='english'){
							$err = array('data'=> array('status'=>'0','message'=>'Incorrect current password'));
					}elseif ($this->language=='arabic') {
							$err = array('data'=> array('status'=>'0','message'=>'كلمة المرور الحالية غير صحيحة'));
					}elseif ($this->language=='french') {
							$err = array('data'=> array('status'=>'0','message'=>'incorrect current password'));
					}


				echo json_encode($err); exit;
			} else {
				if($new_password == $user_data['password']) {
					$err = array('data' =>array('status' => '0', 'message' => "New password can't be same as current password."));
					echo json_encode($err); exit;
				}
		        $update_data = array(
		       		'password'=>$new_password,
		       		'modified' => date("Y-m-d H:i:s"),
		        );
		        

	   			if($this->Common_model->addEditRecords($tableName,$update_data,$where)) {
	   	

	   			if($this->language=='english'){
							$response = array('data'=> array('status'=>'1','message'=>'Password changed successfully'));
					}elseif ($this->language=='arabic') {
							$response = array('data'=> array('status'=>'1','message'=>'تم تغيير الرقم السري بنجاح'));
					}elseif ($this->language=='french') {
							$response = array('data'=> array('status'=>'1','message'=>'Password changed successfully'));
					}


	   				
					echo json_encode($response);  exit;
	   			} else {
	   				$err = array('data' =>array('status' => '0', 'message' => 'Server not responding please try again !!'));
					echo json_encode($err); exit;
	   			}
			}
		}
	}

/*===========================================Statis Pages=========================================*/
	public function page()
	{   $this->check_login();
		$page_id  =	$this->test_input($this->input->post('page_id'));

		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please send page id'));
			echo json_encode($err); exit;
		}
		$tableName = 'pages';
		$where = array('page_id' => $page_id);
		if(!$page_data = $this->Common_model->getRecords($tableName,'*',$where,'',true)) {
			$err = array('data' =>array('status' => '0', 'message' => 'Page id not exists'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','message'=>'Page found successfully','details'=>$page_data));
			echo json_encode($response); exit;
		}
	}


/*===========================================Faq Pages=========================================*/
	public function Faq()
	{ 
        $tableName = 'faq';
		$where = array('status' =>'Active','approved'=>1);
		if(!$page_data = $this->Common_model->getRecords($tableName,'*',$where,'',false)) {
			$err = array('data' =>array('status' => '0', 'message' => 'Server not responding please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','message'=>'Faq found successfully','details'=>$page_data));
			echo json_encode($response); exit;
		}
	}

/*===========================================location=========================================*/

    public function location() {
       $this->check_login();
       $location	=	$this->test_input($this->input->post('location'));
       $user_id		=	$this->test_input($this->input->post('user_id'));
       $latitude	=	$this->test_input($this->input->post('latitude'));
       $longitude	=	$this->test_input($this->input->post('longitude'));


       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter your user id.'));
			echo json_encode($err); exit;
		} 

       if(empty($location)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter location Type.'));
			echo json_encode($err); exit;
		} else if($location != 'Yes' && $location != 'No') {
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter exact location Type.'));
			echo json_encode($err); exit;
		}
       if($location == 'Yes'){
		    $update_data = array(
                'location'  =>  $this->input->post('location'),
                'modified'  =>  date("Y-m-d H:i:s"),
                'latitude'  =>	$this->test_input($this->input->post('latitude')),
                'longitude'	=>	$this->test_input($this->input->post('longitude')),
		    ); 
        
		} else {
            $update_data = array(
                'location'  =>  $this->input->post('location'),
                'modified'  =>  date("Y-m-d H:i:s"),
		    );
        }
		if(!$this->Common_model->addEditRecords('users', $update_data,array('user_id'=>$user_id))) {
		    $err = array('data' =>array('status' => '0', 'message' => 'Some error occured! Please try again.'));
            echo json_encode($err); exit;
		} else {
			$suc = array('data' =>array('status' => '1', 'message' => 'location updated successfully.'));
            echo json_encode($suc); exit;
		} 
    }


	/*===========================================Forgot Password=========================================*/
	public function forgot_password_old()
	{ 
			$email = $this->test_input($this->input->post('email'));
		  	  
			if(empty($email)) {
				$err = array('data'=> array('status'=>'0','message'=>'Please enter your email.'));
				echo json_encode($err); exit;
			}

			if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				$err = array('data' =>array('status' => '0', 'message' => 'Please enter valid email.'));
				echo json_encode($err);exit;
			}

			
			$tableName ='users';
			$where = array('email' =>$email);
			if(!$user_data = $this->Common_model->getRecords($tableName,'user_id,username,full_name,password',$where,'',true)) {
				$err = array('data' =>array('status' => '0', 'message' => 'Please enter registered email.'));
			echo json_encode($err); exit;
		} else {
			
			if(!empty($user_data['full_name'])) {
				$user_name = $user_data['full_name'];
			}
			 
			$subject = SITE_TITLE." Your Login Password";
		 
			$data['title']=  'Your Login Password';
			$data['username']= $user_name;
			$data['message']= '<b>Your login Password is: </b>'.base64_decode($user_data['password']);
			$body = $this->load->view('template/common', $data,TRUE);
			$to_email = $email;
			$from_email = getAdminEmail(); 
			// $this->Common_model->setMailConfig();

		 
			//Send mail 
			if($this->Common_model->defaultEmailSend($to_email,$subject,$body,$from_email)) 
			{

				$response = array('data'=> array('status'=>'1','message'=>'We have sent your password to the email.'));
				echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'message' => 'Some error occured. Please try again !!.'));
				echo json_encode($err); exit;
			}
		}
	}

		public function forgot_password()
	{ 
			$email = $this->test_input($this->input->post('email'));
		  	  
			if(empty($email)) {
				$err = array('data'=> array('status'=>'0','message'=>'Please enter your email.'));
				echo json_encode($err); exit;
			}

			if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				$err = array('data' =>array('status' => '0', 'message' => 'Please enter valid email.'));
				echo json_encode($err);exit;
			}

			
			$tableName ='users';
			$where = array('email' =>$email);
			if(!$user_data = $this->Common_model->getRecords($tableName,'user_id,username,full_name,password',$where,'',true)) {
				$err = array('data' =>array('status' => '0', 'message' => 'Please enter registered email.'));
			echo json_encode($err); exit;
		} else {
			
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

				$response = array('data'=> array('status'=>'1','message'=>'We have sent your password to the email.'));
				echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'message' => 'Some error occured. Please try again !!.'));
				echo json_encode($err); exit;
			}
		}
	}

	/*=============================Reset Password===========================================*/
	public function reset_password()
	{
		$email 				=	$this->test_input($this->input->post('email'));
	  	$code				=	$this->test_input($this->input->post('code'));
	  	$new_password		=	$this->test_input($this->input->post('new_password'));
		
		$tableName = 'users';
		$user_data = array();
		if(empty($code)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter reset password code.'));
			echo json_encode($err); exit;
		} else {
			$where = array('email' => $email,'token' => $code);
			if(!$user_data = $this->Common_model->getRecords($tableName,'*',$where,'',true)) {
				$err = array('data' =>array('status' => '0', 'message' => 'Please enter valid code received on the email.'));
				echo json_encode($err); exit;
			}
		}

		if(empty($new_password)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter your password.'));
			echo json_encode($err); exit;
		} else if(!passwordValidate($new_password)) {
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter valid password.'));
			echo json_encode($err); exit;
		}

		$new_password = base64_encode($new_password);
		if($new_password == $user_data['password']) {
			$err = array('data' =>array('status' => '0', 'message' => "New password can't be same as old password."));
			echo json_encode($err); exit;
		}

		//check code is alive or expire
		$token_date = strtotime($user_data['token_date']);
		$current_date=strtotime(date("Y-m-d H:i:s"));
		$diff=$current_date-$token_date;
		if($diff > 86400) {
			$err = array('data' =>array('status' => '0', 'message' => 'Reset password code has been expired !!'));
			echo json_encode($err); exit;
		} 
		
		//update the new password in the database
		$date = date('Y-m-d H:i:s');
		$update_data = array(
 			'password' => $new_password, 
 			'token' =>'',
 			'token_date' =>'',
 			'modified'  =>  date("Y-m-d H:i:s"),
		);

       	$where = array('user_id' => $user_data['user_id']);
       	if($this->Common_model->addEditRecords($tableName,$update_data,$where)) {
       		$where = array('user_id' => $user_data['user_id']);
			$resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);

			if($resiver['notification']=='Yes'){
			    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
			    $demo=$this->badge_count($user_data['user_id'],'users','user_id');
			     $iosarray = array(
                    'alert' => 'You have successfully reset your password',
                    'type'  => 'reset_password',
                    'user_id' => $user_data['user_id'],
                    'badge' => $demo,
                    'sound' => 'default',
       			);

				$andarray = array(
	                'message'   => 'You have successfully reset your password',
	                'type'      =>'reset_password',
	                'user_id' => $user_data['user_id'],
	                'title'     => 'Notification',
            	);
				

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
			    $savearray = 'user_id-'.$user_data['user_id'];
				$add_data =array('user_id' =>$user_data['user_id'],'created_by' =>$user_data['user_id'],'type'=>'reset_password', 'notification_title'=>'reset password', 'notification_description'=>'You have successfully reset your password', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
				$this->Common_model->addEditRecords('notifications',$add_data); 
			}

			$response = array('data'=> array('status'=>'1','message'=>'Password reset successfully.'));
			echo json_encode($response); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'message' => 'Server not responding. Please try again !!'));
			echo json_encode($err); exit;
		}
	}
	/*=============================tour image===========================================*/
	public function banner()
	{ 
        $tableName = 'banners';
		
		if(!$page_data = $this->Common_model->getRecords($tableName,'*',array('type'=>'Home'),'',true)) {
			$err = array('data' =>array('status' => '0', 'message' => 'Server not responding please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','message'=>' banner found successfully','details'=>$page_data));
			echo json_encode($response); exit;
		}
	}
	public function banner_faq()
	{ 
        $tableName = 'banners';
		
		if(!$page_data = $this->Common_model->getRecords($tableName,'*',array('type'=>'FAQ'),'',true)) {
			$err = array('data' =>array('status' => '0', 'message' => 'Server not responding please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','message'=>' banner found successfully','details'=>$page_data));
			echo json_encode($response); exit;
		}
	}

	
	/*=============================Create Post===========================================*/	

	public function createPost()
    {  
    	$this->check_login();
        $detail	         =  $this->input->post('detail');  
        $post_title		 =	$this->test_input($this->input->post('post_title'));
        $user_id		 =	$this->test_input($this->input->post('user_id'));
        $post_date		 =	$this->test_input($this->input->post('post_date'));
        $page_id		 =	$this->test_input($this->input->post('page_id'));
        $hash_user		 =	$this->input->post('hash_user');
        $hash_page		 =	$this->input->post('hash_page');
        $tag_user		 =	$this->input->post('tag_user');
        $tag_page_id	 =	$this->input->post('tag_page_id');
        $latitude		 =	$this->input->post('latitude');
        $longitude		 =	$this->input->post('longitude');
        $address		 =	$this->input->post('address');
        $address_name	 =	$this->input->post('address_name');
        if(empty($address_name))
        {
        	  $address_name	='';
        }   
        if(empty($address))
        {
        	  $address	='';
        }
      

       if(empty($detail) && empty($_FILES['user_photo']['name'])){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Detail Or Photo'));
			echo json_encode($err); exit;
		} 


        if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter User Id.'));
			echo json_encode($err); exit;
		} 

		if(empty($post_title)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Post Title.'));
			echo json_encode($err); exit;
		}
 
		
		if(!empty($_FILES['user_photo']['name'])){
    		$photo = 0;
    		$allowed =  array('jpg','png','jpeg','JPG','PNG','JPEG');
			$filesCount = count($_FILES['user_photo']['name']);
            if ($filesCount <= 5) {
             	for($i = 0; $i <$filesCount; $i++){
					$filename = $_FILES['user_photo']['name'][$i];
				    $ext = pathinfo($filename, PATHINFO_EXTENSION);
				    if(!in_array($ext,$allowed) ) {
					   $err = array('data' =>array('status' => '0', 'message' => 'Only jpg|jpeg|png image types allowed..'));
			   			echo json_encode($err); exit;	
				    } 
			    }
            } else {
				$err = array('data' =>array('status' => '0', 'message' => 'You can not uplode more than 5.'));
			    echo json_encode($err); exit;
            }
        }
 

        if(!empty($_FILES['user_video']['name'])){
    		$photo = 0;
    		$allowed =  array('mp4','MP4');
			$filesCount = count($_FILES['user_video']['name']);
            if ($filesCount <= 2) {
             	for($i = 0; $i <$filesCount; $i++){
					$filename = $_FILES['user_video']['name'][$i];
				    $ext = pathinfo($filename, PATHINFO_EXTENSION);
				    if(!in_array($ext,$allowed) ) {
					   $err = array('data' =>array('status' => '0', 'message' => 'Only mp4 image types allowed..'));
			   			echo json_encode($err); exit;	
				    } 
			    }
            } else {
			$err = array('data' =>array('status' => '0', 'message' => 'You can not uplode more than 2.'));
		    echo json_encode($err); exit;
            }
        }
   
	   	$link="#";
	    $createtag = createtag($this->input->post('detail'), $link);
		if(count($createtag)==1){
			$detail =  current($createtag); 
		}else{
		    $detail =   end($createtag);
		}
        
        $update_data = array(
          	'post_title' => $post_title,
			'post_detail' => $detail,
			'business_page_id'=> $page_id,
			'post_date' => date('Y-m-d', strtotime($post_date)),
			'user_id' => $user_id,
			'created' => date("Y-m-d H:i:s"),
		);
        
		$last_id =   $this->Common_model->addEditRecords('user_post', $update_data);
		$where = array('post_id' => $last_id);
      
    	$this->Common_model->addEditRecords('user_post',array('parent_id' => $last_id),$where);

		if(count($createtag)!=1){
			array_pop($createtag);
			foreach ($createtag as $value) {
				if($id = $this->Common_model->getRecords('tags','tag_word_id',array('word' =>$value),'',true)){
                    $addtag = array(
					'tag_word_id' => $id['tag_word_id'],
					'post_id' => $last_id,
					'created' => date("Y-m-d H:i:s"),
					); 
				   $this->Common_model->addEditRecords('hashtagword', $addtag);
				}else {
					$addtag = array(
					'word' => $value,
					'created' => date("Y-m-d H:i:s"),
					); 
					$id = $this->Common_model->addEditRecords('tags', $addtag);

					$add = array(
					'tag_word_id' => $id,
					'post_id' => $last_id,
					'created' => date("Y-m-d H:i:s"),
					); 
					$this->Common_model->addEditRecords('hashtagword', $add);
				}	
			}
		}

		if(!empty($hash_user)){
            $tag_user_data = explode(",",$hash_user);
           	foreach ($tag_user_data as $user) {
                $addtag = array(
	      	        'post_id' => $last_id,
	      	        'id' => $user,
	      	        'is_page' => '0',
			        'created' => date("Y-m-d H:i:s"),
			    ); 

			    $this->Common_model->addEditRecords('hashtaguser', $addtag);

		        $where = array('user_id' => $user);
		        $resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);
		        $row = array('user_id' => $user_id);
		        $sender=$this->Common_model->getRecords('users','username,account_type',$row,'',true);
	        	
	        	if($page_id =='0'){
					$is_page = '0';
				}else{
					$is_page = '1';
				}
        		if($user != $user_id) {
            		if($resiver['notification']=='Yes'){
                		$log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
                		$demo=$this->badge_count($user,'users','user_id');
                		
                		if(!empty($log)){
                    		foreach ($log as $key) {
		                    	$iosarray = array(
				                    'alert' => $sender['username'].' mentioned you in their post',
				                    'type'  => 'comment_tag',
				                    'post_id' => $last_id,
				                    'account_type' =>  $sender['account_type'],
				                    'page_id' =>  $page_id,
				                     'is_user' =>  '1',
				                    'is_page' => $is_page,  
				                    'badge' => $demo,
				                    'sound' => 'default',
		               			);

								$andarray = array(
					                'message'   => $sender['username'].' mentioned you in their post',
					                'type'      =>'comment_tag',
					                'post_id' => $last_id, 
					                'account_type' =>  $sender['account_type'],
				                    'page_id' =>  $page_id,
				                     'is_user' =>  '1',
				                    'is_page' => $is_page,  
					                'title'     => 'Notification',
				            	);
						
                        
		                        if($key['device_type']=='Android'){
		                            $referrer = androidNotification($key['device_id'],$andarray);
		                        }

		                        if($key['device_type']=='IOS'){
		                            $referrer = iosNotification($key['device_id'],$iosarray);
		                        }
                    		}
                		}
		                $savearray = 'post_id-'.$last_id.'@page_id-'.$page_id.'@is_page-'.$is_page.'@account_type-'.$sender['account_type'].'@is_user-1';
		                $add_data =array('user_id' => $user,'created_by' =>$user_id,'type'=>'comment_tag', 'notification_title'=>'tagged Post', 'notification_description'=>$sender['username'].' mentioned you in their post.', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
		                $this->Common_model->addEditRecords('notifications',$add_data); 

            		}
        		}
            }
        }

        if(!empty($hash_page)){
        	
            $tag_user_data = explode(",",$hash_page);
       
           foreach ($tag_user_data as $user) {
            	
            	
                $addtag = array(
	      	        'post_id' => $last_id,
	      	        'id' => $user,
	      	        'is_page' => '1',
			        'created' => date("Y-m-d H:i:s"),
			    ); 

			    $this->Common_model->addEditRecords('hashtaguser', $addtag);


			       $where = array('business_page_id' => $user);
        $resiver=$this->Common_model->getRecords('business_page','push_notification,user_id',$where,'',true);
        $row = array('user_id' => $user_id);
        $sender=$this->Common_model->getRecords('users','username',$row,'',true);
        if($page_id =='0'){
				$is_page = '0';
			}else{
				$is_page = '1';
			}
       
            if($resiver['push_notification']=='Yes'){
            	 $lis = array('user_id' => $resiver['user_id']);
                $log=$this->Common_model->getRecords('users_log','device_type,device_id',$lis,'',false);
              $where = array('user_id' =>$resiver['user_id']);
			$count=$this->Common_model->getRecords('users','badge_count',$where,'',true); 
                if(!empty($log)){
                    foreach ($log as $key) {

                    	 $iosarray = array(
		                    'alert' => $sender['username'].' mentioned you in their post',
		                    'type'  => 'comment_tag',
		                    'post_id' => $last_id,
		                    
		                    'page_id' =>  $page_id,
		                     'is_user' =>  '0',
		                    'is_page' => $is_page,  
		                    'badge' => $count['badge_count'],
		                    'sound' => 'default',
               			);

						$andarray = array(
			                'message'   => $sender['username'].' mentioned you in their post',
			                'type'      =>'comment_tag',
			                'post_id' => $last_id, 
			                
		                    'page_id' =>  $page_id,
		                     'is_user' =>  '0',
		                    'is_page' => $is_page,  
			                'title'     => 'Notification',
		            	);
					
                        
                        if($key['device_type']=='Android'){
                            $referrer = androidNotification($key['device_id'],$andarray);
                        }

                        if($key['device_type']=='IOS'){
                            $referrer = iosNotification($key['device_id'],$iosarray);
                        }
                    }
                }
               	$savearray = 'post_id-'.$last_id.'@page_id-'.$page_id.'@is_page-'.$is_page.'@is_user-0';
                $add_data =array('user_id' => $user,'created_by' =>$user_id,'type'=>'comment_tag', 'notification_title'=>'tagged Post', 'notification_description'=>$sender['username'].' mentioned you in their post.', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
                $this->Common_model->addEditRecords('notifications',$add_data); 

            }
        
			}
        }
        
        if(!empty($tag_user)){
        	
            $tag_user_data = explode(",",$tag_user);
       
           foreach ($tag_user_data as $user) {
            	
            	
                $addtag = array(
	      	        'post_id' => $last_id,
	      	        'user_id' => $user,
			        'created' => date("Y-m-d H:i:s"),
			    ); 

			    $this->Common_model->addEditRecords('tag_user', $addtag);
				$demo=$this->badge_count($user,'users','user_id');
			  
        $where = array('user_id' => $user);
        $resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);
        $row = array('user_id' => $user_id);
        $sender=$this->Common_model->getRecords('users','username',$row,'',true);
         if($page_id =='0'){
				$is_page = '0';
			}else{
				$is_page = '1';
			}
        if($user != $user_id){
            if($resiver['notification']=='Yes'){
                $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
  
                       $iosarray = array(
		                    'alert' => $sender['username'].'  tagged you on a post',
		                    'type'  => 'tagged',
		                    'post_id' => $last_id, 
		                    'page_id' =>  $page_id,
		                    'is_user' =>  '1',
		                    'is_page' => $is_page,  
		                    'badge' => $demo,
		                    'sound' => 'default',
               			);

						$andarray = array(
			                'message'   => $sender['username'].' tagged you on a post',
			                'type'      =>'tagged',
			                'post_id' => $last_id, 
		                    'page_id' =>  $page_id,
		                    'is_user' =>  '1',
		                    'is_page' => $is_page,  
			                'title'     => 'Notification',
		            	);
					
                        
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
              	$savearray = 'post_id-'.$last_id.'@page_id-'.$page_id.'@is_page-'.$is_page.'@is_user-1';
                $add_data =array('user_id' => $user,'created_by' =>$user_id,'type'=>'tagged', 'notification_title'=>'tagged Post', 'notification_description'=>$sender['username'].' tagged you on their post.', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
                $this->Common_model->addEditRecords('notifications',$add_data); 

            }
        }


            }
        }


	 	if(!empty($tag_page_id)){
        	
			$tag_page_data = explode(",",$tag_page_id);
       
           	foreach ($tag_page_data as $page) {
                $addtag = array(
	      	        'post_id' => $last_id,
	      	        'page_id' => $page,
			        'created' => date("Y-m-d H:i:s"),
			    ); 
			    $this->Common_model->addEditRecords('tag_page', $addtag);
			     $where = array('business_page_id' => $user);
        $resiver=$this->Common_model->getRecords('business_page','push_notification,user_id',$where,'',true);
        $row = array('user_id' => $user_id);
        $sender=$this->Common_model->getRecords('users','username',$row,'',true);
        	if($page_id =='0'){
				$is_page = '0';
			}else{
				$is_page = '1';
			}

			      
       
            if($resiver['push_notification']=='Yes'){
            	 $lis = array('user_id' => $resiver['user_id']);
                $log=$this->Common_model->getRecords('users_log','device_type,device_id',$lis,'',false);
  				$where = array('user_id' =>$resiver['user_id']);
			$count=$this->Common_model->getRecords('users','badge_count',$where,'',true); 
                       $iosarray = array(
		                    'alert' => $sender['username'].' tagged you on a post',
		                    'type'  => 'tagged',
		                    'post_id' => $last_id, 
		                    'page_id' =>  $page_id,
		                    'is_user' =>  '0',
		                    'is_page' => $is_page,  
		                    'badge' => $count['badge_count'],
		                    'sound' => 'default',
               			);

						$andarray = array(
			                'message'   => $sender['username'].' tagged you on a post',
			                'type'      =>'tagged',
			                'post_id' => $last_id, 
		                    'page_id' =>  $page_id,
		                    'is_user' =>  '0',
		                    'is_page' => $is_page,  
			                'title'     => 'Notification',
		            	);
					
                        
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
              	$savearray = 'post_id-'.$last_id.'@page_id-'.$page_id.'@is_page-'.$is_page.'@is_user-1';
                $add_data =array('user_id' => $user,'created_by' =>$user_id,'type'=>'tagged', 'notification_title'=>'tagged Post', 'notification_description'=>$sender['username'].' tagged you on their post.', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
                $this->Common_model->addEditRecords('notifications',$add_data); 

            }
        

        	}
    	}
        
        
        if(!empty($latitude) && !empty($longitude)) {

        		/* for duble address check */
			$get_address_name =$this->Common_model->getRecords('gio_location','location_id',array('address' =>$address),'',true);
			if(!empty($get_address_name))
			{
				$where = array('address' =>$address);
			}else
			{
				$where = array('latitude' => $latitude,'longitude' => $longitude);
			}
 
		    if($gio_location=$this->Common_model->getRecords('gio_location','location_id',$where,'',true)) {
		    	$location = array(
					'location_id' => $gio_location['location_id'],
				);
				$this->Common_model->addEditRecords('user_post', $location,array('post_id'=> $last_id));  
		    }else {

				$addresstag = array(
					'latitude'  => $latitude,
					'longitude' => $longitude,
					'address_name' => $address_name,
					'address'   => $address,
					'created'   => date("Y-m-d H:i:s"),
				); 
				$gio_location =  $this->Common_model->addEditRecords('gio_location', $addresstag);
				$location = array(
					'location_id' => $gio_location,
				);
				$this->Common_model->addEditRecords('user_post', $location,array('post_id'=> $last_id));  
			}
		}
		
		if(!empty($_FILES['user_photo'])) {
			$filesCount = count($_FILES['user_photo']['name']);
			
			if ($filesCount <= 5) {
				for($i = 0; $i <$filesCount; $i++){
					$_FILES['post_photo']['name'] = $_FILES['user_photo']['name'][$i];
					$_FILES['post_photo']['type'] = $_FILES['user_photo']['type'][$i];
					$_FILES['post_photo']['tmp_name'] = $_FILES['user_photo']['tmp_name'][$i];
					$_FILES['post_photo']['error'] = $_FILES['user_photo']['error'][$i];
					$_FILES['post_photo']['size'] = $_FILES['user_photo']['size'][$i];
					
					//Rename image name 
					$img = time().'_'.rand();

					$config['upload_path'] = 'resources/images/post/';
					//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
					$config['allowed_types'] = '*';
					$config['file_name'] =  $img;
		
					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if($this->upload->do_upload('post_photo')){
						$fileData = $this->upload->data();
						$uploadData = array();
						$uploadData= array(
							'file_path' => 'resources/images/post/'.$config['file_name'].$fileData['file_ext'],
							'post_id' => $last_id,
							'created' => date("Y-m-d H:i:s"),
							'file_type'=> 'Image'
						);

						$this->Common_model->addEditRecords('post_img', $uploadData);
					} else {
						$error = array('error' => $this->upload->display_errors());
                        $err = array('data' =>array('status' => '0', 'message' => $error ));
						echo json_encode($err); exit;
					}
				} 
			} else {
				$err = array('data' =>array('status' => '0', 'message' => 'You can not uplode more then 5.'));
				echo json_encode($err); exit;
			}
		}


		if(!empty($_FILES['video_thumb'])){
			$files = count($_FILES['video_thumb']['name']);
			
			if ($files <= 2) {
				for($i = 0; $i <$files; $i++){
					$_FILES['post_video_thumb']['name'] = $_FILES['video_thumb']['name'][$i];
					$_FILES['post_video_thumb']['type'] = $_FILES['video_thumb']['type'][$i];
					$_FILES['post_video_thumb']['tmp_name'] = $_FILES['video_thumb']['tmp_name'][$i];
					$_FILES['post_video_thumb']['error'] = $_FILES['video_thumb']['error'][$i];
					$_FILES['post_video_thumb']['size'] = $_FILES['video_thumb']['size'][$i];

					$_FILES['post_user_video']['name'] = $_FILES['user_video']['name'][$i];
					$_FILES['post_user_video']['type'] = $_FILES['user_video']['type'][$i];
					$_FILES['post_user_video']['tmp_name'] = $_FILES['user_video']['tmp_name'][$i];
					$_FILES['post_user_video']['error'] = $_FILES['user_video']['error'][$i];
					$_FILES['post_user_video']['size'] = $_FILES['user_video']['size'][$i];

					//Rename image name 
					$img = time().'_'.rand();

					$config['upload_path'] = 'resources/images/post/';
					//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG|3gp';
					$config['allowed_types'] = '*';
					$config['file_name'] =  $img;
		
					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if($this->upload->do_upload('post_video_thumb')){
						$videoData = $this->upload->data();
					} else {
						$error = array('error' => $this->upload->display_errors());
                        $err = array('data' =>array('status' => '0', 'message' => $error ));
						echo json_encode($err); exit;
					}
					if($this->upload->do_upload('post_user_video')){
						$filevideo = $this->upload->data();	
					} else {
						$error = array('error' => $this->upload->display_errors());
                        $err = array('data' =>array('status' => '0', 'message' => $error ));
						echo json_encode($err); exit;
					}
					$uploadData = array();
					$uploadData= array(
						'file_path' => 'resources/images/post/'.$videoData['file_name'],
						'video_path' => 'resources/images/post/'.$filevideo['file_name'],
						'post_id' => $last_id,
						'created' => date("Y-m-d H:i:s"),
						'file_type'=> 'Video'
					);

					$this->Common_model->addEditRecords('post_img', $uploadData);
				} 
			} else {
				$err = array('data' =>array('status' => '0', 'message' => 'You can not upload more then 5.'));
				echo json_encode($err); exit;
			}
		}
                      
		if(!$last_id) {
		    $err = array('data' =>array('status' => '0', 'message' => 'Some error occured! Please try again.'));
		    echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','message'=>'Posted'));
		    echo json_encode($response); exit;
		}
    }

    public function getAddress($latitude,$longitude){
        if(!empty($latitude) && !empty($longitude)){
            $geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=false&key='.GOOGLE_API_KEY); 
            $output = json_decode($geocodeFromLatLong);
            $status = $output->status;
            $address = ($status=="OK")?$output->results[1]->formatted_address:'';
                if(!empty($address)){
                    return $address;
                }else{
                    return false;
                }
            }else{
                return false;   
        }
    }


	public function getPost() {
		$this->check_login();
		$user_id =	$this->input->post('user_id');	
	    $getdata = $this->App_model->getPostlogin($user_id);
	   

	    $page_arr  = $this->App_model->getpagelogin($user_id);
	    $getpage = array();
	   	if(!empty($page_arr))
	   	{
	   		$t = 0;
	   		foreach ($page_arr as $newa ) {
	   			if(is_numeric($newa))
	   			{ 
	   			 $getpage[$t] = $newa;
	   			 $t++;
	   			}
	   		
	   		}
	   	}
	 //  	echo "<pre>";print_r($getpage);die;
	    $where = array('user_id' =>$user_id);
		if($page_row = $this->Common_model->getRecords('business_page','business_page_id',$where,'',false)){
			foreach ($page_row as $key=> $get) {
			$getpage[$key] =	$get['business_page_id'];
		    }
		} 
	 

	    $dataone = $this->App_model->getdatalist($getdata,$getpage);

	    if(!empty($dataone)){
		    foreach ($dataone as $get) {
				if (in_array($get['user_id'], $getdata)){
		      	$getPost[]=$get;
		      	}else{
		      		if($get['business_page_id']!='0'){
		      			$getPost[]=$get;
		      		}else {
						$where = array('user_id' =>$get['user_id'],'account_type'=>'Public');
				 		if($post_images = $this->Common_model->getRecords('users','user_id',$where,'',false)) {
				 		$getPost[]=$get;
				 		}
				 	}	
		     	}
		    }
		}

	    if(!empty($getPost)){
			$index=0;
		
		    foreach ($getPost as $get) {

		    	if($get['business_page_id']!='0' ){
		    		$where = array('business_page_id' =>$get['business_page_id']);
		    		if($page_row = $this->Common_model->getRecords('business_page','user_id,business_image,business_name',$where,'',true)) {
		    			
		    			if($page_row['user_id']==$user_id){
		    				$getPost[$index]['is_my_page'] = '1';	
		    			}else{
		    				$getPost[$index]['is_my_page'] = '0';		
		    			}
				    		$getPost[$index]['username'] = $page_row['business_name'];
				    		$getPost[$index]['is_page'] = '1';
				    		$getPost[$index]['page_id'] = $get['business_page_id'];
				      		$getPost[$index]['profile_pic'] = $page_row['business_image'];
		      		}else{
			      		$getPost[$index]['is_page'] = '0';
			      		$getPost[$index]['page_id'] = '';
			      		$getPost[$index]['is_my_page'] = '0';
		      		}

		      		if($isFollow = $this->App_model->isFollowpage($get['business_page_id'],$user_id)) {
				    if($isFollow[0]['status']=='Follow'){
						$getPost[$index]['isFollow']  = '1';	
					}else{
						$getPost[$index]['isFollow']  = '2';
					}
					}else {
						$getPost[$index]['isFollow'] = '0';
					}
					
		      	}else {
		      	$getPost[$index]['is_page'] = '0';
		      	$getPost[$index]['page_id'] = '';
		      	$getPost[$index]['is_my_page'] = '0';
		      	if($isFollow = $this->App_model->isFollow($get['user_id'],$user_id)) {
					if($isFollow[0]['status']=='Follow'){
						$getPost[$index]['isFollow'] = '1';	
					}else{
						$getPost[$index]['isFollow'] = '2';
					}
				}else {
					$getPost[$index]['isFollow'] = '0';
				}
		      	}
		    
				if($get['user_id']==$user_id){
	                $getPost[$index]['myPost'] = '1';
				}else {
					$getPost[$index]['myPost'] = '0';
				}

				if($get['post_date'] == '0000-00-00') {
				$getPost[$index]['post_date'] ='';
	   			}

				if($post_likes = $this->App_model->getlikes($get['post_id'])) {
				$getPost[$index]['likes'] = $post_likes->likes;
				}else {
					$getPost[$index]['likes'] = '0';
				}

				if($post_comment = $this->App_model->getcomment($get['post_id'])) {
					$getPost[$index]['comment'] = $post_comment->comment;
				}else {
					$getPost[$index]['comment'] = '0';
				}

				if($isLike = $this->App_model->isLike($get['post_id'],$user_id)) {
					$getPost[$index]['isLike'] = $isLike->isLike;
				}else {
					$getPost[$index]['isLike'] = '0';
				}

				

	            $post_images = array();
				$where = array('post_id' => $get['parent_id']);
				if($post_images = $this->Common_model->getRecords('post_img','file_path,file_type,video_path',$where,'',false)) {
					$getPost[$index]['post_media'] = $post_images;
				}else{
				$getPost[$index]['post_media'] = $post_images;	
				}

	            $index++;
			}
			$where = array('user_id' =>$user_id);
			$count=$this->Common_model->getRecords('users','badge_count,chat_badge',$where,'',true); 
		
		$response = array('data'=> array('status'=>'1','message'=>'Post','details'=>$getPost,'badge_count'=>$count['badge_count'],'chat_badge'=>$count['chat_badge']));
		echo json_encode($response); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'message' => 'No Data Found'));
			echo json_encode($err); exit;	
		}
	} 
	public function getfollowerSharedPostC($user_id) {
		if($followers_data = $this->App_model->getFollowers($user_id)) {
			$fshared_data=array();
			$fshared_post=array();
			foreach($followers_data as $list) {
				$fshared_data = $this->App_model->getfollowerSharedPost($list['follow_user_id']);
				if(!empty($fshared_data)) {
					foreach($fshared_data as $row) {
						$fshared_post[] = $row;
					}
				}
			}
			return $fshared_post;
		}

		
	}

	public function like(){
		 
		$user_id		 =	$this->test_input($this->input->post('user_id'));
        $media_id		 =	$this->test_input($this->input->post('media_id'));
      	$this->check_login();
       if(empty($media_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter media Id.'));
			echo json_encode($err); exit;
		} 

       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter User Id.'));
			echo json_encode($err); exit;
		}

	    $where = array('media_id' => $media_id,'user_id' => $user_id);
		if($this->Common_model->getRecords('media_like','*',$where,'',true)) {
			  
			$this->Common_model->deleteRecords('media_like',$where);
			if($post_likes = $this->App_model->getlikes($media_id)) {
				$likes = $post_likes->likes;
			}else {
				$likes = '0';
			}

			$response = array('data'=> array('status'=>'1','message'=>'unlike','count'=>$likes));
			echo json_encode($response); exit;
		} else {
		        $add_data =array('media_id' => $media_id,'user_id' => $user_id,'created' => date("Y-m-d H:i:s"));
		        $this->Common_model->addEditRecords('media_like',$add_data);


					$media=$this->Common_model->getRecords('media','user_id',array('id'=>$media_id),'',true);
					$second_user=$this->Common_model->getRecords('users','user_id,full_name,device_id,device_type,badge_count',array('user_id'=>$media['user_id']),'',true);

					// echo "<pre>";print_r($second_user);die;
					$current_user=$this->Common_model->getRecords('users','full_name',array('user_id'=>$user_id),'',true);

		  
					      	$iosarray = array(
			                    'alert' => $current_user['full_name'].' liked your media.',
			                    'type'  => 'media_type',
			                   	'media_id'=> $media_id,
			                   	'badge' => $second_user['badge_count'],
			                    'sound' => 'default',
			                    'user_id' => $user_id,
			       			);

  
							$andarray = array(
				                'message'   => $current_user['full_name'].' liked your media.',
				               	'type'  => 'media_type',
			                   	'media_id'=> $media_id,
			                   	'user_id' => $user_id,
				                'title'     => $current_user['full_name'].' liked your media.',
			            	);
							

					    		if($second_user['device_type']=='Android'){
									$referrer = androidNotification($second_user['device_id'],$andarray);
								}

					    		if($second_user['device_type']=='IOS'){

			                   		 $referrer = iosNotification($second_user['device_id'],$iosarray);
			                   		
					    		}
					    
				 
					    $add_data =array('user_id' => $second_user['user_id'],'media_id'=>$media_id,'created_by' =>$user_id,'type'=>'media_like', 'notification_title'=> $current_user['full_name'].' liked your media.', 'notification_description'=>  $current_user['full_name'].' liked your media.','created'=>date('Y-m-d H:i:s'));
			    		$this->Common_model->addEditRecords('notifications',$add_data); 
   
				   if($post_likes = $this->App_model->getlikes($media_id)) {
						$likes = $post_likes->likes;
					}else {
						$likes = '0';
					}
	     
         	$response = array('data'=> array('status'=>'1','message'=>'like','count'=>$likes));
			echo json_encode($response); exit;  
        }
	} 


	public function block_unblock(){
		 
		$user_id		 =	$this->test_input($this->input->post('user_id'));
        $chat_room_id		 =	$this->test_input($this->input->post('room_id'));
      	$this->check_login();   
       if(empty($chat_room_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter room Id.'));
			echo json_encode($err); exit;
		} 



     
	    $where = array('room_id' => $chat_room_id,'is_blocked'=>1);
		if($this->Common_model->getRecords('users_chat','*',$where,'',true)) {


			$users_c = $this->Common_model->getRecords('users_chat','sender_id,receiver_id',array('room_id'=>$chat_room_id),'',true);
			if($users_c['sender_id']==$user_id){
				$second_id = $users_c['receiver_id'];
				$first_id = $user_id;

				 	 $this->Common_model->addEditRecords('users_chat',array('is_blocked'=>0),array('sender_id' =>$first_id,'receiver_id' =>$second_id));
				 	 $this->Common_model->addEditRecords('users_chat',array('is_blocked'=>0),array('receiver_id' =>$first_id,'sender_id' =>$second_id));

			}else{
				$second_id = $users_c['sender_id'];
				$first_id = $user_id;

				 	$this->Common_model->addEditRecords('users_chat',array('is_blocked'=>0),array('sender_id' =>$first_id,'receiver_id' =>$second_id));
				 	$this->Common_model->addEditRecords('users_chat',array('is_blocked'=>0),array('receiver_id' =>$first_id,'sender_id' =>$second_id));
			}

			  
		

			$response = array('data'=> array('status'=>'1','message'=>'Patient unblocked'));
			echo json_encode($response); exit;
		} else {

			$users_c = $this->Common_model->getRecords('users_chat','sender_id,receiver_id',array('room_id'=>$chat_room_id),'',true);
			if($users_c['sender_id']==$user_id){
				$second_id = $users_c['receiver_id'];
				$first_id = $user_id;

				 	 $this->Common_model->addEditRecords('users_chat',array('is_blocked'=>1),array('sender_id' =>$first_id,'receiver_id' =>$second_id));
				 	 $this->Common_model->addEditRecords('users_chat',array('is_blocked'=>1),array('receiver_id' =>$first_id,'sender_id' =>$second_id));

			}else{
				$second_id = $users_c['sender_id'];
				$first_id = $user_id;

				 	$this->Common_model->addEditRecords('users_chat',array('is_blocked'=>1),array('sender_id' =>$first_id,'receiver_id' =>$second_id));
				 	$this->Common_model->addEditRecords('users_chat',array('is_blocked'=>1),array('receiver_id' =>$first_id,'sender_id' =>$second_id));
			}


		   	// $this->Common_model->addEditRecords('users_chat',array('is_blocked'=>1),array('room_id' => $chat_room_id));    

         	$response = array('data'=> array('status'=>'1','message'=>'Patient blocked'));
			echo json_encode($response); exit;
        }
	} 


	public function appointment(){
		 
		$user_id	 =	$this->test_input($this->input->post('user_id'));
        $dr_id		 =	$this->test_input($this->input->post('dr_id'));
      	$this->check_login();
       	if(empty($dr_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter dr id.'));
			echo json_encode($err); exit;
		} 

	    $add_data =array('user_id' => $user_id,'dr_id' => $dr_id,'created' => date("Y-m-d H:i:s"));
	    $this->Common_model->addEditRecords('appointment',$add_data);
 
		$second_user=$this->Common_model->getRecords('users','user_id,full_name,device_id,device_type,badge_count',array('user_id'=>$dr_id),'',true);

		$current_user=$this->Common_model->getRecords('users','full_name',array('user_id'=>$user_id),'',true);


		      	$iosarray = array(
                    'alert' => $current_user['full_name'].' want to appointment.',
                    'type'  => 'appointment',
                   	'badge' => $second_user['badge_count'],
                    'sound' => 'default',
                    'user_id' => $user_id,
       			);
 
				$andarray = array(
	                'message'   => $current_user['full_name'].' want to appointment.',
	               	'type'  => 'appointment',
                   	'user_id' => $user_id,
	                'title'     => $current_user['full_name'].' want to appointment.',
            	);
				

		    		if($second_user['device_type']=='Android'){
						$referrer = androidNotification($second_user['device_id'],$andarray);
					}

		    		if($second_user['device_type']=='IOS'){

                   		 $referrer = iosNotification($second_user['device_id'],$iosarray);
                   		
		    		}
		    
	 
		    $add_data =array('user_id' => $second_user['user_id'],'created_by' =>$user_id,'type'=>'appointment', 'notification_title'=> $current_user['full_name'].'want to appointment.', 'notification_description'=>  $current_user['full_name'].' want to appointment.','created'=>date('Y-m-d H:i:s'));
    		$this->Common_model->addEditRecords('notifications',$add_data); 


			if($this->language=='english'){
					$response = array('data'=> array('status'=>'1','message'=>'Appointment request sent successfully'));
			}elseif ($this->language=='arabic') {
					$response = array('data'=> array('status'=>'1','message'=>'طلب حجز موعد تم بنجاح) '));
			}elseif ($this->language=='french') {
					$response = array('data'=> array('status'=>'1','message'=>'Une demande de rendez-vous a été envoyée avec succès'));
			}
	    
         
			echo json_encode($response); exit;  
        
	} 



	public function postComment(){
		$this->check_login();

        $user_id		 =	$this->test_input($this->input->post('user_id'));
        $post_id		 =	$this->test_input($this->input->post('post_id'));
        $comment		 =	$this->test_input($this->input->post('comment'));
        $page_id		 =	$this->test_input($this->input->post('page_id'));
       
       if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}

       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter User Id.'));
			echo json_encode($err); exit;
		}

		if(empty($comment)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Comment.'));
			echo json_encode($err); exit;
		}
		$where = array('post_id' => $post_id);
	    $user=$this->Common_model->getRecords('user_post','user_id,business_page_id',$where,'',true);
		$where = array('user_id' => $user['user_id']);
		$resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);
		$row = array('user_id' => $user_id);
	    $sender=$this->Common_model->getRecords('users','username',$row,'',true);
		if($user['user_id'] != $user_id){
			if($resiver['notification']=='Yes'){
				if($user['business_page_id']=='0'){
					$is_page = '0';
				}else{
					$is_page = '1';
				}
			    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
 					$demo=$this->badge_count($user['user_id'],'users','user_id');
			     $iosarray = array(
		                    'alert' => $sender['username'].' commented on your post',
		                    'type'  => 'comment',
		                    'post_id' => $post_id, 
		                    'page_id' => $user['business_page_id'],
		                    'is_page' => $is_page,  
		                    'badge' => $demo,
		                    'sound' => 'default',
               			);

						$andarray = array(
			                'message'   => $sender['username'].' commented on your post',
			                'type'      =>'comment',
			                'post_id'   => $post_id,
			                'page_id' =>   $user['business_page_id'],
		                    'is_page' =>   $is_page, 
			                'title'     => 'Notification',
		            	);
						

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
			   $savearray = 'post_id-'.$post_id.'@page_id-'.$user['business_page_id'].'@is_page-'.$is_page;
			    $add_data =array('page_id'=>$page_id,'post_id'=>$post_id,'user_id' => $user['user_id'],'created_by' =>$user_id,'type'=>'comment', 'notification_title'=>'Comment Post', 'notification_description'=>$sender['username'].' commented on your post', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
	    		$this->Common_model->addEditRecords('notifications',$add_data); 

			}
		}
		   
        $update_data =array('post_id' => $post_id,'user_id' => $user_id, 'page_id' => $page_id, 'comment' =>$comment ,'created' => date("Y-m-d H:i:s"));
        $this->Common_model->addEditRecords('post_comment',$update_data);
    	if($post_comment = $this->App_model->getcomment($post_id)) {
			$comment =  $post_comment->comment;
		}else {
			$comment = '0';
		}
        $response = array('data'=> array('status'=>'1','message'=>'comment','details'=>$comment));
		echo json_encode($response); exit;
     }  


	public function getPostDetails() {
		$this->check_login();
		$post_id =	$this->input->post('post_id');
		$user_id =	$this->input->post('user_id');
		$page_id =	$this->input->post('page_id');
		$type =	$this->input->post('type');

		if(empty($post_id)){
		$user_id =	'0';
		}   

		if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}else {
			$tableName="user_post";
			$where = array('post_id' =>$post_id,'is_deleted'=>'0','status'=>'Active');
			$view_post=$this->Common_model->getRecords($tableName,'view_post,post_id',$where,'',true);
			if(empty($view_post)){
				$err = array('data' =>array('status' => '0', 'message' => 'Post is Deactive or may be Deleted.'));
				echo json_encode($err); exit;
			}
		}

        if($type == 'post_view'){
		$tableName="user_post";
		$where = array('post_id' => $post_id);
		$view_post=$this->Common_model->getRecords($tableName,'view_post',$where,'',true);
		$count= $view_post['view_post']+1;
		$update_data = array('view_post'=>$count);
		$resdevice=$this->Common_model->addEditRecords($tableName,$update_data,$where);
	    }

	    $where = array('post_id' =>$post_id);
        $post_list  = $this->Common_model->getRecords('user_post','',$where,'',true); 
     
		
	    if($getPost =  $this->App_model->getPostdetails($post_id)) {
            $post_images = array();
			if($getPost['post_date'] == '0000-00-00') {
				$getPost['post_date'] ='';
			}

			if(!empty($page_id)){
		    		$where = array('business_page_id' =>$page_id);
		    		if($page_row = $this->Common_model->getRecords('business_page','business_image,business_name',$where,'',true)) {
			    		$getPost['username'] = $page_row['business_name'];
			      		$getPost['profile_pic'] = $page_row['business_image'];
		      		}

		      		if($isFollow = $this->App_model->isFollowpage($page_id,$user_id)) {
				    if($isFollow[0]['status']=='Follow'){
						$getPost['isFollow']  = '1';	
					}else{
						$getPost['isFollow']  = '2';
					}
					}else {
						$getPost['isFollow'] = '0';
					}
					$getPost['is_page'] = '1';

		      	}else{
				if($isFollow = $this->App_model->isFollow($getPost['user_id'],$user_id)) {
			    if($isFollow[0]['status']=='Follow'){
					$getPost['isFollow']  = '1';	
				}else{
					$getPost['isFollow']  = '2';
				}
				}else {
					$getPost['isFollow'] = '0';
				}
				$getPost['is_page'] = '0';

		      	}

            if($post_likes = $this->App_model->getlikes($post_id)) {
				$getPost['likes'] = $post_likes->likes;
			}else {
				$getPost['likes'] = '0';
			}

			if($post_comment = $this->App_model->getcomment($post_id)) {
				$getPost['comment'] = $post_comment->comment;
			}else {
				$getPost['comment'] = '0';
			}
			$where = array('post_id' => $post_list['parent_id']);
			if($post_images = $this->Common_model->getRecords('post_img','file_path,file_type,video_path,post_img_id as media_id',$where,'',false)) {
				$getPost['post_media'] = $post_images;
			}else{
			$getPost['post_media'] = $post_images;	
			}
			if($isLike = $this->App_model->isLike($post_id,$user_id)) {
				$getPost['isLike'] = $isLike->isLike;
			}else {
				$getPost['isLike'] = '0';
			}
			if($getPost['user_id']==$user_id){
                $getPost['myPost'] = '1';
			}else {
				$getPost['myPost'] = '0';
			}
		
			if($post_comment_list = $this->App_model->getcommentlist($post_id)) {
				$getPost['comment_list'] = $post_comment_list;
			}else {
				$getPost['comment_list'] = array();
			}
			if($post_comment_list = $this->App_model->taguser($post_list['parent_id'])) {
				$getPost['tag_user'] = $post_comment_list;
			}else {
				$getPost['tag_user'] = array();
			}
			// echo "<pre>";print_r($getPost['tag_user'])
			if($post_comment_list = $this->App_model->hashtaguser($post_list['parent_id'])) {
				$getPost['hashtaguser'] = $post_comment_list;
			}else {
				$getPost['hashtaguser'] = array();
			}

			if($post_comment_list = $this->App_model->hashtagpage($post_list['parent_id'])) {
				
				$index=0;
		    	foreach ($post_comment_list as $key) {
		    		
		    		if($key['user_id']==$user_id){
						$post_comment_list[$index]['is_my_page'] = '1';
					}else{
						$post_comment_list[$index]['is_my_page'] = '0';
					}

		    		$index++;
		    	}
			   	$getPost['hashtagpage'] = $post_comment_list; 
			}else {
				$getPost['hashtagpage'] = array();
			}

			if($post_comment_list = $this->App_model->hashtagword($post_list['parent_id'])) {
				$getPost['hashtagword'] = $post_comment_list;
			}else {
				$getPost['hashtagword'] = array();
			}

			if($post_comment_lists = $this->App_model->tagpage($post_list['parent_id'])) {
					$index=0;
		    	foreach ($post_comment_lists as $key) {
		    		
		    		if($key['user_id']==$user_id){
						$post_comment_lists[$index]['is_my_page'] = '1';
					}else{
						$post_comment_lists[$index]['is_my_page'] = '0';
					}

		    		$index++;
		    	}
			
				$getPost['tag_page'] = $post_comment_lists;
			}else {
				$getPost['tag_page'] = array();
			}
			$response = array('data'=> array('status'=>'1','message'=>'Details','details'=>$getPost));
			echo json_encode($response); exit;
			   
		}else {
            $response = array('data'=> array('status'=>'0','message'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
           
        }
    }

    public function getCommentList() {
    	$this->check_login();
        $post_id =	$this->test_input($this->input->post('post_id'));
        $page_id =	$this->test_input($this->input->post('page_id'));

        if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}
            if($post_comment = $this->App_model->getcommentlist($post_id)) {
				   $index=0;
				foreach ($post_comment as $get) {
					
						if($get['page_id']!='0'){
				    		$where = array('business_page_id' =>$get['page_id']);
				    		if($page_row = $this->Common_model->getRecords('business_page','business_image,business_name',$where,'',true)) {
					    		
					    		$post_comment[$index]['username'] = $page_row['business_name'];
					      		$post_comment[$index]['profile_pic'] = $page_row['business_image'];
				      		}
				      		
				      	}
				      	 $index++;
		      	}
				$response = array('data'=> array('status'=>'1','message'=>'comment found successfully','details'=>$post_comment));
				echo json_encode($response); exit;
			}else {
				$response = array('data'=> array('status'=>'0','message'=>'Be the first one to comment'));
				echo json_encode($response); exit;
			}
    }

    public function FollowingList() {
    	$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id'));
		$second_user_id = $this->test_input($this->input->post('owner_user_id'));
       	if(empty($second_user_id)){
			 $second_user_id = $user_id;	
		}

            if($post_comment = $this->App_model->postFollowList($second_user_id)) {

            	  $index=0;
				foreach ($post_comment as $comment) {
					
                	if($isFollow = $this->App_model->isFollow($comment['user_id'],$user_id)) {
					    if($isFollow[0]['status']=='Follow'){
							$post_comment[$index]['isFollow']  = '1';	
						}else{
							$post_comment[$index]['isFollow']  = '2';
						}
					}else {
						$post_comment[$index]['isFollow'] = '0';
					}
					$index++;
				}
            	
             
				$response = array('data'=> array('status'=>'1','message'=>'Following List Found Successfully','details'=>$post_comment));
				echo json_encode($response); exit;
			}else {
				$response = array('data'=> array('status'=>'0','message'=>'No Follow found '));
				echo json_encode($response); exit;
			}
    }

      public function FollowerList() {
    	$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id'));
      
       	$second_user_id = $this->test_input($this->input->post('owner_user_id'));
       	if(empty($second_user_id)){
			 $second_user_id = $user_id;	
		}
            if($post_comment = $this->App_model->postFollowingList($second_user_id)) {
                  $index=0;
				foreach ($post_comment as $comment) {

					
                	if($isFollow = $this->App_model->isFollow($comment['user_id'],$user_id)) {
					    if($isFollow[0]['status']=='Follow'){
							$post_comment[$index]['isFollow']  = '1';	
							$post_comment[$index]['on_top']  = '1';	
						}else{
							$post_comment[$index]['isFollow']  = '2';
							$post_comment[$index]['on_top']  = '1';	
						}
					}else {
						$post_comment[$index]['isFollow'] = '0';
						$post_comment[$index]['on_top'] = '1';
					}
					if($comment['user_id']==$user_id)
					{
						$post_comment[$index]['on_top']  = '100';	
					}

					$index++;
				}
				if(!empty($post_comment))
				{
					$post_comments = multid_sort($post_comment, 'on_top','Desc'); 
				}
				// echo "<pre>";print_r($post_comments);
				$response = array('data'=> array('status'=>'1','message'=>'Follower List Found Successfully','details'=>$post_comments));
				echo json_encode($response); exit;
			}else {
				$response = array('data'=> array('status'=>'0','message'=>'No Following found '));
				echo json_encode($response); exit;
			}
    }

     public function postFollow() {
    	$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id'));
        $follow_user_id =	$this->test_input($this->input->post('follow_user_id'));
    	
        if(empty($follow_user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Follow User Id.'));
			echo json_encode($err); exit;
		}

        $where = array('follow_user_id' => $follow_user_id, 'user_id' => $user_id);
			if($post_images = $this->Common_model->getRecords('follow_user','*',$where,'',true)) {
				$this->Common_model->deleteRecords('follow_user',$where);

					$where11 = array('user_id' =>$follow_user_id,'created_by' =>$user_id,'type'=>'follow');
					$add =array('status' =>'1');
					$this->Common_model->addEditRecords('notifications',$add,$where11); 

				$response = array('data'=> array('status'=>'1','message'=>'Unfollow'));
					echo json_encode($response); exit;
			}else{
				$update_data  =  array('user_id' => $follow_user_id);
        		$result = $this->Common_model->getRecords('users', 'account_type', $update_data,"user_id Desc", true); 
        		if($result['account_type']=='Public'){
            		$update_data =array('follow_user_id' => $follow_user_id,'user_id' => $user_id, 'status'=>'Follow','created' => date("Y-m-d H:i:s"));
               		$status = 'Follow';
               		$msg = ' is following you';
                }else{
                	$update_data =array('follow_user_id' => $follow_user_id,'user_id' => $user_id, 'status'=>'Pending','created' => date("Y-m-d H:i:s"));
             		$status = 'Pending';
             		$msg = ' wants to follow you';
             	}


			if($user_id != $follow_user_id){
				
				$where = array('user_id' => $follow_user_id);
				$resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);
				$row = array('user_id' => $user_id);
		        $sender=$this->Common_model->getRecords('users','username',$row,'',true);
		        $where12 = array('user_id' =>$follow_user_id,'created_by' =>$user_id,'type'=>'follow');
			    $notifications=$this->Common_model->getRecords('notifications','notification_id',$where12,'',true);
				if(empty($notifications)){
					if($resiver['notification']=='Yes'){
					    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
					    $demo=$this->badge_count($user_id,'users','user_id');
						$iosarray = array(
		                    'alert' => $sender['username'].$msg,
		                    'type'  => 'follow',
		                   'status'=>$status,
		                   'other_user_id'=>$user_id,
		                    'badge' =>  $demo,
		                    'sound' => 'default',
               			);

						$andarray = array(
			                'message'   => $sender['username'].$msg,
			                'type'      =>'follow',
			                'status'=>$status,
			              'other_user_id'=>$user_id,
			                'title'     => 'Notification',
		            	);
						
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
					  $savearray = 'other_user_id-'.$user_id.'@status-'.$status; 
					  $add_data =array('user_id' =>$follow_user_id,'created_by' =>$user_id,'type'=>'follow', 'notification_title'=>'user follow', 'notification_description'=>$sender['username'].$msg, 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
		        		$this->Common_model->addEditRecords('notifications',$add_data);   

					}
				}else{
					$where11 = array('user_id' =>$follow_user_id,'created_by' =>$user_id,'type'=>'follow');
					$add11 =array('status' =>'0','notification_sent_datetime' => date("Y-m-d H:i:s"));
					$this->Common_model->addEditRecords('notifications',$add11,$where11); 
				}
				}
			


            	$this->Common_model->addEditRecords('follow_user',$update_data);
          
            	$response = array('data'=> array('status'=>'1','message'=>$status,'details'=>$status));
				echo json_encode($response); exit;
            }
    }


   public function postshare() {
    	$this->check_login();
        $user_id         =	$this->test_input($this->input->post('user_id'));
        $post_id         =	$this->test_input($this->input->post('post_id'));
        $page_id         =	$this->test_input($this->input->post('page_id'));
      
        $post_owner	     =  $this->test_input($this->input->post('post_owner'));

        if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}

		if(empty($post_owner)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Post Owner Id.'));
			echo json_encode($err); exit;
		}

		$where = array('post_id' => $post_id);
		
		$resuser=$this->Common_model->getRecords('user_post','*',$where,'',true);
		if(!empty($page_id )){
			$wh = array('business_page_id' => $page_id);
		
			$res=$this->Common_model->getRecords('business_page','*',$wh,'',true);
			$share_by=$res['business_name'];
			$page=$page_id;
			$shareby_id=0;
		}else{

			$wh = array('user_id' => $user_id);
			
			$res=$this->Common_model->getRecords('users','*',$wh,'',true);
			$share_by=$res['username'];
			$shareby_id=$user_id;
			$page=0;
		}
     


        $update_data = array(
          	'business_page_id' 	=> $resuser['business_page_id'],
          	'post_title' 		=> $resuser['post_title'],
          	'shareby_id' 		=> $shareby_id,
          	'share_by' 			=> $share_by,
          	'is_page_shareby'   => $page,
			'post_detail' 		=> $resuser['post_detail'],
			'user_id' 			=> $resuser['user_id'],
			'parent_id'			=> $resuser['parent_id'],
			'business_page_id'	=> $resuser['business_page_id'],
			'post_date'  		=> date('Y-m-d', strtotime($resuser['post_date'])),
			'location_id' 		=> $resuser['location_id'],
			'created' 			=> date("Y-m-d H:i:s"),
		);
		 		
        if($post_id=$this->Common_model->addEditRecords('user_post',$update_data)){


		
			
			if($resuser['user_id'] != $user_id){
					
					if($resuser['business_page_id']=='0'){
						$is_page = '0';
					}else{
						$is_page = '1';
					}
					$where = array('user_id' => $resuser['user_id']);
					$resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);
					$row = array('user_id' => $user_id);
			        $sender=$this->Common_model->getRecords('users','username',$row,'',true);
	
					if($resiver['notification']=='Yes'){
					    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
					    $demo=$this->badge_count($user_id,'users','user_id');
					    if(!empty($log)){
					    	foreach ($log as $key) {
					     $iosarray = array(
		                    'alert' => $sender['username'].' shared your post',
		                    'type'  => 'share',
		                    'post_id' => $post_id, 
		                    'page_id' => $resuser['business_page_id'],
		                    'is_page' => $is_page,  
		                    'badge' =>  $demo,
		                    'sound' => 'default',
               			);

						$andarray = array(
			                'message'   => $sender['username'].' shared your post',
			                'type'      =>'share',
			                'post_id'   => $post_id,
			                'page_id' =>   $resuser['business_page_id'],
		                    'is_page' =>   $is_page, 
			                'title'     => 'Notification',
		            	);

					    if($key['device_type']=='Android'){
									$referrer = androidNotification($key['device_id'],$andarray);
								}

					    		if($key['device_type']=='IOS'){
	                           		$referrer = iosNotification($key['device_id'],$iosarray);
					    		}	
					    	
					    	}
					    }
					   	$savearray = 'post_id-'.$post_id.'@page_id-'.$resuser['business_page_id'].'@is_page-'.$is_page;

					    $add_data =array('post_id'=>$post_id,'page_id'=>$resuser['business_page_id'],'user_id' =>$resuser['user_id'],'created_by' =>$user_id,'type'=>'share', 'notification_title'=>'Post share', 'notification_description'=>$sender['username'].' shared your post', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
		        		$this->Common_model->addEditRecords('notifications',$add_data); 

					}
				}
			


        	$response = array('data'=> array('status'=>'1','message'=>'Post Shared'));
			echo json_encode($response); exit;
        }else{
        	$response = array('data'=> array('status'=>'0','message'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
        }

    }


     public function reportPost() {
    	$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id'));
        $post_id =	$this->test_input($this->input->post('post_id'));

        if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}

        $report_detail =	$this->test_input($this->input->post('report_detail'));
        $It_Spam =	$this->test_input($this->input->post('it_spam'));
        $It_Inappropriate =	$this->test_input($this->input->post('it_inappropriate'));

        $update_data = array('post_id' => $post_id,'user_id' => $user_id, 'report_detail' => $report_detail, 'It_Spam' => $It_Spam , 'It_Inappropriate' => $It_Inappropriate,  'created' => date("Y-m-d H:i:s"));
             		
        if($this->Common_model->addEditRecords('report_post',$update_data)){
        	$response = array('data'=> array('status'=>'1','message'=>'Thank you for your report'));
			echo json_encode($response); exit;
        }else{
        	$response = array('data'=> array('status'=>'0','message'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
        }

    }

    public function deletePost() {
    	$this->check_login();
        $post_id =	$this->test_input($this->input->post('post_id')); 

        if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}
		$where = array('post_id' =>$post_id);
        $post_list  = $this->Common_model->getRecords('user_post','',$where,'',true); 
     
        if($post_list['parent_id']== $post_id){
        	 $update_data = array('parent_id' => $post_list['parent_id']);
        	}else{
    		 $update_data = array('post_id' => $post_id);
        	}

      
        $array = array('is_deleted' => '1','deleted_by' => 'user'); 
        if($this->Common_model->addEditRecords('user_post',$array,$update_data)){
        	$response = array('data'=> array('status'=>'1','message'=>'Post Deleted'));
			echo json_encode($response); exit;
        }else {
        	$response = array('data'=> array('status'=>'0','message'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
		}
	}

 
 
 

	public function getCategory()
	{ 
		// $this->check_login();
        $tableName = 'categories';
		$where = array('status'=>'Active');
		if(!$categories = $this->Common_model->getRecords($tableName,'category_id,name',$where,'orders ASC',false)) {
			$err = array('data' =>array('status' => '0', 'message' => 'Server not responding please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','message'=>' categories found successfully','details'=>$categories));
			echo json_encode($response); exit;
		}
	}

	public function getSubCategory()
	{   $this->check_login();
		$categories	=	$this->test_input($this->input->post('categories_id'));
	  			
  			if(empty($categories)){
				$err = array('data' =>array('status' => '0', 'message' => 'Please Select Categories.'));
				echo json_encode($err); exit;
			} 

		
		
        $tableName = 'sub_categories';
		$where = array('category_id' => $categories,'status'=>'Active');
		if(!$sub_categories = $this->Common_model->getRecords($tableName,'sub_category_id,name,image',$where,'orders ASC',false)) { 
			$err = array('data' =>array('status' => '0', 'message' => 'categories not valid please try again !!'));
			echo json_encode($err); exit;
		} else {
  
			$response = array('data'=> array('status'=>'1','message'=>' sub categoriesfound successfully','details'=>$sub_categories));
			echo json_encode($response); exit;
		}
	}
 
 
 
	public function getuserDetails()
	{
	 	$this->check_login();
		$user_id =	$this->input->post('user_id');
		if(empty($user_id))
		{
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		}
 
	    if($user_details = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id,'status'=>'Active','is_deleted'=>'0'),'',true)) {

	    		$country_name =  $this->Common_model->getRecords('countries','name',array('id'=>$user_details['country_id']),'',true);

	    		$user_details['country_name'] =$country_name['name'];
	    		$state_name =  $this->Common_model->getRecords('states','name',array('id'=>$user_details['state_id']),'',true);
	    		$user_details['state_name'] =$state_name['name'];
	    		$city_name =  $this->Common_model->getRecords('cities','name',array('id'=>$user_details['city_id']),'',true);
	    		$user_details['city_name'] =$city_name['name'];	
	    		$category_name =  $this->Common_model->getRecords('categories','name',array('category_id'=>$user_details['category_id']),'',true);
	    		$user_details['category_name'] =$category_name['name'];
			 
			$response = array('data'=> array('status'=>'1','message'=>'Details','details'=>$user_details));
			echo json_encode($response); exit;
			   
		}else {
            $response = array('data'=> array('status'=>'0','message'=>'User not found.'));
			echo json_encode($response); exit;
           
        }
    }


   	public function addDeviceId()
   	{

   		$device_id =	$this->input->post('device_id');
   		$device_type =	$this->input->post('device_type');
		if(empty($device_id))
		{
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter device_id.'));
			echo json_encode($err); exit;
		}
		$device_type =	$this->input->post('device_type');
		if(empty($device_type))
		{
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter device_type.'));
			echo json_encode($err); exit;
		} 

		if(!$this->Common_model->getRecords('devices_id','*',array('device_type'=>$device_type,'device_id'=>$device_id),'',true)){
			$insert_data = array(
				'device_id'=> $this->input->post('device_id'),
				'device_type'=> $this->input->post('device_type'), 
			);


			
	 		if(!$ad_id= $this->Common_model->addEditRecords('devices_id', $insert_data)) {
				
				$err = array('data' =>array('status' => '0', 'message' => 'Some error occured! Please try again.'));
				echo json_encode($err); exit;
			} else {
				$err = array('data' =>array('status' => '1', 'message' => 'Device Added'));
				echo json_encode($err); exit;
	   		}
   		}else
   		{
   			$err = array('data' =>array('status' => '1', 'message' => 'Device Added'));
			echo json_encode($err); exit;
   		}
   }	
 
   

	public function doctors_list()
   	{	 
		$user_id  = $this->input->post('user_id');
		$this->check_login();
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		}
		// Get My Doctor
		
        $mydr = $this->Common_model->getRecords('users','dr_id',array('user_id'=>$user_id),'',true);

		$this->Common_model->addEditRecords('users',array('is_verified'=>1),array('user_id'=>$user_id));

        if(empty($mydr['dr_id'])){
        	$user_list = $this->Common_model->getRecords('users','user_id,profile_pic,payment_status,full_name,category_id',array('user_id!='=>$user_id,'user_type'=>'doctor','is_deleted'=>'0','status'=>'active'),'user_id DESC',false);
        }else{
        	 $user_list = $this->Common_model->getRecords('users','user_id,profile_pic,payment_status,full_name,category_id',array('user_id!='=>$user_id,'user_type'=>'doctor','is_deleted'=>'0','status'=>'active','user_id'=>$mydr['dr_id']),'user_id DESC',false);
        }


        $dr_list =array();
        if(!empty($user_list))
        {
        	foreach ($user_list as $key => $list) {
        		$dr_list[$key]= $list; 
        		
        		$category_id_data= $this->Common_model->getRecords('categories','name',array('status'=>'Active','category_id'=>$list['category_id']),'',true);
        		$dr_list[$key]['category_name']=$category_id_data['name'];

        		$dr_list[$key]['media_count']=(string)$this->Common_model->getNumRecords('media','id',array('user_id'=>$list['user_id']));
        		// echo $this->db->last_query();
        	}

			$response = array('data'=> array('status'=>'1','message'=>'list','doctor_list'=>$dr_list));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'message' => 'doctors not found !!'));
			echo json_encode($err); exit;
        }
			
	 	 
   }	
   	public function doctor_detail()
   	{	 
		$doctor_id  = $this->input->post('doctor_id');
		$user_id  = $this->input->post('user_id');
		$this->check_login();
		if(empty($doctor_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter doctor_id.'));
			echo json_encode($err); exit;
		}
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		}

        $doctor_detail = $this->Common_model->getRecords('users','*',array('user_id'=>$doctor_id,'user_type'=>'doctor','is_deleted'=>'0','status'=>'active'),'',true);
        $dr_list =array();
        if(!empty($doctor_detail))
        {
        		$doctor_detail= $this->Common_model->getdrdetailsRecords($doctor_id);
        		$doctor_detail['is_feedback']='0';
        			//0 = feeback de skta h 1 = kr diye 2 = nhi kr skta
        		// echo $this->db->last_query();die;
        		$get_room_id = $this->Common_model->checkblock($doctor_id,$user_id);

        		if($get_room_id){
        			$doctor_detail['is_blocked']='1';	
        		}else{
        			$doctor_detail['is_blocked']='0';	
        		}

				$media_list= $this->Common_model->getmedialist($doctor_id);  
				$media  = array();  
				// echo "<pre>";print_r($media_list);die;  
				if(!empty($media_list))
				{
					foreach ($media_list as $key => $list) {
 						 
 						 $media[$key] = $list;
 						 
 						 if($this->Common_model->getRecords('media_like','*',array('media_id'=>$list['id'],'user_id'=>$user_id),'',true))
 						 {
 						 	$media[$key]['is_liked']='1';
 						 }else
 						 {
 						 	$media[$key]['is_liked']='0';
 						 }
					}
				}  	       	 

			$response = array('data'=> array('status'=>'1','message'=>'list','doctor_detail'=>$doctor_detail,'media_list'=>$media));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'message' => 'doctors not found !!'));
			echo json_encode($err); exit;
        }	 
   }	

   	public function media_details()
   	{	 
		$media_id  = $this->input->post('media_id');
		$user_id  = $this->input->post('user_id');
		$this->check_login();
		if(empty($media_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter media_id.'));
			echo json_encode($err); exit;
		}
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		}
 
       		$media_details= $this->Common_model->getmediadetails($media_id);  
				$media  = array();  
				// echo "<pre>";print_r($media_list);die;  
				if(!empty($media_details))
				{
					 if($this->Common_model->getRecords('media_like','*',array('media_id'=>$media_id,'user_id'=>$user_id),'',true))
					 {
					 	$media_details['is_liked']='1';
					 }else
					 {
					 	$media_details['is_liked']='0';
					 }

					 $response = array('data'=> array('status'=>'1','message'=>'list','details'=>$media_details));
						echo json_encode($response); exit;
				}else
		        {
					$err = array('data' =>array('status' => '0', 'message' => 'media not found !!'));
					echo json_encode($err); exit;
		        }	   	       	 

   }	

   public function uploadMedia()
	{
	  	// $username			=	$this->test_input($this->input->post('username'));
	  	$media_type			=	$this->test_input($this->input->post('media_type'));
	  	$user_id			=	$this->test_input($this->input->post('user_id'));
	  	$title			=	$this->test_input($this->input->post('title'));
	  	$detail			=	$this->test_input($this->input->post('detail'));
	  	// $video_image			=	$this->test_input($this->input->post('video_image'));
	  
	  	$this->check_login();
		if(empty($media_type)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please select media type.'));
			echo json_encode($err); exit;
		}else
		{
			if($media_type!='video' && $media_type!='audio' && $media_type!='image')
			{
				$err = array('data' =>array('status' => '0', 'message' => 'User type must be video or audio or image.'));
				echo json_encode($err); exit;	
			}
		} 
		
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		}  
		if(empty($title)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter title.'));
			echo json_encode($err); exit;
		}  

	
	    $date= date('Y-m-d H:i:s');

	    if(!empty($_FILES['media']['name'])){
     //  echo "<pre>";print_r($_FILES);exit;
       		$newFileName = $_FILES['media']['name'];
            $fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
            $filename = uniqid(time()).".".$fileExt;
	        $config['upload_path'] = 'resources/media/';
	        $config['file_name'] = $filename;
			$config['allowed_types'] = '*';
            $this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('media')) 
			{
				$err = array('data' =>array('status' => '0', 'message' =>strip_tags($this->upload->display_errors())));
	            echo json_encode($err); exit; 		
			}
			else
			{
				$upload_data=$this->upload->data();	
            } 
        }else
        {
        		$err = array('data' =>array('status' => '0', 'message' => 'Please upload media.'));
				echo json_encode($err); exit;
        } 

        if(!empty($_FILES['video_image']['name'])){
     //  echo "<pre>";print_r($_FILES);exit;
       		$newFileName = $_FILES['video_image']['name'];
            $fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
            $filename = uniqid(time()).".".$fileExt;
	        $config['upload_path'] = 'resources/media/';
	        $config['file_name'] = $filename;
			$config['allowed_types'] = '*';
            $this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('video_image')) 
			{
				$err = array('data' =>array('status' => '0', 'message' =>strip_tags($this->upload->display_errors())));
	            echo json_encode($err); exit; 		
			}
			else
			{

				$video_image=$this->upload->data();	
				$video_image=	'resources/media/'.$video_image['file_name'];
            } 
        }else
        {
        	if($media_type=='video'){

        		$err = array('data' =>array('status' => '0', 'message' => 'Please upload video image.'));
				echo json_encode($err); exit;
			}else
			{
				$video_image='';
			}
        }

	    $insert_data = array( 
	    	'media_type'=>$media_type,
	    	'video_image'=>$video_image,
	    	'detail'=>$detail,
	    	'title'=>$title,
	    	'user_id'=>$user_id,
	    	'file' =>'resources/media/'.$upload_data['file_name'],
	    	'created'=>date('Y-m-d h:i:s'),
	    );
		
		if($media_id = $this->Common_model->addEditRecords('media',$insert_data)) {
	
				
					$current_user=$this->Common_model->getRecords('users','full_name,badge_count,device_id,device_type',array('user_id'=>$user_id),'',true);

		  
					      	$iosarray = array(
			                    'alert' => 'Media uploaded successfully.',
			                    'type'  => 'media_upload',
			                   	'media_id'=> $media_id,
			                   	'badge' => $current_user['badge_count']+1,
			                    'sound' => 'default',
			       			);

							$andarray = array(
				                'message'   => 'Media uploaded successfully.',
				               	'type'  => 'media_upload',
			                   	'media_id'=> $media_id,
				                'title'     => 'Media uploaded successfully.',
			            	);
							

					    		if($current_user['device_type']=='Android'){
									$referrer = androidNotification($current_user['device_id'],$andarray);
								}

					    		if($current_user['device_type']=='IOS'){
			                   		$referrer = iosNotification($current_user['device_id'],$iosarray);
					    		}
					    
				 
					    $add_data =array('user_id' => $user_id,'media_id'=>$media_id,'created_by' =>$user_id,'type'=>'media_upload', 'notification_title'=> 'Media uploaded successfully.', 'notification_description'=>  'Media uploaded successfully.','created'=>date('Y-m-d H:i:s'));
			    		$this->Common_model->addEditRecords('notifications',$add_data); 

					$response = array('data'=> array('status'=>'1','message'=>'Media uploaded successfully'));
				    echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'message' => 'Server not responding. Please try again !!'));
				echo json_encode($err); exit;
			}
	 
	}

	public function deleteMedia(){
		$media_id = $this->input->post('media_id'); 
		if(empty($media_id)){
            $err = array('data' =>array('status' => '0', 'message' => 'Please enter media id.'));
            echo json_encode($err); exit;
        }
		$this->Common_model->deleteRecords('media',array('id'=>$media_id));
			if($this->language=='english'){
					$response = array('data'=> array('status'=>'1','message'=>'Media deleted successfully'));
			}elseif ($this->language=='arabic') {
					$response = array('data'=> array('status'=>'1','message'=>'تم حذف الوسائط بنجاح'));
			}elseif ($this->language=='french') {
					$response = array('data'=> array('status'=>'1','message'=>'Media deleted successfully'));
			}
		echo json_encode($response); exit;
	}

   
   public function testingpost()
   {
		$this->chkServer('gateway.sandbox.push.apple.com',2195); 
			//chkServer('gateway.push.apple.com',2195); 

			


   }
 
 	function chkServer($host, $port) 
			{ 
					$hostip = @gethostbyname($host); 

					if ($hostip == $host) 
					{ 
					echo "Server is down or does not exist"; 
					} 
					else 
					{ 
					if (!$x = @fsockopen($hostip, $port, $errno, $errstr, 5)) 
					{ 
					echo "Port $port is closed."; 
					} 
					else 
					{ 
					echo "Port $port is open."; 
					if ($x) 
					{ 
					@fclose($x); 
					} 
					} 
				} 
			} 

	public function notificationstest()
	{
		$iosarray = array(
                'alert' => 'testing',
                'type'  => 'chat', 
                'sound' => 'default',
   			); 

		// echo $referrer = iosNotification('95E02F62BF5DFC0E025C243470604A415B50EADE314D21601ABBB209428551B3',$iosarray);

	echo	$referrer = androidNotification('f3oLzZzEloU:APA91bEze0PvFJ6zTyDipkBwehxVkf83j6W0Uf3-lh7-t2eiQrDgifm5FeUE9veIx8dfjRz-j8Mpq4Yt5wKvf7slF-q-ikXsiIi_3sB7SHtSWFHLS6Q2llXHYSfzjTxA1rrcEvBC7dH2','test notifications');

	}

  
	 
 

     public function getTagList() {
    	$this->check_login();
	  	$user_id		 =	$this->test_input($this->input->post('user_id'));
        $post_id		 =	$this->test_input($this->input->post('post_id'));
        $page_id		 =	$this->test_input($this->input->post('page_id'));
    	$result =array('');
    	$page_result =array('');
        $update_data     =  array('user_id !=' => $user_id);
    	$result = $this->Common_model->getRecords('users', 'user_id,full_name,username,profile_pic', $update_data,"user_id Desc", false);
    	$index1 = 0;
		$new_data1  = array();
    	foreach ($result as $data_result) {
    			$new_data1[$index1] = $data_result;
    			$new_data1[$index1]['is_page'] = 'no';

    			$index1++; 
    	}
    	$where_page     =  array('is_deleted' =>0);
    	$page_result = $this->Common_model->getRecords('business_page','business_page_id,business_full_name as full_name,business_name as username,business_image as profile_pic',$where_page,"business_page_id Desc", false);
    	$index = 0;
  		$new_data  = array();
    	foreach ($page_result as $data_new) {
    			$new_data[$index] = $data_new;
    			$new_data[$index]['is_page'] = 'yes';
    			$index++; 
    	}
    	$data['hashTagList']  = $this->Common_model->getRecords('tags','*','',"word ASC", false);


    	$data['userTagList']   = array_merge($new_data1,$new_data); 
    	
    	$response = array('data'=> array('status'=>'1','message'=>'users found successfully','details'=>$data));
		echo json_encode($response); exit;

	}

	public function paymentHistory() {
		$this->check_login();
  		$user_id =	$this->test_input($this->input->post('user_id'));
  		$page_id =	$this->test_input($this->input->post('page_id'));
	  	$where = array('type' => '4','user_id' => $user_id);
	  	$newarray = array();
  		$points  = $this->Common_model->getRecords('points_manage','created,amount,transaction_id',$where,"", false);
            $index=0;
		  if(!empty($points)){
			foreach ($points as $list) {
				$points[$index]['title'] = 'Points Purchased';
				 $index++;
			}}
		$paymentHistory = $this->App_model->paymentHistory($user_id,$page_id);
		  $index=0;
		  if(!empty($paymentHistory)){
			foreach ($paymentHistory as $get) {
				$paymentHistory[$index]['title'] = 'Subscription Purchsed';
				 $index++;
			}}

			  $where1 = array('page_id' =>$page_id,'user_id' => $user_id);

			$offer_purchase  = $this->Common_model->getRecords('offer_purchase','created,amount,transaction_id',$where1,"", false);
            $index=0;
		  if(!empty($offer_purchase)){
			foreach ($offer_purchase as $row) {
				$offer_purchase[$index]['title'] = 'Offer Purchased';
				 $index++;
			}}
		//$data = '';
		$data = array_merge($points,$paymentHistory,$offer_purchase);
		$alldata =array();
		if(!empty($data)){
			foreach ($data  as $key => $list) {
				$alldata[$list['created']] = $list;
				 
			}
		}

		krsort($alldata);

		unset($data);
		$data =array();
		if(!empty($alldata)){
			foreach ($alldata  as $key => $list) {
				$data[] = $list;
				 
			}
		}

		if($data){
			$response = array('data'=> array('status'=>'1','message'=>'Payment history','details'=>$data));
			echo json_encode($response); exit;
		}else{
			$err = array('data' =>array('status' => '0', 'message' => 'Payment history not found !!'));
			echo json_encode($err); exit;
		}


	}


	public function get_offer_plan()
	{  	$this->check_login(); 
		$points = $this->Common_model->getRecords('offer_plan','*','','',false);
	
		$response = array('data'=> array('status'=>'1','message'=>'Offer plan','details'=>$points ));
		echo json_encode($response);	
	}

  
  public function get_page()
	{
		$page_id	 =	$this->input->post('page_id');
		if(empty($page_id)){
				$err = array('data' =>array('status' => '0', 'message' => 'Please enter page id.'));
				echo json_encode($err); exit;
		}


		//$get_panding_list = $this->Common_model->getRecords('chating_request','id,sender_user',array('receiver_user' =>$user_id),'',false);
		$page_details=$this->Common_model->getRecords('pages','title,content',array('page_id'=>$page_id),'',true);
		if(!empty($page_details))
 		{
 		  $response =array('data'=> array('status'=>'1','message'=>'results','details'=>$page_details));
 		  echo json_encode($response); 
 		  exit;

 		} else {
			$response = array('data'=> array('status'=>'0','message'=>'Results not found.'));
			  echo json_encode($response); 
 		} 
	}


	 
 

	public function badge_count($id,$table,$list)
	{
		$where = array($list =>$id);

		$count=$this->Common_model->getRecords($table,'badge_count',$where,'',true); 
		$badge_count=$count['badge_count']+1;
		$update_data = array('badge_count' =>$badge_count);
        
        $this->Common_model->addEditRecords($table,$update_data,$where);
       
        return $badge_count;


	}

	public function get_room_id()
	{
		// $this->check_login();
	  	$sender_id		 =	$this->test_input($this->input->post('sender_id'));
	  	$receiver_id	 =	$this->test_input($this->input->post('receiver_id'));
	  	$type		     =	$this->test_input($this->input->post('type'));

		if(empty($sender_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter sender id.'));
			echo json_encode($err); exit;
		} 
		if(empty($receiver_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter receiver id.'));
			echo json_encode($err); exit;
		}  
		if(empty($type)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter type.'));
			echo json_encode($err); exit;
		} 
  
	 	$get_room_id = $this->Common_model->getRoomidCheck($sender_id,$receiver_id,$type);
		$suc = array('data' =>array('status' => '1', 'message' =>'Room Id ','room_id'=>$get_room_id));
        echo json_encode($suc); exit; 	  
	}
	
	public function user_chating()
	{
		$this->check_login();
	  	$sender_id		 =	$this->test_input($this->input->post('sender_id'));
	  	$receiver_id	 =	$this->test_input($this->input->post('receiver_id'));
	  	$message		 =	$this->test_input($this->input->post('message'));
	  	$type		     =	$this->test_input($this->input->post('type'));

		if(empty($sender_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter sender id.'));
			echo json_encode($err); exit;
		} 
		if(empty($receiver_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter receiver id.'));
			echo json_encode($err); exit;
		}  
		if(empty($type)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter type.'));
			echo json_encode($err); exit;
		} 
  
	 	$get_room_id = $this->Common_model->getRoomid($sender_id,$receiver_id,$type);
	 	 
		$update_data = array(
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,
            'room_id' => $get_room_id,
            'type' => $type,
          	'created' => date("Y-m-d H:i:s"),
		);
		$this->Common_model->addEditRecords('users_chat',$update_data); 


		// New Code

		$get_room_id = $this->Common_model->getRoomidid($sender_id,$receiver_id,'video');
	    	
	 	 	if(empty($get_room_id)){

				$get_room_id = $this->Common_model->getRoomid($sender_id,$receiver_id,'video');
				$update_data = array(
		            'sender_id' => $sender_id,
		            'receiver_id' => $receiver_id,
		            'message' =>'',
		            'room_id' => $get_room_id,
		            'type' =>'video',
		          	'created' => date("Y-m-d H:i:s"),
				);
				$this->Common_model->addEditRecords('users_chat',$update_data); 
			}

		$get_room_id = $this->Common_model->getRoomidid($sender_id,$receiver_id,'audio');
	    	
	 	 	if(empty($get_room_id)){

				$get_room_id = $this->Common_model->getRoomid($sender_id,$receiver_id,'audio');
				$update_data = array(
		            'sender_id' => $sender_id,
		            'receiver_id' => $receiver_id,
		            'message' =>'',
		            'room_id' => $get_room_id,
		            'type' =>'audio',
		          	'created' => date("Y-m-d H:i:s"),
				);
				$this->Common_model->addEditRecords('users_chat',$update_data); 
			}	
			 
		    $receiver_record=$this->Common_model->getRecords('users','device_type,device_id',array('user_id'=>$receiver_id),'',true);
		    $sender_record=$this->Common_model->getRecords('users','full_name',array('user_id'=>$sender_id),'',true);
			$iosarray = array(
                'alert' =>  substr($message,0,60),
                'type'  => 'chat', 
             	'user_name'=> $sender_record['full_name'], 
				'user_id' => $sender_id, 
				'room_id' => $get_room_id,
                'sound' => 'default',
   			); 
			$andarray = array(
                'message'   =>  $message,
                'type'  => 'chat', 
               	'user_name'=> $sender_record['full_name'],
               	'user_id' => $sender_id,
               	'room_id' => $get_room_id,
                'title'     => 'Notification',
        	);
			
		    if(!empty($receiver_record)){ 
				if(strtolower($receiver_record['device_type'])=='android'){
					$referrer = androidNotification($receiver_record['device_id'],$andarray);
				} 
	    		if(strtolower($receiver_record['device_type'])=='ios'){
               		$referrer = iosNotification($receiver_record['device_id'],$iosarray);
	    		} 
		    }  
		    
		    $sender_record=$this->Common_model->getRecords('users_chat','*',array('room_id'=>$get_room_id),'id Desc',true);

		    $r_p =$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$sender_record['sender_id']),'',true);
		    $sender_record['sender_name'] =$r_p['full_name'];
		    $sender_record['sender_image'] =$r_p['profile_pic'];
		        
		    $r_p =$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$sender_record['receiver_id']),'',true);
		    $sender_record['receiver_name'] =$r_p['full_name'];
		    $sender_record['receiver_image'] =$r_p['profile_pic'];
		        
			$suc = array('data' =>array('status' => '1', 'message' =>'Message send successfully', 'room_id'=>$get_room_id, 'detail'=>$sender_record));
            echo json_encode($suc); exit; 	  
	}

	public function reject_call()
	{
		$this->check_login();
	  	$sender_id      =	$this->test_input($this->input->post('sender_id'));
	  	$room_id	    =	$this->test_input($this->input->post('room_id'));
	  	$type		    =	$this->test_input($this->input->post('type'));

		if(empty($sender_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter sender id.'));
			echo json_encode($err); exit;
		} 
		if(empty($room_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter room id.'));
			echo json_encode($err); exit;
		}  
		if(empty($type)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter type.'));
			echo json_encode($err); exit;
		} 

        $reject_type = 'video_call_reject';
        if($type=='audio'){
            $reject_type = 'audio_call_reject';
		}
	
	    $receiver_record=$this->Common_model->getRecords('users','device_type,device_id,full_name',array('user_id'=>$sender_id),'',true);
		
		$iosarray = array(
            'alert' =>  $receiver_record['full_name'].' calling you',
            'type'  => $reject_type, 
         	'user_name'=> $receiver_record['full_name'], 
			'user_id' => $sender_id,
			'room_id' => $room_id,
            'sound' => 'default',
   		); 
		$andarray = array(
            'message'   =>  $receiver_record['full_name'].' calling you',
            'type'  => $reject_type, 
           	'user_name'=> $receiver_record['full_name'],
           	'user_id' => $sender_id,
           	'room_id' => $room_id,
            'title'     => 'Notification',
    	);

    	$update_data = array(
            'on_call' => 0,
          	'modified' => date("Y-m-d H:i:s"),
		);
	    
    	// 
    	$old_record=$this->Common_model->getRecords('users_chat','sender_id,receiver_id',array('room_id' => $room_id),'',true);

	    // $this->Common_model->addEditRecords('users_chat',$update_data, array('room_id' => $room_id,'type'=>$type)); 


		$this->Common_model->addEditRecords('users_chat',$update_data,array('sender_id'=>$old_record['sender_id'],'receiver_id'=>$old_record['receiver_id']));
		$this->Common_model->addEditRecords('users_chat',$update_data,array('sender_id'=>$old_record['receiver_id'],'receiver_id'=>$old_record['sender_id']));

		  
		
		if(!empty($receiver_record)){ 
				if(strtolower($receiver_record['device_type'])=='android'){
					$referrer = androidNotification($receiver_record['device_id'],$andarray);
				} 
	    		if(strtolower($receiver_record['device_type'])=='ios'){
               		$referrer = iosNotification($receiver_record['device_id'],$iosarray);
	    		} 
		    }  
		    
		$suc = array('data' =>array('status' => '1', 'message' =>'Call rejected successfully'));
        echo json_encode($suc); exit; 	  
	}

	public function end_call()
	{
		$this->check_login();
	  	$sender_id      =	$this->test_input($this->input->post('sender_id'));
	  	$receiver_id      =	$this->test_input($this->input->post('receiver_id'));
	  	$room_id	    =	$this->test_input($this->input->post('room_id'));
	  	$type		    =	$this->test_input($this->input->post('type'));

		if(empty($sender_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter sender id.'));
			echo json_encode($err); exit;
		} 
		if(empty($room_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter room id.'));
			echo json_encode($err); exit;
		}  
		if(empty($type)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter type.'));
			echo json_encode($err); exit;
		} 

        $reject_type = 'video_call_ended';
        if($type=='audio'){
            $reject_type = 'audio_call_ended';
		}
		/// Sender id
	    $receiver_record=$this->Common_model->getRecords('users','device_type,device_id,full_name',array('user_id'=>$sender_id),'',true);
		
		$iosarray = array(
            // 'alert' =>  $receiver_record['full_name'].' calling you',
            'alert' =>  'Call End',
            'type'  => $reject_type, 
         	'user_name'=> $receiver_record['full_name'], 
			'user_id' => $sender_id,
			'room_id' => $room_id,
            'sound' => 'default',
   		); 
		$andarray = array(
            // 'message'   =>  $receiver_record['full_name'].' calling you',
            'message'   =>  'Call End',
            'type'  => $reject_type, 
           	'user_name'=> $receiver_record['full_name'],
           	'user_id' => $sender_id,
           	'room_id' => $room_id,
            'title'     => 'Notification',
    	);
		
		if(!empty($receiver_record)){ 
			if(strtolower($receiver_record['device_type'])=='android'){
				$referrer = androidNotification($receiver_record['device_id'],$andarray);
			} 
    		if(strtolower($receiver_record['device_type'])=='ios'){
           		$referrer = iosNotification($receiver_record['device_id'],$iosarray);
    		} 
	    }  


	   	//Recever id 

	   	if(!empty($receiver_id)){

		    $receiver_record=$this->Common_model->getRecords('users','device_type,device_id,full_name',array('user_id'=>$receiver_id),'',true);
			
			$iosarray = array(
	            // 'alert' =>  $receiver_record['full_name'].' calling you',
	            'alert' =>  'Call End',
	            'type'  => $reject_type, 
	         	'user_name'=> $receiver_record['full_name'], 
				'user_id' => $sender_id,
				'room_id' => $room_id,
	            'sound' => 'default',
	   		); 
			$andarray = array(
	            // 'message'   =>  $receiver_record['full_name'].' calling you',
	            'message'   =>  'Call End',
	            'type'  => $reject_type, 
	           	'user_name'=> $receiver_record['full_name'],
	           	'user_id' => $sender_id,
	           	'room_id' => $room_id,
	            'title'     => 'Notification',
	    	);
			
			if(!empty($receiver_record)){ 
				if(strtolower($receiver_record['device_type'])=='android'){
					$referrer = androidNotification($receiver_record['device_id'],$andarray);
				} 
	    		if(strtolower($receiver_record['device_type'])=='ios'){
	           		$referrer = iosNotification($receiver_record['device_id'],$iosarray);
	    		} 
		    }  
	   	}
 
 
		    
		$update_data = array(
            'on_call' => 0,
          	'modified' => date("Y-m-d H:i:s"),
		); 


		$old_record=$this->Common_model->getRecords('users_chat','sender_id,receiver_id',array('room_id' => $room_id),'',true);

	    // $this->Common_model->addEditRecords('users_chat',$update_data, array('room_id' => $room_id,'type'=>$type)); 


		$this->Common_model->addEditRecords('users_chat',$update_data,array('sender_id'=>$old_record['sender_id'],'receiver_id'=>$old_record['receiver_id']));
		$this->Common_model->addEditRecords('users_chat',$update_data,array('sender_id'=>$old_record['receiver_id'],'receiver_id'=>$old_record['sender_id']));


	    // $this->Common_model->addEditRecords('users_chat',$update_data, array('room_id' => $room_id)); 
		    // die;
		$suc = array('data' =>array('status' => '1', 'message' =>'Call ended successfully'));
        echo json_encode($suc); exit; 	  
	}
	
    public function call_check()
	{
		$this->check_login();
	  	$sender_id		 =	$this->test_input($this->input->post('sender_id'));
	  	$receiver_id	 =	$this->test_input($this->input->post('receiver_id'));
	  	$type		     =	$this->test_input($this->input->post('type'));
	  	$repeat		     =	$this->test_input($this->input->post('repeat'));

		if(empty($sender_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter sender id.'));
			echo json_encode($err); exit;
		} 
		if(empty($receiver_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter receiver id.'));
			echo json_encode($err); exit;
		}  
		if(empty($type)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter type.'));
			echo json_encode($err); exit;
		} 
   
	/*	if(empty($message)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter message.'));
			echo json_encode($err); exit;
		} */
			
		$check_already_on_call = $this->Common_model->checkAlreadyCall($receiver_id,$type);
		// echo $this->db->last_query();die;
		if(!empty($check_already_on_call)){

			$user_details=$this->Common_model->getRecords('users','full_name',array('user_id'=>$receiver_id),'',true);
	
			if(!empty($type)){

				if($this->language=='english'){
					$err = array('data' =>array('status' => '0', 'message' => 'User on other call.'));
				}elseif ($this->language=='arabic') {
					$err = array('data' =>array('status' => '0', 'message' => 'المستخدم مختلف'));

				}elseif ($this->language=='french') {
					$err = array('data' =>array('status' => '0', 'message' => 'L\'utilisateur est dans un autre appel'));

				}

				// $err = array('data' =>array('status' => '0', 'message' => $user_details['full_name'].' on other call.'));


				echo json_encode($err); exit;
			} 	
		}

	 	$get_room_id = $this->Common_model->getRoomid($sender_id,$receiver_id,$type);
	 	 // echo $this->db->last_query();die;
		$update_data = array(
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id, 
            'room_id' => $get_room_id,
            'type' => $type,
            'on_call' => 1,
          	'created' => date("Y-m-d H:i:s"),
		);
		if (empty($repeat)){

	    	$this->Common_model->addEditRecords('users_chat',$update_data); 

	    	// New Code

	    	$get_room_id = $this->Common_model->getRoomidid($sender_id,$receiver_id,'chat');
	    	
	 	 	if(empty($get_room_id)){

				$get_room_id = $this->Common_model->getRoomid($sender_id,$receiver_id,'chat');
				$update_data = array(
		            'sender_id' => $sender_id,
		            'receiver_id' => $receiver_id,
		            'message' =>'',
		            'room_id' => $get_room_id,
		            'type' =>'chat',
		          	'created' => date("Y-m-d H:i:s"),
				);
				$this->Common_model->addEditRecords('users_chat',$update_data); 
			}

			if($type=='audio'){
				$get_room_id = $this->Common_model->getRoomidid($sender_id,$receiver_id,'video');
	    	
		 	 	if(empty($get_room_id)){

					$get_room_id = $this->Common_model->getRoomid($sender_id,$receiver_id,'video');
					$update_data = array(
			            'sender_id' => $sender_id,
			            'receiver_id' => $receiver_id,
			            'message' =>'',
			            'room_id' => $get_room_id,
			            'type' =>'video',
			          	'created' => date("Y-m-d H:i:s"),
					);
					$this->Common_model->addEditRecords('users_chat',$update_data); 
				}
			}else{
					$get_room_id = $this->Common_model->getRoomidid($sender_id,$receiver_id,'audio');
		    	
			 	 	if(empty($get_room_id)){

						$get_room_id = $this->Common_model->getRoomid($sender_id,$receiver_id,'audio');
						$update_data = array(
				            'sender_id' => $sender_id,
				            'receiver_id' => $receiver_id,
				            'message' =>'',
				            'room_id' => $get_room_id,
				            'type' =>'audio',
				          	'created' => date("Y-m-d H:i:s"),
						);
						$this->Common_model->addEditRecords('users_chat',$update_data); 
					}
			}



		}
		    $receiver_record=$this->Common_model->getRecords('users','device_type,device_id',array('user_id'=>$receiver_id),'',true);
		    $sender_record=$this->Common_model->getRecords('users','full_name',array('user_id'=>$sender_id),'',true);
			$iosarray = array(
                'alert' =>  $sender_record['full_name'].' calling you',
                'type'  => $type, 
             	'user_name'=> $sender_record['full_name'], 
				'user_id' => $sender_id,
				'room_id' => $get_room_id,
                'sound' => 'default',
   			); 
			$andarray = array(
                'message'   =>  $sender_record['full_name'].' calling you',
                'type'  => $type, 
               	'user_name'=> $sender_record['full_name'],
               	'user_id' => $sender_id,
               	'room_id' => $get_room_id,
                'title'     => 'Notification',
        	);
			
		    if(!empty($receiver_record)){ 
				if(strtolower($receiver_record['device_type'])=='android'){
					$referrer = androidNotification($receiver_record['device_id'],$andarray);
				} 
	    		if(strtolower($receiver_record['device_type'])=='ios'){
               		$referrer = iosNotification($receiver_record['device_id'],$iosarray);
	    		} 
		    }  
		    
		    $sender_record=$this->Common_model->getRecords('users_chat','*',array('room_id'=>$get_room_id),'id Desc',true);

		    $r_p =$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$sender_record['sender_id']),'',true);
		    $sender_record['sender_name'] =$r_p['full_name'];
		    $sender_record['sender_image'] =$r_p['profile_pic'];
		        
		    $r_p =$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$sender_record['receiver_id']),'',true);
		    $sender_record['receiver_name'] =$r_p['full_name'];
		    $sender_record['receiver_image'] =$r_p['profile_pic'];
		        
			$suc = array('data' =>array('status' => '1', 'message' =>'Message send successfully', 'room_id'=>$get_room_id, 'detail'=>$sender_record));
            echo json_encode($suc); exit; 	  
	}



	public function chat_detail()
	{
		$this->check_login();
	  	$room_id		 =	$this->test_input($this->input->post('room_id'));
	 
		if(empty($room_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter room id.'));
			echo json_encode($err); exit;
		} 
		$chat_record=$this->Common_model->getRecords('users_chat','*',array('room_id'=>$room_id,'type'=>'chat','message!='=>''),'',false);	
		//$neww ='';
		if(!empty($chat_record))
		{
		    foreach($chat_record as $key => $list)
		    {
		        $neww[$key]=$list;
		        $r_p =$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$list['sender_id']),'',true);
		        $neww[$key]['sender_name'] =$r_p['full_name'];
		        $neww[$key]['sender_image'] =$r_p['profile_pic'];
		        
		        $r_p =$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$list['receiver_id']),'',true);
		        $neww[$key]['receiver_name'] =$r_p['full_name'];
		        $neww[$key]['receiver_image'] =$r_p['profile_pic'];
		    }
		    
		    $suc = array('data' =>array('status' => '1','detail'=>$neww));
            echo json_encode($suc); exit; 
		}else
		{
		     $suc = array('data' =>array('status' => '0','detail'=>'No record found'));
            echo json_encode($suc); exit; 
		    
		}
			  
	}


	public function chattinglist()
	{
		$user_id =	$this->test_input($this->input->post('user_id'));

	  	if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'message' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		} 
		$this->check_login();
		$array = array();
		$receiver_array = array();
		if($records=$this->Common_model->getchatlist($user_id)) {
		   // echo "<pre>";print_r($records);
		    foreach ($records as $key => $list) {

		    	if($list['sender_id']==$user_id){
		    		if(!in_array($list['receiver_id'],$receiver_array)){
		    			$receiver_array[] = $list['receiver_id'];
		    		}
		    		 $second_user_record=$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$list['receiver_id']),'',true);
			 		 if($list['type']=='video'){
		    			$array[$list['receiver_id']]['video_room_id'] =$list['room_id'];
		    			if(empty($array[$list['receiver_id']]['audio_room_id'])){
		    				$array[$list['receiver_id']]['audio_room_id']='';
		    			}
		    			if(empty($array[$list['receiver_id']]['chat_room_id'])){
		    				$array[$list['receiver_id']]['chat_room_id']='';
		    			}
		    		 }elseif($list['type']=='audio'){
		    		 	$array[$list['receiver_id']]['audio_room_id'] =$list['room_id'];	
		    		 	if(empty($array[$list['receiver_id']]['video_room_id'])){
		    				$array[$list['receiver_id']]['video_room_id']='';
		    			}
		    			if(empty($array[$list['receiver_id']]['chat_room_id'])){
		    				$array[$list['receiver_id']]['chat_room_id']='';
		    			}
		    		 }elseif($list['type']=='chat'){
		    		 	$array[$list['receiver_id']]['chat_room_id'] =$list['room_id'];
		    		 	if(empty($array[$list['receiver_id']]['video_room_id'])){
		    				$array[$list['receiver_id']]['video_room_id']='';
		    			}
		    			if(empty($array[$list['receiver_id']]['audio_room_id'])){
		    				$array[$list['receiver_id']]['audio_room_id']='';
		    			}	
		    		 }
			 		$array[$list['receiver_id']]['user_id'] =$list['receiver_id'];
			 		$array[$list['receiver_id']]['profile_pic'] = $second_user_record['profile_pic'];
			 		$array[$list['receiver_id']]['full_name'] = $second_user_record['full_name'];
			 		$array[$list['receiver_id']]['message'] = $list['message'];
			 		$array[$list['receiver_id']]['is_blocked'] = $list['is_blocked'];
		    	
		    	}else{
		    		if(!in_array($list['sender_id'],$receiver_array)){
		    			$receiver_array[] = $list['sender_id'];
		    		}
		    		 $second_user_record=$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$list['sender_id']),'',true);
		    		 if($list['type']=='video'){
		    			$array[$list['sender_id']]['video_room_id'] =$list['room_id'];
		    			if(empty($array[$list['sender_id']]['audio_room_id'])){
		    				$array[$list['sender_id']]['audio_room_id']='';
		    			}
		    			if(empty($array[$list['sender_id']]['chat_room_id'])){
		    				$array[$list['sender_id']]['chat_room_id']='';
		    			}
		    		 }elseif($list['type']=='audio'){
		    		 	$array[$list['sender_id']]['audio_room_id'] =$list['room_id'];	
		    		 	if(empty($array[$list['sender_id']]['video_room_id'])){
		    				$array[$list['sender_id']]['video_room_id']='';
		    			}
		    			if(empty($array[$list['sender_id']]['chat_room_id'])){
		    				$array[$list['sender_id']]['chat_room_id']='';
		    			}
		    		 }elseif($list['type']=='chat'){
		    		 	$array[$list['sender_id']]['chat_room_id'] =$list['room_id'];
		    		 	if(empty($array[$list['sender_id']]['video_room_id'])){
		    				$array[$list['sender_id']]['video_room_id']='';
		    			}
		    			if(empty($array[$list['sender_id']]['audio_room_id'])){
		    				$array[$list['sender_id']]['audio_room_id']='';
		    			}	
		    		 }
			 		$array[$list['sender_id']]['user_id'] =$list['sender_id'];
			 		$array[$list['sender_id']]['profile_pic'] = $second_user_record['profile_pic'];
			 		$array[$list['sender_id']]['full_name'] = $second_user_record['full_name'];
			 		$array[$list['sender_id']]['message'] = $list['message'];
			 		$array[$list['sender_id']]['is_blocked'] = $list['is_blocked'];
		    	}
	    	}
	    
	    	if(!empty($array)){
	    		$new_array = array();
	    		$index = 0;
	    		foreach ($array as $value) {
	    			$new_array[] = $value;
	    		}
	    	}else{
	    		$new_array =array();
	    	}

			$suc = array('data' =>array('status' => '1', 'message' =>'message', 'details'=>$new_array));
            echo json_encode($suc); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'message' => 'No Data Found'));
            echo json_encode($err); exit;
		} 


	}

 	public function account_information(){

 		 $account_info = $this->Common_model->getRecords('admin','acount_info','','',true);
 		 $suc = array('data' =>array('status' => '1', 'account_info' =>$account_info['acount_info']));
          echo json_encode($suc); exit;
 	}
 
 

    function testing_notification(){
      	$iosarray = array(
            'alert' =>' liked your media.',
            'type'  => 'media_type',
           	'media_id'=>'',
           	'badge' => '',
            'sound' => 'default',
            'user_id' =>'',
			);

      		echo $referrer = iosNotification('95E02F62BF5DFC0E025C243470604A415B50EADE314D21601ABBB209428551B3',$iosarray);

	// echo	$referrer = androidNotification('f3oLzZzEloU:APA91bEze0PvFJ6zTyDipkBwehxVkf83j6W0Uf3-lh7-t2eiQrDgifm5FeUE9veIx8dfjRz-j8Mpq4Yt5wKvf7slF-q-ikXsiIi_3sB7SHtSWFHLS6Q2llXHYSfzjTxA1rrcEvBC7dH2',$iosarray);

 //       echo		 $referrer = iosNotification('90DD1288722CA0E08A5751BCBF000655C154D1D9E82517707A7975B518DD3845',$iosarray);
    }


    function sendotp($to_number){
 
		$sid    = SK_KEY;
		$token  = TOKEN;
		$twilio = new Client($sid, $token);
		$code = rand(1111,9999);
		$message = $twilio->messages
		                  ->create($to_number, // to
		                           [
		                               "body" => "Your Varfication code is : ".$code,
		                               "from" => FROM_NUMBER
		                           ]
		                  );

		return $code;

	}

	 function test_sendotp(){
 		$to_number = '+919827567489';
		$sid    = SK_KEY;
		$token  = TOKEN;
		$twilio = new Client($sid, $token);
		$code = rand(1111,9999);
		$message = $twilio->messages
		                  ->create($to_number, // to
		                           [
		                               "body" => "Your Varfication code is : ".$code,
		                               "from" => FROM_NUMBER
		                           ]
		                  );

		return $code;exit;

	}

	public function resend_code() 
	{
		$tableName="users";	
		
        $user_id   =	$this->test_input($this->input->post('user_id'));
       
		if(empty($user_id )) {
			$err = array('data'=> array('status'=>'0','message'=>'Please enter user_id.'));
			echo json_encode($err);exit;
		}
			$where = array('user_id' => $user_id);

			$res=$this->Common_model->getRecords($tableName,'token,user_id,is_verified,mobile',$where,'',true);

		    if(!empty($res))  {
			    	
		    	if($res['is_verified']==0){
		    		$code = $this->sendotp($res['mobile']);
		    		if(!empty($code)){

		    			if($this->Common_model->addEditRecords('users',array('token'=>$code),array('user_id'=>$res['user_id']))) {
		    				$err = array('data'=> array('status'=>'2','message'=>'Verification code has been sent to your phone number.')) ;
							echo json_encode($err);
							exit;
		    			}
		    		}
		    	}
	    		
	    	}else{
	    		$err = array('data'=> array('status'=>'2','message'=>'Already Verified.')) ;
				echo json_encode($err);
	    	}
	} 

}


 