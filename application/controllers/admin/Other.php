<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Other extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin/Common_model');
		$this->load->model('admin/Other_model');
		$this->load->model('App_model');
		$this->load->helper('common_helper');
		error_reporting(0);
	}

	public function index()	{
		$this->Common_model->check_login();
		$this->fabric_list();
	}

	public function add_category() 
	{	$admin_id = $this->session->userdata('admin_id');
		
		$this->Common_model->check_login();
		$data['title']="Add Category | ".SITE_TITLE;
		$data['page_title']="Add Category";
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
			'title' => 'Category List',
			'link' => site_url('admin/categories/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Category',
			'link' => ""
		);	
		if($this->input->post()) {
			$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[categories.name ]',array('required'=>'Please enter %s','is_unique'=>'This %s already exists.'));
			$this->form_validation->set_rules('status', 'status', 'trim|required',array('required'=>'Please select %s'));
			$this->form_validation->set_error_delimiters('<p class="inputerror">', '</p>');

			if($this->form_validation->run()==TRUE) 
			{
				$order_data = $this->Common_model->getRecords('categories','MAX(orders) as max_order','','',true);	
				$order = $order_data['max_order']+1;
				/*
				if($_FILES['image']['name'] =='') { 
					$this->session->set_flashdata('error', "Please upload image.");
				} else {
					if($_FILES['image']['error']==0) {
						$image_path = CATEGORY_PATH;
						$allowed_types = 'jpg|jpeg|png|svg';
						$file='image';
						$height = 100;
						$width = 100;
						$responce = commonImageUpload($image_path,$allowed_types,$file,$width,$height);
				
						if($responce['status']==0){
							$data['upload_error'] = $responce['msg'];	
						} else {
							$insert_data = array(
								'name'=> $this->input->post('name'),
								'orders'=> $order,
								'status'=> $this->input->post('status'),
								'image'=> $responce['image_path'],
								'created'=> date("Y-m-d H:i:s")
							);
							
					 		if(!$this->Common_model->addEditRecords('categories', $insert_data)) {
								$this->session->set_flashdata('error', 'Some error occured! Please try again.');
							} else {
								$this->session->set_flashdata('success', 'Category Added Successfully.');
								redirect('admin/categories/list');
							}
						}
					} else {
						$this->session->set_flashdata('error', "Invalid image, Please try again.");
					}
				}*/

				$insert_data = array(
					'name'=> $this->input->post('name'),
					'orders'=> $order,
					'status'=> $this->input->post('status'),
					// 'image'=> $responce['image_path'],
					'created'=> date("Y-m-d H:i:s")
				);
				if(!$this->Common_model->addEditRecords('categories', $insert_data)) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->session->set_flashdata('success', 'Category Added Successfully.');
					redirect('admin/categories/list');
				}
			}
		}
		
		$data['from_action']=site_url('admin/categories/add');
		$data['back_action']=site_url('admin/categories/list');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_category');
		$this->load->view('admin/include/footer');
	}

	
	public function edit_category($id) 
	{   

		$this->Common_model->check_login();
		$admin_id = $this->session->userdata('admin_id');
		
		$data['title']="Edit Category | ".SITE_TITLE;
		$data['page_title']="Edit Category";
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
			'title' => 'Category list',
			'link' => site_url('admin/categories/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Category',
			'link' => ""
		);	

		if(!$data['category']=$this->Common_model->getRecords('categories','*',array('category_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}
		
		if($this->input->post()) {


			if(strtolower($data['category']['name'])== strtolower(trim($this->input->post('name')))) {
				$this->form_validation->set_rules('name', 'name', 'trim|required',array('required'=>'Please enter %s'));
			} else {
				$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[categories.name ]',array('required'=>'Please enter %s','is_unique'=>'This %s already exists.'));
			}
			$this->form_validation->set_rules('status', 'status', 'trim|required',array('required'=>'Please select %s'));
			$this->form_validation->set_error_delimiters('<p class="inputerror">', '</p>');

			if($this->form_validation->run()==TRUE) 
			{


				/*if($_FILES['image']['name'] =='') { 
				 
					$image_path = $data['category']['image'];
				} else 
				{
					if($_FILES['image']['error']==0) {
						$image_path = CATEGORY_PATH;
						$allowed_types = 'jpg|jpeg|png|svg';
						$file='image';
						$height = '';
						$width = '';
						$responce = commonImageUpload($image_path,$allowed_types,$file,$width,$height);
				
						if($responce['status']==0){
							$data['upload_error'] = $responce['msg'];	
						} else {
							$image_path=$responce['image_path']; 
						}
					} else {
						$this->session->set_flashdata('error', "Invalid image, Please try again.");
					}
				}*/ 


				$update_data = array(
					'status'=> $this->input->post('status'),
					'name'=> $this->input->post('name'),
					// 'image'=> $image_path,
					'modified'=> date("Y-m-d H:i:s")
				);
				//echo "<pre>"; print_r($id); exit;	
		 		if(!$this->Common_model->addEditRecords('categories', $update_data,array('category_id'=>$id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->session->set_flashdata('success', 'Category Updated Successfully.');
					redirect('admin/categories/list');
				}
			}
		}
		$data['from_action']=site_url('admin/categories/edit/'.$id);
		$data['back_action']=site_url('admin/categories/list');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/edit_category');
		$this->load->view('admin/include/footer');

	}

	public function category_list()
	{
		$this->Common_model->check_login();
		$admin_id = $this->session->userdata('admin_id');
			
		$data['title']="Category List | ".SITE_TITLE;
		$data['page_title']="Category List";
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
			'title' => 'Category List',
			'link' => ""
		);
		
		$page = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
		$data['records_result']=$this->Common_model->getRecords('categories', '*', "","name ASC", false);
		$data['total_records'] = count($data['records_result']);
		$data['add_action']=site_url('admin/categories/add');
		
		$data['subCategories']=site_url('admin/sub_categories/list/');
		
		// $data['ratingCategories']=site_url('admin/rating_categories/list');
	//	if($access['edit']=='1'){$data['edit_action']=site_url('admin/categories/edit');}

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/category_list');
		$this->load->view('admin/include/footer');
	} 

	public function media_list()
	{
		$this->Common_model->check_login();
		$admin_id = $this->session->userdata('admin_id');
			
		$data['title']="Media List | ".SITE_TITLE;
		$data['page_title']="Media List";
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
			'title' => 'Media List',
			'link' => ""
		);
		
		$page = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
		$data['records_result']=$this->Common_model->getmediaRecords();
		// echo "<pre>";print_r($data['records_result']);die;
		$data['total_records'] = count($data['records_result']);
		// $data['add_action']=site_url('admin/categories/add'); 
		
		// $data['ratingCategories']=site_url('admin/rating_categories/list');
	//	if($access['edit']=='1'){$data['edit_action']=site_url('admin/categories/edit');}

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/media_list');
		$this->load->view('admin/include/footer');
	} 

	public function add_subcategory() 
	{
		$this->Common_model->check_login();
		$admin_id = $this->session->userdata('admin_id');
		
		$data['title']="Add Sub Category | ".SITE_TITLE;
		$data['page_title']="Add Sub Category";
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
			'title' => 'Category List',
			'link' => site_url('admin/sub_categories/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Sub Category',
			'link' => ""
		);	
		if($this->input->post()) {
			$this->form_validation->set_rules('category', 'category', 'trim|required',array('required'=>'Please enter %s'));
			$category = $this->input->post('category');
		 
			$this->form_validation->set_rules('status', 'status', 'trim|required',array('required'=>'Please select %s'));
			$this->form_validation->set_error_delimiters('<p class="inputerror">', '</p>');

			if($this->form_validation->run()==TRUE) 
			{ 

				$order_data = $this->Common_model->getRecords('sub_categories','MAX(orders) as max_order','','',true);	
				$order = $order_data['max_order']+1;
 
				if($_FILES['image']['name'] =='') { 
					$this->session->set_flashdata('error', "Please upload image.");
				} else {
					if($_FILES['image']['error']==0) {
						$image_path = CATEGORY_PATH;
						$allowed_types = 'jpg|jpeg|png|svg';
						$file='image';
						$height = 100;
						$width = 100;
						$responce = commonImageUpload($image_path,$allowed_types,$file,$width,$height);
				
						if($responce['status']==0){
							$data['upload_error'] = $responce['msg'];	
						} else {
							$insert_data = array(
								'name'=> $this->input->post('name'),
								'orders'=> $order,
								'category_id'=> $this->input->post('category'),
								'image'=> $responce['image_path'],
								'created'=> date("Y-m-d H:i:s")
							);
							
					 		if(!$this->Common_model->addEditRecords('sub_categories', $insert_data)) {
								$this->session->set_flashdata('error', 'Some error occured! Please try again.');
							} else {
								$this->session->set_flashdata('success', 'Sub Category Added Successfully.');
								redirect('admin/sub_categories/list/'.$this->input->post('category'));
							}
						}
					} else {
						$this->session->set_flashdata('error', "Invalid image, Please try again.");
					}
				}
			}
		}
			   
	    $data['categories']=$this->Common_model->getDropdownList('categories','category_id','name','Category',array('status!='=>'Inactive'));
	    
		$data['from_action']=site_url('admin/sub_categories/add');
		$data['back_action']=$_SERVER['HTTP_REFERER'];

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_subcategory');
		$this->load->view('admin/include/footer');
	}

	public function subcategory_list()
	{
		$this->Common_model->check_login();
		$admin_id = $this->session->userdata('admin_id');
		
		$data['title']="Sub Category List | ".SITE_TITLE;
		$data['page_title']="Sub Category List";
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
			'title' => 'Sub Category List',
			'link' => ""
		);
		
		$category_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		
		$data['records_result']=$this->Other_model->getSubCategories($category_id);
		$data['total_records'] = count($data['records_result']);
		// echo "<pre>";print_r($data['records_result']);die;
		$data['add_action']=site_url('admin/sub_categories/add');
		$data['edit_action']=site_url('admin/sub_categories/edit');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/subcategory_list');
		$this->load->view('admin/include/footer');
	} 

	public function edit_subcategory($id) 
	{
		$this->Common_model->check_login();
		$admin_id = $this->session->userdata('admin_id');
		
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Edit Sub Category | ".SITE_TITLE;
		$data['page_title']="Edit Sub Category";
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
			'title' => 'Sub Category list',
			'link' => site_url('admin/sub_categories/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Sub Category',
			'link' => ""
		);	
		if(!$data['subcategory']=$this->Common_model->getRecords('sub_categories','*',array('sub_category_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}
		// echo "<pre>";print_r();die;

		$categories=$this->Common_model->getDropdownList('categories','category_id','name','Category',array('status'=>'Active'));

		  // echo "<pre>";print_r($categories);die;
		if(!empty( $categories))
	    {
	    	foreach ($categories as $key => $value) {

	    			 
	    			 	$data['categories'][$key] =  $value;
	    			 
	    	} 
	    }
	    // echo "<pre>";print_r($data['categories']);die;
		$this->form_validation->set_rules('category', 'category', 'trim|required',array('required'=>'Please enter %s'));
		$category = $this->input->post('category');
			
		if($this->input->post()) {
			if(strtolower($data['subcategory']['name']) == strtolower(trim($this->input->post('name')))) {
				$this->form_validation->set_rules('name', 'name', 'trim|required',array('required'=>'Please enter %s'));
			} else {
				$this->form_validation->set_rules('name', 'name', 'trim|required|callback_sub_categories_check[' . $category . ']',array('required'=>'Please enter %s','sub_categories_check'=>'This %s already exists.'));
			}
			
			$this->form_validation->set_rules('status', 'status', 'trim|required',array('required'=>'Please select %s'));
			$this->form_validation->set_error_delimiters('<p class="inputerror">', '</p>');

			if($this->form_validation->run()==TRUE) 
			{

				if($_FILES['image']['name'] =='') { 
				 
					$image_path = $data['subcategory']['image'];
				} else 
				{
					if($_FILES['image']['error']==0) {
						$image_path = CATEGORY_PATH;
						$allowed_types = 'jpg|jpeg|png|svg';
						$file='image';
						$height = '';
						$width = '';
						$responce = commonImageUpload($image_path,$allowed_types,$file,$width,$height);
				
						if($responce['status']==0){
							$data['upload_error'] = $responce['msg'];	
						} else {
							$image_path=$responce['image_path']; 
						}
					} else {
						$this->session->set_flashdata('error', "Invalid image, Please try again.");
					}
				} 
				
				$update_data = array(
					'name'=> $this->input->post('name'),
					'category_id'=> $this->input->post('category'),
					'image'=> $image_path,
					'price'=> $this->input->post('price'),
					'status'=> $this->input->post('status'),
					'modified'=> date("Y-m-d H:i:s")
				);
			
				
				
		 		if(!$this->Common_model->addEditRecords('sub_categories', $update_data,array('sub_category_id'=>$id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					 
	 

					$this->session->set_flashdata('success', 'Sub Category Updated Successfully.');
					redirect('admin/sub_categories/list/'.$this->input->post('category'));
				}
			}
		}
		$data['from_action']=site_url('admin/sub_categories/edit/'.$id);
		$data['back_action']=$_SERVER['HTTP_REFERER'];
		// echo "<pre>"; print_r($data); exit;	
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/edit_subcategory');
		$this->load->view('admin/include/footer');

	}
	public function change_approved() 
	{
		
		$id = $this->input->post('id');
		$status = $this->Common_model->getRecords('ads','approved',array('id'=>$id),'',true);
		if($status['approved']==0)
		{
			$new_status = 1;
		}else
		{
			$new_status =0;
		}

		$this->Common_model->addEditRecords('ads', array('approved'=>$new_status),array('id'=>$id));
		echo 'Yes';die;


		// echo "<pre>";print_r();die;

	

	}
	public function change_approved_faq() 
	{
		
		$id = $this->input->post('id');
		$status = $this->Common_model->getRecords('faq','approved',array('faq_id'=>$id),'',true);
		if($status['approved']==0)
		{
			$new_status = 1;
		}else
		{
			$new_status =0;
		}

		$this->Common_model->addEditRecords('faq', array('approved'=>$new_status),array('faq_id'=>$id));
		echo 'Yes';die;


		// echo "<pre>";print_r();die;

	

	}

	/*********************************Interest*****************************/
 
 

} // class end