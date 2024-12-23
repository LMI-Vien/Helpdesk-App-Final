<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceRequests extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->view('admin/service_request_form');
    }

    public function tracc_form() {
        $this->load->view('admin/tracc_request_form');
    }

    public function submit_request() {

    }
}
?>