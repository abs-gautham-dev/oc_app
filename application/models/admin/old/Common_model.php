<?php 

class Common_model extends CI_Model 
{
	function __construct()
    {
         parent::__construct();
    }
	
	public function check_login()
	{
		if($this->session->userdata('admin_id')) {
			return true;
		} else {
			// redirect('admin/login');
				return true;
		}
	}
	public function paginate($url,$total_row)
	{
		$this->load->library('pagination');
			
		$config = array();
		$config["base_url"] = $url;
		$config["total_rows"] = $total_row;
		$config["per_page"] = ADMIN_LIMIT;
		// $config['use_page_numbers'] = TRUE;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = round($choice);
		$config["uri_segment"] = 4;
		// $config['num_links'] = 2;
		$config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='active'><a href='#'>";
		$config['cur_tag_close'] = "</a></li>";
		$config['next_tag_open'] = "<li class='next'>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li class='prev'>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}
	
	public function getDropdownList($table,$col1,$col2,$title="",$where="",$group="") 
	{

		$this->db->select($col1);
		$this->db->select($col2);
		if($where != "")
		{	
			$this->db->where($where);
		}
		if($group != "")
		{
		 $this->db->group_by($group); 
		}
		$this->db->order_by($col2,'asc');
		$query= $this->db->get($table);
		$query_result = $query->result_array();
		$return = array();
	    if( is_array( $query_result ) && count( $query_result ) > 0 )
	    {
	    	if($title !=""){
	    		$return[''] = 'Select '.ucfirst($title);
	    	}else{
	        	$return[''] = 'Select '.ucfirst($col1);
	        }
	        foreach($query_result as $row)
	        {
	            $return[$row[$col1]] = $row[$col2];
	        }
	    }
	    return $return;
	}
	
	public function getRecords($table, $fields="", $condition="", $orderby="", $single_row=false,$limit=-1) //$condition is array 
	{
		if($fields != "")
		{
			$this->db->select($fields);
		}
		 
		if($orderby != "")
		{
			$this->db->order_by($orderby); 
		}

		if($limit>-1)
		{
			$this->db->limit(ADMIN_LIMIT,$limit);
		}

		
		if($condition != "")
		{
			$rs = $this->db->get_where($table,$condition);
		}
		else
		{
			$rs = $this->db->get($table);
		}
		//echo $this->db->last_query(); exit;
		
		if($single_row)
		{  
			return $rs->row_array();
		}
		return $rs->result_array();

	}

	// this function returns table data.
	public function getFieldValue($table, $fields="", $condition="") //$condition is array 
	{
		if($fields != "")
		{
			$this->db->select($fields);
		}

		if($condition != "")
		{
			$rs = $this->db->get_where($table,$condition);
		}
		$result = $rs->row_array();
		return $result[$fields];

	}

	
	public function addEditRecords($table_name, $data_array, $where='')
	{
		if($table_name && is_array($data_array))
		{
			$columns = $this->getTableFields($table_name);
			foreach($columns as $coloumn_data)
				$column_name[]=$coloumn_data['Field'];
					  
			foreach($data_array as $key=>$val)
			{
				if(in_array(trim($key),$column_name))
				{
					$data[$key] = $val;
				}
			 }

			if($where == "")
			{	
				$query = $this->db->insert_string($table_name, $data);
				$this->db->query($query);
				return  $this->db->insert_id();
			}
			else
			{
				$query = $this->db->update_string($table_name, $data, $where);
				$this->db->query($query);
				return  $this->db->affected_rows();
			}
			
		}			
	}

	function getNumRecords($table, $fields="", $condition="") 
	{
		if($fields != "")
		{
			$this->db->select($fields);
		}
		if($condition != "")
		{
			$rs = $this->db->get_where($table,$condition);
		}
		else
		{
			$rs = $this->db->get($table);
		}		
		return $rs->num_rows();
	}
	
	// function for deleting records by condition.
	function deleteRecords($table, $where)
	{ 
		$this->db->delete($table, $where);
		return $this->db->affected_rows();
	}

	// this function is used to get all the fields of a table.
	function getTableFields($table_name)
	{
		$query = "SHOW COLUMNS FROM $table_name";
		$rs = $this->db->query($query);
		return $rs->result_array();
	}

	// This function is used to set up mail configuration..
	function setMailConfig()
	{
		$this->load->library('email');
		$config['smtp_host'] = SMTP_HOST;
		$config['smtp_user'] = SMTP_USER;
		$config['smtp_pass'] = SMTP_PASS;
		$config['smtp_port'] = SMTP_PORT;
		$config['protocol'] = PROTOCOL;
		$config['mailpath'] = MAILPATH;
		$config['mailtype'] = MAILTYPE;
		$config['charset'] = CHARSET;
		$config['wordwrap'] = WORD_WRAP;
		$config['smtp_timeout'] = 300;
		$config['newline'] = "\r\n";
		$this->email->set_crlf( "\r\n" );

		$this->email->initialize($config);
	}

	function sendEmail($to_email,$subject,$body,$from_email)
	{
		$headers  = 'MIME-Version: 1.0' . "\r\n";
	 	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	 	$this->email->set_newline("\r\n");
		$this->email->from($from_email); 
		$this->email->to($to_email);
		$this->email->subject($subject); 
		$this->email->message($body); 
		return $this->email->send();
	}
	

	function defaultEmailSend($to_email,$subject,$body,$from_email)
	{
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: ".$from_email."\r\n";
		if(mail($to_email,$subject,$body,$headers)){
			return true;
		} else {
			return false;
		}
	}

	public function remember_me()
    {
        $result = $this->common_model->getRecords('admin','*',array('admin_id ' => $_COOKIE['remember_me']),'',true);
        $this->session->set_userdata('admin_auth',$result);
    }

    public function groupByRecords($table_name='' , $field_name = '', $id='')
    {
        $this->db->select("$field_name,count(*) as total");
        $this->db->group_by($field_name);
        $this->db->having($field_name,$id);
        $query = $this->db->get($table_name);
        return $query->row_array();
    }

    public function groupBytotal($table_name='' , $name = '', $field_name = '', $id='')
    {
        $this->db->select("count($name) as total");
        $this->db->where('is_deleted','0');
        $this->db->where($field_name,$id);
        $query = $this->db->get($table_name);
        return $query->row_array();
    }
   



   

    public function getmediaRecords()
    {
        $this->db->select('(select count(id) as total_like from media_like where media_id=m.id) as total_like,m.*,u.full_name');
        $this->db->from('media m');
        $this->db->join('users u','u.user_id = m.user_id'); 
        $this->db->order_by('m.id','Desc'); 
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getdrdetailsRecords($doctor_id)
    {
        $this->db->select('u.full_name,u.profile_pic,u.email,u.mobile,u.about,c.name as category_name,con.name as country_name,states.name as state_name,cities.name as city_name');
        $this->db->from('users u');
        $this->db->join('categories c','c.category_id = u.category_id','left'); 
        $this->db->join('countries con','con.id = u.country_id','left'); 
        $this->db->join('states','states.id = u.state_id','left'); 
        $this->db->join('cities','cities.id = u.city_id','left'); 
        $this->db->where('u.user_id',$doctor_id); 
        $query = $this->db->get();
        return $query->row_array();
    }
    public function getmedialist($doctor_id)
    {
        $this->db->select('media.*,(select count(id) as like_count from media_like where media_like.media_id = media.id) as like_count');
        $this->db->from('media');
        $this->db->where('media.user_id',$doctor_id); 
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getmediadetails($media_id)
    {
        $this->db->select('media.*,(select count(id) as like_count from media_like where media_like.media_id = media.id) as like_count');
        $this->db->from('media');
        $this->db->where('media.id',$media_id); 
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getRoomid($sender_id,$receiver_id,$type)
    {
        $this->db->select('room_id');
        $this->db->from('users_chat');
       /* $this->db->group_start();
        	$this->db->where('sender_id',$sender_id); 
        	$this->db->where('receiver_id',$receiver_id); 
        $this->db->group_end();
        $this->db->group_start();
        	$this->db->where('sender_id',$receiver_id); 
        	$this->db->where('receiver_id',$sender_id); 
        $this->db->group_end();*/
        $where = '(sender_id='.$sender_id.' and receiver_id='.$receiver_id.' ) OR (sender_id='.$receiver_id.' and receiver_id='.$sender_id.' )';
       	$this->db->where($where);
       	$this->db->where('type',$type);
        $query1 = $this->db->get();
        $record1 = $query1->row_array();
        
        if(!empty($record1))
        {
        	$id = $record1['room_id'];
        	return $id;exit;
        }else
        {
        	$this->db->select('room_id');
	        $this->db->from('users_chat');
	        $query2 = $this->db->get();
	        $record2 = $query2->row_array();
	        if(!empty($record2))
	        {
	        	$record2 = $record2['room_id'];
	        	return $record2+1;exit;
	        }else
	        {
	        	return 1;exit;
	        	
	        }
        }
    }    
    public function getchatlist($user_id)
    {
        $this->db->select("*");
        $this->db->from('users_chat');
        $where = '(sender_id='.$user_id.' or receiver_id='.$user_id.' )';
       	$this->db->where($where); 
        $this->db->order_by('id','Desc'); 
        $this->db->group_by('room_id'); 
        $query = $this->db->get();
        return $query->result_array();
         
    }

    function push_notification_send($device_id,$device_type,$alert_message) {  

        $iosarray = array(
            'alert' => $alert_message,
            'type'  => 'notification',
            'badge' =>  1,
            'sound' => 'default',
        );

        $andarray = array(
            'message'   =>$alert_message,
            'type'      =>'notification',
            'title'     => 'Notification',
        );
        if(!empty($log)){
            foreach ($log as $key) {
                if($device_type=='android'){
                    $referrer = androidNotification($device_id,$andarray);
                }

                if($device_type=='ios'){
                    $referrer = iosNotification($device_id,$iosarray);
                }
            }
        } 
       
         
    }
    public function getAdslist($limit,$page,$sub_cate='',$device_id="")
    {
        $this->db->select('*');
        $this->db->from('ads');
		if(!empty($sub_cate))
		{
        	// $this->db->like('title',$name);
		 $this->db->where('sub_category_id',$sub_cate);
		}
		if(!empty($device_id))
		{
        	// $this->db->like('title',$name);
		 $this->db->where('device_id',$device_id);
		}
        $this->db->where('status','Active');
        $this->db->where('approved','1');
         
		$this->db->limit($limit,$page);
		  
        $this->db->order_by('id','Desc'); 
        $query = $this->db->get();
        return $query->result_array();
    }

  public function getfavAdslist($limit,$page,$name='',$uniq_is)
    {
        $this->db->select('ads.*');
        $this->db->from('ads');
        $this->db->join('fav_ad fa','fa.ad_id = ads.id');
		// if(!empty($name))
		// {
  //       	$this->db->like('ads.title',$name);
		// }
        $this->db->where('ads.status','Active');
        $this->db->where('ads.approved','1');
        $this->db->where('fa.uniq_id',$uniq_is);
         
		$this->db->limit($limit,$page);
		  
        
        $query = $this->db->get();
        return $query->result_array();
    }


    public function business_review($page_id,$disply_rating)
    {
        $this->db->select('IFNULL(AVG(rp.rating), "0") as rating ,r.title,r.description,r.status,r.verified_rating,r.review_id,r.created,u.profile_pic,u.username');
        $this->db->from('review r');
        $this->db->join('users u','u.user_id = r.user_id');
        $this->db->join('rating_page rp','rp.review_id = r.review_id');
        $this->db->where('r.page_id',$page_id);
        $this->db->where('r.status','Active');
        if(!empty($disply_rating)){
        $this->db->where('r.verified_rating',$disply_rating);}
        $this->db->group_by('r.review_id'); 
         $this->db->order_by('r.review_id','Desc'); 
        $query = $this->db->get();
        return $query->result_array();
    }

    public function business_review1($page_id,$user_id)
    {
        $this->db->select('r.title,r.description');
        $this->db->from('review r');
        $this->db->join('users u','u.user_id = r.user_id');
        $this->db->join('rating_page rp','rp.review_id = r.review_id');
        $this->db->where('r.page_id',$page_id);
        $this->db->where('r.user_id',$user_id);
        if(!empty($disply_rating)){
        $this->db->where('r.verified_rating',$disply_rating);}
        $this->db->group_by('r.review_id'); 
       

        $query = $this->db->get();
        return $query->row();
    }
    


    public function get_membership_list($membership_id='',$gropu_by='')
    {
    	$this->db->select('subscription_user.*,users.email,users.username,subscription_plan.stripe_plan_id,subscription_plan.amount,subscription_plan.month,business_page.business_name,business_page.business_full_name');
	        $this->db->from('subscription_user');
	        $this->db->join('users','users.user_id = subscription_user.user_id','left');
	        $this->db->join('subscription_plan','subscription_plan.subscription_plan_id = subscription_user.subscription_plan_id','left');
	        $this->db->join('business_page','business_page.business_page_id = subscription_user.page_id','left');
    	if($membership_id!='')
    	{
		  	$this->db->where('subscription_user.subscription_user_id',$membership_id);
	    } 
	    if($gropu_by!='')
	    {
    	 	$this->db->group_by('subscription_user.user_id'); 
	    }   
       	$query = $this->db->get();
        return $query->result_array();
    }
 	public function get_membership_list_user_id($user_id)
    {
    	$this->db->select('subscription_user.*,users.email,users.username,subscription_plan.stripe_plan_id,subscription_plan.amount,subscription_plan.month,business_page.business_name');
	        $this->db->from('subscription_user');
	        $this->db->join('users','users.user_id = subscription_user.user_id','left');
	        $this->db->join('subscription_plan','subscription_plan.subscription_plan_id = subscription_user.subscription_plan_id','left');
	        $this->db->join('business_page','business_page.business_page_id = subscription_user.page_id','left');
    	
		  	$this->db->where('subscription_user.user_id',$user_id);
	   
	    
       	$query = $this->db->get();
        return $query->result_array();
    }

 	public function get_membership_list_with_search($membership_id='',$start_date,$end_date)
    {
    	$this->db->select('subscription_user.*,users.email,users.username,subscription_plan.stripe_plan_id,subscription_plan.amount,subscription_plan.month,business_page.business_name');
        $this->db->from('subscription_user');
        $this->db->join('users','users.user_id = subscription_user.user_id','left');
        $this->db->join('subscription_plan','subscription_plan.subscription_plan_id = subscription_user.subscription_plan_id','left');
        $this->db->join('business_page','business_page.business_page_id = subscription_user.page_id','left');
    	 
  		$this->db->where('subscription_user.created >=',$start_date);
  		$this->db->where('subscription_user.created <=',$end_date);
	     
       	$query = $this->db->get();
        return $query->result_array();
    }

    public function getRecordsimg($table, $fields="", $condition="", $orderby="", $single_row=false,$limit=-1) //$condition is array 
	{
		if($fields != "")
		{
			$this->db->select($fields);
		}
		 
		if($orderby != "")
		{
			$this->db->order_by($orderby); 
		}

		if($limit>-1)
		{
			$this->db->limit(5,$limit);
		}

		
		if($condition != "")
		{
			$rs = $this->db->get_where($table,$condition);
		}
		else
		{
			$rs = $this->db->get($table);
		}
		//echo $this->db->last_query(); exit;
		
		if($single_row)
		{  
			return $rs->row_array();
		}
		return $rs->result_array();

	}

	public function getPermissions($admin_id) {
    	$this->db->select('i.*,s.name');
        $this->db->from('irg_user_access i');
        $this->db->join('irg_sections s','s.id = i.section_id','left');
  		$this->db->where('i.admin_id',$admin_id);
       	$query = $this->db->get();
        return $query->result_array();
    }

    public function getPermissionsadd() {
    	$this->db->select('i.*');
        $this->db->from('irg_sections i');
       	$query = $this->db->get();
        return $query->result_array();
    }
    

 	public function analytics($page_id,$time_from='',$time_end='')
    {
        $this->db->select('redeem_offers.*,s.offers_title');
        $this->db->from('redeem_offers');
        $this->db->join('business_offers s','s.business_offers = redeem_offers.offer_id');
        $this->db->where('time >=', $time_from);
        $this->db->where('time <=', $time_end);
        $this->db->where('page_id',$page_id);
        $query = $this->db->get(); 
        return $query->result_array(); 
    }
 
	public function google_busniess_details($place_id)
	{
     	$url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid='.$place_id.'&key='.GOOGLE_PLACE_KEY;
       	$curl = curl_init();

        curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_SSL_VERIFYPEER=>false,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_FRESH_CONNECT=>true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array("cache-control: no-cache")
        ));
        $json = curl_exec($curl);
        // echo $json;exit;
        $err = curl_error($curl);

        curl_close($curl);
    	$obj = json_decode($json); 
    	// echo "<pre>";print_r($obj->result);
		if(!empty($obj->result))
		{
			if(isset($obj->result->name))
		  	{ 
				$arr['bussiness_full_name'] = $obj->result->name;
				//$arr['bussiness_name'] = str_replace(' ', '',$obj->result->name);
				$arr['bussiness_name'] = $obj->result->name;
			}else
			{
				$arr['bussiness_full_name'] = '';
				$arr['bussiness_name'] ='';
			}
			if(isset($obj->result->international_phone_number))
		  	{ 
				$arr['mobile'] = $obj->result->international_phone_number;
			}else
			{
				$arr['mobile'] ='';
			}
			if(isset($obj->result->website))
		  	{ 
				$arr['website'] = $obj->result->website;
			}else
			{
				$arr['website'] ='';
			}
			if(isset( $obj->result->opening_hours->open_now))
		  	{ 
				$arr['status'] = (string)$obj->result->opening_hours->open_now;
			}else
			{
				$arr['status'] ='';
			}

			if(isset($obj->result->opening_hours->weekday_text))
		  	{ 	
		  		$wek=0;
				//$arr['weekday_text'] =$obj->result->opening_hours->weekday_text;
				// foreach($obj->result->opening_hours->weekday_text as $weekday) {
				// 	//echo $weekday;exit;
				// 	$arr['weekday_text'][$wek] = str_replace(" – "," - ",$weekday);
				// 	$wek++;
				// }


				foreach($obj->result->opening_hours->weekday_text as $weekday) {
				 	$day_time = str_replace(" – "," - ",$weekday);

			 		if(isset(explode(', ', $day_time)[1]))
			 		{
			 			$last_time = explode(', ', $day_time)[1];
			 			if(strpos($last_time, 'PM'))
			 			{
			 				$ec = explode('-', $last_time)[0];
		 					$add_formate = $ec.'PM'.' -'.explode('-', $last_time)[1];
		 					$arr['weekday_text'][$wek] = explode(', ', $day_time)[0].', '.$add_formate;

			 			}elseif(strpos($last_time, 'AM'))
			 			{
			 				$ec = explode('-', $last_time)[0];
		 					$add_formate = $ec.'AM'.' -'.explode('-', $last_time)[1];
		 					$arr['weekday_text'][$wek] = explode(', ', $day_time)[0].', '.$add_formate;
			 			} 
			 		}else
			 		{
			 			$arr['weekday_text'][$wek] = str_replace(" – "," - ",$weekday);
			 		}

					
					$wek++;
				}
				// echo "<pre>";print_r(str_replace(" – "," - ",$weekday));
				// echo "<pre>";print_r($arr['weekday_text']);die;
			}else
			{
				$arr['weekday_text'] ='';
			}
			if(isset($obj->result->opening_hours->open_now))
		  	{ 
		  		$day = date("N")-1;
		  		$day_array = explode(': ',$arr['weekday_text'][$day]);
				$arr['today'] ="Today ".trim($day_array[1]);
			}else
			{
				$arr['today'] ='Closed';
			}
			if(isset( $obj->result->place_id))
		  	{ 
				$arr['place_id'] = $obj->result->place_id;
			}else
			{
				$arr['place_id'] ='';
			}
			if(isset( $obj->result->place_id))
		  	{ 
				$arr['place_id'] = $obj->result->place_id;
			}else
			{
				$arr['place_id'] ='';
			}
			if(isset( $obj->result->rating))
		  	{ 
				$arr['rating'] = (string)$obj->result->rating;
			}else
			{
				$arr['rating'] ='';
			}
			if(isset($obj->result->address_components[0]->long_name))
		  	{ 
				$arr['area'] = $obj->result->address_components[1]->long_name;
			}else
			{
				$arr['area'] ='';
			}
			 
		    $arr['from_price']= '';
		   
		    if(isset($obj->result->price_level)) {
		      	$arr['to_price']= $obj->result->price_level;
		    } else {
		      	$arr['to_price']= '';
		    } 
			if(isset( $obj->result->address_components[2]->long_name))
		  	{ 
				$arr['city_name'] =  $obj->result->address_components[2]->long_name;
			}else
			{
				$arr['city_name'] ='';
			}
			if(isset( $obj->result->address_components[3]->long_name))
		  	{ 
				$arr['state_name'] =  $obj->result->address_components[3]->long_name;
			}else
			{
				$arr['state_name'] ='';
			}
			if(isset( $obj->result->address_components[4]->long_name))
		  	{ 
				$arr['country_name'] =  $obj->result->address_components[4]->long_name;
			}else
			{
				$arr['country_name'] ='';
			}	
			if(isset( $obj->result->address_components[5]->long_name))
		  	{ 
				$arr['zipcode'] =  $obj->result->address_components[5]->long_name;
			}else
			{
				$arr['zipcode'] ='';
			} 
			 
		 	if(isset($obj->result->geometry->location->lat))
		  	{ 
				$arr['latitude'] = (string)$obj->result->geometry->location->lat;
			}else
			{
				$arr['latitude'] ='';
			}
			if(isset($obj->result->geometry->location->lng))
		  	{ 
				$arr['longitude'] = (string)$obj->result->geometry->location->lng;
			}else
			{
				$arr['longitude'] ='';
			}
			if(isset( $obj->result->types))
		  	{ 
		  		$arr['categories'] = $obj->result->types;
				$arr['category'] =  str_replace('_',' ',implode(' , ',$obj->result->types));
			} else {
				$arr['category'] ='';
			}	

			$arr['review_list'] = array();
			if(isset( $obj->result->reviews)) { 
		  		foreach($obj->result->reviews as $review) {
		  			$review->time = date('Y-m-d H:i:s',$review->time);
		  			$review->from_google = '1';
		  			$review->rating =(string)$review->rating;
		  			$arr['review_list'][]= (array)$review;
		  			
		  		}
		  		if(!empty($arr['review_list']))
		  		{ 
		  		 $arr['review_list']= multid_sort($arr['review_list'],'time','Desc');
		  		}
				$arr['review'] = (string)count($obj->result->reviews);
				
				if($reviews = $this->getGoogleRating($arr['place_id'])) {

					$greview=1;
					$local_review =array();
					foreach($reviews as $record) {
						$greview=$greview+1;
						$arr['rating'] = $arr['rating'] + $record['rating'];

						$review_content = array(
							'author_name'=> $record['full_name'],
							'profile_photo_url'=> base_url().$record['profile_pic'],
							'rating' =>  (string)$record['rating'],
							'text' =>  $record['description'],
							'title' =>  '',
							'time' => date("Y-m-d H:i:s", strtotime($record['modified'])),
							'from_google' =>  '0',
						);
						$local_review[]=$review_content;
						
					}
					$arr['review_list'] = array_merge($arr['review_list'],$local_review);
					$arr['rating'] =  (string)$arr['rating']/$greview;
					$arr['review'] = $arr['review']+ $greview;
					//echo "<pre>";print_r($aray);die;
					if(!empty($arr['review_list']))
					{
						$data = multid_sort($arr['review_list'], 'time','Desc');
						$arr['review_list'] = $data;
						//echo "<pre>";print_r($data);die;
					}
				 	
				}
			} else {
				$arr['review_list'] =array();
				$arr['review'] =  "0";
			} 

			if(isset( $obj->result->formatted_address)) { 
				$arr['address'] = $obj->result->formatted_address;
			} else {
				$arr['address'] ='';
			}
			if(isset( $obj->result->website)) { 
				$arr['website'] = $obj->result->website;
			} else {
				$arr['website'] ='';
			}
			if(isset( $obj->result->url)) { 
				$arr['url'] = $obj->result->url;
			} else {
				$arr['url'] ='';
			}

			$arr['is_google'] = "1";
			$arr['pageMessageBadgeCount'] = "0";
		  	if(isset( $obj->result->photos))
		  	{ 
			 	$photos_arr = $obj->result->photos;
			 	$index=0;
			 	$images=array();
			 	if(!empty($photos_arr)) {
					foreach ($photos_arr as $p_arr) {
						
					 	$image_get = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference='.$p_arr->photo_reference.'&key='.GOOGLE_PLACE_KEY.'';
						$images[$index]= $image_get;
						if($index==0) {
							$arr['business_image'] = $image_get; 
						}
						$index++;
					}
				}
				$arr['images'] =$images;
			} else {
				$arr['images'] =array();
			}
			return $arr;
		}
	}

   public function getGoogleRating($page_id) { 
        $this->db->select('g.*,u.username,u.full_name,u.profile_pic');
        $this->db->from('google_page_rating g');
        $this->db->join('users u','u.user_id = g.user_id');
        $this->db->where('g.page_id',$page_id);
        $query = $this->db->get();
        return $query->result_array();
    }
		
	function getPlaceName($latitude, $longitude)
	{
		   //This below statement is used to send the data to google maps api and get the place

		 $geocode_stats = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false&key='.GOOGLE_API_KEY); 
		$result='';
		$output_deals = json_decode($geocode_stats); 
		if($output_deals->status=='OK'){
		  $address_components=$output_deals->results[0]->address_components;


		  for($i=0;$i<count($address_components);++$i){
		    if(array("locality", "political")==$address_components[$i]->types){
		      $result=$address_components[$i]->short_name;
		      break;
		    }
		  }
		//  echo "<pre>";print_r($result);die;
		} else{
		  $result=$output_deals->status;
		}
		return $result; 
	} 

	public function google_busniess_list($keyword,$lat,$long,$places='')
 	{
 		// $city_name = $this->getPlaceName($lat,$long);
 		if(empty($places))
 		{  
		    // $url='https://maps.googleapis.com/maps/api/place/nearbysearch/json?query='.urlencode($keyword).'&location='.$lat.','.$long.'&radius=5000&key='.GOOGLE_PLACE_KEY;

		     $url='https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$lat.','.$long.'&radius=500000&keyword='.urlencode($keyword).'&key='.GOOGLE_PLACE_KEY; 


 		}else
 		{
 			if($places=='places_near_by')
 			{	
				$url='https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$lat.','.$long.'&radius=5000&key='.GOOGLE_PLACE_KEY; 
 			}else
 			{
				 $url='https://maps.googleapis.com/maps/api/place/textsearch/json?query='.urlencode($keyword).'&key='.GOOGLE_PLACE_KEY; 
 			} 
 		}
 		//echo $this->getPlaceName($lat,$long);die;
		
   		//echo $url;die;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_SSL_VERIFYPEER=>false,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_FRESH_CONNECT=>true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array("cache-control: no-cache")
		));
		$json = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		$obj = json_decode($json);
		$arr = array();
	 	$status = $obj->status;
		if(!empty($obj->results))
		{
			$index = 0;
			if(isset($obj->next_page_token)){
				$arr['next_page_token']= $obj->next_page_token;
			} else {
				$arr['next_page_token']= '';
			} 
			 	
			 
		  	foreach ($obj->results as $test) {
			  	if(isset($test->id)) {
			  		$arr['data'][$index]['googe_id']= $test->id;
			  	} else {
			  		$arr['data'][$index]['googe_id']= '';
			  	}
		  		if(isset($test->place_id)) {
		  			$arr['data'][$index]['place_id']= $test->place_id;
		  		} else {
		  			$arr['data'][$index]['place_id']= '';
		  		}
	  			$arr['data'][$index]['from_price']= 0;
		   
			    if(isset($test->price_level)) {
			      	$arr['data'][$index]['to_price']= $test->price_level;
			    } else {
			      	$arr['data'][$index]['to_price']=0;
			    } 

		  		if(isset($test->name)) {
		  			$arr['data'][$index]['name']= $test->name;
		  		} else {
		  			$arr['data'][$index]['name']= '';
		  		}
			  	if(isset($test->vicinity)) {
			  		$arr['data'][$index]['address']= $test->vicinity;
			  	} else { 
		  			if(isset($test->formatted_address)) {
			  			$arr['data'][$index]['address']= $test->formatted_address;
			  		} else {

	  				if(isset($test->vicinity)) {
			  			$arr['data'][$index]['address']= $test->vicinity;
			  		}else
			  		{
			  			$arr['data'][$index]['address']= '';
			  		} 
			  			
			  			
			  		}
		  		}
		  	
		  		if(isset($test->types)) {
		  			$arr['data'][$index]['category']= $test->types;
		  		} else {
		  			$arr['data'][$index]['category']= '';
		  		}	
		  		if(isset($test->photos[0]->photo_reference)) {
		  			$image_path = $test->photos[0]->photo_reference;
		  		} else {
		  			$image_path = '';
		  		}
			 $image_get = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference='.$image_path.'&key='.GOOGLE_PLACE_KEY.'';
				$arr['data'][$index]['image_url']= $image_get;
		 	//	$arr['data'][$index]['image_url']= '';
			 	if(isset($test->opening_hours->open_now)) {
					if( $test->opening_hours->open_now==1) {
						$status = 'open';
					} else {
						$status = 'close';
					}
			  		$arr['data'][$index]['status']= $status;
			  	} else {
		  			$arr['data'][$index]['status']= '';
		  		}
			  	if(isset($test->rating)) {
			  		$arr['data'][$index]['rating']= (string)number_format((float)$test->rating, 2, '.', '');
			  	} else {
			  		$arr['data'][$index]['rating']= (string)number_format((float)'', 2, '.', '');;
			  	}	
			  	if(isset($test->opening_hours->weekday_text)) {
			  		$arr['data'][$index]['weekday_text']= $test->opening_hours->weekday_text;
			  	} else {
			  		$arr['data'][$index]['weekday_text']= '';
			  	}	
			 	if(isset($test->geometry->location->lat)) {
			  		$arr['data'][$index]['lat']= $test->geometry->location->lat;
			  	} else {
			  		$arr['data'][$index]['lat']= '';
			  	}
			  	if(isset($test->geometry->location->lng)) {
			  		$arr['data'][$index]['lng']=$test->geometry->location->lng;
			  	} else {
			  		$arr['data'][$index]['lng']= '';
			  	}
		  	  	

		  		$index++; 
		  	}
		}else
		{
			$arr['google_status']= $status;
		}
		return $arr;
 	}


 	function getCalculatedResult($spon,$unspon)
 	{
 			$result = array();
			$j= 0;$s = 0;$a=0;

			for ($i=0; $i<count($unspon); $i++) {
				if($a==0 || $a==1)
				{ 
			 	  	if(!empty($spon[$j]))
			 	  	{
			 	  		$result[] = $spon[$j];
			 	  	}else
			 	  	{
			 	  		 $result[] = $unspon[$i]; 
			 	  	}
			 	  	$j++; 
			 	  	$s=0;   		
				}else
				{
					$result[] = $unspon[$i]; 
					if($s==4)
					{
					 	 $a = -1;
					}
				   	$s++;  
				} 
			  $a++;
			}

			return $result;
 	}

}

	