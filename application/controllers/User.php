<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User extends CI_Controller {

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
	public function doctor_profile($id)
	{
		check_permission('patient');
		$data['title']="Doctor Profile | ".SITE_TITLE;
		$data['page_title']="Doctor Profile";
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
			'title' => 'Doctor Profile',
			'link' => ""
		);
		$user_id = $this->session->userdata('user_id');
		$user_data = $this->Common_model->getRecords('users','*',array('user_id'=>$id),'',true);

	 	$data['get_room_id']=$this->Common_model->getRoomidid($user_id,$id,'chat');

	 	$data['question_list'] = $this->Common_model->getRecords('individual_feedback','*',array('dr_id'=>$id),'',false);

		$country_name =  $this->Common_model->getRecords('countries','name',array('id'=>$user_data['country_id']),'',true);

		$user_data['country_name'] =$country_name['name'];
		$state_name =  $this->Common_model->getRecords('states','name',array('id'=>$user_data['state_id']),'',true);
		$user_data['state_name'] =$state_name['name'];
		$city_name =  $this->Common_model->getRecords('cities','name',array('id'=>$user_data['city_id']),'',true);
		$user_data['city_name'] =$city_name['name'];	
		$category_name =  $this->Common_model->getRecords('categories','name',array('category_id'=>$user_data['category_id']),'',true);
		$user_data['category_name'] =$category_name['name'];


		$media_list= $this->Common_model->getmedialist($id);  
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


		$data['user'] =$user_data;
		$data['media'] =$media;

		//echo "<pre>";print_r($media);
	

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/doctor_profile');
		$this->load->view('frontend/footer');
	}
	 

	
	public function upload_media()
	{
		check_permission('doctor');
		$data['title']="Upload Media | ".SITE_TITLE;
		$data['page_title']="Upload Media";
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
			'title' => 'Upload Media',
			'link' => ""
		);
		$user_id = $this->session->userdata('user_id');
		//$user_id = '2';
		$user_data = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id),'',true);

		  
		$media_list= $this->Common_model->getmedialist($user_id);  
 
		$data['media'] =$media_list;

		//echo "<pre>";print_r($media);
	

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/upload_media');
		$this->load->view('frontend/footer');
	} 


	public function upload_my_media()
	{

	  	$user_id = $this->session->userdata('user_id');
	  	//$user_id = '2';
	  	$media_type		=	$this->input->post('media_type');
	  	$title			=	$this->input->post('title');
	  	$detail			=	$this->input->post('discription');
	  
	
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
        } 

       

	    $insert_data = array( 
	    	'media_type'=>$media_type,
	    	//'video_image'=>$video_image,
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

					$response = array('data'=> array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong>'.$this->lang->line('media_success_msg').'</div>'));
				    echo json_encode($response); exit;
			} else {
				$err = array('data' =>array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>'.$this->lang->line('error_message').'</div>'));
				echo json_encode($err); exit;
			}
	 
	}



	public function doctor_list($id)
	{
		check_permission('patient');
		$data['title']="Doctor List | ".SITE_TITLE;
		$data['page_title']="Doctor List";
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
			'title' => 'Doctor List',
			'link' => ""
		);

		$user_id = $this->session->userdata('user_id');
		$data['category_list'] = $this->Common_model->getRecords('categories','*',array('status'=>'Active'),'',false);

		$user_data = $this->Common_model->getRecords('users','*',array('category_id'=>$id,'user_type'=>'doctor'),'',false);
		$users_list = array();
		if(!empty($user_data)){
			foreach ($user_data as $key => $list) {
			 	$users_list[$key] = $list;
			 	$category_name =  $this->Common_model->getRecords('categories','name',array('category_id'=>$list['category_id']),'',true);
			 	$users_list[$key]['category_name'] =  $category_name['name'];
			}
		}  	     

		//echo "<pre>";print_r($user_data);die;
		$data['user_list'] =$users_list;

		$this->load->view('frontend/header',$data);
		//$this->load->view('frontend/doctor_list');
		$this->load->view('frontend/category');
		$this->load->view('frontend/footer');
	}
	public function chat()
	{
		//check_permission('patient');
		$data['title']="Chat List | ".SITE_TITLE;
		$data['page_title']="Chat List";
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
			'title' => 'Chat List',
			'link' => ""
		);

		$user_id = $this->session->userdata('user_id');
		$user_type = $this->session->userdata('user_type');
		if(empty($user_id)){
			
				redirect(base_url());
			
		}

		$user_id = $this->session->userdata('user_id');


		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/chating');
		$this->load->view('frontend/footer');
	}




	public function chattinglist()
	{
		$user_id =	$this->session->userdata('user_id');
		//$user_id =	1;
 		$html = '';
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

	    	if($new_array){
	    		//echo "<pre>";print_r($new_array);die;

	    		foreach ($new_array as $key => $list) {

	    			if(file_exists(base_url().$list['profile_pic'])){ 

                        $user_image =base_url().$list['profile_pic'];
                     }else{
                         $user_image =base_url().'assets/front/img/user.png';
                     }


			 		$html .= ' <a href="javascript:void(0)" onclick="chat_history('.$list['user_id'].')" >';
			 		$html .= '<div class="chat_list ">';
					$html .= '		  <div class="chat_people">';
					$html .= '			<div class="chat_img"> <img src="'.$user_image.'" alt="'.$list['full_name'].'"> </div>';
					$html .= '			<div class="chat_ib">';
					$html .= '			  <h5>'.ucfirst($list['full_name']).' <span class="chat_date">Dec 25</span></h5>';
					$html .= '			  <p>'.ucfirst($list['message']).'</p>';
					$html .= '			<div style="width: 27px;float: right;" class=""><a href="'.base_url().'call/'.$list['user_id'].'"><img src="'.base_url().'assets/front/img/download.png"></a> </div>';
					$html .= '			</div>';
					$html .= '		  </div>';
					$html .= '		</div>';
					$html .= '		</a>';

	    		}
	    	}
	    		 
			$suc = array('status' => '1', 'msg' =>'message', 'details'=>$html);
            echo json_encode($suc); exit;
		} else {
			$err = array('status' => '0', 'msg' => 'No Data Found');
            echo json_encode($err); exit;
		}  

	}


	public function edit_profile()
	{
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

		$user_id = $this->session->userdata('user_id');
		$user_type = $this->session->userdata('user_type');
		if(empty($user_id)){
				redirect(base_url());
		}

		$data['countries'] = $this->Common_model->getRecords('countries','*');
		$data['category_list'] = $this->Common_model->getRecords('categories','*',array('status'=>'Active'),'',false);
		$data['user_data'] = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id),'',true);

		if($this->input->post()){

				//$user_type			=	$this->input->post('user_type');
			  	$full_name			=	$this->input->post('name');
			  	//$email				=	$this->input->post('email');
			  	//$password			=	$this->input->post('password');
			  	$country_id			=	$this->input->post('country');
			  	$state_id			=	$this->input->post('state');
			  	$city_id			=	$this->input->post('city');
			  	//$mobile			=	$this->input->post('mobile');
			  	//$country_code			=	$this->input->post('country_code');
			  	$address			=	$this->input->post('address');
			  	//$about			=	$this->input->post('about');
			  	
			  	if($data['user_data']['user_type']=='doctor'){
			  		$category_id		=	$this->input->post('category_id');
			  	}else{
			  		$category_id		=	'';
			  	}

	
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

		      
			    $insert_data = array( 
			    	//'user_type'=>$user_type,
			    	'full_name'=>$full_name,
			    	//'email' => $email, 
			    	//'token'=>$code,
			    	//'password' => $password,
			    	'country_id' => $country_id,
			    	'state_id' => $state_id,
			    	'city_id' => $city_id,
			    	//'mobile' => $mobile,
			    	//'mobile2' => $mobile,
			    	'payment_status' => 'Paid',
			    	'profile_pic' =>$imagess,
			    	'address' => $address,
			    	//'about' => $about,
			    	'category_id'=>$category_id,
			    	'modified'=>date('Y-m-d h:i:s'),
			    );
				
				if($user_id = $this->Common_model->addEditRecords('users',$insert_data,array('user_id'=>$user_id)))  {
			
				 	
					//$response = array('data'=> array('status'=>'1','msg'=>$this->lang->line('update_success'),'user_id'=>$user_id));
					$response = array('data'=> array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong> '.$this->lang->line('update_success').'</div>'));

				    echo json_encode($response); exit;
 
				 }else{
				 	$err = array('data' =>array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>'.$this->lang->line('error_message').'</div>'));
				    echo json_encode($err); exit;

				 }
		}

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/edit_profile');
		$this->load->view('frontend/footer');
	}

	public function add_feedback()
	{
		$data['title']="Add Feedback | ".SITE_TITLE;
		$data['page_title']="Add Feedback";
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
			'title' => 'Add Feedback',
			'link' => ""
		);

		$user_id = $this->session->userdata('user_id');
		check_permission('doctor');
		$data['question_list'] = $this->Common_model->getRecords('individual_feedback','*',array('dr_id'=>$user_id),'',false);
		 

		if($this->input->post()){

				
			  	$question_type			=	$this->input->post('question_type');
			  	$question			=	$this->input->post('question');
			  	$question_option			=	$this->input->post('question_option');
			  
			    $date= date('Y-m-d H:i:s');
 

		      
			    $insert_data = array( 
			    	'dr_id'=>$user_id,
			    	'question'=>$question,
			    	'question_value'=>$question_option,
			    	'question_type'=>$question_type,	
			    	'created'=>date('Y-m-d h:i:s'),
			    );
				
				if($user_id = $this->Common_model->addEditRecords('individual_feedback',$insert_data))  {
			
				 	
					$response = array('data'=> array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong> '.$this->lang->line('add_question_success').'</div>'));

				    echo json_encode($response); exit;
 
				 }else{
				 	$err = array('data' =>array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>'.$this->lang->line('error_message').'</div>'));
				    echo json_encode($err); exit;

				 }
		}

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/add_feedback');
		$this->load->view('frontend/footer');
	}

	public function give_feedback($id)
	{
		$data['title']="Give Feedback | ".SITE_TITLE;
		$data['page_title']="Give Feedback";
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
			'title' => 'Give Feedback',
			'link' => ""
		);
		$data['doctor_id']= $id;

		$user_id = $this->session->userdata('user_id');
		check_permission('patient');
		$data['question_list'] = $this->Common_model->getRecords('individual_feedback','*',array('dr_id'=>$id),'',false);
		 
		

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/give_feedback');
		$this->load->view('frontend/footer');
	}

	function give_feedback_ajax(){
	//	echo "<pre>";print_r($_POST);die;
			$randon = rand(100000,9999999);
			$user_id = $this->session->userdata('user_id');
			$answer			=	$this->input->post('answer');
			$dr_id			=	$this->input->post('dr_id');
			if(!empty($answer)){
				foreach ($answer as $question_id => $ans) {
				
				  $insert_data = array( 
			    	'doctor_id'=>$dr_id,
			    	'question_id'=>$question_id, 
			    	'user_id'=>$user_id, 
			    	'answer'=>$ans,	
			    	'random'=>$randon,	
			    	'created'=>date('Y-m-d h:i:s'),
			    );
				 $this->Common_model->addEditRecords('individual_feedback_answer',$insert_data);
					# code...
				}
			}


			// $dr_detail = $this->Common_model->getRecords('users','*',array('dr_id'=>$dr_id),'',true);
			// $user_detail = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id),'',true);
			// $data['message'] = 'Patient '.$user_detail['full_name'].' has left feedback for your doctor.';
			// $subject = SITE_TITLE.': Feedback received for doctor.';
			// $data['username']=$dr_detail['full_name'];
			// $body = $this->load->view('template/common', $data,TRUE);
			// $to_email = $dr_detail['email'];
			// $from_email = getAdminEmail(); 
			// $this->Common_model->setMailConfig();
			// $this->Common_model->sendEmail($to_email,$subject,$body,$from_email);



			$response = array('data'=> array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong> '.$this->lang->line('add_ans_question_success').'</div>'));

			echo json_encode($response); exit;
 
	}

	public function delete_question(){
		$id = $this->input->post('id');

				
		$this->Common_model->deleteRecords('individual_feedback',array('id'=>$id));
	
			$response = array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong> '.$this->lang->line('delete_question_success').'</div>');

		    echo json_encode($response); exit;

	}

	public function change_password()
	{
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

		$user_id = $this->session->userdata('user_id');
		$user_type = $this->session->userdata('user_type');
		if(empty($user_id)){
				redirect(base_url());
		}

		$data['countries'] = $this->Common_model->getRecords('countries','*');
		$data['category_list'] = $this->Common_model->getRecords('categories','*',array('status'=>'Active'),'',false);
		$data['user_data'] = $this->Common_model->getRecords('users','*',array('user_id'=>$user_id),'',true);

		if($this->input->post()){
			$password = $this->input->post('password');
			$insert_data = array( 
			    
			    	'password' => base64_decode($password),
			    	'modified'=>date('Y-m-d h:i:s'),
			    );
				
				if($user_id = $this->Common_model->addEditRecords('users',$insert_data,array('user_id'=>$user_id)))  {
			
				 	
					//$response = array('data'=> array('status'=>'1','msg'=>$this->lang->line('update_success'),'user_id'=>$user_id));
					$response = array('data'=> array('status'=>'1','msg'=>'<div class="alert alert-success" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('success').'!</strong>'.'</strong> '.$this->lang->line('password_changed').'</div>'));

				    echo json_encode($response); exit;
 
				 }else{
				 	$err = array('data' =>array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>'.$this->lang->line('error_message').'</div>'));
				    echo json_encode($err); exit;

				 }
			
		}

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/change_password');
		$this->load->view('frontend/footer');
	}


	public function user_chating()
	{
	
	  	$sender_id		 =	$this->session->userdata('user_id');
	  	$receiver_id	 =	$this->input->post('receiver_id');
	  	$message		 =	$this->input->post('message');
	  	$type		     =	'chat';
		
  
	 	$get_room_id = $this->Common_model->getRoomid($sender_id,$receiver_id,$type);
	 	// echo $this->db->last_query();die;
		$update_data = array(
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,
            'room_id' => $get_room_id,
            'type' => $type,
          	'created' => date("Y-m-d H:i:s"),
		);
		$id = $this->Common_model->addEditRecords('users_chat',$update_data); 


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
			
		  //   if(!empty($receiver_record)){ 
				// if(strtolower($receiver_record['device_type'])=='android'){
				// 	$referrer = androidNotification($receiver_record['device_id'],$andarray);
				// } 
	   //  		if(strtolower($receiver_record['device_type'])=='ios'){
    //            		$referrer = iosNotification($receiver_record['device_id'],$iosarray);
	   //  		} 
		  //   }  
		    
		    $sender_record=$this->Common_model->getRecords('users_chat','*',array('room_id'=>$get_room_id),'id Desc',true);

		    $r_p =$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$sender_record['sender_id']),'',true);
		    $sender_record['sender_name'] =$r_p['full_name'];
		    $sender_record['sender_image'] =$r_p['profile_pic'];
		        
		    $r_p =$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$sender_record['receiver_id']),'',true);
		    $sender_record['receiver_name'] =$r_p['full_name'];
		    $sender_record['receiver_image'] =$r_p['profile_pic'];



		    if(file_exists(base_url().$sender_record['sender_image'])){ 

                $user_image =base_url().$sender_record['sender_image'];
             }else{
                 $user_image =base_url().'assets/front/img/user.png';
             }


		   		$html = '';
				$html .= ' <div class="outgoing_msg chatmsg" chat_id = '.$id.'>';
				$html .= '   <div class="sent_msg">';
				$html .= ' 	  <p>'.$message.'</p>';
				$html .= ' 	  <span class="time_date">'.date('h:i: A').' |  '.date('M d').'</span></div>';
				$html .= ' </div>'; 
		        
			$suc =array('status' => '1', 'msg' =>'Message send successfully', 'room_id'=>$get_room_id, 'detail'=>$html);
            echo json_encode($suc); exit; 	  
	}



	public function chat_history()
	{
		
	  	$receiver_id =$this->input->post('user_id');
	 	$user_id = $this->session->userdata('user_id');
	

		$chat_record = $this->Common_model->getChatlisting($user_id,$receiver_id);

		$html = '';
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
 				

				if($list['sender_id']==$user_id){
					$html .= ' <div class="outgoing_msg chatmsg"  chat_id = '.$list['id'].'>';
					$html .= '   <div class="sent_msg">';
					$html .= ' 	<p>'.$list['message'].'</p>';
					$html .= ' 	  <span class="time_date">'.date('h:i: A',strtotime($list['created'])).' |  '.date('M d',strtotime($list['created'])).'</span></div>';
					$html .= ' </div>'; 
 				}else{

	    			if(file_exists(base_url().$neww[$key]['sender_image'])){ 

                        $user_image =base_url().$neww[$key]['sender_image'];
					}else{
						$user_image =base_url().'assets/front/img/user.png';
					}

 					$html .= ' <div class="incoming_msg chatmsg" chat_id = '.$list['id'].'>';
					$html .= '   <div class="incoming_msg_img"> <img src="'.$user_image.'" alt="sunil"> </div>';
					$html .= '   <div class="received_msg">';
					$html .= ' 	<div class="received_withd_msg">';
					$html .= ' 	  <p>'.$list['message'].'</p>';
					$html .= ' 	  <span class="time_date">'.date('h:i: A',strtotime($list['created'])).' |  '.date('M d',strtotime($list['created'])).'</span></div>';
					$html .= '   </div>';
					$html .= ' </div>';
 				}
 
		    }

		    	$update_data = array(
		            'unread' =>0,
		            'type' =>'chat',
				);
				$this->Common_model->addEditRecords('users_chat',$update_data,array('sender_id' => $receiver_id,'receiver_id' => $user_id)); 
				//echo $this->db->last_query();die;
		    
		    $suc = array('status' => '1','detail'=>$html,'user_id'=>$receiver_id);
            echo json_encode($suc); exit; 
		}else
		{
		     $suc = array('status' => '0','detail'=>'','user_id'=>$receiver_id);
            echo json_encode($suc); exit; 
		    
		}
			  
	}

	public function get_message(){


		$receiver_id =$this->input->post('receiver_id');
		$last_msg =$this->input->post('last_msg');
	 	$user_id = $this->session->userdata('user_id');
		 
		$chat_record=$this->Common_model->getRecords('users_chat','*',array('sender_id'=>$receiver_id,'receiver_id'=>$user_id,'type'=>'chat','message!='=>'','id >'=>$last_msg),'',false);	
		//echo "<pre>";print_r($chat_record);die;
		$html = '';
		if(!empty($chat_record))
		{
		    foreach($chat_record as $key => $list)
		    {
		    	
		        $neww[$key]=$list;
		        $r_p =$this->Common_model->getRecords('users','full_name,profile_pic',array('user_id'=>$list['sender_id']),'',true);
		        $neww[$key]['sender_name'] =$r_p['full_name'];
		        $neww[$key]['sender_image'] =$r_p['profile_pic'];	 

    			if(file_exists(base_url().$r_p['profile_pic'])){ 

                    $user_image =base_url().$r_p['profile_pic'];
				}else{
					$user_image =base_url().'assets/front/img/user.png';
				}

				$html .= ' <div class="incoming_msg chatmsg" chat_id = '.$list['id'].'>';
				$html .= '   <div class="incoming_msg_img"> <img src="'.$user_image.'" alt="sunil"> </div>';
				$html .= '   <div class="received_msg">';
				$html .= ' 	<div class="received_withd_msg">';
				$html .= ' 	  <p>'.$list['message'].'</p>';
				$html .= ' 	  <span class="time_date">'.date('h:i: A',strtotime($list['created'])).' |  '.date('M d',strtotime($list['created'])).'</span></div>';
				$html .= '   </div>';
				$html .= ' </div>';
				
		    }

		    	$update_data = array(
		            'unread' =>0,
		            'type' =>'chat',
				);
				$this->Common_model->addEditRecords('users_chat',$update_data,array('sender_id' => $receiver_id,'receiver_id' => $user_id)); 
		    
		    $suc = array('status' => '1','detail'=>$html);
            echo json_encode($suc); exit; 
		}

	}


	public function book_appointment(){

			$user_id = $this->session->userdata('user_id');
			//$user_id = 1;
			$dr_id		 =$this->input->post('dr_id');

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



			$response = array('data'=> array('status'=>'2','msg'=>'success'));
			echo json_encode($response); exit;

	}



	public function like(){
		 
		$user_id = $this->session->userdata('user_id');
		//$user_id =1;
        $media_id		 =	($this->input->post('media_id'));
     
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
	     
         	$response = array('data'=> array('status'=>'1','msg'=>'like','count'=>$likes));
			echo json_encode($response); exit;  
        }
	} 



	public function appointment_list(){


		check_permission('doctor');

		$data['title']="Appointment List | ".SITE_TITLE;
		$data['page_title']="Appointment List";
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
		$user_id = $this->session->userdata('user_id');
		//$user_id = 7;
		$data['appointment_list']  = $this->Common_model->appointmentList($user_id);

		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/appointment_list');
		$this->load->view('frontend/footer');
	}


	public function feedback_list(){
		check_permission('doctor');
		$data['title']="Feedback List | ".SITE_TITLE;
		$data['page_title']="Feedback List";
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
		$user_id = $this->session->userdata('user_id');
		//$user_id = 1;
		$data['feedback_list']  = $this->Common_model->individual_feedback($user_id);
		//echo "<pre>";print_r($data['feedback_list']);die;
		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/feedback_list');
		$this->load->view('frontend/footer');
	}

	public function feedback_view($id) 
	{
		check_permission('doctor');
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
		$user_id =$this->session->userdata('user_id');
		//$user_id =1;
	 
		$data['question_list']  = $this->Common_model->individual_feedback_view($id);
	//	echo $this->db->last_query();
	//	echo "<pre>";print_r($data['question_list']);die;
		
		
		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/feedback_view');
		$this->load->view('frontend/footer');
	}	


	 public function notification_list() {

	 	$data['title']="Notification List | ".SITE_TITLE;
		$data['page_title']="Notification List";
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
			'title' => 'Notification List',
			'link' => ""
		);	

      	$user_id = $this->session->userdata('user_id');
      	//$user_id = 7;
      	if(empty($user_id)){
			
				redirect(base_url());
		}
		 
		$update_data = array(
            'badge_count' =>0,
            'modified' => date("Y-m-d H:i:s"),
	    );

		$this->Common_model->addEditRecords('users', $update_data,array('user_id'=>$user_id));
		
	    $data['notification'] = $this->Common_model->getRecords('notifications','*',array('user_id'=>$user_id),'notification_id Desc',false);
	  //  echo "<pre>";print_r( $data['notification'] );die;
		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/notification_list');
		$this->load->view('frontend/footer');
    }


	 public function advertisement_list() {

	 	$data['title']="Advertisement List | ".SITE_TITLE;
		$data['page_title']="Advertisement List";
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
			'title' => 'Advertisement List',
			'link' => ""
		);	

      	$user_id = $this->session->userdata('user_id');
      	//$user_id = 7;
      	if(empty($user_id)){
			
				redirect(base_url());
		}
		 
		$update_data = array(
            'badge_count' =>0,
            'modified' => date("Y-m-d H:i:s"),
	    );

		$this->Common_model->addEditRecords('users', $update_data,array('user_id'=>$user_id));
		
	    $data['advertisement'] = $this->Common_model->getRecords('advertisement','*',array('user_id'=>$user_id),'notification_id Desc',false);
	  //  echo "<pre>";print_r( $data['notification'] );die;
		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/advertisement_list');
		$this->load->view('frontend/footer');
    }




	 public function call($other_user_id) {

	 	$data['title']="Call | ".SITE_TITLE;
		$data['page_title']="Call";
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
			'title' => 'Call',
			'link' => ""
		);
		$a = $this->input->get('action');
		if($a=='accpet'){
			$data['accpet'] = 'yes';
		}else{
			$data['accpet'] = 'no';
		}

		$data['other_user_id'] = $other_user_id;

      	$user_id = $this->session->userdata('user_id');
      	//$user_id = 7;
      	if(empty($user_id)){
			
				//redirect(base_url());
		}

		$my_name = $this->Common_model->getRecords('users','full_name',array('user_id'=>$user_id),'',true);
		$data['my_name'] = $my_name['full_name'];
		$other_name = $this->Common_model->getRecords('users','full_name',array('user_id'=>$other_user_id),'',true);
		$data['other_name'] = $other_name['full_name'];

		$data['get_room_id']=$this->Common_model->getRoomidid($user_id,$other_user_id,'video');
	 	// echo $this->db->last_query();die;
	 	if(empty($data['get_room_id'])){
			$get_room_id = $this->Common_model->getRoomid($user_id,$other_user_id,'video');

	 		$update_data = array(
            'sender_id' => $user_id,
            'receiver_id' => $other_user_id,
            'message' => '',
            'room_id' => $get_room_id,
            'type' => 'video',
          	'created' => date("Y-m-d H:i:s"),
			);
			$id = $this->Common_model->addEditRecords('users_chat',$update_data); 
			$data['get_room_id']= $get_room_id;
	 	}
		 
	 	$data['user_data'] = $this->Common_model->getRecords('users','*',array('user_id'=>$other_user_id),'',true);
		 
		
		$this->load->view('frontend/header',$data);
		$this->load->view('frontend/call');
		$this->load->view('frontend/footer');
    }


    function check_call(){
    	$other_user_id= $this->input->post('other_user_id');
		$user_id = $this->session->userdata('user_id');
		if($this->Common_model->getRecords('users','*',array('user_id'=>$other_user_id,'chat_token'=>''),'',true)){

			$err =array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>User Offline Please call after some time</div>');
				echo json_encode($err); exit;
		}else{

		 $check_already_on_call = $this->Common_model->checkAlreadyCall($other_user_id,'video');
		 if(!empty($check_already_on_call)){
		 	$err =array('status' => '0', 'msg' =>'<div class="alert alert-danger" id="success-alert">  <button type="button" class="close" data-dismiss="alert">x</button> <strong>'.$this->lang->line('error').'</strong>User on other call</div>');
				echo json_encode($err); exit;
		 }else{

		 		$err =array('status' => '1');
				echo json_encode($err); exit;

		 }

		}

    }

}
