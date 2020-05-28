<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Jobs extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin/Common_model');
		$this->load->model('admin/Other_model');
		$this->load->model('App_model');
		$this->load->model('admin/Admin_model');
		$this->load->helper('Common_helper'); 
		
	}
	public function index()
	{
		echo "hello";die;
	}
	public function list()
	{
        
		$this->Common_model->check_login();
		$this->access('9','view');
		$data['title']= 'Jobs'." | ".SITE_TITLE;
		$data['page_title']='Jobs';
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
			'title' => 'Jobs',
			'link' => ""
		);
		$where2 =''; 
		$admin_id = $this->session->userdata('admin_id');
		$doctor_id = $this->session->userdata('doctor_id');
        $user_type = $this->session->userdata('user_type');

       
		$data['records_result']=$this->Common_model->getRecords('jobs', '*','',"user_id Desc", false,'',$where2);
 		// echo $this->db->last_query();die;
		$index=0;
	 
	 
		
		$data['add_action']=site_url('admin/jobs/add');
		$data['edit_action']=site_url('admin/jobs/edit');
		$data['view_action']=site_url('admin/jobs/view');

		$admin_id = $this->session->userdata('admin_id');
		$data['add']=site_url('admin/jobs/edit');
		$data['view']=site_url('admin/jobs/edit');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/jobs/list');	
		$this->load->view('admin/include/footer');
	}
	public function add() 
	{
		$this->Common_model->check_login();
		$data['title']="Add Job | ".SITE_TITLE;
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
			'title' => 'Jobs List',
			'link' => site_url('admin/jobs/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Jobs',
			'link' => ""
		);	

		if($this->input->post()) {
						$insert_data = array( 
		                  	'title' => $this->input->post('title'),
		                  	'short_desc'=>  $this->input->post('short_desc'),
							'long_desc' => $this->input->post('long_desc'),
							'address' => $this->input->post('address'),
							'country_id' => $this->input->post('country'),
							'state_id' => $this->input->post('state'),
							'city_id' => $this->input->post('city'),
							'start_date'=> $this->input->post('start_date'),
							'end_date' => $this->input->post('end_date'),
						);
					 //echo "<pre>"; print_r($insert_data);exit;
			 		if(!$id=$this->Common_model->addEditRecords('jobs', $insert_data)) {
						$this->session->set_flashdata('error', 'Some error occured! Please try again.');
					} else {
							$this->session->set_flashdata('success', 'Job Added Successfully.');
								redirect('admin/jobs/list'); 
					}
				} 
			$data['countries']=$this->Common_model->getDropdownList('countries','id','name','Country');
			$data['from_action']=site_url('admin/jobs/add');
			$data['back_action']=site_url('admin/jobs/list');

			$this->load->view('admin/include/header',$data);
			$this->load->view('admin/include/sidebar');
			$this->load->view('admin/jobs/add');
			$this->load->view('admin/include/footer');		
		}
	public function edit($id) 
	{
		$this->Common_model->check_login();
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Edit Job | ".SITE_TITLE;
		$data['page_title']="Edit Job";
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
			'title' => 'Jobs list',
			'link' => site_url('admin/jobs/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Job',
			'link' => ""
		);	
		if(!$data['user']=$this->Common_model->getRecords('jobs','*',array('user_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}
		$data['countries']=$this->Common_model->getDropdownList('countries','id','name','Country');
        if($this->input->post()) {
				//echo "<pre>";print_r($this->input->post()); exit;
			
			
				$update_data = array( 
		                  	'title' => $this->input->post('title'),
		                  	'short_desc'=>  $this->input->post('short_desc'),
							'long_desc' => $this->input->post('long_desc'),
							'address' => $this->input->post('address'),
							'country_id' => $this->input->post('country'),
							'state_id' => $this->input->post('state'),
							'city_id' => $this->input->post('city'),
							'start_date'=> $this->input->post('start_date'),
							'end_date' => $this->input->post('end_date'),
						);
				if(!$this->Common_model->addEditRecords('jobs', $update_data,array('user_id'=>$id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->session->set_flashdata('success', 'Job updated successfully.');
						redirect('admin/jobs/list');
				}
			
		}
	
		$data['from_action']=site_url('admin/jobs/edit/'.$id);
		$data['back_action']=site_url('admin/jobs/list');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/jobs/edit');
		$this->load->view('admin/include/footer');

	}
	public function location_list()
	{
        
		$this->Common_model->check_login();
		$this->access('9','view');
		$data['title']= 'Job Location'." | ".SITE_TITLE;
		$data['page_title']='Job Locations';
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
			'title' => 'Job Locations',
			'link' => ""
		);
		$where2 =''; 
		$admin_id = $this->session->userdata('admin_id');
		$doctor_id = $this->session->userdata('doctor_id');
        $user_type = $this->session->userdata('user_type');

       
		$data['records_result']=$this->Common_model->getRecords('job_locations', '*','',"user_id Desc", false,'',$where2);
 		// echo $this->db->last_query();die;
		$index=0;
	 
	 
		
		$data['add_action']=site_url('admin/jobs/add_job_loaction');
		$data['edit_action']=site_url('admin/jobs/edit_job_location');
		$data['view_action']=site_url('admin/jobs/view');

		$admin_id = $this->session->userdata('admin_id');
		$data['add']=site_url('admin/jobs/add_job_loaction');
		$data['view']=site_url('admin/jobs/edit_job_location');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/jobs/list_location');	
		$this->load->view('admin/include/footer');
	}
	public function add_location() 
	{
		$this->Common_model->check_login();
		$data['title']="Add Location | ".SITE_TITLE;
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
			'title' => 'Job Location',
			'link' => site_url('admin/jobs/location_list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Add Location',
			'link' => ""
		);	

		if($this->input->post()) {
						$insert_data = array( 
		                  	'job_id' => $this->input->post('jobs'),
		                  	'address'=>  $this->input->post('address'),
							'lat' => $this->input->post('lat'),
							'long' => $this->input->post('long'),
						);
					 //echo "<pre>"; print_r($insert_data);exit;
			 		if(!$id=$this->Common_model->addEditRecords('job_locations', $insert_data)) {
						$this->session->set_flashdata('error', 'Some error occured! Please try again.');
					} else {
							$this->session->set_flashdata('success', 'Job Location Added Successfully.');
								redirect('admin/jobs/location_list'); 
					}
				} 
			$data['jobs']=$this->Common_model->select_column('jobs','user_id,title');
			$data['from_action']=site_url('admin/jobs/add_location');
			$data['back_action']=site_url('admin/jobs/list_location');

			$this->load->view('admin/include/header',$data);
			$this->load->view('admin/include/sidebar');
			$this->load->view('admin/jobs/add_job_location');
			$this->load->view('admin/include/footer');		
	}
	public function edit_job_location($id){
		$this->Common_model->check_login();
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Edit Job | ".SITE_TITLE;
		$data['page_title']="Edit Job Location";
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
			'title' => 'Location list',
			'link' => site_url('admin/jobs/location_list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'Edit Job Location',
			'link' => ""
		);	
		if(!$data['user']=$this->Common_model->getRecords('job_locations','*',array('user_id'=>$id),'',true)) {
			redirect('pages/page_not_found');
		}
		$data['jobs']=$this->Common_model->select_column('jobs','user_id,title');

        if($this->input->post()) {
				//echo "<pre>";print_r($this->input->post()); exit;
			
			
				$update_data = array( 
		                  	'job_id' => $this->input->post('jobs'),
		                  	'address'=>  $this->input->post('address'),
							'lat' => $this->input->post('lat'),
							'long' => $this->input->post('long'),
						);
				if(!$this->Common_model->addEditRecords('job_locations', $update_data,array('user_id'=>$id))) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->session->set_flashdata('success', 'Job location updated successfully.');
						redirect('admin/jobs/location_list');
				}
			
		}
	
		$data['from_action']=site_url('admin/jobs/edit_job_location/'.$id);
		$data['back_action']=site_url('admin/jobs/list_location');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/jobs/edit_job_location');
		$this->load->view('admin/include/footer');
	}
	public function access($section_id,$type)
	{
		$admin_id = $this->session->userdata('admin_id');
		
	}
}	