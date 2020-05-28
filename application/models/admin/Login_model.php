<?php if(! defined('BASEPATH')) exit("No direct script access allowed");


class Login_model extends CI_Model {

	
	function chklogin($data)
	{
		$this->db->select(array('admin_id', 'user_type', 'username'));
		$this->db->from('admin');
		$this->db->where('username',$data['uname']);
		$this->db->where('password',md5($data['pswd']));
		$qry=$this->db->get();
		if($qry->num_rows()>0)
		{
			$result=$qry->row_array();
			return $result;
		}
		else
		{
			return false;
		}
	}
	public function change_password($data)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('password',md5($data['old']));
		$query=$this->db->get();
		if($query->num_rows())
		{
				$this->db->set('password',md5($data['confirm']));
				$this->db->set('modified_at','NOW()',false);
				$this->db->where('id',1);
				$this->db->update('users');
				return true;
		}
		else
		{
			return false;	
		}
	}
	
	public function user_list_all() {
		$query= $result=$this->db->get('users');
		if ($query->num_rows() > 0) {
           
            return $query;
        }
        return false;
	}
	
	public function get_user($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('users');
	} /* Edit Product*/

	
	public function insert_user($data) {
		$user_data=array(
			'user_type' =>$data['user_type'] ,
			'first_name' =>$data['first_name'] ,
			'last_name'=>$data['last_name'],
			'username'=>$data['username'],
			'email'=>$data['email'],
			'password'=>md5($data['password']),
			'status'=>$data['status']
		);

		return $this->db->insert('users',$user_data);

	}/* insert user end*/


	public function update_user($data) {
		$user_data=array('user_type' =>$data['user_type'] ,
			'first_name' =>$data['first_name'] ,
			'last_name' =>$data['last_name'] ,
			'status' =>$data['status'] 
		);
		$this->db->where('id',$data['id']);
		return $this->db->update('users',$user_data);
	}/* update user end*/

	
}