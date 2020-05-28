<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('vendor/autoload.php');

use Twilio\Rest\Client;

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct() {
        parent::__construct();
        error_reporting(0);
        //  ini_set('display_errors', TRUE);
        $this->load->helper(array('email','common_helper'));
        $this->load->library(array('form_validation'));
        $this->load->model('App_model');
        $this->load->model('admin/Common_model');
	
    }
	public function index()
	{
			redirect(base_url().'admin/login');
	
	}

 
	

}
