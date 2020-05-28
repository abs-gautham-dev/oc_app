<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

// error_reporting(E_ALL);
// 	 ini_set('display_errors', TRUE);
public function __construct()
{
	parent:: __construct();
	$this->load->database();
	$this->load->model('admin/Common_model');
	$this->load->helper('Common_helper');

	error_reporting(0);
}

public function cat_image_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = CATEGORY_PATH;
	$config['allowed_types'] = 'jpg|jpeg|png|svg';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 50;
			$config['height']       = 50;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
		}
		//unlink($upload_data['full_path']);
		
		$this->db->select('image');
		$this->db->where('category_id',$aid);
		$q=$this->db->get('categories');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$image_data=array('image' =>CATEGORY_PATH.$upload_data['file_name']);
		$this->db->where('category_id',$aid);
		$this->db->update('categories',$image_data);
		echo "1";
	}
}/* category_image_upload */



public function amenities_image_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = CATEGORY_PATH;
	$config['allowed_types'] = 'jpg|jpeg|png';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 50;
			$config['height']       = 50;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
		}
		//unlink($upload_data['full_path']);
		
		$this->db->select('icon_image');
		$this->db->where('amenity_id',$aid);
		$q=$this->db->get('amenities');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$image_data=array('icon_image' =>CATEGORY_PATH.$upload_data['file_name']);
		$this->db->where('amenity_id',$aid);
		$this->db->update('amenities',$image_data);
		echo "1";
	}
}/* category_image_upload */


//Old data 
public function delete_product($id){
	$this->load->model('admin/product_model');
	$this->product_model->delete_product($id);
}

// delete_product_gallery
public function delete_product_gallery($id){

	$this->load->model('admin/product_model');
	$delete_gallery=$this->product_model->delete_product_gallery($id);
}
// delete_banner
public function delete_banner_image($id){

	$this->load->model('admin/banner_model');
	$delete_gallery=$this->banner_model->delete_banner_image($id);
}
// delete_client 
public function delete_client_image($id){	
	$this->load->model('admin/client_model');
	$delete_gallery=$this->client_model->delete_client_image($id);
}


// delete_testimonial 
public function delete_testimonial_image($id){
	$this->load->model('admin/testimonial_model');
	$delete_gallery=$this->testimonial_model->delete_testimonial_image($id);
}

// delete_product_attribute 
public function delete_product_attribute($id){
	$this->load->model('admin/product_model');
	$delete_gallery=$this->product_model->delete_product_attribute($id);
}

	
//upload_image
public function image_upload() {
	$config['upload_path'] = 'resources/images/product/';
	$config['allowed_types'] = 'gif|jpg|png';
	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	$this->upload->set_allowed_types('*');
	
	$pid=$this->input->post("pid");
	if (!$this->upload->do_upload('img_upload')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		$product_data=array('img_name' =>$upload_data['file_name'] 
		);
		$this->db->where('product_id',$pid);
		$this->db->update('products',$product_data);
		echo "<img src='".base_url('resources/images/product')."/".$upload_data['file_name']."'  width='200px' height='200px' classs='img-responsive'/>";
	}
}/* Product Image Upload*/



		
// image update by ajx resources/images/fabric
public function fabric_image_upload() {
	$config['upload_path'] = 'resources/images/fabric/';
	$config['allowed_types'] = 'jpeg|jpg|png';
	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	//$this->upload->set_allowed_types('*');
	
	$fabric_id=$this->input->post("fabric_id");
	if (!$this->upload->do_upload('img_upload')) {
		echo $this->upload->display_errors();		
	} else {
		$upload_data=$this->upload->data();
		///
		$img = time().'_'.rand().$upload_data['file_ext'];
		$config['image_library'] = 'gd2';
		$config['source_image'] = $upload_data['full_path'];
		$config['new_image'] = 'resources/images/fabric/' . $img;
		$config['quality'] = 100;
		$config['maintain_ratio'] = FALSE;
		$config['width']         = 70;
		$config['height']       = 58;

		$this->load->library('image_lib', $config);

		$this->image_lib->resize();
		$this->image_lib->clear();
		unlink($upload_data['full_path']);
		///
		// $fabric_data=array('image' =>$upload_data['file_name'] );
		$fabric_data=array('fabric_image' =>'resources/images/fabric/'.$img);
		$this->db->where('fabric_id',$fabric_id);
		$this->db->update('fabrics',$fabric_data);
		// echo "<img src='".base_url('resources/images/fabric')."/".$upload_data['file_name']."'  width='200px' height='200px' classs='img-responsive'/>";
		echo "<img src='".base_url('resources/images/fabric')."/".$img."'  width='200px' height='200px' classs='img-responsive'/>";
	}
}

// image update by ajx resources/images/fabric
public function fabric_main_image_upload() {
	$config['upload_path'] = 'resources/images/fabric/';
	$config['allowed_types'] = 'jpeg|jpg|png';
	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	//$this->upload->set_allowed_types('*');
	
	$fabric_id=$this->input->post("fabric_id");
	if (!$this->upload->do_upload('img_upload')) {
		echo $this->upload->display_errors();		
	} else {
		$upload_data=$this->upload->data();
		///
		$img = time().'_'.rand().$upload_data['file_ext'];
		$config['image_library'] = 'gd2';
		$config['source_image'] = $upload_data['full_path'];
		$config['new_image'] = 'resources/images/fabric/' . $img;
		$config['quality'] = 100;
		$config['maintain_ratio'] = FALSE;
		$config['width']         = 100;
		$config['height']       = 100;

		$this->load->library('image_lib', $config);

		$this->image_lib->resize();
		$this->image_lib->clear();
		unlink($upload_data['full_path']);
		///
		// $fabric_data=array('image' =>$upload_data['file_name'] );
		$fabric_data=array('main_image' =>'resources/images/fabric/'.$img);
		$this->db->where('fabric_id',$fabric_id);
		$this->db->update('fabrics',$fabric_data);
		// echo "<img src='".base_url('resources/images/fabric')."/".$upload_data['file_name']."'  width='200px' height='200px' classs='img-responsive'/>";
		echo "<img src='".base_url('resources/images/fabric')."/".$img."'  width='200px' height='200px' classs='img-responsive'/>";
	}
}

// image update by ajx resources/images/fabric
public function fabric_detail_image_upload() {
	$config['upload_path'] = 'resources/images/fabric/';
	$config['allowed_types'] = 'jpeg|jpg|png';
	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	//$this->upload->set_allowed_types('*');
	
	$fabric_id=$this->input->post("fabric_id");
	if (!$this->upload->do_upload('img_upload')) {
		echo $this->upload->display_errors();		
	} else {
		$upload_data=$this->upload->data();
		///
		$img = time().'_'.rand().$upload_data['file_ext'];
		$config['image_library'] = 'gd2';
		$config['source_image'] = $upload_data['full_path'];
		$config['new_image'] = 'resources/images/fabric/' . $img;
		$config['quality'] = 100;
		$config['maintain_ratio'] = FALSE;
		$config['width']         = 100;
		$config['height']       = 100;

		$this->load->library('image_lib', $config);

		$this->image_lib->resize();
		$this->image_lib->clear();
		unlink($upload_data['full_path']);
		///
		// $fabric_data=array('image' =>$upload_data['file_name'] );
		$fabric_data=array('detail_image' =>'resources/images/fabric/'.$img);
		$this->db->where('fabric_id',$fabric_id);
		$this->db->update('fabrics',$fabric_data);
		// echo "<img src='".base_url('resources/images/fabric')."/".$upload_data['file_name']."'  width='200px' height='200px' classs='img-responsive'/>";
		echo "<img src='".base_url('resources/images/fabric')."/".$img."'  width='200px' height='200px' classs='img-responsive'/>";
	}
}
	
/* resources/images/fabric Image Upload*/

//upload_testimonial_client 
public function testimonial_image_upload() {
	$config['upload_path'] = 'resources/images/testimonial/';
	$config['allowed_types'] = 'gif|jpg|png';
	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	$this->upload->set_allowed_types('*');
	
	$tmid=$this->input->post("tmid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		$testimonial_data=array('image' =>$upload_data['file_name'] 
		);
		$this->db->where('testimonial_id',$tmid);
		$this->db->update('testimonial_sliders',$testimonial_data);
		echo "<img src='".base_url('resources/images/testimonial')."/".$upload_data['file_name']."'  width='200px' height='200px' classs='img-responsive'/>";
	}
}/* client Image Upload*/

//upload_testimonial_client 
//attribute_image_upload upload_attribute_image
public function attribute_image_upload() {

	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	//$fileExt = array_pop(explode(".", $newFileName));
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = 'resources/images/attribute/';
	$config['allowed_types'] = 'gif|jpg|jpeg|png|svg';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		$this->db->select('image');
		$this->db->where('attribute_id',$aid);
		$q=$this->db->get('attributes');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$attribute_data=array('image' =>'resources/images/attribute/'.$upload_data['file_name']);
		$this->db->where('attribute_id',$aid);
		$this->db->update('attributes',$attribute_data);
		echo "1";
		//echo "<img src='".base_url('resources/images/attribute')."/".$upload_data['file_name']."'  width='200px' height='200px' classs='img-responsive'/>";
	}
}/* attribute Image Upload*/


/* profile pic upload */
public function profile_pic_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	// $fileExt = array_pop(explode(".", $newFileName));
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = 'resources/images/profile/';
	$config['allowed_types'] = 'jpg|jpeg|png';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 150;
			$config['height']       = 150;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
			//unlink($upload_data['full_path']);
		}
		$this->db->select('profile_pic');
		$this->db->where('admin_id',$aid);
		$q=$this->db->get('admin');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$update_data=array('profile_pic' =>'resources/images/profile/'.$upload_data['file_name']);
		$this->db->where('admin_id',$aid);
		$this->db->update('admin',$update_data);
		$admin_id = $this->session->userdata('admin_id');
		if($aid == $admin_id){
		$this->session->set_userdata($update_data);}
		echo "1";
	}
}/* Profile Pic Upload*/


/* profile pic upload */
public function profile_pic_user() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	// $fileExt = array_pop(explode(".", $newFileName));
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = 'resources/images/profile/';
	$config['allowed_types'] = 'jpg|jpeg|png';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 150;
			$config['height']       = 150;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
			//unlink($upload_data['full_path']);
		}
		$this->db->select('profile_pic');
		$this->db->where('user_id',$aid);
		$q=$this->db->get('users');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$update_data=array('profile_pic' =>'resources/images/profile/'.$upload_data['file_name']);
		$this->db->where('user_id',$aid);
		$this->db->update('users',$update_data);
		$this->session->set_userdata($update_data);
		echo "1";
	}
}/* Profile Pic Upload*/

//attribute_option_image_upload 
public function attribute_option_image_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = array_pop(explode(".", $newFileName));
	$filename = uniqid(time()).".".$fileExt;

	$config['upload_path'] = 'resources/images/attribute_option/';
	$config['allowed_types'] = 'gif|jpg|jpeg|png|svg';
	$config['file_name'] = $filename;
	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	$this->upload->set_allowed_types('*');
	
	$aoid=$this->input->post("aoid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$this->db->select('image');
		$this->db->where('id',$aoid);
		$q=$this->db->get('attribute_options');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$upload_data=$this->upload->data();
		$attribute_option_data=array('image' =>'resources/images/attribute_option/'.$upload_data['file_name']);
		$this->db->where('id',$aoid);
		$this->db->update('attribute_options',$attribute_option_data);
		echo "1";
		// echo "<img src='".base_url('resources/images/attribute_option')."/".$upload_data['file_name']."'  width='200px' height='200px' classs='img-responsive'/>";
	}
}/* attribute Image Upload*/

//Wash Care Image Upload
public function wash_care_image_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	// $fileExt = array_pop(explode(".", $newFileName));
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = WASHCARE_PATH;
	$config['allowed_types'] = 'jpg|jpeg|png|svg';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 100;
			$config['height']       = 100;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
			//unlink($upload_data['full_path']);
		}
		$this->db->select('image');
		$this->db->where('wash_care_id',$aid);
		$q=$this->db->get('wash_care');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$image_data=array('image' =>WASHCARE_PATH.$upload_data['file_name']);
		$this->db->where('wash_care_id',$aid);
		$this->db->update('wash_care',$image_data);
		echo "1";
	}
}/* Wash Care Image Upload*/

//button type Image Upload
public function button_type_image_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = BUTTON_PATH;
	$config['allowed_types'] = 'jpg|jpeg|png|svg';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 100;
			$config['height']       = 100;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
			//unlink($upload_data['full_path']);
		}
		$this->db->select('image');
		$this->db->where('button_type_id',$aid);
		$q=$this->db->get('button_types');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$image_data=array('image' =>BUTTON_PATH.$upload_data['file_name']);
		$this->db->where('button_type_id',$aid);
		$this->db->update('button_types',$image_data);
		echo "1";
	}
}/* button_type_image_upload */

public function button_image_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = BUTTON_PATH;
	$config['allowed_types'] = 'jpg|jpeg|png|svg';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 100;
			$config['height']       = 100;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
			//unlink($upload_data['full_path']);
		}
		$this->db->select('image');
		$this->db->where('button_id',$aid);
		$q=$this->db->get('buttons');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$image_data=array('image' =>BUTTON_PATH.$upload_data['file_name']);
		$this->db->where('button_id',$aid);
		$this->db->update('buttons',$image_data);
		echo "1";
	}
}/* button__image_upload */

public function yarn_image_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = YARN_PATH;
	$config['allowed_types'] = 'jpg|jpeg|png|svg';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 100;
			$config['height']       = 100;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
		}
		//unlink($upload_data['full_path']);
		
		$this->db->select('image');
		$this->db->where('yarn_id',$aid);
		$q=$this->db->get('yarns');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$image_data=array('image' =>YARN_PATH.$upload_data['file_name']);
		$this->db->where('yarn_id',$aid);
		$this->db->update('yarns',$image_data);
		echo "1";
	}
}/* yarn_image_upload */

public function weave_image_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = WEAVE_PATH;
	$config['allowed_types'] = 'jpg|jpeg|png|svg';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 100;
			$config['height']       = 100;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
		}
		//unlink($upload_data['full_path']);
		
		$this->db->select('image');
		$this->db->where('weave_id',$aid);
		$q=$this->db->get('weaves');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$image_data=array('image' =>WEAVE_PATH.$upload_data['file_name']);
		$this->db->where('weave_id',$aid);
		$this->db->update('weaves',$image_data);
		echo "1";
	}
}/* weave_image_upload */



//upload_image_banner
public function banner_image_upload() {
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = BANNER_PATH;
	$config['allowed_types'] = 'jpg|jpeg|png';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		$this->db->select('image');
		$this->db->where('banner_id',$aid);
		$q=$this->db->get('banners');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$image_data=array('image' =>BANNER_PATH.$upload_data['file_name']);
		$this->db->where('banner_id',$aid);
		$this->db->update('banners',$image_data);
		echo "1";
	}
}/* banner Image Upload*/

//client_logo_upload
public function client_logo_upload() 
{	
	$newFileName = $_FILES['image']['name'];
	$fileExt = pathinfo($newFileName, PATHINFO_EXTENSION);
	
	$filename = uniqid(time()).".".$fileExt;
	//set filename in config for upload

	$config['upload_path'] = CLIENT_LOGO_PATH;
	$config['allowed_types'] = 'jpg|jpeg|png';
	$config['file_name'] = $filename;

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	// $this->upload->set_allowed_types('*');
	
	$aid=$this->input->post("aid");
	if (!$this->upload->do_upload('image')) 
	{
		echo $this->upload->display_errors();		
	}
	else
	{
		$upload_data=$this->upload->data();
		if($upload_data['file_type']!='image/svg+xml'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $upload_data['full_path'];
			$config['new_image'] = $filename;
			$config['quality'] = 100;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 180;
			$config['height']       = 110;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
			$this->image_lib->clear();
		}
		
		$this->db->select('image');
		$this->db->where('logo_id',$aid);
		$q=$this->db->get('clients_logo');
		$r=$q->row();
		if(!empty($r->image)){
			unlink($r->image);
		}

		$image_data=array('image' =>CLIENT_LOGO_PATH.$upload_data['file_name']);
		$this->db->where('logo_id',$aid);
		$this->db->update('clients_logo',$image_data);
		echo "1";
	}
}/* client_logo Upload*/



/************************************************************************************/
	//get the states by country id
    public function get_states()
    {
    	$states = array();
       	if($_POST['id']) {
       		$states = $this->Common_model->getRecords('states', 'id,name', array('country_id'=>$_POST['id']), '', false);
       	} 
        echo json_encode($states);  exit;
    }

    //get the states by country id
    public function get_cities()
    {
    	$cities = array();
       	if($_POST['id']) {
       		$cities = $this->Common_model->getRecords('cities', 'id,name', array('state_id'=>$_POST['id']), '', false);
       	} 
       	//echo $sub_outlets;
        echo json_encode($cities);  exit;
    }

    //check username already exists or not
    public function check_username()
    {
       	if($_POST['id'] && $_POST['username']) {
       		if($this->Common_model->getRecords('admin', 'admin_id', array('admin_id!='=>$_POST['id'],'username'=>$_POST['username']), '', true)) {
       			echo 1;
       		} else {
       			echo 0;
       		}
       	} 
    }

      public function check_username_user()
    {
       	if($_POST['id'] && $_POST['username']) {
       		if($this->Common_model->getRecords('users', 'user_id', array('user_id!='=>$_POST['id'],'username'=>$_POST['username']), '', true)) {
       			echo 1;
       		} else {
       			echo 0;
       		}
       	} 
    }

  	public function check_business_name()
    {
       	if($_POST['id'] && $_POST['username']) {
       		$user_name = str_replace(' ', '', $_POST['username']);
       		if($this->Common_model->getRecords('business_page', 'business_page_id', array('business_page_id!='=>$_POST['id'],'business_name'=>$user_name), '', true)) {
       			$a='1';
       		} else {
       			$a='0';
       		}
       		if($a!='1'){
       		if($this->Common_model->getRecords('users', 'user_id', array('username'=>$_POST['username']), '', true)) {
       			$a='1';
       		} else {
       			$a='0';
       		}}
       		echo $a;
       	} 
    }
    
    //check email already exists or not
    public function check_admin_email()
    {
       	if($_POST['id'] && $_POST['email']) {
       		if($this->Common_model->getRecords('admin', 'admin_id', array('admin_id!='=>$_POST['id'],'email'=>$_POST['email']), '', true)) {
       			echo 1;
       		} else {
       			echo 0;
       		}
       	} 
    }

    //check email already exists or not
    public function check_user_email()
    {   
    	if( $_POST['email']) {
            echo  user_email($_POST['id'],$_POST['email']);
       }	 
    }


    public function check_user_mobile()
    {
       	if($_POST['id'] && $_POST['mobile']) {
       		  echo  user_mobile($_POST['id'],$_POST['mobile']);
       	} 
    }

    public function change_status(){
		if($this->input->post()) 
		{
			$field = $this->input->post('field'); 
			$id = $this->input->post('id'); 
			$table_name = $this->input->post('table_name');
			$where = array($field=> $id);
			$date = date("Y-m-d H:i:s");
			if($status = $this->Common_model->getFieldValue($table_name,'status',$where)) {
				$new_status = "Active";
				if($status=="Active") {
					$new_status = "Inactive";
				} 

				if($this->Common_model->addEditRecords($table_name,array('status'=>$new_status,'modified'=>$date),$where))
				{
					$data = array('msg'=>'success','status' =>$new_status);
				
				} else {
					$data = array('msg'=>'fail','admin_approve' =>$admin_approve);
				}
				echo json_encode($data);
			}
		}
	}

	 public function change_status_delete(){
		if($this->input->post()) 
		{
			$field = $this->input->post('field'); 
			$id = $this->input->post('id'); 
			$table_delete = $this->input->post('table_delete');
			$table_name = $this->input->post('table_name');
			$where = array($field=> $id);
			$date = date("Y-m-d H:i:s");
			if($status = $this->Common_model->getFieldValue($table_name,'status',$where)) {
				$new_status = "Active";
				if($status=="Active") {
					$new_status = "Inactive";
				} else{
					
		            $this->Common_model->deleteRecords($table_delete, $where);
				}

				if($this->Common_model->addEditRecords($table_name,array('status'=>$new_status,'modified'=>$date),$where))
				{
					$data = array('msg'=>'success','status' =>$new_status);
				
				} else {
					$data = array('msg'=>'fail','admin_approve' =>$admin_approve);
				}
				echo json_encode($data);
			}
		}
	}

	   public function change_status_review(){
		if($this->input->post()) 
		{
			$field = $this->input->post('field'); 
			$id = $this->input->post('id'); 
			$table_name = $this->input->post('table_name');
			$where = array($field=> $id);
			$date = date("Y-m-d H:i:s");

			if($status = $this->Common_model->getFieldValue($table_name,'status',$where)) {
				$new_status = "Active";
				if($status=="Active") {
					$new_status = "Inactive";
				} 
					$this->Common_model->addEditRecords('rating_page',array('status'=>$new_status,'modified'=>$date),$where);
				if($this->Common_model->addEditRecords($table_name,array('status'=>$new_status,'modified'=>$date),$where))
				{
					$data = array('msg'=>'success','status' =>$new_status);
				
				} else {
					$data = array('msg'=>'fail','admin_approve' =>$admin_approve);
				}
				echo json_encode($data);
			}
		}
	}

	

	 public function verified(){
			if($this->input->post()) 
			{
				$field = $this->input->post('field'); 
				$id = $this->input->post('id'); 
				$table_name = $this->input->post('table_name');
				$where = array('business_page_id' =>$id);
				$date = date("Y-m-d H:i:s");
				if($status = $this->Common_model->getFieldValue($table_name,'status',$where)) {
					$new_status = "verified";
					if($status=="verified") {
						$new_status = "unverified";
					}else{

						$where456 = array('business_page_id' => $id);
					$resiver=$this->Common_model->getRecords('business_page','user_id,push_notification,business_name',$where456,'',true);
					$business_name = $resiver['business_name'];

					

					

					$where11 = array('user_id' => $resiver['user_id']);
					if($resiver['push_notification']=='Yes'){
					    $log=$this->Common_model->getRecords('users_log','device_type,device_id',$where11,'',false);
					   
						$count=$this->Common_model->getRecords('users','badge_count',$where11,'',true); 
					      	$iosarray = array(
			                    'alert' => 'Congratulations! Your '.$resiver['business_name'].' is now a verified page.',
			                    'type'  => 'verified',
			                   	'page_id'=> $id,
			                   
			                    'badge' => $count['badge_count'],
			                    'sound' => 'default',
			       			);

							$andarray = array(
				                'message'   =>  'Congratulations! Your '.$resiver['business_name'].' is now a verified page.',
				                'type'      =>'verified',
				               	'page_id'=> $id,
				                'title'     => 'Notification',
			            	);
							$savearray = 'page_id-'.$id;

					    if(!empty($log)){
					    	foreach ($log as $key) {
					    		
					    		if($key['device_type']=='Android'){
									$referrer = androidNotification($key['device_id'],$andarray);
								}

					    		if($key['device_type']=='IOS'){
			                   		$referrer = iosNotification($key['device_id'],$iosarray);
					    		}
					    	}
					    }
					   
					    $add_data =array('user_id' => $resiver['user_id'],'page_id'=>$id,'created_by' =>$resiver['user_id'],'type'=>'verified', 'notification_title'=>'verified', 'notification_description'=> 'Congratulations! Your '.$resiver['business_name'].' is now a verified page.', 'notification_sent_datetime' => date("Y-m-d H:i:s"),'info'=> $savearray);
			    		$this->Common_model->addEditRecords('notifications',$add_data); 

					}


						 	$new_status = "verified";
					} 

					if($this->Common_model->addEditRecords($table_name,array('status'=>$new_status,'modified'=>$date),$where))
					{
						$data = array('msg'=>'success','status' =>$new_status);
					
					} else {
						$data = array('msg'=>'fail','admin_approve' =>$admin_approve);
					}
					echo json_encode($data);
				}
			}
		} 
		public function change_varification(){
			if($this->input->post()) 
			{ 
				$verification= $this->input->post('value');
				$id= $this->input->post('id');
				$where = array('business_page_id' =>$id);
				$date = date("Y-m-d H:i:s");
					if($this->Common_model->addEditRecords('business_page',array('verification'=>$verification,'modified'=>$date),$where))
					{
						$data = array('msg'=>'success','status' =>$verification);
					
					} else {
						$data = array('msg'=>'fail','admin_approve' =>$admin_approve);
					}
					echo json_encode($data);
			}
		
		}

    public function change_status_post(){
		if($this->input->post()) 
		{
			$field = $this->input->post('field'); 
			$id = $this->input->post('id'); 
			$table_name = $this->input->post('table_name');
			$where = array($field=> $id);
			$date = date("Y-m-d H:i:s");
			if($status = $this->Common_model->getFieldValue($table_name,'status',$where)) {
				$new_status = "Active";
				if($status=="Active") {
					$new_status = "Deactive";
				} 

				if($this->Common_model->addEditRecords($table_name,array('status'=>$new_status,'modified'=>$date),$where))
				{
					$data = array('msg'=>'success','status' =>$new_status);
				
				} else {
					$data = array('msg'=>'fail','admin_approve' =>$admin_approve);
				}
				echo json_encode($data);
			}
		}
	}

	public function delete_record()
	{ 
		
		if($this->input->post()) 
		{
			
	        $field = $this->input->post('field'); 
		    $id = $this->input->post('id'); 
			$table = $this->input->post('table_name');
			$where = array($field=> $id);
		    $delete_gallery=$this->Common_model->deleteRecords($table, $where);

	    }
	}

	// public function update_order() {
 //        if($this->input->post('id')!='' && $this->input->post('table')!='' && $this->input->post('field')!='') {
 //            $table = $this->input->post('table');
 //            $order= $this->input->post('order');
 //            $id = $this->input->post('id');
 //            $where_id = $this->input->post('field');
 //            $date = date("Y-m-d H:i:s");
 //           	// if($table=="faq")  {  
 //           	// 	$where_id="id";
 //           	// } else if($table=="brands") {
 //           	//  	$where_id="brand_id";
 //           	// }
 //            $order_data = $this->Common_model->getRecords($table,'orders',array($where_id=>$id),'',true);
            
 //            $old_order = $order_data['orders'];
 //            if($old_order==$order) {
 //                $data = array('msg'=>'No change required on order update.','status'=>2);
 //                echo json_encode($data);exit;
 //            } else if($old_order < $order) {
 //                $query = $this->db->query("select * from $table where orders>$old_order AND orders<=$order");
 //                $outlet_data = $query->result_array();
 //                if($outlet_data) {
 //                    foreach($outlet_data as $row) {
 //                        $this->Common_model->addEditRecords($table,array('orders'=>$row['orders']-1,'modified'=>$date),array($where_id=>$row[$where_id]));
 //                    }
 //                }
 //            } else if($old_order > $order) {
 //                $query = $this->db->query("select * from $table where orders<$old_order AND orders>=$order");
 //                $outlet_data = $query->result_array();
 //                if($outlet_data) {
 //                    foreach($outlet_data as $row) {
 //                        $this->Common_model->addEditRecords($table,array('orders'=>$row['orders']+1,'modified'=>$date),array($where_id=>$row[$where_id]));
 //                    }
 //                }
 //            }

 //            if($this->Common_model->addEditRecords($table,array('orders'=>$order,'modified'=>$date),array($where_id=>$id))) {
 //                $data = array('msg'=>'Order Updated Successfully.','status'=>1);
 //                $note = 'Order updated.';
	// 			$action = 'Edit';
	// 			$record_id = $id;
	// 			add_log($record_id,$action,$table,$note);

 //                $this->session->set_flashdata('success', 'Order Updated Successfully.');
 //                echo json_encode($data); exit;
 //            } else {
 //                $data = array('msg'=>'Some error occured. Please try again !!','status'=>0);
 //                echo json_encode($data); exit;
 //            }
 //        } else {
 //            $data = array('msg'=>'Some error occured. Please try again !!','status'=>0);
 //        }
 //        echo json_encode($data);
 //    }

	public function delete_post(){
		if($this->input->post()) 
		{
	       $field = $this->input->post('field'); 
		    $id = $this->input->post('id'); 
			$table = $this->input->post('table_name');
			$where = array($field=> $id);
		    $delete_gallery=$this->Common_model->deleteRecords($table, $where);

	    }
	}


		public function update_order() {
        if($this->input->post('id')!='' && $this->input->post('table')!='' && $this->input->post('field')!='') {
            $table = $this->input->post('table');
            $order= $this->input->post('order');
            $id = $this->input->post('id');
            $where_id = $this->input->post('field');
            $date = date("Y-m-d H:i:s");
           	// if($table=="faq")  {  
           	// 	$where_id="id";
           	// } else if($table=="brands") {
           	//  	$where_id="brand_id";
           	// }
            $order_data = $this->Common_model->getRecords($table,'orders',array($where_id=>$id),'',true);
            
            $old_order = $order_data['orders'];
            if($old_order==$order) {
                $data = array('msg'=>'No change required on order update.','status'=>2);
                echo json_encode($data);exit;
            } else if($old_order < $order) {
                $query = $this->db->query("select * from $table where orders>$old_order AND orders<=$order");
                $outlet_data = $query->result_array();
                if($outlet_data) {
                    foreach($outlet_data as $row) {
                        $this->Common_model->addEditRecords($table,array('orders'=>$row['orders']-1,'modified'=>$date),array($where_id=>$row[$where_id]));
                    }
                }
            } else if($old_order > $order) {
                $query = $this->db->query("select * from $table where orders<$old_order AND orders>=$order");
                $outlet_data = $query->result_array();
                if($outlet_data) {
                    foreach($outlet_data as $row) {
                        $this->Common_model->addEditRecords($table,array('orders'=>$row['orders']+1,'modified'=>$date),array($where_id=>$row[$where_id]));
                    }
                }
            }

            if($this->Common_model->addEditRecords($table,array('orders'=>$order,'modified'=>$date),array($where_id=>$id))) {
                $data = array('msg'=>'Order Updated Successfully.','status'=>1);
                $note = 'Order updated.';
				$action = 'Edit';
				$record_id = $id;
				// add_log($record_id,$action,$table,$note);

                $this->session->set_flashdata('success', 'Order Updated Successfully.');
                echo json_encode($data); exit;
            } else {
                $data = array('msg'=>'Some error occured. Please try again !!','status'=>0);
                echo json_encode($data); exit;
            }
        } else {
            $data = array('msg'=>'Some error occured. Please try again !!','status'=>0);
        }
        echo json_encode($data);
    }	

/* End of file ajax_controller.php */
/* Location: ./application/controllers/ajax_controller.php */

 


public function delete_page()
{
	    if($this->input->post()) 
		{

			$field = $this->input->post('field'); 
			$id = $this->input->post('id'); 
			$table_name = $this->input->post('table_name');
			$where = array($field=> $id);
			$date = date("Y-m-d H:i:s");
			$status = $this->Common_model->getFieldValue($table_name,'is_deleted',$where);
				echo $status;
				$new_status = "1";
				if($status=="1") {
					$new_status = "0";
				} 

				if($this->Common_model->addEditRecords($table_name,array('is_deleted'=>$new_status,'modified'=>$date),$where))
				{
					$data = array('msg'=>'success','status' =>$new_status);
				
				} else {
					$data = array('msg'=>'fail','admin_approve' =>$admin_approve);
				}
				echo json_encode($data);
			
		
		}
}

public function sponsored(){
 
			if($this->input->post()) 
			{
				$field = $this->input->post('field'); 
				$id = $this->input->post('id'); 
				$table_name = $this->input->post('table_name');
				$where = array('business_page_id' =>$id);
				$date = date("Y-m-d H:i:s");
				if($status = $this->Common_model->getFieldValue($table_name,'sponsored',$where)) {
					$new_status = "Yes";
					if($status=="Yes") {
						$new_status = "No";
					}else{
						 	$new_status = "Yes";
					} 

					if($this->Common_model->addEditRecords($table_name,array('sponsored'=>$new_status,'modified'=>$date),$where))
					{
						$data = array('msg'=>'success','status' =>$new_status);
					
					} else {
						$data = array('msg'=>'fail','admin_approve' =>$admin_approve);
					}
					echo json_encode($data);
				}
			}
	  
		
		}
 

  	public function get_sub_cate()
    {
    	$get_sub_cate = array();
    	$total_arr = array();
       	if(isset($_POST['id'])) {
       		// echo "<pre>";print_r($_POST['id']); die;
       		// $exp = explode(',',$_POST['id']);
       		// foreach ($_POST['id'] as $key => $list) {

       			$get_sub_cate= $this->Common_model->getRecords('sub_categories', 'sub_category_id,category_id,name', array('category_id'=>$_POST['id'],'status'=>'Active'), '', false);
       			// $total_arr =array_merge($get_sub_cate,$total_arr);

       		// }
       		
       	} 
     	//  echo $this->db->last_query();die;
       	//echo $sub_outlets;
        echo json_encode($get_sub_cate); exit;
    }

 
 }
?>