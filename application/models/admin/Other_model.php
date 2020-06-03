<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Other_model extends CI_Model {

public function __construct()
{
	parent:: __construct();
}

public function show_list_categories(){
	$return = array();
    $query  = $this->db->get('categories')->result_array();
    if( is_array( $query ) && count( $query ) > 0 )
    {
        $return[''] = 'Select Category';
        foreach($query as $row)
        {
            $return[$row['category_id']] = $row['name'];
        }
    }
    return $return;
}

public function getSubCategories($category_id)
{
	$this->db->select('sc.*,c.name as category_name');
    $this->db->from('sub_categories sc');
    $this->db->join('categories c','c.category_id = sc.category_id');
    $this->db->where('sc.category_id',$category_id);
    $this->db->order_by("sc.name DESC");
    $query= $this->db->get();
	if ($query->num_rows() > 0) {
        return $query->result_array();
    }
    return false;
}


public function getRatingCategories($category_id)
{
    $this->db->select('sc.*,c.name as category_name');
    $this->db->from('rating_categories sc');
    $this->db->join('categories c','c.category_id = sc.category_id');
    $this->db->where('sc.category_id',$category_id);
    $this->db->order_by("sc.name DESC");
    $query= $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result_array();
    }
    return false;
}

public function getReportUser($user_id)
{
    $this->db->select('report_user.*,users.*');
    $this->db->from('report_user');
    $this->db->join('users','users.user_id = report_user.report_user_id');
    $this->db->where('report_user.report_user_id',$user_id);
    $query= $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result_array();
    }
    return false;
}
public function getReportpost($post)
{
    $this->db->select('report_post.*,users.*');
    $this->db->from('report_post');
    $this->db->join('users','users.user_id = report_post.user_id');
    $this->db->where('report_post.post_id',$post);
    $query= $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->result_array();
    }
    return false;
}


public function getReportPage($page_id)
{
    $this->db->select('report_page.*,business_page.*,users.*');
    $this->db->from('report_page');
    $this->db->join('business_page','business_page.business_page_id = report_page.page_id');
    $this->db->join('users','users.user_id = report_page.user_id');
    $this->db->where('report_page.page_id',$page_id);
    $query= $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->result_array();
    }
    return false;
}




//Old data
public function getDropdownList($table,$col1,$col2,$title="") 
{

	$this->db->select($col1);
	$this->db->select($col2);
	$this->db->where('status','Active');
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


}/* model end*/

?>