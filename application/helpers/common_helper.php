<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(-1);
if (!function_exists('getInterestList')){
    function getInterestList(){
        //get main CodeIgniter object
        $ci =& get_instance();
        $where = array('status' =>'Active');
        $res =array();
        $res = $ci->Common_model->getRecords('interest','interest_id,name',$where,'interest_id Desc',false);
        return $res;
       
    }
}
if (!function_exists('get_sub_category')){
    function get_sub_category($cate_id){
        //get main CodeIgniter object
        $ci =& get_instance();
        $res =array();
        $res = $ci->Common_model->getRecords('sub_categories','sub_category_id,name',array('category_id'=>$cate_id),'',false);
        return $res;
       
    }
}

if (!function_exists('is_used')){
    function is_used($table,$is_used,$id){
        //get main CodeIgniter object
        $ci =& get_instance();
        $res =array();
        $res = $ci->Common_model->getRecords($table,$id,array($id=>$is_used),'',true);
        return $res[$id];
    }
}if (!function_exists('is_used_category')){
    function is_used_category($category_id){
        $ci =& get_instance();
        $query = $ci->db->query(" SELECT business_page.business_page_id as page_id from business_page where  business_page.is_deleted = 0 AND 
            (   
                find_in_set($category_id,business_page.sub_category_id)
                OR business_page.category_id2=$category_id OR  find_in_set($category_id,business_page.sub_category_id2)
                OR  business_page.category_id3=$category_id OR  find_in_set($category_id,business_page.sub_category_id3)
                 
                 )"

                 ); 
        return $query->num_rows();
        // return $res[$id];
    }
}


if (!function_exists('getCountriesList')){
    function getCountriesList(){
        //get main CodeIgniter object
        $ci =& get_instance();
        $res =array();
        $res = $ci->Common_model->getRecords('countries','id,name,phonecode','','',false);
        return $res;
       
    }
}

if (!function_exists('getSubcategoryList')){
    function getSubcategoryList($category_id){
        //get main CodeIgniter object
        $ci =& get_instance();
        $res =array();
        if($category_id) {
            $res = $ci->Common_model->getRecords('sub_categories','sub_category_id,name',array('category_id'=>$category_id),'',false);
        }
        return $res;
    }
}
if (!function_exists('getStatesList')){
    function getStatesList($country_id){
        //get main CodeIgniter object
        $ci =& get_instance();
        $res =array();
        if($country_id) {
            $res = $ci->Common_model->getRecords('states','id,name',array('country_id'=>$country_id),'',false);
        }
        return $res;
    }
}

if (!function_exists('getCitiesList')){
    function getCitiesList($state_id){
        //get main CodeIgniter object
        $ci =& get_instance();
        $res =array();
        if($state_id) {
           $res = $ci->Common_model->getRecords('cities','id,name',array('state_id'=>$state_id),'',false);
        }
        return $res;
    }
}

if (!function_exists('passwordValidate')){
    function passwordValidate($password){
        if(preg_match("/^(?=.*\d)(?=.*[a-zA-Z])(?=.*[-_!@#$]).{8,30}$/", $password)) {
            return true;
        } else {
           return false; 
        }
    }
}

if (!function_exists('getAdminEmail')){
    function getAdminEmail(){
        //get main CodeIgniter object
        // $ci =& get_instance();
        // $res =array();
        // $res = $ci->Common_model->getRecords('admin','email',array('admin_id' => 1),'',true);
        // return $res['email'];
        return 'admin@gmail.com';


    }
}

if (!function_exists('commonImageUpload')){
    function commonImageUpload($upload_path,$allowed_types,$file,$width,$height) 
    {
        $ci =& get_instance();
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = $allowed_types;
        $ci->load->library('upload', $config);
        $ci->upload->initialize($config);
        
        if (!$ci->upload->do_upload($file)) {
            return array('status'=>0,'msg'=>$ci->upload->display_errors("<p class='inputerror'>","</p>"));        
        } else {
            $upload_data=$ci->upload->data();
           
            $img=$upload_data['file_name'];
            if($upload_data['file_type']!='image/svg+xml'){
                $img = uniqid(time()).$upload_data['file_ext'];
                $config['image_library'] = 'gd2';
                $config['source_image'] = $upload_data['full_path'];
                $config['new_image'] = $upload_path.$img;
                $config['quality'] = 100;
                $config['maintain_ratio'] = FALSE;
                $config['width']         = $width;
                $config['height']       = $height;

                $ci->load->library('image_lib', $config);

                $ci->image_lib->resize();
                $ci->image_lib->clear();
                unlink($upload_data['full_path']);
            }
            return array('status'=>1,'image_path'=>$upload_path.$img);
        }
    }
}
if (!function_exists('BannerUpload')){

    function BannerUpload($upload_path,$allowed_types,$file) {
        $ci =& get_instance();
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = $allowed_types;
        $ci->load->library('upload', $config);
        $ci->upload->initialize($config);
        
        if (!$ci->upload->do_upload($file)) {
            return array('status'=>0,'msg'=>$ci->upload->display_errors("<p class='inputerror'>","</p>"));        
        } else {
            $upload_data=$ci->upload->data();
            $img=$upload_data['file_name'];
            $img = uniqid(time()).$upload_data['file_ext'];
            rename($upload_data['full_path'], $upload_path.$img);
            return array('status'=>1,'image_path'=>$upload_path.$img);
        }
    }
}

if (!function_exists('user_email')){
    function user_email($id='',$email){
        //get main CodeIgniter object
        $ci =& get_instance();
        if(empty($id)){
            $record= $ci->Common_model->getRecords('users', 'user_id', array('email'=>$email), '', true);
        }else{
            $record= $ci->Common_model->getRecords('users', 'user_id', array('user_id!='=>$id,'email'=>$email), '', true);
        }
        if(!empty($record)) {
            return 1;
        } else {
            return 0;
        }
    }
}

if (!function_exists('firebase_email')){
    function firebase_email($id,$email){
        //get main CodeIgniter object
        $ci =& get_instance();
        if($ci->Common_model->getRecords('users', 'user_id', array('user_id!='=>$id,'firebase_email'=>$email), '', true)) {
            return 1;
        } else {
            return 0;
        }
    }
}

if (!function_exists('user_username')){
    function user_username($id,$username){
        //get main CodeIgniter object
        $ci =& get_instance();
        if($ci->Common_model->getRecords('users', 'user_id', array('user_id!='=>$id,'username'=>$username), '', true)) {
            return 1;
        } else {
            return 0;
        }
    }
}


if (!function_exists('get_sub_categories_name')){
    function get_sub_categories_name($id,$with_cat='Yes'){
        //get main CodeIgniter object
        $ci =& get_instance();
        $cate_name = $ci->Common_model->getRecords('sub_categories', 'name,category_id', array('status='=>'Active','sub_category_id'=>$id), '', true);
      
        if($cate_name) {
              $main_cate_name = $ci->Common_model->getRecords('categories', 'name', array('status='=>'Active','category_id'=>$cate_name['category_id']), '', true);

       // echo $ci->db->last_query();die;
              if($with_cat=='Yes')
              {
                return $main_cate_name['name'].'-'.$cate_name['name'];
            }else
            {
                return $cate_name['name'];
            }
            
        } else {
            return '';
        }
    }
}



if (!function_exists('user_mobile')){
    function user_mobile($id,$mobile){
        //get main CodeIgniter object
        $ci =& get_instance();
        if($ci->Common_model->getRecords('users', 'user_id', array('user_id!='=>$id,'mobile'=>$mobile), '', true)) {
            return 1;
        } else {
            return 0;
        }
    }
}


if (!function_exists('createtag')){
    function createtag($detail,$link){
        $detail=trim($detail);
        $exploded_string = array();
        $exploded_string =  explode("#", $detail);
        array_shift($exploded_string);
        $hash_tag_array = array();
        foreach($exploded_string as $list){
            $hash_string =  explode(" ", $list);
            $hash_tag = $hash_string[0];
            if($hash_tag) {
                $hash_tag_array[] = $hash_tag;
            }
        }
        $hash_tag_array[count($hash_tag_array)] = $detail;
        return $hash_tag_array;
    }
}

if (!function_exists('createuser')){
    function createuser($detail,$link){
        $detail=trim($detail);
        $exploded_string = array();
        $exploded_string =  explode("@", $detail);
        array_shift($exploded_string);
        $hash_tag_array = array();
        foreach($exploded_string as $list){
            $hash_string =  explode(" ", $list);
            $hash_tag = $hash_string[0];
            if($hash_tag) {
                $hash_tag_array[] = $hash_tag;
            }
        }

        $hash_tag_array[count($hash_tag_array)] = $detail;
        return $hash_tag_array;
    }
}

if (!function_exists('addNotification')){
    function addNotification ($created_by,$to_userid,$type,$message,$record_id,$main_media_id) {
        $date= date('Y-m-d H:i:s');
        $insert_data = array(
            'created_by'=>  $created_by,
            'to_user'=> $to_userid, 
            'type'=>$type,
            'message'=>$message,
            'record_id'=>$record_id,
            'main_media_id'=>$main_media_id,
            'created_datetime'=>$date
        );
        $ci =& get_instance();      
        $notification = $ci->common_model->addEditRecords('notification',$insert_data);
        if(!$notification) {
            $err = array('data'=> array('status'=>'0','message'=>'Some error occured!!'));
            echo json_encode($err);
        }
    }
}

if (!function_exists('iosNotification')) {
    function iosNotification($deviceToken,$message_arr='')
    { 

         error_reporting(-1);
        if(!empty($deviceToken)) {
            if($deviceToken !='NA') {
                // private key's passphrase here:
                $passphrase = '1234';
                $ctx = stream_context_create();

                // if(DEV_MODE==true){
                    $pem = 'pem/PushDev.pem';
                    $url = 'ssl://gateway.sandbox.push.apple.com:2195';
                // }else
                // {
                    // $pem = 'pem/PushPro.pem';
                    // $url = 'ssl://gateway.push.apple.com:2195';
                // }
              
                stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);
                stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

                // Open a connection to the APNS server
                $fp = stream_socket_client($url, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

                if (!$fp){
                   return false;
                }
                // Create the payload body
                $body['aps'] = $message_arr;
                // echo "<pre>";print_r($body['aps'] );
                $payload = json_encode($body);
                
                // Build the binary notification
                $msg = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;
                $result = fwrite($fp, $msg, strlen($msg)); 
                // Send it to the server
                if (!$result) {
                    return false;
                } else {
                    fclose($fp);
                    return true;
                }
                // Close the Connection to the Server.
            } else {
                return false;
            }
        } else {
            return false;
        }
    } //end send notification on ios
}

if (!function_exists('androidNotification')) {
    function androidNotification($deviceToken,$message_arr='') 
    {
        if(!empty($deviceToken))
        {   
            $deviceToken = array($deviceToken);
            $url = 'https://fcm.googleapis.com/fcm/send'; 

            $fields = array(
                'registration_ids' => $deviceToken,
                'data' => $message_arr
            );
            $headers = array( 
               // 'Authorization: key=AAAAGX3gVfA:APA91bFJkd01Era_5nEq_lejVr_KMvANX4yVcikwHIB1p8ZhRtp_aZ16iOZJ7N5A1017TFpUYkFYLcQrXnrwUqmYUM7s1jBq8BookgCuWEDZul6FzcK2_l9VxuBXAgGwmElPCDctb1oe',
                'Authorization: key=AAAAeZXpZOE:APA91bH2IZaZP4dXp61-wxnXf0kV3y8nzUvNqht0eWkRd0uEuUhKDQLGCqmJZ7rp1NghR5A0tRJ78l-1liwKaNHGvoq7yKJ8xvlSr3NHvH4V7s3tD3Ld-RK2-b_RB-W-IBHVOIhXeAit',
                'Content-Type: application/json'
            );
            // Open connection
            $ch = curl_init();
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Execute post
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
            json_encode($result);
            return true;
        }else{
            return false;
        }
        
    } //end send notification on android
}



   function get_distance($lat1, $lon1, $lat2, $lon2, $unit) {

          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtoupper($unit);

          if ($unit == "K") {
            return ($miles * 1.609344);
          } else if ($unit == "N") {
              return ($miles * 0.8684);
            } else {
                return $miles;
              }
    }




    function multid_sort($arr, $index='',$order) {
        $b = array();
        $c = array();
        foreach ($arr as $key => $value) {
            $b[$key] = $value[$index];
        }
        if($order=='Desc')
        {
          
            arsort($b);
        }else
        {

            asort($b);
        }
       

        foreach ($b as $key => $value) {
            $c[] = $arr[$key];
        }

        return $c;
    }

    function check_permission($type){
        $ci =& get_instance();
        $user_id = $ci->session->userdata('user_id');
        if(empty($user_id)){
             redirect(base_url()); 
        }else{
          //  echo '123';die;
           // echo $type;
            if($users = $ci ->Common_model->getRecords('users','user_type',array('user_id'=>$user_id),'',true))
            {
              //  echo $users['user_type'];die;
                if($type!=$users['user_type'] && !empty($type)){
                    if($type=='doctor'){
                         redirect(base_url('doctor/list/4'));  
                     }else{
                          redirect(base_url('upload_media')); 
                     }
                }else{
                    return true;
                }

            }

        }
    }


    function check_for_request($sender_user,$receiver_user)
    {  
        $ci =& get_instance();
        $checkalready =  $ci ->App_model->check_chat_availability($sender_user,$receiver_user);
        if(!empty($checkalready))
        {
             return '0';exit;
        }else
        { 
          if($ci ->Common_model->getRecords('chating_request','id',array('sender_user'=>$sender_user,'receiver_user'=>$receiver_user,'status'=>'0'),'',true))
          {
            // 'you already send';
            return '2';exit;

          }elseif ($ci ->Common_model->getRecords('chating_request','id',array('receiver_user'=>$sender_user,'sender_user'=>$receiver_user,'status'=>'0'),'',true))        {

          //   return 'user request panding';
             return  '3';exit;
             
          }else
          {
                if( $ci ->Common_model->getRecords('users','user_id,account_type',array('user_id'=>$receiver_user,'account_type'=>'Private'),'',true)) 
                {
                    if($ci ->Common_model->getRecords('follow_user','follow_id',array('follow_user_id'=>$sender_user,'user_id'=>$receiver_user,'status'=>'Follow'),'',true))
                    {
                         return  '0';exit;
                    }else
                    {
                        return  '1';exit;
                    } 

                }else
                {
                     return  '0';exit;
                }  
              }   
           
        }


    }

