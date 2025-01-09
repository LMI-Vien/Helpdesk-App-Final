<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersDashboard_controller extends CI_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('UsersDashboard_model');
	}

	// Users Dashboard
	public function users_dashboard() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$msrfTickets = $this->Main_model->get_msrf($user_details[1]['recid']);
			$traccConcerns = $this->Main_model->get_tracc_concerns($user_details[1]['recid']);
			$traccRequests = $this->Main_model->get_tracc_requests($user_details[1]['recid']);
			$name = $user_details[1]['fname'] . ' ' . $user_details[1]['mname'] . ' ' . $user_details[1]['lname'];
			$cutofftime = new DateTime($this->Main_model->get_cutoff()->time);
			$ticketopen = new DateTime($this->Main_model->get_cutoff()->open_time);

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['msrf'] = $msrfTickets;
				$data['concerns'] = $traccConcerns;
				$data['requests'] = $traccRequests;
				$data['cutofftime'] = $cutofftime;
				$data['ticketopen'] = $ticketopen;

				$this->load->view('users/header', $data);
				$this->load->view('users/dashboard', $data);
				$this->load->view('users/footer', $data);
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
        	$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}
}
?>