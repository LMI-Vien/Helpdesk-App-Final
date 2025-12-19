<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDashboard_controller extends CI_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('AdminDashboard_model');

		if($this->session->userdata('login_data')['role'] == 'L1') {
			show_404();
		}
	}

    // ADMIN Dashboard, Viewing of ADMIN Dashboard
	public function admin_dashboard($active_menu = 'dashboard') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
				$data['unviewedPatchnotes'] = $this->Main_model->get_patchnotes();
				// Fetch total users count
				$data['total_users'] = $this->AdminDashboard_model->get_total_users();
				// Fetch total msrf ticket count
				$data['total_msrf_tickets'] = $this->AdminDashboard_model->get_total_msrf_ticket();
				// Fetch total departments count
				$data['total_departments'] = $this->AdminDashboard_model->get_total_departments();
				// Fetch total tracc concern count
				$data['total_tracc_concern_tickets'] = $this->AdminDashboard_model->get_total_tracc_concern_ticket();
				// Fetch total tracc request count
				$data['total_tracc_request_tickets'] = $this->AdminDashboard_model->get_total_tracc_request_ticket();
				
				$allowed_menus = ['dashboard', 'system_administration', 'other_menu'];
				if (!in_array($active_menu, $allowed_menus)) {
		        	$active_menu = 'dashboard';
		    	}

				$data['active_menu'] = $active_menu;

				$this->check_upload_size();
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/dashboard', $data);
				$this->load->view('admin/footer');

			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("authentication");
			}

		} else {
			$this->session->sess_destroy();
        	$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("authentication");
		}
	}


    public function check_upload_size(){
		$dirs = ['uploads/tracc_concern', 'uploads/msrf', 'uploads/tracc_request']; // Directories to check
		$total_size = 0;

		foreach ($dirs as $dir) {
			$full_path = FCPATH . $dir;
			// Check if the directory exists
			if (is_dir($full_path)) {
				// Iterate through files in the directory
				$files = scandir($full_path);
				foreach ($files as $file) {
					if ($file !== '.' && $file !== '..') {
						$total_size += filesize($full_path . '/' . $file);
					}
				}
			}
		}

		// 1GB
		if ($total_size > 1 * 1024 * 1024 * 1024) {
			// Set a session variable for alert
			$this->session->set_flashdata('upload_alert', 'The total upload size exceeds the limit!');
		}
	}
}
?>