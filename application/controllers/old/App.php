<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once(APPPATH.'libraries/Stripe/init.php');
// require_once(APPPATH.'libraries/Stripe/lib/Stripe.php');
class App extends CI_Controller {
	
	function __construct() {
        parent::__construct();
        error_reporting(0);
        //  ini_set('display_errors', TRUE);
        $this->load->helper(array('email','common_helper'));
        $this->load->library(array('form_validation'));
        $this->load->model('App_model');
        $this->load->model('admin/Common_model');
        $this->count=0;
        
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
			echo json_encode(array('data'=> array('status' =>'0' ,'msg'=>"Error Invalid Api Key")));
			header('HTTP/1.0 401 Unauthorized');
			die;
		}
    }

 
	public function testing()
	{
		 $username = $this->input->post('username');
	   if(preg_match('/^(_?[a-zA-Z0-9]+.?[a-zA-Z0-9_]+[A-Z-z0-9]+)+$/i',$username))
		{  			
		 
		  if(preg_match('/[^a-z_\-0-9.]/i',$username))
			{
				$err = array('data'=> array('status'=>'0','msg'=>'Please use combination of Alphabets or underscore or point '.' to enter username.'));
				echo json_encode($err);exit; 
			} 



		}else
		{
			$err = array('data'=> array('status'=>'0','msg'=>'Please use combination of Alphabets or underscore or point '.' to enter username.'));
			echo json_encode($err);exit;  
		}
		
		   
	}
 
	public function valid_username($username)
	{
		 
	   if(preg_match('/^(_?[a-zA-Z0-9]+.?[a-zA-Z0-9_]+[A-Z-z0-9]+)+$/i',$username))
		{  		
			 if(preg_match('/[^a-z_\-0-9.]/i',$username))
			{
				$err = array('data'=> array('status'=>'0','msg'=>'Please use combination of Alphabets or underscore or point '.' to enter username.'));
				echo json_encode($err);exit; 
			} 	
		 
		}else
		{
			$err = array('data'=> array('status'=>'0','msg'=>'Please use combination of Alphabets or underscore or point '.' to enter username.'));
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
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter the User Id.'));
			echo json_encode($err);exit;
		}
		if(empty($mobile_auth_token )) {
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter mobile_auth_token.'));
			echo json_encode($err);exit;
		}
		$where = array('user_id' => $user_id);

		$resuser=$this->Common_model->getRecords('users','*',$where,'',true);

		if(empty($resuser))
    	{
    		$err = array('data'=> array('status'=>'4','msg'=>'Oops! Logged in is not found. Please if you can try Logging again.'));
			echo json_encode($err);
			exit;
    	}

    	if($resuser['is_deleted']=='1')
    	{
    		$err = array('data'=> array('status'=>'4','msg'=>'Sorry, Logged in user is deleted. Please try Logging again.'));
			echo json_encode($err);
			exit;
    	}

    	if($resuser['mobile_auth_token']!=$mobile_auth_token)
    	{
    		$err = array('data'=> array('status'=>'4','msg'=>'You are logged in other devices that\'s you logout from here.'));
			echo json_encode($err);
			exit;
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
				$err = array('data'=> array('status'=>'0','msg'=>'Please enter device type!'));
				echo json_encode($err);
				exit;
			}else if($device_type !='Android' && $device_type !='IOS' ){
			$err = array('data' =>array('status' => '0', 'msg' => 'Device type must be either Android or IOS'));
			echo json_encode($err); exit;
		    }

			$tableName="users"; 
		 
		    if(empty($email))
			{
				$err = array('data'=> array('status'=>'0','msg'=>'Please enter the email.'));
				echo json_encode($err);
				exit;
			}

			if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter a valid email.'));
				echo json_encode($err); exit;
			}

			if(empty($password))
			{
				$err = array('data'=> array('status'=>'0','msg'=>'Please enter the Password.'));
				echo json_encode($err);
				exit;
			}

			
		   if(empty($device_type))
			{
				$err = array('data'=> array('status'=>'0','msg'=>'Please enter device type!'));
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
		    		$err = array('data'=> array('status'=>'0','msg'=>'Your profile is Inactive, Please contact us.'));
					echo json_encode($err);
					exit;
		    	}

		    	$where=array('user_id'=>$res['user_id']);
		    	$date = date('Y-m-d H:i:s');
		    	$mobile_auth_token = base64_encode(rand());
	          	$update_data = array('device_id'=>$device_id,'device_type'=>$device_type,'created'=>$date,'mobile_auth_token'=>$mobile_auth_token);
				if($resdevice=$this->Common_model->addEditRecords('users',$update_data,array('user_id'=>$res['user_id'],))) {
					$res=$this->Common_model->getRecords($tableName,'*',$where,'',true);				
					$response = array('data'=> array('status'=>'1','msg'=>'Login Successfully','details'=>$res));
				  	echo json_encode($response);
				  	exit;	
			    } else {
			    	$err = array('data' =>array('status' => '0', 'msg' => 'Some error occured Please try again !!'));
					echo json_encode($err);
					exit;
			    }
			}
			else
			{
				$err = array('data' =>array('status' => '0', 'msg' => 'Incorrect email or password'));
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
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter user id '));
			echo json_encode($err);
			exit;
		}
		if(empty($device_id))
		{
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter device id '));
			echo json_encode($err);
			exit;
		}
       
     	$response = array('data'=> array('status'=>'4','msg'=>'Logout successful.'));
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
	  	$country_id			=	$this->test_input($this->input->post('country_id'));
	  	$state_id			=	$this->test_input($this->input->post('state_id'));
	  	$city_id			=	$this->test_input($this->input->post('city_id'));
	  	$mobile			=	$this->test_input($this->input->post('mobile'));
	  	$address			=	$this->test_input($this->input->post('address'));
	  	$latitude			=	$this->test_input($this->input->post('latitude'));
	  	$longitude			=	$this->test_input($this->input->post('longitude'));
	  	$about			=	$this->test_input($this->input->post('about'));
	  	$device_id			=	$this->test_input($this->input->post('device_id'));
	  	$device_type		=	$this->input->post('device_type');
	  	$category_id		=	$this->input->post('category_id');


		if(empty($user_type)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please select user type.'));
			echo json_encode($err); exit;
		}else
		{
			if($user_type!='doctor' && $user_type!='patient')
			{
				$err = array('data' =>array('status' => '0', 'msg' => 'User type must be doctor or patient.'));
				echo json_encode($err); exit;	
			}elseif($user_type=='doctor' && empty($category_id))
			{
				$err = array('data' =>array('status' => '0', 'msg' => 'Please select category.'));
				echo json_encode($err); exit;
			}
		} 

		if($user_type=='patient')
		{
			$category_id='';
		}

		if(empty($full_name)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter full_name.'));
			echo json_encode($err); exit;
		} 
		
		if(empty($email)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter your email.'));
			echo json_encode($err); exit;
		} 

		if(empty($country_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter country.'));
			echo json_encode($err); exit;
		} 

		if(empty($state_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter state.'));
			echo json_encode($err); exit;
		} 

		if(empty($city_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter city.'));
			echo json_encode($err); exit;
		} 
		if(empty($mobile)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter mobile.'));
			echo json_encode($err); exit;
		} 

		if(empty($latitude)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter latitude.'));
			echo json_encode($err); exit;
		}
		if(empty($longitude)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter longitude.'));
			echo json_encode($err); exit;
		}  

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter a valid email.'));
			echo json_encode($err); exit;
		}
        $where1 = array('email' => $email);
		if($this->Common_model->getRecords('users','user_id',$where1,'',true)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'An account already exist with similar Email ID. Please try login with another.'));
			echo json_encode($err); exit;
		} 
 
		if(empty($password)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter your password.'));
			echo json_encode($err); exit;
		} 
 
		
		if(empty($device_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter device id'));
			echo json_encode($err); exit;
		} 
		if(empty($device_type)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter device type'));
			echo json_encode($err); exit;
		} 
		else if($device_type !='Android' && $device_type !='IOS' ){
			$err = array('data' =>array('status' => '0', 'msg' => 'Device type must be either Andriod or IOS'));
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
				$err = array('data' =>array('status' => '0', 'msg' =>strip_tags($this->upload->display_errors())));
	            echo json_encode($err); exit; 		
			}
			else
			{
				$upload_data=$this->upload->data();
				
            } 
        }


	    $insert_data = array( 
	    	'user_type'=>$user_type,
	    	'full_name'=>$full_name,
	    	'email' => $email, 
	    	'password' => $password,
	    	'country_id' => $country_id,
	    	'state_id' => $state_id,
	    	'city_id' => $city_id,
	    	'mobile' => $mobile,
	    	'profile_pic' =>'resources/images/profile/'.$upload_data['file_name'],
	    	'address' => $address,
	    	'latitude' => $latitude,
	    	'longitude' => $longitude,
	    	'about' => $about,
	    	'device_id'=>$device_id,
	    	'category_id'=>$category_id,
	    	'device_type'=>$device_type, 
	    	'created'=>date('Y-m-d h:i:s'),
	    );
		
		if($user_id = $this->Common_model->addEditRecords('users',$insert_data)) {
	
				//send welcome email to user
	  
				$data['message'] = 'Hello '.$full_name.'<br>'.'You have been registered successfully.';

				$subject = SITE_TITLE.': Registered successfully';
				$data['username']= $full_name;
				$body = $this->load->view('template/common', $data,TRUE);
				$to_email = $email;
				$from_email = getAdminEmail(); 
				$this->Common_model->setMailConfig();
				$this->Common_model->sendEmail($to_email,$subject,$body,$from_email);

				$user_data = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id),'',true);
				 
					$response = array('data'=> array('status'=>'1','msg'=>'Signup Successfully','details'=>$user_data));
				    echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding. Please try again !!'));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter full_name.'));
			echo json_encode($err); exit;
		} 
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		} 

		if($user_type!='doctor' && $user_type!='patient')
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'User type must be doctor or patient.'));
			echo json_encode($err); exit;	
		}elseif($user_type=='doctor' && empty($category_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please select category.'));
			echo json_encode($err); exit;
		}
		
		if(empty($email)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter your email.'));
			echo json_encode($err); exit;
		} 

		if(empty($country_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter country.'));
			echo json_encode($err); exit;
		} 

		if(empty($state_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter state.'));
			echo json_encode($err); exit;
		} 

		if(empty($city_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter city.'));
			echo json_encode($err); exit;
		} 
		if(empty($mobile)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter mobile.'));
			echo json_encode($err); exit;
		} 

		if(empty($latitude)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter latitude.'));
			echo json_encode($err); exit;
		}
		if(empty($longitude)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter longitude.'));
			echo json_encode($err); exit;
		}  

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter a valid email.'));
			echo json_encode($err); exit;
		}
        $where1 = array('email' => $email,'user_id!='=>$user_id);
		if($this->Common_model->getRecords('users','user_id',$where1,'',true)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'An account already exist with similar Email ID. Please try login with another.'));
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
				$err = array('data' =>array('status' => '0', 'msg' =>strip_tags($this->upload->display_errors())));
	            echo json_encode($err); exit; 		
			}
			else
			{
				$upload_data=$this->upload->data();
				
            } 
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
	    	'profile_pic' =>'resources/images/profile/'.$upload_data['file_name'],
	    	'address' => $address,
	    	'latitude' => $latitude,
	    	'longitude' => $longitude,
	    	'about' => $about,  
	    	'modified'=>date('Y-m-d h:i:s'),
	    );
		
		if($this->Common_model->addEditRecords('users',$insert_data,array('user_id'=>$user_id))) {
	
				//send welcome email to use
				$user_data = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id),'',true);
				 
					$response = array('data'=> array('status'=>'1','msg'=>'Profile Update Successfully','details'=>$user_data));
				    echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding. Please try again !!'));
				echo json_encode($err); exit;
			}
	 
	}
 
	public function get_countries(){
	
		$data['countries'] = array();
		if($data['countries']=getCountriesList()) {
			$response = array('data'=> array('status'=>'1','msg'=>'Country list found' ,'details'=>$data['countries']));
		} else {
			$response = array('data'=> array('status'=>'0','msg'=>'Countries not found.'));
		}
		echo json_encode($response);  exit;

	}

	public function get_states(){
		// $this->check_login();
        $country_id	=	$this->test_input($this->input->post('country_id'));

        if(empty($country_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter country id.'));
			echo json_encode($err); exit;
		}

		$data['states'] = array();
		if($data['states']=getStatesList($country_id)) {
			$response = array('data'=> array('status'=>'1','msg'=>'State found' ,'details'=>$data['states']));
		} else {
			$response = array('data'=> array('status'=>'0','msg'=>'states not found.'));
		}
		echo json_encode($response);  exit;

	}

	public function get_cities(){
		// $this->check_login();
        $state_id	=	$this->test_input($this->input->post('state_id'));

        if(empty($state_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter state id.'));
			echo json_encode($err); exit;
		}

		$data['cities'] = array();
		if($data['cities']=getCitiesList($state_id)) {
			$response = array('data'=> array('status'=>'1','msg'=>'City found' ,'details'=>$data['cities']));
		} else {
			$response = array('data'=> array('status'=>'0','msg'=>'Cities not found.'));
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
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter username.'));
	// 		echo json_encode($err); exit;
	// 	}else
	// 	{
	// 		$this->valid_username($username);
	// 	}

	// 	if(empty($full_name)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter username.'));
	// 		echo json_encode($err); exit;
	// 	} 
		
	// 	if(empty($email)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter your email.'));
	// 		echo json_encode($err); exit;
	// 	} 

	// 	if(empty($user_id)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter your user id.'));
	// 		echo json_encode($err); exit;
	// 	} 

	// 	if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter a valid email.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	if(user_username($user_id,$username)=='1') {
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Username already used.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	$tableName1="business_page";
	// 	$where = array('business_name' => $username);
	// 	if($this->Common_model->getRecords($tableName1,'business_page_id',$where,'',true)) {
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Username already used.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	if(user_email($user_id,$email)=='1') {
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'An account already exist with similar Email ID. Please try login with another.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	if(firebase_email($user_id,$email)=='1') {
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'An account already exist with similar Email ID. Please try login with another.'));
	// 		echo json_encode($err); exit;
	// 	}

	// 	if(!empty($mobile)) {
	// 		if(user_mobile($user_id,$mobile)=='1') {
	// 			$err = array('data' =>array('status' => '0', 'msg' => 'mobile already used.'));
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
	// 			$err = array('data' =>array('status' => '0', 'msg' =>strip_tags($this->upload->display_errors())));
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
	// 	    $err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
 //            echo json_encode($err); exit;
	// 	} else {
	// 		$getPost =  	$this->App_model->getProfile($user_id);
 //        	$interested =  	$this->App_model->user_interested($user_id);
	// 		$suc = array('data' =>array('status' => '1', 'msg' => 'Profile updated successfully.','details'=>$getPost,'interested'=>$interested));
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
        	$response = array('data'=> array('status'=>'1','msg'=>'Profile','details'=>$data));
			echo json_encode($response); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'msg' => 'Record not found.'));
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
			$response = array('data'=> array('status'=>'1','msg'=>'PostList','details'=>$getPost));
			echo json_encode($response); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'msg' => 'Result not found'));
			echo json_encode($err); exit;	
		}
 
	}
 
    /*=============================editProfile and View===========================================*/
 

    public function notification() {
       $this->check_login();
       $notification	=	$this->test_input($this->input->post('notification'));
       $user_id			=	$this->test_input($this->input->post('user_id')); 

       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter your user id.'));
			echo json_encode($err); exit;
		} 

       if(empty($notification)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Notification Type.'));
			echo json_encode($err); exit;
		} else if($notification != 'Yes' && $notification != 'No') {
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter exact Notification Type.'));
			echo json_encode($err); exit;
		}

		$update_data = array(
            'notification' => $this->input->post('notification'),
            'modified' => date("Y-m-d H:i:s"),
	    );
		if(!$this->Common_model->addEditRecords('users', $update_data,array('user_id'=>$user_id))) {
		    $err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
            echo json_encode($err); exit;
		} else {
			$suc = array('data' =>array('status' => '1', 'msg' => 'Notification updated successfully.'));
            echo json_encode($suc); exit;
		} 
    }


    public function advNotification() {
       $this->check_login();
       $adv_notification	=	$this->test_input($this->input->post('adv_notification'));
       $user_id			    =	$this->test_input($this->input->post('user_id'));


       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter your user id.'));
			echo json_encode($err); exit;
		} 

       if(empty($adv_notification)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Adv Notification Type.'));
			echo json_encode($err); exit;
		} else if($adv_notification != 'Yes' && $adv_notification != 'No') {
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter exact Adv Notification Type.'));
			echo json_encode($err); exit;
		}
            
		$update_data = array(
            'adv_notification' => $this->input->post('adv_notification'),
            'modified' => date("Y-m-d H:i:s"),
	    );
		if(!$this->Common_model->addEditRecords('users', $update_data,array('user_id'=>$user_id))) {
		    $err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
            echo json_encode($err); exit;
		} else {
			$suc = array('data' =>array('status' => '1', 'msg' => 'Adv Notification updated successfully.'));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please send user id'));
			echo json_encode($err); exit;
		}

		if(empty($current_password)) {
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter current password'));
			echo json_encode($err); exit;
		}


		if(empty($new_password)){
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter new Password'));
			echo json_encode($err); exit;

		}
		
		$current_password=base64_encode($current_password);
		$new_password=base64_encode($new_password);
		
		if(!$user_data = $this->Common_model->getRecords($tableName,'*',array('user_id' => $user_id,'status'=>'Active'),'',true)) {
			$err = array('data'=> array('status'=>'5','msg'=>'Your profile has been Inactive by admin.'));
			echo json_encode($err); exit;
		} else {
			if($user_data['password'] != $current_password) {
				$err = array('data'=> array('status'=>'0','msg'=>'Incorrect current password'));
				echo json_encode($err); exit;
			} else {
				if($new_password == $user_data['password']) {
					$err = array('data' =>array('status' => '0', 'msg' => "New password can't be same as current password."));
					echo json_encode($err); exit;
				}
		        $update_data = array(
		       		'password'=>$new_password,
		       		'modified' => date("Y-m-d H:i:s"),
		        );
		        

	   			if($this->Common_model->addEditRecords($tableName,$update_data,$where)) {
	   
	   				$response = array('data'=> array('status'=>'1','msg'=>'Password changed successfully'));
					echo json_encode($response);  exit;
	   			} else {
	   				$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding please try again !!'));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please send page id'));
			echo json_encode($err); exit;
		}
		$tableName = 'pages';
		$where = array('page_id' => $page_id);
		if(!$page_data = $this->Common_model->getRecords($tableName,'*',$where,'',true)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Page id not exists'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>'Page found successfully','details'=>$page_data));
			echo json_encode($response); exit;
		}
	}


/*===========================================Faq Pages=========================================*/
	public function Faq()
	{ 
        $tableName = 'faq';
		$where = array('status' =>'Active','approved'=>1);
		if(!$page_data = $this->Common_model->getRecords($tableName,'*',$where,'',false)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>'Faq found successfully','details'=>$page_data));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter your user id.'));
			echo json_encode($err); exit;
		} 

       if(empty($location)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter location Type.'));
			echo json_encode($err); exit;
		} else if($location != 'Yes' && $location != 'No') {
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter exact location Type.'));
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
		    $err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
            echo json_encode($err); exit;
		} else {
			$suc = array('data' =>array('status' => '1', 'msg' => 'location updated successfully.'));
            echo json_encode($suc); exit;
		} 
    }


	/*===========================================Forgot Password=========================================*/
	public function forgot_password()
	{ 
			$email = $this->test_input($this->input->post('email'));
		  	  
			if(empty($email)) {
				$err = array('data'=> array('status'=>'0','msg'=>'Please enter your email.'));
				echo json_encode($err); exit;
			}

			if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter valid email.'));
				echo json_encode($err);exit;
			}

			
			$tableName ='users';
			$where = array('email' =>$email);
			if(!$user_data = $this->Common_model->getRecords($tableName,'user_id,username,full_name,password',$where,'',true)) {
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter registered email.'));
			echo json_encode($err); exit;
		} else {
			
			if($user_data['full_name']) {
				$user_name = $user_data['full_name'];
			}
			 
			$subject = "Reset Password Code";
		 
			$data['title']=  'Forgot Password';
			$data['username']= $user_name;
			$data['message']= '<b>Your login Password is: </b>'.base64_decode($user_data['password']);
			$body = $this->load->view('template/common', $data,TRUE);
			$to_email = $email;
			$from_email = getAdminEmail(); 
			// $this->Common_model->setMailConfig();

		 
			//Send mail 
			if($this->Common_model->sendEmail($to_email,$subject,$body,$from_email)) 
			{

				$response = array('data'=> array('status'=>'1','msg'=>'We have sent your password to the email.'));
				echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'Some error occured. Please try again !!.'));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter reset password code.'));
			echo json_encode($err); exit;
		} else {
			$where = array('email' => $email,'token' => $code);
			if(!$user_data = $this->Common_model->getRecords($tableName,'*',$where,'',true)) {
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter valid code received on the email.'));
				echo json_encode($err); exit;
			}
		}

		if(empty($new_password)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter your password.'));
			echo json_encode($err); exit;
		} else if(!passwordValidate($new_password)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter valid password.'));
			echo json_encode($err); exit;
		}

		$new_password = base64_encode($new_password);
		if($new_password == $user_data['password']) {
			$err = array('data' =>array('status' => '0', 'msg' => "New password can't be same as old password."));
			echo json_encode($err); exit;
		}

		//check code is alive or expire
		$token_date = strtotime($user_data['token_date']);
		$current_date=strtotime(date("Y-m-d H:i:s"));
		$diff=$current_date-$token_date;
		if($diff > 86400) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Reset password code has been expired !!'));
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

			$response = array('data'=> array('status'=>'1','msg'=>'Password reset successfully.'));
			echo json_encode($response); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding. Please try again !!'));
			echo json_encode($err); exit;
		}
	}
	/*=============================tour image===========================================*/
	public function banner()
	{ 
        $tableName = 'banners';
		
		if(!$page_data = $this->Common_model->getRecords($tableName,'*',array('type'=>'Home'),'',true)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>' banner found successfully','details'=>$page_data));
			echo json_encode($response); exit;
		}
	}
	public function banner_faq()
	{ 
        $tableName = 'banners';
		
		if(!$page_data = $this->Common_model->getRecords($tableName,'*',array('type'=>'FAQ'),'',true)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>' banner found successfully','details'=>$page_data));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Detail Or Photo'));
			echo json_encode($err); exit;
		} 


        if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter User Id.'));
			echo json_encode($err); exit;
		} 

		if(empty($post_title)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Title.'));
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
					   $err = array('data' =>array('status' => '0', 'msg' => 'Only jpg|jpeg|png image types allowed..'));
			   			echo json_encode($err); exit;	
				    } 
			    }
            } else {
				$err = array('data' =>array('status' => '0', 'msg' => 'You can not uplode more than 5.'));
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
					   $err = array('data' =>array('status' => '0', 'msg' => 'Only mp4 image types allowed..'));
			   			echo json_encode($err); exit;	
				    } 
			    }
            } else {
			$err = array('data' =>array('status' => '0', 'msg' => 'You can not uplode more than 2.'));
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
                        $err = array('data' =>array('status' => '0', 'msg' => $error ));
						echo json_encode($err); exit;
					}
				} 
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'You can not uplode more then 5.'));
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
                        $err = array('data' =>array('status' => '0', 'msg' => $error ));
						echo json_encode($err); exit;
					}
					if($this->upload->do_upload('post_user_video')){
						$filevideo = $this->upload->data();	
					} else {
						$error = array('error' => $this->upload->display_errors());
                        $err = array('data' =>array('status' => '0', 'msg' => $error ));
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
				$err = array('data' =>array('status' => '0', 'msg' => 'You can not upload more then 5.'));
				echo json_encode($err); exit;
			}
		}
                      
		if(!$last_id) {
		    $err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
		    echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>'Posted'));
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
		
		$response = array('data'=> array('status'=>'1','msg'=>'Post','details'=>$getPost,'badge_count'=>$count['badge_count'],'chat_badge'=>$count['chat_badge']));
		echo json_encode($response); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'msg' => 'No Data Found'));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter media Id.'));
			echo json_encode($err); exit;
		} 

       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter User Id.'));
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

			$response = array('data'=> array('status'=>'1','msg'=>'unlike','count'=>$likes));
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
			       			);

							$andarray = array(
				                'message'   => $current_user['full_name'].' liked your media.',
				               	'type'  => 'media_type',
			                   	'media_id'=> $media_id,
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
	     
         	$response = array('data'=> array('status'=>'1','msg'=>'like','count'=>$likes));
			echo json_encode($response); exit;  
        }
	} 



	public function postComment(){
		$this->check_login();

        $user_id		 =	$this->test_input($this->input->post('user_id'));
        $post_id		 =	$this->test_input($this->input->post('post_id'));
        $comment		 =	$this->test_input($this->input->post('comment'));
        $page_id		 =	$this->test_input($this->input->post('page_id'));
       
       if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}

       if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter User Id.'));
			echo json_encode($err); exit;
		}

		if(empty($comment)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Comment.'));
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
        $response = array('data'=> array('status'=>'1','msg'=>'comment','details'=>$comment));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}else {
			$tableName="user_post";
			$where = array('post_id' =>$post_id,'is_deleted'=>'0','status'=>'Active');
			$view_post=$this->Common_model->getRecords($tableName,'view_post,post_id',$where,'',true);
			if(empty($view_post)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Post is Deactive or may be Deleted.'));
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
			$response = array('data'=> array('status'=>'1','msg'=>'Details','details'=>$getPost));
			echo json_encode($response); exit;
			   
		}else {
            $response = array('data'=> array('status'=>'0','msg'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
           
        }
    }

    public function getCommentList() {
    	$this->check_login();
        $post_id =	$this->test_input($this->input->post('post_id'));
        $page_id =	$this->test_input($this->input->post('page_id'));

        if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Id.'));
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
				$response = array('data'=> array('status'=>'1','msg'=>'comment found successfully','details'=>$post_comment));
				echo json_encode($response); exit;
			}else {
				$response = array('data'=> array('status'=>'0','msg'=>'Be the first one to comment'));
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
            	
             
				$response = array('data'=> array('status'=>'1','msg'=>'Following List Found Successfully','details'=>$post_comment));
				echo json_encode($response); exit;
			}else {
				$response = array('data'=> array('status'=>'0','msg'=>'No Follow found '));
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
				$response = array('data'=> array('status'=>'1','msg'=>'Follower List Found Successfully','details'=>$post_comments));
				echo json_encode($response); exit;
			}else {
				$response = array('data'=> array('status'=>'0','msg'=>'No Following found '));
				echo json_encode($response); exit;
			}
    }

     public function postFollow() {
    	$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id'));
        $follow_user_id =	$this->test_input($this->input->post('follow_user_id'));
    	
        if(empty($follow_user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Follow User Id.'));
			echo json_encode($err); exit;
		}

        $where = array('follow_user_id' => $follow_user_id, 'user_id' => $user_id);
			if($post_images = $this->Common_model->getRecords('follow_user','*',$where,'',true)) {
				$this->Common_model->deleteRecords('follow_user',$where);

					$where11 = array('user_id' =>$follow_user_id,'created_by' =>$user_id,'type'=>'follow');
					$add =array('status' =>'1');
					$this->Common_model->addEditRecords('notifications',$add,$where11); 

				$response = array('data'=> array('status'=>'1','msg'=>'Unfollow'));
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
          
            	$response = array('data'=> array('status'=>'1','msg'=>$status,'details'=>$status));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}

		if(empty($post_owner)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Owner Id.'));
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
			


        	$response = array('data'=> array('status'=>'1','msg'=>'Post Shared'));
			echo json_encode($response); exit;
        }else{
        	$response = array('data'=> array('status'=>'0','msg'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
        }

    }


     public function reportPost() {
    	$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id'));
        $post_id =	$this->test_input($this->input->post('post_id'));

        if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}

        $report_detail =	$this->test_input($this->input->post('report_detail'));
        $It_Spam =	$this->test_input($this->input->post('it_spam'));
        $It_Inappropriate =	$this->test_input($this->input->post('it_inappropriate'));

        $update_data = array('post_id' => $post_id,'user_id' => $user_id, 'report_detail' => $report_detail, 'It_Spam' => $It_Spam , 'It_Inappropriate' => $It_Inappropriate,  'created' => date("Y-m-d H:i:s"));
             		
        if($this->Common_model->addEditRecords('report_post',$update_data)){
        	$response = array('data'=> array('status'=>'1','msg'=>'Thank you for your report'));
			echo json_encode($response); exit;
        }else{
        	$response = array('data'=> array('status'=>'0','msg'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
        }

    }

    public function deletePost() {
    	$this->check_login();
        $post_id =	$this->test_input($this->input->post('post_id')); 

        if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Id.'));
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
        	$response = array('data'=> array('status'=>'1','msg'=>'Post Deleted'));
			echo json_encode($response); exit;
        }else {
        	$response = array('data'=> array('status'=>'0','msg'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
		}
	}

	public function delete_media(){
		$this->check_login();
        $media_id =	$this->test_input($this->input->post('media_id')); 

        if(empty($media_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Media Id.'));
			echo json_encode($err); exit;
		}

		$where = array('post_img_id' =>$media_id);
		$image_data=$this->Common_model->getRecords('post_img','video_path,file_path',$where,'',true); 
		
		//Delete post image
		$this->Common_model->deleteRecords('post_img',$where);
      
      	if(!empty($image_data)){
			if(!empty($image_data['file_path'])){
				unlink($image_data['file_path']);
			}
			if(!empty($image_data['video_path'])){
				unlink($image_data['video_path']);
			}
        	$response = array('data'=> array('status'=>'1','msg'=>'Delete Media'));
			echo json_encode($response); exit;
        }else {
        	$response = array('data'=> array('status'=>'0','msg'=>'Some error occured. Please try again !!.'));

			echo json_encode($response); exit;
		}
	}

	public function editPost(){
    	$this->check_login();
        $post_id         =	$this->test_input($this->input->post('post_id'));
        $detail	         =  $this->input->post('detail');  
        $post_title		 =	$this->test_input($this->input->post('post_title'));
        $user_id		 =	$this->test_input($this->input->post('user_id'));
        $post_date		 =	$this->test_input($this->input->post('post_date'));
        $tag_user		 =	$this->input->post('tag_user');
        $tag_page_id	 =	$this->input->post('tag_page_id');
      	$hash_user		 =	$this->input->post('hash_user');
        $hash_page		 =	$this->input->post('hash_page');
        $latitude		 =	$this->input->post('latitude');
        $longitude		 =	$this->input->post('longitude');
        $address		 =	$this->input->post('address');
        $address_name		 =	$this->input->post('address_name');
        //$location_id	 =	$this->input->post('location_id');

      
    	if(empty($post_title)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Title.'));
			echo json_encode($err); exit;
		}

		if(empty($post_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Post Id.'));
			echo json_encode($err); exit;
		}

		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter User Id.'));
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
					   $err = array('data' =>array('status' => '0', 'msg' => 'Only jpg|jpeg|png image types allowed.'));
			   			echo json_encode($err); exit;	
				    } 
			    }
            } else {
			$err = array('data' =>array('status' => '0', 'msg' => 'You can not uplode more then 5.'));
		    echo json_encode($err); exit;
            }
        }

    

        if(!empty($_FILES['user_video']['name'])){
    		$photo = 0;
    		$allowed =  array('mp4','MP4','3gp');
			$filesCount = count($_FILES['user_video']['name']);
            if ($filesCount <= 2) {
             	for($i = 0; $i <$filesCount; $i++){
					$filename = $_FILES['user_video']['name'][$i];
				    $ext = pathinfo($filename, PATHINFO_EXTENSION);
				    if(!in_array($ext,$allowed) ) {
					   $err = array('data' =>array('status' => '0', 'msg' => 'Only mp4 video types allowed..'));
			   			echo json_encode($err); exit;	
				    } 
			    }
            } else {
			$err = array('data' =>array('status' => '0', 'msg' => 'You can not uplode more then 2.'));
		    echo json_encode($err); exit;
            }
        }
  

	   	$link="#";
	    $createtag = createtag($this->input->post('detail'), $link);
	    // $detail = $this->input->post('detail');
		if(count($createtag)==1){
			 $detail =  current($createtag); 
		 }else{
		     $detail =   end($createtag);
		 }
        $where = array('post_id' =>$post_id);
        $post_list  = $this->Common_model->getRecords('user_post','',$where,'',true); 
        $post_id  =   $post_list['parent_id'];
        $update_data = array(
          	'post_title' => $post_title,
			'post_detail' => $detail,
			'post_date' => date('Y-m-d', strtotime($post_date)),
			'modified' => date("Y-m-d H:i:s"),
		);
		$where = array('parent_id' =>$post_list['parent_id']);
        
        $last_id =$this->Common_model->addEditRecords('user_post',$update_data,$where);


        if(count($createtag)!=1){
		array_pop($createtag);
			foreach ($createtag as $value) {
				if($id = $this->Common_model->getRecords('tags','tag_word_id',array('word' =>$value),'',true)){
					if($this->Common_model->getRecords('hashtagword','hashtag_id',array('tag_word_id' =>$id['tag_word_id'],'post_id' => $post_id),'',true)){

					}else{
	                    $addtag = array(
						'tag_word_id' => $id['tag_word_id'],
						'post_id' => $post_id,
						'created' => date("Y-m-d H:i:s"),
						); 
					   	$this->Common_model->addEditRecords('hashtagword', $addtag);
					}
				}else {
					$addtag = array(
					'word' => $value,
					'created' => date("Y-m-d H:i:s"),
					); 
					$id = $this->Common_model->addEditRecords('tags', $addtag);

					$add = array(
						'tag_word_id' => $id,
						'post_id' => $post_id,
						'created' => date("Y-m-d H:i:s"),
					); 
					$this->Common_model->addEditRecords('hashtagword', $add);
				}	
			}
		}
		if(!empty($hash_user)){
        	
            $tag_user_data = explode(",",$hash_user);
       
           foreach ($tag_user_data as $user) {
            	if($this->Common_model->getRecords('hashtaguser','hashtaguser_id',array( 'post_id' => $post_id,'id' => $user, 'is_page' => '0'),'',true)){

				}else{
            	
	                $addtag = array(
		      	        'post_id' => $post_id,
		      	        'id' => $user,
		      	        'is_page' => '0',
				        'created' => date("Y-m-d H:i:s"),
				    ); 

				    $this->Common_model->addEditRecords('hashtaguser', $addtag);

				  
			        $where = array('user_id' => $user);
			        $resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);
			        $row = array('user_id' => $user_id);
			        $sender=$this->Common_model->getRecords('users','username',$row,'',true);
			        // if($user != $user_id){
			        //     if($resiver['notification']=='Yes'){
			        //         $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);

			        //         if(!empty($log)){
			        //             foreach ($log as $key) {
			                        
			        //                 if($key['device_type']=='Android'){
			        //                     $referrer = androidNotification($key['device_id'],$sender['username'].' have tagged you on their post.');
			        //                 }

			        //                 if($key['device_type']=='IOS'){
			        //                     $referrer = iosNotification($key['device_id'],$sender['username'].' have tagged you on their post.');
			        //                 }
			        //             }
			        //         }
			               
			        //       //  $add_data =array('user_id' => $user,'created_by' =>$user_id,'type'=>'tagged', 'notification_title'=>'tagged Post', 'notification_description'=>$sender['username'].' have tagged you on their post.', 'notification_sent_datetime' => date("Y-m-d H:i:s"));
			        //        // $this->Common_model->addEditRecords('notifications',$add_data); 

			        //     }
			        // }
	            }

            }
        }



		if(!empty($hash_page)){
        	
            $tag_user_data = explode(",",$hash_page);
       
           foreach ($tag_user_data as $user) {
            	if($this->Common_model->getRecords('hashtaguser','hashtaguser_id',array( 'post_id' => $post_id,'id' => $user, 'is_page' => '1'),'',true)){

				}else{
            	
	                $addtag = array(
		      	        'post_id' => $post_id,
		      	        'id' => $user,
		      	        'is_page' => '1',
				        'created' => date("Y-m-d H:i:s"),
				    ); 

				    $this->Common_model->addEditRecords('hashtaguser', $addtag);
				}
			}
        }
       
        if(empty($post_list['location_id'])){

        	if(!empty($latitude) && !empty($longitude) && !empty($address)) {

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
					$this->Common_model->addEditRecords('user_post', $location,array('post_id'=> $post_id));  
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
					$this->Common_model->addEditRecords('user_post', $location,array('post_id'=> $post_id));  
				}
			}
               
        }else{
   			if(!empty($latitude) && !empty($longitude) && !empty($address)) {
					
	        		$where = array('latitude' => $latitude,'longitude' => $longitude);
				    if($gio_location=$this->Common_model->getRecords('gio_location','location_id',$where,'',true)) {
				    	$location = array(
							'location_id' => $gio_location['location_id'],
						);
						$this->Common_model->addEditRecords('user_post', $location,array('post_id'=> $post_id));  
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
						$this->Common_model->addEditRecords('user_post', $location,array('post_id'=> $post_id));  
					}
				}
				else{
					$location = array(
							'location_id' => 0,
						);
						$this->Common_model->addEditRecords('user_post', $location,array('post_id'=> $post_id)); 

				}

			}
        
        $taguser = $this->App_model->taguser($post_id);
		
		if($tag_user) {
			$tag_user_data = explode(",",$tag_user);
			$reserved = array();
	   	    if(!empty($taguser)) {
		   	    foreach ($taguser as $list) {
		   	    	if(in_array($list['user_id'], $tag_user_data)){
		            	$reserved[] = $list['user_id'];
		   	    	} else {
		         		//Delete tagged user
		         		$where = array('tag_user_id'=>$list['tag_user_id']);
						$this->Common_model->deleteRecords('tag_user',$where);
		   	    	}
		   	    }
		   	}

	   	    foreach ($tag_user_data as $user_id) {
	   	    	if(!in_array($user_id, $reserved)){
	            	$addtag = array(
						'post_id' => $post_id,
						'user_id' => $user_id,
						'created' => date("Y-m-d H:i:s")
					); 
		            $this->Common_model->addEditRecords('tag_user', $addtag);
	   	    	} 
	   	    }
		} else {
			if(!empty($taguser)) {
		   	    foreach ($taguser as $list) {
		   	    	$where = array('tag_user_id'=>$list['tag_user_id']);
					$this->Common_model->deleteRecords('tag_user',$where);
		   	    }
		   	}
		}

		$tagpage = $this->App_model->tagpage($post_id);
		
		if($tag_page_id) {
			$tag_page_data = explode(",",$tag_page_id);
			$reserved2 = array();
	   	    if(!empty($tagpage)) {
		   	    foreach ($tagpage as $list) {
		   	    	if(in_array($list['page_id'], $tag_page_data)){
		            	$reserved2[] = $list['page_id'];
		   	    	} else {
		         		//Delete tagged user
		         		$where = array('tag_id'=>$list['tag_id']);
						$this->Common_model->deleteRecords('tag_page',$where);
		   	    	}
		   	    }
		   	}

	   	    foreach ($tag_page_data as $page_id) {
	   	    	if(!in_array($page_id, $reserved2)){
	            	$addtag = array(
						'post_id' => $post_id,
						'page_id' => $page_id,
						'created' => date("Y-m-d H:i:s")
					); 
		            $this->Common_model->addEditRecords('tag_page', $addtag);
	   	    	} 
	   	    }
		} else {
			if(!empty($tagpage)) {
		   	    foreach ($tagpage as $list) {
		   	    	$where = array('tag_id'=>$list['tag_id']);
					$this->Common_model->deleteRecords('tag_page',$where);
		   	    }
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
					$config['allowed_types'] = '*';
					$config['file_name'] =  $img;
		
					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if($this->upload->do_upload('post_photo')){
						$fileData = $this->upload->data();
						$uploadData = array();
						$uploadData= array(
							'file_path' => 'resources/images/post/'.$config['file_name'].$fileData['file_ext'],
							'post_id' => $post_id,
							'created' => date("Y-m-d H:i:s"),
							'file_type'=> 'Image'
						);

						$this->Common_model->addEditRecords('post_img', $uploadData);
					} else {
						$error = array('error' => $this->upload->display_errors());
                        $err = array('data' =>array('status' => '0', 'msg' => $error ));
						echo json_encode($err); exit;
					}
				} 
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'You can not uplode more then 5.'));
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
					$config['allowed_types'] = '*';
					$config['file_name'] =  $img;
		
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					
					$this->upload->do_upload('post_video_thumb');
					$videoData = $this->upload->data();
					$this->upload->do_upload('post_user_video');
					$filevideo = $this->upload->data();

					$uploadData = array();
					$uploadData= array(
						'file_path' => 'resources/images/post/'.$videoData['file_name'],
						'video_path' => 'resources/images/post/'.$filevideo['file_name'],
						'post_id' => $post_id,
						'created' => date("Y-m-d H:i:s"),
						'file_type'=> 'Video'
					);

					$this->Common_model->addEditRecords('post_img', $uploadData);
				} 
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'You can not upload more than 5.'));
				echo json_encode($err); exit;
			}
		}

		if(!$last_id) {
		    $err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
            echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>'Post Updated Successfully'));
            echo json_encode($response); exit;
		}
	}

	public function getExploreList() {

		$this->check_login();
		$auth_key =	$this->input->post('auth_key');
		$lat = $this->input->post('lat');
		$lng = $this->input->post('lng');
		$user_id = $this->input->post('user_id');
		$sort = $this->input->post('sort');

		/************************ For seach results *******************************/
		$search_result = $this->input->post('search_result');
		$search_user_id = $this->input->post('search_user_id');
		$hashtag_id = $this->input->post('hashtag_id');


		/************************ For seach results *******************************/

		
		if(!empty($sort)) {	

			if ($sort!='likes' && $sort!='views' && $sort!='latest') {

					$err = array('data'=> array('status'=>'0','msg'=>'Sort must be likes or views or latest.'));
					echo json_encode($err);exit;
			}
		}
		if(!empty($user_id)){
			$this->check_login();
			if($lat =='' && $lng =='' ){
				$where = array('user_id' => $user_id);
			    $user  = $this->Common_model->getRecords('users','latitude,longitude',$where,'',true);
			    if($user['latitude'] =='' && $user['longitude'] =='' ){
					$err = array('data' =>array('status' => '0', 'msg' => 'latitude and longitude is required'));
					echo json_encode($err); exit;
		        }
		    }else{
		    	$where = array('user_id' => $user_id);
				$update_data =array('latitude' => $lat,'longitude' => $lng);

            	$this->Common_model->addEditRecords('users',$update_data,$where);
		    }

		  	
		  	if($search_result=='yes')
		  	{
				 if(empty($hashtag_id)){
					if(empty($search_user_id))
			  		{ 
			  			// echo '123';die;
			  			$getPost = $this->App_model->getExplore_with_search($lat,$lng,$sort,'',$user_id);
			  			// echo $this->db->last_query();die;
			  		}else
			  		{ 
		  				$getPost = $this->App_model->getExplore_with_search_user_id($sort,$search_user_id,$user_id);
			  		}
		  		}else{
	              $getPost =  $this->App_model->gethashtagpost($user_id,$hashtag_id,$sort);

		  		}

		  	}else
		  	{
		  		$getPost = $this->App_model->getExplore($lat,$lng,$sort,$user_id);
		  	}
		 	
			if($getPost) {
			    $index=0;
				foreach ($getPost as $get) {
				 	if($get['business_page_id']!='0'){
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
		      	}else {
			      	$getPost[$index]['is_page'] = '0';
			      	$getPost[$index]['page_id'] = '';
			      	$getPost[$index]['is_my_page'] = '0';
		      	}


					if($get['user_id']==$user_id){
		                $getPost[$index]['myPost'] = '1';
					}else {
						$getPost[$index]['myPost'] = '0';
					}

					if($get['post_date'] == '0000-00-00') {
					$getPost[$index]['post_date'] ='';
		   			}
					
		            $post_images = array();
					$where = array('post_id' => $get['post_id']);
					if($post_images = $this->Common_model->getRecords('post_img','file_path,file_type,video_path',$where,'',false)) {
						$getPost[$index]['post_media'] = $post_images;
					}else{
					$getPost[$index]['post_media'] = $post_images;	
					}

					if($isFollow = $this->App_model->isFollow($get['user_id'],$user_id)) {
						if($isFollow[0]['status']=='Follow'){
							$getPost[$index]['isFollow'] = '1';	
						}else{
							$getPost[$index]['isFollow'] = '2';
						}
					} else {
						$getPost[$index]['isFollow'] = '0';
					}
		            $index++;
				} 
				$response = array('data'=> array('status'=>'1','msg'=>'PostList','details'=>$getPost));
				echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'Record not found.'));
				echo json_encode($err); exit;	
			}

		} else {

        	if($search_result=='yes')
		  	{	
		  		if(empty($search_user_id))
		  		{

		  			if($lat =='' && $lng =='' ){
						$err = array('data' =>array('status' => '0', 'msg' => 'latitude and longitude is required'));
						echo json_encode($err); exit;
	        		}
	        		$getPost = $this->App_model->getExplore_with_search($lat,$lng,$sort);
		  		}else
		  		{
				$getPost = $this->App_model->getExplore_with_search_user_id($sort,$search_user_id);
		  		}

		  	}else
		  	{
		  		if($lat =='' && $lng =='' ){
					$err = array('data' =>array('status' => '0', 'msg' => 'latitude and longitude is required'));
					echo json_encode($err); exit;
		        }
		  		$getPost = $this->App_model->getExplore($lat,$lng,$sort);
		  	}

	        
	        if($getPost) {
			    $index=0;
				foreach ($getPost as $get) {
					
					$getPost[$index]['myPost'] = '0';
                    $getPost[$index]['isFollow'] = '0';
					if($get['post_date'] == '0000-00-00') {
					$getPost[$index]['post_date'] ='';
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
				$response = array('data'=> array('status'=>'1','msg'=>'PostList','details'=>$getPost));
				echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'Result not found'));
				echo json_encode($err); exit;	
			}
    	}

	}

	public function getExploreAll() {
        $this->check_login();
 		$auth_key =	$this->input->post('auth_key');
		$lat = $this->input->post('lat');
		$lng = $this->input->post('lng');
		$user_id = $this->input->post('user_id');
		$data_type = $this->input->post('data_type');
		$sort = $this->input->post('sort');

		/************************ For seach results *******************************/
			$search_result = $this->input->post('search_result');
			$search_user_id = $this->input->post('search_user_id');
			$hashtag_id = $this->input->post('hashtag_id');
		/************************ For seach results *******************************/

		if(empty($data_type)) {

				$err = array('data'=> array('status'=>'0','msg'=>'Please enter data type.'));
				echo json_encode($err);exit;

		}elseif ($data_type!='image' && $data_type!='video' && $data_type!='map') {

				$err = array('data'=> array('status'=>'0','msg'=>'Data type must be Image or video or map.'));
				echo json_encode($err);exit;
		}
		if(!empty($sort)) {	

			if ($sort!='likes' && $sort!='views' && $sort!='latest') {

					$err = array('data'=> array('status'=>'0','msg'=>'Sort must be likes or views or latest.'));
					echo json_encode($err);exit;
			}
		}

		if(!empty($user_id)){
			$this->check_login();
			if($lat =='' && $lng =='' ){
				$where = array('user_id' => $user_id);
			    $user  = $this->Common_model->getRecords('users','latitude,longitude',$where,'',true);
			    if($user['latitude'] =='' && $user['longitude'] =='' ){
					$err = array('data' =>array('status' => '0', 'msg' => 'latitude and longitude is required'));
					echo json_encode($err); exit;
		        }else
		        {
		        	$lat = $user['latitude'];
		        	$lng=  $user['longitude'];
		        }
		    }else{
		    	$where = array('user_id' => $user_id);
				$update_data =array('latitude' => $lat,'longitude' => $lng);
            	$this->Common_model->addEditRecords('users',$update_data,$where);
		    }
		  
		}

		if($data_type == 'map')
		{
			if($search_result=='yes'){
				if(empty($hashtag_id)){
					if(empty($search_user_id)){
						if(empty($lat)) {
							$err = array('data'=> array('status'=>'0','msg'=>'Please enter latitude.'));
							echo json_encode($err);exit;
						}
						if(empty($lng)) {
							$err = array('data'=> array('status'=>'0','msg'=>'Please enter longitude.'));
							echo json_encode($err);exit;
						}
				
						$data =  $this->App_model->getExplore_maps_location_place($lat,$lng);
					}else
					{
						if(empty($lat)) {
							$err = array('data'=> array('status'=>'0','msg'=>'Please enter latitude.'));
							echo json_encode($err);exit;
						}
						if(empty($lng)) {
							$err = array('data'=> array('status'=>'0','msg'=>'Please enter longitude.'));
							echo json_encode($err);exit;
						}
						$data =  $this->App_model->getExplore_maps_location_place_with_user_id($search_user_id,$lat,$lng);
					}
				}else
				{
					$data =  $this->App_model->getExplore_maps_hashtag_place($hashtag_id);
				}


			}else
			{
				$data =  $this->App_model->getExplore_maps_location($lat,$lng,$user_id);
				// echo $this->db->last_query();die;
			}

	
		}else{

			$index=0;

			/****************************************For serch_results*************************************/
		if($search_result=='yes'){
				if(empty($hashtag_id)){
					if(empty($search_user_id)){
						$data =  $this->App_model->getExplore_images_search($lat,$lng,$data_type,$sort);
					}else
					{
						$data =  $this->App_model->getExplore_images_search_with_user_id($data_type,$sort,$search_user_id);
					}	
				}else{

						$data =  $this->App_model->getExplore_hashtag_search($data_type,$sort,$hashtag_id);
				}
		}else{
			if(empty($lat)) {
				$err = array('data'=> array('status'=>'0','msg'=>'Please enter latitude.'));
				echo json_encode($err);exit;
			}
			if(empty($lng)) {
				$err = array('data'=> array('status'=>'0','msg'=>'Please enter longitude.'));
				echo json_encode($err);exit;
			}
			
			$data =  $this->App_model->getExplore_images($lat,$lng,$data_type,$sort,$user_id);
		}	
			foreach($data as $list){
				if($list['business_page_id']!='0'){
		    		$where = array('business_page_id' =>$list['business_page_id']);
		    		if($page_row = $this->Common_model->getRecords('business_page','user_id,business_image,business_name',$where,'',true)) {
		    			
		    			if($page_row['user_id']==$user_id){
		    				$data[$index]['is_my_page'] = '1';	
		    			}else{
		    				$data[$index]['is_my_page'] = '0';		
		    			}
				    		$data[$index]['username'] = $page_row['business_name'];
				    		$data[$index]['is_page'] = '1';
				    		$data[$index]['page_id'] = $list['business_page_id'];
				      		$data[$index]['profile_pic'] = $page_row['business_image'];
		      		}else{
			      		$data[$index]['is_page'] = '0';
			      		$data[$index]['page_id'] = '';
			      		$data[$index]['is_my_page'] = '0';
		      		}
		      	}else {
		      	$data[$index]['is_page'] = '0';
		      	$data[$index]['page_id'] = '';
		      	$data[$index]['is_my_page'] = '0';
		      	}


				if($isFollow = $this->App_model->isFollow($list['user_id'],$user_id)) {
				    if($isFollow[0]['status']=='Follow'){
						$data[$index]['isFollow'] = '1';	
					}else{
						$data[$index]['isFollow'] = '2';
					}
				} else {
					$data[$index]['isFollow'] = '0';
				}
				if($list['user_id']==$user_id){
	                $data[$index]['myPost'] = '1';
				}else {
					$data[$index]['myPost'] = '0';
				}
				$index++;
			}
		}
		if(!empty($data))
		{
			$response = array('data'=> array('status'=>'1','msg'=>'PostList','details'=>$data));
			echo json_encode($response); exit;
		}else
		{
			$response = array('data'=> array('status'=>'0','msg'=>'No result found.'));
			echo json_encode($response); exit;
		}	
		

	}

	public function getExploreMap() {
		$this->check_login();
		$location_id = $this->input->post('location_id');
		$user_list   =  $this->App_model->getExploreMap($location_id);
		// echo $this->db->last_query();die;
		$index=0;
		foreach($user_list as $list){ 
		    	if($list['business_page_id']!='0'){
		    		$where = array('business_page_id' =>$list['business_page_id']);
		    		if($page_row = $this->Common_model->getRecords('business_page','user_id,business_image,business_name',$where,'',true)) {
		    			$user_list[$index]['username'] = $page_row['business_name'];
			    		$user_list[$index]['profile_pic'] = $page_row['business_image'];
		    			$redeem=$this->Common_model->getRecords('business_page','disply_rating,category_id,from_price,to_price',$where,'',true);
						if($redeem['disply_rating']=='verified_rating'){
							$disply_rating ='verified';
						}else {
							$disply_rating ='';
						}
						$user_list[$index]['from_price'] = $redeem['from_price'];
						$user_list[$index]['to_price'] = $redeem['to_price'];
		    			$user_list[$index]['rating'] = 	$this->Common_model->business_rating($list['business_page_id'],$disply_rating);
		      		}else{
		      			$user_list[$index]['username'] = '';
		      			$user_list[$index]['profile_pic'] = '';
		      			$user_list[$index]['rating'] = '';
		      			$user_list[$index]['from_price'] = '';
		      			$user_list[$index]['to_price'] = '';
		      		}
		      	}else
		      	{
	      			$user_list[$index]['rating'] ='';
					$user_list[$index]['from_price'] ='';
					$user_list[$index]['to_price'] ='';
		      	}
		    
			if($image = $this->App_model->postImage($list['post_id'])) {
			    $user_list[$index]['post_image'] = $image['file_path'];
			} else {
				$user_list[$index]['post_image'] = '';
			}
		
			$index++;
		
		
		}
			if(!empty($user_list))
			{
				$response = array('data'=> array('status'=>'1','msg'=>'PostList','details'=>$user_list));
				echo json_encode($response); exit;
			}else
			{
				$error = array('data'=> array('status'=>'0','msg'=>'No Post found.'));
				echo json_encode($error); exit;
			}
			
   }


	public function getExploreSearch()
	{
		$this->check_login();
		$search_type = $this->input->post('search_type');
		$keyword = $this->input->post('keyword');
		if(empty($search_type)) {	
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter search type'));
			echo json_encode($err);exit;
		}elseif($search_type!='place' && $search_type!='people'  && $search_type!='hash_tag')
		{
			$err = array('data'=> array('status'=>'0','msg'=>'Search type must be place or people or hash_tag'));
			echo json_encode($err);exit;
		}


		if($search_type=='place')
		{ 
			if($keyword!='')
			{
				$data_select = 'location_id,latitude,longitude,address,';
			 	$arr  = array('address'=>$keyword);

		 		$select_data = $this->App_model->search($keyword,$arr,'gio_location',$data_select); 
	 	 	}else
	 	 	{	
 	 			$lat = $this->input->post('lat');
	 	 		$lng = $this->input->post('lng');
 	 			if(empty($lat) || empty($lng)) {

					$err = array('data'=> array('status'=>'0','msg'=>'Please enter latitude and longitude'));
					echo json_encode($err);exit;
				}
	 	 		
	 	 		$select_data =  $this->App_model->defaultpleaselist($lat,$lng);
	 	 	} 
		}else if($search_type=='hash_tag'){

			if($keyword!='')
			{
			 	$data_select = 'word,tag_word_id'; 
			 	$arr  = array('word'=>$keyword);
		 		$select_data = $this->App_model->search($keyword,$arr,'tags',$data_select); 
	 		}else{
				$select_data = $this->App_model->defaulthash_tag();
			
			}

		}
		else
		{ 
			if($keyword!='')
			{
			 	$data_select = 'user_id,username,full_name,profile_pic,account_type'; 
			 	$arr  = array('username'=>$keyword,'full_name'=>$keyword);
			 	$select_data = array();
		 		$select_data_arr = $this->App_model->search($keyword,$arr,'users',$data_select); 
		 		if(!empty($select_data_arr))
		 		{
		 			$index=0;
		 			
		 			foreach ($select_data_arr as $select_data_list) {

		 				$select_data[$index] = $select_data_list;
			 			$post_check = $this->Common_model->getRecords('user_post','post_id',array('is_deleted'=>0,'business_page_id'=>0,'user_id'=>$select_data_list['user_id'],'status'=>'Active'),'',true);
			 			if(!empty($post_check))
			 			{
			 				$select_data[$index]['post'] = 'yes';
			 			}else
			 			{
			 				$select_data[$index]['post'] = 'no';
			 			}


		 			$index++;	 
		 			}
		 		//	echo "<pre>";print_r($select_data);die;
		 			
		 		}
	 		}else{

	 			$user_id = $this->input->post('user_id');
 	 			if($user_id){

					$select_data = $this->App_model->defaultuserlist($user_id);
					if(!empty($select_data))
					{
						$inde = 0;
						foreach ($select_data as $selet_list) {
						$select_data[$inde]  = $selet_list;



						$post_check = $this->Common_model->getRecords('user_post','post_id',array('is_deleted'=>0,'business_page_id'=>0,'user_id'=>$selet_list['user_id'],'status'=>'Active'),'',true);
			 			if(!empty($post_check))
			 			{
			 				$select_data[$inde]['post'] = 'yes';
			 			}else
			 			{
			 				$select_data[$inde]['post'] = 'no';
			 			}
  
					 
						$inde++;
						}
					}

					
					
				}else
				{
	 			 	$select_data = $this->App_model->defaultuser($user_id);
				}
			}
 		} 
		

		if(!empty($select_data))
 		{
 		  $response =array('data'=> array('status'=>'1','msg'=>'results','details'=>$select_data));
 		}else
 		{
			$response = array('data'=> array('status'=>'0','msg'=>'Results not found.'));
 		}
		echo json_encode($response); 
		exit;

	}

	public function getCategory()
	{ 
		// $this->check_login();
        $tableName = 'categories';
		$where = array('status'=>'Active');
		if(!$categories = $this->Common_model->getRecords($tableName,'category_id,name',$where,'orders ASC',false)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>' categories found successfully','details'=>$categories));
			echo json_encode($response); exit;
		}
	}

	public function getSubCategory()
	{   $this->check_login();
		$categories	=	$this->test_input($this->input->post('categories_id'));
	  			
  			if(empty($categories)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please Select Categories.'));
				echo json_encode($err); exit;
			} 

		
		
        $tableName = 'sub_categories';
		$where = array('category_id' => $categories,'status'=>'Active');
		if(!$sub_categories = $this->Common_model->getRecords($tableName,'sub_category_id,name,image',$where,'orders ASC',false)) { 
			$err = array('data' =>array('status' => '0', 'msg' => 'categories not valid please try again !!'));
			echo json_encode($err); exit;
		} else {
  
			$response = array('data'=> array('status'=>'1','msg'=>' sub categoriesfound successfully','details'=>$sub_categories));
			echo json_encode($response); exit;
		}
	}
 





	public function getGooglePageInfo() {   
		$this->check_login();
		$user_id	        =  	$this->input->post('user_id');  
        $page_id		 	=	$this->test_input($this->input->post('page_id'));

		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter User Id.'));
			echo json_encode($err); exit;
		} 
		$business_page = array();
		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Page Id.'));
			echo json_encode($err); exit;
		} else{
			$business_page=$this->Common_model->google_busniess_details($page_id);
			// echo "<pre>";print_r($business_page);die;
		}

		if(!empty($business_page))
		{
			$where = array('follow_page_id' => $page_id);
			$business_page['Follower']=(string)$this->Common_model->getNumRecords('follow_page','follower_id',$where);
			//$business_page['is_follow']="0";
			//$business_page['price_level']=$price_level;
			$business_page['page_badge_count'] ="0";
			$business_page['pageMessageBadgeCount'] ="0";
			$business_page['user_id'] = $user_id;
			$business_page['is_follow'] = $this->Common_model->business_page_is_follow($page_id,$user_id);
			$check_clam =$this->Common_model->getRecords('google_business_clam','busniess_id',array('user_id'=>$user_id,'busniess_id'=>$page_id),'',true);
			if(!empty($check_clam)) {
				$business_page['is_claimed'] =  'yes';
			} else {
				$business_page['is_claimed'] =  'no';
			}
 


		$page_details=$this->Common_model->getRecords('most_viewed','*',array('page_id'=>$page_id),'',true);	
    	if(!empty($page_details))
    	{
    		$count = $page_details['count']+1;
    		 $insert_data = array(
			    	'count'=>  $count,
			    ); 
			$user_id = $this->Common_model->addEditRecords('most_viewed',$insert_data,array('id'=>$page_details['id'])); 
    	}else
    	{  
    		 $insert_data = array(
			    	'count'=> '1',
			    	'page_id'=>$page_id,
			    ); 
		 	 $this->Common_model->addEditRecords('most_viewed',$insert_data);  
    	}
   
		}
		// echo "<pre>";print_r($business_page);exit;
		if(!$business_page) {
			$err = array('data' =>array('status' => '0', 'msg' => 'No data found.'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>'page found successfully','details'=>$business_page));
			echo json_encode($response); exit;
		}
	}

	public function get_interest(){
		$this->check_login();
		$data['interest'] = array();
		if($data['interest']=getInterestList()) {
			$response = array('data'=> array('status'=>'1','msg'=>'interest list found' ,'details'=>$data['interest']));
		} else {
			$response = array('data'=> array('status'=>'0','msg'=>'interest not found.'));
		}
		echo json_encode($response);  exit;

	}

	public function editPage(){
		$this->check_login();
		$user_id	         =  $this->input->post('user_id'); 
		$page_id	 		 =  $this->input->post('page_id');  
        $business_name		 =	$this->test_input($this->input->post('business_name'));
        $business_full_name  =	$this->test_input($this->input->post('business_full_name'));
  	 	$lat		 		 =	$this->input->post('lat');
        $lng		         =	$this->input->post('lng');
        $description	     =  $this->test_input($this->input->post('description'));  
        $page_website		 =	$this->test_input($this->input->post('page_website'));
        $address_line1		 =	$this->test_input($this->input->post('address_1'));
        $address_line2		 =	$this->test_input($this->input->post('address_2'));
        $country		   	 =	$this->test_input($this->input->post('country'));
        $state		 		 =	$this->test_input($this->input->post('state'));
        $city				 =	$this->test_input($this->input->post('city'));
        $email				 =	$this->test_input($this->input->post('email'));
        $mobile				 =	$this->test_input($this->input->post('mobile'));
        $post_code			 =	$this->test_input($this->input->post('post_code'));
    	$disply_rating		 = 	$this->test_input($this->input->post('disply_rating'));

       	$sunday_from		 =	$this->input->post('sunday_from');
       	$sunday_to			 =	$this->input->post('sunday_to');
        $sunday24hours		 =	$this->input->post('sunday24hours');
        $sundayWorking		 =	$this->input->post('sundayWorking');
        $monday_from		 =	$this->input->post('monday_from');
        $monday_to			 =	$this->input->post('monday_to');
        $monday24hours		 =	$this->input->post('monday24hours');
        $mondayWorking		 =	$this->input->post('mondayWorking');
        $tuesday_from		 =	$this->input->post('tuesday_from');
        $tuesday_to			 =	$this->input->post('tuesday_to');
        $tuesday24hours		 =	$this->input->post('tuesday24hours');
        $tuesdayWorking		 =	$this->input->post('tuesdayWorking');
        $wednesday_from		 =	$this->input->post('wednesday_from');
        $wednesday_to		 =	$this->input->post('wednesday_to');
        $wednesday24hours	 =	$this->input->post('wednesday24hours');
        $wednesdayWorking	 =	$this->input->post('wednesdayWorking');
        $thursday_from		 =	$this->input->post('thursday_from');
        $thursday_to		 =	$this->input->post('thursday_to');
        $thursday24hours	 =	$this->input->post('thursday24hours');
        $thursdayWorking	 =	$this->input->post('thursdayWorking');
        $friday_from		 =	$this->input->post('friday_from');
        $friday_to			 =	$this->input->post('friday_to');
        $friday24hours		 =	$this->input->post('friday24hours');
        $fridayWorking		 =	$this->input->post('fridayWorking');
        $saturday_from		 =	$this->input->post('saturday_from');
        $saturday_to		 =	$this->input->post('saturday_to');
        $saturday24hours	 =	$this->input->post('saturday24hours');
        $saturdayWorking	 =	$this->input->post('saturdayWorking');


        $amenities 			 =	$this->test_input($this->input->post('amenities'));
        $is_free			 =	$this->input->post('is_free');
        $from_price			 =	$this->input->post('from_price');
        $to_price			 =	$this->input->post('to_price');

        $category_id		 =	$this->input->post('category_id');
        $sub_category_id	 =	$this->input->post('sub_category_id'); 
     	
     	$category_id2		 =	$this->input->post('category_id2');
        $sub_category_id2	 =	$this->input->post('sub_category_id2'); 

 		$category_id3		 =	$this->input->post('category_id3');
        $sub_category_id3	 =	$this->input->post('sub_category_id3'); 



	


        if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter User Id.'));
			echo json_encode($err); exit;
		}  
		 if(empty($business_full_name)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter business full name.'));
			echo json_encode($err); exit;
		} 

		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Page Id.'));
			echo json_encode($err); exit;
		} else{
			$where = array('business_page_id' => $page_id);

			$resuser=$this->Common_model->getRecords('business_page','*',$where,'',true);
      
			if($resuser['user_id']!= $user_id)
	    	{
	    		$err = array('data'=> array('status'=>'0','msg'=>'You are not authorized user'));
				echo json_encode($err);
				exit;
	    	}
		}

		if(empty($business_name)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter business name.'));
			echo json_encode($err); exit;
		}

		if(empty($category_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter category.'));
			echo json_encode($err); exit;

		}
		if(empty($sub_category_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter sub category .'));
			echo json_encode($err); exit;
		}

		$business_name = strtolower(str_replace(" ","",$business_name));

		if(isset($is_free)) {

			if($is_free==1){
				$from_price=0;
				$to_price=0;
			} else {

				if(empty($from_price)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter From Price.'));
					echo json_encode($err); exit;
				}

				if(empty($to_price)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter To Price.'));
					echo json_encode($err); exit;
				}
			}
		}
		

		$tableName="business_page";
		$where = array('business_name' => $business_name,'business_page_id !=' => $page_id,'is_deleted'=>'0');
		if($this->Common_model->getRecords($tableName,'business_page_id',$where,'',true)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Business name already used.'));
			echo json_encode($err); exit;
		}

		$tableName="users";
		$where = array('username' => $business_name,'is_deleted'=>'0');
		if($this->Common_model->getRecords($tableName,'user_id',$where,'',true)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Business name already used.'));
			echo json_encode($err); exit;
		}

		

		if(!empty($_FILES['page_photo']['name'])){
    		$photo = 0;
    		$allowed =  array('jpg','png','jpeg','JPG','PNG','JPEG');
			$filesCount = count($_FILES['page_photo']['name']);
            for($i = 0; $i <$filesCount; $i++){
				$filename = $_FILES['page_photo']['name'][$i];
			    $ext = pathinfo($filename, PATHINFO_EXTENSION);
			    if(!in_array($ext,$allowed) ) {
				   $err = array('data' =>array('status' => '0', 'msg' => 'Only jpg|jpeg|png image types allowed..'));
		   			echo json_encode($err); exit;	
			    } 
		    }
        }

  //       if(!empty($sub_category_id2))
		// {
		// 	$sub_category_id2 =  implode(',',$sub_category_id2);
		// }else
		// {
		// 	$sub_category_id2 ='';
		// }
		// if(!empty($sub_category_id3))
		// {
		// 	$sub_category_id3 =  implode(',',$sub_category_id3);
		// }else
		// {
		// 	$sub_category_id3 ='';
		// }




		$update_data = array(
          	'business_name' => $business_name,
          	'business_full_name'=> $business_full_name,
			'description' => $description,

			'category_id' => $category_id,
			'sub_category_id' => $sub_category_id,
			'category_id2' => $category_id2,
			'sub_category_id2' => $sub_category_id2,
			'category_id3' => $category_id3,
			'sub_category_id3' => $sub_category_id3,


			'website' => $page_website,
			'latitude' => $lat,
			'longitude' => $lng,
			'address_1' => $address_line1,
			'address_2' => $address_line2,
			'city_id' => $city,
			'country_id' => $country,
			'state_id' => $state,
			'zipcode' => $post_code,
			'user_id' => $user_id,
			'disply_rating' => $disply_rating,
			'email' => $email,
			'mobile' => $mobile,
			'from_price' => $from_price,
			'to_price'=> $to_price,
			'modified' => date("Y-m-d H:i:s"),
		);
		
		if($sundayWorking==1){
		  	if($sunday24hours==1){
		  		$sunday_from 		=  '';		
     			$sunday_to 			=  '';	
                $sunday24hours 		=  '1';
		  	}else{
		  		// if(!empty($sunday_from)){
		  		// 	$sunday_from 	=  date("H:i:s", strtotime(explode(',',$sunday_from)[0])).','.date("H:i:s", strtotime(explode(',',$sunday_from)[1]));	
		  		// }

		  		// if(!empty($sunday_to)){	
     	// 			$sunday_to 		=   date("H:i:s", strtotime(explode(',',$sunday_to)[0])).','.date("H:i:s", strtotime(explode(',',$sunday_to)[1]));	
     	// 		}	
     			$sunday24hours 		=  '0';
		  	}
	  	}else{
		  	$sunday_from 		=  '';		
     		$sunday_to 			=  '';			
     		$sunday24hours 		=  '0';
		}
		

		if($mondayWorking==1){
		  	if($monday24hours==1){
		  		$monday_from 		=  '';		
     			$monday_to 			=  '';	
                $monday24hours 		=  '1';
		  	}else{
		  		// if(!empty($monday_from)){
		  		// $monday_from 		=   date("H:i:s", strtotime(explode(',',$monday_from)[0])).','.date("H:i:s", strtotime(explode(',',$monday_from)[1]));	
		  		// }	
		  		// if(!empty($monday_to)){	
     	// 		$monday_to 			=   date("H:i:s", strtotime(explode(',',$monday_to)[0])).','.date("H:i:s", strtotime(explode(',',$monday_to)[1]));	
     	// 		}
     			$monday24hours 		=  '0';
		  	}
	  	}else{
		  	$monday_from 		=  '';		
     		$monday_to 			=  '';			
     		$monday24hours 		=  '0';
		}

		if($tuesdayWorking==1){
		  	if($tuesday24hours==1){
		  		$tuesday_from 		=  '';		
     			$tuesday_to 			=  '';	
                $tuesday24hours 		=  '1';
		  	}else{
		  		// if(!empty($tuesday_from)){
		  		// $tuesday_from 		=   date("H:i:s", strtotime(explode(',',$tuesday_from)[0])).','.date("H:i:s", strtotime(explode(',',$tuesday_from)[1]));	
		  		// }
		  		// if(!empty($tuesday_to)){	
     	// 		$tuesday_to 		=   date("H:i:s", strtotime(explode(',',$tuesday_to)[0])).','.date("H:i:s", strtotime(explode(',',$tuesday_to)[1]));	
     	// 		}	
     			$tuesday24hours 	=  '0';
		  	}
	  	}else{
		  	$tuesday_from 		=  '';		
     		$tuesday_to 			=  '';			
     		$tuesday24hours 		=  '0';
		}

		if($wednesdayWorking==1){
		  	if($wednesday24hours==1){
		  		$wednesday_from 		=  '';		
     			$wednesday_to 			=  '';	
                $wednesday24hours 		=  '1';
		  	}else{
		  		// if(!empty($wednesday_from)){
		  		// $wednesday_from 		=  date("H:i:s", strtotime(explode(',',$wednesday_from)[0])).','.date("H:i:s", strtotime(explode(',',$wednesday_from)[1]));	
		  		// }
		  		// if(!empty($wednesday_to)){		
     	// 		$wednesday_to 			=   date("H:i:s", strtotime(explode(',',$wednesday_to)[0])).','.date("H:i:s", strtotime(explode(',',$wednesday_to)[1]));	
     	// 		}
     			$wednesday24hours 		=  '0';
		  	}
	  	}else{
		  	$wednesday_from 		=  '';		
     		$wednesday_to 			=  '';			
     		$wednesday24hours 		=  '0';
		}

		if($thursdayWorking==1){
		  	if($thursday24hours==1){
		  		$thursday_from 		=  '';		
     			$thursday_to 			=  '';	
                $thursday24hours 		=  '1';
		  	}else{
		  		// if(!empty($thursday_from)){
		  		// $thursday_from 		=   date("H:i:s", strtotime(explode(',',$thursday_from)[0])).','.date("H:i:s", strtotime(explode(',',$thursday_from)[1]));	
		  		// }
		  		// if(!empty($thursday_to)){		
		     	// 		$thursday_to 			=   date("H:i:s", strtotime(explode(',',$thursday_to)[0])).','.date("H:i:s", strtotime(explode(',',$thursday_to)[1]));	
		     	// 		}
     			$thursday24hours 		= '0';
		  	}
	  	}else{
		  	$thursday_from 		=  '';		
     		$thursday_to 			=  '';			
     		$thursday24hours 		=  '0';
		}

		if($fridayWorking==1){
		  	if($friday24hours==1){
		  		
		  		$friday_from 		=  '';		
     			$friday_to 			=  '';	
                $friday24hours 		= '1';
		  	}else{
		  		// if(!empty($friday_from)){
		  		// $friday_from 		=   date("H:i:s", strtotime(explode(',',$friday_from)[0])).','.date("H:i:s", strtotime(explode(',',$friday_from)[1]));	
		  		// }
		  		// if(!empty($friday_to)){	
		     	// 		$friday_to 			=   date("H:i:s", strtotime(explode(',',$friday_to)[0])).','.date("H:i:s", strtotime(explode(',',$friday_to)[1]));	
		     	// 		}	
     			$friday24hours 		=  '0';
		  	}
	  	}else{
		  	$friday_from 		=  '';		
     		$friday_to 			=  '';			
     		$friday24hours 		=  '0';
		}

		if($saturdayWorking==1){
	  		if($saturday24hours==1){
		  		$saturday_from 		=  '';		
     			$saturday_to 			=  '';	
                $saturday24hours 		=  '1';
		  	}else{
		  		// if(!empty($saturday_from)){
		  		// $saturday_from 		=  date("H:i:s", strtotime(explode(',',$saturday_from)[0])).','.date("H:i:s", strtotime(explode(',',$saturday_from)[1]));	
		  		// }
		  		// if(!empty($saturday_to)){		
		     	// 		$saturday_to 		=  date("H:i:s", strtotime(explode(',',$saturday_to)[0])).','.date("H:i:s", strtotime(explode(',',$saturday_to)[1]));	
		     	// 		}	
     			$saturday24hours 	=  '0';
		  	}
	  	}else{
		  	$saturday_from 		=  '';		
     		$saturday_to 			=  '';			
     		$saturday24hours 		= '0';
		}
		  
     $update = array(
		'sunday_from' 		=>  $sunday_from,		
     	'sunday_to' 		=>  $sunday_to,			
     	'sunday24hours' 	=>  $sunday24hours,	
     	'sundayWorking' 	=>  $sundayWorking,
 		'monday_from' 		=>  $monday_from,			
     	'monday_to' 		=>  $monday_to,		
      	'monday24hours' 	=>  $monday24hours,	
      	'mondayWorking' 	=>  $mondayWorking,	
      	'tuesday_from' 		=>  $tuesday_from,			
      	'tuesday_to' 		=>  $tuesday_to,			 
      	'tuesday24hours'	=>  $tuesday24hours,
      	'tuesdayWorking' 	=>  $tuesdayWorking,		 
      	'wednesday_from'	=>  $wednesday_from,				
      	'wednesday_to' 		=>  $wednesday_to,			
      	'wednesday24hours'	=>  $wednesday24hours,
      	'wednesdayWorking' 	=>  $wednesdayWorking,			 
      	'thursday_from' 	=>  $thursday_from,			
      	'thursday_to' 		=>  $thursday_to,		
      	'thursday24hours' 	=>  $thursday24hours,
      	'thursdayWorking' 	=>  $thursdayWorking,			 
      	'friday_from' 		=>  $friday_from,				
      	'friday_to' 		=>  $friday_to,			
      	'friday24hours' 	=> 	$friday24hours,
      	'fridayWorking' 	=>  $fridayWorking,			 
       	'saturday_from'		=> 	$saturday_from,				
      	'saturday_to' 		=> 	$saturday_to,			 
       	'saturday24hours' 	=> 	$saturday24hours,
       	'saturdayWorking' 	=>  $saturdayWorking	
       	);

     	$where = array('business_page_id' => $page_id);

     	if(!empty($_FILES['page_photo'])) {
			$filesCount = count($_FILES['page_photo']['name']);
			
			
				for($i = 0; $i <$filesCount; $i++){
					$_FILES['page_img']['name'] = $_FILES['page_photo']['name'][$i];
					$_FILES['page_img']['type'] = $_FILES['page_photo']['type'][$i];
					$_FILES['page_img']['tmp_name'] = $_FILES['page_photo']['tmp_name'][$i];
					$_FILES['page_img']['error'] = $_FILES['page_photo']['error'][$i];
					$_FILES['page_img']['size'] = $_FILES['page_photo']['size'][$i];
					
					//Rename image name 
					$img = time().'_'.rand();

					$config['upload_path'] = 'resources/images/page/';
					//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
					$config['allowed_types'] = '*';
					$config['file_name'] =  $img;
		
					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if($this->upload->do_upload('page_img')){
						$fileData = $this->upload->data();
						$uploadData = array();
						$uploadData= array(
							'file_path' => 'resources/images/page/'.$config['file_name'].$fileData['file_ext'],
							'business_page_id' =>  $page_id,
							'created' => date("Y-m-d H:i:s"),
							
						);

						$this->Common_model->addEditRecords('business_img', $uploadData);
					} else {
						$error = array('error' => $this->upload->display_errors());
                        $err = array('data' =>array('status' => '0', 'msg' => $error ));
						echo json_encode($err); exit;
					}
				} 
		}

		$resuser=$this->Common_model->getRecords('working_hours','*',$where,'',true);

		$bpa = $this->App_model->business_page_amenity($page_id);
		
		if($amenities) {
			$amenities_data = explode(",",$amenities);
			$reserved = array();
	   	    if(!empty($bpa)) {
		   	    foreach ($bpa as $list) {
		   	    	if(in_array($list['amenity_id'], $amenities_data)){
		            	$reserved[] = $list['amenity_id'];
		   	    	} else {
		         		//Delete tagged user
		         		$where = array('page_amenity_id'=>$list['page_amenity_id']);
						$this->Common_model->deleteRecords('business_page_amenity',$where);
		   	    	}
		   	    }
		   	}

	   	    foreach ($amenities_data as $amenity_id) {
	   	    	if(!in_array($amenity_id, $reserved)){
	            	$addtag = array(
						'page_id' => $page_id,
						'amenity_id' => $amenity_id,
						'created' => date("Y-m-d H:i:s")
					); 
		            $this->Common_model->addEditRecords('business_page_amenity', $addtag);
	   	    	} 
	   	    }
		} else {
			if(!empty($bpa)) {
		   	    foreach ($bpa as $list) {
		   	    	$where = array('page_id' => $page_id);
					$this->Common_model->deleteRecords('business_page_amenity',$where);
		   	    }
		   	}
		}
        
        if(!empty($resuser)){
	        $where = array('business_page_id' => $page_id);
	        $update['modified'] = date("Y-m-d H:i:s");
	        $this->Common_model->addEditRecords('working_hours', $update,$where);

			
        }else{
        	$update['business_page_id'] = $page_id;
        	$update['created'] = date("Y-m-d H:i:s");
        	$this->Common_model->addEditRecords('working_hours', $update);
        }
        $where = array('business_page_id' => $page_id);
      	if(!$this->Common_model->addEditRecords('business_page', $update_data,$where)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>'page edit successfully'));
			echo json_encode($response); exit;
		}


	}

	 public function reportUser() {
    	$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id'));
        $report_user_id =	$this->test_input($this->input->post('report_user_id'));

        if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}

		if(empty($report_user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Report User Id.'));
			echo json_encode($err); exit;
		}

        $report_detail =	$this->test_input($this->input->post('report_detail'));
        $It_Spam =	$this->test_input($this->input->post('it_spam'));
        $It_Inappropriate =	$this->test_input($this->input->post('it_inappropriate'));

        $update_data = array('report_user_id' => $report_user_id,'user_id' => $user_id, 'report_detail' => $report_detail, 'It_Spam' => $It_Spam , 'It_Inappropriate' => $It_Inappropriate,  'created' => date("Y-m-d H:i:s"));
             		
        if($this->Common_model->addEditRecords('report_user',$update_data)){
        	$response = array('data'=> array('status'=>'1','msg'=>'Thank you for your report'));
			echo json_encode($response); exit;
        }else{
        	$response = array('data'=> array('status'=>'0','msg'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
        }

    }

    public function getAmenities()
	{   $where = array('status'=>'Active');
		$tableName = 'amenities';
		if(!$amenities = $this->Common_model->getRecords($tableName,'amenity_id,name,icon_image',$where,'amenity_id Desc',false)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>' amenities found successfully','details'=>$amenities));
			echo json_encode($response); exit;
		}
	}

	  public function deletepage() {
    	$this->check_login();
        $page_id =	$this->test_input($this->input->post('page_id')); 

        if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Page Id.'));
			echo json_encode($err); exit;
		}else{
			$user_id	=  $this->input->post('user_id');  
			$where = array('business_page_id' => $page_id);

			$resuser=$this->Common_model->getRecords('business_page','*',$where,'',true);

			if($resuser['user_id']!= $user_id)
	    	{
	    		$err = array('data'=> array('status'=>'0','msg'=>'You are not authorized user'));
				echo json_encode($err);
				exit;
	    	}
        }
        $update_data = array('business_page_id' => $page_id);
        $array = array('is_deleted' => '1','deleted_by' => 'user'); 
        $arr = array('is_deleted' => '1'); 
        $this->Common_model->addEditRecords('business_offers',$arr,$update_data);
        if($this->Common_model->addEditRecords('business_page',$array,$update_data)){

        	$where = array('user_id' => $resuser['user_id']);
			$resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);

			if($resiver['notification']=='Yes'){
					    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
					    $where = array('user_id' =>$resuser['user_id']);
						$count=$this->Common_model->getRecords('users','badge_count',$where,'',true); 
					    $iosarray = array(
		                    'alert' =>'The '.$resuser['business_name'].' is deleted successfully.',
		                    'type'  => 'deletepage',
		                    'badge' => $count['badge_count'],
		                    'sound' => 'default',
               			);

						$andarray = array(
			                'message'   => 'The '.$resuser['business_name'].' is deleted successfully.',
			                'type'      =>'deletepage',
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
					   $savearray = '';
					    $add_data =array('user_id' =>$user_id,'created_by' =>$user_id,'type'=>'deletepage', 'notification_title'=>'delete page', 'notification_description'=>'The '.$resuser['business_name'].' is deleted successfully.', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
		        		$this->Common_model->addEditRecords('notifications',$add_data); 
		        	}		

        	$response = array('data'=> array('status'=>'1','msg'=>'Page Deleted'));
			echo json_encode($response); exit;
        }else {
        	$response = array('data'=> array('status'=>'0','msg'=>'Some error occured. Please try again !!.'));
			echo json_encode($response); exit;
		}
	}

	public function deletePageImage(){
		$this->check_login();
        $business_img_id =	$this->test_input($this->input->post('business_img_id')); 

        if(empty($business_img_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter business image Id.'));
			echo json_encode($err); exit;
		}

		$where = array('business_img_id' =>$business_img_id);
		$image_data=$this->Common_model->getRecords('business_img','file_path',$where,'',true); 
		
		//Delete post image
		$this->Common_model->deleteRecords('business_img',$where);
      
      	if(!empty($image_data)){
			if(!empty($image_data['file_path'])){
				unlink($image_data['file_path']);
			}
			
        	$response = array('data'=> array('status'=>'1','msg'=>'Delete Image'));
			echo json_encode($response); exit;
        }else {
        	$response = array('data'=> array('status'=>'0','msg'=>'Some error occured. Please try again !!.'));

			echo json_encode($response); exit;
		}
	}

	public function AddStarImage(){
		$this->check_login();
        $business_img_id =	$this->test_input($this->input->post('business_img_id')); 
        $page_id =	$this->test_input($this->input->post('page_id')); 
        if(!empty($business_img_id)){
			
			 $list = explode(",",$business_img_id);
		}else {
			$list = array();
		}
      
	
		if(count($list)>5){
			$err = array('data' =>array('status' => '0', 'msg' => 'you can select at most 5 images.'));
			echo json_encode($err); exit;
		}
        if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Page Id.'));
			echo json_encode($err); exit;
		}else{
			$user_id	=  $this->input->post('user_id');  
			$where = array('business_page_id' => $page_id);

			$resuser=$this->Common_model->getRecords('business_page','*',$where,'',true);

			if($resuser['user_id']!= $user_id)
	    	{
	    		$err = array('data'=> array('status'=>'0','msg'=>'You are not authorized user'));
				echo json_encode($err);
				exit;
	    	}
        }
			$update_data = array('is_star'=>'No');
        	$where = array('business_page_id' => $page_id);
		    $res=$this->Common_model->addEditRecords('business_img',$update_data,$where);
        foreach($list as $row){
        	$update_data = array('is_star'=>'Yes');
        	$where = array('business_img_id' => $row);
		    $resdevice=$this->Common_model->addEditRecords('business_img',$update_data,$where);
		}

		
			$suc = array('data' =>array('status' => '1', 'msg' => 'updated successfully.'));
            echo json_encode($suc); exit;
		

        

	}

	public function getFollowingRequestList()
	{

		$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id')); 
        $select_panding = array();
        $get_panding_list = array();
        $new_array = array();
        $select_panding = $this->App_model->panding_details($user_id);
       	$get_panding_list = $this->App_model->get_panding_list($user_id);

       	$new_array = array_merge($select_panding,$get_panding_list);
   		$new_array = multid_sort($new_array,'created','Desc');
      //  echo "<pre>";print_r($new_array);die;
		//$data = multid_sort($final_arr,$sorting,$order);

       	if($new_array){
        $response = array('data'=> array('status'=>'1','msg'=>'list','details'=>$new_array));
			echo json_encode($response); exit;
		}else
		{
			$response = array('data'=> array('status'=>'0','msg'=>'record not found'));
			echo json_encode($response); exit;
		}
	}

	public function acceptRejectFolloRequest()
	{
		$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id')); 
        $follower_id =	$this->test_input($this->input->post('requested_user_id')); 
        $action =	$this->test_input($this->input->post('status')); 

        if(empty($follower_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter Requested id.'));
			echo json_encode($err); exit;
		}
		if(empty($action)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter status.'));
			echo json_encode($err); exit;
		}
		if($action == 'accept') 
		{
			$accept = $this->App_model->request_action($user_id,$follower_id,$action);

			/******************************* delete chat request *****************************/
			$this->Common_model->deleteRecords('chating_request',array('sender_user' =>$follower_id,'receiver_user' =>$user_id,'status'=>'0'));

			/******************************* delete chat request *****************************/

	        $response = array('data'=> array('status'=>'1','msg'=>'accpeted'));
			echo json_encode($response); exit;
		}elseif ($action == 'reject') {

			$accept = $this->App_model->request_action($user_id,$follower_id,$action);
			$response = array('data'=> array('status'=>'1','msg'=>'canceled'));
			echo json_encode($response); exit;
		}else
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'status must be accept or reject.'));
			echo json_encode($err); exit;
		}

	}

	public function myRequestUserList()
	{
		$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id')); 
        $select_panding = array();
        $get_requested_chat_list = array();
        $new_array = array();
        $select_panding = $this->App_model->sender_pending_request($user_id);
        $get_requested_chat_list = $this->App_model->get_requested_chat_list($user_id);

     	$new_array = array_merge($select_panding,$get_requested_chat_list);
     
     	$new_array = multid_sort($new_array,'created','Desc');
     		  // echo "<pre>";print_r($new_array);die;
     	if($new_array){
	        $response = array('data'=> array('status'=>'1','msg'=>'list','details'=>$new_array));
			echo json_encode($response); exit;
		}else
		{
			$response = array('data'=> array('status'=>'0','msg'=>'record not found'));
			echo json_encode($response); exit;
		}
	}
	

	public function setUpPin()
	{
		$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id')); 
        $user_pin =	$this->test_input($this->input->post('pin')); 
        if(empty($user_pin)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter pin.'));
			echo json_encode($err); exit;
		}else
		{

			if(is_numeric($user_pin))
			{
				$user_pin = str_pad($user_pin, 4, '0', STR_PAD_LEFT);

				$prekey= $this->Common_model->getRecords('users','user_id',array("user_id"=>$user_id,'user_pin'=>'0'),'',true);  
				if(!empty($prekey))
				{
					if(strlen($user_pin)==4)
					{
						$select_panding = $this->App_model->add_update_key($user_id,$user_pin);
						$response = array('data'=> array('status'=>'1','msg'=>'Pin created.'));
						echo json_encode($response); exit;

					}else
					{
						$response = array('data'=> array('status'=>'0','msg'=>'Please enter 4 digits.'));
						echo json_encode($response); exit;
					}
				}else {
			 		$response = array('data'=> array('status'=>'0','msg'=>'You are already created pin.'));
					echo json_encode($response); exit;
				}	
			}else
			{
				$response = array('data'=> array('status'=>'0','msg'=>'Please enter numeric digits.'));
				echo json_encode($response); exit;
			}
		}
		

	}


	public function myPagesList()
	{
		$this->check_login();
        $user_id =	$this->test_input($this->input->post('user_id'));
     	if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}

		$get_pages = $this->App_model->get_business_page($user_id);
		if($get_pages)
		{ 
			$response = array('data'=> array('status'=>'1','msg'=>'page list','details'=>$get_pages));
			echo json_encode($response); exit;
		}else
		{
			$response = array('data'=> array('status'=>'0','msg'=>'No page found.'));
			echo json_encode($response); exit;
		}
	}
  
	 
 
  


	 
  

	 

  


	public function createOffers()
	{

		$this->check_login();
		$user_id  =	$this->test_input($this->input->post('user_id'));
		$page_id  =	$this->test_input($this->input->post('page_id'));
		$offer_type  =	$this->test_input($this->input->post('offer_type'));
		$title  =	$this->test_input($this->input->post('title'));
		$description  =	$this->test_input($this->input->post('description'));
		$country_id  =	$this->test_input($this->input->post('country_id'));
		$state_id  =	$this->test_input($this->input->post('state_id'));
		$city_id  =	$this->test_input($this->input->post('city_id'));
		$offer_end_date  =	$this->test_input($this->input->post('offer_end_date'));
		$offer_days  =	$this->test_input($this->input->post('offer_days'));
		$offer_months  =	$this->test_input($this->input->post('offer_months'));
		$notification  =	$this->test_input($this->input->post('notification'));
		
 
		$offer_image  =	$this->test_input($this->input->post('offer_image'));
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
 		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
			echo json_encode($err); exit;
		}
		$get_plan_info = $this->App_model->subscription_user_lists($page_id); 
		// echo $this->db->last_query();die;
		// echo "<pre>";print_r($get_plan_info);die;
    	if(empty($get_plan_info))
    	{
    		$err = array('data' =>array('status' => '0', 'msg' => "You don't Have any Subscription plan For This Page."));
			echo json_encode($err); exit;
    	}

	 	
		if(empty($offer_type)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offers type.'));
			echo json_encode($err); exit;
		}elseif ($offer_type=='1') {

			$offers_type = 'multi_buy';

				$discount_type  =	$this->test_input($this->input->post('discount_type'));
				$buy  =	$this->test_input($this->input->post('buy'));
				$buy_text  =	$this->test_input($this->input->post('buy_text'));
				$get  =	$this->test_input($this->input->post('get'));
				$get_text  =	$this->test_input($this->input->post('get_text'));
				$note  =	$this->test_input($this->input->post('note'));
				$tandc  =	$this->test_input($this->input->post('term_and_condition'));

				if(empty($discount_type)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter discount type.'));
					echo json_encode($err); exit;
				}
				if($discount_type=='free')
				{
					if(empty($buy)){
						$err = array('data' =>array('status' => '0', 'msg' => 'Please enter buy.'));
						echo json_encode($err); exit;
					}
					// if(empty($buy_text)){
					// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter buy product name.'));
					// 	echo json_encode($err); exit;
					// }
					if(empty($get)){
						$err = array('data' =>array('status' => '0', 'msg' => 'Please enter get.'));
						echo json_encode($err); exit;
					}
					// if(empty($get_text)){
					// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter get product name.'));
					// 	echo json_encode($err); exit;
					// }
				}
				if($discount_type=='discount')
				{
					if(empty($get)){
						$err = array('data' =>array('status' => '0', 'msg' => 'Please enter get.'));
						echo json_encode($err); exit;
					}
					// if(empty($get_text)){
					// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter get product name.'));
					// 	echo json_encode($err); exit;
					// }
				}
				// if(empty($note)){
				// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter note.'));
				// 	echo json_encode($err); exit;
				// }
				/*if(empty($tandc)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter term and condition.'));
					echo json_encode($err); exit;
				}  */

			
		}elseif ($offer_type=='2') { 
				$offers_type = 'standard_discount'; 
				$discount_type  =	$this->test_input($this->input->post('discount_type'));
				$discount_value  =	$this->test_input($this->input->post('discount_value'));
				// $product_note  =	$this->test_input($this->input->post('product_note'));
				$product_name  =	$this->test_input($this->input->post('product_name'));
				$product_description  =	$this->test_input($this->input->post('product_description'));
				$tandc  =	$this->test_input($this->input->post('term_and_condition'));

				if(empty($discount_type)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter discount type.'));
					echo json_encode($err); exit;
				}
				if(empty($discount_value)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter discount value.'));
					echo json_encode($err); exit;
				}
				// if(empty($product_note)){
				// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter product note.'));
				// 	echo json_encode($err); exit;
				// }
				// if(empty($product_note)){
				// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter product name.'));
				// 	echo json_encode($err); exit;
				// }
				if(empty($product_description)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter product description.'));
					echo json_encode($err); exit;
				} 
			/*	if(empty($tandc)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter term and condition.'));
					echo json_encode($err); exit;
				} */
		}else
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'offer type must be 1 or 2.'));
			echo json_encode($err); exit;
		}
		 
		// if(empty($title)){
		// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter title.'));
		// 	echo json_encode($err); exit;
		// }
 	// 	if(empty($description)){
		// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter description.'));
		// 	echo json_encode($err); exit;
		// }
		if(!empty($offer_end_date)){
		    $offer_end_dates = date('Y-m-d', strtotime($offer_end_date));
		}else
		{
			 $offer_end_dates= '';
		}
	
		if(empty($offer_days)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offers days.'));
			echo json_encode($err); exit;
		}
	
    	
	    	$get_iold_offer_count = $this->Common_model->getRecords('business_offers','business_offers',array("business_page_id"=>$page_id,'is_deleted'=>'0'),'',false);  
	 		$to =0;
			$get_iold = $this->Common_model->getRecords('offer_purchase','offer',array("page_id"=>$page_id,'end_date >='=> date('Y-m-d')),'',false);  
	    	if(!empty($get_iold)){
	    		foreach ($get_iold as $key) {
	    			$to +=	$key['offer'];
	    		}
	    	}
	    	
	    	if(count($get_iold_offer_count) >=$get_plan_info['offers']+$to)
	    	{ 
	    		$newvar = $get_plan_info['offers']+$to;
	    		$err = array('data' =>array('status' => '0', 'msg' => 'You do not create offer more then '.$newvar." For This Page."));
				echo json_encode($err); exit;
	    	}


	    if($notification =='yes')
		{
			$notification_title  =	$this->test_input($this->input->post('notification_title'));
			$notification_description  =	$this->test_input($this->input->post('notification_description'));

		 	if(empty($notification_title)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter notification title.'));
				echo json_encode($err); exit;
			}	
			if(empty($notification_description)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter notification description.'));
				echo json_encode($err); exit;
			}
			if(!empty($_FILES['notification_images']))
			{	
		
		    	$photo = 0;
				$allowed =  array('jpg','png','jpeg','JPG','PNG','JPEG');
				$filesCount = count($_FILES['notification_images']['name']);
		        for($i = 0; $i <$filesCount; $i++){
					$filename = $_FILES['notification_images']['name'][$i];
				    $ext = pathinfo($filename, PATHINFO_EXTENSION);
				    if(!in_array($ext,$allowed) ) {
					   $err = array('data' =>array('status' => '0', 'msg' => 'Only jpg|jpeg|png image types allowed..'));
			   			echo json_encode($err); exit;	
				    } 
			    }
		    }
		}
	 
		$get_page_exp_date = $this->Common_model->getRecords('subscription_user','end_date',array("page_id"=>$page_id,'is_deleted'=>'0'),'',true); 
		
		if($offer_end_dates=='')
		{
			if(!empty($get_page_exp_date))
			{
				$page_en_date = $get_page_exp_date['end_date']; 
			}else
			{
				$page_en_date='';
			}
			
		}else{
			$page_en_date = $offer_end_dates; 
		}
		$get_sort = $this->Common_model->getRecords('business_offers','business_offers',array("business_page_id"=>$page_id),'',false);  
		$sort= count($get_sort);
	    $data = array(
					'business_page_id'=>$page_id,
					'created_by_user'=>$user_id,
					'offers_type'=>$offers_type,
					'offers_title'=>$title,
					'description'=>$description,
					'country_id'=>$country_id,
					'state_id'=>$state_id,
					'city_id'=>$city_id,
					'exp_date'=>$offer_end_dates,
					'days'=>$offer_days,
					'months'=>$offer_months,
					'page_expired_date'=>$page_en_date,
					'notification_msg'=>$notification,
					'sort'=>$sort+1,
					'created'=>date("Y-m-d H:i:s"),
	     );

	    $offer_id = $this->Common_model->addEditRecords('business_offers',$data);
 
	    if(!empty($_FILES['offer_images'])) {
			$filesCount = count($_FILES['offer_images']['name']);
			for($i = 0; $i <$filesCount; $i++){
				$_FILES['page_img']['name'] = $_FILES['offer_images']['name'][$i];
				$_FILES['page_img']['type'] = $_FILES['offer_images']['type'][$i];
				$_FILES['page_img']['tmp_name'] = $_FILES['offer_images']['tmp_name'][$i];
				$_FILES['page_img']['error'] = $_FILES['offer_images']['error'][$i];
				$_FILES['page_img']['size'] = $_FILES['offer_images']['size'][$i];
				
				//Rename image name 
				$img = time().'_'.rand();

				$config['upload_path'] = 'resources/images/offers/';
				//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
				$config['allowed_types'] = '*';
				$config['file_name'] =  $img;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if($this->upload->do_upload('page_img')){
					$fileData = $this->upload->data();
					$uploadData = array();
					$uploadData= array(
						'file_path' => 'resources/images/offers/'.$config['file_name'].$fileData['file_ext'],
						'offer_id' =>  $offer_id ,
						'created' => date("Y-m-d H:i:s"),
						);
				 	$this->Common_model->addEditRecords('offers_images', $uploadData);
				} else {
					$error = array('error' => $this->upload->display_errors());
	                $err = array('data' =>array('status' => '0', 'msg' => $error ));
					echo json_encode($err); exit;
				}
			} 
		}


		if($offer_type=='1')
		{
			$offer_data = array(
				'user_id'=>$user_id,
				'page_id'=>$page_id,
				'business_offers_id'=>$offer_id,
				'discount_type'=>$discount_type,
				'buy'=>$buy,
				'buy_text'=>$buy_text,
				'get'=>$get,
				'get_text'=>$get_text,
				'note'=>$note,
				'tandc'=>$tandc,
				'created'=>date("Y-m-d H:i:s")
			);
			$offer_type=	$this->Common_model->addEditRecords('multi_buy',$offer_data);
			

		}
	 
		if($offer_type=='2')
		{
			$offer_data = array(
				'user_id'=>$user_id,
				'page_id'=>$page_id,
				'business_offers_id'=>$offer_id,
				'discount_type'=>$discount_type,
				'discount_value'=>$discount_value,
				// 'product_note'=>$product_note,
				'product_name'=>$product_name,
				'product_description'=>$product_description,
				'tandc'=>$tandc,
				'created'=>date("Y-m-d H:i:s")
			);
			$offer_type=	$this->Common_model->addEditRecords('standard_discount',$offer_data);

		}
		

		if($notification =='yes')
		{ 
		 	if(!empty($_FILES['notification_images'])) {
				$filesCount = count($_FILES['notification_images']['name']);
				for($i = 0; $i <$filesCount; $i++){
					$_FILES['page_img']['name'] = $_FILES['notification_images']['name'][$i];
					$_FILES['page_img']['type'] = $_FILES['notification_images']['type'][$i];
					$_FILES['page_img']['tmp_name'] = $_FILES['notification_images']['tmp_name'][$i];
					$_FILES['page_img']['error'] = $_FILES['notification_images']['error'][$i];
					$_FILES['page_img']['size'] = $_FILES['notification_images']['size'][$i];
					
					//Rename image name 
					$img = time().'_'.rand();

					$config['upload_path'] = 'resources/images/offer_notification_image/';
					//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
					$config['allowed_types'] = '*';
					$config['file_name'] =  $img;

					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if($this->upload->do_upload('page_img')){
						$fileData = $this->upload->data();
						$image_path = 'resources/images/offer_notification_image/'.$config['file_name'].$fileData['file_ext'];
						
					} else {
						$error = array('error' => $this->upload->display_errors());
		                $err = array('data' =>array('status' => '0', 'msg' => $error ));
						echo json_encode($err); exit;
					}
				} 
			}else
			{
				$image_path='';
			}

					$uploadData = array();
					$uploadData= array(
						'user_id' => $user_id,
						'page_id' => $page_id,
						'offer_id' => $offer_id ,
						'country_id' => $country_id,
						'state_id' => $state_id,
						'city_id' => $city_id,
						'notification_title' => $notification_title,
						'notification_description' => $notification_description,
						'notification_image' =>$image_path,						
						'created' => date("Y-m-d H:i:s")
						);
				 	$this->Common_model->addEditRecords('offers_notification', $uploadData);

		}
 

 

		if($offer_id)
		{    

            $this->sendnotification($offer_id,$user_id,$page_id);

			$response = array('data'=> array('status'=>'1','msg'=>'Offer created'));
			echo json_encode($response); exit;
        } else {
			$err = array('data' =>array('status' => '0', 'msg' => 'Please try again !!'));
			echo json_encode($err); exit;
		}
 
	}

    public function sendnotification($offer_id,$user_id,$page_id)
	{	
		$where_page_1 = array('settings_id'=>'1');
		$settings = $this->Common_model->getRecords('settings','value',$where_page_1,"", true);
		$pointscut	=	$settings['value'];
		$where_type = array("user_id"=>$user_id);
		$where = array("business_offers"=>$offer_id);
  		$get_point = $this->Common_model->getRecords('points','point',$where_type,'',true);	
  		if($get_point['point']>=$pointscut){
  			$offers = $this->Common_model->getRecords('business_offers','*',$where,'',true);
  			if($offers['notification_msg']=='yes'){
  					$where = array('user_id' => $user_id);
			$getprepoint=$this->Common_model->getRecords('points','*',$where,'',true);
			if(!empty($getprepoint))
			{
			 	$toal_point = 	$getprepoint['point']-$pointscut;
		  		$this->Common_model->addEditRecords('points',array('point'=>$toal_point),array('user_id'=>$user_id,'points_id'=>$getprepoint['points_id']));
		  		 $data = array(
                'user_id' => $user_id,
                'point' => $pointscut,
                'message' => 'Notifications sent (Points deducted)',
                'type' => 2,
                'created' => date('Y-m-d H:i:s')
            ); 
            $response = $this->Common_model->addEditRecords('points_manage', $data);
			}

  			$whereuser = array("country_id !="=>0,"state_id !="=>0,"city_id !="=>0,'adv_notification'=>'Yes','user_id !='=>$user_id);	
  		    $users = $this->Common_model->getRecords('users','user_id,country_id,city_id,state_id',$whereuser,'',false);
  		    $where1 = array("offer_id"=>$offer_id);
  			$notification = $this->Common_model->getRecords('offers_notification','*',$where1,'',true);

			foreach ($users as $user ) {
				if(!empty($offers['city_id']))
	 				{
						$city = explode(',',$offers['city_id']);
		 				foreach ($city as $cid) {
			 				 if($user['city_id'] == $cid) {
		 				 		$where = array('user_id' =>$user['user_id']);
		 				 		
								$count=$this->Common_model->getRecords('users','badge_count',$where,'',true); 
		 				 		$log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
 				 		 		$iosarray = array(
			                    'alert' => $notification['notification_description'],
			                    'type'  => 'notification',
			                  	'page_id'=> $page_id,
			                   	'offer_id'=> $offer_id,
			                   	'badge' => $count['badge_count'],
			                    'sound' => 'default',
			       				);

							$andarray = array(
				                'message'   =>  $notification['notification_description'],
				                'type'      =>'notification',
			                	'offer_id'=> $offer_id,
			                	'page_id'=> $page_id,
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
						    	$savearray = 'offer_id-'.$offer_id.'@page_id-'.$page_id;
						    	$add_data =array('offer_id'=>$offer_id,'user_id' => $user['user_id'] ,'created_by' =>$user_id,'type'=>'notification', 'notification_title'=>$notification['notification_title'], 'notification_description'=>$notification['notification_description'], 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
	    						$this->Common_model->addEditRecords('notifications',$add_data); 	
			   					 }
			   
			  
			 				 }
		 				}
		 			}elseif (!empty($offers['state_id'])) {
	 					$state = explode(',',$offers['state_id']);
		 				foreach ($state as $sid) {
			 				 if($user['state_id'] == $sid) {
			 				$where = array('user_id' =>$user['user_id']);
			 				$count=$this->Common_model->getRecords('users','badge_count',$where,'',true); 
		 				 		$log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
 				 		 		$iosarray = array(
			                    'alert' => $notification['notification_description'],
			                    'type'  => 'notification',
			                    'page_id'=> $page_id,
			                   	'offer_id'=> $offer_id,
			                   	'badge' =>  $count['badge_count'],
			                    'sound' => 'default',
			       				);

							$andarray = array(
				                'message'   =>  $notification['notification_description'],
				                'type'      =>'notification',
				                'page_id'=> $page_id,
			                	'offer_id'=> $offer_id,
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
						    	$savearray = 'offer_id-'.$offer_id.'@page_id-'.$page_id;
						    	$add_data =array('offer_id' => $offer_id,'user_id' => $user['user_id'] ,'created_by' =>$user_id,'type'=>'notification', 'notification_title'=>$notification['notification_title'], 'notification_description'=>$notification['notification_description'], 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
	    						$this->Common_model->addEditRecords('notifications',$add_data); 	
			   					 }
			   
			 				 }
		 				}
		 			}elseif (!empty($offers['country_id'])) {
		 				$country = explode(',',$offers['country_id']);
		 				foreach ($country as $cid) {
			 				 if($user['country_id'] == $cid) {
			 			$where = array('user_id' =>$user['user_id']);
			 			$count=$this->Common_model->getRecords('users','badge_count',$where,'',true); 
		 				 		$log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
 				 		 		$iosarray = array(
			                    'alert' => $notification['notification_description'],
			                    'type'  => 'notification',
			                    'page_id'=> $page_id,
			                   	'offer_id'=> $offer_id,
			                   	'badge' =>  $count['badge_count'],
			                    'sound' => 'default',
			       				);

							$andarray = array(
				                'message'   =>  $notification['notification_description'],
				                'type'      =>'notification',
				                'page_id'=> $page_id,
			                	'offer_id'=> $offer_id,
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
						    		$savearray = 'offer_id-'.$offer_id.'@page_id-'.$page_id;
						    	$add_data =array('offer_id'=>$offer_id,'user_id' => $user['user_id'] ,'created_by' =>$user_id,'type'=>'notification', 'notification_title'=>$notification['notification_title'], 'notification_description'=>$notification['notification_description'], 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
	    						$this->Common_model->addEditRecords('notifications',$add_data); 	
			   					 }
			   
			 				 }
		 				}
		 			}else
		 			{
	                 $where = array('user_id' =>$user['user_id']);
	                 $count=$this->Common_model->getRecords('users','badge_count',$where,'',true); 
		 				 		$log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
 				 		 		$iosarray = array(
			                    'alert' => $notification['notification_description'],
			                    'type'  => 'notification',
			                    'page_id'=> $page_id,
			                   	'offer_id'=> $offer_id,
			                   	'badge' =>  $count['badge_count'],
			                    'sound' => 'default',
			       				);

							$andarray = array(
				                'message'   =>  $notification['notification_description'],
				                'type'      =>'notification',
				                'page_id'=> $page_id,
			                	'offer_id'=> $offer_id,
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
						    		$savearray = 'offer_id-'.$offer_id.'@page_id-'.$page_id;	
						    	$add_data =array('offer_id'=>$offer_id,'user_id' => $user['user_id'] ,'created_by' =>$user_id,'type'=>'notification', 'notification_title'=>$notification['notification_title'], 'notification_description'=>$notification['notification_description'], 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
	    						$this->Common_model->addEditRecords('notifications',$add_data); 	
			   					 }
			   
		 			}

				}			


  			}

  			
  		}

	}

 


	public function getOfferDetails()
	{
		$this->check_login();
		$user_id  =	$this->test_input($this->input->post('user_id'));
		$page_id  =	$this->test_input($this->input->post('page_id'));
		$offer_id  =	$this->test_input($this->input->post('offer_id'));

		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
			echo json_encode($err); exit;
		}
		if(empty($offer_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offer id.'));
			echo json_encode($err); exit;
		}
		$where_offer = array("business_offers"=>$offer_id,"business_page_id"=>$page_id,"is_deleted"=>'0');
		$get_offer= $this->Common_model->getRecords('business_offers','*',$where_offer,'',true);  
		if(!$get_offer)
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Offers not found !!'));
			echo json_encode($err); exit;
		}


		$page_name = array("business_page_id"=>$page_id);
		$page_names= $this->Common_model->getRecords('business_page','business_name,business_full_name',$page_name,'',true);  
		if(!empty($page_names['business_name'])){
			$get_offer['business_name'] = $page_names['business_full_name'];
		}else
		{
			$get_offer['business_name']='';
		}
		if(!empty($get_offer['country_id'])){
			$county = explode(',', $get_offer['country_id']);
			foreach ($county as $country_id) {
				$where_country= array("id"=>$country_id);
				$get_offer['country'][]= $this->Common_model->getRecords('countries','id,name,phonecode',$where_country,'',true);  
			}
		}else{
			$get_offer['country']=array();
		}

		if(!empty( $get_offer['state_id']) &&  $get_offer['state_id']!='null' ){	

			$state = explode(',', $get_offer['state_id']);
			
			foreach ($state as $state_id) {
				$where_state= array("id"=>$state_id);
				$get_offer['state'][]= $this->Common_model->getRecords('states','id,name',$where_state,'',true);
				  // echo $this->db->last_query();die;
			}
		}else{
			$get_offer['state']=array();
		}

		if(!empty($get_offer['city_id'])){
			$city = explode(',', $get_offer['city_id']);
			foreach ($city as $city_id) {
				$where_city= array("id"=>$city_id);
				$get_offer['city'][]= $this->Common_model->getRecords('cities','id,name',$where_city,'',true);  
			}
		}else
		{
			$get_offer['city']=array();
		}


		$offer_image= array("offer_id"=>$get_offer['business_offers']);
		$get_offer['offer_image'] = $this->Common_model->getRecords('offers_images','id,file_path',$offer_image,'',false);  
	 	if($get_offer['offers_type'] =='multi_buy')
		{
	
			$where_type= array("business_offers_id"=>$get_offer['business_offers']);
		 	$get_offer['multi_buy']= $this->Common_model->getRecords('multi_buy','discount_type,buy,buy_text,get,get_text,note,tandc as term_and_condition',$where_type,'',true);  
		}else
		{
			$get_offer['multi_buy']='';
		} 
		if($get_offer['offers_type'] =='standard_discount')
		{
			$where_type= array("business_offers_id"=>$get_offer['business_offers']);
		 	$get_offer['standard_discount']= $this->Common_model->getRecords('standard_discount','discount_type,discount_value,product_note,product_name,product_description,tandc as term_and_condition',$where_type,'',true);  
		}else
		{
			$get_offer['standard_discount']='';
		}

		if($get_offer['notification_msg']=='yes')
		{
			$where_notification= array("offer_id"=>$get_offer['business_offers']);
			$offer_de = $this->Common_model->getRecords('offers_notification','notification_title,notification_description,notification_image,',$where_notification,'',true);  
	 		if(!empty($offer_de)){
	 			$get_offer['offers_notification']= $offer_de;
	 		}else
	 		{
	 			$get_offer['offers_notification']['notification_title']='';
	 			$get_offer['offers_notification']['notification_description']='';
	 			$get_offer['offers_notification']['notification_image']='';
	 		}
	 		
		}else
		{
			$get_offer['offers_notification']='';
		}

		$points = array();
		$card = array();
		$username = array();

		$where_points  =  array('user_id' => $user_id);
		$points= $this->Common_model->getRecords('points','point',$where_points,'',true); 
		$where_card  =  array('page_id' => $page_id,'is_deleted'=>'0');
 		$cards= $this->Common_model->getRecords('subscription_user','end_date,card_no,amount,created',$where_card,'',true);
		$where_user =  	array('user_id' => $user_id);
		$username =  $this->Common_model->getRecords('users','username,full_name',$where_user,'',true); 
		$card['points']=0;
		if(!empty($points['point'])){
			$card['points'] = $points['point'];
		}
		$card['end_date']='';
		$card['card_no']='';
		$card['amount']='';
		if(!empty($cards['end_date'])){
			$card['end_date'] = $cards['end_date'];
			$card['card_no'] = $cards['card_no'];
			$card['amount'] = $cards['amount'];
			$card['created'] = $cards['created'];
		}

		$card['username']='';
		$card['full_name']='';
		if(!empty($username)){
			$card['username'] = $username['username'];
			$card['full_name'] = $username['full_name'];
		}
		$get_offer['user_details'] =   $card;
		$tableName="redeem_offers";
		$where = array('user_id' => $user_id,'page_id' =>$page_id,'offer_id' =>$offer_id);
		if($this->Common_model->getRecords($tableName,'user_id',$where,'',true)) {
			$get_offer['redeem_offers'] =  1;
		}else{
			$get_offer['redeem_offers'] =  0;
		}
		$get_offer['redeem_offer_valid']=$this->checkOfferMonth($offer_id);

	 	if($get_offer)
		{
			$response = array('data'=> array('status'=>'1','msg'=>'Offer details','details'=>$get_offer));
			echo json_encode($response); exit;
        }


 
	}



	 function checkOfferMonth($offer_id)
	{
		// $offer_id = '4';
		$cards= $this->Common_model->getRecords('business_offers','days,months,exp_date',array('business_offers'=>$offer_id),'',true);
	  // echo $this->db->last_query();die;
		if(!empty($cards))
		{
			$data_exp_date = $cards['exp_date'];
			$curr_date = date('Y-m-d');
					// $data = 		'1-6-2018'
			// echo $data_exp_date.'<br>';// 					'17-5-2018'
			// echo strtotime($curr_date).'<br>';
			// echo strtotime($data_exp_date);

			// if(!empty(var))
			if(strtotime($curr_date) <= strtotime($data_exp_date)  || empty($data_exp_date))
			{
			
				$data_month = $cards['months'];
					if(!empty($data_month))
					{
						// echo $data_month ;die;
						$current_month = date('F Y');

						$month_arr = explode(',', $data_month);
						// echo "<pre>";print_r($month_arr) ;die;
						if(in_array($current_month, $month_arr))
						{
							$data_days = $cards['days'];
							$days_arr = explode(', ', $data_days);
						  	$current_day = date('l');
						  
							if(in_array($current_day, $days_arr))
							{
								return '1';
							}else
							{
								return '0';
							}
						}else
						{
							return '0';
						}
					}else
					{
						return '0';
					}
			}else
			{
				return '0';
			}
					
						
			
		}else
		{
			return '0';
		}
	}


	public function getMyoffersList()
	{
		$this->check_login();
		$user_id  =		$this->test_input($this->input->post('user_id'));
	 	$page_id  =		$this->test_input($this->input->post('page_id'));
		
		if(!empty($page_id))
		{
			// $where_offer = array("created_by_user"=>$user_id,'business_page_id'=>$page_id,"is_deleted"=>'0');
			$get_offer  = $this->App_model->mygetoffers($page_id,$user_id);

		}else{
			// $where_offer = array("created_by_user"=>$user_id,"is_deleted"=>'0');

			$get_offer  = $this->App_model->mygetoffers(0,$user_id);
		} 
		
		// $get_offer= $this->Common_model->getRecords('business_offers','business_offers,sort,page_expired_date,business_page_id,offers_type,offers_title,description,status',$where_offer,'sort ASC',false);  	


		$index = 0;
		foreach ($get_offer as $offers) {
			$get_offer[$index] =  $offers; 
			if($offers['page_expired_date'] >=DATE('Y-m-d'))
			{
				$get_offer[$index]['is_expired'] ='no';
			}else
			{
				$get_offer[$index]['is_expired'] ='yes';
			}
			$get_rating = $this->App_model ->get_rating_avg($offers['business_page_id']);
			$get_offer[$index]['rating'] = $get_rating['rating'];
			$offer_image= array("offer_id"=>$offers['business_offers']);
			$get_offer[$index]['offer_image'] = $this->Common_model->getRecords('offers_images','id,file_path',$offer_image,'',false);  

			if($offers['offers_type'] =='multi_buy'){
				$where_type= array("business_offers_id"=>$offers['business_offers']);
			 	$get_offer[$index]['multi_buy']= $this->Common_model->getRecords('multi_buy','discount_type,buy,buy_text,get,get_text,note',$where_type,'',true);  
			}else
			{
				$get_offer[$index]['multi_buy']='';
			} 
			if($offers['offers_type'] =='standard_discount')
			{
				$where_type= array("business_offers_id"=>$offers['business_offers']);
			 	$get_offer[$index]['standard_discount']= $this->Common_model->getRecords('standard_discount','discount_type,discount_value,product_note,product_description',$where_type,'',true);  
			}else
			{
				$get_offer[$index]['standard_discount']='';
			}


		$index++;	 
		}

		if($get_offer)
		{
			$response = array('data'=> array('status'=>'1','msg'=>'Offer list','details'=>$get_offer));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'msg' => 'Offers not found !!'));
			echo json_encode($err); exit;
        }

	}



	public function offerSorting()
	{

		$this->check_login();
		$user_id     =	$this->test_input($this->input->post('user_id'));
		$offer_id    =  $this->test_input($this->input->post('offer_id')); 
		$page_id =  $this->test_input($this->input->post('page_id')); 
		$sort_number =  $this->test_input($this->input->post('sort_number')); 
 	
		if(empty($offer_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offer id.'));
			echo json_encode($err); exit;
		}	
		if(empty($sort_number)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter sort number.'));
			echo json_encode($err); exit;
		}else
		{
			$where_old = array('business_page_id'=>$page_id);
			$count_number=$this->Common_model->getRecords('business_offers','sort',$where_old,'',false); 
			if($sort_number > count($count_number))
			{
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter max sort number "'.count($count_number).'"'));
				echo json_encode($err); exit;
			}
		}
		$where_sort = array("business_offers"=>$offer_id);
		$sort = $this->Common_model->getRecords('business_offers','sort,business_offers',$where_sort,'',true);  

		$where_old = array('business_page_id'=>$page_id,'sort'=>$sort_number);
		$update = array("sort"=>$sort['sort']);
		$update_sort=$this->Common_model->addEditRecords('business_offers',$update,$where_old);

		$update_data = array('sort'=>$sort_number);
		$where_s = array("business_offers"=>$offer_id);
		$update_sort=$this->Common_model->addEditRecords('business_offers',$update_data,$where_s);

		 
		$response = array('data'=> array('status'=>'1','msg'=>'Record sorted'));
		echo json_encode($response); exit;
	
  
 
	}


	public function updateOffers()
	{

		$this->check_login();
		$offer_id  =		$this->test_input($this->input->post('offer_id'));
		$user_id  =			$this->test_input($this->input->post('user_id'));
		$page_id  =			$this->test_input($this->input->post('page_id'));
		$offer_type  =		$this->test_input($this->input->post('offer_type'));
		$title  =			$this->test_input($this->input->post('title'));
		$description  =		$this->test_input($this->input->post('description'));
		$country_id  =		$this->test_input($this->input->post('country_id'));
		$state_id  =		$this->test_input($this->input->post('state_id'));
		$city_id  =			$this->test_input($this->input->post('city_id'));
		$offer_end_date  =	$this->test_input($this->input->post('offer_end_date'));
		$offer_days  	=	$this->test_input($this->input->post('offer_days'));
		$offer_months  =	$this->test_input($this->input->post('offer_months'));
		$notification  =	$this->test_input($this->input->post('notification'));
		
		$offer_image  =		$this->test_input($this->input->post('offer_image'));
	
	 	if(empty($offer_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offer id.'));
			echo json_encode($err); exit;
		}if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
 		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
			echo json_encode($err); exit;
		}
		if(empty($offer_type)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offers type.'));
			echo json_encode($err); exit;
		}elseif ($offer_type=='1') {

			$offers_type = 'multi_buy';

				$discount_type  =	$this->test_input($this->input->post('discount_type'));
				$buy  =	$this->test_input($this->input->post('buy'));
				$buy_text  =	$this->test_input($this->input->post('buy_text'));
				$get  =	$this->test_input($this->input->post('get'));
				$get_text  =	$this->test_input($this->input->post('get_text'));
				$note  =	$this->test_input($this->input->post('note'));
				$tandc  =	$this->test_input($this->input->post('term_and_condition'));

				if(empty($discount_type)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter discount type.'));
					echo json_encode($err); exit;
				}
				if($discount_type=='free')
				{
					if(empty($buy)){
						$err = array('data' =>array('status' => '0', 'msg' => 'Please enter buy.'));
						echo json_encode($err); exit;
					}
					// if(empty($buy_text)){
					// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter buy product name.'));
					// 	echo json_encode($err); exit;
					// }
					if(empty($get)){
						$err = array('data' =>array('status' => '0', 'msg' => 'Please enter get.'));
						echo json_encode($err); exit;
					}
					// if(empty($get_text)){
					// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter get product name.'));
					// 	echo json_encode($err); exit;
					// }
				}
				if($discount_type=='discount')
				{
					if(empty($get)){
						$err = array('data' =>array('status' => '0', 'msg' => 'Please enter get.'));
						echo json_encode($err); exit;
					}
					// if(empty($get_text)){
					// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter get product name.'));
					// 	echo json_encode($err); exit;
					// }
				}
				// if(empty($note)){
				// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter note.'));
				// 	echo json_encode($err); exit;
				// }
				
			
		} else if ($offer_type=='2') { 
				$offers_type = 'standard_discount'; 
				$discount_type  =	$this->test_input($this->input->post('discount_type'));
				$discount_value  =	$this->test_input($this->input->post('discount_value'));
				// $product_note  =	$this->test_input($this->input->post('product_note'));
				$product_name  =	$this->test_input($this->input->post('product_name'));
				$product_description  =	$this->test_input($this->input->post('product_description'));
				$tandc  =	$this->test_input($this->input->post('term_and_condition'));

				if(empty($discount_type)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter discount type.'));
					echo json_encode($err); exit;
				}
				if(empty($discount_value)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter discount value.'));
					echo json_encode($err); exit;
				}
				// if(empty($product_note)){
				// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter product note.'));
				// 	echo json_encode($err); exit;
				// }
				// if(empty($product_note)){
				// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter product name.'));
				// 	echo json_encode($err); exit;
				// }
				if(empty($product_description)){
					$err = array('data' =>array('status' => '0', 'msg' => 'Please enter product description.'));
					echo json_encode($err); exit;
				} 
			
		}else
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'offer type must be 1 or 2.'));
			echo json_encode($err); exit;
		}
		 
		// if(empty($title)){
		// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter title.'));
		// 	echo json_encode($err); exit;
		// }
 	// 	if(empty($description)){
		// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter description.'));
		// 	echo json_encode($err); exit;
		// }
		if(!empty($offer_end_date)){
		    $offer_end_dates = date('Y-m-d', strtotime($offer_end_date));
		}else
		{
			 $offer_end_dates= '';
		}

		if(empty($offer_days)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offers days.'));
			echo json_encode($err); exit;
		}


		$pre_images_get = $this->Common_model->getRecords('offers_images','file_path',array('offer_id'=>$offer_id),'',false);  
		$old_image =  count($pre_images_get);

		if(!empty($_FILES['offer_images']))
	 	{
	 		$new_image =  count($_FILES['offer_images']['name']);
	 	}else
	 	{
	 		$new_image =  0;
	 	}
		
		$image_count = $old_image +$new_image;
		if($image_count > 0){
	    	if($image_count < 6 ){
		    	$photo = 0;
				$allowed =  array('jpg','png','jpeg','JPG','PNG','JPEG');
				if(!empty($_FILES['offer_images']))
	 			{
					$filesCount = count($_FILES['offer_images']['name']);
			        for($i = 0; $i <$filesCount; $i++){
						$filename = $_FILES['offer_images']['name'][$i];
					    $ext = pathinfo($filename, PATHINFO_EXTENSION);
					    if(!in_array($ext,$allowed) ) {
						   $err = array('data' =>array('status' => '0', 'msg' => 'Only jpg|jpeg|png image types allowed..'));
				   			echo json_encode($err); exit;	
					    } 
				    }
			    }
		    }else
		    {
		    	$err = array('data' =>array('status' => '0', 'msg' => 'Please chose max 5 images.'));
				echo json_encode($err); exit;
		    }
		}else
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please select offers images.'));
			echo json_encode($err); exit;
		}    
	   
	    if($notification =='yes')
		{
			$pre_images_get = $this->Common_model->getRecords('offers_notification','notification_image',array('offer_id'=>$offer_id,'notification_image'=>''),'',false);

			$notification_title  =	$this->test_input($this->input->post('notification_title'));
			$notification_description  =	$this->test_input($this->input->post('notification_description'));

		 	if(empty($notification_title)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter notification title.'));
				echo json_encode($err); exit;
			}	
			if(empty($notification_description)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter notification description.'));
				echo json_encode($err); exit;
			}
			$old_image =  count($pre_images_get); 
	
		}
		$get_page_exp_date = $this->Common_model->getRecords('subscription_user','end_date',array("page_id"=>$page_id,'is_deleted'=>'0'),'',true); 
		
		if($offer_end_dates=='')
		{
			if(!empty($get_page_exp_date))
			{
				$page_en_date = $get_page_exp_date['end_date']; 
			}else
			{
				$page_en_date='';
			}
			
		}else{
			$page_en_date = $offer_end_dates; 
		}
		$pre_offers_type = $this->Common_model->getRecords('business_offers','offers_type',array('business_offers'=>$offer_id),'',true);  
	    $data = array(
			'offers_type'=>$offers_type,
			'offers_title'=>$title,
			'description'=>$description,
			'country_id'=>$country_id,
			'state_id'=>$state_id,
			'city_id'=>$city_id,
			'exp_date'=> $offer_end_dates,
			'days'=>$offer_days,
			'months'=>$offer_months,
			'status'=>'0',
			'page_expired_date'=>$page_en_date,
			'notification_msg'=>$notification
		);

		$this->Common_model->addEditRecords('business_offers',$data,array('business_offers'=>$offer_id));
 
	    if(!empty($_FILES['offer_images'])) {
			$filesCount = count($_FILES['offer_images']['name']);
			for($i = 0; $i <$filesCount; $i++){
				$_FILES['page_img']['name'] = $_FILES['offer_images']['name'][$i];
				$_FILES['page_img']['type'] = $_FILES['offer_images']['type'][$i];
				$_FILES['page_img']['tmp_name'] = $_FILES['offer_images']['tmp_name'][$i];
				$_FILES['page_img']['error'] = $_FILES['offer_images']['error'][$i];
				$_FILES['page_img']['size'] = $_FILES['offer_images']['size'][$i];
				
				//Rename image name 
				$img = time().'_'.rand();

				$config['upload_path'] = 'resources/images/offers/';
				//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
				$config['allowed_types'] = '*';
				$config['file_name'] =  $img;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if($this->upload->do_upload('page_img')){
					$fileData = $this->upload->data();
					$uploadData = array();
					$uploadData= array(
						'file_path' => 'resources/images/offers/'.$config['file_name'].$fileData['file_ext'],
						'offer_id' =>  $offer_id ,
						'created' => date("Y-m-d H:i:s"),
						);
			 		$this->Common_model->addEditRecords('offers_images', $uploadData);
				} else {
					$error = array('error' => $this->upload->display_errors());
	                $err = array('data' =>array('status' => '0', 'msg' => $error ));
					echo json_encode($err); exit;
				}
			} 
		}


		if($offer_type=='1')
		{
			$offer_data = array(
				'user_id'=>$user_id,
				'page_id'=>$page_id,
				'business_offers_id'=>$offer_id,
				'discount_type'=>$discount_type,
				'buy'=>$buy,
				'buy_text'=>$buy_text,
				'get'=>$get,
				'get_text'=>$get_text,
				'note'=>$note,
				'tandc'=>$tandc,
				'created'=>date("Y-m-d H:i:s")
			);

			$where_delete = array("user_id"=>$user_id,"page_id"=>$page_id,'business_offers_id'=>$offer_id);
			$this->Common_model->deleteRecords('standard_discount',$where_delete);

			if($pre_offers_type['offers_type']=='multi_buy'){
				$this->Common_model->addEditRecords('multi_buy',$offer_data,array("user_id"=>$user_id,"page_id"=>$page_id,'business_offers_id'=>$offer_id));
			}else
			{
				$this->Common_model->addEditRecords('multi_buy',$offer_data);
			}

		}
	 
		if($offer_type=='2')
		{

			$offer_data = array(
				'user_id'=>$user_id,
				'page_id'=>$page_id,
				'business_offers_id'=>$offer_id,
				'discount_type'=>$discount_type,
				'discount_value'=>$discount_value,
				// 'product_note'=>$product_note,
				'product_name'=>$product_name,
				'product_description'=>$product_description,
				'tandc'=>$tandc,
				'created'=>date("Y-m-d H:i:s")
			);
			$where_delete = array("user_id"=>$user_id,"page_id"=>$page_id,'business_offers_id'=>$offer_id);

			$this->Common_model->deleteRecords('multi_buy',$where_delete);

			if($pre_offers_type['offers_type']=='standard_discount'){
				$offer_type=	$this->Common_model->addEditRecords('standard_discount',$offer_data,array("user_id"=>$user_id,"page_id"=>$page_id,'business_offers_id'=>$offer_id));
			}else{
				$offer_type=	$this->Common_model->addEditRecords('standard_discount',$offer_data);
			}
		}
		if($notification =='yes')
		{

			if(!empty($_FILES['notification_images'])) {
				$filesCount = count($_FILES['notification_images']['name']);
				for($i = 0; $i <$filesCount; $i++){
					$_FILES['page_img']['name'] = $_FILES['notification_images']['name'][$i];
					$_FILES['page_img']['type'] = $_FILES['notification_images']['type'][$i];
					$_FILES['page_img']['tmp_name'] = $_FILES['notification_images']['tmp_name'][$i];
					$_FILES['page_img']['error'] = $_FILES['notification_images']['error'][$i];
					$_FILES['page_img']['size'] = $_FILES['notification_images']['size'][$i];
					
					//Rename image name 
					$img = time().'_'.rand();

					$config['upload_path'] = 'resources/images/offer_notification_image/';
					//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
					$config['allowed_types'] = '*';
					$config['file_name'] =  $img;

					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					if($this->upload->do_upload('page_img')){
						$fileData = $this->upload->data();
						$image_paths = 'resources/images/offer_notification_image/'.$config['file_name'].$fileData['file_ext'];
						
					} else {
						$error = array('error' => $this->upload->display_errors());
		                $err = array('data' =>array('status' => '0', 'msg' => $error ));
						echo json_encode($err); exit;
					}
				} 
			}else
			{
				$image_paths='';
			}

			$uploadData = array();
			$uploadData= array(
				'user_id' => $user_id,
				'page_id' => $page_id,
				'offer_id' => $offer_id ,
				'country_id' => $country_id,
				'state_id' => $state_id,
				'city_id' => $city_id,
				'notification_title' => $notification_title,
				'notification_description' => $notification_description,
				'notification_image' =>$image_paths
				);
		 	$this->Common_model->addEditRecords('offers_notification', $uploadData,array("user_id"=>$user_id,"page_id"=>$page_id,'offer_id'=>$offer_id));

		}
  
		$response = array('data'=> array('status'=>'1','msg'=>'Offer Updated'));
		echo json_encode($response); exit;
          
	}
	public function delete_offer()
	{
		$this->check_login();
		$user_id  =			$this->test_input($this->input->post('user_id'));	 
		$offer_id  =		$this->test_input($this->input->post('offer_id'));	
		if(empty($offer_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offer id.'));
			echo json_encode($err); exit;
		}
		
       	$getshot = $this->Common_model->getRecords('business_offers','sort,business_page_id',array('business_offers'=>$offer_id),'',true);
		
		$getshotlist = $this->Common_model->getRecords('business_offers','sort,business_offers',array('business_page_id'=>$getshot['business_page_id']),'',false);
		
		if(!empty($getshotlist)){
			foreach ($getshotlist as $offers) {
            if($getshot['sort'] < $offers['sort']){
				$newshot = $offers['sort']-1;
				$update = array(
				'sort'=>$newshot,
			);
	        $this->Common_model->addEditRecords('business_offers',$update,array("business_offers"=>$offers['business_offers']));
			}
			}
		}

	        $update_data = array(
				'is_deleted'=>'1',
			);

		$this->Common_model->deleteRecords('notifications',array('offer_id'=>$offer_id));
		$this->Common_model->deleteRecords('redeem_offers',array('offer_id'=>$offer_id));
		$this->Common_model->addEditRecords('business_offers',$update_data,array("created_by_user"=>$user_id,'business_offers'=>$offer_id));

		$response = array('data'=> array('status'=>'1','msg'=>'Offer Deleted'));
		echo json_encode($response); exit;
	}

	public function delete_offer_image()
	{
	 	$this->check_login();
		$user_id  =			$this->test_input($this->input->post('user_id'));	 
		$offer_id  =		$this->test_input($this->input->post('offer_id'));	
		$image_id  =		$this->test_input($this->input->post('image_id'));	
		if(empty($offer_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offer id.'));
			echo json_encode($err); exit;
		}
		if(empty($image_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter image id.'));
			echo json_encode($err); exit;
		}
		$where_delete = array('offer_id'=>$offer_id,'id'=>$image_id);
		$image_data=$this->Common_model->getRecords('offers_images','file_path',$where_delete,'',true); 
		
		$this->Common_model->deleteRecords('offers_images',$where_delete);

      	if(!empty($image_data)){
			if(!empty($image_data['file_path'])){
				unlink($image_data['file_path']);
			}
			if(!empty($image_data['video_path'])){
				unlink($image_data['video_path']);
			}
		}

		$response = array('data'=> array('status'=>'1','msg'=>'Image Deleted'));
		echo json_encode($response); exit;
 	
	}			

	public function delete_notification_image()
	{
		$this->check_login();
		$user_id   =	$this->test_input($this->input->post('user_id'));	 
		$offer_id  =	$this->test_input($this->input->post('offer_id'));	
	 	if(empty($offer_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offer id.'));
			echo json_encode($err); exit;
		}
	 
		$where_delete = array('offer_id'=>$offer_id);
		$image_data=$this->Common_model->getRecords('offers_notification','notification_image',$where_delete,'',true); 
	 	$this->Common_model->addEditRecords('offers_notification',array('notification_image'=>''),$where_delete);
	 

      	if(!empty($image_data)){
			if(!empty($image_data['notification_image'])){
				unlink($image_data['notification_image']);
			} 
		}

		$response = array('data'=> array('status'=>'1','msg'=>'Image Deleted'));
		echo json_encode($response); exit;

	}

	public function getAdslist()
	{
	
   		$page    =  $this->test_input($this->input->post('page'));
     	$limit  =   $this->test_input($this->input->post('limit')); 
     	$sub_cat  =   $this->test_input($this->input->post('sub_cat')); 
     	$device_id  =   $this->test_input($this->input->post('device_id')); 
       	if(empty($page)){
            $err = array('data' =>array('status' => '0', 'msg' => 'Please enter page number.'));
            echo json_encode($err); exit;
        }	
        if(empty($sub_cat)){
            $err = array('data' =>array('status' => '0', 'msg' => 'Please enter sub_cat.'));
            echo json_encode($err); exit;
        }
        if(empty($limit)){
            $limit = 1000000000000000;
        }
        $start = ($page-1)*$limit;

        $ads_list= $this->Common_model->getAdslist($limit,$start,$sub_cat,$device_id);
       // echo $this->db->last_query();
        $ad_list =array();
		if(!empty($ads_list))
		{
			foreach ($ads_list as $key => $ads_list_list) {
				$ad_list[$key] = $ads_list_list; 
				$ad_list[$key]['image'] =  $this->Common_model->getRecords('ads_images','*',array('ad_id'=>$ads_list_list['id']),'',false);
			}

			$response = array('data'=> array('status'=>'1','msg'=>'Ads list','details'=>$ad_list));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'msg' => 'Ad not found !!'));
			echo json_encode($err); exit;
        }

	}


	public function my_ad()
	{
	
   		$page    =  $this->test_input($this->input->post('page'));
     	$limit  =   $this->test_input($this->input->post('limit')); 
     	$device_id  =   $this->test_input($this->input->post('device_id')); 
       	if(empty($page)){
            $err = array('data' =>array('status' => '0', 'msg' => 'Please enter page number.'));
            echo json_encode($err); exit;
        }
        if(empty($device_id)){
            $err = array('data' =>array('status' => '0', 'msg' => 'Please enter devices id.'));
            echo json_encode($err); exit;
        }	 
        if(empty($limit)){
            $limit = 1000000000000000;
        }
        $start = ($page-1)*$limit;

        $ads_list= $this->Common_model->getAdslist($limit,$start,'',$device_id);
       // echo $this->db->last_query();
        $ad_list =array();
		if(!empty($ads_list))
		{
			foreach ($ads_list as $key => $ads_list_list) {
				$ad_list[$key] = $ads_list_list; 
				$ad_list[$key]['image'] =  $this->Common_model->getRecords('ads_images','*',array('ad_id'=>$ads_list_list['id']),'',false);
			}

			$response = array('data'=> array('status'=>'1','msg'=>'Ads list','details'=>$ad_list));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'msg' => 'Ad not found !!'));
			echo json_encode($err); exit;
        }

	}

	public function getuserDetails()
	{
	 	$this->check_login();
		$user_id =	$this->input->post('user_id');
		if(empty($user_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		}
 
	    if($user_details = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id,'status'=>'Active','is_deleted'=>'0'),'',true)) {

	    		$country_name =  $this->Common_model->getRecords('countries','name',array('id'=>$user_details['country_id']),'',true);

	    		$user_details['country_name'] =$country_name['name'];
	    		$state_name =  $this->Common_model->getRecords('states','name',array('id'=>$user_details['state_id']),'',true);
	    		$user_details['state_name'] =$state_name['name'];
	    		$city_name =  $this->Common_model->getRecords('cities','name',array('id'=>$user_details['city_id']),'',true);
	    		$user_details['city_name'] =$city_name['name'];
			 
			$response = array('data'=> array('status'=>'1','msg'=>'Details','details'=>$user_details));
			echo json_encode($response); exit;
			   
		}else {
            $response = array('data'=> array('status'=>'0','msg'=>'User not found.'));
			echo json_encode($response); exit;
           
        }
    }


   	public function addDeviceId()
   	{

   		$device_id =	$this->input->post('device_id');
   		$device_type =	$this->input->post('device_type');
		if(empty($device_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter device_id.'));
			echo json_encode($err); exit;
		}
		$device_type =	$this->input->post('device_type');
		if(empty($device_type))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter device_type.'));
			echo json_encode($err); exit;
		} 

		if(!$this->Common_model->getRecords('devices_id','*',array('device_type'=>$device_type,'device_id'=>$device_id),'',true)){
			$insert_data = array(
				'device_id'=> $this->input->post('device_id'),
				'device_type'=> $this->input->post('device_type'), 
			);


			
	 		if(!$ad_id= $this->Common_model->addEditRecords('devices_id', $insert_data)) {
				
				$err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
				echo json_encode($err); exit;
			} else {
				$err = array('data' =>array('status' => '1', 'msg' => 'Device Added'));
				echo json_encode($err); exit;
	   		}
   		}else
   		{
   			$err = array('data' =>array('status' => '1', 'msg' => 'Device Added'));
			echo json_encode($err); exit;
   		}
   }	
    public function addFaq()
   	{

   		$device_id =	$this->input->post('device_id');
		if(empty($device_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter device_id.'));
			echo json_encode($err); exit;
		}
		$title =	$this->input->post('title');
		if(empty($title))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter title.'));
			echo json_encode($err); exit;
		}
	
		$detail =	$this->input->post('detail');
		if(empty($detail))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter detail.'));
			echo json_encode($err); exit;
		}
		$link =	$this->input->post('link');
		// if(empty($link))
		// {
		// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter link.'));
		// 	echo json_encode($err); exit;
		// }

		if(!empty($_FILES['image']['name']))
		 	{	
		 		// echo '123';
					$_FILES['images']['name'] = $_FILES['image']['name'];
					$_FILES['images']['type'] = $_FILES['image']['type'];
					$_FILES['images']['tmp_name'] =$_FILES['image']['tmp_name'];
					$_FILES['images']['error'] = $_FILES['image']['error'];
					$_FILES['images']['size'] =	 $_FILES['image']['size']; 
					//Rename image name 
					$img = time().'_'.rand();

					$config['upload_path'] =CATEGORY_PATH;
					//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
					$config['allowed_types'] = '*';
					$config['file_name'] =  $img; 
					$this->load->library('upload', $config);
					$this->upload->initialize($config); 
					if($this->upload->do_upload('images')){
						$fileData = $this->upload->data();

				 		$image = CATEGORY_PATH.$config['file_name'].$fileData['file_ext'];	

				 		$order_data = $this->Common_model->getRecords('faq','MAX(orders) as max_order','','',true);	
						$order = $order_data['max_order']+1;
						$link = $this->input->post('link');
						if(empty($link))
						{
							$link ='';
						}
						$insert_data = array(
								// 'faq'=> $this->input->post('faq'),
								'device_id'=> $this->input->post('device_id'),
								'question'=> $this->input->post('title'),
								'answer'=> $this->input->post('detail'),
								'link'=> $link,
								'image'=> $image,
								'orders'=> $order,
								// 'approved'=> '1',
								 
								'created'=> date("Y-m-d H:i:s")
							);

					 		if(!$ad_id= $this->Common_model->addEditRecords('faq', $insert_data)) {
								$this->session->set_flashdata('error', 'Some error occured! Please try again.');
							} else {  
						 		// die;
								$err = array('data' =>array('status' => '1', 'msg' => 'FAQ Added Successfully'));
								echo json_encode($err); exit;
   							}
					} 
				}else
				{
					$order_data = $this->Common_model->getRecords('faq','MAX(orders) as max_order','','',true);	
						$order = $order_data['max_order']+1;
						$link = $this->input->post('link');
						if(empty($link))
						{
							$link ='';
						}
						$insert_data = array(
								// 'faq'=> $this->input->post('faq'),
								'device_id'=> $this->input->post('device_id'),
								'question'=> $this->input->post('title'),
								'answer'=> $this->input->post('detail'),
								'link'=> $link,
								'image'=> '',
								'orders'=> $order,
								// 'approved'=> '1',
								 
								'created'=> date("Y-m-d H:i:s")
							);

					 		if(!$ad_id= $this->Common_model->addEditRecords('faq', $insert_data)) {
								$this->session->set_flashdata('error', 'Some error occured! Please try again.');
							} else {  
						 		// die;
								$err = array('data' =>array('status' => '1', 'msg' => 'FAQ Added Successfully'));
								echo json_encode($err); exit;
   							}
				}  				
   }	

    public function editFaq()
   	{

   		$device_id =	$this->input->post('device_id');
		$title =	$this->input->post('title');
		$detail =	$this->input->post('detail');
		$link =	$this->input->post('link');
		$faq_id =	$this->input->post('faq_id');
		if(empty($device_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter device_id.'));
			echo json_encode($err); exit;
		}
		if(empty($faq_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter faq_id.'));
			echo json_encode($err); exit;
		}
		if(empty($title))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter title.'));
			echo json_encode($err); exit;
		}
	
		if(empty($detail))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter detail.'));
			echo json_encode($err); exit;
		}
		// if(empty($link))
		// {
		// 	$err = array('data' =>array('status' => '0', 'msg' => 'Please enter link.'));
		// 	echo json_encode($err); exit;
		// }

		if(!empty($_FILES['image']['name']))
		 	{	
		 		// echo '123';
					$_FILES['images']['name'] = $_FILES['image']['name'];
					$_FILES['images']['type'] = $_FILES['image']['type'];
					$_FILES['images']['tmp_name'] =$_FILES['image']['tmp_name'];
					$_FILES['images']['error'] = $_FILES['image']['error'];
					$_FILES['images']['size'] =	 $_FILES['image']['size']; 
					//Rename image name 
					$img = time().'_'.rand();

					$config['upload_path'] =CATEGORY_PATH;
					//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
					$config['allowed_types'] = '*';
					$config['file_name'] =  $img; 
					$this->load->library('upload', $config);
					$this->upload->initialize($config); 
					if($this->upload->do_upload('images')){
						$fileData = $this->upload->data();

				 		$image = CATEGORY_PATH.$config['file_name'].$fileData['file_ext'];	

				 	
					} 
				}else
				{
					$faq_list = $this->Common_model->getRecords('faq','image',array('faq_id'=>$faq_id),'',true);	
					$image = $faq_list['image'];
				}

				// 	$order_data = $this->Common_model->getRecords('faq','MAX(orders) as max_order','','',true);	
						// $order = $order_data['max_order']+1;
						$link = $this->input->post('link');
						if(empty($link))
						{
							$link ='';
						}
					$insert_data = array(
								// 'faq'=> $this->input->post('faq'),
								'device_id'=> $this->input->post('device_id'),
								'question'=> $this->input->post('title'),
								'answer'=> $this->input->post('detail'),
								'link'=> $link,
								'image'=> $image,
								// 'orders'=> $order,
								 
								'created'=> date("Y-m-d H:i:s")
							);

					 		if(!$this->Common_model->addEditRecords('faq', $insert_data,array('faq_id'=>$faq_id))) {
								$this->session->set_flashdata('error', 'Some error occured! Please try again.');
							} else {  
						 		// die;
								$err = array('data' =>array('status' => '1', 'msg' => 'FAQ Updated Successfully'));
								echo json_encode($err); exit;
   							}
							
   }	



   public function editAdd()
   	{

   		$ad_id =	$this->input->post('ad_id');
		if(empty($ad_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter ad_id.'));
			echo json_encode($err); exit;
		}
   		$category_id =	$this->input->post('category_id');
		if(empty($category_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter category_id.'));
			echo json_encode($err); exit;
		}
		$sub_category_id =	$this->input->post('sub_category_id');
		if(empty($sub_category_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter sub_category_id.'));
			echo json_encode($err); exit;
		}
		/*$title =	$this->input->post('title');
		if(empty($title))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter title.'));
			echo json_encode($err); exit;
		}*/
		$detail =	$this->input->post('detail');
		if(empty($detail))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter detail.'));
			echo json_encode($err); exit;
		}
		$phone_number =	$this->input->post('phone_number');
		if(empty($phone_number))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter phone_number.'));
			echo json_encode($err); exit;
		}
		$old_image =	$this->input->post('old_image');
		

					$insert_data = array(
					'category_id'=> $this->input->post('category_id'),
					'sub_category_id'=> $this->input->post('sub_category_id'),
					// 'title'=> $this->input->post('title'),
					'detail'=> $this->input->post('detail'),
					'phone_number'=> $this->input->post('phone_number'),
					'status'=> $this->input->post('status'),
					// 'image'=> $image_path,
					'created'=> date("Y-m-d H:i:s")
				);

				if(!$this->Common_model->addEditRecords('ads', $insert_data,array('id'=>$ad_id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {

						$this->Common_model->deleteRecords('ads_images',array('ad_id'=>$ad_id));
						// echo $this->db->last_qiery();
						 		if(!empty($old_image))
						 		{
						 			foreach ($old_image as $key => $image_list) {

						 				$insert_data = array(
												'ad_id'=> $ad_id,
												'image'=>$image_list,
											);
										
								 		if(!$this->Common_model->addEditRecords('ads_images', $insert_data)) {
											$this->session->set_flashdata('error', 'Some error occured! Please try again.');
										}
						 										 			
						 			}
						 		}

						 if(!empty($_FILES['image']['name'])){		
						for($i=0;$i < count($_FILES['image']['name']); $i++)
		 						{ 

			 					if(!empty($_FILES['image']['name'][$i]))
							 	{	
							 		// echo '123';
										$_FILES['images']['name'] = $_FILES['image']['name'][$i];
										$_FILES['images']['type'] = $_FILES['image']['type'][$i];
										$_FILES['images']['tmp_name'] =$_FILES['image']['tmp_name'][$i];
										$_FILES['images']['error'] = $_FILES['image']['error'][$i];
										$_FILES['images']['size'] =	 $_FILES['image']['size'][$i]; 
										//Rename image name 
										$img = time().'_'.rand();

										$config['upload_path'] =CATEGORY_PATH;
										//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
										$config['allowed_types'] = '*';
										$config['file_name'] =  $img; 
										$this->load->library('upload', $config);
										$this->upload->initialize($config); 
										if($this->upload->do_upload('images')){
											$fileData = $this->upload->data();

											$insert_data = array(
												'ad_id'=> $ad_id,
												'image'=>CATEGORY_PATH.$config['file_name'].$fileData['file_ext'],
											);
										
								 		if(!$this->Common_model->addEditRecords('ads_images', $insert_data)) {
											$this->session->set_flashdata('error', 'Some error occured! Please try again.');
										}
										}
									}
						 		}  
						 	}	



						 		// die;
								$err = array('data' =>array('status' => '1', 'msg' => 'Ad Updated Successfully'));
								echo json_encode($err); exit;
   	}
   }
 
   public function adAdd()
   	{

   		 
   		$category_id =	$this->input->post('category_id');
		if(empty($category_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter category_id.'));
			echo json_encode($err); exit;
		}
		$sub_category_id =	$this->input->post('sub_category_id');
		if(empty($sub_category_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter sub_category_id.'));
			echo json_encode($err); exit;
		}
		/*$title =	$this->input->post('title');
		if(empty($title))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter title.'));
			echo json_encode($err); exit;
		}*/
		$detail =	$this->input->post('detail');
		if(empty($detail))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter detail.'));
			echo json_encode($err); exit;
		}
		$phone_number =	$this->input->post('phone_number');
		if(empty($phone_number))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter phone_number.'));
			echo json_encode($err); exit;
		}
		$device_id =	$this->input->post('device_id');
		if(empty($device_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter device_id.'));
			echo json_encode($err); exit;
		}
		
		 
		

					$insert_data = array(
					'category_id'=> $this->input->post('category_id'),
					'sub_category_id'=> $this->input->post('sub_category_id'),
					'device_id'=> $this->input->post('device_id'),
					// 'title'=> $this->input->post('title'),
					'detail'=> $this->input->post('detail'),
					'phone_number'=> $this->input->post('phone_number'),
					'status'=> 'Active',
					'approved'=> '0',
					// 'image'=> $image_path,
					'created'=> date("Y-m-d H:i:s")
				);

				if(!$ad_id = $this->Common_model->addEditRecords('ads', $insert_data)) {
					// $this->session->set_flashdata('error', 'Some error occured! Please try again.');
						$err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
													echo json_encode($err); exit;
				} else {

						// $this->Common_model->deleteRecords('ads_images',array('ad_id'=>$ad_id));
						// echo $this->db->last_qiery();
						if(!empty($_FILES['image']['name']))
						{ 	 
							for($i=0;$i < count($_FILES['image']['name']); $i++)
		 					{ 
			 					if(!empty($_FILES['image']['name'][$i]))
							 	{	
							 		// echo '123';
										$_FILES['images']['name'] = $_FILES['image']['name'][$i];
										$_FILES['images']['type'] = $_FILES['image']['type'][$i];
										$_FILES['images']['tmp_name'] =$_FILES['image']['tmp_name'][$i];
										$_FILES['images']['error'] = $_FILES['image']['error'][$i];
										$_FILES['images']['size'] =	 $_FILES['image']['size'][$i]; 
										//Rename image name 
										$img = time().'_'.rand();

										$config['upload_path'] =CATEGORY_PATH;
										//$config['allowed_types'] = 'jpg|png|mp4|mov|jpeg|JPG|PNG|MP4|MOV|JPEG';
										$config['allowed_types'] = '*';
										$config['file_name'] =  $img; 
										$this->load->library('upload', $config);
										$this->upload->initialize($config); 
										if($this->upload->do_upload('images')){
											$fileData = $this->upload->data();
											$insert_data = array(
												'ad_id'=> $ad_id,
												'image'=>CATEGORY_PATH.$config['file_name'].$fileData['file_ext'],
											);
								 		if(!$this->Common_model->addEditRecords('ads_images', $insert_data)) {
												$err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
													echo json_encode($err); exit;
										}
										}
									}
						 		}    
								
   						}
   		$err = array('data' =>array('status' => '1', 'msg' => 'Ad Add Successfully'));
								echo json_encode($err); exit;
   		}
   }
 





   	public function adFavourite()
   	{

   		$ad_id =	$this->input->post('ad_id');
		if(empty($ad_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter ad_id.'));
			echo json_encode($err); exit;
		}
		$uniq_id =	$this->input->post('uniq_id');
		if(empty($uniq_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter uniq_id.'));
			echo json_encode($err); exit;
		}

			$insert_data = array(
				'ad_id'=> $this->input->post('ad_id'),
				'uniq_id'=> $this->input->post('uniq_id'),
				
			);
		
 		if(!$ad_id= $this->Common_model->addEditRecords('fav_ad', $insert_data)) {
			// $this->session->set_flashdata('error', 'Some error occured! Please try again.');
			$err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
			echo json_encode($err); exit;
		} else {
	 		// die;
			$err = array('data' =>array('status' => '1', 'msg' => 'Ad Added in favourite list'));
			echo json_encode($err); exit;
			}
   }	

   	public function removeFavourite()
   	{

   		$ad_id =	$this->input->post('ad_id');
		if(empty($ad_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter ad_id.'));
			echo json_encode($err); exit;
		}
		$uniq_id =	$this->input->post('uniq_id');
		if(empty($uniq_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter uniq_id.'));
			echo json_encode($err); exit;
		}

						
				 		if(!$this->Common_model->deleteRecords('fav_ad',array('ad_id'=>$ad_id,'uniq_id'=>$uniq_id))) {
							$err = array('data' =>array('status' => '0', 'msg' => 'Some error occured! Please try again.'));
							echo json_encode($err); exit;
						} else {
					 		// die;
							$err = array('data' =>array('status' => '1', 'msg' => 'Ad removed from favourite list'));
							echo json_encode($err); exit;
							}
   }	

	public function doctors_list()
   	{	 
		$user_id  = $this->input->post('user_id');
		$this->check_login();
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		}


        $user_list = $this->Common_model->getRecords('users','user_id,profile_pic,payment_status,full_name,category_id',array('user_id!='=>$user_id,'user_type'=>'doctor','is_deleted'=>'0','status'=>'active'),'',false);
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

			$response = array('data'=> array('status'=>'1','msg'=>'list','doctor_list'=>$dr_list));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'msg' => 'doctors not found !!'));
			echo json_encode($err); exit;
        }
			
	 	 
   }	
   	public function doctor_detail()
   	{	 
		$doctor_id  = $this->input->post('doctor_id');
		$user_id  = $this->input->post('user_id');
		$this->check_login();
		if(empty($doctor_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter doctor_id.'));
			echo json_encode($err); exit;
		}
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		}

        $doctor_detail = $this->Common_model->getRecords('users','*',array('user_id'=>$doctor_id,'user_type'=>'doctor','is_deleted'=>'0','status'=>'active'),'',true);
        $dr_list =array();
        if(!empty($doctor_detail))
        {
        		$doctor_detail= $this->Common_model->getdrdetailsRecords($doctor_id);
        		// echo $this->db->last_query();die;
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

			$response = array('data'=> array('status'=>'1','msg'=>'list','doctor_detail'=>$doctor_detail,'media_list'=>$media));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'msg' => 'doctors not found !!'));
			echo json_encode($err); exit;
        }	 
   }	

   	public function media_details()
   	{	 
		$media_id  = $this->input->post('media_id');
		$user_id  = $this->input->post('user_id');
		$this->check_login();
		if(empty($media_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter media_id.'));
			echo json_encode($err); exit;
		}
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user_id.'));
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

					 $response = array('data'=> array('status'=>'1','msg'=>'list','details'=>$media_details));
						echo json_encode($response); exit;
				}else
		        {
					$err = array('data' =>array('status' => '0', 'msg' => 'media not found !!'));
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
			$err = array('data' =>array('status' => '0', 'msg' => 'Please select media type.'));
			echo json_encode($err); exit;
		}else
		{
			if($media_type!='video' && $media_type!='audio' && $media_type!='image')
			{
				$err = array('data' =>array('status' => '0', 'msg' => 'User type must be video or audio or image.'));
				echo json_encode($err); exit;	
			}
		} 
		
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		}  
		if(empty($title)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter title.'));
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
				$err = array('data' =>array('status' => '0', 'msg' =>strip_tags($this->upload->display_errors())));
	            echo json_encode($err); exit; 		
			}
			else
			{
				$upload_data=$this->upload->data();	
            } 
        }else
        {
        		$err = array('data' =>array('status' => '0', 'msg' => 'Please upload media.'));
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
				$err = array('data' =>array('status' => '0', 'msg' =>strip_tags($this->upload->display_errors())));
	            echo json_encode($err); exit; 		
			}
			else
			{

				$video_image=$this->upload->data();	
            } 
        }else
        {
        	if($media_type=='video'){

        		$err = array('data' =>array('status' => '0', 'msg' => 'Please upload video image.'));
				echo json_encode($err); exit;
			}else
			{
				$video_image='';
			}
        }

	    $insert_data = array( 
	    	'media_type'=>$media_type,
	    	'video_image'=>'resources/media/'.$video_image['file_name'],
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

					$response = array('data'=> array('status'=>'1','msg'=>'Media uploaded successfully'));
				    echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'Server not responding. Please try again !!'));
				echo json_encode($err); exit;
			}
	 
	}

	public function favourite_list()
   	{	 
		$uniq_id =	$this->input->post('uniq_id');
		$page    =  $this->test_input($this->input->post('page'));
     	$limit  =   $this->test_input($this->input->post('limit')); 
     	$name  =   $this->test_input($this->input->post('name')); 
		if(empty($uniq_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter uniq_id.'));
			echo json_encode($err); exit;
		}

       	if(empty($page)){
            $err = array('data' =>array('status' => '0', 'msg' => 'Please enter page number.'));
            echo json_encode($err); exit;
        }
        if(empty($limit)){
            $limit = 1000000000000000;
        }
        $start = ($page-1)*$limit;

        $ads_list= $this->Common_model->getfavAdslist($limit,$start,$name,$uniq_id);
       // echo $this->db->last_query();
        $ad_list =array();
		if(!empty($ads_list))
		{
			foreach ($ads_list as $key => $ads_list_list) {
				$ad_list[$key] = $ads_list_list; 
				$ad_list[$key]['image'] =  $this->Common_model->getRecords('ads_images','*',array('ad_id'=>$ads_list_list['id']),'',false);
			}

			$response = array('data'=> array('status'=>'1','msg'=>'Ads list','details'=>$ad_list));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'msg' => 'Ad not found !!'));
			echo json_encode($err); exit;
        }
			
	 	 
   }	





	public function checkPagePin()
	{	$this->check_login();
		$business_page_id  	=		$this->test_input($this->input->post('page_id'));
		$get_user_pin = $this->Common_model->getRecords('business_page','business_page_id,page_pin',array('business_page_id'=>$business_page_id,'page_pin !='=>0),'',true); 
		if(empty($get_user_pin))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Enter Pin.'));
			echo json_encode($err); exit;
		}else {
			$err = array('data' =>array('status' => '1', 'msg' => 'You have already Pin'));
			echo json_encode($err); exit;
		}

	}


	public function redeemOffer()
	{
		$this->check_login();
		$user_id  	=		$this->test_input($this->input->post('user_id'));
		$page_id  	=		$this->test_input($this->input->post('page_id'));
		$page_pin  =		$this->test_input($this->input->post('page_pin'));
		$user_pin  =		$this->test_input($this->input->post('user_pin'));
		$amount  =			$this->test_input($this->input->post('amount'));
		$offer_id  =		$this->test_input($this->input->post('offer_id'));
		$lat  =				$this->test_input($this->input->post('lat'));
		$lng  =				$this->test_input($this->input->post('lng'));
	 		
		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
			echo json_encode($err); exit;
		}
		if(empty($page_pin)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page pin.'));
			echo json_encode($err); exit;
		}
		if(empty($user_pin)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
		if(empty($offer_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter offer id.'));
			echo json_encode($err); exit;
		}
		if(empty($amount)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter amount.'));
			echo json_encode($err); exit;
		} 

		$get_user_pin = $this->Common_model->getRecords('users','user_id,user_pin',array('user_id'=>$user_id,'user_pin'=>$user_pin),'',true); 
		if(empty($get_user_pin))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Invalid user pin.'));
			echo json_encode($err); exit;
		}
		$get_page_pin = $this->Common_model->getRecords('business_page','business_page_id,page_pin',array('business_page_id'=>$page_id,'page_pin'=>$page_pin),'',true); 

		if(empty($get_page_pin))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Invalid page pin.'));
			echo json_encode($err); exit;
		}

		$date= date('Y-m-d H:i:s');
		$time= date('H:i:s');
	    $insert_data = array(
	    	'user_id'=>  $user_id,
	    	'page_id'=> $page_id,
	    	'offer_id'=> $offer_id,
	    	'amount'=> $amount,
	    	'latitude'=> $lat,
	    	'longitude'=> $lng,
	    	'time'=> $time,
	    	'created'=>$date,
	    );

		$record = $this->Common_model->addEditRecords('redeem_offers',$insert_data);
		if($record)
		{
			
		$where = array('user_id' => $user_id);
		$resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);
		$username = $resiver['username'];
		$demo=$this->badge_count($user_id,'users','user_id');
		if($resiver['notification']=='Yes'){
		    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);

		      	$iosarray = array(
                    'alert' => 'You have successfully redeemed offer',
                    'type'  => 'redeemed',
                   	'offer_id'=> $offer_id,
                   	'page_id'=> $page_id, 
                    'ismyoffer' => '0',
                    'badge' => $demo,
                    'sound' => 'default',
       			);

				$andarray = array(
	                'message'   => 'You have successfully redeemed offer',
	                'type'      =>'redeemed',
	                'page_id'=> $page_id,
	                'ismyoffer' => '0',
	               	'offer_id'=> $offer_id,
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
		   $savearray = 'offer_id-'.$offer_id.'@page_id-'.$page_id;
		    $add_data =array('user_id' => $user_id,'created_by' =>$user_id,'page_id' =>$page_id,'type'=>'redeemed', 'notification_title'=>'redeemed  offer', 'notification_description'=>'You have successfully redeemed offer', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
    		$this->Common_model->addEditRecords('notifications',$add_data); 

		}
		
		$where = array('business_page_id' => $page_id);
		$resiver=$this->Common_model->getRecords('business_page','user_id,push_notification,business_name',$where,'',true);

		$wherelist = array('business_offers'=> $offer_id);
		$offers=$this->Common_model->getRecords('business_offers','offers_title,',$wherelist,'',true);
		$offers_title = $offers['offers_title'];
        $business_name = $resiver['business_name'];

		
		
		$where11 = array('user_id' => $resiver['user_id']);
		if($resiver['push_notification']=='Yes'){
		    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where11,'',false);
            $where = array('user_id' =>$resiver['user_id']);
			$count=$this->Common_model->getRecords('users','badge_count',$where,'',true); 
		      	$iosarray = array(
                    'alert' =>  $username.' have redeemed offer '.$offers_title.' for '.$business_name,
                    'type'  => 'redeemed',
                   	'offer_id'=> $offer_id, 
                   	'page_id'=> $page_id,
                    'ismyoffer' => '0',
                    'badge' => $count['badge_count'],
                    'sound' => 'default',
       			);

				$andarray = array(
	                'message'   => $username.' have redeemed offer '.$offers_title.' for '.$business_name,
	                'type'      =>'redeemed',
	               	'offer_id'=> $offer_id,
	               	'ismyoffer' => '0',
	               	'page_id'=> $page_id,
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
		   $savearray = 'offer_id-'.$offer_id.'@page_id-'.$page_id;
		    $add_data =array('user_id' => $user_id,'page_id'=>$page_id,'created_by' =>$user_id,'type'=>'redeemed', 'notification_title'=>'redeemed  offer', 'notification_description'=>$username.' have redeemed offer '.$offers_title.' for '.$business_name, 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
    		$this->Common_model->addEditRecords('notifications',$add_data); 

		}


				$response = array('data'=> array('status'=>'1','msg'=>'Offer redeemed successfully.'));
				echo json_encode($response); exit;



		}else
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Some problem in server!!'));
			echo json_encode($err); exit;
		}
	}

	public function nearbyBusiness()
	{
		$this->check_login();
		$user_id  	=		$this->test_input($this->input->post('user_id'));
		$latitude  =		$this->test_input($this->input->post('lat'));
		$longitude  =		$this->test_input($this->input->post('lng'));
		$category_id  =		$this->test_input($this->input->post('category_id'));

		
	 	if(empty($latitude)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter latitude.'));
			echo json_encode($err); exit;
		} 
		if(empty($longitude)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter longitude.'));
			echo json_encode($err); exit;
		} 
		if(empty($category_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter category id.'));
			echo json_encode($err); exit;
		} 

		
		if (strpos($category_id,'places') !== false) 
		{ 
			$place_near_by = 'yes';
			$explode_lat = explode('@',$category_id);
		}else
		{ 
			$place_near_by = 'no'; 
		} 
		// Normal   Pages 

		if($place_near_by=='no')
		{
			//From Current  Location 
		 	$get_pages = $this->App_model->get_near_by_page($category_id,$latitude,$longitude);
		}else
		{ 
			//From Search Location 
			$get_pages = $this->App_model->get_near_by_page_with_place($explode_lat[1],$explode_lat[2]);
		}

		$index = 0;
		$index2 = 0;
		$sponsored = array();
		$unsponsored = array(); 
		foreach ($get_pages as $pages) {
				
				if($pages['sponsored']=='Yes')
				{	
					$sponsored[$index] = $pages;
					$sponsored[$index]['business_name'] = $pages['business_full_name']; 
  
					$sub_cat_id = $pages['sub_category_id'];
					$sub_categories_name = array();
					if(!empty($sub_cat_id))
					{ 
						$qama_saprate = explode(',',$sub_cat_id);
						foreach ($qama_saprate as $list) {
								$sub_categories_name[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
					}
					$sub_cat_id2 = $pages['sub_category_id2'];
					$sub_categories_name2 = array();
					if(!empty($sub_cat_id2))
					{
						
						$qama_saprate = explode(',',$sub_cat_id2);
						foreach ($qama_saprate as $list) {
								$sub_categories_name2[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
					}
					$sub_cat_id = $pages['sub_category_id3'];
					$sub_categories_name3 = array();
					if(!empty($sub_cat_id))
					{ 
						$qama_saprate = explode(',',$sub_cat_id);
						foreach ($qama_saprate as $list) {
								$sub_categories_name3[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
					}

					$array_merge = array_merge($sub_categories_name,$sub_categories_name2,$sub_categories_name3);
					$new_arr = array_unique($array_merge);
					$sponsored[$index]['sub_category_name']  = implode(', ',$new_arr);



					$get_featured_images = $this->Common_model->getRecords('featured_images','image as file_path ',array('business_page_id'=>$pages['page_id']),'order_number ASC',false);
					/*************************** Check featured_images **************************/
					if(empty($get_featured_images))
					{ 
						$select_image2 = $this->Common_model->getRecordsimg('business_img','file_path',array('business_page_id'=>$pages['page_id']),'is_star',false,0); 
								
					}else
					{ 
						$select_image2 = $get_featured_images;
					}

						
		  			$tableName = 'business_page';
					$where = array('business_page_id' => $pages['page_id']);

					$redeem=$this->Common_model->getRecords('business_page','disply_rating,category_id',$where,'',true);
					if($redeem['disply_rating']=='verified_rating'){
						$disply_rating ='verified';
					} else {
						$disply_rating ='';
					}
					$sponsored[$index]['rating']=  (string)number_format((float)$this->Common_model->business_rating($pages['page_id'],$disply_rating), 2, '.', '');
		  			$sponsored[$index]['images'] = $select_image2;
		  			$sponsored[$index]['data_type'] =  'local'; 	 
					$index ++;

				}else
				{ 
					$unsponsored[$index2] = $pages;
					$unsponsored[$index2]['business_name'] = $pages['business_full_name']; 
					// $sub_cat_id = $pages['sub_category_id'];
					
					// if(!empty($sub_cat_id))
					// {
					// 	$sub_categories_name = '';
					// 	$qama_saprate = explode(',',$sub_cat_id);
					// 	foreach ($qama_saprate as $list) {
					// 			$sub_categories_name[]= ucfirst(get_sub_categories_name($list));
					// 	}
					
					// 	$unsponsored[$index2]['sub_category_name']  = implode(' , ',$sub_categories_name);
					// }



					$sub_cat_id = $pages['sub_category_id'];
					$sub_categories_name = array();
					if(!empty($sub_cat_id))
					{ 
						$qama_saprate = explode(',',$sub_cat_id);
						foreach ($qama_saprate as $list) {
								$sub_categories_name[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						
					}
					$sub_cat_id2 = $pages['sub_category_id2'];
					$sub_categories_name2 = array();
					if(!empty($sub_cat_id2))
					{
						
						$qama_saprate = explode(',',$sub_cat_id2);
						foreach ($qama_saprate as $list) {
								$sub_categories_name2[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						
					}
					$sub_cat_id = $pages['sub_category_id3'];
					$sub_categories_name3 = array();
					if(!empty($sub_cat_id))
					{ 
						$qama_saprate = explode(',',$sub_cat_id);
						foreach ($qama_saprate as $list) {
								$sub_categories_name3[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
					}

					$array_merge = array_merge($sub_categories_name,$sub_categories_name2,$sub_categories_name3);
					$new_arr = array_unique($array_merge);
					$unsponsored[$index2]['sub_category_name']  = implode(', ',$new_arr);



 



					$get_featured_images = $this->Common_model->getRecords('featured_images','image as file_path ',array('business_page_id'=>$pages['page_id']),'order_number ASC',false);
					/*************************** Check featured_images **************************/
					if(empty($get_featured_images))
					{ 
						$select_image2 = $this->Common_model->getRecordsimg('business_img','file_path',array('business_page_id'=>$pages['page_id']),'is_star',false,0); 
								
					}else
					{ 
						$select_image2 = $get_featured_images;
					}

						
		  			$tableName = 'business_page';
					$where = array('business_page_id' => $pages['page_id']);

					$redeem=$this->Common_model->getRecords('business_page','disply_rating,category_id',$where,'',true);
					if($redeem['disply_rating']=='verified_rating'){
						$disply_rating ='verified';
					} else {
						$disply_rating ='';
					}
					$unsponsored[$index2]['rating']=  (string)number_format((float)$this->Common_model->business_rating($pages['page_id'],$disply_rating), 2, '.', '');
		  			$unsponsored[$index2]['images'] = $select_image2;
		  			$unsponsored[$index2]['data_type'] =  'local';  
					$index2 ++; 
				}
			
		} 
		 
		/******************************************* New Google Changes Start **************************************************/
		
			$getkeyword = $this->Common_model->getRecords('categories','search_keyword',array('category_id'=>$category_id),'',true);  
			
			if(!empty($getkeyword))
			{
				$search_key = $getkeyword['search_keyword'];
			}else
			{ 
				$getkeyword = $this->Common_model->getRecords('sub_categories','name',array('sub_category_id'=>$category_id),'',true);  
				$search_key = $getkeyword['name'];
			}
			if($place_near_by=='no')
			{  
			 	$records = $this->Common_model->google_busniess_list($search_key,$latitude,$longitude);
			}else
			{ 
				 $records = $this->Common_model->google_busniess_list($search_key,$explode_lat[1],$explode_lat[2],'places_near_by');
			}
		
		$data='';
		$index=0;
		$google_record = array();
		if(!empty($records['data']))
		{
			foreach ($records['data'] as $record) {
				// $check_name =$this->Common_model->getRecords('business_page','business_name,business_full_name',array('business_full_name'=>$record['name'],'is_deleted'=>'0'),'',true);
				$check_name =$this->Common_model->getRecords('business_page','business_name,business_full_name',array('google_place_id'=>$record['place_id'],'is_deleted'=>'0'),'',true);

				if(empty($check_name))
				{
				    $distance = get_distance($record['lat'],$record['lng'], $latitude,$longitude, "K");   
					
					$check_clam =$this->Common_model->getRecords('google_business_clam','busniess_id',array('user_id'=>$user_id,'busniess_id'=>$record['place_id']),'',true);
					if(!empty($check_clam))
					{
						 $google_record[$index]['is_clam'] =  'yes';
					}else
					{
						 $google_record[$index]['is_clam'] =  'no';
					}

				    $google_record[$index]['data_type'] =  'google';
					$google_record[$index]['business_name'] =  $record['name'];
					$google_record[$index]['status'] =  'unverified';
					$google_record[$index]['sponsored'] =  'no';
					$google_record[$index]['page_id'] =  $record['place_id'];
					$google_record[$index]['user_id'] =  '';
					$google_record[$index]['email'] =  '';
					$google_record[$index]['mobile'] =  '';
					$google_record[$index]['latitude'] = $record['lat'];
					$google_record[$index]['longitude'] = $record['lng'];
					$google_record[$index]['category_name'] = ucfirst(str_replace('_',' ',$record['category'][0]));
					if(isset($record['category'][1]))
					{
						$sub_cat = $record['category'][1];
					}else
					{
						$sub_cat = '';
					}
					$google_record[$index]['sub_category_name'] = ucfirst(str_replace('_',' ',$sub_cat));
					$google_record[$index]['distance'] = (string)round($distance,2);
					$google_record[$index]['rating'] =  (string)$record['rating'];
					$google_record[$index]['from_price'] =(string)$record['from_price']; 
					$google_record[$index]['to_price'] =(string)$record['to_price']; 
					$google_record[$index]['images'][0]['file_path'] = $record['image_url'];
				 	$index++;
			 	}
			} 
		}  


		$final_arr= array_merge($unsponsored,$google_record);
		$unsponsored = multid_sort($final_arr, 'distance','ASC'); 
		
		if(!empty($unsponsored))
		{ 
			$data = $this->Common_model->getCalculatedResult($sponsored,$unsponsored);
		} 

		if(!empty($sponsored))
		{
			$spons = 'yes';
		}else
		{
			$spons = 'no';
		}

		if($data) {
			$response = array('data'=> array('status'=>'1','msg'=>'page list','details'=>$data,'sponsored_page'=>$spons));
			echo json_encode($response); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'msg' => 'Record not found !!'));
			echo json_encode($err); exit;
		}

	}

	public function business_search()
	{
		$this->check_login();
		$user_id  =			$this->test_input($this->input->post('user_id'));
		$search_type  =		$this->test_input($this->input->post('search_type'));
		$search_keyword  =	$this->test_input($this->input->post('search_keyword'));
		$lat   			 =	$this->test_input($this->input->post('lat'));
		$lng  			 =  $this->test_input($this->input->post('lng'));
		
		if(empty($search_type)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter search type.'));
			echo json_encode($err); exit;
		}elseif($search_type!='places' && $search_type!='business')
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'search type must be places Or business.'));
			echo json_encode($err); exit;
		}
		if(empty($lat) || empty($lng)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter latitude and longitude .'));
			echo json_encode($err); exit;
		}

		if(empty($search_keyword))
		{
		 	$record_listing =$this->App_model->search_business('',$search_type,$lat,$lng);
		}else
		{
			$record_listing =$this->App_model->search_business($search_keyword,$search_type,$lat,$lng);
		}	
		 
		$index = 0;
		$index2 = 0;
		$sponsored = array();
		$unsponsored = array(); 


		if($search_type=='business')
		{  		 
			foreach ($record_listing as $offers) { 

				if($offers['sponsored']=='Yes')
				{

					$sponsored[$index] = $offers; 

					// $sub_cat_id = $offers['sub_category_id'];
					$sponsored[$index]['business_name'] = $offers['business_full_name'];
					
					$sub_cat_id = $offers['sub_category_id'];
					$sub_categories_name = array();
					if(!empty($sub_cat_id))
					{ 
						$qama_saprate = explode(',',$sub_cat_id);
						foreach ($qama_saprate as $list) {
								$sub_categories_name[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
					}
					$sub_cat_id2 = $offers['sub_category_id2'];
					$sub_categories_name2 = array();
					if(!empty($sub_cat_id2))
					{
						
						$qama_saprate = explode(',',$sub_cat_id2);
						foreach ($qama_saprate as $list) {
								$sub_categories_name2[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
					}
					$sub_cat_id = $offers['sub_category_id3'];
					$sub_categories_name3 = array();
					if(!empty($sub_cat_id))
					{ 
						$qama_saprate = explode(',',$sub_cat_id);
						foreach ($qama_saprate as $list) {
								$sub_categories_name3[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
					}

					$array_merge = array_merge($sub_categories_name,$sub_categories_name2,$sub_categories_name3);
					$new_arr = array_unique($array_merge);
					$sponsored[$index]['sub_category_name']  = implode(', ',$new_arr);


					$where = array('business_page_id' => $offers['page_id']);
	   	
				 	$get_featured_images = $this->Common_model->getRecords('featured_images','image as file_path,id as business_img_id',$where,'order_number ASC',false);
				 	if(empty($get_featured_images))
					{
						if($post_images = $this->Common_model->getRecordsimg('business_img','file_path,business_img_id',$where,'is_star',false,0)) {
							$sponsored[$index]['image'] = $post_images;
						} else {
							$sponsored[$index]['image']= $post_images;	
						} 
					}else
					{
						$sponsored[$index]['image'] = $get_featured_images;
					}
					

					$tableName = 'business_page';
					$where = array('business_page_id' => $offers['page_id']);

					$redeem=$this->Common_model->getRecords('business_page','disply_rating,category_id',$where,'',true);
					if($redeem['disply_rating']=='verified_rating'){

						$disply_rating ='verified';
					}else {
						$disply_rating ='';
					}

					$sponsored[$index]['rating']=  (string)$this->Common_model->business_rating($offers['page_id'],$disply_rating);

					$index ++;	
				}else
				{

					$unsponsored[$index2] = $offers; 

					// $sub_cat_id = $offers['sub_category_id'];
					$unsponsored[$index2]['business_name'] = $offers['business_full_name'];
						
					$sub_cat_id = $offers['sub_category_id'];
					$sub_categories_name = array();
					if(!empty($sub_cat_id))
					{ 
						$qama_saprate = explode(',',$sub_cat_id);
						foreach ($qama_saprate as $list) {
								$sub_categories_name[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						
					}
					$sub_cat_id2 = $offers['sub_category_id2'];
					$sub_categories_name2 = array();
					if(!empty($sub_cat_id2))
					{
						
						$qama_saprate = explode(',',$sub_cat_id2);
						foreach ($qama_saprate as $list) {
								$sub_categories_name2[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
						
					}
					$sub_cat_id = $offers['sub_category_id3'];
					$sub_categories_name3 = array();
					if(!empty($sub_cat_id))
					{ 
						$qama_saprate = explode(',',$sub_cat_id);
						foreach ($qama_saprate as $list) {
								$sub_categories_name3[]= ucfirst(get_sub_categories_name($list,'no'));
						}
					
					}

					$array_merge = array_merge($sub_categories_name,$sub_categories_name2,$sub_categories_name3);
					$new_arr = array_unique($array_merge);
					$unsponsored[$index2]['sub_category_name']  = implode(', ',$new_arr);

					$where = array('business_page_id' => $offers['page_id']);
	   	
				 	$get_featured_images = $this->Common_model->getRecords('featured_images','image as file_path,id as business_img_id',$where,'order_number ASC',false);
				 	if(empty($get_featured_images))
					{
						if($post_images = $this->Common_model->getRecordsimg('business_img','file_path,business_img_id',$where,'is_star',false,0)) {
							$unsponsored[$index2]['image'] = $post_images;
						} else {
							$unsponsored[$index2]['image']= $post_images;	
						} 
					}else
					{
						$unsponsored[$index2]['image'] = $get_featured_images;
					}
					

					$tableName = 'business_page';
					$where = array('business_page_id' => $offers['page_id']);

					$redeem=$this->Common_model->getRecords('business_page','disply_rating,category_id',$where,'',true);
					if($redeem['disply_rating']=='verified_rating'){

						$disply_rating ='verified';
					}else {
						$disply_rating ='';
					}

						$unsponsored[$index2]['rating']=  (string)$this->Common_model->business_rating($offers['page_id'],$disply_rating);

					$index2 ++;
				}
			}
		}else
		{   
			 // echo "<pre>";print_r($record_listing);die;
			if(!empty($record_listing))
			{
				foreach ($record_listing as $offers) { 
				 	if($offers['sponsored']=='Yes')
					{
						$sponsored[$index] =  $offers; 
						$sponsored[$index]['anything_id'] = "places @".$offers['latitude'].'@'.$offers['longitude']; 
						$index ++;
					}else
					{
						$unsponsored[$index2] =  $offers; 
						$unsponsored[$index2]['anything_id'] = "places @".$offers['latitude'].'@'.$offers['longitude']; 
						$index2 ++;

					}
				}  

			}
			
		}


		/***************************************** New Google Changes Start **************************************/
		
		

			if(!empty($search_keyword))
			{
				if($search_type=='places')
				{
					$records = $this->Common_model->google_busniess_list($search_keyword,$lat,$lng,'places'); 
				}else
				{
					$records = $this->Common_model->google_busniess_list($search_keyword,$lat,$lng);
				}
				
			}else
			{
				$records = ''; 
			}
 
		 
		$index=0;
		$google_record = array();
		if(!empty($records['data']))
		{
			// echo '123';die;
			// echo "<pre>";print_r($records['data']);die;
			foreach ($records['data'] as $record) {
				$name_record =  strtolower(str_replace(" ","",$record["name"]));
				$check_name =$this->Common_model->getRecords('business_page','business_name,business_full_name',array('google_place_id'=>$record['place_id'],'is_deleted'=>'0'),'',true);
				if(empty($check_name))
				{
				    $distance = get_distance($record['lat'], $record['lng'], $lat,$lng, "K");   
				 	$check_clam =$this->Common_model->getRecords('google_business_clam','busniess_id',array('user_id'=>$user_id,'busniess_id'=>$record['place_id']),'',true);
					if(!empty($check_clam))
					{
						 $google_record[$index]['is_clam'] =  'yes';
					}else
					{
						 $google_record[$index]['is_clam'] =  'no';
					}
				    $google_record[$index]['data_type'] =  'google';
					$google_record[$index]['business_name'] =  $record['name'];
					$google_record[$index]['latitude'] = $record['lat'];
					$google_record[$index]['longitude'] = $record['lng'];
					$google_record[$index]['page_id'] =  $record['place_id'];
					$google_record[$index]['address_1'] =  $record['address'];
					$google_record[$index]['address_2'] =  $record['address']; 
					$google_record[$index]['distance'] = (string)round($distance,2);
					$google_record[$index]['from_price'] =(string)$record['from_price']; 
					$google_record[$index]['to_price'] =(string)$record['to_price']; 
					if($search_type=='places')
					{
						$google_record[$index]['anything_id'] ="places @".$record['lat']."@".$record['lng']; 
					}
					if($search_type=='business')
					{  
						$google_record[$index]['status'] =  'unverified';
						$google_record[$index]['sponsored'] =  'no';
						$google_record[$index]['email'] =  '';
						$google_record[$index]['mobile'] =  '';
						if(isset($record['category'][0]))
						{
							$google_record[$index]['category_name'] =  ucfirst(str_replace('_',' ',$record['category'][0]));
						}else
						{
							$google_record[$index]['category_name']='';
						}
						
						if(isset($record['category'][1]))
						{
							$google_record[$index]['sub_category_name'] = ucfirst(str_replace('_',' ',$record['category'][1]));
						}else
						{
							$google_record[$index]['sub_category_name'] = '';
						}

					 
						$google_record[$index]['rating'] =  (string)$record['rating'];
						$google_record[$index]['image'][0]['file_path'] = $record['image_url'];
					}
				 	$index++;
			 	}
			}
		} 

		$final_arr= array_merge($unsponsored,$google_record);
		// echo "<pre>";print_r($final_arr);die;
		$unsponsored = multid_sort($final_arr, 'distance','ASC'); 
		
		if(!empty($unsponsored))
		{ 
			$data = $this->Common_model->getCalculatedResult($sponsored,$unsponsored);
		}else
		{
			$data = '';		
		}  

		if(!empty($sponsored))
		{
			$spons = 'yes';
		}else
		{
			$spons = 'no';
		}
 
		if($data){
			$response = array('data'=> array('status'=>'1','msg'=>'list','details'=>$data,'sponsored_page'=>$spons));
			echo json_encode($response); exit;
		}else{
			$err = array('data' =>array('status' => '0', 'msg' => 'Record Not found.'));
			echo json_encode($err); exit;
		}


	}
 



	public function notifications_page()
	{
		$this->check_login();
		$page_id =	$this->test_input($this->input->post('page_id'));

		$where = array('business_page_id' =>$page_id);
		$update_data = array('badge_count' => 0);
        
        $this->Common_model->addEditRecords('business_page',$update_data,$where);
		$notifications = $this->App_model->getNotificationspage($page_id); 

        if($notifications)
			{	$index=0;	
				foreach ($notifications as  $post_list) {
					if($post_list['type']=='verified') {
						$notifications[$index]['username']='Travelouder';
						$notifications[$index]['profile_pic']='resources/Travelouder.png';
					}
					if($post_list['type']=='category') {
						$notifications[$index]['username']='Travelouder';
						$notifications[$index]['profile_pic']='resources/Travelouder.png';
					}
					$where = array('business_page_id' => $page_id);

					$resuser=$this->Common_model->getRecords('business_page','*',$where,'',true);

					if($post_list['type']=='subscription') {
						$notifications[$index]['username']=$resuser['business_name'];
						$notifications[$index]['profile_pic']=$resuser['business_image'];
					}

				    $array = explode('@',$post_list['info']);
					$nekey='';
					$nevalue='';
					$newarr = array();
					foreach ($array as  $value) {
				 	
				 	$arrays = explode('-',$value);
						 foreach ($arrays as $key =>  $value5) {
							 
							 if($key==0)
							 {
							 	//$arr[$value5].'<br>';
							 	$nekey= $value5;
						
							 }else
							 {
								$nevalue=  $value5;
							 }	
							 $newarr[$nekey] = $nevalue; 	
							} 
						

			 } 
			 $notifications[$index]['info']=$newarr;
			
			 	$index++;
			}
			$response = array('data'=> array('status'=>'1','msg'=>'notifications','details'=>$notifications));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'msg' => 'notifications not found !!'));
			echo json_encode($err); exit;
        }


	}



	public function sponsoredPage()
	{
		$this->check_login();
		$user_id  	=		$this->test_input($this->input->post('user_id'));
		$latitude  =		$this->test_input($this->input->post('lat'));
		$longitude  =		$this->test_input($this->input->post('lng'));
		$category_id  =		$this->test_input($this->input->post('category_id'));

	 	if(empty($latitude)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter latitude.'));
			echo json_encode($err); exit;
		} 
		if(empty($longitude)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter longitude.'));
			echo json_encode($err); exit;
		} 
		if(empty($category_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter category id.'));
			echo json_encode($err); exit;
		}
		$data = array();
	 	//$get_pages = $this->App_model->all_sponsoredPage($latitude,$longitude,'yes',100000000);
	 	$get_pages = $this->App_model->getAllsponsorpage($category_id,$latitude,$longitude);
		$index= 0;
		foreach ($get_pages as $pages) {
		  			 $data[$index] = $pages;
		 		 

	 				$sub_cat_name = $pages['sub_category_name'];
					if(!empty($pages['sub_category_id2']))
					{
						$sub_cat_name .=', '.get_sub_categories_name($pages['sub_category_id2']);
					}
					if(!empty($pages['sub_category_id3']))
					{
						$sub_cat_name .=', '.get_sub_categories_name($pages['sub_category_id3']);
					} 
					$data[$index]['sub_category_name'] =$sub_cat_name;	
					if(!isset($data[$index]['sub_category_name']))
  					{
  						$data[$index]['sub_category_name'] = '';
  					}


		  			$select_image = $this->Common_model->getRecordsimg('business_img','file_path',array('business_page_id'=>$pages['page_id']),'is_star',false,0);  
		  			$get_rating = $this->App_model ->get_rating_avg($pages['page_id']);
		  			$data[$index]['rating'] = $get_rating['rating'];
		  			$data[$index]['images'] = $select_image;

		$index ++;
		} 
		if($data)
		{
			$response = array('data'=> array('status'=>'1','msg'=>'page list','details'=>$data));
			echo json_encode($response); exit;
		}else
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Page not found!!'));
			echo json_encode($err); exit;
		}

	}


	public function notifications()
	{
		$this->check_login();
		$user_id =	$this->test_input($this->input->post('user_id'));
		$where = array('user_id' =>$user_id);
		$update_data = array('badge_count' => 0);
        
        $this->Common_model->addEditRecords('users',$update_data,$where);

		$notifications = $this->App_model->getNotifications($user_id); 
		if($notifications)
			{	$index=0;	
				foreach ($notifications as  $post_list) {
				    $array = explode('@',$post_list['info']);
					$nekey='';
					$nevalue='';
					$newarr = array();
					foreach ($array as  $value) {
				 	
				 	$arrays = explode('-',$value);
						 foreach ($arrays as $key =>  $value5) {
							 
							 if($key==0)
							 {
							 	//$arr[$value5].'<br>';
							 	$nekey= $value5;
						
							 }else
							 {
								$nevalue=  $value5;
							 }	
							 $newarr[$nekey] = $nevalue; 	
							} 
						

			 } 
			 $notifications[$index]['info']=$newarr;
			
			 	$index++;
			}
			
			$notifications = multid_sort($notifications, 'notification_sent_datetime','Desc'); 
			

			$response = array('data'=> array('status'=>'1','msg'=>'notifications','details'=>$notifications));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'msg' => 'notifications not found !!'));
			echo json_encode($err); exit;
        }
	}
 
	


	// public function offerNearby()
	// {
	// 	$this->check_login();
	// 	$user_id  =		$this->test_input($this->input->post('user_id'));
	// 	$latitude  =		$this->test_input($this->input->post('lat'));
	// 	$longitude  =		$this->test_input($this->input->post('lng'));
	// 	$category_id  =		$this->test_input($this->input->post('category_id'));
	// 	if(empty($latitude)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter latitude.'));
	// 		echo json_encode($err); exit;
	// 	} 
	// 	if(empty($longitude)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter longitude.'));
	// 		echo json_encode($err); exit;
	// 	} 
	// 	if(empty($category_id)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter category id.'));
	// 		echo json_encode($err); exit;
	// 	} 


	// 	if (strpos($category_id,'places') !== false) 
	// 	{ 
	// 		$place_near_by = 'yes';
	// 		$explode_lat = explode('@',$category_id);
	// 	}else
	// 	{ 
	// 		$place_near_by = 'no'; 
	// 	}

	// 	$data = array();
 


	// 	$get_user = array("user_id"=>$user_id,"status"=>'active');
	// 	$get_user_details= $this->Common_model->getRecords('users','user_id,country_id,state_id,city_id',$get_user,'',true);  	
 // 	 	if(!empty($get_user_details['country_id']) || !empty($get_user_details['state_id']) || !empty($get_user_details['city_id']))
 // 		{

 // 			$user_country_id = $get_user_details['country_id'];
 // 			$user_state_id = $get_user_details['state_id'];
 // 			$user_city_id = $get_user_details['city_id'];

 // 		}else{

	// 		$deal_lat=$latitude;
	// 		$deal_long=$longitude;
	// 		$geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?Key='.GOOGLE_API_KEY.'&latlng='.$deal_lat.','.$deal_long.'&sensor=false');

	//         $output= json_decode($geocode);
	//       //  echo "<pre>";print_r($output);die;
	// 		if(!empty($output))
	// 		{ 
	// 			if(isset($output->results[0]->address_components))
	// 			{
	// 				for($j=0;$j<count($output->results[0]->address_components);$j++) {
	// 		           	$cn=array($output->results[0]->address_components[$j]->types[0]);
						
	// 					if(in_array("locality", $cn)){
	// 						$city_name= $output->results[0]->address_components[$j]->long_name;
	// 					}
						
	// 					if(in_array("administrative_area_level_1", $cn)){
	// 						$state_name= $output->results[0]->address_components[$j]->long_name;
	// 					}	
						
	// 					if(in_array("country", $cn)){
	// 						$country_name=  $output->results[0]->address_components[$j]->long_name;
	// 					}
	// 	        	}

	// 			}else
	// 			{
	// 				$city_name='';
	// 	        	$state_name='';
	// 	        	$country_name='';
	// 			}
			    
	//         }else
	//         {
	//         	$city_name='';
	//         	$state_name='';
	//         	$country_name='';
	//         } 


	            
	//         $get_user_details= $this->App_model->get_county_state_city_id('cities','id,name,state_id','name',$city_name);  
	//         $get_states= $this->Common_model->getRecords('states','id,name,country_id',array('id'=>$get_user_details[0]['state_id']),'',true);  
	//         //print_r( $get_user_details[0]['state_id']);die;
	// 		$user_country_id = $get_states['country_id'];
	// 		$user_state_id = $get_user_details[0]['state_id'];
	// 		$user_city_id = $get_user_details[0]['id'];
 // 		} 



	// 	$where_offer = array("is_deleted"=>'0');
	// 	if($place_near_by=='no')
	// 	{ 
	// 		$get_offers= $this->App_model->get_near_by_offer($category_id,$latitude,$longitude);  	
	// 		//echo $this->db->last_query();die;
	// 	}else
	// 	{ 
	// 		$get_offers= $this->App_model->get_near_by_offer_with_place($explode_lat[1],$explode_lat[2]);  	
	// 	}


	// 	$index = 0;
	// 	$record ='0';
	// 	$get_offer =array();
	// 	// echo "<pre>";print_r($get_offers );die;
	// 	foreach ($get_offers as $offers) {

	// 		if(empty($offers['country_id']) && empty($offers['state_id']) && empty($offers['city_id']))
	// 		{ 
	// 			$get_offer[$index] =  $offers; 


	// 				$sub_cat_id = $offers['sub_category_id'];
	// 				$sub_categories_name = array();
	// 				if(!empty($sub_cat_id))
	// 				{ 
	// 					$qama_saprate = explode(',',$sub_cat_id);
	// 					foreach ($qama_saprate as $list) {
	// 							$sub_categories_name[]= ucfirst(get_sub_categories_name($list,'no'));
	// 					}
					
	// 					// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
	// 				}
	// 				$sub_cat_id2 = $offers['sub_category_id2'];
	// 				$sub_categories_name2 = array();
	// 				if(!empty($sub_cat_id2))
	// 				{
						
	// 					$qama_saprate = explode(',',$sub_cat_id2);
	// 					foreach ($qama_saprate as $list) {
	// 							$sub_categories_name2[]= ucfirst(get_sub_categories_name($list,'no'));
	// 					}
					
	// 					// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
	// 				}
	// 				$sub_cat_id = $offers['sub_category_id3'];
	// 				$sub_categories_name3 = array();
	// 				if(!empty($sub_cat_id))
	// 				{ 
	// 					$qama_saprate = explode(',',$sub_cat_id);
	// 					foreach ($qama_saprate as $list) {
	// 							$sub_categories_name3[]= ucfirst(get_sub_categories_name($list,'no'));
	// 					}
					
	// 					// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
	// 				}

	// 				$array_merge = array_merge($sub_categories_name,$sub_categories_name2,$sub_categories_name3);
	// 				$new_arr = array_unique($array_merge);
	// 				$get_offer[$index]['sub_category_name']  = implode(', ',$new_arr);

 






	// 			$offer_image= array("offer_id"=>$offers['business_offers']);

	// 			$get_offer[$index]['offer_image'] = $this->Common_model->getRecords('offers_images','id,file_path',$offer_image,'',false);  
	// 			if($offers['offers_type'] =='multi_buy'){

	// 				$where_type= array("business_offers_id"=>$offers['business_offers']);
	// 			 	$get_offer[$index]['multi_buy']= $this->Common_model->getRecords('multi_buy','discount_type,buy,buy_text,get,get_text,note',$where_type,'',true);  
	// 			}else{
	// 				$get_offer[$index]['multi_buy']='';
	// 			} 
	// 			if($offers['offers_type'] =='standard_discount'){

	// 				$where_type= array("business_offers_id"=>$offers['business_offers']);
	// 			 	$get_offer[$index]['standard_discount']= $this->Common_model->getRecords('standard_discount','discount_type,discount_value,product_note,product_description',$where_type,'',true);  
	// 			}else
	// 			{
	// 				$get_offer[$index]['standard_discount']='';
	// 			}
	// 			$get_rating2 = $this->App_model ->get_rating_avg($offers['page_id']);
 //  				$get_offer[$index]['rating'] = $get_rating2['rating'];

	// 		$index++;	
	// 		$record ='1';
	// 		}else{
				
	//  		 	$show = 'no';

	//  			if(!empty($offers['city_id']))
	//  			{
	//  				$city = explode(',',$offers['city_id']);
	//  				foreach ($city as $cid) {
	// 	 				 if($user_city_id == $cid) {
	// 	 				  	$show ='yes'; 
	// 	 				 }
	//  				}
	//  			}elseif (!empty($offers['state_id'])) {
 // 					$state = explode(',',$offers['state_id']);
	//  				foreach ($state as $sid) {
	// 	 				 if($user_state_id == $sid) {
	// 	 				 	$show ='yes';
	// 	 				 }
	//  				}
	//  			}elseif (!empty($offers['country_id'])) {
	//  				$country = explode(',',$offers['country_id']);
	//  				foreach ($country as $cid) {
	// 	 				 if($user_country_id == $cid) {
	// 	 				 	$show ='yes';
	// 	 				 }
	//  				}
	//  			}

	//  			if($show =='yes'){
	// 				$get_offer[$index] =  $offers; 

	// 				$sub_cat_id = $offers['sub_category_id'];
	// 				$sub_categories_name = array();
	// 				if(!empty($sub_cat_id))
	// 				{ 
	// 					$qama_saprate = explode(',',$sub_cat_id);
	// 					foreach ($qama_saprate as $list) {
	// 							$sub_categories_name[]= ucfirst(get_sub_categories_name($list,'no'));
	// 					}
					
	// 					// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
	// 				}
	// 				$sub_cat_id2 = $offers['sub_category_id2'];
	// 				$sub_categories_name2 = array();
	// 				if(!empty($sub_cat_id2))
	// 				{
						
	// 					$qama_saprate = explode(',',$sub_cat_id2);
	// 					foreach ($qama_saprate as $list) {
	// 							$sub_categories_name2[]= ucfirst(get_sub_categories_name($list,'no'));
	// 					}
					
	// 					// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
	// 				}
	// 				$sub_cat_id = $offers['sub_category_id3'];
	// 				$sub_categories_name3 = array();
	// 				if(!empty($sub_cat_id))
	// 				{ 
	// 					$qama_saprate = explode(',',$sub_cat_id);
	// 					foreach ($qama_saprate as $list) {
	// 							$sub_categories_name3[]= ucfirst(get_sub_categories_name($list,'no'));
	// 					}
					
	// 					// $sponsored[$index]['sub_category_name']  = implode(' , ',$sub_categories_name);
	// 				}

	// 				$array_merge = array_merge($sub_categories_name,$sub_categories_name2,$sub_categories_name3);
	// 				$new_arr = array_unique($array_merge);
	// 				$get_offer[$index]['sub_category_name']  = implode(', ',$new_arr);

 








	// 				$offer_image= array("offer_id"=>$offers['business_offers']);

	// 				$get_offer[$index]['offer_image'] = $this->Common_model->getRecords('offers_images','id,file_path',$offer_image,'',false);  
	// 				if($offers['offers_type'] =='multi_buy'){

	// 					$where_type= array("business_offers_id"=>$offers['business_offers']);
	// 				 	$get_offer[$index]['multi_buy']= $this->Common_model->getRecords('multi_buy','discount_type,buy,buy_text,get,get_text,note',$where_type,'',true);  
	// 				}else{
	// 					$get_offer[$index]['multi_buy']='';
	// 				} 
	// 				if($offers['offers_type'] =='standard_discount'){

	// 					$where_type= array("business_offers_id"=>$offers['business_offers']);
	// 				 	$get_offer[$index]['standard_discount']= $this->Common_model->getRecords('standard_discount','discount_type,discount_value,product_note,product_description',$where_type,'',true);  
	// 				}else
	// 				{
	// 					$get_offer[$index]['standard_discount']='';
	// 				}
	// 				$get_rating2 = $this->App_model ->get_rating_avg($offers['page_id']);
	//   				$get_offer[$index]['rating'] = $get_rating2['rating'];
	// 				$record ='1';
	// 				$index++;
	// 			}

			
			
	// 		} 
	// 	}

	// 	if($record ==1)
	// 	{
	// 		$response = array('data'=> array('status'=>'1','msg'=>'Offer list','details'=>$get_offer));
	// 		echo json_encode($response); exit;
 //        }else
 //        {
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Offers not found !!'));
	// 		echo json_encode($err); exit;
 //        }

	// }




	// public function nearbyBusiness_with_filter()
	// {
	// 	$this->check_login();
	// 	$user_id  	=		$this->test_input($this->input->post('user_id'));
	// 	$latitude  =		$this->test_input($this->input->post('lat'));
	// 	$longitude  =		$this->test_input($this->input->post('lng'));
	// 	$rating  =			$this->test_input($this->input->post('rating'));
	// 	$nearest  =			$this->test_input($this->input->post('nearest'));
	// 	$categories  =		$this->test_input($this->input->post('categories'));
	// 	$next  			 =  $this->input->post('next');
	// 	$page 			 =  $this->input->post('page');
		
	//  	if(empty($latitude)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter latitude.'));
	// 		echo json_encode($err); exit;
	// 	} 
	// 	if(empty($longitude)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter longitude.'));
	// 		echo json_encode($err); exit;
	// 	}
	// 	if(empty($page)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page number.'));
	// 		echo json_encode($err); exit;
	// 	}  
	// 	$offset =  ($page-1) * 3; 
	// 	$data = array();
	//  	$get_pages = $this->App_model->get_near_by_page_with_filter($latitude,$longitude,'Yes',2,$offset,$rating,$nearest,$categories);
	// 	$index= 0;

	// 	foreach ($get_pages as $pages) {
	// 		$data[$index] = $pages;
	// 		$select_image = $this->Common_model->getRecordsimg('business_img','file_path',array('business_page_id'=>$pages['page_id']),'is_star',false,0); 
	// 		$data[$index]['images'] = $select_image;

	// 		$index ++;
	// 	} 
 // 		$get_pages2 = $this->App_model->get_near_by_page_with_filter($latitude,$longitude,'No',3,$offset,$rating,$nearest,$categories);
		
	// 	foreach ($get_pages2 as $pages2) {
	// 		 $data[$index] = $pages2;
 //  			$select_image2 = $this->Common_model->getRecordsimg('business_img','file_path',array('business_page_id'=>$pages2['page_id']),'is_star',false,0); 
 //  			$data[$index]['images'] = $select_image2;

	// 		$index ++;
	// 	} 

	// 	/********************************************************* New Google Changes Start ************************************************************/
	// 	if(!empty($next))
	// 	{ 
	// 		if($next=='start')
	// 		{
	// 			$next = '';
	// 		}


	//  		if(!empty($categories)){
	// 		 $get_cate_name= $this->Common_model->getRecords('categories','',array('category_id'=>$categories[0]),'',true);  
	// 		 if(!empty($get_cate_name))
	// 		 {
	// 		 	$records = $this->Common_model->google_busniess_list($next,'',$get_cate_name['name'],$latitude,$longitude);
	// 		 }else
	// 		 {
	// 		 	$records = $this->Common_model->google_busniess_list($next,'','food',$latitude,$longitude);
	// 		 }
	// 		}else
	// 		{
	// 			$records = $this->Common_model->google_busniess_list($next,'','food',$latitude,$longitude);
	// 		}
	// 	}else
	// 	{
	// 		$records='';
	// 	}
		 
	// 	$index=0;
	// 	$new_arr = array();
	// 	if(!empty($records['data']))
	// 	{
	// 		foreach ($records['data'] as $record) {
	// 			// echo $namee= $record["name"];
	// 			$name_record =  strtolower(str_replace(" ","",$record["name"]));
	// 			$check_name =$this->Common_model->getRecords('business_page','business_name,business_full_name',array('google_place_id'=>$record['place_id'],'is_deleted'=>'0'),'',true);
	// 			if(empty($check_name))
	// 			{
	// 			    $distance = get_distance($record['lat'], $record['lng'], $latitude,$longitude, "K");   
	// 				$check_clam =$this->Common_model->getRecords('google_business_clam','busniess_id',array('user_id'=>$user_id,'busniess_id'=>$record['place_id']),'',true);
	// 				if(!empty($check_clam))
	// 				{
	// 					 $new_arr[$index]['is_clam'] =  'yes';
	// 				}else
	// 				{
	// 					 $new_arr[$index]['is_clam'] =  'no';
	// 				}
	// 			    $new_arr[$index]['data_type'] =  'google';
	// 				$new_arr[$index]['business_name'] =  $record['name'];
	// 				$new_arr[$index]['status'] =  'unverified';
	// 				$new_arr[$index]['sponsored'] =  'no';
	// 				$new_arr[$index]['page_id'] =  $record['place_id'];
	// 				$new_arr[$index]['user_id'] =  '';
	// 				$new_arr[$index]['email'] =  '';
	// 				$new_arr[$index]['mobile'] =  '';
	// 				$new_arr[$index]['latitude'] = $record['lat'];
	// 				$new_arr[$index]['longitude'] = $record['lng'];
	// 				$new_arr[$index]['category_name'] = $record['category'][0];
	// 				$new_arr[$index]['sub_category_name'] = $record['category'][1];
	// 				$new_arr[$index]['distance'] = (string)round($distance,2);
	// 				$new_arr[$index]['rating'] =  $record['rating'];
	// 				$new_arr[$index]['images'][0]['file_path'] = $record['image_url'];
	// 			 	$index++;
	// 		 	}
	// 		}
	// 	} 

	// 	$final_arr= array_merge($data,$new_arr);
	// 	if($rating =='yes')
	// 	{
	// 		$sorting='rating';	
	// 		$order ='Desc';
			
	// 	}elseif($nearest=='yes') {

	// 		$sorting='distance';
	// 		$order ='ASC';	
	// 	}
		 
		
	// 	//$data = multid_sort($final_arr,$sorting,$order);
	// 	$data = $final_arr;
	// 	//echo "<pre>";print_r($data);die;
	// 	/********************************************************* New Google Changes End ************************************************************/

	// 	if(isset($records['next_page_token']))
	// 	{
	// 		$next_t = $records['next_page_token'];
	// 	}else
	// 	{
	// 		$next_t = '';
	// 	}
	// 	if(isset($records['google_status']))
	// 	{
	// 		$google_status = $records['google_status'];
	// 	}else
	// 	{
	// 		$google_status = '';
	// 	}
 

	// 	if($data)
	// 	{
	// 		$response = array('data'=> array('status'=>'1','next'=>$next_t,'msg'=>'page list','details'=>$data,'current_page'=>$page,'google_status'=>$google_status));
	// 		echo json_encode($response); exit;
	// 	}else
	// 	{
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Page not found!!'));
	// 		echo json_encode($err); exit;
	// 	}


	// }

	// public function offerNearby_with_filter()
	// {


	// 	$this->check_login();
	// 	$user_id  =			$this->test_input($this->input->post('user_id'));
	// 	$latitude  =		$this->test_input($this->input->post('lat'));
	// 	$longitude  =		$this->test_input($this->input->post('lng'));
	//  	$rating  =			$this->test_input($this->input->post('rating'));
	// 	$nearest  =			$this->test_input($this->input->post('nearest'));
	// 	$categories  =		$this->test_input($this->input->post('categories')); 
	// 	if(empty($latitude)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter latitude.'));
	// 		echo json_encode($err); exit;
	// 	} 
	// 	if(empty($longitude)){
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Please enter longitude.'));
	// 		echo json_encode($err); exit;
	// 	} 

	// 	$deal_lat=$latitude;
	// 	$deal_long=$longitude;
	// 	$geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?Key='.GOOGLE_API_KEY.'&latlng='.$deal_lat.','.$deal_long);

 //     	$output= json_decode($geocode);
		 	
	//     for($j=0;$j<count($output->results[0]->address_components);$j++) {
 //           	$cn=array($output->results[0]->address_components[$j]->types[0]);
			
	// 		if(in_array("locality", $cn)){
	// 			$city_name= $output->results[0]->address_components[$j]->long_name;
	// 		}
			
	// 		if(in_array("administrative_area_level_1", $cn)){
	// 			$state_name= $output->results[0]->address_components[$j]->long_name;
	// 		}	
			
	// 		if(in_array("country", $cn)){
	// 			$country_name=  $output->results[0]->address_components[$j]->long_name;
	// 		}
 //        }


            
 //        $get_user_details= $this->App_model->get_county_state_city_id('cities','id,name,state_id','name',$city_name);  
 //        $get_states= $this->Common_model->getRecords('states','id,name,country_id',array('id'=>$get_user_details[0]['state_id']),'',true);  
 //        //print_r( $get_user_details[0]['state_id']);die;
	// 	$user_country_id = $get_states['country_id'];
	// 	$user_state_id = $get_user_details[0]['state_id'];
	// 	$user_city_id = $get_user_details[0]['id'];
 // 		//} 



	// 	$where_offer = array("is_deleted"=>'0');
	// 	//$get_offers= $this->App_model->get_near_by_offer($latitude,$longitude);  	
	// 	$get_offers= $this->App_model->get_near_by_offer_with_filter($latitude,$longitude,$rating,$nearest,$categories);  	
	
	// 	$index = 0;
	// 	$record ='0';
	// 	$get_offer =array();
	// 	foreach ($get_offers as $offers) {

	// 		if(empty($offers['country_id']) && empty($offers['state_id']) && empty($offers['city_id']))
	// 		{

	// 			$get_offer[$index] =  $offers; 
	// 			$offer_image= array("offer_id"=>$offers['business_offers']);

	// 			$get_offer[$index]['offer_image'] = $this->Common_model->getRecords('offers_images','id,file_path',$offer_image,'',false);  
	// 			if($offers['offers_type'] =='multi_buy'){

	// 				$where_type= array("business_offers_id"=>$offers['business_offers']);
	// 			 	$get_offer[$index]['multi_buy']= $this->Common_model->getRecords('multi_buy','discount_type,buy,buy_text,get,get_text,note',$where_type,'',true);  
	// 			}else{
	// 				$get_offer[$index]['multi_buy']='';
	// 			} 
	// 			if($offers['offers_type'] =='standard_discount'){

	// 				$where_type= array("business_offers_id"=>$offers['business_offers']);
	// 			 	$get_offer[$index]['standard_discount']= $this->Common_model->getRecords('standard_discount','discount_type,discount_value,product_note,product_description',$where_type,'',true);  
	// 			}else
	// 			{
	// 				$get_offer[$index]['standard_discount']='';
	// 			}

	// 			$index++;	
	// 			$record ='1';
	// 		}else{
				
	//  		 	$show = 'no';

	//  			if(!empty($offers['city_id']))
	//  			{
	//  				$city = explode(',',$offers['city_id']);
	//  				foreach ($city as $cid) {
	// 	 				 if($user_city_id == $cid) {
	// 	 				  	$show ='yes'; 
	// 	 				 }
	//  				}
	//  			}elseif (!empty($offers['state_id'])) {
 // 					$state = explode(',',$offers['state_id']);
	//  				foreach ($state as $sid) {
	// 	 				 if($user_state_id == $sid) {
	// 	 				 	$show ='yes';
	// 	 				 }
	//  				}
	//  			}elseif (!empty($offers['country_id'])) {
	//  				$country = explode(',',$offers['country_id']);
	//  				foreach ($country as $cid) {
	// 	 				 if($user_country_id == $cid) {
	// 	 				 	$show ='yes';
	// 	 				 }
	//  				}
	//  			}

	//  			if($show =='yes'){
	// 				$get_offer[$index] =  $offers; 
	// 				$offer_image= array("offer_id"=>$offers['business_offers']);

	// 				$get_offer[$index]['offer_image'] = $this->Common_model->getRecords('offers_images','id,file_path',$offer_image,'',false);  
	// 				if($offers['offers_type'] =='multi_buy'){

	// 					$where_type= array("business_offers_id"=>$offers['business_offers']);
	// 				 	$get_offer[$index]['multi_buy']= $this->Common_model->getRecords('multi_buy','discount_type,buy,buy_text,get,get_text,note',$where_type,'',true);  
	// 				}else{
	// 					$get_offer[$index]['multi_buy']='';
	// 				} 
	// 				if($offers['offers_type'] =='standard_discount'){

	// 					$where_type= array("business_offers_id"=>$offers['business_offers']);
	// 				 	$get_offer[$index]['standard_discount']= $this->Common_model->getRecords('standard_discount','discount_type,discount_value,product_note,product_description',$where_type,'',true);  
	// 				}else
	// 				{
	// 					$get_offer[$index]['standard_discount']='';
	// 				}
	// 				$record ='1';
	// 				$index++;
	// 			}

			
			
	// 		} 
	// 	}

	// 	if($record ==1)
	// 	{
	// 		$response = array('data'=> array('status'=>'1','msg'=>'Offer list','details'=>$get_offer));
	// 		echo json_encode($response); exit;
 //        }else
 //        {
	// 		$err = array('data' =>array('status' => '0', 'msg' => 'Offers not found !!'));
	// 		echo json_encode($err); exit;
 //        }

	// }



	public function notificationstest()
	{
		// $referrer = iosNotification('cc135a4f0d72d6b724a6fc0d013268a4cbc7a7290685c07494a8d8b093252a3f','test notifications');

	echo	$referrer = androidNotification('ffabouoURgs:APA91bEmVFuaS_dVfEIlkrQe1Fm1LCnXUy2ByWlsDdTRpscyMstIQKYFsu_vrpIvaDP5wzv0YECmqPYQiKXws3FqZ1cJMvJ4n5vHdrUe-YWAJep_18NtudPi8iX0S9qdqZKKoxGQgFBn','test notifications');

	}


	

	public function listRating()
	{
		$this->check_login();
		$user_id 	  =	$this->test_input($this->input->post('user_id'));
		$page_id  	  =	$this->test_input($this->input->post('page_id'));

		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
			echo json_encode($err); exit;
		}
		$tableName = 'business_page';
		$where = array('business_page_id' => $page_id);

		$redeem=$this->Common_model->getRecords('business_page','disply_rating,category_id',$where,'',true);
		if($redeem['disply_rating']=='verified_rating'){

			$disply_rating ='verified';
		}else {
			$disply_rating ='';
		}

		$tableName = 'rating_categories';
		$where = array('category_id' => $redeem['category_id']);
		$rat = $this->Common_model->getRecords($tableName,'rating_categories_id,name',$where,'',false);
		
		for($i=0;$i<count($rat);$i++){
			$rat[$i]['rating'] =  number_format($this->Common_model->business_rating_one($page_id,$disply_rating,$rat[$i]['rating_categories_id']),2); 

		}
	    $data['rating'] =  $rat;
		$data['totleRating'] =  number_format($this->Common_model->business_rating($page_id,$disply_rating),2);
		$data['count'] = $this->Common_model->business_rating_count($page_id,$disply_rating);
		$data['review'] = $this->Common_model->business_review($page_id,$disply_rating);
		if($data){
			$response = array('data'=> array('status'=>'1','msg'=>'Rating','details'=>$data));
			echo json_encode($response); exit;
		}else{
			$err = array('data' =>array('status' => '0', 'msg' => 'Rating not found !!'));
			echo json_encode($err); exit;
			}
	}



	public function analytics()
	{
		$this->check_login();
		$user_id 	  =	 $this->test_input($this->input->post('user_id'));
		$page_id  	  =	 $this->test_input($this->input->post('page_id'));
		$type  	      =  $this->test_input($this->input->post('type'));

		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
			echo json_encode($err); exit;
		}
		if(empty($type)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter type.'));
			echo json_encode($err); exit;
		}elseif($type!='places' && $type!='time')
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Type must be places Or time.'));
			echo json_encode($err); exit;
		}
		if($type=='places'){
			$data = $this->App_model->analytics($page_id,$type); 

			$index=0;
			$location='';
			$total='';
			$check =0;
			foreach ($data as $redeem) {
				if(!empty($redeem['latitude']) && !empty($redeem['longitude']))
				{	
					$check =1;
					$deal_lat=$redeem['latitude'];
					$deal_long=$redeem['longitude'];
					$geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?Key='.GOOGLE_API_KEY.'&latlng='.$deal_lat.','.$deal_long);
			     	$output= json_decode($geocode);
				 	if($output->status=='OK')
				 	{ 
					    for($j=0;$j<count($output->results[0]->address_components);$j++) {
				           	$cn=array($output->results[0]->address_components[$j]->types[0]);
							
							if(in_array("locality", $cn)){
								$city_name= $output->results[0]->address_components[$j]->long_name;
							}
							
							if(in_array("administrative_area_level_1", $cn)){
								$state_name= $output->results[0]->address_components[$j]->long_name;
							}	
							
							if(in_array("country", $cn)){
								$country_name=  $output->results[0]->address_components[$j]->long_name;
							}
						} 
					}else
					{
						$country_name ='';
						$state_name='';
						$city_name='';
					}
					$location[$index] = $redeem;
					$location[$index]['country'] =$country_name;
					$location[$index]['state'] =$state_name;
					$location[$index]['city'] =$city_name;
					$total +=$redeem['total'];
					$index++;
				} 
				if($check==1){
				 	$parset = 100/$total;
				 	$text = 0;
			 	
					foreach ($location as $record) {
						$percentage_count = round($record['total']*$parset,2);
						$new_data[$text]['city'] = $record['city'];
						$new_data[$text]['percentage'] =  $percentage_count;
						$text++; 
					}
				}	
			}
			$city_name_count =array();
			$percentage_count = 0;
			$cities_array = array();
			//echo "<pre>";print_r($new_data);
			if(!empty($new_data)) {
				foreach ($new_data as $row) {
					if(!in_array($row['city'], $cities_array))
					{
						$cities_array[] = $row['city'];
						$city_name_count[$row['city']]= $row['percentage'];
						
					} else {
						$city_name_count[$row['city']] = $city_name_count[$row['city']]+$row['percentage'];
						
					}	
				}
			}


		
			$final_array = array();
			if(!empty($city_name_count)) {
				$indexs = 0;
				foreach ($city_name_count as $key=>$value) {
					if(!empty($key))
					{
						$final_array[$indexs]['city'] = $key;
						$final_array[$indexs]['percentage'] = $value;
						$indexs++;

					}
					
				}
			
			}
			
			if($check=='1'){
				$response = array('data'=> array('status'=>'1','msg'=>'list','details'=>$final_array));
				echo json_encode($response); exit;
			}else{
				$err = array('data' =>array('status' => '0', 'msg' => 'No record found !!'));
				echo json_encode($err); exit;
			}

		}else
		{
			$time = array( '09:00:00-11:59:59','12:00:00-14:59:59','15:00:00-17:59:59','18:00:00-20:59:59','21:00:00-23:59:59','00:00:01-02:59:59','03:00:00-05:59:59','06:00:00-08:59:59');
			$count =0;
			$total_per=0;
			foreach ($time as $times) {
				$explode = explode('-',$times);
		 		$time_record[$count] = $this->App_model->analytics($page_id,$type,$explode[0],$explode[1]);
		 	 
		 		if($time_record[$count])
		 		{
		 			$recor[$count]=1;
		 		}else{
		 			$recor[$count]=0;
		 		}
		 		$total_per +=$time_record[$count];
				$count ++;
			}
			
			if(in_array(1,$recor)){
			 	$parset = 100/$total_per;
		 	  	$total_per;
			 	$re =0;
			 	$arr_name = array('09 AM-12 PM','12 PM-03 PM','03 PM-06 PM','06 PM-09 PM','09 PM -12 AM','12 AM-03 AM','03 AM-06 AM','06 AM-09 AM');
		 	    foreach ($time_record as $record) {
		 	    	if(round($record* $parset,2)!=0){


		 	    	 $newdata[$re]['time'] =  $arr_name[$re];
		 	    	 $newdata[$re]['percentage'] =  round($record* $parset,2);
		 	    $re++;
		 	    }	 
		 	    } 

	 	    }
	 	    if($recor!=0){
	 	    	if(empty($newdata))
	 	    	{
	 	    		$err = array('data' =>array('status' => '0', 'msg' => 'No record found !!'));
					echo json_encode($err); exit;

	 	    	}else
	 	    	{
	 	    		$response = array('data'=> array('status'=>'1','msg'=>'list','details'=>$newdata));
					echo json_encode($response); exit;
	 	    	}
				
			}else{
				$err = array('data' =>array('status' => '0', 'msg' => 'No record found !!'));
				echo json_encode($err); exit;
			}
			  
		}
 
	}

    public function myListRating()
	{
		$this->check_login();
		$user_id 	  =	$this->test_input($this->input->post('user_id'));
		$page_id  	  =	$this->test_input($this->input->post('page_id'));
		$type = $this->test_input($this->input->post('type'));  //google or local
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
			echo json_encode($err); exit;
		}
		if($type=='local')
		{ 
				$tableName = 'business_page';
				$where = array('business_page_id' => $page_id);
				$redeem=$this->Common_model->getRecords('business_page','category_id,business_name',$where,'',true);
				$data['page_name'] =$redeem['business_name'];

				$tableName = 'rating_categories';
				$where = array('category_id' => $redeem['category_id']);
				$rat = $this->Common_model->getRecords($tableName,'rating_categories_id,name',$where,'',false);
				
				for($i=0;$i<count($rat);$i++){
					$rat[$i]['rating'] =  number_format($this->Common_model->business_rating_one1($page_id,$user_id,$rat[$i]['rating_categories_id']),2); 

				}
			    $data['rating'] =  $rat;
				
				$data['review'] = $this->Common_model->business_review1($page_id,$user_id);
				if(empty($data['review'])){
					$data['review']['title']=''; 	
					$data['review']['description']=''; 	
				}

		}else
		{
				$where = array('page_id' => $page_id,'user_id'=>$user_id);
			   	$data['rating'] =  array();
			   $rating = 	$this->Common_model->getRecords('google_page_rating','title,description,rating',$where,'',true);  
			   if(!empty($rating))
			   {
			   	$data['review']=  $rating;
			   }else
			   {
			   		$data['review'] =  (object)array();
			   }
				
		}
		if($data){
			$response = array('data'=> array('status'=>'1','msg'=>'Rating','details'=>$data));
			echo json_encode($response); exit;
		}else{
			$err = array('data' =>array('status' => '0', 'msg' => 'Rating not found !!'));
			echo json_encode($err); exit;
		}
	}

 	public function getUserList() {
    	$this->check_login();
	  	$user_id		 =	$this->test_input($this->input->post('user_id'));
        $post_id		 =	$this->test_input($this->input->post('post_id'));
        $page_id		 =	$this->test_input($this->input->post('page_id'));
        if(empty($post_id)){
        	$result =array('');
        	$page_result =array('');
	        $update_data     =  array('user_id !=' => $user_id,'is_deleted' =>'0');
	    	$result = $this->Common_model->getRecords('users', 'user_id,full_name,username,profile_pic', $update_data,"user_id Desc", false);
	    	$index1 = 0;
    		$new_data1  = array();
	    	foreach ($result as $data_result) {
	    			$new_data1[$index1] = $data_result;
	    			$new_data1[$index1]['is_page'] = 'no';
	    			$index1++; 
	    	}
	    	$where_page     =  array('is_deleted' =>'0');
	    	$page_result = $this->Common_model->getRecords('business_page','business_page_id,business_full_name ,business_name,business_image',$where_page,"business_page_id Desc", false);
	    	$index = 0;
	  		$new_data  = array();
	    	foreach ($page_result as $data_new) {
	    			$new_data[$index] = $data_new;
	    			$new_data[$index]['is_page'] = 'yes';
	    			$index++; 
	    	}
	    	$data = array_merge($new_data1,$new_data); 
	    	$response = array('data'=> array('status'=>'1','msg'=>'users found successfully','details'=>$data));
			echo json_encode($response); exit;


		}else{

			$result =array('');
        	$page_result =array('');
			if(empty($page_id)){
				$page_id = 0;
			}
	    	$result = $this->App_model->user_list_for_tag('user_id,full_name,username,profile_pic',$post_id, $user_id);
	    	$index1 = 0;
    		$new_data1  = array();
	    	foreach ($result as $data_result) {
    			$new_data1[$index1] = $data_result;
    			$new_data1[$index1]['is_page'] = 'no';
    			$index1++; 
    		}

   		 	$result = $this->App_model->page_list_for_tag('business_page_id,business_full_name ,business_name,business_image',$post_id,$page_id);	
   		 	$index = 0;
	  		$new_data  = array();
	    	foreach ($result as $data_new) {
	    			$new_data[$index] = $data_new;
	    			$new_data[$index]['is_page'] = 'yes';
	    			$index++; 
	    	}
		   
	    		
			$data = array_merge($new_data1,$new_data); 
			$response = array('data'=> array('status'=>'1','msg'=>'users found successfully','details'=>$data));
			echo json_encode($response); exit;

    	}

	}


	public function get_subcription_plans()
	{ 
		$where1 = array('subscription_plan_id' => '13');
	  	$where = array('is_free' => 'No','subscription_plan_id !=' => '13','amount >' =>'0');
		$subscription = $this->Common_model->getRecords('subscription_plan','*',$where,'',false);
		$subscriptionyear = $this->Common_model->getRecords('subscription_plan','*',$where1,'',true);
		$response = array('data'=> array('status'=>'1','msg'=>'subscription plan','details'=>$subscription,'year'=>$subscriptionyear ));
		echo json_encode($response);	
	}



	public function get_points()
	{   $this->check_login();
	  	$user_id		 =$this->test_input($this->input->post('user_id'));
	  	$where = array('user_id' => $user_id);
	    $manage = $this->Common_model->getRecords('points_manage','*',$where,'',false);
		$total = $this->Common_model->getRecords('points',"point",$where,'',true);
		if(empty($total)){
		$total['point']	='0';
		}
		$response = array('data'=> array('status'=>'1','msg'=>'subscription plan','details'=>$manage,'total'=>$total ));
		echo json_encode($response);	
	}

	public function payment()
 	{

 		$this->check_login(); 
		$user_id 	  =	 $this->test_input($this->input->post('user_id'));
		$page_id  	  =	 $this->test_input($this->input->post('page_id'));
		$access_token  	  =	 $this->test_input($this->input->post('access_token'));
		$plan_id  	  =	 $this->test_input($this->input->post('plan_id'));
		$card_number  	  =	 $this->test_input($this->input->post('card_number'));
		$card_exp  	  =	 $this->test_input($this->input->post('card_exp'));
		//$email_id  	  =	 $this->test_input($this->input->post('email_id'));
		
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
		
		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
			echo json_encode($err); exit;
		}
		if(empty($access_token)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter access token id.'));
			echo json_encode($err); exit;
		}
		if(empty($plan_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter plan id.'));
			echo json_encode($err); exit;
		}
		if(empty($card_number)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter card number.'));
			echo json_encode($err); exit;
		}
		if(empty($card_exp)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter card expiry date.'));
			echo json_encode($err); exit;
		}
		$where_plan =array('subscription_plan_id'=>$plan_id);
		$subscription_plan = $this->Common_model->getRecords('subscription_plan','*',$where_plan,'',true);
		if(empty($subscription_plan)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter correct plan id.'));
			echo json_encode($err); exit;
		}
		$where_user=array('user_id'=>$user_id);
		$user_email = $this->Common_model->getRecords('users','*',$where_user,'',true);
		if(empty($user_email)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Email not found.'));
			echo json_encode($err); exit;
		}

	 	 $plan_month = $subscription_plan['month'];
	 	 $point = $subscription_plan['points'];
	 	 $plan_end_date1 = date('Y-m-d', strtotime("+$plan_month months"));
	 	 $plan_end_date = date('Y-m-d', strtotime('-1 day', strtotime($plan_end_date1)));
        try {
            \Stripe\Stripe::setApiKey(SK_KEY);

          	$customer = \Stripe\Customer::create(array(
                    "email" => $user_email['email'],
                    "source" => $access_token,
                )); 
            $subscribe = \Stripe\Subscription::create(array(
                    "customer" => $customer->id,
                    "plan" => $subscription_plan['stripe_plan_id']
                ));
            	$wr=array('subscription_plan_id'=> $plan_id);
             $user_offers = $this->Common_model->getRecords('subscription_plan','offers',$wr,'',true);
             $page_al = $this->Common_model->getRecords('subscription_user','subscription_user_id',array('page_id'=>$page_id),'',true);
    
             $data = array(
                'subscription_plan_id' => $plan_id,
                'start_date' => date('Y-m-d'),
                'end_date' => $plan_end_date,
                'transaction_id' => $subscribe->id,
                'card_no' => $card_number,
                'subscription_type' =>'normal',
                'card_exp' => $card_exp,
                'offers' => $user_offers['offers'],
                'user_id' => $user_id,
                'page_id' => $page_id,
                'amount' => $subscription_plan['amount'],
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            );
             if(empty($page_al))
             {
             	if(!empty($subscribe->id))
	         	{
	           		 $response = $this->Common_model->addEditRecords('subscription_user', $data);
	            }
	        }else
	        {
	        	if(!empty($subscribe->id))
	         	{
	           		 $response = $this->Common_model->addEditRecords('subscription_user', $data,array('subscription_user_id' => $page_al['subscription_user_id']));
	            }
	        }

         	

            /********************************Mail user ****************************************/

        	$get_templete =array('type'=>'subscription');
			$get_templete_details = $this->Common_model->getRecords('mail_templete','*',$get_templete,'',true);
			$message_from_data =  $get_templete_details['message'];
			if($subscription_plan['month'] < 12)
			{
				$message = str_replace('{{plan_name}}',$subscription_plan['month'].' month',$message_from_data);
			}else
			{
				$message = str_replace('{{plan_name}}','1 year',$message_from_data);
			}
			$subject = $get_templete_details['subject'];
			$data['title']=  $get_templete_details['title'];
			$data['username']= $user_email['full_name'];
			$data['message']= $message;
			$body = $this->load->view('template/common', $data,TRUE);
			$to_email = $user_email['email'];
			$from_email = getAdminEmail(); 
			$this->Common_model->setMailConfig();
			$this->Common_model->sendEmail($to_email,$subject,$body,$from_email);

            /******************************** Mail admin ****************************************/
			$where_admin=array('admin_id'=>1);
			$admin_details = $this->Common_model->getRecords('admin','*',$where_admin,'',true);

			if($subscription_plan['month'] < 12)
			{
				$message = ucfirst($user_email['username']).' Subscribed for '.$subscription_plan['month'].' Month plan.';
			}else
			{
				$message = ucfirst($user_email['username']).' Subscribed for 1 Year plan.';
			}
			$subject = "Welcome greeting from " .SITE_TITLE;
			$data['username']= $admin_details['fullname'];
			$data['message']= $message;
			$body = $this->load->view('template/common', $data,TRUE);
			$to_email =  $admin_details['email'];
			$from_email = getAdminEmail(); 
			$this->Common_model->setMailConfig();
			$this->Common_model->sendEmail($to_email,$subject,$body,$from_email);
		    $this->Common_model->addEditRecords('business_page',array('sponsored'=>'Yes'),array('business_page_id'=>$page_id));
        	$where = array('user_id' => $user_id);
			$getprepoint=$this->Common_model->getRecords('points','*',$where,'',true);
			if(!empty($getprepoint))
			{
			 	$toal_point = 	$getprepoint['point']+$point;
		  		$this->Common_model->addEditRecords('points',array('point'=>$toal_point),array('user_id'=>$user_id,'points_id'=>$getprepoint['points_id']));
			}else
			{
	  			$this->Common_model->addEditRecords('points',array('point'=>$point,'user_id'=>$user_id));
			}
			
				$where456 = array('business_page_id' => $page_id);
					$resiver=$this->Common_model->getRecords('business_page','user_id,push_notification,business_name',$where456,'',true);
					$business_name = $resiver['business_name'];
					$where11 = array('user_id' => $resiver['user_id']);
					if($resiver['push_notification']=='Yes'){
					    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where11,'',false);
					    $where = array('user_id' =>$resiver['user_id']);
						$count=$this->Common_model->getRecords('users','badge_count',$where,'',true); 
					      	$iosarray = array(
			                    'alert' => 'Thank you for sharing business with Us.',
			                    'type'  => 'subscription',
			                   	'page_id'=> $page_id,
			                   	'badge' => $count['badge_count'],
			                    'sound' => 'default',
			       			);

							$andarray = array(
				                'message'   => 'Thank you for sharing business with Us.',
				                'type'      =>'subscription',
				               	'page_id'=> $page_id,
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
					   $savearray = 'page_id-'.$page_id;
					    $add_data =array('user_id' => $user_id,'page_id'=>$page_id,'created_by' =>$user_id,'type'=>'subscription', 'notification_title'=>'subscription', 'notification_description'=> 'Thank you for sharing business with Us.', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
			    		$this->Common_model->addEditRecords('notifications',$add_data); 

					}


            if ($response) {
            	$err = array('data' =>array('status' => '1', 'msg' => 'Payment successfully completed.'));
					echo json_encode($err); exit;
            } else {
        		$err = array('data' =>array('status' => '0', 'msg' => 'Sorry try again.'));
					echo json_encode($err); exit;
           
            }
  
       } catch (Stripe_CardError $e) {
          
        	$err = array('data' =>array('status' => '0', 'msg' => STRIPE_FAILED));
					echo json_encode($err); exit;

        } catch (Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $err = array('data' =>array('status' => '0', 'msg' =>$e->getMessage()));
			echo json_encode($err); exit;
         
        } catch (Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
         	$err = array('data' =>array('status' => '0', 'msg' =>AUTHENTICATION_STRIPE_FAILED));
			echo json_encode($err); exit;

        } catch (Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $err = array('data' =>array('status' => '0', 'msg' =>NETWORK_STRIPE_FAILED));
			echo json_encode($err); exit;

        } catch (Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
           $err = array('data' =>array('status' => '0', 'msg' =>STRIPE_FAILED));
			echo json_encode($err); exit;
     
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $err = array('data' =>array('status' => '0', 'msg' =>STRIPE_FAILED));
			echo json_encode($err); exit;
        }
    }


    public function unsubscribe()
	{
		$this->check_login(); 
		$user_id 	  =	 $this->test_input($this->input->post('user_id'));
		$page_id  	  =	 $this->test_input($this->input->post('page_id'));
		
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
		if(empty($page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
			echo json_encode($err); exit;
		}
		$where_user=array('user_id'=>$user_id);
		$user_email = $this->Common_model->getRecords('users','*',$where_user,'',true);
		if(empty($user_email)){
			$err = array('data' =>array('status' => '0', 'msg' => 'user not found.'));
			echo json_encode($err); exit;
		}
		$where_user=array('user_id'=>$user_id,'page_id'=>$page_id,'is_deleted'=>'0');
		$user_sub_id = $this->Common_model->getRecords('subscription_user','transaction_id,subscription_plan_id',$where_user,'',true);
		if(empty($user_sub_id))
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Subscription not found.'));
			echo json_encode($err); exit;
		}
	

			try {	
				require_once(APPPATH.'libraries/Stripe/init.php');
				\Stripe\Stripe::setApiKey(SK_KEY); //Replace with your Secret Key
				require_once(APPPATH.'libraries/Stripe/lib/Stripe.php');

			   \Stripe\Stripe::setApiKey(SK_KEY);

		
				$subscription = \Stripe\Subscription::retrieve($user_sub_id['transaction_id']);
				$responce = $subscription->cancel();

				if($responce->status=="canceled") {
					
					$customer = \Stripe\Customer::retrieve($responce->customer);
				  	$res = $customer->delete();
                    if($res->deleted=="true") {
                	    $where = array('transaction_id' => $user_sub_id['transaction_id']);
                        $data =array('is_deleted' => '1','is_canceled' => '1');
                        $this->Common_model->addEditRecords('subscription_user',$data,$where);
                        $get_templete =array('type'=>'cancel_subscription');
							$get_templete_details = $this->Common_model->getRecords('mail_templete','*',$get_templete,'',true);
							$message_from_data =  $get_templete_details['message'];
							$subscription_plan_old = $this->Common_model->getRecords('subscription_plan','*',array('subscription_plan_id'=>$user_sub_id['subscription_plan_id']),'',true);
							if($subscription_plan_old['month'] < 12)
							{
								$message = str_replace('{{plan_name}}',$subscription_plan_old['month'].' month',$message_from_data);
							}else
							{
								$message = str_replace('{{plan_name}}','1 year',$message_from_data);
							}
							$subject = $get_templete_details['subject'];
							$data['title']=  $get_templete_details['title'];
							$data['username']= $user_email['full_name'];
							$data['message']= $message;
							$body = $this->load->view('template/common', $data,TRUE);
							$to_email = $user_email['email'];
							$from_email = getAdminEmail(); 
							$this->Common_model->setMailConfig();
							$this->Common_model->sendEmail($to_email,$subject,$body,$from_email);
						$response = array('data'=> array('status'=>'1','msg'=>'plan canceled.'));
						echo json_encode($response);	
					}
				} 
			}
			catch (Stripe_Error $e) { 
				    $response = array('data'=> array('status' => 0, 'msg' => $e->getMessage()));
			    	echo json_encode($response);	
            		exit();
			} catch (Exception $e) {
			 	 	$response = array('data'=> array('status' => 0, 'msg' => $e->getMessage()));
			 		echo json_encode($response);
				 	exit();
			}
			
		
	}
	public function get_points_plan()
	{  
		$points = $this->Common_model->getRecords('points_plan','*','','',false);
	
		$response = array('data'=> array('status'=>'1','msg'=>'points plan','details'=>$points ));
		echo json_encode($response);	
	}


	public function pointsBuy()
 	{

 		$this->check_login(); 
		$user_id 	  =	 $this->test_input($this->input->post('user_id')); 
		$access_token  	  =	 $this->test_input($this->input->post('access_token'));
		$plan_id  	  =	 $this->test_input($this->input->post('plan_id')); 
	 	
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
		 
		if(empty($access_token)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter access token id.'));
			echo json_encode($err); exit;
		}
		if(empty($plan_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter plan id.'));
			echo json_encode($err); exit;
		} 

		$where_plan =array('points_plan_id'=>$plan_id);
		$subscription_plan = $this->Common_model->getRecords('points_plan','*',$where_plan,'',true);
		if(empty($subscription_plan)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter correct plan id.'));
			echo json_encode($err); exit;
		}
		
 		 $amount =$subscription_plan['amount']*100;
 
        try {
            \Stripe\Stripe::setApiKey(SK_KEY);
            $charge = \Stripe\Charge::create(array(
                "amount" =>$amount, 
               	"currency" => "GBP",
                "card" => $access_token,
                "description" => "point purchase"
            ));
			$points = $subscription_plan['points'];

         
			$where = array('user_id' => $user_id);
			$getprepoint=$this->Common_model->getRecords('points','*',$where,'',true);
			if(!empty($getprepoint))
			{
			 	$toal_point = 	$getprepoint['point']+$points;
		  		$this->Common_model->addEditRecords('points',array('point'=>$toal_point),array('user_id'=>$user_id,'points_id'=>$getprepoint['points_id']));
			}else
			{
	  			$this->Common_model->addEditRecords('points',array('point'=>$points,'user_id'=>$user_id));
			}
			 $data = array(
                'transaction_id' => $charge->id,
                'user_id' => $user_id,
                'point' => $points,
                'amount' =>$subscription_plan['amount'],
                'message' => 'Purchased points sent (Points added)',
                'type' => 4,
                'created' => date('Y-m-d H:i:s')
            ); 
            $response = $this->Common_model->addEditRecords('points_manage', $data);

            if ($response) {
            	$err = array('data' =>array('status' => '1', 'msg' => 'Payment successfully completed.'));
					echo json_encode($err); exit;
            } else {
        		$err = array('data' =>array('status' => '0', 'msg' => 'Sorry try again.'));
					echo json_encode($err); exit;
           
            }
  
        } catch (Stripe_CardError $e) {
          
        	$err = array('data' =>array('status' => '0', 'msg' => STRIPE_FAILED));
					echo json_encode($err); exit;

        } catch (Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $err = array('data' =>array('status' => '0', 'msg' =>$e->getMessage()));
			echo json_encode($err); exit;
         
        } catch (Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
         	$err = array('data' =>array('status' => '0', 'msg' =>AUTHENTICATION_STRIPE_FAILED));
			echo json_encode($err); exit;

        } catch (Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $err = array('data' =>array('status' => '0', 'msg' =>NETWORK_STRIPE_FAILED));
			echo json_encode($err); exit;

        } catch (Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
           $err = array('data' =>array('status' => '0', 'msg' =>STRIPE_FAILED));
			echo json_encode($err); exit;
     
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $err = array('data' =>array('status' => '0', 'msg' =>STRIPE_FAILED));
			echo json_encode($err); exit;
        }
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
    	
    	$response = array('data'=> array('status'=>'1','msg'=>'users found successfully','details'=>$data));
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
			$response = array('data'=> array('status'=>'1','msg'=>'Payment history','details'=>$data));
			echo json_encode($response); exit;
		}else{
			$err = array('data' =>array('status' => '0', 'msg' => 'Payment history not found !!'));
			echo json_encode($err); exit;
		}


	}


	public function get_offer_plan()
	{  	$this->check_login(); 
		$points = $this->Common_model->getRecords('offer_plan','*','','',false);
	
		$response = array('data'=> array('status'=>'1','msg'=>'Offer plan','details'=>$points ));
		echo json_encode($response);	
	}

	public function purchaseOffer()
 	{

 		$this->check_login(); 
		$user_id 	  =	 $this->test_input($this->input->post('user_id')); 
		$access_token  	  =	 $this->test_input($this->input->post('access_token'));
		$plan_id  	  =	 $this->test_input($this->input->post('plan_id')); 
		$page_id  	  =	 $this->test_input($this->input->post('page_id')); 
		$card_number  	  =	 $this->test_input($this->input->post('card_number'));
		$card_exp  	  =	 $this->test_input($this->input->post('card_exp'));
	 	
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		}
		 
		if(empty($access_token)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter access token id.'));
			echo json_encode($err); exit;
		}
		if(empty($plan_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter plan id.'));
			echo json_encode($err); exit;
		} 

		$where_plan =array('offer_plan_id'=>$plan_id);
		$subscription_plan = $this->Common_model->getRecords('offer_plan','*',$where_plan,'',true);
		if(empty($subscription_plan)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter correct plan.'));
			echo json_encode($err); exit;
		}

		$where_user=array('user_id'=>$user_id);
		$user_email = $this->Common_model->getRecords('users','*',$where_user,'',true);
		if(empty($user_email)){
			$err = array('data' =>array('status' => '0', 'msg' => 'user not found.'));
			echo json_encode($err); exit;
		}
		if(empty($card_number)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter card number.'));
			echo json_encode($err); exit;
		}
		if(empty($card_exp)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter card expiry date.'));
			echo json_encode($err); exit;
		}
		
 		 $amount =$subscription_plan['amount']*100;
 
        try {
            \Stripe\Stripe::setApiKey(SK_KEY);
            $charge = \Stripe\Charge::create(array(
                "amount" =>$amount, 
               	"currency" => "GBP",
                "card" => $access_token,
                "description" => "offer purchase"
            ));
			$offer = $subscription_plan['offer'];

			 $plan_end_date = date('Y-m-d', strtotime("+1 months"));
             $data = array(
                'offer_plan_id' => $plan_id,
                'start_date' => date('Y-m-d'),
                'end_date' => $plan_end_date,
                'transaction_id' => $charge->id,
                'card_no' => $card_number,
                'card_exp' => $card_exp,
                'user_id' => $user_id,
                'page_id' => $page_id,
                'offer'  => $offer,
                'amount' => $subscription_plan['amount'],
                'created' => date('Y-m-d H:i:s')
              
            );
         	if(!empty($charge->id))
         	{
           		$response = $this->Common_model->addEditRecords('offer_purchase', $data);

	            $get_templete =array('type'=>'purchase_offer');
				$get_templete_details = $this->Common_model->getRecords('mail_templete','*',$get_templete,'',true);
				$message_from_data =  $get_templete_details['message'];
				$message = str_replace('{{offer}}',$offer,$message_from_data);
				$data['title']=  $get_templete_details['title'];
				$subject = $get_templete_details['subject'];
				$data['username']= $user_email['full_name'];
				$data['message']= $message;
				$body = $this->load->view('template/common', $data,TRUE);
				$to_email = $user_email['email'];
				$from_email = getAdminEmail(); 
				$this->Common_model->setMailConfig();
				$this->Common_model->sendEmail($to_email,$subject,$body,$from_email);
				$err = array('data' =>array('status' => '1', 'msg' => 'Payment successfully completed.'));
				echo json_encode($err); exit;
         		}
         		else{
         			$err = array('data' =>array('status' => '0', 'msg' => 'Sorry try again.'));
					echo json_encode($err); exit;

         		}

      
  
        } catch (Stripe_CardError $e) {
          
        	$err = array('data' =>array('status' => '0', 'msg' => STRIPE_FAILED));
					echo json_encode($err); exit;

        } catch (Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $err = array('data' =>array('status' => '0', 'msg' =>$e->getMessage()));
			echo json_encode($err); exit;
         
        } catch (Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
         	$err = array('data' =>array('status' => '0', 'msg' =>AUTHENTICATION_STRIPE_FAILED));
			echo json_encode($err); exit;

        } catch (Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $err = array('data' =>array('status' => '0', 'msg' =>NETWORK_STRIPE_FAILED));
			echo json_encode($err); exit;

        } catch (Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
           $err = array('data' =>array('status' => '0', 'msg' =>STRIPE_FAILED));
			echo json_encode($err); exit;
     
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $err = array('data' =>array('status' => '0', 'msg' =>STRIPE_FAILED));
			echo json_encode($err); exit;
        }
    }

	public function cancel_offer()
	{  	$this->check_login();
		$offer_id =	$this->input->post('offer_id');
		//$user_id =	$this->input->post('user_id');

		$where = array('business_offers'=>$offer_id);
		$update_data = array('status'=>'1');
		$resdevice=$this->Common_model->addEditRecords('business_offers',$update_data,$where);

		if($resdevice)
		{	
			$response = array('data'=> array('status'=>'1','msg'=>'Cancelled offer won`t be visible to anyone else. It would only be visible to you. '));
			echo json_encode($response); exit;
		}else
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'Please try again !!'));
			echo json_encode($err); exit; 
		}
	
			
	}



	public function getMyRedeemOffers()
	{
		$this->check_login();
		$user_id  =		$this->test_input($this->input->post('user_id'));
	 	$page_id  =		$this->test_input($this->input->post('page_id'));

		$where = array("user_id"=>$user_id);

		$get_list= $this->Common_model->getRecords('redeem_offers','offer_id',$where,'',false);  
		$get_offer=array();
		
		$index = 0;
		foreach ($get_list as $list) {
			$where_offer = array("business_offers"=>$list['offer_id'],"is_deleted"=>'0');
			$offers= $this->Common_model->getRecords('business_offers','business_offers,sort,page_expired_date,business_page_id,offers_type,offers_title,description',$where_offer,'sort ASC',true);  	
			
		

				$get_offer[$index]=  $offers; 
				if($offers['page_expired_date'] >=DATE('Y-m-d'))
				{
					$get_offer[$index]['is_expired'] ='no';
				}else
				{
					$get_offer[$index]['is_expired'] ='yes';
				}
				$get_rating = $this->App_model ->get_rating_avg($offers['business_page_id']);
				$get_offer[$index]['rating'] = $get_rating['rating'];
				$offer_image= array("offer_id"=>$offers['business_offers']);
				$get_offer[$index]['offer_image'] = $this->Common_model->getRecords('offers_images','id,file_path',$offer_image,'',false);  

				if($offers['offers_type'] =='multi_buy'){
					$where_type= array("business_offers_id"=>$offers['business_offers']);
				 	$get_offer[$index]['multi_buy']= $this->Common_model->getRecords('multi_buy','discount_type,buy,buy_text,get,get_text,note',$where_type,'',true);  
				}else
				{
					$get_offer[$index]['multi_buy']='';
				} 
				if($offers['offers_type'] =='standard_discount')
				{
					$where_type= array("business_offers_id"=>$offers['business_offers']);
				 	$get_offer[$index]['standard_discount']= $this->Common_model->getRecords('standard_discount','discount_type,discount_value,product_note,product_description',$where_type,'',true);  
				}else
				{
					$get_offer[$index]['standard_discount']='';
				}
		
			$index++;	
		}	
		 
		
	if($get_offer)
		{
			$response = array('data'=> array('status'=>'1','msg'=>'Offer list','details'=>$get_offer));
			echo json_encode($response); exit;
        }else
        {
			$err = array('data' =>array('status' => '0', 'msg' => 'Offers not found !!'));
			echo json_encode($err); exit;
        }

	}

	public function gethashtagpost()
	{
		$this->check_login();
		$hashtag_id  =		$this->test_input($this->input->post('hashtag_id'));
		$user_id  =		$this->test_input($this->input->post('user_id'));
		if(empty($hashtag_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter #tag.'));
			echo json_encode($err); exit;
		} 
		$getPost = $this->App_model->gethashtagpost($user_id,$hashtag_id,$sort='');
		// echo $this->db->last_query();die;
		
		if($getPost) {
			    $index=0;
				foreach ($getPost as $get) {
				 	if($get['business_page_id']!='0'){
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
		      	}else {
			      	$getPost[$index]['is_page'] = '0';
			      	$getPost[$index]['page_id'] = '';
			      	$getPost[$index]['is_my_page'] = '0';
		      	}


					if($get['user_id']==$user_id){
		                $getPost[$index]['myPost'] = '1';
					}else {
						$getPost[$index]['myPost'] = '0';
					}

					if($get['post_date'] == '0000-00-00') {
					$getPost[$index]['post_date'] ='';
		   			}
					
		            $post_images = array();
					$where = array('post_id' => $get['post_id']);
					if($post_images = $this->Common_model->getRecords('post_img','file_path,file_type,video_path',$where,'',false)) {
						$getPost[$index]['post_media'] = $post_images;
					}else{
						$getPost[$index]['post_media'] = $post_images;	
					}

					if($isFollow = $this->App_model->isFollow($get['user_id'],$user_id)) {
						if($isFollow[0]['status']=='Follow'){
							$getPost[$index]['isFollow'] = '1';	
						}else{
							$getPost[$index]['isFollow'] = '2';
						}
					} else {
						$getPost[$index]['isFollow'] = '0';
					}
		            $index++;
				} 
				$response = array('data'=> array('status'=>'1','msg'=>'PostList','details'=>$getPost));
				echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'msg' => 'Record not found.'));
				echo json_encode($err); exit;	
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


	public function user_chating()
	{
		// $this->check_login();
	  	$sender_id		 =	$this->test_input($this->input->post('sender_id'));
	  	$receiver_id	 =	$this->test_input($this->input->post('receiver_id'));
	  	$message		 =	$this->test_input($this->input->post('message'));
	  	$type		     =	$this->test_input($this->input->post('type'));

		if(empty($sender_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter sender id.'));
			echo json_encode($err); exit;
		} 
		if(empty($receiver_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter receiver id.'));
			echo json_encode($err); exit;
		}  
		if(empty($type)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter type.'));
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
			 
		    $receiver_record=$this->Common_model->getRecords('users','device_type,device_id',array('user_id'=>$receiver_id),'',true);
		    $sender_record=$this->Common_model->getRecords('users','full_name',array('user_id'=>$sender_id),'',true);
			$iosarray = array(
                'alert' =>  substr($message,0,60),
                'type'  => 'chat', 
             	'user_name'=> $sender_record['full_name'], 
				'user_id' => $receiver_id, 
                'sound' => 'default',
   			); 
			$andarray = array(
                'message'   =>  $message,
                'type'  => 'chat', 
               	'user_name'=> $sender_record['full_name'],
               	'user_id' => $receiver_id,
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
		 
			$suc = array('data' =>array('status' => '1', 'msg' =>'Message send successfully','room_id'=>$get_room_id));
            echo json_encode($suc); exit; 	  
	}


	public function chat_detail()
	{
		// $this->check_login();
	  	$room_id		 =	$this->test_input($this->input->post('room_id'));
	 
		if(empty($room_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter room id.'));
			echo json_encode($err); exit;
		} 
		$chat_record=$this->Common_model->getRecords('users_chat','*',array('room_id'=>$room_id,'type'=>'chat'),'',false);	 
		 		
		$suc = array('data' =>array('status' => '1','detail'=>$chat_record));
        echo json_encode($suc); exit; 	  
	}


	public function chattinglist()
	{
		$user_id =	$this->test_input($this->input->post('user_id'));

	  	if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user_id.'));
			echo json_encode($err); exit;
		} 
		$array = array();
		if($records=$this->Common_model->getchatlist($user_id)) {
		   	// echo "<pre>";print_r($this->db->last_query());die;
		    foreach ($records as $key => $list) {
		 		if($user_id==$list['sender_id'])
		 		{
		 			$second_user=$list['receiver_id'];
		 		}else
		 		{
		 			$second_user=$list['sender_id'];
		 		}
		 		// echo $second_user;die;
		 		$second_user_record=$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$second_user),'',true);
		 		// echo "<pre>";print_r($second_user_record);die;
		 	
		 		$array1 = array('room_id'=>$list['room_id'],'type'=>$list['type']);
		 		$array[$key]['chat_type'] =$array1;
		 		$array[$key]['user_id'] = $second_user;
		 		$array[$key]['profile_pic'] = $second_user_record['profile_pic'];
		 		$array[$key]['full_name'] = $second_user_record['full_name'];
		 		$array[$key]['message'] = $list['message'];

		    }
			$suc = array('data' =>array('status' => '1', 'msg' =>'message', 'details'=>$array));
            echo json_encode($suc); exit;
		} else {
			$err = array('data' =>array('status' => '0', 'msg' => 'No Data Found'));
            echo json_encode($err); exit;
		} 


	}

	public function user_delete()
	{
		$user_id   =	$this->test_input($this->input->post('user_id'));
      
		if(empty($user_id))
		{
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter user id '));
			echo json_encode($err);
			exit;
		}
		$where=array('user_id'=>$user_id);

		$wh=array('follow_user_id'=>'1','user_id'=>$user_id);
	  
		$this->Common_model->deleteRecords('users',$where);

		$this->Common_model->deleteRecords('follow_user',$wh);

     	$response = array('data'=> array('status'=>'1','msg'=>'Oops! Seems our server is not responding. Please try again later.'));
		  	echo json_encode($response);	
		  	exit;

	}




	/************************************************New Google Changes Start******************************************************/
	public function business_claim() {
		$this->check_login();
		$user_id   =	$this->test_input($this->input->post('user_id')); 
		$business_title   =	$this->test_input($this->input->post('business_title')); 
		$business_location   =	$this->test_input($this->input->post('business_location')); 
		$busniess_id   =	$this->test_input($this->input->post('page_id')); 
		
		if(empty($user_id)) {
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter user id '));
			echo json_encode($err);
			exit;
		}
		if(empty($business_title)) {
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter business title.'));
			echo json_encode($err);
			exit;
		}
		if(empty($business_location)) {
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter business location.'));
			echo json_encode($err);
			exit;
		}
		if(empty($busniess_id)) {
			$err = array('data'=> array('status'=>'0','msg'=>'Please enter busniess id.'));
			echo json_encode($err);
			exit;
		}

	 
		$date= date('Y-m-d H:i:s');
	    $insert_data = array(
	    	'user_id'=>  $user_id,
	    	'business_title'=> $business_title,
	    	'business_location'=> $business_location,
	    	'busniess_id'=> $busniess_id,
	    );

		if($id = $this->Common_model->addEditRecords('google_business_clam',$insert_data)) {
			$update_data = array('follow_page_id' => $id);
			$where = array('follow_page_id'=>$busniess_id);
			$this->Common_model->addEditRecords('follow_page',$insert_data,$where);
			$response = array('data'=> array('status'=>'1','msg'=>'Congratulations !! Business claimed successfully.'));
			echo json_encode($response);	
		} else {
			$err = array('data' =>array('status' => '0', 'msg' => 'Some error occured Please try again !!'));
			echo json_encode($err); exit;
		}

	}


	public function add_featured_image()
	{

		$this->check_login();
        $order_number		 =	$this->input->post('order_number');
        $user_id		 =	$this->input->post('user_id');
        $business_page_id	 =	$this->input->post('business_page_id');
        $image		 =	$this->input->post('image'); 
	 	if(empty($order_number)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter order number.'));
			echo json_encode($err); exit;
		} 
		if(empty($user_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
			echo json_encode($err); exit;
		} 
		if(empty($business_page_id)){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please enter business page id.'));
			echo json_encode($err); exit;
		} else{
			$where = array('business_page_id' => $business_page_id);
			$resuser=$this->Common_model->getRecords('business_page','*',$where,'',true);

			if($resuser['user_id']!= $user_id) {
	    		$err = array('data'=> array('status'=>'0','msg'=>'You are not authorized user'));
				echo json_encode($err);
				exit;
	    	}
		}  

	 	if(empty($_FILES['image']['name'])){
			$err = array('data' =>array('status' => '0', 'msg' => 'Please select image.'));
			echo json_encode($err); exit;
		} 

		$chec_order=$this->Common_model->getRecords('featured_images','*',array('order_number'=>$order_number,'user_id'=>$user_id,'business_page_id'=>$business_page_id),'',true);
		if(!empty($chec_order))
		{
				$err = array('data'=> array('status'=>'0','msg'=>'You already uploaded image on this number'));
				echo json_encode($err);
				exit;

		}


		if(!empty($_FILES['image'])) {
				$filesCount = count($_FILES['image']['name']);  
				$_FILES['image']['name'] = $_FILES['image']['name'];
				$_FILES['image']['type'] = $_FILES['image']['type'];
				$_FILES['image']['tmp_name'] = $_FILES['image']['tmp_name'];
				$_FILES['image']['error'] = $_FILES['image']['error'];
				 	
				//Rename image name 
				$img = time().'_'.rand();
				$allowed =  array('jpg','png','jpeg','JPG','PNG','JPEG');
				$config['upload_path'] = 'resources/images/featured_image/';
				$config['allowed_types'] = '*';
				$config['file_name'] =  $img;
	
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
			    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			   	if(!in_array($ext,$allowed) ) {
					   $err = array('data' =>array('status' => '0', 'msg' => 'Only jpg|jpeg|png image types allowed..'));
			   			echo json_encode($err); exit;	
			    } 

				if($this->upload->do_upload('image')){
					$fileData = $this->upload->data();
				 
					$uploadData= array(
						'image' => 'resources/images/featured_image/'.$config['file_name'].$fileData['file_ext'],
						'order_number' => $order_number,
						'user_id' => $user_id,
						'business_page_id' => $business_page_id, 
						'created' => date("Y-m-d H:i:s"),  
					);

					if($this->Common_model->addEditRecords('featured_images', $uploadData))
					{     
						$err = array('data' =>array('status' => '1', 'msg' => 'Image uploaded successfully.' ));
						echo json_encode($err); exit;
					}

				} else {
					$error = array('error' => $this->upload->display_errors());
                    $err = array('data' =>array('status' => '0', 'msg' => $error ));
					echo json_encode($err); exit;
				}
			}  
		}	
	 



	public function delete_featured_image()
		{ 
			$this->check_login();
	        $order_number		 =	$this->input->post('order_number');
	        $user_id		 =	$this->input->post('user_id');
	        $business_page_id	 =	$this->input->post('business_page_id');
	        $image_id	 =	$this->input->post('image_id'); 
		 	if(empty($order_number)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter order number.'));
				echo json_encode($err); exit;
			} 
		 	if(empty($user_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
				echo json_encode($err); exit;
			}
			if(empty($image_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter image id.'));
				echo json_encode($err); exit;
			}
		 	if(empty($business_page_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter business page id.'));
				echo json_encode($err); exit;
			} else{
				$where = array('business_page_id' => $business_page_id);
				$resuser=$this->Common_model->getRecords('business_page','*',$where,'',true);

				if($resuser['user_id']!= $user_id) {
		    		$err = array('data'=> array('status'=>'0','msg'=>'You are not authorized user'));
					echo json_encode($err);
					exit;
		    	}
			} 

			$get_record=$this->Common_model->getRecords('featured_images','*',array('id'=>$image_id),'',true);
			if(!empty($get_record))
			{
				$where = array('id'=>$image_id);
				$this->Common_model->deleteRecords('featured_images',$where);
				if(file_exists ($get_record['image'])){
					unlink($get_record['image']);
				}
				$err = array('data' =>array('status' => '1', 'msg' => 'Image deleted successfully.','order_number'=>$order_number));
				echo json_encode($err); exit;
			}else
			{
				$err = array('data'=> array('status'=>'0','msg'=>'Image Not Found.'));
				echo json_encode($err);
				exit; 
			}

			

		}


		public function get_featured_image()
		{ 
			$this->check_login(); 
	        $user_id		 =	$this->input->post('user_id');
	        $business_page_id	 =	$this->input->post('business_page_id');
	       
		 	if(empty($user_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
				echo json_encode($err); exit;
			}
		 	if(empty($business_page_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter business page id.'));
				echo json_encode($err); exit;
			} else{
				$where = array('business_page_id' => $business_page_id);
				$resuser=$this->Common_model->getRecords('business_page','*',$where,'',true);

				if($resuser['user_id']!= $user_id) {
		    		$err = array('data'=> array('status'=>'0','msg'=>'You are not authorized user'));
					echo json_encode($err);
					exit;
		    	}
			} 

			$get_record=$this->Common_model->getRecords('featured_images','id,order_number,user_id,business_page_id,image',array('user_id'=>$user_id,'business_page_id'=>$business_page_id),'order_number ASC',false);
			if(!empty($get_record))
			{  
				$err = array('data' =>array('status' => '1', 'details' =>$get_record,'msg'=>'record found.'));
				echo json_encode($err); exit;
			}else
			{
				$err = array('data'=> array('status'=>'0','msg'=>'Images Not Found.'));
				echo json_encode($err);
				exit; 
			} 
		} 



	/************************************************New Google Changes End******************************************************/
	public function getSubCategorylist()
	{   
		$this->check_login(); 
        $tableName = 'sub_categories';
		$where = array('status'=>'Active');
		$sub_categories = $this->Common_model->getRecords($tableName,'sub_category_id,name,category_id',$where,'sub_category_id Desc',false);
		$category_list =array();
		if(!empty($sub_categories))
		{
			foreach ($sub_categories as $key => $value) {

				$categories_main = $this->Common_model->getRecords('categories','name as category_name',array('category_id'=>$value['category_id']),'',true);
				 $category_list[$key]['sub_category_id'] =$value['sub_category_id'];
				 $category_list[$key]['name'] =$categories_main['category_name'].' - '.$value['name'];
			}
		}

		if(empty($category_list)) {
			$err = array('data' =>array('status' => '0', 'msg' => 'categories not valid please try again !!'));
			echo json_encode($err); exit;
		} else {
			$response = array('data'=> array('status'=>'1','msg'=>' sub categoriesfound successfully','details'=>$category_list));
			echo json_encode($response); exit;
		}
	}
 


	public function most_viewed()
	{
        $page_id	 =	$this->input->post('page_id');

    	$page_details=$this->Common_model->getRecords('most_viewed','*',array('page_id'=>$page_id),'',true);	
    	if(!empty($page_details))
    	{
    		$count = $page_details['count']+1;
    		 $insert_data = array(
			    	'count'=>  $count,
			    ); 
			$user_id = $this->Common_model->addEditRecords('most_viewed',$insert_data,array('id'=>$page_details['id'])); 
    	}else
    	{  
    		 $insert_data = array(
			    	'count'=> '1',
			    	'page_id'=>$page_id,
			    ); 
		 	 $this->Common_model->addEditRecords('most_viewed',$insert_data);  
    	}

    	$response = array('data'=> array('status'=>'1','msg'=>'Viewed successfully'));
		echo json_encode($response); exit;
    	
	}


	// public function check_for_request()
	// {
	// 	$user_id	 =	$this->input->post('user_id');
	//   	$friend_id	 =	$this->input->post('friend_id');  
	//   	echo check_for_request($user_id,$friend_id);
	// }


	public function send_chatting_request()
	{
		$user_id	 =	$this->input->post('user_id');
	  	$friend_id	 =	$this->input->post('friend_id');  
	  	if(empty($user_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
				echo json_encode($err); exit;
		}
		if(empty($friend_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter friend id.'));
				echo json_encode($err); exit;
		}

		 $insert_data = array(
		    	'sender_user'=> $user_id,
		    	'receiver_user'=>$friend_id,
		    	'status'=>'0',
		    	'created'=> date("Y-m-d H:i:s"),
		    ); 
	 	if($this->Common_model->getRecords('chating_request','*',$insert_data,'',true)) 
		{
			//echo $this->db->last_query();die;
	 	 	$response = array('data'=> array('status'=>'1','msg'=>'Request already Send .'));
			echo json_encode($response); exit; 
		}else
		{
			$this->Common_model->addEditRecords('chating_request',$insert_data);  

 	/************************************ Notification*****************************************************/
	 
			$user_data  =  array('user_id' => $friend_id);
    		$result = $this->Common_model->getRecords('users', 'account_type', $user_data,"", true); 
    	
     		$msg = ' wants to send you a message';

			if($user_id != $friend_id){
				$status = 'Pending';
				$where = array('user_id' => $friend_id);
				$resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);
				$row = array('user_id' => $user_id);
		        $sender=$this->Common_model->getRecords('users','username',$row,'',true);
			 
					if($resiver['notification']=='Yes'){
					    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
					    $demo=$this->badge_count($user_id,'users','user_id');
						$iosarray = array(
		                    'alert' => $sender['username'].$msg,
		                    'type'  => 'chat_request',
		                   	'status'=>$status,
		                   	'other_user_id'=>$user_id,
		                    'badge' =>  $demo,
		                    'sound' => 'default',
               			);

						$andarray = array(
			                'message'   => $sender['username'].$msg,
			                'type'      =>'chat_request',
			                'status'=>$status,
			              	'other_user_id'=>$user_id,
			                'title'     => 'Message Request',
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
					  $add_data =array('user_id' =>$friend_id,'created_by' =>$user_id,'type'=>'chat_request', 'notification_title'=>'Chat Request', 'notification_description'=>$sender['username'].$msg, 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
		        		$this->Common_model->addEditRecords('notifications',$add_data);   

					}
				}

	 	 	$response = array('data'=> array('status'=>'1','msg'=>'Request Send Successfully.'));
			echo json_encode($response); exit;
		} 

	}

	public function action_request()
	{
		$user_id	 =	$this->input->post('user_id');
		$action	 =	$this->input->post('action');
		$record_id	 =	$this->input->post('record_id');
		if(empty($action)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter action.'));
				echo json_encode($err); exit;
		}elseif($action!='accept' && $action!='reject')
		{
			$err = array('data' =>array('status' => '0', 'msg' => 'action type must be accept Or reject.'));
			echo json_encode($err); exit;
		}
		if(empty($record_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter record id.'));
				echo json_encode($err); exit;
		}
		if(empty($user_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
				echo json_encode($err); exit;
		}

		if($action=='accept')
		{
			 
		 	$update = array( 'status'=>'1','created'=>date("Y-m-d H:i:s") ); 
		 	if($this->Common_model->addEditRecords('chating_request',$update,array('id'=>$record_id)))
		 	{

		 	$get_details = 	 $this->Common_model->getRecords('chating_request','*',array('id'=>$record_id),"", true); 
		 /************************************ Notification*****************************************************/
	 	
			$user_data  =  array('user_id' => $get_details['sender_user']);
    		$result = $this->Common_model->getRecords('users', 'account_type', $user_data,"", true); 
    	
     		$msg = ' accpeted your chat request';

			if($user_id != $get_details['sender_user']){
				$status = 'accpeted';
				$where = array('user_id' => $get_details['sender_user']);
				$resiver=$this->Common_model->getRecords('users','notification,username',$where,'',true);
				$row = array('user_id' => $user_id);
		        $sender=$this->Common_model->getRecords('users','username',$row,'',true);


					if($resiver['notification']=='Yes'){
					    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where,'',false);
					    $demo=$this->badge_count($user_id,'users','user_id');
						$iosarray = array(
		                    'alert' => $sender['username'].$msg,
		                    'type'  => 'chat_request_accept',
		                   	'status'=>$status,
		                   	'other_user_id'=>$user_id,
		                    'badge' =>  $demo,
		                    'sound' => 'default',
               			);

						$andarray = array(
			                'message'   => $sender['username'].$msg,
			                'type'      =>'chat_request_accept',
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
					  $add_data =array('user_id' => $get_details['sender_user'],'created_by' =>$user_id,'type'=>'chat_request', 'notification_title'=>'Chat Request', 'notification_description'=>$sender['username'].$msg, 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
		        		$this->Common_model->addEditRecords('notifications',$add_data);  
		        	

					}
				
			}

 			$response = array('data'=> array('status'=>'1','msg'=>'Request accepted.'));
			echo json_encode($response); exit;
		 	}else{
	 			 echo	$this->db->last_query();die;
		 		$response = array('data'=> array('status'=>'0','msg'=>'Some Error Please try again letter.'));
				echo json_encode($response); exit;
		 	} 


 		}else
 		{
 			$this->Common_model->deleteRecords('chating_request',array('id'=>$record_id));
 			$response = array('data'=> array('status'=>'1','msg'=>'Request rejected.'));
			echo json_encode($response); exit;
 		}
	

	}

	public function getchatPending_request()
	{
		$user_id	 =	$this->input->post('user_id');
		if(empty($user_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter user id.'));
				echo json_encode($err); exit;
		}


		//$get_panding_list = $this->Common_model->getRecords('chating_request','id,sender_user',array('receiver_user' =>$user_id),'',false);
		$get_panding_list = $this->App_model->get_panding_list($user_id);
		if(!empty($get_panding_list))
 		{
 		  $response =array('data'=> array('status'=>'1','msg'=>'results','details'=>$get_panding_list));
 		  echo json_encode($response); 
 		  exit;

 		} else {
			$response = array('data'=> array('status'=>'0','msg'=>'Results not found.'));
			  echo json_encode($response); 
 		} 
	}



	public function get_page()
	{
		$page_id	 =	$this->input->post('page_id');
		if(empty($page_id)){
				$err = array('data' =>array('status' => '0', 'msg' => 'Please enter page id.'));
				echo json_encode($err); exit;
		}


		//$get_panding_list = $this->Common_model->getRecords('chating_request','id,sender_user',array('receiver_user' =>$user_id),'',false);
		$page_details=$this->Common_model->getRecords('pages','title,content',array('page_id'=>$page_id),'',true);
		if(!empty($page_details))
 		{
 		  $response =array('data'=> array('status'=>'1','msg'=>'results','details'=>$page_details));
 		  echo json_encode($response); 
 		  exit;

 		} else {
			$response = array('data'=> array('status'=>'0','msg'=>'Results not found.'));
			  echo json_encode($response); 
 		} 
	}


	

}


 