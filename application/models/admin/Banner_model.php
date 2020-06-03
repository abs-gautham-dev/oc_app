<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner_model extends CI_Model {

public function __construct()
{
	parent:: __construct();
}



public function insert_banner($data){
$banner_data=array(
					'title' =>$data['title'] ,
					'caption' =>$data['caption'] ,
					'image'=>$data['image']
				);

return $this->db->insert('banner_sliders',$banner_data);
}


public function update_banner($data) {
	$banner_data=array(
					'title' =>$data['title'] ,
					'caption' =>$data['caption'] 
					);
	$this->db->where('banner_id',$data['bid']);
	return $this->db->update('banner_sliders',$banner_data);
}
// list
public function show_banners()
{
	$query= $result=$this->db->get('banner_sliders');

	if ($query->num_rows() > 0) {
           
            return $query;
        }
        return false;

}/* Show_products*/

public function get_banner($id)
{
	$this->db->where('banner_id', $id);
	return $this->db->get('banner_sliders');
}/* Edit Product*/


public function delete_banner_image($id) {

	$this->db->where('banner_id', $id);
	$this->db->delete('banner_sliders'); 
	
}

}/* model end*/

/* End of file product_model.php */
/* Location: ./application/models/banner_sliders.php */

?>