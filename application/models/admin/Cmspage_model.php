<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmspage_model extends CI_Model {

public function __construct()
{
	parent:: __construct();
}

			
public function insert_cmspage($data)
{

	$cmspage_data=array(
		'title' =>$data['title'] ,
		'description' =>$data['description'] ,
		'content'=>$data['content'],
		'seo_title'=>$data['seo_title'],
		'seo_description'=>$data['seo_description'],
		'seo_keywords'=>$data['seo_keywords'],
		'status'=>$data['status']
	);

return $this->db->insert('cms_pages',$cmspage_data);

}/* insert product end*/


public function update_cmspage($data)
{
	$cmspage_data=array(
		'title' =>$data['title'] ,
		'description' =>$data['description'] ,
		'content'=>$data['content'],
		'seo_title'=>$data['seo_title'],
		'seo_description'=>$data['seo_description'],
		'seo_keywords'=>$data['seo_keywords'],
		'status'=>$data['status']
	);
					
	$this->db->where('cmspage_id',$data['cmsid']);
	return $this->db->update('cms_pages',$cmspage_data);
}/* update product end*/



public function show_cmspages($limit,$start)
{
	$this->db->limit($limit, $start);
	$query= $result=$this->db->get('cms_pages');

	if ($query->num_rows() > 0) {
        return $query;
    }
    return false;

}/* Show_products*/

public function get_cmspage($id)
{
	$this->db->where('cmspage_id', $id);
	return $this->db->get('cms_pages');
}/* Edit Product*/

public function total_cmspages()
{
	$result=$this->db->get('cms_pages');
	return $result->num_rows();
}

public function delete_cmspage($id)
{
	$this->db->where('cmspage_id', $id);
	$this->db->delete('cms_pages'); 
	
}

}/* model end*/



?>