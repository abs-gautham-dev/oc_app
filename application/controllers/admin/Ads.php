<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ads extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin/Common_model');
		$this->load->helper('Common_helper');
		$this->load->model('admin/Admin_model');
		$this->load->model('admin/Other_model');

	}
	public function access($section_id,$type)
	{
		$admin_id = $this->session->userdata('admin_id');
		
	}

 

 	public function ads_list()
 	{
 		$this->Common_model->check_login();
		$this->access('17','view');
		$data['title']="Ads| ".SITE_TITLE;
		$data['page_title']="Ads";
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
			'title' => 'Ads',
			'link' => ""
		);
		
	 
	
		$admin_id = $this->session->userdata('admin_id');
		$where_page = array('admin_id'=>$admin_id,'section_id'=>'17');
		$data['records_result'] = $this->Common_model->getRecords('ads', '*','',"detail ASC", false);
  		
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/ads_list');	
		$this->load->view('admin/include/footer');
 	}

 	public function add_ad()
	{
		
		$this->Common_model->check_login();
		$data['title']="Add Ad | ".SITE_TITLE;
		$data['page_title']="Add Ad";
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
			'title' => 'Add Ad',
			'link' => ""
		);
	 	$data['categories'] = $this->Common_model->getRecords('categories','*');
		$data['sub_categories'] = $this->Common_model->getRecords('sub_categories','*');
		$data['user_idd'] =$this->uri->segment(4);;
		

	 if($this->input->post()) {


		 
				// if($_FILES['image']['name'] =='') { 
				// 	$this->session->set_flashdata('error', "Please upload image.");
				// } else {
				// 	if($_FILES['image']['error']==0) {
				// 		$image_path = CATEGORY_PATH;
				// 		$allowed_types = 'jpg|jpeg|png|svg';
				// 		$file='image';
				// 		$height = '';
				// 		$width = '';
				// 		$responce = commonImageUpload($image_path,$allowed_types,$file,$width,$height);
				
				// 		if($responce['status']==0){
				// 			$data['upload_error'] = $responce['msg'];	
				// 		} else {
				// 			$insert_data = array(
				// 				'category_id'=> $this->input->post('category_id'),
				// 				'sub_category_id'=> $this->input->post('sub_category_id'),
				// 				'title'=> $this->input->post('title'),
				// 				'detail'=> $this->input->post('detail'),
				// 				'phone_number'=> $this->input->post('phone_number'),
				// 				'status'=> $this->input->post('status'),
				// 				'image'=> $responce['image_path'],
				// 				'approved'=> 1,
				// 				'created'=> date("Y-m-d H:i:s")
				// 			);
							
				// 	 		if(!$this->Common_model->addEditRecords('ads', $insert_data)) {
				// 				$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				// 			} else {
				// 				$this->session->set_flashdata('success', 'Category Added Successfully.');
				// 				redirect('admin/ads/ads_list');
				// 			}
				// 		}
				// 	} else {
				// 		$this->session->set_flashdata('error', "Invalid image, Please try again.");
				// 	}
				// }
			 		// $image_name = implode(',',$image_path);


					$insert_data = array(
								'category_id'=> $this->input->post('category_id'),
								'sub_category_id'=> $this->input->post('sub_category_id'),
								// 'title'=> $this->input->post('title'),
								'detail'=> $this->input->post('detail'),
								'phone_number'=> $this->input->post('phone_number'),
								'status'=> $this->input->post('status'), 
								'approved'=> 1,
								'created'=> date("Y-m-d H:i:s")
							);
							
					 		if(!$ad_id= $this->Common_model->addEditRecords('ads', $insert_data)) {
								$this->session->set_flashdata('error', 'Some error occured! Please try again.');
							} else {


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

						 		// die;
								$this->session->set_flashdata('success', 'Category Added Successfully.');
								redirect('admin/ads/ads_list');
							}
			 
		}
		
		 $data['categories']= $this->Common_model->getRecords('categories', '*',array('status!='=>'Inactive'),"", false);
	  	 
	 
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_ad');
		$this->load->view('admin/include/footer');
	}

 	public function ad_edit()
	{
		$this->Common_model->check_login();
		$admin_id = $this->session->userdata('admin_id');
		
		$id = $this->uri->segment('4');
		 
	
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Edit Ad | ".SITE_TITLE;
		$data['page_title']="Edit Ad";
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
			'title' => 'Ad list',
			'link' => site_url('admin/ads/ads_list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Ad',
			'link' => ""
		);	
		if(!$data['ads']=$this->Common_model->getRecords('ads','*',array('id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}
		

		$data['categories']= $this->Common_model->getRecords('categories', '*',array('status!='=>'Inactive'),"", false);
		$data['ad_images']= $this->Common_model->getRecords('ads_images', '*',array('ad_id'=>$id),"", false);
			  	 
	    // echo "<pre>";print_r($data['categories']);die;
			
		if($this->input->post()) {   
			$category = $this->input->post('category');
			$old_image = $this->input->post('old_image');

			// if($_FILES['image']['name'] =='') { 
				 
			// 		$image_path = $data['ads']['image'];
			// 	} else 
			// 	{
			// 		if($_FILES['image']['error']==0) {
			// 			$image_path = CATEGORY_PATH;
			// 			$allowed_types = 'jpg|jpeg|png|svg';
			// 			$file='image';
			// 			$height = '';
			// 			$width = '';
			// 			$responce = commonImageUpload($image_path,$allowed_types,$file,$width,$height);
				
			// 			if($responce['status']==0){
			// 				$data['upload_error'] = $responce['msg'];	
			// 			} else {
			// 				$image_path=$responce['image_path'];
							
					 		
			// 			}
			// 		} else {
			// 			$this->session->set_flashdata('error', "Invalid image, Please try again.");
			// 		}
			// 	}


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

				if(!$ad_id = $this->Common_model->addEditRecords('ads', $insert_data,array('id'=>$id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {

						$this->Common_model->deleteRecords('ads_images',array('ad_id'=>$id));
						// echo $this->db->last_qiery();
						 		if(!empty($old_image))
						 		{
						 			foreach ($old_image as $key => $image_list) {

						 				$insert_data = array(
												'ad_id'=> $id,
												'image'=>$image_list,
											);
										
								 		if(!$this->Common_model->addEditRecords('ads_images', $insert_data)) {
											$this->session->set_flashdata('error', 'Some error occured! Please try again.');
										}
						 										 			
						 			}
						 		}


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
												'ad_id'=> $id,
												'image'=>CATEGORY_PATH.$config['file_name'].$fileData['file_ext'],
											);
										
								 		if(!$this->Common_model->addEditRecords('ads_images', $insert_data)) {
											$this->session->set_flashdata('error', 'Some error occured! Please try again.');
										}
										}
									}
						 		}  








					$this->session->set_flashdata('success', 'Ad Updated Successfully.');
					redirect('admin/ads/ads_list');
				}
				 
		 
		}
		$data['from_action']=site_url('admin/sub_categories/edit/'.$id);
		//$data['back_action']=$_SERVER['HTTP_REFERER'];
		// echo "<pre>"; print_r($data); exit;	
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/edit_ad');
		$this->load->view('admin/include/footer');
	}


	public function get_sub_category()
    {
    	$sub_categories = array();
       	if($_POST['id']) {
       		$sub_categories = $this->Common_model->getRecords('sub_categories', 'category_id as id,name', array('category_id'=>$_POST['id']), '', false);
       	} 
        echo json_encode($sub_categories);  exit;
    }




} // class end