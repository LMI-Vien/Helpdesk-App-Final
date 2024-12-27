<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDept_controller extends CI_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('AdminDept_model');
	}

    //VIEWING of DEPARTMENT for ADMIN Datatable
	public function admin_team() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['dashboard', 'system_administration', 'users', 'team'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';

				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_Department/admin_team', $data);
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

	//ADDING FORM of DEPARTMENT for ADMIN
    public function admin_list_department() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$department_data = $this->Main_model->getDepartment();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['department_data'] = $department_data;

				$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';

				$data['active_menu'] = $active_menu;

				if ($department_data[0] == "ok") {
					// Assign department data to the view data array
					$data['departments'] = $department_data[1];
				} else {
					// Handle the case when no departments are found
					$data['departments'] = array();
					echo "No departments found."; // Output a message for debugging
				}

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_Department/add_department', $data);
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

    // ADDING Department FUNCTION
	public function department_add() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
	
		$this->form_validation->set_rules('dept_desc', 'Department Name', 'trim|required');
	
		if ($this->input->is_ajax_request()) {
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(array('status' => 'error', 'message' => validation_errors()));
				return;
			}
	
			$process = $this->AdminDept_model->add_department();
	
			if ($process[0] == 1) {
				echo json_encode(array('status' => 'success', 'message' => 'Department added successfully!'));
			} else {
				echo json_encode(array('status' => 'error', 'message' => $process[0]));
			}
			return; 
		} else {
			$user_details = $this->Main_model->user_details();
			$data['user_details'] = $user_details[1];
			$allowed_menus = ['dashboard', 'system_administration', 'users', 'other_menu'];
			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_administration';
			$data['active_menu'] = $active_menu;
	
			$this->load->view('admin/header', $data);
			$this->load->view('admin/sidebar', $data);
			$this->load->view('admin/add_department', $data);
			$this->load->view('admin/footer');
		}
	}

    // UPDATING Department FUNCTION
	public function department_update($id) {
		$id = (int) $id;
		$data['user'] = $this->Main_model->get_department_details($id);
		$data['recid'] = $id;

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$dept_desc = $this->input->post('dept_desc');
			$manager_id = $this->input->post('manager_id');
			$sup_id = $this->input->post('sup_id');
	
			$update_data = array(
				'dept_desc' => $dept_desc,
				'manager_id' => $manager_id,
				'sup_id' => $sup_id,
			);
	
			foreach ($update_data as $key => $value) {
				if (empty($value)) {
					unset($update_data[$key]);
				}
			}

			$status = $this->AdminDept_model->update_department($update_data, $id);
	
			if ($status[0] == 1) {
				echo json_encode(array('status' => 'success', 'message' => $status[1]));
			} else {
				echo json_encode(array('status' => 'error', 'message' => $status[1]));
			}
			return; 
		}
	
		$this->load->view('admin/update_department', $data);
	}

    // UPDATING FORM for Department ADMIN
	public function list_update_department($id) {
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$department_data = $this->Main_model->getDepartment();
			$dept_details = $this->Main_model->get_department_details($id);

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
	
				$data['user_details'] = $user_details[1];
				$data['department_data'] = $department_data;
				$data['dept_details'] = $dept_details[1];
	
				$allowed_menus = ['dashboard', 'system_administration', 'departments', 'other_menu'];
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
				$this->load->view('admin/admin_Department/update_department', $data);
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

	// DELETING Department FUNCTION
	public function department_delete($id) {
		if (is_numeric($id)) {
			$status = $this->AdminDept_model->delete_department($id);
				if ($status) {
					echo json_encode(['status' => 'success', 'message' => 'Successfully Deleted']);
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Failed to delete the department.']);
				}
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Invalid department ID.']);
			}
	}
}
?>