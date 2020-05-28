<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct()
    {
    	parent:: __construct();
    }

    function get_post_list($limit=-1,$condition,$row) //$condition is array 
    {
        $this->db->select('up.post_title,up.post_detail,up.status,up.post_date,u.username,u.email,gl.address,up.post_id');
        $this->db->from('user_post up');
        $this->db->join('users u', 'u.user_id = up.user_id');
        $this->db->join('gio_location gl', 'gl.location_id = up.location_id','left');
        $this->db->where($condition);
        if($row!='yes') {
            if($limit>-1) {
                $this->db->limit(ADMIN_LIMIT,$limit);
            }
        }
        $this->db->order_by("post_id", "desc");
        $rs = $this->db->get(); 
        
        if($row=='yes') {
           return $rs->row_array();
        } else {
           return $rs->result_array();
        }
    }

      public function getPagesList($limit, $offset)
    {
        if($this->input->get()) {
            if($this->input->get('page_name') !='') {
                $this->db->like('username', $this->input->get('page_name'));
            }
            if($this->input->get('orderby') !='') {
                $this->db->order_by('user_id', $this->input->get('orderby'));
            } 
        } else {
            $this->db->order_by('user_id','DESC');
        }
        
        $this->db->select('*');
        $this->db->from('users');
        if($limit==0 && $offset==0) {
            return $this->db->count_all_results();
        } else {
            $this->db->limit($limit,$offset);
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    function get_post_according_to_report() 
    {
        $this->db->select('user_post.*,users.*');
        $this->db->from('user_post');
        $this->db->join('users', 'users.user_id = user_post.user_id');
        $this->db->join('report_post', 'report_post.post_id = user_post.post_id');
        $this->db->group_by('report_post.post_id');
          $this->db->Where('user_post.is_deleted',0);
        $rs = $this->db->get(); 
        return $rs->result_array();
    }


    public function getrecordlike($start_date,$end_date)
    {
        $start_date = $start_date.' 00:00:00';
        $end_date = $end_date.' 23:59:59';
        $this->db->select('user_id');
        $this->db->from('business_page');
        $this->db->Where('status','verified');
        $this->db->Where('created >=',$start_date);
        $this->db->Where('created <=',$end_date);
        $query = $this->db->get();

     return   $rowcount = $query->num_rows();
    }
    public function getrecordlike_offer($start_date,$end_date)
    {
        $start_date = $start_date.' 00:00:00';
        $end_date = $end_date.' 23:59:59';
        $this->db->select('subscription_user_id');
        $this->db->from('subscription_user');
        $this->db->Where('created >=',$start_date);
        $this->db->Where('created <=',$end_date);
        $query = $this->db->get();

     return   $rowcount = $query->num_rows();
    }

    public function business_rating_one($page_id,$disply_rating,$rating_categories_id)
    { if(!empty($disply_rating)){
        $disply="AND `verified_rating`='$disply_rating'";
        }else {
        $disply=''; 
        }
       $query = $this->db->query(" SELECT IFNULL(AVG(rating), '0') as rating from rating_page  WHERE `page_id`='$page_id' AND `rating_categories_id`='$rating_categories_id' ".$disply);
        
        return $query->row()->rating;
    }

    public function business_rating($page_id,$disply_rating)
    { if(!empty($disply_rating)){
        $disply="AND `verified_rating`='$disply_rating'";
        }else {
        $disply=''; 
        }
       $query = $this->db->query(" SELECT IFNULL(AVG(rating), '0') as rating from rating_page  WHERE `page_id`='$page_id'".$disply);
        
        return $query->row()->rating;
    }

    public function business_rating_count($page_id,$disply_rating)
    { if(!empty($disply_rating)){
        $disply="AND `verified_rating`='$disply_rating'";
        }else {
        $disply=''; 
        }
       $query = $this->db->query("SELECT IFNULL(count(review_id), '0') as count from review  WHERE `page_id`='$page_id' ".$disply);
        
        return $query->row()->count;
    }

       public function business_review($page_id,$disply_rating)
    {
        $this->db->select('IFNULL(AVG(rp.rating), "0") as rating ,r.title,r.description,r.status,r.verified_rating,r.review_id,r.created,u.profile_pic,u.username');
        $this->db->from('review r');
        $this->db->join('users u','u.user_id = r.user_id');
        $this->db->join('rating_page rp','rp.review_id = r.review_id');
        $this->db->where('r.page_id',$page_id);
       
        if(!empty($disply_rating)){
        $this->db->where('r.verified_rating',$disply_rating);}
        $this->db->group_by('r.review_id'); 
        $query = $this->db->get();
        return $query->result_array();
    }

        function get_pagelist($country,$state,$city) 
        {
            $this->db->select('business_page.*,users.username,users.free_page,categories.name,sub_categories.name as subname');
            $this->db->from('business_page');
            $this->db->join('users', 'users.user_id = business_page.user_id');
            $this->db->join('categories', 'categories.category_id = business_page.category_id','left');
            $this->db->join('sub_categories', 'sub_categories.sub_category_id = business_page.sub_category_id','left');
            if(!empty($country)){
                $this->db->where('business_page.country_id',$country);
            }
            if(!empty($state)){
                $this->db->where('business_page.state_id',$state);
            }
            if(!empty($city)){
                $this->db->where('business_page.city_id',$city);
            }
            $this->db->order_by('business_page.business_page_id','DESC');
             $this->db->where('business_page.is_deleted','0');
            $rs = $this->db->get(); 
            return $rs->result_array();
        }

        public function get_used_country() {
        $this->db->select('u.country_id,c.name');
        $this->db->distinct();
        $this->db->from('business_page u');
        $this->db->join('countries c','c.id=u.country_id');
        $query= $this->db->get();
        if ($query->num_rows() > 0) {
            $list_array = $query->result_array();
            $countries_list = array('0'=>'Select Country');

            foreach($list_array  as $list) {
                $countries_list[$list['country_id']] = $list['name'];
            }
            return $countries_list;
        } else return false;
    }

    
    public function get_used_state() {
        $this->db->select('u.state_id,s.name');
        $this->db->distinct();
        $this->db->from('business_page u');
        $this->db->join('states s','s.id=u.state_id');
        $query= $this->db->get();
        if ($query->num_rows() > 0) {
            $list_array = $query->result_array();
            $countries_list = array('0'=>'Select State');
            foreach($list_array  as $list) {
                $countries_list[$list['state_id']] = $list['name'];
            }
            return $countries_list;
        } else return false;
    }

    public function get_used_city() {
        $this->db->select('u.city_id,c.name');
        $this->db->distinct();
        $this->db->from('business_page u');
        $this->db->join('cities c','c.id=u.city_id');
        $query= $this->db->get();
        if ($query->num_rows() > 0) {
            $list_array = $query->result_array();
            $countries_list = array('0'=>'Select City');
            foreach($list_array  as $list) {
                $countries_list[$list['city_id']] = $list['name'];
            }
            return $countries_list;
        } else return false;
    }

  public function get_clam_request()
  {
    $this->db->select('gbc.*,u.user_id,u.username,u.email,u.profile_pic,u.mobile');
    $this->db->from('google_business_clam gbc');
    $this->db->join('users u','u.user_id=gbc.user_id');
    $this->db->order_by("gbc.id", "desc");
    $query= $this->db->get();

    $list_array = $query->result_array();
    return $list_array;
  }



}/* model end*/

?>