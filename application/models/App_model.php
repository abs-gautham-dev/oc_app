<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_Model {
	function __construct() {
        parent::__construct();
    }


    public function getItemsByPropertyId($property_id,$user_id,$room_id='')
    {
        if($room_id!="") {
            $this->db->where('i.room_id',$room_id);
        }
        if($items_list = $this->input->post('items_list')) {
            $where="(";
            for($i=0;$i< count($items_list);$i++) {
                $item_id = $items_list[$i];
                $where.= "i.id = $item_id OR ";
            }
            $where = trim($where, " OR ");
            $where.=")";
            $this->db->where($where, NULL, FALSE);
            $this->db->order_by('i.room_id','DESC');
        } else {
           $this->db->order_by('i.id','DESC'); 
        }
        $this->db->select('i.id,i.item_name,i.price_paid,i.current_price,i.purchased_date,i.quantity,i.brand_name,i.model,i.serial_type,i.serial_number,i.receipts,i.category_id,i.room_id,i.label_id,i.policy_id,i.property_id,i.description,r.room_name,c.category_name,l.label_name,p.property_name,p.currency,pl.policy_name');
        $this->db->from('items i');
        $this->db->join('categories c','c.id = i.category_id','left');
        $this->db->join('labels l','l.id = i.label_id','left');
        $this->db->join('rooms r','r.id = i.room_id','left');
        $this->db->join('properties p','p.id = i.property_id','left');
        $this->db->join('policies pl','pl.id = i.policy_id','left');
        $this->db->where('i.property_id',$property_id);
        $this->db->where('i.user_id',$user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getItemsByCategoryId($category_id,$user_id)
    {
        $this->db->select('i.id,i.item_name,i.price_paid,i.current_price,i.purchased_date,i.quantity,i.brand_name,i.model,i.serial_type,i.serial_number,i.receipts,i.category_id,i.room_id,i.label_id,i.policy_id,i.property_id,i.description,r.room_name,c.category_name,l.label_name,c.category_name,p.property_name,p.currency,pl.policy_name');
        $this->db->from('items i');
        $this->db->join('categories c','c.id = i.category_id','left');
        $this->db->join('labels l','l.id = i.label_id','left');
        $this->db->join('rooms r','r.id = i.room_id','left');
        $this->db->join('properties p','p.id = i.property_id','left');
        $this->db->join('policies pl','pl.id = i.policy_id','left');
        $this->db->where('i.category_id',$category_id);
        $this->db->where('i.user_id',$user_id);
        $this->db->order_by('i.id','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getItemsByItemId($item_id,$user_id)
    {
        $this->db->select('i.id,i.item_name,i.price_paid,i.current_price,i.purchased_date,i.quantity,i.brand_name,i.model,i.serial_type,i.serial_number,i.receipts,i.category_id,i.room_id,i.label_id,i.policy_id,i.property_id,i.description,r.room_name,c.category_name,l.label_name,c.category_name,p.property_name,p.currency,pl.policy_name');
        $this->db->from('items i');
        $this->db->join('categories c','c.id = i.category_id','left');
        $this->db->join('labels l','l.id = i.label_id','left');
        $this->db->join('rooms r','r.id = i.room_id','left');
        $this->db->join('properties p','p.id = i.property_id','left');
        $this->db->join('policies pl','pl.id = i.policy_id','left');
        $this->db->where('i.id',$item_id);
        $this->db->where('i.user_id',$user_id);
        $this->db->order_by('i.id','DESC');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_all_countries()
    {
        $query = $this->db->get('countries');
        return $query->result_array();
    }

    public function get_policy_count($property_id)
    {
        $this->db->distinct('policy_id');
        $this->db->from('items');
        $this->db->where('property_id',$property_id);
        $policy_id = array('', 0);
        $this->db->where_not_in('policy_id', $policy_id);
        return $this->db->count_all_results();
    }

    public function getCatPriceItem($category_id,$user_id)
    {
        $this->db->select('(i.current_price*i.quantity) as price,currency');
        $this->db->from('items i');
        $this->db->join('properties p','p.id = i.property_id','left');
        $this->db->where('i.category_id',$category_id);
        $this->db->where('i.user_id',$user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSingleCatPriceItem($category_id,$user_id)
    {
        $this->db->select('(current_price*quantity) as total_price');
        $this->db->from('items');
        $this->db->where('category_id',$category_id);
        $this->db->where('user_id',$user_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function filterRecords($user_id,$property_id)
    {
        if($this->input->post()) {
            if($this->input->post('room_id') !='') {
                $this->db->group_start();
                foreach($this->input->post('room_id') as $room_id) {
                    $this->db->or_where('i.room_id', $room_id);
                }
                $this->db->group_end();
            }
            if($this->input->post('category_id') !='') {
                $this->db->group_start();
                foreach($this->input->post('category_id') as $category_id) {
                    $this->db->or_where('i.category_id', $category_id);
                }
                $this->db->group_end();
            }
            if($this->input->post('policy')  !='') {
                if($this->input->post('policy')==0) {
                    $this->db->where('i.policy_id', 0);
                } else {
                    $this->db->where('i.policy_id>', 0);
                }
            }
            if($this->input->post('label_id') !='') {
                $this->db->group_start();
                foreach($this->input->post('label_id') as $label_id) {
                    $this->db->or_where('i.label_id', $label_id);
                }
                $this->db->group_end();
            }
           
            if($this->input->post('price') !='') {
                $this->db->order_by('total_price', $this->input->post('price'));
            } 
        } 
        $this->db->select('i.id,i.item_name,i.price_paid,i.current_price,i.purchased_date,i.quantity,(i.current_price * i.quantity) as total_price,i.brand_name,i.model,i.serial_type,i.serial_number,i.receipts,i.category_id,i.room_id,i.label_id,i.policy_id,i.property_id,i.description,r.room_name, c.category_name,l.label_name,c.category_name,p.property_name,p.currency,pl.policy_name');
        $this->db->from('items i');
        $this->db->join('categories c','c.id = i.category_id','left');
        $this->db->join('labels l','l.id = i.label_id','left');
        $this->db->join('rooms r','r.id = i.room_id','left');
        $this->db->join('properties p','p.id = i.property_id','left');
        $this->db->join('policies pl','pl.id = i.policy_id','left');
        $this->db->where('i.user_id',$user_id);
        $this->db->where('i.property_id',$property_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getNotifications($user_id)
    {
       
        $this->db->select('n.*,u.username,u.profile_pic');
        $this->db->from('notifications n');
        $this->db->join('users u','u.user_id = n.created_by');
       
        $this->db->where('n.user_id',$user_id);
        $this->db->where('n.status','0');
        $this->db->where('n.page_id','0');
        $this->db->order_by('n.notification_id','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

       public function getNotificationspage($page_id)
    {
       
        $this->db->select('n.*,u.username,u.profile_pic');
        $this->db->from('notifications n');
        $this->db->join('users u','u.user_id = n.created_by');
        $this->db->where('n.status','0');
        $this->db->where('n.page_id',$page_id);
        $this->db->order_by('n.notification_id','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getPost()
    {  
        $this->db->select("up.post_id,up.post_title, up.post_detail,u.username, up.post_date,IFNULL(lo.address, '') as address, IFNULL(lo.address_name, '') as address_name, IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude,u.full_name,u.profile_pic"); 
        $this->db->from('user_post up');
        $this->db->join('users u','u.user_id = up.user_id','left');
        $this->db->join('gio_location lo','lo.location_id = up.location_id','left');
        $this->db->join('post_like l','l.post_id = up.post_id','left');
        $this->db->where('u.account_type','Public');
        $this->db->where('up.status','Active');
        $this->db->where('up.is_deleted','0');
        $this->db->group_by('up.post_id'); 
        $this->db->order_by('l.created', 'desc'); 
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPostlogin($user_id)
    {   
        $this->db->select('follow_user_id'); 
        $this->db->from('follow_user fu');
        $this->db->where('fu.user_id',$user_id);
        $this->db->where('fu.status','Follow');
        $query = $this->db->get();
        $fu=$query->result_array();
        $ara = array('follow_user_id' => $user_id);
        array_push($fu,$ara);
        foreach( $fu as $label){
            $form_titles[] = $label['follow_user_id'];
        }
        return $form_titles;
    }
        public function getpagelogin($user_id)
    {   
        $this->db->select('follow_page_id'); 
        $this->db->from('follow_page fp');
        $this->db->where('fp.user_id',$user_id);
        $this->db->where('fp.status','Follow');
        $query = $this->db->get();
        $form_titles = array();
        $fu=$query->result_array();
        foreach( $fu as $label){
            $form_titles[] = $label['follow_page_id'];
        }
        return $form_titles;
    }

    public function getdatalist()
    { 
      
        $this->db->select("up.user_id,up.post_id,up.post_title,u.username,up.created, up.post_detail, up.post_date,IFNULL(lo.address, '') as address,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude,u.full_name,u.profile_pic,up.share_by,up.shareby_id,up.parent_id,up.business_page_id"); 
        $this->db->from('user_post up');
        $this->db->join('users u','u.user_id = up.user_id','left');
        $this->db->join('gio_location lo','lo.location_id = up.location_id','left');
        $this->db->where('up.status','Active'); 
        $this->db->where('up.is_deleted','0');
        $this->db->group_start();
        $this->db->group_start();
        $this->db->group_start();
        $this->db->group_start();
        $this->db->or_where_in('up.user_id', $form_titles);
        $this->db->where('up.shareby_id','0');
        $this->db->group_end();
        $this->db->or_where_in('up.shareby_id', $form_titles);
        $this->db->group_end();
        $this->db->where('up.business_page_id','0');
        $this->db->group_end();
        
        if(!empty($getpage)){
            $this->db->or_group_start();
            $this->db->where_in('up.business_page_id', $getpage);
            $this->db->or_where_in('up.is_page_shareby', $getpage);
            $this->db->group_end();
        }

        $this->db->group_end();
        $this->db->group_by('up.post_id'); 
        $this->db->order_by('up.created', 'desc'); 
        $query = $this->db->get();
        return $query->result_array();

         

    }


    public function getdatalistone($form_titles)
    { 
        $this->db->select("up.user_id,up.post_id,up.post_title,u.username,up.created, up.post_detail, up.post_date,IFNULL(lo.address, '') as address,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude,u.full_name,u.profile_pic,up.shareby_username,up.shareby_id,up.parent_id1"); 
        $this->db->from('user_post up');
        $this->db->join('users u','u.user_id = up.user_id','left');
        $this->db->join('follow_user fu','fu.user_id = up.user_id','left');
        $this->db->join('gio_location lo','lo.location_id = up.location_id','left');
        $this->db->where_in('up.shareby_id', $form_titles);
        $this->db->where('u.account_type','Public');
        $this->db->or_where_in('up.user_id', $form_titles);
        $this->db->where('up.status','Active'); 
        $this->db->where('up.is_deleted','0');
        $this->db->group_by('up.post_id'); 
        $this->db->order_by('up.created', 'desc'); 
        $query = $this->db->get();
        return $query->result_array();

    }

    public function getExplore($lat,$lng,$sort='',$user_id=0)
    { 
        if($sort == 'views'){
            $sort_condition = '`up`.`view_post` DESC ';
        }else if ($sort == 'likes') {
            $sort_condition = '`post_likes` DESC';
        }else if ($sort == 'latest') {
            $sort_condition = '`up`.`created` DESC ';
        } else {
            $sort_condition =  'distance';
        }

        $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as post_likes, `u`.`user_id`,`u`.`username`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`, `up`.`post_date`,`up`.`business_page_id`,`up`.`shareby_id`,IFNULL(lo.address, '') as address,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude, ( 3959 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS(  `lo`.`latitude` ) ) * COS( RADIANS(  `lo`.`longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS(  `lo`.`latitude` ) ) ) ) AS distance
                                    FROM `user_post` `up` 
                                    INNER JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id` 
                                    WHERE `u`.`account_type` = 'Public'
                                    AND  (up.user_id NOT IN (select follow_user_id from `follow_user` where user_id='".$user_id."' AND follow_user_id = `up`.`user_id`))
                                    AND `up`.`status` = 'Active' 
                                    AND `up`.`user_id` != ".$user_id." 

                                    AND `up`.is_deleted= 0
                                    AND `up`.`shareby_id`=0
                                    AND `up`.`is_page_shareby`=0
                                    AND `up`.`business_page_id`=0
                                    ORDER BY ".$sort_condition."");
        return $query->result_array();
        
    }

    public function getExplore_with_search($lat,$lng,$sort='',$location_id='',$user_id='')
    { 

        if($sort == 'views') {
            $sort_condition = '`up`.`view_post` DESC ';
        }else if ($sort == 'likes') {
            $sort_condition = '`post_likes` DESC';
        }else if ($sort == 'latest') {
            $sort_condition = '`up`.`created` DESC ';
        } else {
            $sort_condition='`post_likes` DESC';
        }
        
        // echo " SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as post_likes, `u`.`user_id`,`u`.`username`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`business_page_id`, `up`.`post_date`,`up`.`shareby_id`, `lo`.`address`,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude  
        //                             FROM `user_post` `up` 
        //                             INNER JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
        //                             INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
        //                             WHERE `u`.`account_type` = 'Public'
        //                             AND `up`.`status` = 'Active' 
        //                             AND `up`.is_deleted= 0
        //                             AND `up`.`shareby_id`=0
        //                             AND `up`.`is_page_shareby`=0
        //                             AND `up`.`business_page_id`=0
        //                             AND `up`.`user_id`!=".$user_id."
        //                            /* AND `up`.`location_id` ='$location_id' */
        //                             AND `lo`.`latitude` ='$lat'
        //                             AND `lo`.`longitude` ='$lng'
        //                             ORDER BY ".$sort_condition."";die;
        $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as post_likes, `u`.`user_id`,`u`.`username`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`business_page_id`, `up`.`post_date`,`up`.`shareby_id`, `lo`.`address`,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude  
                                    FROM `user_post` `up` 
                                    INNER JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    WHERE `u`.`account_type` = 'Public'
                                      AND
                    (
                    up.user_id NOT IN (select follow_user_id from `follow_user` where user_id='".$user_id."' AND follow_user_id = `up`.`user_id`)
                    ) 
                                    AND `up`.`status` = 'Active' 
                                    AND `up`.is_deleted= 0
                                    AND `up`.`shareby_id`=0
                                    AND `up`.`is_page_shareby`=0
                                    AND `up`.`business_page_id`=0
                                    AND `up`.`user_id`!=".$user_id."
                                   /* AND `up`.`location_id` ='$location_id' */
                                    AND `lo`.`latitude` ='$lat'
                                    AND `lo`.`longitude` ='$lng'
                                    ORDER BY ".$sort_condition."");

        return $query->result_array();
        
    }
    public function getExplore_with_search_user_id($sort='',$search_user_id,$user_id='0')
    { 

         if($sort == 'views')
        {
               $sort_condition = '`up`.`view_post` DESC ';

        }elseif ($sort == 'likes') {

            $sort_condition = '`post_likes` DESC';

        }elseif ($sort == 'latest') {

             $sort_condition = '`up`.`created` DESC ';
        }else
        {
             $sort_condition='`post_likes` DESC';
        }
        

        $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as post_likes, `u`.`user_id`,`u`.`username`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`business_page_id`, `up`.`post_date`,`up`.`shareby_id`, `lo`.`address`,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                    FROM `user_post` `up` 
                                    INNER JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    WHERE `u`.`account_type` = 'Public'
                                      AND
                    (
                    user_post.user_id NOT IN (select follow_user_id from `follow_user` where user_id='".$user_id."' AND follow_user_id = `user_post`.`user_id`)
                    ) 
                                    AND `up`.`status` = 'Active' 
                                    AND `up`.is_deleted= 0
                                    AND `up`.`shareby_id`=0
                                    AND `up`.`user_id`=".$user_id."
                                    AND `up`.`is_page_shareby`=0
                                    AND `up`.`business_page_id`=0
                                    AND `up`.user_id = '$search_user_id'
                                   
                                    ORDER BY ".$sort_condition."");
        return $query->result_array();
        
    }




     public function getExplorelogin($user_id)
    {  
        $this->db->select('up.post_id,up.post_title, up.post_detail, up.post_date,lo.address,u.full_name,u.profile_pic,count(l.post_id) as likes, count(c.post_comment_id) as comment'); 
        $this->db->from('user_post up');
        $this->db->join('users u','u.user_id = up.user_id','left');
        $this->db->join('follow_user fu','fu.user_id = up.user_id','left');
        $this->db->join('gio_location lo','lo.location_id = up.location_id','left');
        $this->db->join('post_like l','l.post_id = up.post_id','left');
        $this->db->join('post_comment c','c.post_id = up.post_id','left');
        $this->db->where('u.account_type','Public');
        $this->db->where('up.status','Active');
        $this->db->group_by('up.post_id'); 
        $this->db->order_by('up.created', 'desc'); 
        $query = $this->db->get();
        return $query->result_array();
 
    }
    
    public function getlikes($media_id)
    { 
        $this->db->select('count(l.media_id) as likes');
        $this->db->from('media_like l');
        $this->db->where('l.media_id',$media_id);
        $this->db->group_by('l.media_id'); 
        $query = $this->db->get();
        return $query->row();
    }

    public function isLike($post_id,$user_id)
    { 
        $this->db->select('count(l.post_id) as isLike');
        $this->db->from('post_like l');
        $this->db->where('l.post_id',$post_id);
        $this->db->where('l.user_id',$user_id);
        $this->db->group_by('l.post_id'); 
        $query = $this->db->get();
        return $query->row();
    }

    public function getcomment($post_id)
    { 
        $this->db->select('count(c.post_id) as comment');
        $this->db->from('post_comment c');
        $this->db->where('c.post_id',$post_id);
        $this->db->group_by('c.post_id');
        $this->db->order_by("post_comment_id", "desc"); 
        $query = $this->db->get();
        return $query->row();
    }

     public function getcommentlist($post_id)
    { 
        $this->db->select('c.comment, c.page_id, c.created as comment_time,u.username, u.full_name,u.profile_pic');
        $this->db->from('post_comment c');
        $this->db->join('users u','u.user_id = c.user_id','left');
        $this->db->where('c.post_id',$post_id);
        $this->db->order_by("post_comment_id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }

     public function get_county_state_city_id($table,$select,$colum,$value)
    {
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where("$colum LIKE '%$value%'");
          $query = $this->db->get(); 
        return $query->result_array();
    }
 

    public function taguser($post_id)
    { 
        $this->db->select('tu.tag_user_id,u.full_name,u.username,u.user_id');
        $this->db->from('tag_user tu');
        $this->db->join('users u','u.user_id = tu.user_id','left');
        $this->db->where('tu.post_id',$post_id);
        $this->db->where('u.is_deleted','0');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function tagpage($post_id)
    { 
        $this->db->select('tag_page.page_id,tag_page.tag_id,business_page.business_full_name,business_page.business_name,business_page.business_page_id,business_page.user_id');
        $this->db->from('tag_page');
        $this->db->join('business_page','business_page.business_page_id = tag_page.page_id','left');
        $this->db->where('tag_page.post_id',$post_id);
         $this->db->where('business_page.is_deleted','0');
        $query = $this->db->get();
        return $query->result_array();
    } 

    public function userInterest($user_id)
    { 
        $this->db->select('tu.interest_id,tu.user_interest_id');
        $this->db->from('user_interest tu');
        $this->db->where('tu.user_id',$user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function business_page_amenity($page_id)
    { 
        $this->db->select('bpa.amenity_id,bpa.page_amenity_id,a.name,a.icon_image');
        $this->db->from('business_page_amenity bpa');
        $this->db->join('amenities a','a.amenity_id = bpa.amenity_id','left');
        $this->db->where('bpa.page_id',$page_id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getProfile($user_id,$second_user='0')
    { 
        $this->db->select("u.*, (select count(follow_user.follow_user_id) from follow_user where follow_user.user_id = $user_id AND follow_user.status = 'Follow' ) as following ,(select count(follow_user.follow_user_id) from follow_user where follow_user.follow_user_id = $user_id AND follow_user.status = 'Follow') as followers ,(select count(follow_user.user_id) from follow_user where follow_user.user_id = $second_user AND follow_user.follow_user_id = $user_id  ) as isFollow,(select count(user_post.post_id) from user_post where user_post.user_id = $user_id AND user_post.is_deleted ='0' AND user_post.business_page_id ='0' AND shareby_id=0 ) as total_post , IFNULL(co.name, '') as country ,IFNULL(st.name, '') as state, IFNULL(ci.name, '') as city");
        $this->db->from('users u');
        $this->db->join('countries co','u.country_id = co.id','left');
        $this->db->join('states st','u.state_id = st.id','left');
        $this->db->join('cities ci','u.city_id =  ci.id','left');

        $this->db->where('u.user_id',$user_id);

        $query = $this->db->get();
        return $query->row_array();
    }
    public function user_interested($user_id)
    { 
    
        $this->db->select('interest.name,interest.interest_id');    
        $this->db->from('user_interest');
        $this->db->where('user_id',$user_id);
        $this->db->join('interest','user_interest.interest_id =  interest.interest_id');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function getPostdetails($post_id)
    { 
        $this->db->select('up.user_id,up.location_id,up.post_id,up.post_title,u.username, up.post_detail, up.post_date,IFNULL(lo.address, "") as address,IFNULL(lo.address_name, "") as address_name,IFNULL(lo.latitude, "") as latitude,IFNULL(lo.longitude, "") as longitude,u.full_name,u.profile_pic,up.share_by,up.shareby_id,up.parent_id,up.business_page_id'); 
        $this->db->from('user_post up');
        $this->db->join('users u','u.user_id = up.user_id','left');
        $this->db->join('follow_user fu','fu.user_id = up.user_id','left');
        $this->db->join('gio_location lo','lo.location_id = up.location_id','left');
        $this->db->where('up.post_id', $post_id);
        $this->db->where('up.status','Active');
        $this->db->where('up.is_deleted','0');
        $this->db->group_by('up.post_id'); 
        $this->db->order_by('up.created', 'desc'); 
        $query = $this->db->get();
        return $query->row_array();
 
    }

     public function postFollowList($user_id)
    { 
        $this->db->select('follow_user_id'); 
        $this->db->from('follow_user fu');
        $this->db->where('fu.user_id',$user_id);
        $this->db->where('fu.status','Follow');
        $query = $this->db->get();
        $form_titles=array('0');
        $fu=$query->result_array();
        $ara = array('follow_user_id' => $user_id);
       foreach( $fu as $label){
            $form_titles[] = $label['follow_user_id'];
        }
        
        $this->db->select('user_id,u.username,u.full_name,u.full_name,profile_pic,firebase_email,firebase_password,firebase_id'); 
        $this->db->from('users u');
        $this->db->where_in('u.user_id', $form_titles);
        
        $query = $this->db->get();
        return $query->result_array();
 
    }

    public function postFollowingList($user_id)
    { 
        $this->db->select('user_id'); 
        $this->db->from('follow_user fu');
        $this->db->where('fu.follow_user_id',$user_id);
        $this->db->where('fu.status','Follow');
        $query = $this->db->get();
        $fu=$query->result_array();
        $form_titles=array('0');
        foreach( $fu as $label){
            $form_titles[] = $label['user_id'];
        }
      
        $this->db->select('user_id,u.username,u.full_name,u.full_name,profile_pic,firebase_email,firebase_password,firebase_id'); 
        $this->db->from('users u');
        $this->db->where_in('u.user_id', $form_titles);
        
        $query = $this->db->get();
        return $query->result_array();
 
    }

     public function isFollow($follow_user_id,$user_id)
    { 
        $this->db->select('fu.status'); 
        $this->db->from('follow_user fu');
        $this->db->where('fu.user_id',$user_id);
        $this->db->where('fu.follow_user_id',$follow_user_id);
        $query = $this->db->get();
        return $query->result_array();
 
    }

      public function isFollowpage($follow_page_id,$user_id)
    { 
        $this->db->select('fu.status'); 
        $this->db->from('follow_page fu');
        $this->db->where('fu.user_id',$user_id);
        $this->db->where('fu.follow_page_id',$follow_page_id);
        $query = $this->db->get();
        return $query->result_array();
 
    }

       
    public function getPostShare($user_id)
    { 
        $this->db->select('ps.post_owner,ps.post_id,ps.user_id,u.username,ps.created'); 
        $this->db->from('post_share ps');
        $this->db->join('users u','u.user_id = ps.user_id');
        $this->db->where('ps.user_id',$user_id);
       
        $query = $this->db->get();
        return  $fu=$query->result_array();
       
        
    }

    public function getPostdel($post_id)
    { 
        $this->db->select("up.user_id,up.post_id,up.post_title,u.username, up.post_detail, up.post_date,IFNULL(lo.address, '') as address,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude,u.full_name,u.profile_pic"); 
        $this->db->from('user_post up');
        $this->db->join('users u','u.user_id = up.user_id','left');
        $this->db->join('follow_user fu','fu.user_id = up.user_id','left');
        $this->db->join('gio_location lo','lo.location_id = up.location_id','left');
        $this->db->where('up.post_id', $post_id);
        $this->db->where('up.status','Active');
        $this->db->where('up.is_deleted','0');
        $this->db->group_by('up.post_id'); 
        $this->db->order_by('up.created', 'desc'); 
        $query = $this->db->get();
     
        return $query->row_array();
    }

    public function getFollowers($user_id) {
        $this->db->select('follow_user_id,u.account_type'); 
        $this->db->from('follow_user fu');
        $this->db->join('users u','u.user_id = fu.follow_user_id');
        $this->db->where('fu.user_id',$user_id);
        $this->db->where('fu.status','Follow');
        $query = $this->db->get();
        $form_titles=array('0');
        return $query->result_array();

    }
    
    public function getfollowerSharedPost($follower_id) { 
        $this->db->select('ps.*,p.username'); 
        $this->db->from('post_share ps');
        $this->db->join('users u','u.user_id = ps.post_owner');
        $this->db->join('users p','p.user_id = ps.user_id');
        $this->db->where('ps.user_id',$follower_id);
        $this->db->where('u.account_type',"Public");
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getExplore_images($lat,$lng,$media_type,$sort='',$user_id='0'){
 

        if($sort == 'views')
        {
              $sort_condition = '`user_post`.`view_post` DESC ';

        }elseif ($sort == 'likes') {

            $sort_condition = '`post_likes` DESC';

        }elseif ($sort == 'latest') {

             $sort_condition = '`user_post`.`created` DESC ';
        }
        else{
            $sort_condition =  'distance';
        }
        // echo "SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = user_post.post_id ) as post_likes, users.user_id,users.username,users.profile_pic,post_img.video_path as video_file_path,user_post.post_id,user_post.post_title,user_post.business_page_id,user_post.post_date,user_post.shareby_id,user_post.view_post,post_img.post_img_id,post_img.file_type,post_img.file_path,IFNULL(lo.address_name, '') as address_name,lo.address,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude,( 3959 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS(  `lo`.`latitude` ) ) * COS( RADIANS(  `lo`.`longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS(  `lo`.`latitude` ) ) ) ) AS distance FROM  `gio_location` `lo` inner join user_post on lo.location_id = user_post.location_id  inner join users on  user_post.user_id = users.user_id  inner join post_img on user_post.post_id = post_img.post_id  where post_img.file_type = '".$media_type."'   AND `user_post`.is_deleted= 0 
        //     AND
        //         (
        //         user_post.user_id NOT IN (select follow_user_id from `follow_user` where user_id='".$user_id."' AND follow_user_id = `user_post`.`user_id`)
        //         ) 
        //     AND

        //  `user_post`.shareby_id= 0 AND `user_post`.user_id!='".$user_id."'  AND `user_post`.business_page_id = 0 AND  `user_post`.status= 'Active' AND `users`.`account_type` = 'Public'  ORDER BY ".$sort_condition." ";die;
    
        $query = $this->db->query("SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = user_post.post_id ) as post_likes, users.user_id,users.username,users.profile_pic,post_img.video_path as video_file_path,user_post.post_id,user_post.post_title,user_post.business_page_id,user_post.post_date,user_post.shareby_id,user_post.view_post,post_img.post_img_id,post_img.file_type,post_img.file_path,IFNULL(lo.address_name, '') as address_name,lo.address,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude,( 3959 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS(  `lo`.`latitude` ) ) * COS( RADIANS(  `lo`.`longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS(  `lo`.`latitude` ) ) ) ) AS distance FROM  `gio_location` `lo` inner join user_post on lo.location_id = user_post.location_id  inner join users on  user_post.user_id = users.user_id  inner join post_img on user_post.post_id = post_img.post_id  where post_img.file_type = '".$media_type."'   AND `user_post`.is_deleted= 0 
                AND
                    (
                    user_post.user_id NOT IN (select follow_user_id from `follow_user` where user_id='".$user_id."' AND follow_user_id = `user_post`.`user_id`)
                    ) 
            AND

         `user_post`.shareby_id= 0 AND `user_post`.user_id!='".$user_id."'  AND `user_post`.business_page_id = 0 AND  `user_post`.status= 'Active' AND `users`.`account_type` = 'Public'  ORDER BY ".$sort_condition." ");
 
        return $query->result_array();
        
    }

     public function getExplore_images_search($lat,$lng,$media_type,$sort=''){
 

        if($sort == 'views')
        {
              $sort_condition = '`user_post`.`view_post` DESC ';

        }elseif ($sort == 'likes') {

            $sort_condition = '`post_likes` DESC';

        }elseif ($sort == 'latest') {

             $sort_condition = '`user_post`.`created` DESC ';
        }
        else{
            $sort_condition =  '`user_post`.`created` DESC ';
        }
    
        $query = $this->db->query("SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = user_post.post_id ) as post_likes, users.user_id,users.username,users.profile_pic,post_img.video_path as video_file_path,user_post.post_id,user_post.post_title,user_post.business_page_id,user_post.shareby_id,user_post.post_date,user_post.view_post,post_img.post_img_id,post_img.file_type,post_img.file_path,lo.address,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude FROM  `gio_location` `lo` 
            inner join user_post on lo.location_id = user_post.location_id  
            inner join users on  user_post.user_id = users.user_id  
            inner join post_img on user_post.post_id = post_img.post_id 
            where post_img.file_type = '".$media_type."'  
             AND `user_post`.is_deleted= 0 
             AND `user_post`.status= 'Active'
             AND `users`.`account_type` = 'Public'
             AND `user_post`.shareby_id =0
              AND `user_post`.business_page_id =0
             AND  `lo`.`latitude` ='$lat' AND `lo`.`longitude` ='$lng'
               ORDER BY ".$sort_condition." ");
 
        return $query->result_array();
        
    } 
    public function getExplore_images_search_with_user_id($media_type,$sort='',$search_user_id){
 

        if($sort == 'views')
        {
              $sort_condition = '`user_post`.`view_post` DESC ';

        }elseif ($sort == 'likes') {

            $sort_condition = '`post_likes` DESC';

        }elseif ($sort == 'latest') {

             $sort_condition = '`user_post`.`created` DESC ';
        }
        else{
            $sort_condition =  '`user_post`.`created` DESC ';
        }
    
        $query = $this->db->query("SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = user_post.post_id ) as post_likes, users.user_id,users.username,users.profile_pic,post_img.video_path as video_file_path,user_post.post_id,user_post.post_title,user_post.business_page_id,user_post.shareby_id,user_post.post_date,user_post.view_post,post_img.post_img_id,post_img.file_type,post_img.file_path,lo.address,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude FROM  `gio_location` `lo` 
            inner join user_post on lo.location_id = user_post.location_id  
            inner join users on  user_post.user_id = users.user_id  
            inner join post_img on user_post.post_id = post_img.post_id 
            where post_img.file_type = '".$media_type."'  
             AND `user_post`.is_deleted= 0 
             AND `user_post`.status= 'Active'
             AND `users`.`account_type` = 'Public' 
             AND `user_post`.shareby_id= 0
              AND `user_post`.business_page_id =0
             AND  `user_post`.user_id='$search_user_id'
               ORDER BY ".$sort_condition." ");
 
        return $query->result_array();
        
    }

  public function getExplore_maps_location($lat,$lng,$user_id='0'){

    $query = $this->db->query(" SELECT  user_post.post_id,user_post.user_id, user_post.business_page_id,lo.location_id ,lo.latitude,lo.longitude,lo.address,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude,( 3959 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS(  `lo`.`latitude` ) ) * COS( RADIANS(  `lo`.`longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS(  `lo`.`latitude` ) ) ) ) AS distance FROM  `gio_location` `lo` 
        inner join user_post on lo.location_id = user_post.location_id 
         INNER JOIN users ON users.user_id = user_post.post_id 
        Where 
                  (
                 user_post.user_id NOT IN (SELECT follow_user_id FROM `follow_user` WHERE user_id='".$user_id."' AND follow_user_id = `user_post`.`user_id`)
                 ) AND


        `user_post`.is_deleted= 0 AND `user_post`.shareby_id= 0  AND 
        `user_post`.`business_page_id`= 0  

        AND
           `users`.is_deleted= '0' AND
           `user_post`.user_id!= '".$user_id."' AND
         `user_post`.status= 'Active'  HAVING distance < 50  ORDER BY distance");
    
        return $query->result_array();
        
    }

    public function getExplore_maps_location_place($lat,$lng){
 
         $query = $this->db->query(" SELECT user_post.post_id,user_post.user_id, user_post.business_page_id, lo.location_id ,lo.latitude,lo.longitude,lo.address, IFNULL(lo.address_name, '') as address_name FROM  `gio_location` `lo` inner join user_post on lo.location_id = user_post.location_id   Where `user_post`.shareby_id= 0 AND `user_post`.is_deleted= 0 AND `user_post`.`shareby_id`= 0 AND `user_post`.`business_page_id`= 0 AND  `user_post`.status= 'Active'   AND  `lo`.`latitude` ='$lat' AND `lo`.`longitude` ='$lng' ");
   
        return $query->result_array();
 
    } 

    public function getExplore_maps_location_place_with_user_id($search_user_id,$lat,$lng)
    {
        $query = $this->db->query(" SELECT ( 3959 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS(  `gio_location`.`latitude` ) ) * COS( RADIANS(  `gio_location`.`longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS(  `gio_location`.`latitude` ) ) ) ) AS distance,users.username,IFNULL(gio_location.address_name, '') as address_name,gio_location.location_id,gio_location.latitude,gio_location.longitude,gio_location.address,gio_location.address,user_post.business_page_id,user_post.user_id,user_post.location_id,users.username  FROM user_post
         inner join gio_location on gio_location.location_id =user_post.location_id 
         inner join users on users.user_id = user_post.user_id
         Where  `user_post`.is_deleted= 0 AND `user_post`.`shareby_id`= 0 AND `user_post`.`business_page_id`= 0 AND  `user_post`.status= 'Active'  AND user_post.user_id = '$search_user_id' GROUP BY user_post.location_id  HAVING distance < 100 ORDER BY distance ");
        return $query->result_array();

        

    }

    public function getExploreMap($location_id) {
        $this->db->select('gl.latitude,gl.longitude,gl.address,gl.address_name,up.post_id,u.username,u.profile_pic,up.business_page_id'); 
        $this->db->from('user_post up');
        $this->db->join('users u','u.user_id = up.user_id');
        $this->db->join('gio_location gl','gl.location_id = up.location_id');
         //    // AND
         //        (
         //            user_post.user_id NOT IN (select follow_user_id from `follow_user` where user_id='".$user_id."' AND follow_user_id = `user_post`.`user_id`)
         //            ) 
         // $this->db->where_not_in('up.user_id','select follow_user_id from `follow_user` where user_id='".$user_id."' AND follow_user_id = `up`.`user_id`');
        $this->db->where('up.location_id',$location_id);
        $this->db->where('up.is_deleted',0);
        $this->db->where('up.business_page_id',0);
        $this->db->where('up.shareby_id',0); 
        $this->db->where('up.status','Active');
        $query = $this->db->get();
        return $query->result_array(); 
    }


    public function postImage($post_id) {
        $this->db->select('pi.file_path'); 
        $this->db->from('post_img pi');
        $this->db->where('pi.post_id',$post_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function search($keyword,$colum,$table,$data_select)
    { 
        $this->db->select($data_select);
        $this->db->or_like($colum);
        $this->db->from($table);
        $results=   $this->db->get();
        return $results->result_array();
    }


      public function searchchat($keyword,$colum,$table,$data_select,$user_id)
    { 
      

        $this->db->select($data_select);
        $this->db->where('user_id !=',$user_id);
        $this->db->group_start();
        $this->db->or_like($colum);
        $this->db->group_end();
        $this->db->from($table);
      
        $results=   $this->db->get();
        return $results->result_array();

    }
      public function defaulthash_tag()
    { 
      

        $this->db->select('word,tag_word_id'); 
            $this->db->from('tags u');
            $this->db->order_by("u.tag_word_id", "desc");
            $this->db->limit(10, 0);
            
            $query = $this->db->get();
            return $query->result_array();

    }
    

    public function defaultpleaselist($lat,$lng)
    { 

       $query = $this->db->query("SELECT lo.location_id ,lo.latitude,lo.longitude,lo.address,( 3959 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS(  `lo`.`latitude` ) ) * COS( RADIANS(  `lo`.`longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS(  `lo`.`latitude` ) ) ) ) AS distance

        FROM  `gio_location` `lo`  ORDER BY distance LIMIT 0 , 10");
 
        return $query->result_array();
    }


    public function defaultuserlist($user_id)
    {

        $this->db->select('follow_user_id'); 
        $this->db->from('follow_user fu');
        $this->db->where('fu.user_id',$user_id);
        $this->db->where('fu.status','Follow');
        $this->db->limit(10, 0);
        $query = $this->db->get();
        $form_titles=array('0');
        $fu=$query->result_array();
        
       foreach( $fu as $label){
            $form_titles[] = $label['follow_user_id'];
        }
        
        $this->db->select('user_id,u.username,u.full_name,u.full_name,profile_pic,account_type'); 
        $this->db->from('users u');
        $this->db->where_in('u.user_id', $form_titles);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function defaultuser()
        {
            $this->db->select('u.user_id,u.username,u.full_name,u.full_name,profile_pic'); 
            $this->db->from('users u');
            $this->db->order_by("u.user_id", "asc");
            $this->db->limit(10, 0);
            
            $query = $this->db->get();
            return $query->result_array();
        }


    public function get_user_post($sort='',$user_id,$user_id1,$type='')
    {
 
         if($sort == 'views')
        {
               $sort_condition = '`up`.`view_post` DESC ';

        }elseif ($sort == 'likes') {

            $sort_condition = '`post_likes` DESC';

        }elseif ($sort == 'latest') {

             $sort_condition = '`up`.`created` DESC ';
        }
        else{
            $sort_condition =  '`up`.`created` DESC ';
        }

        //, ( 3959 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS(  `lo`.`latitude` ) ) * COS( RADIANS(  `lo`.`longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS(  `lo`.`latitude` ) ) ) ) AS distance
        if($type=='yes'){
        $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as likes,(select count(post_comment.post_id) from post_comment where post_comment.post_id = up.post_id ) as comment, IFNULL(`lo`.`address`, '') as address,IFNULL(`lo`.`address_name`, '') as address_name,`u`.`user_id`,`u`.`username`,`u`.`full_name`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`shareby_id`, `up`.`post_date`,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                    FROM `user_post` `up` 
                                    left JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    left JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    WHERE `up`.`status` = 'Active' 
                                    AND `up`.is_deleted = 0 
                                    AND `u`.user_id ='$user_id'
                                    AND `up`.`business_page_id`=0
                                    AND `up`.`shareby_id` =0
                                    ORDER BY ".$sort_condition."");
        return $query->result_array();
        }else
        {
            if($this->isFollow($user_id,$user_id1)){
            $de = '';
            }else{
                $de =   "AND `u`.`account_type` = 'Public'"; 
            }
            $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as likes,(select count(post_comment.post_id) from post_comment where post_comment.post_id = up.post_id ) as comment, IFNULL(`lo`.`address`, '') as address,IFNULL(`lo`.`address_name`, '') as address_name,`u`.`user_id`,`u`.`username`,`u`.`full_name`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`shareby_id`, `up`.`post_date`,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                    FROM `user_post` `up` 
                                    left JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    left JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    WHERE `up`.`status` = 'Active'".$de." 
                                   
                                    AND `up`.is_deleted = 0 
                                    AND `u`.user_id ='$user_id'
                                    AND `up`.`business_page_id`=0
                                    AND `up`.`shareby_id` =0
                                    ORDER BY ".$sort_condition."");
        return $query->result_array();
        }
    }



    public function user_been_there($user_id,$user_id1,$type='')
    {
        if($type=='yes')
        {
        $query = $this->db->query(" SELECT `lo`.location_id,`lo`.latitude,`lo`.longitude,  `u`.`user_id`,`up`.`post_id`,`up`.`shareby_id`,IFNULL(`lo`.`address`, '') as address,IFNULL(`lo`.`address_name`, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                    FROM `user_post` `up` 
                                    INNER JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    WHERE  `up`.`status` = 'Active' 
                                    AND `up`.is_deleted= 0 
                                    AND `up`.shareby_id= 0 
                                    AND `up`.`business_page_id`=0
                                    AND `u`.user_id ='$user_id' 
                                    GROUP BY `lo`.location_id
                                    ");
        return $query->result_array();
    }else
    {
         if($this->isFollow($user_id,$user_id1)){
            $de = '';
            }else{
                $de =   "AND `u`.`account_type` = 'Public'"; 
            }
         $query = $this->db->query(" SELECT `lo`.location_id,`lo`.latitude,`lo`.longitude,  `u`.`user_id`,`up`.`post_id`,`up`.`shareby_id`,IFNULL(`lo`.`address`, '') as address,IFNULL(`lo`.`address_name`, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                    FROM `user_post` `up` 
                                    INNER JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    WHERE  `up`.`status` = 'Active' 
                                    AND `up`.is_deleted= 0 ".$de." 
                                  
                                    AND `up`.shareby_id= 0 
                                    AND `up`.`business_page_id`=0
                                    AND `u`.user_id ='$user_id' 
                                    GROUP BY `lo`.location_id
                                    ");
        return $query->result_array();

    }
        
    }
 

    function tagged_user_post($sort,$user_id,$user_id1,$other_user='')
    {
        if($sort == 'views')
        {
               $sort_condition = '`up`.`view_post` DESC ';

        }elseif ($sort == 'likes') {

            $sort_condition = '`post_likes` DESC';

        }elseif ($sort == 'latest') {

             $sort_condition = '`up`.`created` DESC ';
        }
        else{
            $sort_condition =  '`up`.`created` DESC ';
        }

        if($other_user =='yes'){
             if($this->isFollow($user_id,$user_id1)){

            $de = '';
            }else{
                $de =   "AND `u`.`account_type` = 'Public'"; 
            }
          
            $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as likes,(select count(post_comment.post_id) from post_comment where post_comment.post_id = up.post_id ) as comment, `u`.`user_id`,`u`.`username`,`u`.`full_name`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`shareby_id`, `up`.`post_date`,IFNULL(`lo`.`address_name`, '') as address_name,IFNULL(`lo`.`address`,'') as address,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                    FROM `user_post` `up` 
                                    LEFT JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    LEFT JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    LEFT JOIN tag_user ON `tag_user`.post_id = `up`.post_id
                                    WHERE `up`.`status` = 'Active' ".$de." 
                                  
                                    AND `up`.is_deleted= 0 
                                    AND `up`.shareby_id= 0 
                                    AND `up`.`business_page_id`=0
                                    AND `tag_user`.user_id = $user_id 
                                
                                    ORDER BY ".$sort_condition."");
            // echo " SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as likes,(select count(post_comment.post_id) from post_comment where post_comment.post_id = up.post_id ) as comment, `u`.`user_id`,`u`.`username`,`u`.`full_name`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`shareby_id`, `up`.`post_date`,IFNULL(`lo`.`address_name`, '') as address_name,IFNULL(`lo`.`address`,'') as address,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
            //                         FROM `user_post` `up` 
            //                         LEFT JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
            //                         LEFT JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
            //                         LEFT JOIN tag_user ON `tag_user`.post_id = `up`.post_id
            //                         WHERE `up`.`status` = 'Active' ".$de." 
                                  
            //                         AND `up`.is_deleted= 0 
            //                         AND `up`.shareby_id= 0 
            //                         AND `up`.`business_page_id`=0
            //                         AND `tag_user`.user_id = $user_id 
            //                         AND `tag_user`.permission = '1'
            //                         ORDER BY ".$sort_condition."";die;
    //   AND `tag_user`.permission = '1'
        }else
        {
            
           
            $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as likes,(select count(post_comment.post_id) from post_comment where post_comment.post_id = up.post_id ) as comment, `u`.`user_id`,`u`.`username`,`u`.`full_name`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`shareby_id`, `up`.`post_date`,IFNULL(`lo`.`address_name`, '') as address_name, IFNULL(`lo`.`address`,'') as address,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                    FROM `user_post` `up` 
                                    LEFT JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    LEFT JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    LEFT JOIN tag_user ON `tag_user`.post_id = `up`.post_id
                                    WHERE 
                                     `up`.`status` = 'Active' 
                                    AND `up`.is_deleted= 0 
                                    AND `up`.shareby_id= 0 
                                    AND `up`.`business_page_id`=0
                                    AND `tag_user`.user_id = $user_id 
                                    ORDER BY ".$sort_condition."");

        }
        return $query->result_array();

    }

     public function get_post_profile_gallery($user_id,$user_id1,$type=''){
            if($type=='yes')
            {
            $query = $this->db->query("SELECT user_post.post_id,post_img.file_path,post_img.file_type,post_img.video_path From user_post
            inner join users on  user_post.user_id = users.user_id  
            inner join post_img on user_post.post_id = post_img.post_id 
            where  `user_post`.is_deleted= 0  AND `user_post`.business_page_id= 0 AND  `user_post`.shareby_id= 0 AND `user_post`.status= 'Active'  AND `user_post`.user_id= '$user_id'
              ");
 
        return $query->result_array();
        }else
        {
              
             if($this->isFollow($user_id,$user_id1)){
            $de = '';
            }else{
                $de =   "AND users.`account_type` = 'Public'"; 
            }
          
             $query = $this->db->query("SELECT user_post.post_id,post_img.file_path,post_img.file_type,post_img.video_path From user_post
            inner join users on  user_post.user_id = users.user_id  
            inner join post_img on user_post.post_id = post_img.post_id 
            where  `user_post`.is_deleted= 0 ".$de." AND `user_post`.business_page_id= 0 AND  `user_post`.shareby_id= 0 AND `user_post`.status= 'Active'  AND `user_post`.user_id= '$user_id'
              ");
 
        return $query->result_array();
        }
        
    } 

    public function remove_tag($post_id,$user_id,$table)
    {
        $arr = array('post_id' => $post_id,'user_id' => $user_id);
        $this->db->where($arr);
        $this->db->delete($table);

        if($this->db->affected_rows() >0) 
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function dont_allow_tag($post_id,$user_id,$table)
    { 
        $this->remove_tag($post_id,$user_id,$table);
        $date = date('Y-m-d H:i:s');
        $data = array('post_id' => $post_id,'user_id'=>$user_id,'created'=>$date);
        $query = $this->db->insert('dont_allow_tag', $data);   
        return  $this->db->insert_id();
    }
    public function allow_tag($post_id,$user_id,$table)
    { 
        $this->remove_tag($post_id,$user_id,$table);
        $data=array('status'=>'allow','permission'=>1);
        $this->db->where('post_id',$post_id);
        $this->db->update('tag_user',$data);
        return  'updated';
    }
     

    public function user_list_for_tag($condition,$post_id,$user_id)
    {
        $query = $this->db->query("SELECT $condition FROM users WHERE user_id NOT IN (SELECT user_id FROM `dont_allow_tag` where post_id=$post_id) AND `user_id` != $user_id  ORDER By user_id Desc ");
       return $data= $query->result_array();
    }
    public function page_list_for_tag($condition,$post_id,$page_id)
    {
        $query = $this->db->query("SELECT $condition FROM business_page WHERE business_page_id NOT IN (SELECT page_id FROM `dont_allow_tag_for_page` where post_id=$post_id) AND `business_page_id` != $page_id  ORDER By business_page_id Desc ");
       return $data= $query->result_array();
    }


    public function map_location_for_been_there($lat,$long)
    {
        $query = $this->db->query(" SELECT  lo.location_id ,lo.latitude,lo.longitude,lo.address,IFNULL(`lo`.`address_name`, '') as address_name, ( 3959 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS(  `lo`.`latitude` ) ) * COS( RADIANS(  `lo`.`longitude` ) - RADIANS(  '$long' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS(  `lo`.`latitude` ) ) ) ) AS distance  FROM  `gio_location` `lo`  HAVING distance < 25  ORDER BY distance");
        return $query->result_array();
    }

    public function getEditPageInfo($page_id) { 
        $this->db->select(" 'sub_category_name' as sub_category_name,`bp`.`business_page_id`,`bp`.`user_id`,`bp`.`business_name`,`bp`.`pageMessageBadgeCount`,`bp`.`business_full_name`,`bp`.`business_image`,`bp`.`category_id`,`bp`.`sub_category_id`,`bp`.`category_id2`,`bp`.`sub_category_id2`,`bp`.`category_id3`,`bp`.`sub_category_id3`,`bp`.`description`,`bp`.`email`,`bp`.`mobile`,`bp`.`address_1`,`bp`.`address_2`,`bp`.`city_id`,`bp`.`user_id`,`bp`.`user_id`,`bp`.`user_id`,`bp`.`user_id`,`bp`.`user_id`,`bp`.`user_id`,`bp`.`user_id`,`bp`.`country_id`,`bp`.`state_id`,`bp`.`zipcode`,`bp`.`website`,IFNULL(`bp`.`latitude`, '' )as latitude,IFNULL(`bp`.`longitude`, '' )as longitude,`bp`.`status`,`bp`.`push_notification`,`bp`.`disply_rating`,`bp`.`disply_rating`,IFNULL(`wh`.`sunday_from`, '' )as sunday_from,IFNULL(`wh`.`sunday_to`, '' )as sunday_to,IFNULL(`wh`.`sunday24hours`, '' )as sunday24hours,IFNULL(`wh`.`sundayWorking`, '' )as sundayWorking,IFNULL(`wh`.`monday_from`, '' )as monday_from,IFNULL(`wh`.`monday_to`, '' )as monday_to,IFNULL(`wh`.`monday24hours`, '' )as monday24hours,IFNULL(`wh`.`mondayWorking`, '' )as mondayWorking,IFNULL(`wh`.`tuesday_from`, '' )as tuesday_from,IFNULL(`wh`.`tuesday_to`, '' )as tuesday_to,IFNULL(`wh`.`tuesday24hours`, '' )as tuesday24hours,IFNULL(`wh`.`tuesdayWorking`, '' )as tuesdayWorking,IFNULL(`wh`.`wednesday_from`, '' )as wednesday_from,IFNULL(`wh`.`wednesday_to`, '' )as wednesday_to,IFNULL(`wh`.`wednesday24hours`, '' )as wednesday24hours,IFNULL(`wh`.`wednesdayWorking`, '' )as wednesdayWorking,,IFNULL(`wh`.`thursday_from`, '' )as thursday_from,IFNULL(`wh`.`thursday_to`, '' )as thursday_to,IFNULL(`wh`.`thursday24hours`, '' )as thursday24hours,IFNULL(`wh`.`thursdayWorking`, '' )as thursdayWorking,IFNULL(`wh`.`friday_from`, '' )as friday_from,IFNULL(`wh`.`friday_to`, '' )as friday_to,IFNULL(`wh`.`friday24hours`, '' )as friday24hours,IFNULL(`wh`.`fridayWorking`, '' )as fridayWorking,IFNULL(`wh`.`saturday_from`, '' )as saturday_from,IFNULL(`wh`.`saturday_to`, '' )as saturday_to,IFNULL(`wh`.`saturday24hours`, '' )as saturday24hours,IFNULL(`wh`.`saturdayWorking`, '' )as saturdayWorking,
            IFNULL(`countries`.`name`, '' ) as country_name,
            IFNULL(`states`.`name`, '' ) as state_name,
            IFNULL(`cities`.`name`, '' ) as city_name"
            ); 
        $this->db->from('business_page bp');
        $this->db->join('working_hours wh','wh.business_page_id = bp.business_page_id','left');
        $this->db->join('countries','countries.id = bp.country_id','left');
        $this->db->join('states','states.id = bp.state_id','left');
        $this->db->join('cities','cities.id = bp.city_id','left');

        $this->db->where('bp.business_page_id',$page_id);
       
        $query = $this->db->get();
        return $query->row_array();
    }



    public function panding_details($user_id)
    {    
         $query = $this->db->query("select 'yes' as is_follow_request, follow_user.user_id,follow_user.status,users.user_id,follow_user.created,users.profile_pic,users.username,users.full_name from `follow_user` inner join users on follow_user.user_id =users.user_id   where follow_user.follow_user_id = $user_id AND `follow_user`.status ='Pending'");
        return $data= $query->result_array();
    }

    public function request_action($user_id,$follower_id,$action)
    {
        if($action =='accept')
        {
            $query = $this->db->query("UPDATE `follow_user` SET `status`='Follow' WHERE `follow_user_id`='$user_id' AND `user_id`='$follower_id'");
        }else
        {
        $query = $this->db->query("DELETE FROM `follow_user` WHERE `follow_user_id` ='$user_id' AND `user_id` = '$follower_id'");
        }
         if($this->db->affected_rows() >0) 
            {
                return true;
            }
            else
            {
                return false;
            }
    }

    public function sender_pending_request($user_id)
    {

        $query = $this->db->query("select  'yes' as is_follow_request, follow_user.follow_id,follow_user.user_id,follow_user.status,follow_user.created,users.user_id,users.profile_pic,users.username,users.full_name from `follow_user` inner join users on follow_user.follow_user_id =users.user_id   where follow_user.user_id = $user_id ");
        return $data= $query->result_array();

    }

    public function get_requested_chat_list($user_id)
    {
        //echo "select  'no' as is_follow_request,  '' as status, chating_request.created,users.user_id,users.profile_pic,users.username,users.full_name from `chating_request` inner join users on chating_request.receiver_user =users.user_id   where chating_request.sender_user = $user_id AND chating_request.status ='1'";die;
         $query = $this->db->query("select  'no' as is_follow_request,  '' as status, chating_request.created,users.user_id,users.profile_pic,users.username,users.full_name from `chating_request` inner join users on chating_request.receiver_user =users.user_id   where chating_request.sender_user = $user_id AND chating_request.status ='1'");
        return $data= $query->result_array();

    }

    public function add_update_key($user_id,$pin)
    { 
        $query = $this->db->query("UPDATE `users` SET `user_pin`='$pin' WHERE `user_id`='$user_id' ");
        if($this->db->affected_rows() >0) 
            {
                return true;
            }
            else
            {
                return false;
            }
    }

    public function add_update_pin($page_id,$pin)
    { 
        $query = $this->db->query("UPDATE `business_page` SET `page_pin`='$pin' WHERE `business_page_id`='$page_id' ");
        if($this->db->affected_rows() >0) 
            {
                return true;
            }
            else
            {
                return false;
            }
    }


    function get_business_page($user_id)
    {

        $query = $this->db->query("select 
            business_page.business_page_id, 
            business_page.business_name,
            business_page.business_full_name, 
            business_page.address_1,
            business_page.address_2,
            IFNULL(cities.name,'') as city_name,
            IFNULL(states.name,'') as state_name,
            IFNULL(countries.name,'') as country_name 
            from
            business_page 
            left join countries on countries.id = business_page.country_id 
            left join states on business_page.state_id = states.id 
            left join cities on  business_page.city_id =cities.id 
            where business_page.user_id='$user_id'
            AND business_page.is_deleted=0");
        return $data= $query->result_array();
    }




     public function get_user_post_for_page($sort='',$user_id,$page_id,$type='')
        {
     
             if($sort == 'views')
            {
                   $sort_condition = '`up`.`view_post` DESC ';

            }elseif ($sort == 'likes') {

                $sort_condition = '`post_likes` DESC';

            }elseif ($sort == 'latest') {

                 $sort_condition = '`up`.`created` DESC ';
            }
            else{
                $sort_condition =  '`up`.`created` DESC ';
            }

            if($type=='yes')
            {
            $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as likes,(select count(post_comment.post_id) from post_comment where post_comment.post_id = up.post_id ) as comment, IFNULL(`lo`.`address`, '') as address,`u`.`user_id`,`u`.`username`,`u`.`full_name`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`shareby_id`, `up`.`post_date`,IFNULL(`lo`.`address_name`, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude,`up`.`business_page_id`
                                        FROM `user_post` `up` 
                                        left JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                        left JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                        WHERE `up`.`status` = 'Active' 
                                        AND `up`.is_deleted = 0 
                                        AND `u`.user_id ='$user_id'
                                        AND `up`.`shareby_id` =0
                                        AND `up`.`is_page_shareby` =0
                                        AND `up`.`business_page_id`='$page_id'
                                        ORDER BY ".$sort_condition."");
            return $query->result_array();
        }else
        {
             $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as likes,(select count(post_comment.post_id) from post_comment where post_comment.post_id = up.post_id ) as comment, IFNULL(`lo`.`address`, '') as address,`u`.`user_id`,`u`.`username`,`u`.`full_name`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`shareby_id`, `up`.`post_date`,IFNULL(`lo`.`address_name`, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude,`up`.`business_page_id`
                                        FROM `user_post` `up` 
                                        left JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                        left JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                        WHERE `up`.`status` = 'Active' 
                                        AND `u`.`account_type` = 'Public'
                                        AND `up`.is_deleted = 0 
                                        AND `u`.user_id ='$user_id'
                                        AND `up`.`shareby_id` =0
                                        AND `up`.`is_page_shareby` =0
                                        AND `up`.`business_page_id`='$page_id'
                                        ORDER BY ".$sort_condition."");
            return $query->result_array();
            
        }
        }


    public function user_been_there_for_page($user_id,$page_id,$type='')
    {
        if($type=='yes'){
            $query = $this->db->query(" SELECT `lo`.location_id,`lo`.latitude,`lo`.longitude,  `u`.`user_id`,`up`.`post_id`,`up`.`shareby_id`,IFNULL(`lo`.`address`, '') as address, IFNULL(`lo`.`address_name`, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                    FROM `user_post` `up` 
                                    INNER JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    WHERE 
                                     `up`.`status` = 'Active' 
                                    AND `up`.is_deleted= 0 
                                    AND `up`.shareby_id= 0 
                                    AND `u`.user_id ='$user_id' 
                                    AND `up`.`business_page_id`='$page_id'
                                    GROUP BY `lo`.location_id
                                    ");
        return $query->result_array();
    }else
    {
           $query = $this->db->query(" SELECT `lo`.location_id,`lo`.latitude,`lo`.longitude,  `u`.`user_id`,`up`.`post_id`,`up`.`shareby_id`,IFNULL(`lo`.`address`, '') as address, IFNULL(`lo`.`address_name`, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                    FROM `user_post` `up` 
                                    INNER JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                    INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                    WHERE `u`.`account_type` = 'Public'
                                    AND `up`.`status` = 'Active' 
                                    AND `up`.is_deleted= 0 
                                    AND `up`.shareby_id= 0 
                                    AND `u`.user_id ='$user_id' 
                                    AND `up`.`business_page_id`='$page_id'
                                    GROUP BY `lo`.location_id
                                    ");
        return $query->result_array();

    }
        
    }

    function tagged_user_post_for_page($sort,$user_id,$page_id,$other_user='')
    {
        if($sort == 'views')
        {
               $sort_condition = '`up`.`view_post` DESC ';

        }elseif ($sort == 'likes') {

            $sort_condition = '`post_likes` DESC';

        }elseif ($sort == 'latest') {

             $sort_condition = '`up`.`created` DESC ';
        }
        else{
            $sort_condition =  '`up`.`created` DESC ';
        }

        if($other_user=='yes'){
        
            $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as likes,(select count(post_comment.post_id) from post_comment where post_comment.post_id = up.post_id ) as comment, `u`.`user_id`,`u`.`username`,`u`.`full_name`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`shareby_id`, `up`.`post_date`,IFNULL(`lo`.`address_name`, '') as address_name,IFNULL( `lo`.`address`,'') as address ,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                        FROM `user_post` `up` 
                                        LEFT JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                        INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                        INNER JOIN tag_page ON `tag_page`.post_id = `up`.post_id
                                        WHERE `u`.`account_type` = 'Public'
                                        AND `up`.`status` = 'Active' 
                                        AND `up`.is_deleted = 0 
                                        AND `up`.shareby_id = 0 
                                        AND `tag_page`.status = '1' 
                                        AND `tag_page`.page_id = '$page_id'
                                        ORDER BY ".$sort_condition."");
        }else
        {

             $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as likes,(select count(post_comment.post_id) from post_comment where post_comment.post_id = up.post_id ) as comment, `u`.`user_id`,`u`.`username`,`u`.`full_name`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`,`up`.`shareby_id`, `up`.`post_date`,IFNULL( `lo`.`address`,'') as address ,IFNULL(`lo`.`address_name`, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude
                                        FROM `user_post` `up` 
                                        LEFT JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                                        INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                                        INNER JOIN tag_page ON `tag_page`.post_id = `up`.post_id
                                        WHERE 
                                         `up`.`status` = 'Active' 
                                        AND `up`.is_deleted= 0 
                                        AND `up`.shareby_id= 0  
                                        AND `tag_page`.page_id = '$page_id' 
                                        ORDER BY ".$sort_condition."");
        }
        return $query->result_array();
        

    }

    public function get_page_gallery($page_id)
    {

        $query = $this->db->query("SELECT `business_img`.business_img_id,`business_img`.business_page_id,`business_img`.file_path,`business_img`.is_star from `business_img` where  `business_img`.business_page_id='$page_id'");
        return $query->result_array();
    }

    public function get_pagestar_gallery($page_id)
    {

        $query = $this->db->query("SELECT `business_img`.business_img_id,`business_img`.business_page_id,`business_img`.file_path,`business_img`.is_star from `business_img` where  `business_img`.business_page_id='$page_id' ORDER BY  `is_star` ");
        return $query->result_array();
    }

    public function pagepostFollowingList($user_id)
    { 
        $this->db->select('user_id'); 
        $this->db->from('follow_page fu');
        $this->db->where('fu.follow_page_id',$user_id);
        $this->db->where('fu.status','Follow');
        $query = $this->db->get();
        $fu=$query->result_array();
        $form_titles=array('0');
        foreach( $fu as $label){
            $form_titles[] = $label['user_id'];
        }
      
        $this->db->select('user_id,u.username,u.full_name,u.full_name,profile_pic'); 
        $this->db->from('users u');
        $this->db->where_in('u.user_id', $form_titles);
        
        $query = $this->db->get();
        return $query->result_array();
 
    }

    public function pageisFollow($follow_user_id,$user_id)
    { 
        $this->db->select('fu.status'); 
        $this->db->from('follow_page fu');
        $this->db->where('fu.user_id',$user_id);
        $this->db->where('fu.follow_page_id',$follow_user_id);
        $query = $this->db->get();
        return $query->result_array();
    }


     public function get_page_profile_gallery($page_id){
   
            $query = $this->db->query("SELECT user_post.post_id,post_img.file_path,post_img.file_type,post_img.video_path From user_post
            inner join business_page on  business_page.business_page_id = user_post.business_page_id  
            inner join post_img on user_post.post_id = post_img.post_id 
            where  `user_post`.is_deleted= 0  AND `user_post`.shareby_id= 0 AND `user_post`.status= 'Active'  AND `user_post`.`business_page_id`= '$page_id'
              ");
 
        return $query->result_array();
        
    } 

    public function pageFollowingList($page_id,$user_id)
    { 

        $query = $this->db->query("SELECT users.user_id,users.username,users.full_name,users.full_name,users.profile_pic from follow_page inner join users on users.user_id = follow_page.user_id where follow_page.follow_page_id = '$page_id'  ");
 //AND follow_page.user_id <> $user_id
       return $query->result_array();
        
 
    }

    public function remove_page_tag($post_id,$page_id,$table)
    {

        $arr = array('post_id' => $post_id,'page_id' => $page_id);
        $this->db->where($arr);
        $this->db->delete($table);

        if($this->db->affected_rows() >0) 
        {
            return true;
        }
        else
        {
            return false;
        } 
    }

    public function dont_allow_tag_for_page($post_id,$page_id)
    { 
        $this->remove_page_tag($post_id,$page_id,'tag_page');
        $date = date('Y-m-d H:i:s');
        $data = array('post_id' => $post_id,'page_id'=>$page_id,'created'=>$date);
        $query = $this->db->insert('dont_allow_tag_for_page', $data);   
        return  $this->db->insert_id();
    }
    public function allow_tag_for_page($post_id,$page_id)
    {
        $this->remove_page_tag($post_id,$page_id,'dont_allow_tag_for_page');
        $this->db->select('*'); 
        $this->db->from('tag_page');
        $this->db->where(array('post_id'=>$post_id,'page_id'=>$page_id));
        $query = $this->db->get();
        $num = $query->num_rows();  
        $sql = $this->db->last_query();
 
        if($num > 0)
        {      
            $date = date('Y-m-d H:i:s');
            $data = array('post_id' => $post_id,'page_id'=>$page_id,'created'=>$date,'status'=>'1');
            $this->db->update('tag_page',$data);
            return  'updated';
        } else
        {
            $date = date('Y-m-d H:i:s');
            $data = array('post_id' => $post_id,'page_id'=>$page_id,'created'=>$date,'status'=>'1');
            $query = $this->db->insert('tag_page', $data);   
            return  $this->db->insert_id();
        }
    }


    public function updateverication($page_id)
    {


        $data=array('verification'=>2);
        $this->db->where('business_page_id',$page_id);
        $this->db->update('business_page',$data);
        return 'updated';
    }
    public function checkauthpage($page_id,$user_id)
    {
        $this->db->select('*');
        $this->db->from('business_page');
        $this->db->where(array('business_page_id'=>$page_id,'user_id'=>$user_id));

        $query = $this->db->get();
       $jay =   $this->db->last_query();
       print_r($jay);
        $num = $query->num_rows();  

    }


    public function get_multiple_statecity($table,$field,$where)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where_in($field,$where);
        $query = $this->db->get(); 
     
        return $query->result_array();
    }

    public function get_records($table,$colum,$where)
    {
        $this->db->select($colum);
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get(); 
     
        return $query->result_array();
    }

    public function subscription_user_lists($page_id)
    {
        $data = date('Y-m-d');
          $query = $this->db->query("SELECT subscription_user.subscription_user_id,subscription_user.end_date,subscription_user.offers,subscription_plan.is_free from subscription_user inner join subscription_plan on subscription_user.subscription_plan_id = subscription_plan.subscription_plan_id where subscription_user.page_id = '$page_id' AND subscription_user.is_deleted = '0' AND end_date >= $data ");
        return $query->row_array(); 

    }   
    public function getoffers($page_id)
    {
            $data = date('Y-m-d');
          $query = $this->db->query("SELECT bo.business_offers,bo.sort,bo.business_page_id,bo.offers_type,bo.offers_title,bo.description,bo.country_id,bo.state_id,bo.city_id,su.end_date as page_expired_date  FROM  business_offers bo INNER JOIN subscription_user su ON su.page_id = bo.business_page_id WHERE bo.business_page_id = '$page_id' AND bo.is_deleted = '0' AND su.is_deleted = '0' AND su.end_date >= $data ORDER BY bo.sort ASC ");
        return $query->result_array(); 

    }  
     public function mygetoffers($page_id=0,$user_id=0)
    {
        if($page_id!=0)
        { 
          $query = $this->db->query("SELECT bo.business_offers,bo.sort,bo.business_page_id,bo.offers_type,bo.offers_title,bo.description,bo.country_id,bo.state_id,bo.city_id,su.end_date as page_expired_date  FROM  business_offers bo INNER JOIN subscription_user su ON su.page_id = bo.business_page_id WHERE bo.business_page_id = '$page_id' AND bo.is_deleted = '0'  AND bo.created_by_user = '$user_id' AND su.is_deleted = '0' ORDER BY bo.sort ASC ");
        }else
        {
             $query = $this->db->query("SELECT bo.business_offers,bo.sort,bo.business_page_id,bo.offers_type,bo.offers_title,bo.description,bo.country_id,bo.state_id,bo.city_id,su.end_date as page_expired_date  FROM  business_offers bo INNER JOIN subscription_user su ON su.page_id = bo.business_page_id WHERE su.is_deleted = '0' AND bo.created_by_user = '$user_id' ORDER BY bo.sort ASC ");
        }
        return $query->result_array(); 

    }
    public function get_near_by_page($category_id,$lat,$lng)
    {
      
           $query = $this->db->query(" SELECT  'local' as data_type,business_page.sub_category_id,business_page.sub_category_id2,business_page.sub_category_id3,business_page.from_price,business_page.to_price, business_page.from_price,business_page.to_price, business_page.business_name,business_page.business_full_name,  business_page.status,business_page.business_page_id as page_id,business_page.user_id,business_page.business_image,business_page.verification,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude,business_page.sponsored,  categories.name as category_name,sub_categories.name as sub_category_name, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` left join categories on categories.category_id = business_page.category_id left join sub_categories on sub_categories.sub_category_id = business_page.sub_category_id where  business_page.is_deleted = 0 AND business_page.status != 'unverified' AND business_page.latitude !='' AND  business_page.longitude !='' AND (

                 business_page.category_id=$category_id  OR  find_in_set($category_id,business_page.sub_category_id)
                OR business_page.category_id2=$category_id OR  find_in_set($category_id,business_page.sub_category_id2)
                OR  business_page.category_id3=$category_id OR  find_in_set($category_id,business_page.sub_category_id3)
                 
                 )

                  HAVING distance < 160 ORDER BY distance ASC limit 30");

        return $query->result_array();


    } 

    public function getAllsponsorpage($category_id,$lat,$lng)
    {
      
           $query = $this->db->query(" SELECT  'local' as data_type,business_page.sub_category_id,business_page.sub_category_id2,business_page.sub_category_id3, business_page.sub_category_id2,business_page.sub_category_id3,business_page.from_price,business_page.to_price, business_page.from_price,business_page.to_price, business_page.business_name,business_page.business_full_name,  business_page.status,business_page.business_page_id as page_id,business_page.user_id,business_page.business_image,business_page.verification,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude,business_page.sponsored,  categories.name as category_name,sub_categories.name as sub_category_name, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` left join categories on categories.category_id = business_page.category_id left join sub_categories on sub_categories.sub_category_id = business_page.sub_category_id where business_page.sponsored = 'yes' AND  business_page.is_deleted = 0 AND business_page.status != 'unverified' AND business_page.latitude !='' AND  business_page.longitude !='' AND (

                         business_page.category_id=$category_id  OR  find_in_set($category_id,business_page.sub_category_id)
                OR business_page.category_id2=$category_id OR  find_in_set($category_id,business_page.sub_category_id2)
                OR  business_page.category_id3=$category_id OR  find_in_set($category_id,business_page.sub_category_id3)



                    ) HAVING distance < 160 ORDER BY distance ASC ");

        return $query->result_array();


    } 

    public function get_near_by_page_with_place($lat,$lng)
    {
           $query = $this->db->query(" SELECT business_page.sub_category_id,business_page.sub_category_id2,business_page.sub_category_id3,business_page.business_full_name,business_page.from_price,business_page.to_price, business_page.from_price,business_page.to_price, business_page.business_name, business_page.status,business_page.business_page_id as page_id,business_page.user_id,business_page.business_image,business_page.verification,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude,business_page.sponsored,  categories.name as category_name,sub_categories.name as sub_category_name, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` left join categories on categories.category_id = business_page.category_id left join sub_categories on sub_categories.sub_category_id = business_page.sub_category_id where  business_page.is_deleted = 0 AND business_page.status != 'unverified' AND business_page.latitude !='' AND  business_page.longitude !='' HAVING distance < 160 ORDER BY distance ASC LIMIT 30");
        return $query->result_array();

    } 


    public function all_sponsoredPage($lat,$lng,$sponsored='no',$limit,$offset=0)
    {
        $query = $this->db->query(" SELECT  business_page.sub_category_id,business_page.sub_category_id2,business_page.sub_category_id3,business_page.from_price,business_page.to_price, business_page.from_price,business_page.to_price, business_page.business_name, business_page.status,business_page.business_page_id as page_id,business_page.user_id,business_page.business_image,business_page.verification,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude,business_page.sponsored,  categories.name as category_name,sub_categories.name as sub_category_name, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` left join categories on categories.category_id = business_page.category_id left join sub_categories on sub_categories.sub_category_id = business_page.sub_category_id where business_page.sponsored = '$sponsored' AND  business_page.is_deleted = 0 AND business_page.status != 'unverified' AND business_page.latitude !='' AND  business_page.longitude !=''  HAVING distance < 160 ORDER BY distance ASC LIMIT $offset,$limit ");
        return $query->result_array();

    } 


    public function get_rating_avg($page_id)
    {
        $query = $this->db->query(" SELECT  round(IFNULL(AVG(rating),'0'),1) as rating from `rating_page`  where page_id = '$page_id'");
        return $query->row_array();
    }


    public function get_near_by_offer($category_id,$lat,$lng)
    {

        $query = $this->db->query(" SELECT business_page.sub_category_id,business_page.sub_category_id2,business_page.sub_category_id3,business_page.business_full_name as business_name,business_page.status,business_page.sponsored,business_page.user_id,business_offers.business_offers,business_offers.sort,business_offers.country_id,business_offers.state_id,business_offers.city_id,business_offers.offers_type,business_page.business_page_id as page_id,business_page.status,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude, categories.name as category_name,sub_categories.name as sub_category_name, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` left join categories on categories.category_id = business_page.category_id left join sub_categories on sub_categories.sub_category_id = business_page.sub_category_id left join business_offers  on business_page.business_page_id =business_offers.business_page_id 

            inner join subscription_user on subscription_user.page_id = business_page.business_page_id 
            where  
          subscription_user.is_deleted = '0' AND subscription_user.end_date >= NOW() AND

         business_offers.is_deleted = '0' AND business_page.is_deleted = '0' AND business_page.latitude !='' AND  business_page.longitude !='' AND  (

                  business_page.category_id=$category_id  OR  find_in_set($category_id,business_page.sub_category_id)
                OR business_page.category_id2=$category_id OR  find_in_set($category_id,business_page.sub_category_id2)
                OR  business_page.category_id3=$category_id OR  find_in_set($category_id,business_page.sub_category_id3)


             ) HAVING distance < 160   ORDER BY distance ASC  ");
        return $query->result_array();

    } 
   public function get_near_by_offer_with_place($lat,$lng)
    {

        $query = $this->db->query(" SELECT business_page.sub_category_id,business_page.sub_category_id2,business_page.sub_category_id3,business_page.business_full_name as business_name,business_page.status,business_page.sponsored,business_page.user_id,business_offers.business_offers,business_offers.sort,business_offers.country_id,business_offers.state_id,business_offers.city_id,business_offers.offers_type,business_page.business_page_id as page_id,business_page.status,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude, categories.name as category_name,sub_categories.name as sub_category_name, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` left join categories on categories.category_id = business_page.category_id left join sub_categories on sub_categories.sub_category_id = business_page.sub_category_id left join business_offers  on business_page.business_page_id =business_offers.business_page_id 
          inner join subscription_user on subscription_user.page_id = business_page.business_page_id 

         where  subscription_user.is_deleted = '0' AND subscription_user.end_date >= NOW() AND  business_offers.is_deleted = '0' AND business_page.is_deleted = '0'  AND business_page.latitude !='' AND  business_page.longitude !='' HAVING distance < 160   ORDER BY distance ASC  ");
        return $query->result_array();

    } 

    public function get_near_by_page_with_filter($lat,$lng,$sponsored='no',$limit,$offet=0,$rating='',$nearest='',$categories='')
    {
         
        if(!empty($rating) && empty($nearest))
        {
            $order = " HAVING distance < 160 ORDER BY (Select  IFNULL(AVG(rating),'0') from rating_page where  business_page.business_page_id=rating_page.page_id) DESC";
        }elseif(!empty($nearest)  && empty($rating))
        {

            $order  = " HAVING distance < 160 ORDER BY distance ASC";
        }elseif(!empty($nearest)  && !empty($rating))
        {
            $order = "HAVING distance < 160 ORDER BY distance ASC,(Select  IFNULL(AVG(rating),'0') from rating_page where  business_page.business_page_id=rating_page.page_id) DESC";
        }else
        {
            $order='';
        }
        $where= '';
        if(!empty($categories))
        {
            $cate = explode(',', $categories);
              $count = count($cate);
              $c=1;
              $where .=' AND (';
            foreach ($cate as $category_id) {
                if($c!=1){
                    $where  .=' OR';
                }  
                $where .= " business_page.category_id='$category_id'"; 
                $c++;
            } 
             $where .=')';
            
        } 

 

        $query = $this->db->query(" SELECT business_page.sub_category_id2,business_page.sub_category_id3,business_page.from_price,business_page.to_price, IFNULL(round((Select AVG(rating) from rating_page where  business_page.business_page_id=rating_page.page_id),'1'),0) as rating ,  business_page.business_name,business_page.sponsored, business_page.status,business_page.business_page_id as page_id,business_page.user_id,business_page.business_image,business_page.verification,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude,business_page.sponsored, categories.name as category_name,sub_categories.name as sub_category_name, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` 
            left join categories on categories.category_id = business_page.category_id  left join sub_categories on sub_categories.sub_category_id = business_page.sub_category_id  where  business_page.sponsored = '$sponsored' AND business_page.is_deleted = 0  AND business_page.status != 'unverified' AND  business_page.latitude !='0' AND  business_page.longitude !='0' AND  business_page.latitude !='' AND  business_page.longitude !='' $where $order LIMIT $offset,$limit   ");
        
        return $query->result_array();

    } 

    public function get_near_by_offer_with_filter($lat,$lng,$rating='',$nearest='',$categories='')
    {

        if(!empty($rating) && empty($nearest))
        {
            $order = "HAVING distance < 160 ORDER BY (Select  IFNULL(AVG(rating),'0') from rating_page where  business_page.business_page_id=rating_page.page_id) DESC";
        }elseif(!empty($nearest)  && empty($rating))
        {
            $order  = "HAVING distance < 160 ORDER BY distance ASC";

        }elseif(!empty($nearest)  && !empty($rating))
        {
            $order = "HAVING distance < 160 ORDER BY distance ASC,(Select  IFNULL(AVG(rating),'0') from rating_page where  business_page.business_page_id=rating_page.page_id) DESC";
        }
        else
        {
            $order='';
        }
        $where= '';
        if(!empty($categories))
        {
            $cate = explode(',', $categories);
              $count = count($cate);
              $c=1;
              $where .=' AND (';
            foreach ($cate as $category_id) {
                if($c!=1){
                    $where  .=' OR';
                }  
                $where .= " business_page.category_id='$category_id'"; 
                $c++;
            } 
             $where .=')';
            
        } 

        $query = $this->db->query(" SELECT business_page.sponsored,business_page.sub_category_id2,business_page.sub_category_id3, IFNULL(round((Select AVG(rating) from rating_page where  business_page.business_page_id=rating_page.page_id),'1'),0) as rating ,  categories.category_id,business_page.business_name,business_page.user_id,business_offers.business_offers,business_offers.sort,business_offers.country_id,business_offers.state_id,business_offers.city_id,business_offers.offers_type,business_page.business_page_id as page_id,business_page.status,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude, categories.name as category_name,sub_categories.name as sub_category_name, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` 
            left join categories on categories.category_id = business_page.category_id 
            left join sub_categories on sub_categories.sub_category_id = business_page.sub_category_id 
            left join business_offers  on business_page.business_page_id =business_offers.business_page_id 
            where  business_offers.is_deleted = '0' AND business_page.is_deleted = '0' AND business_offers.page_expired_date >= '".DATE('Y-m-d')."' AND  business_page.latitude !='0' AND  business_page.longitude !='0' AND business_page.latitude !='' AND  business_page.longitude !=''  $where $order  ");
        return $query->result_array();

    }  

    public function search_business($keyword='',$type,$lat='',$lng='')
    {  
        if(empty($keyword))
        {

            if($type=='places'){  

                 $query = $this->db->query(" SELECT  business_page.business_full_name, business_page.sub_category_id, business_page.sub_category_id2,business_page.sub_category_id3,business_page.sponsored,business_page.from_price,business_page.from_price,business_page.to_price, 'local' as data_type, business_page.business_name, business_page.latitude, business_page.longitude,business_page.business_page_id ,business_page.user_id,business_page.address_1,business_page.address_2, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page`  where business_page.is_deleted = 0 AND business_page.status != 'unverified' AND  business_page.latitude !='0' AND business_page.latitude !='' AND  business_page.longitude !='0' AND business_page.longitude !='' GROUP BY business_page.latitude  HAVING distance < 160 ORDER BY distance ASC limit 30");
            }else
            {
                $query = $this->db->query(" SELECT business_page.business_full_name,business_page.sub_category_id, business_page.sub_category_id2,business_page.sub_category_id3, business_page.from_price,business_page.to_price, 'local' as data_type, IFNULL(round((Select AVG(rating) from rating_page where  business_page.business_page_id=rating_page.page_id),'1'),0) as rating , business_page.business_name, business_page.status,business_page.business_page_id as page_id,business_page.user_id,business_page.business_image,business_page.verification,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude,business_page.sponsored,IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` where  business_page.is_deleted = 0 AND business_page.status != 'unverified' AND   business_page.latitude !='' AND  business_page.longitude !='' AND business_page.latitude !='0' AND  business_page.longitude !='0'   HAVING distance < 160  ORDER BY distance limit 30");
            }
        }else
        { 
            if($type=='places'){  
                // $this->db->where('status !=','unverified');
                // $this->db->where('is_deleted',0);
                // $this->db->where('latitude !=',0);
                // $this->db->where('latitude !=','');
                // $this->db->where('longitude !=',0);
                // $this->db->where('longitude !=',0);
                // $this->db->group_start();
                // $this->db->where('address_1 !=','');
                // $this->db->or_where('address_2 !=','');
                // $this->db->group_end();
                // $this->db->select('business_page.sponsored,business_name,latitude,longitude,business_page_id,user_id,address_1,address_2,business_full_name,sub_category_id,sub_category_id2,sub_category_id3');
                // $this->db->from('business_page');
                // $this->db->group_start();
                // $this->db->like('address_1',$keyword);
                // $this->db->or_like('address_2',$keyword);
                // $this->db->group_end();
                // $this->db->group_by('latitude'); 
                // $query = $this->db->get();


                $query = $this->db->query("select sponsored,business_name,latitude,longitude,business_page_id,user_id,address_1,address_2,business_full_name,sub_category_id,sub_category_id2,sub_category_id3, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page`
                    where status!='unverified' AND is_deleted!=0 AND latitude!= 0 AND latitude!='' AND longitude!= 0 AND longitude!='' AND (address_1!='' OR address_2!='' ) AND (address_1 LIKE '%$keyword%' OR address_2 LIKE '%$keyword%') group by latitude ");


             





              
            }else{
                $query = $this->db->query(" SELECT business_page.business_full_name,business_page.sub_category_id,business_page.sub_category_id2,business_page.sub_category_id3,business_page.from_price,business_page.to_price, 'local' as data_type, IFNULL(round((Select AVG(rating) from rating_page where  business_page.business_page_id=rating_page.page_id),'1'),0) as rating , business_page.business_name, business_page.status,business_page.business_page_id as page_id,business_page.user_id,business_page.business_image,business_page.verification,business_page.email,business_page.mobile,business_page.latitude,business_page.longitude,business_page.sponsored,  categories.name as category_name,sub_categories.name as sub_category_name, IFNULL(round(( 6371 * ACOS( COS( RADIANS(  '$lat' ) ) * COS( RADIANS( `latitude` ) ) * COS( RADIANS( `longitude` ) - RADIANS(  '$lng' ) ) + SIN( RADIANS( '$lat' ) ) * SIN( RADIANS( `latitude` ) ) ) ),1),'123') AS distance  FROM `business_page` left join categories on categories.category_id = business_page.category_id left join sub_categories on sub_categories.sub_category_id = business_page.sub_category_id where business_page.business_name LIKE '%$keyword%' AND business_page.is_deleted = 0 AND business_page.status != 'unverified' AND   business_page.latitude !='' AND  business_page.longitude !='' AND business_page.latitude !='0' AND  business_page.longitude !='0'  ORDER BY distance ASC  limit 30 ");
            }
            
      
        } 
       
        return $query->result_array(); 
    }

    public function analytics($page_id,$type,$time_from='',$time_end='')
    {
        if($type=='places'){
            $this->db->select('*,count(*) as total');
            $this->db->from('redeem_offers');
            $this->db->where('page_id',$page_id);
            $this->db->group_by('latitude');  
            $query = $this->db->get(); 
            return $query->result_array(); 
      
        }else{
            $this->db->select('latitude');
            $this->db->from('redeem_offers');
            $this->db->where('time >=', $time_from);
            $this->db->where('time <=', $time_end);
            $this->db->where('page_id',$page_id);
            $query = $this->db->get(); 
        
            return $query->num_rows(); 
        }

    }
    public function user_subscriber_check($user_id,$page_id)
    {
            $date = date('Y-m-d');
            $this->db->select('subscription_plan_id,subscription_type');
            $this->db->from('subscription_user');
            $this->db->where('user_id', $user_id);
            $this->db->where('page_id',$page_id);
            $this->db->where('is_deleted','0');
            $this->db->where('end_date >=',$date);
            $query = $this->db->get(); 
            // echo $this->db->last_query();die;
            return $query->row_array(); 
    } 
 
    public function paymentHistory($user_id,$page_id)
    { 
        $this->db->select('su.created,su.transaction_id,su.amount');
        
        $this->db->from('subscription_user su');
       
        $this->db->where('user_id',$user_id);
        $this->db->where('page_id',$page_id);
        $this->db->where('page_id',$page_id);
        $this->db->where('amount > ',0);
            $query = $this->db->get(); 
            return $query->result_array(); 
      
      
    }

    public function hashtaguser($post_id)
    { 
        $this->db->select('u.username,u.user_id');
        $this->db->from('hashtaguser tu');
        $this->db->join('users u','u.user_id = tu.id');
        $this->db->where('tu.post_id',$post_id);
        $this->db->where('tu.is_page','0');
        $this->db->where('u.is_deleted','0');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function hashtagpage($post_id)
    { 
        $this->db->select('u.business_name,u.business_page_id,u.user_id');
        $this->db->from('hashtaguser tu');
        $this->db->join('business_page u','u.business_page_id = tu.id');
        $this->db->where('tu.post_id',$post_id);
        $this->db->where('tu.is_page','1');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function hashtagword($post_id)
    { 
        $this->db->select('u.word,u.tag_word_id');
        $this->db->from('hashtagword tu');
        $this->db->join('tags u','u.tag_word_id = tu.tag_word_id');
        $this->db->where('tu.post_id',$post_id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function gethashtagpost($user_id,$hashtag_id,$sort='')
    { 
         if($sort == 'views')
        {
               $sort_condition = '`up`.`view_post` DESC ';

        }elseif ($sort == 'likes') {

            $sort_condition = '`post_likes` DESC';

        }else  {

             $sort_condition = '`up`.`created` DESC ';
        }
       
         $query = $this->db->query(" SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = up.post_id ) as post_likes,  `u`.`user_id`,`u`.`username`, `u`.`profile_pic`, `up`.`post_id`,`up`.`view_post`, `up`.`post_title`, `up`.`post_detail`, `up`.`post_date`,`up`.`business_page_id`,`up`.`shareby_id`,IFNULL(lo.address, '') as address,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude 
                FROM `hashtagword` `hw`  INNER JOIN  `user_post` `up` ON `hw`.`post_id` = `up`.`post_id`  INNER JOIN `users` `u` ON `u`.`user_id` = `up`.`user_id`
                LEFT JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`
                 WHERE `up`.`status` = 'Active' 

                   AND
                   (
                        (
                        up.user_id  = (select follow_user_id from `follow_user` where user_id='".$user_id."' AND follow_user_id = `up`.`user_id` limit 1)
                        ) 
                        OR  
                        ( 
                            u.account_type='public'
                        )
                    )
                    
                AND `up`.is_deleted= 0
                AND `up`.`shareby_id`=0
                AND `up`.`is_page_shareby`=0
                AND `hw`.`tag_word_id` = $hashtag_id
               
               ORDER BY ".$sort_condition."");
        return $query->result_array();
    }

    public function getExplore_maps_hashtag_place($hashtag_id){
        
        $query = $this->db->query("SELECT up.post_id,up.user_id,up.business_page_id, IFNULL(lo.location_id, '') as location_id,  IFNULL(lo.latitude, '') as latitude, IFNULL(lo.longitude, '') as longitude, IFNULL(lo.address, '') as address, IFNULL(lo.address_name, '') as address_name FROM hashtagword hw  INNER JOIN user_post up ON hw.post_id=up.post_id LEFT JOIN `gio_location` `lo` ON `lo`.`location_id` = `up`.`location_id`  Where up.shareby_id= 0 AND up.is_deleted= 0 AND up.`shareby_id`= 0 AND  up.status= 'Active'   AND  `hw`.`tag_word_id` = $hashtag_id ");
   
        return $query->result_array();
 
    }

     public function getExplore_hashtag_search($media_type,$sort='',$hashtag_id){
 

        if($sort == 'views')
        {
              $sort_condition = '`user_post`.`view_post` DESC ';

        }elseif ($sort == 'likes') {

            $sort_condition = '`post_likes` DESC';

        }elseif ($sort == 'latest') {

             $sort_condition = '`user_post`.`created` DESC ';
        }
        else{
            $sort_condition =  '`user_post`.`created` DESC ';
        }
    
        $query = $this->db->query("SELECT (select count(post_like.post_like_id) from post_like where post_like.post_id = user_post.post_id ) as post_likes, users.user_id,users.username,users.profile_pic,post_img.video_path as video_file_path,user_post.post_id,user_post.post_title,user_post.business_page_id,user_post.shareby_id,user_post.post_date,user_post.view_post,post_img.post_img_id,post_img.file_type,post_img.file_path,lo.address,IFNULL(lo.address_name, '') as address_name,IFNULL(lo.latitude, '') as latitude,IFNULL(lo.longitude, '') as longitude FROM   `hashtagword` `hw` 
            INNER JOIN user_post ON hw.post_id = user_post.post_id 
            LEFT join `gio_location` `lo` on lo.location_id = user_post.location_id  
            inner join users on  user_post.user_id = users.user_id  
            inner join post_img on user_post.post_id = post_img.post_id 
            where post_img.file_type = '".$media_type."'  
             AND `user_post`.is_deleted= 0 
             AND `user_post`.status= 'Active'
             AND `user_post`.shareby_id =0
             AND `hw`.`tag_word_id` = $hashtag_id 
               ORDER BY ".$sort_condition." ");
 
        return $query->result_array();
        
    }

    public function pagechatting($page_id,$user)
    { 
        $this->db->select('pg.*,bp.business_name,bp.business_image,u.username,u.profile_pic');
        $this->db->from('pagechatting pg');
        $this->db->join('business_page bp','bp.business_page_id = pg.page_id');
        $this->db->join('users u','u.user_id = pg.user_id');
        $this->db->where('pg.page_id',$page_id);
         $this->db->where('pg.user_id',$user);
        $query = $this->db->get();
        return $query->result_array();
    } 

     public function pagechattinglist($page_id)
    { 
        $this->db->select('pg.*,bp.business_name,bp.business_image,u.username,u.profile_pic');
        $this->db->from('(SELECT * FROM pagechatting ORDER BY created DESC) AS pg');
        $this->db->join('business_page bp','bp.business_page_id = pg.page_id');
        $this->db->join('users u','u.user_id = pg.user_id');
        $this->db->where('pg.page_id',$page_id);
        $this->db->group_by('pg.user_id','desc'); 
        $this->db->order_by('pg.created', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    } 

    
    public function check_chat_availability($sender_user,$receiver_user)
    {

     $query = $this->db->query("SELECT * FROM `chating_request` WHERE (`sender_user`=$sender_user ANd `receiver_user`=$receiver_user AND status='1'  ) OR  ( `sender_user` =$receiver_user AND `receiver_user` =$sender_user AND status='1')");
        //echo $this->db->last_query();
         return $query->result_array();
       // echo $this->db->last_query();die;
    }

  

    public function get_panding_list($user_id)
    {
        $this->db->select('cr.created,cr.id as record_id, "no" as is_follow_request,u.firebase_id,u.firebase_email,u.firebase_password,  u.user_id,u.username,u.full_name,u.profile_pic');
        $this->db->from('chating_request cr'); 
        $this->db->join('users u','u.user_id = cr.sender_user');
        $this->db->where('cr.receiver_user',$user_id); 
        $this->db->where('u.status','Active'); 
        $this->db->where('cr.status','0'); 
        $this->db->where('u.is_deleted','0'); 
        $query = $this->db->get();
        return $query->result_array();
    }

    

}
