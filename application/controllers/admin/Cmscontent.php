<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmscontent extends CI_Controller {
	public function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/Common_model');
		$this->load->helper('common_helper');
	}

	public function access($section_id,$type)
	{
		$admin_id = $this->session->userdata('admin_id');
		


	}


	public function index()
	{
		$this->Common_model->check_login();
		$this->banners_list();
	}

	public function banners_list()
	{
		$this->Common_model->check_login();
		$data['title']="banner image | ".SITE_TITLE;
		$data['page_title']="banner image";
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
			'title' => 'banner image',
			'link' => ""
		);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$where=array();
		$data['records_result']=$this->Common_model->getRecords('banners', '*', "","", false,$page);
		$data['pagination']=$this->Common_model->paginate(site_url('admin/banners/list'),$this->Common_model->getNumRecords('banners','banner_id'));
		// echo "<pre>";print_r($data);exit;

		$data['add_action']=site_url('admin/banner/add');
		$data['edit_action']=site_url('admin/banner/edit');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/banners_list');
		$this->load->view('admin/include/footer');
	} 

	public function add_banner() 
	{
		$this->Common_model->check_login();
		$data['title']="Add banner image | ".SITE_TITLE;
		$data['page_title']="Add banner image";
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
			'title' => 'banner image',
			'link' => site_url('admin/banner/image')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add banner image',
			'link' => ""
		);	
		
	    if(isset($_FILES['image'])){
			if($_FILES['image']['name'] =='') { 
				$this->session->set_flashdata('error', "Please upload image.");
			} else {
				if($_FILES['image']['error']==0) {
					$image_path = BANNER_PATH;
					$allowed_types = 'jpg|jpeg|png';
					$file='image';
					$responce = bannerUpload($image_path,$allowed_types,$file);
			
					if($responce['status']==0){
						$data['upload_error'] = $responce['msg'];	
					} else {
						$insert_data = array(
							
							'image'=> $responce['image_path'],
							'created'=> date("Y-m-d H:i:s")
						);
						
				 		if(!$this->Common_model->addEditRecords('banners', $insert_data)) {
							$this->session->set_flashdata('error', 'Some error occured! Please try again.');
						} else {
							$this->session->set_flashdata('success', 'Banner Image Added Successfully.');
							redirect('admin/banner/image');
						}
					}
				} else {
					$this->session->set_flashdata('error', "Invalid image, Please try again.");
				}
			}
		}
		
		$data['from_action']=site_url('admin/banner/add');
		$data['back_action']=site_url('admin/banner/image');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_banner');
		$this->load->view('admin/include/footer');
	}	

	public function edit_banner($id) 
	{
		$this->Common_model->check_login();
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Edit banner | ".SITE_TITLE;
		$data['page_title']="Edit banner image";
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
			'title' => 'banner image',
			'link' => site_url('admin/banner/image')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit banner image',
			'link' => ""
		);	
		
		if(!$data['banners']=$this->Common_model->getRecords('banners','*',array('banner_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}

		if($this->input->post()) {
		 
		 

	
				if($_FILES['image']['name'] =='') { 
				 
					$image_path = $data['banners']['image'];
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
					'link'=> $this->input->post('link'),
					'image'=> $image_path,
					'modified'=> date("Y-m-d H:i:s")
				);
				//echo "<pre>"; print_r($id); exit;	
		 		if(!$this->Common_model->addEditRecords('banners', $update_data,array('banner_id'=>$id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->session->set_flashdata('success', 'banners Updated Successfully.');
					redirect('admin/banner/image');
				}
		 
		}

		$data['from_action']=site_url('admin/banner/edit/'.$id);
		$data['back_action']=site_url('admin/banner/image');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/edit_banner');
		$this->load->view('admin/include/footer');

	}
	//end of banners

	

	//Cms page start
	public function pages_list()
	{
		$this->Common_model->check_login();
		$this->access('12','view');
		$data['title']="Pages | ".SITE_TITLE;
		$data['page_title']="Pages";
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
			'title' => 'Pages',
			'link' => ""
		);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$where=array();
		$data['records_result']=$this->Common_model->getRecords('pages', '*', "","title asc", false,$page);
		$data['pagination']=$this->Common_model->paginate(site_url('admin/pages/list'),$this->Common_model->getNumRecords('pages','page_id'));
		$admin_id = $this->session->userdata('admin_id');
		
		$data['edit_action']=site_url('admin/pages/edit');
		
		// echo "<pre>";print_r($data);exit;
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/pages_list');
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
			'class'=>'',
			'title' => 'Pages List',
			'link' => site_url('admin/pages/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Page',
			'link' => ""
		);	
		if($this->input->post()) {
			$this->form_validation->set_rules('title', 'name', 'trim|required',array('required'=>'Please enter %s'));
			$this->form_validation->set_rules('content', 'content', 'trim|required',array('required'=>'Please enter %s'));
			$this->form_validation->set_rules('seo_title', 'seo title', 'trim');
			$this->form_validation->set_rules('seo_description', 'seo description', 'trim');
			$this->form_validation->set_rules('seo_keywords', 'seo keywords', 'trim');
			$this->form_validation->set_error_delimiters('<p class="inputerror">', '</p>');

			if($this->form_validation->run()==TRUE) 
			{
				$insert_data = array(
					'title'=> $this->input->post('title'),
					'content'=> $this->input->post('content'),
					'seo_title'=> $this->input->post('seo_title'),
					'seo_description'=> $this->input->post('seo_description'),
					'seo_keywords'=> $this->input->post('seo_keywords'),
					'created'=> date("Y-m-d H:i:s")
				);
							
		 		if(!$this->Common_model->addEditRecords('pages', $insert_data)) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->session->set_flashdata('success', 'Page Added Successfully.');
					redirect('admin/pages/list');
				}
			}
		}
		
		$data['from_action']=site_url('admin/pages/add');
		$data['back_action']=site_url('admin/pages/list');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_page');
		$this->load->view('admin/include/footer');
	}	
	public function notification() 
	{
		$this->Common_model->check_login();
		$data['title']="Notification | ".SITE_TITLE;
		$data['page_title']="Notification";
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
			'title' => 'Notification List',
			'link' => site_url('admin/pages/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Notification List',
			'link' => ""
		);	
		$admin_id =$this->session->userdata('admin_id');
		$data['records_results'] = $this->Common_model->simple_notification_list();
		$data['add_action'] = base_url().'admin/notification/add';
		// echo $this->db->last_query();
		// echo "<pre>";print_r($data['records_results']);die;
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/notification_list');
		$this->load->view('admin/include/footer');
	}	

	public function notification_add() 
	{
		$this->Common_model->check_login();
		$data['title']="Notification | ".SITE_TITLE;
		$data['page_title']="Notification";
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
			'title' => 'Notification List',
			'link' => site_url('admin/pages/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Notification',
			'link' => ""
		);	
		$admin_id =$this->session->userdata('admin_id');
		$data['dr_list']=$this->Common_model->getRecords('users', '*',array('is_deleted'=>0,'status'=>'Active','user_type'=>'Doctor'),"user_id Desc", false);
		$data['patient_list']=$this->Common_model->getRecords('users', '*',array('is_deleted'=>0,'status'=>'Active','user_type'=>'Patient'),"user_id Desc", false);
		if($this->input->post()) {
		 	$alert_message = $this->input->post('notification');
		 	$users = $this->input->post('users');
		 	$this->Common_model->addEditRecords('notifications',array('notification_title'=>'Notification','notification_description'=>$alert_message,'created'=>date('Y-m-d h:i:s')));
		 	// echo $this->db->last_query();die;
			
			if(!empty($users))
			{
				$rand = rand(1,99999999);
				foreach ($users as $key => $list) {
					$details = 	$this->Common_model->getRecords('users','device_id,device_type',array('user_id'=>$list),'',true);
					if(!empty($details)){
						$this->Common_model->push_notification_send($details['device_id'],$details['device_type'],$alert_message,'simple_notification');
						  $add_data =array('user_id' => $list,'created_by' =>$admin_id,'type'=>'simple_notification', 'notification_title'=>$alert_message, 'notification_description'=>$alert_message,'created'=>date('Y-m-d H:i:s'),'is_admin'=>1,'group_by'=>$rand);
			    		$this->Common_model->addEditRecords('notifications',$add_data); 
   
					} 	
				}
			}
			$this->session->set_flashdata('success', 'Notification Send Successfully.');
			redirect('admin/notification');	 
		}

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_notification');
		$this->load->view('admin/include/footer');
	}	


	public function advertisement() 
	{
		$this->Common_model->check_login();
		$data['title']="Advertisement | ".SITE_TITLE;
		$data['page_title']="Advertisement";
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
		$admin_id =$this->session->userdata('admin_id');
		$data['records_results'] = $this->Common_model->advertisementList();
		$data['add_action'] = base_url().'admin/advertisement/add';
		// echo $this->db->last_query();
		// echo "<pre>";print_r($data['records_results']);die;
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/advertisement_list');
		$this->load->view('admin/include/footer');
	}	

	public function advertisement_add() 
	{
		$this->Common_model->check_login();
		$data['title']="Advertisement | ".SITE_TITLE;
		$data['page_title']="Advertisement";
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
			'title' => 'Advertisement List',
			'link' => site_url('admin/advertisement/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Advertisement',
			'link' => ""
		);	
		$admin_id =$this->session->userdata('admin_id');
		$data['dr_list']=$this->Common_model->getRecords('users', '*',array('is_deleted'=>0,'status'=>'Active','user_type'=>'Doctor'),"user_id Desc", false);
		$data['patient_list']=$this->Common_model->getRecords('users', '*',array('is_deleted'=>0,'status'=>'Active','user_type'=>'Patient'),"user_id Desc", false);
		if($this->input->post()) {
		 	$alert_message = $this->input->post('title');
		 	$users = $this->input->post('users');
		 	$detail = $this->input->post('detail');
		  
		  	if(isset($_FILES['image'])){
				if($_FILES['image']['name'] =='') { 
					$this->session->set_flashdata('error', "Please upload image.");
				} else {
					if($_FILES['image']['error']==0) {
						$image_path = 'resources/images/featured_image/';
						$allowed_types = '*';
						$file='image';
						$responce = bannerUpload($image_path,$allowed_types,$file);
				
						if($responce['status']==0){
							$data['upload_error'] = $responce['msg'];	
						} else {
							 $image_paths =$responce['image_path'];
						}
					} else {
						$this->session->set_flashdata('error', "Invalid image, Please try again.");
						redirect('admin/advertisement/add');	 
					}
				}
			}
			
			if(!empty($users))
			{
				$rand = rand(1,99999999);
				foreach ($users as $key => $list) {
					$details = 	$this->Common_model->getRecords('users','device_id,device_type',array('user_id'=>$list),'',true);
					if(!empty($details)){
						// echo "<pre>";print_r(	$details);die;
						$this->Common_model->push_notification_send($details['device_id'],$details['device_type'],$alert_message,'ad_notification');
						  $add_data =array('user_id' => $list,'created_by' =>$admin_id,'type'=>'ad_notification', 'notification_title'=>$alert_message, 'notification_description'=>trim($detail),'created'=>date('Y-m-d H:i:s'),'is_admin'=>1,'group_by'=>$rand,'image'=>$image_paths);
			    		$this->Common_model->addEditRecords('advertisement',$add_data); 
   
					} 	
				}
			}
			$this->session->set_flashdata('success', 'Advertisement Send Successfully.');
			redirect('admin/advertisement');	 
		}

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_advertisement');
		$this->load->view('admin/include/footer');
	}	



	public function edit_page($id) 
	{
		$this->Common_model->check_login();
		$this->access('12','edit');
		if(!$id) {
			redirect('pages/page_not_found');
		}
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
			'class'=>'',
			'title' => 'Pages List',
			'link' => site_url('admin/pages/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Page',
			'link' => ""
		);	
		
		if(!$data['pages']=$this->Common_model->getRecords('pages','*',array('page_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}

		if($this->input->post()) {
			$this->form_validation->set_rules('title', 'name', 'trim|required',array('required'=>'Please enter %s'));
			$this->form_validation->set_rules('content', 'content', 'trim|required',array('required'=>'Please enter %s'));
			$this->form_validation->set_rules('seo_title', 'seo title', 'trim');
			$this->form_validation->set_rules('seo_description', 'seo description', 'trim');
			$this->form_validation->set_rules('seo_keywords', 'seo keywords', 'trim');
		    $this->form_validation->set_error_delimiters('<p class="inputerror">', '</p>');

			if($this->form_validation->run()==TRUE) 
			{
				$update_data = array(
					'title'=> $this->input->post('title'),
					'content'=> $this->input->post('content'),
					'seo_title'=> $this->input->post('seo_title'),
					'seo_description'=> $this->input->post('seo_description'),
					'seo_keywords'=> $this->input->post('seo_keywords'),
					'created'=> date("Y-m-d H:i:s")
				);
				//echo "<pre>"; print_r($id); exit;	
		 		if(!$this->Common_model->addEditRecords('pages', $update_data,array('page_id'=>$id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->session->set_flashdata('success', 'Page Updated Successfully.');
					redirect('admin/pages/list');
				}
			}
		}

		$data['from_action']=site_url('admin/pages/edit/'.$id);
		$data['back_action']=site_url('admin/pages/list');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/edit_page');
		$this->load->view('admin/include/footer');

	}
	//end of cms pages
    
    //faq page start
	public function faq_list()
	{
		$this->Common_model->check_login();
		$this->access('13','view');
		$data['title']="Faq | ".SITE_TITLE;
		$data['page_title']="Frequently asked questions";
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
			'title' => 'Faq',
			'link' => ""
		);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$where=array();
		$data['records_result']=$this->Common_model->getRecords('faq', '*', "","faq_id asc", false,$page);
		$data['total_records'] = count($data['records_result']);
		$data['pagination']=$this->Common_model->paginate(site_url('admin/faq/list'),$this->Common_model->getNumRecords('faq','faq_id'));
		$admin_id = $this->session->userdata('admin_id');
		
		$data['add_action']=site_url('admin/faq/add');
		$data['edit_action']=site_url('admin/faq/edit');
		$data['delete_action']=site_url('admin');
		
		
		// echo "<pre>";print_r($data);exit;
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/faq_list');
		$this->load->view('admin/include/footer');
	} 

	public function add_faq() 
	{
		$this->Common_model->check_login();
		$this->access('13','add');
		$data['title']="Add Faq | ".SITE_TITLE;
		$data['page_title']="Add Faq";
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
			'title' => 'Faq List',
			'link' => site_url('admin/faq/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Faq',
			'link' => ""
		);	
		if($this->input->post()) {
			$this->form_validation->set_rules('question', 'Question', 'trim|required',array('required'=>'Please enter %s'));
			$this->form_validation->set_rules('answer', 'answer', 'trim|required',array('required'=>'Please enter %s'));
			$this->form_validation->set_error_delimiters('<p class="inputerror">', '</p>');

			if($this->form_validation->run()==TRUE) 
			{

				$order_data = $this->Common_model->getRecords('faq','MAX(orders) as max_order','','',true);	
				$order = $order_data['max_order']+1;

				if($_FILES['image']['name'] =='') { 
					

						$insert_data = array(
									'orders'=>$order,
									'question'=> $this->input->post('question'),
									'image'=> '',
									'answer'=> $this->input->post('answer'),
									'link'=> $this->input->post('link'),
								    'created'=> date("Y-m-d H:i:s")
								);
											
					 			if(!$this->Common_model->addEditRecords('faq', $insert_data)) {
									$this->session->set_flashdata('error', 'Some error occured! Please try again.');
								} else {
									$this->session->set_flashdata('success', 'Faq Added Successfully.');
									redirect('admin/faq/list');
								}

				} else {
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
						
							$insert_data = array(
									'orders'=>$order,
									'question'=> $this->input->post('question'),
									'image'=> $responce['image_path'],
									'answer'=> $this->input->post('answer'),
									'link'=> $this->input->post('link'),
								    'created'=> date("Y-m-d H:i:s")
								);
											
					 			if(!$this->Common_model->addEditRecords('faq', $insert_data)) {
									$this->session->set_flashdata('error', 'Some error occured! Please try again.');
								} else {
									$this->session->set_flashdata('success', 'Page Added Successfully.');
									redirect('admin/faq/list');
								}
						}
					} else {
						$this->session->set_flashdata('error', "Invalid image, Please try again.");
					}
				}
 
							
		 	
			}
		}
		
		$data['from_action']=site_url('admin/faq/add');
		$data['back_action']=site_url('admin/faq/list');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/add_faq');
		$this->load->view('admin/include/footer');
	}	

	public function edit_faq($id) 
	{	$this->access('13','edit');
		$this->Common_model->check_login();
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Edit Faq | ".SITE_TITLE;
		$data['page_title']="Edit Faq";
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
			'title' => 'Faq List',
			'link' => site_url('admin/faq/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Faq',
			'link' => ""
		);	
		
		if(!$data['pages']=$this->Common_model->getRecords('faq','*',array('faq_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}

		if($this->input->post()) {
			$this->form_validation->set_rules('question', 'Question', 'trim|required',array('required'=>'Please enter %s'));
			$this->form_validation->set_rules('answer', 'answer', 'trim|required',array('required'=>'Please enter %s'));
			$this->form_validation->set_error_delimiters('<p class="inputerror">', '</p>');

			if($this->form_validation->run()==TRUE) 
			{

				if($_FILES['image']['name'] =='') { 
				 
					$image_path = $data['pages']['image'];
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
					'question'=> $this->input->post('question'),
					'answer'=> $this->input->post('answer'),
					'link'=> $this->input->post('link'),
					'image'=> $image_path,
					'modified'=> date("Y-m-d H:i:s")
				);
				//echo "<pre>"; print_r($id); exit;	
		 		if(!$this->Common_model->addEditRecords('faq', $update_data,array('faq_id'=>$id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->session->set_flashdata('success', 'Faq Updated Successfully.');
					redirect('admin/faq/list');
				}
			}
		}

		$data['from_action']=site_url('admin/faq/edit/'.$id);
		$data['back_action']=site_url('admin/faq/list');

		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/edit_faq');
		$this->load->view('admin/include/footer');

	}
	//end of faq pages	

}