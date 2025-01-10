<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminUsers_controller extends CI_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('AdminUsers_model');
	}

    //VIEWING of USERS/EMPLOYEES for ADMIN Datatable
	public function admin_users() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$unopenedMSRF =  $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedMSRF'] = $unopenedMSRF[0]["COUNT(*)"];
				$unopenedTraccConcern = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccConcern'] = $unopenedTraccConcern[0]["COUNT(*)"];
				$unopenedTraccRequest = $this->Main_model->get_unopened_tracc_request();
				$data['unopenedTraccRequest'] = $unopenedTraccRequest[0]["COUNT(*)"];

				$allowed_menus = ['dashboard', 'system_administration', 'users', 'team'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';

				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_Users/users', $data);
				$this->load->view('admin/footer');
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

    //ADDING FORM Employee in ADMIN
	public function admin_list_employee() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$department_data = $this->Main_model->getDepartment();
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['department_data'] = $department_data;

				$unopenedMSRF =  $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedMSRF'] = $unopenedMSRF[0]["COUNT(*)"];
				$unopenedTraccConcern = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccConcern'] = $unopenedTraccConcern[0]["COUNT(*)"];
				$unopenedTraccRequest = $this->Main_model->get_unopened_tracc_request();
				$data['unopenedTraccRequest'] = $unopenedTraccRequest[0]["COUNT(*)"];
	
				$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';
				$data['active_menu'] = $active_menu;

				if ($department_data[0] == "ok") {
					$data['departments'] = $department_data[1];
				} else {
					$data['departments'] = array();
					echo "No departments found.";
				}
	
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_Users/add_employee', $data);
				$this->load->view('admin/footer');
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

    // Adding employee FUNCTION ADMIN
	public function employee_add() {
		$this->load->helper('form');
		$this->load->library('session');

		$this->form_validation->set_rules('emp_id', 'Employee ID', 'trim|required');

		$user_details = $this->Main_model->user_details();
	
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'message' => validation_errors()
			];
		} else {
			$process = $this->AdminUsers_model->add_employee();

			if ($process[0] == 1) {
				$response = [
					'status' => 'success',
					'message' => $process[1]
				];
			} else {
				$response = [
					'status' => 'error',
					'message' => $process[1]
				];
			}
		}
		echo json_encode($response);
	}

    //Updating Employee FUNCTION for ADMIN
	public function employee_update() {
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		// Retrieve the employee ID from the submitted form data, with XSS filtering
		$id = $this->input->post('id', true);
	
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			$unopenedMSRF =  $this->Main_model->get_unopened_msrf_tickets();
			$data['unopenedMSRF'] = $unopenedMSRF[0]["COUNT(*)"];
			$unopenedTraccConcern = $this->Main_model->get_unopened_tracc_concerns();
			$data['unopenedTraccConcern'] = $unopenedTraccConcern[0]["COUNT(*)"];
			$unopenedTraccRequest = $this->Main_model->get_unopened_tracc_request();
			$data['unopenedTraccRequest'] = $unopenedTraccRequest[0]["COUNT(*)"];
	
			$department_data = $this->Main_model->getDepartment();
	
			if ($user_details[0] == "ok") {
				$process = $this->AdminUsers_model->update_employee();
			
				if ($process[0] == 1) {
					echo json_encode(array('status' => 'success', 'message' => 'Employee is been updated successfully.'));
				} else {
					echo json_encode(array('status' => 'error', 'message' =>  $process[1]));
				}
				return; 
			} else {
				echo json_encode(array('status' => 'error', 'message' => 'Error fetching user information.'));
				return;
			}
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Session expired. Please login again.'));
			return;
		}
	}

    // FORM of Updating Details of a employee/users FOR ADMIN
	public function list_update_employee($id) {
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$department_data = $this->Main_model->getDepartment();
			$users_det = $this->Main_model->users_details_put($id);

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
	
				$data['user_details'] = $user_details[1];
				$data['department_data'] = $department_data;
				$data['users_det'] = $users_det[1];

				$unopenedMSRF =  $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedMSRF'] = $unopenedMSRF[0]["COUNT(*)"];
				$unopenedTraccConcern = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccConcern'] = $unopenedTraccConcern[0]["COUNT(*)"];
				$unopenedTraccRequest = $this->Main_model->get_unopened_tracc_request();
				$data['unopenedTraccRequest'] = $unopenedTraccRequest[0]["COUNT(*)"];
	
				$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';
				$data['active_menu'] = $active_menu;
	

				if ($department_data[0] == "ok") {
					$data['departments'] = $department_data[1];
				} else {
					$data['departments'] = array();
					echo "No departments found.";
				}
	
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_Users/update_employee', $data);
				$this->load->view('admin/footer');
	
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

    //Deleting Employee FUCNTION for ADMIN
	public function employee_delete($id){
		if (is_numeric($id)){
			$status = $this->AdminUsers_model->delete_employee($id);
				if($status){
					echo json_encode(['status' => 'success', 'message' => 'Succesfully Deleted']);
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Failed to delete the Employee.']);
				}
		}else{
			echo json_encode(['status' => 'error', 'message' => 'Invalid Employee ID.']);
		}
	}

	public function inactive_employee($id){
		if (is_numeric($id)){
			$status = $this->AdminUsers_model->update_inactive_employee($id);
			// print_r($status); die();
			if($status){
				echo json_encode(['status' => 'success', 'message' => 'Succesfully Update to Inactive']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Failed to Update the Employee.']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Invalid Employee ID.']);
		}
	}

    

}
?>