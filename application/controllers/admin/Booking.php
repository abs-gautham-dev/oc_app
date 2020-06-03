<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Booking extends CI_Controller {

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
		//echo "hello";die;
	}
	public function list()
	{
        
		$this->Common_model->check_login();
		$this->access('9','view');
		$data['title']= 'Booking'." | ".SITE_TITLE;
		$data['page_title']='Bookings';
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
			'title' => 'Bookings',
			'link' => ""
		);
		$where2 =''; 
		$admin_id = $this->session->userdata('admin_id');
		$doctor_id = $this->session->userdata('doctor_id');
        $user_type = $this->session->userdata('user_type');

       
		$data['records_result']=$this->Common_model->booking_join();
 		// echo $this->db->last_query();die;
		$index=0;
	 
	 
		
		$data['add_action']=site_url('admin/booking/add');
		$data['edit_action']=site_url('admin/booking/invoice');
		$data['view_action']=site_url('admin/booking/view');

		$admin_id = $this->session->userdata('admin_id');
		$data['add']=site_url('admin/booking/add');
		$data['view']=site_url('admin/booking/invoice');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/booking/list');	
		$this->load->view('admin/include/footer');
	}

	public function invoice($id) 
	{
		$this->Common_model->check_login();
		if(!$id) {
			redirect('pages/page_not_found');
		}
		$data['title']="Edit Job | ".SITE_TITLE;
		$data['page_title']="Invoice";
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
			'title' => 'Invoice',
			'link' => site_url('admin/jobs/list')
		);
		$data['breadcrumbs'][] = array(
			'icon'=>'',
			'class'=>'active',
			'title' => 'booking',
			'link' => ""
		);	
        if($this->input->post()) {
				//echo "<pre>";print_r($this->input->post()); exit;
			
			
				$update_data = array( 
		                  	'request_id' => $this->input->post('request_id'),
		                  	'vat'=>  $this->input->post('vat'),
							'amount' => $this->input->post('amount'),
							'total' => $this->input->post('vat')+$this->input->post('amount'),
							'created' => date("Y-m-d H:i:s"),
							'status'=> 'Pending',
						);
				if(!$this->Common_model->addEditRecords('booking_invoice', $update_data)) {
					$this->session->set_flashdata('error', 'Some error occured! Please try again.');
				} else {
					$this->Common_model->addEditRecords('booking_request', array('status'=>'Invoice_created'),array('id'=>$this->input->post('request_id')));
					$this->session->set_flashdata('success', 'Invoice creates successfully for '.$this->input->post('request_id'));
						redirect('admin/booking/list');
				}
			
		}
	
		$data['from_action']=site_url('admin/booking/invoice/'.$id);
		$data['back_action']=site_url('admin/booking/list');
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/include/sidebar');
		$this->load->view('admin/booking/invoice');
		$this->load->view('admin/include/footer');

	}
	
	public function access($section_id,$type)
	{
		$admin_id = $this->session->userdata('admin_id');
		
	}
}	