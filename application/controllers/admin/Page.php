<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin/Common_model');
		$this->load->helper('Common_helper');
		$this->load->model('admin/Admin_model');
		$this->load->model('admin/Other_model');
		//$this->load->library('Ajax_pagination');

	}

	public function access($section_id,$type)
	{
		$admin_id = $this->session->userdata('admin_id');
		
	}

	public function index()	{
		 
		$this->Common_model->check_login();
		$this->access('17','view');
		$data['title']="Page| ".SITE_TITLE;
		$data['page_title']="Page";
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
			'title' => 'Page',
			'link' => ""
		);
		
		$country=$this->input->post('country');
		$state=$this->input->post('state');
		$city=$this->input->post('city');
		$recordss=$this->Admin_model->get_pagelist($country,$state,$city);
		$data['user_lists'] = $this->Common_model->getRecords('users', 'user_id,username',array('status'=>'Active'),"", false);

		
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
			
	 	
		$index++;
		}
		$admin_id = $this->session->userdata('admin_id');
		
		$data['edit_action']=site_url('admin/Page/');


		$data['used_country'] = $this->Admin_model->get_used_country(); 
		$data['used_state'] = $this->Admin_model->get_used_state(); 
		$data['used_city'] = $this->Admin_model->get_used_city(); 

		
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/page_info');	
		$this->load->view('admin/include/footer');
	}

	public function change_user()
	{
		$user_id=$this->input->post('user_id');
		$page_id=$this->input->post('page_id');

		$count_old_page = $this->Common_model->getRecords('business_page','business_page_id',array('user_id' =>$user_id),"", false);
		if(count($count_old_page) >9)
		{
					$err = array('data' =>array('status' => '0', 'msg' => '<div class="alert alert-danger">This User have 10 pages.</div>'));
					echo json_encode($err); exit; 
		}else
		{
			$this->Common_model->addEditRecords('business_page',array('user_id'=>$user_id),array('business_page_id'=>$page_id));
			$err = array('data' =>array('status' => '1', 'msg' => '<div class="alert alert-success"><strong>Success!</strong> User Updated.</div>'));
			echo json_encode($err); exit;  
		}
	}
	

} // class end