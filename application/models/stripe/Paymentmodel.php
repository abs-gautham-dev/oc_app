<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function insert($data){
        $this->db->insert('payments', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
}