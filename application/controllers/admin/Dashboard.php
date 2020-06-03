<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->model('dashboard_model');
		  $this->load->model('App_model');
        $this->load->model('admin/Common_model');
        $this->load->model('admin/Admin_model');

	}

	public function index()
	{
		// /echo $this->session->userdata('admin_id');die;
		$admin_id = 1;
		$where2=''; 

		$data['title']="Dashboard | ".SITE_TITLE;
		// if($this->session->userdata('admin_id')== FALSE)
		// {
		// 	redirect(base_url()."admin/login/");
		// }

		$doctor_id = $this->session->userdata('doctor_id');
        $user_type = $this->session->userdata('user_type');

        if($user_type=='Super Admin'){
        	
        		$where =array('is_deleted'=>0,'user_type'=>'patient');	
        	
        }else{
        	if(empty($doctor_id)){
        	 
	        		$where =array('created_by'=>$admin_id,'is_deleted'=>0,'user_type'=>'patient');	
	        	
        	}else{ 
        		
	        		$where =array('is_deleted'=>0,'user_type'=>'patient');	
	        		$where2 =array('user_id'=>$doctor_id,'dr_id'=>0);	
	        
        	}
        } 

        // $data['all_patient'] = $this->Common_model->getNumRecords('users','user_id',array('user_type'=>'patient'));
		$patient=$this->Common_model->getRecords('users', '*',$where,"user_id Desc", false,'',$where2);
		if(!empty($patient)){
			$data['all_patient'] = count($patient);
		}else{
			$data['all_patient'] = '0';
		}

		 if($user_type=='Super Admin'){
	        	$where =array('is_deleted'=>0,'user_type'=>'doctor');	
        }else{
        	if(empty($doctor_id)){ 
	        	$where =array('created_by'=>$admin_id,'is_deleted'=>0,'user_type'=>'doctor');	
	        	 
        	}else{ 
        		 
	        	$where =array('user_id'=>$doctor_id,'is_deleted'=>0,'user_type'=>'doctor');	
	        	 
        	}
        } 
        
		$all_doctor=$this->Common_model->getRecords('users', '*',$where,"user_id Desc", false,'',$where2);
		if(!empty($all_doctor)){
			$data['all_doctor'] =count($all_doctor);
		}else{
			$data['all_doctor'] = '0';
		}
		// $data['all_doctor'] = $this->Common_model->getNumRecords('users','user_id',array('user_type'=>'doctor'));
		// $data['all_patient'] = $this->Common_model->getNumRecords('users','user_id',array('user_type'=>'patient'));


		$data['total_media'] = $this->Common_model->getNumRecords('media','id');
		$data['all_like'] = $this->Common_model->getNumRecords('media_like','id');
	 
		$data['sitetitle']="Dashboard";
		
		$this->load->view('admin/include/header',$data);
		
		$this->load->view('admin/include/sidebar');
		
		$this->load->view('admin/dashboard');
		
		$this->load->view('admin/include/footer');
	}



	public function search_record()
	{

		$start_date_n = $this->input->post('start_date');
		$end_date_n = $this->input->post('end_date');
        $start    = new DateTime($start_date_n);
        $start->modify('first day of this month');
        $end      = new DateTime($end_date_n);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        $month='';
        $index=0;
 
			 
		foreach ($period as $dt) {

			$month[$index] = $dt->format("F"); 
			$start_date = $dt->format("Y-m-d H:i:s") .",";
			$last_date =  date("Y-m-t H:i:s", strtotime($dt->format("Y-m-d H:i:s")));
   		 	$get_record = $this->Admin_model->getrecordlike($start_date,$last_date);
			$data['record'][$index] = $get_record;  
			if($get_record!=0)
			{
				$arr = array('label'=>$month[$index],'y'=>$get_record);
				$data['all_record'][$index] = $arr;
	 			$index++; 
			}
	    }  
	 	if($index <=1)
		{
			$month[0] = '('.$start_date_n.'  '.$end_date_n.')'; 
			$get_record = $this->Admin_model->getrecordlike($start_date_n,$end_date_n);
			$data['record'][0] = $get_record;  
			/*  $sql = $this->db->last_query();   
			  $get_record;*/
			if($get_record!=0)
			{
				$arr = array('label'=>$month[0],'y'=>$get_record);
				$data['all_record'][0] = $arr;	 
			}else
			{
					$data['all_record'][0] = '';	 
			}

		}  
				 
      	if(empty($data['all_record']))
      	{
      		$data['all_record']='';
      	}

        $arr= array('record'=>$data['all_record']);
        echo json_encode($arr);
		 
	}

	public function search_offers()
	{

			$start_date_n = $this->input->post('start_date');
			$end_date_n = $this->input->post('end_date');
            $start    = new DateTime($start_date_n);
            $start->modify('first day of this month');
            $end      = new DateTime($end_date_n);
            $end->modify('first day of next month');
            $interval = DateInterval::createFromDateString('1 month');
            $period   = new DatePeriod($start, $interval, $end);
            $month='';
            $index=0;

        		foreach ($period as $dt) {

					$month[$index] = $dt->format("F"); 
					$start_date = $dt->format("Y-m-d H:i:s") .",";
					$last_date =  date("Y-m-t H:i:s", strtotime($dt->format("Y-m-d H:i:s")));
	       		 	$get_record = $this->Admin_model->getrecordlike_offer($start_date,$last_date);
					$data['record'][$index] = $get_record;  
					if($get_record!=0)
					{
						$arr = array('label'=>$month[$index],'y'=>$get_record);
						$data['all_record'][$index] = $arr;
			 			$index++; 
					}

				
	            }  
			 	if($index <=1)
				{
					$month[0] = '('.$start_date_n.'  '.$end_date_n.')'; 
					$get_record = $this->Admin_model->getrecordlike_offer($start_date_n,$end_date_n);
					$data['record'][0] = $get_record;     
					if($get_record!=0)
					{
						$arr = array('label'=>$month[0],'y'=>$get_record);
						$data['all_record'][0] = $arr;	 
					}else
					{
							$data['all_record'][0] = '';	 
					}
	 
				}  
          	if(empty($data['all_record']))
          	{
          		$data['all_record']='';
          	}
 
            $arr= array('record'=>$data['all_record']);
            echo json_encode($arr);
		 
	}

}

