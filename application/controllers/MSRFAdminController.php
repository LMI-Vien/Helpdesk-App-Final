<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MSRFAdminController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('MSRFAdminModel');
    }

    
}
?>