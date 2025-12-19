<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
		$this->load->model('Main_model');
	}
	
	
	// LOGIN 
	public function login() {
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('form_validation');
		
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
	
			if ($this->form_validation->run() == FALSE) {
				$response = array(
					'status' => 'error',
					'message' => 'It seems that I can\'t find your credentials',
					'errors' => $this->form_validation->error_array(),
					'attempts_remaining' => null
				);
				echo json_encode($response);
				exit;
			} else {
				$process = $this->Main_model->login();
	
				if (
					is_array($process) &&
					isset($process[0], $process[1]) &&
					is_array($process[1]) &&
					isset($process[1]['status']) &&
					(int)$process[0] === 1 &&
					(int)$process[1]['status'] === 1
				) {
					$role = $process[1]['role'];
					$this->session->set_userdata(array('login_data' => $process[1]));
	
					// Set redirect URL based on role
					if ($role == "L2"){
						$redirect_url = base_url().'admin/dashboard';
					} else if ($role == "L3"){
						$redirect_url = base_url().'admin/dashboard';
					} else {
						$redirect_url = base_url().'users/dashboard';
					}
					$response = array(
						'status' => 'success',
						'message' => 'Login successful',
						'redirect_url' => $redirect_url,
						'attempts_remaining' => null
					);
					echo json_encode($response);
				} else {
					$remaining = isset($process['attempts_remaining']) ? $process['attempts_remaining'] : null;

					$response = array(
						'status' => 'error',
						'message' => isset($process['message']) ? $process['message'] : 'Invalid Login Credentials',
						'attempts_remaining' => $remaining
					);
					echo json_encode($response);
				}
				exit;
			}
		} else {
			$this->load->view('login');
		}
	}
	
	//REGISTRATION
	public function registration() {
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('form_validation');
		
		// Fetch departments from the model
		$page_data['get_departments'] = $this->Main_model->get_departments() ?: [];
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			// Form validation rules
			$this->form_validation->set_rules('employee_id', 'Employee ID', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required|min_length[6]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
			$this->form_validation->set_rules('conpassword', 'Confirm Password', 'required|matches[password]');
	
			if ($this->form_validation->run() == FALSE) {
				$response = array(
					'status' => 'error',
					'message' => validation_errors()
				);
				echo json_encode($response);
				exit;
			}
	
			// Sanitize input using htmlentities to prevent XSS
			$employee_id = htmlentities($this->input->post('employee_id', TRUE));
			$username = htmlentities($this->input->post('username', TRUE));
			$password = $this->input->post('password');
			$conpassword = $this->input->post('conpassword');
			$dept_id = $this->input->post('dept_name');
			$firstname = htmlentities($this->input->post('firstname', TRUE));
			$middlename = htmlentities($this->input->post('middlename', TRUE));
			$lastname = htmlentities($this->input->post('lastname', TRUE));
			$email = htmlentities($this->input->post('email', TRUE));
			$position = htmlentities($this->input->post('position', TRUE));
			$role = $this->input->post('role');
	
			// Process the registration through the model
			$process = $this->Main_model->user_registration($employee_id, $username, $password, $conpassword, $dept_id, $firstname, $middlename, $lastname, $email, $position, $role);
	
			if ($process['status'] === 'success') {
				$response = array(
					'status' => 'success',
					'message' => $process['message']
				);
			} else {
				$response = array(
					'status' => 'error',
					'message' => $process['message']
				);
			}
	
			echo json_encode($response);
			exit;
		}
	
		$this->load->view('registration', $page_data);
	}
	

	// Locking of Users
	public function lock_users() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['data'] = array('user_details' => $user_details[1]);

				$process = $this->Main_model->updated_lock();
				
				if ($process[0] == 1) {
                    redirect(base_url()."admin/users");
                } else {
                    redirect(base_url()."admin/users");
                }
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

	// Unlocking of Users
	public function unlock_users() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['data'] = array('user_details' => $user_details[1]);

				$process = $this->Main_model->updated_unlock();

				if ($process[0] == 1) {
                    redirect(base_url()."admin/users");
                } else {
                    redirect(base_url()."admin/users");
                }
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
   	
	// ADMIN APPROVAL for ALL TICKETS
	public function admin_approval_list($subject, $id) {
		if ($this->session->userdata('login_data')) {
			switch($subject) {
				case "MSRF": {
					$this->Main_model->open_msrf_ticket($id);
				} break;

				case "TRACC_CONCERN": {
					$this->Main_model->open_tracc_concern($id);
				} break;

				case "TRACC_REQUEST": {
					$this->Main_model->open_tracc_request($id);
				} break;
			}

			$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
			$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
			$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
			$data['ict_dept'] = $this->Main_model->get_ict();

			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_role = $this->session->userdata('login_data')['role'];
			$user_dept = $this->session->userdata('login_data')['sup_id'];
			$dept_id = $this->session->userdata('login_data')['dept_id'];
			$user_details = $this->Main_model->user_details();
			$msrf_tickets = $this->Main_model->getTicketsMSRF($id);
			$getTraccCon = $this->Main_model->getTraccConcernByID($id);
			$trf_tickets = $this->Main_model->getTicketsTRF($id);
			$checkbox_newadd = $this->Main_model->getCheckboxDataNewAdd($id);
			$checkbox_update = $this->Main_model->getCheckboxDataUpdate($id);
			$checkbox_account = $this->Main_model->getCheckboxDataAccount($id);
			
			$ict = $this->Main_model->GetICTSupervisor();
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['msrf'] = $msrf_tickets[1];
				$data['tracc_con'] = $getTraccCon[1];
				$data['trf'] = $trf_tickets[1];
				$data['ict'] = $ict;
				$data['checkbox_newadd'] = $checkbox_newadd;
				$data['checkbox_update'] = $checkbox_update;
				$data['checkbox_account'] = $checkbox_account;
				$emp_id = $user_details[1]["emp_id"];
				$getTeam = $this->Main_model->GetTeam($dept_id);
				$data['pages'] = 'tickets';
	
				$allowed_menus = ['dashboard', 'approved_tickets', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'approved_tickets';
	
				$data['active_menu'] = $active_menu;
	
				// Handling TRF tickets company checkboxes
				$checkbox_lmi = $checkbox_rgdi = $checkbox_lpi = $checkbox_sv = 0;
				if (isset($trf_tickets[1]['company'])) {
					// Check if 'company' is a string and explode it into an array
					$company_values = (is_string($trf_tickets[1]['company'])) ? explode(',', $trf_tickets[1]['company']) : $trf_tickets[1]['company'];
					if (is_array($company_values)) {
						$checkbox_lmi = in_array('LMI', $company_values) ? 1 : 0;
						$checkbox_rgdi = in_array('RGDI', $company_values) ? 1 : 0;
						$checkbox_lpi = in_array('LPI', $company_values) ? 1 : 0;
						$checkbox_sv = in_array('SV', $company_values) ? 1 : 0;
					}
				}
	
				$data['checkbox_lmi'] = $checkbox_lmi;
				$data['checkbox_rgdi'] = $checkbox_rgdi;
				$data['checkbox_lpi'] = $checkbox_lpi;
				$data['checkbox_sv'] = $checkbox_sv;
	
				// Switch case to handle different subjects
				switch ($subject) {
					case "TRACC_CONCERN":
						if ($getTraccCon[0] == "ok") {
							$control_number = $getTraccCon[1]['control_number'];
							$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);
							$data['tracc_con'] = $getTraccCon[1];
						} else {
							$data['checkboxes'] = [];
							$data['tracc_con'] = [];
							$this->session->set_flashdata('error', $getTraccCon[1]);
						}
						$this->load->view('admin/header', $data);
						$this->load->view('admin/admin_TRC/tickets_approval_tracc_concern', $data);
						$this->load->view('admin/sidebar', $data);
						$this->load->view('admin/footer');
						break;
	
					case "MSRF":
						$datetime = new Datetime($msrf_tickets[1]['date_requested']);
						$data['msrf']['date_requested'] = $datetime->format('Y-m-d');
						
						$this->load->view('admin/header', $data);
						$this->load->view('admin/admin_MSRF/tickets_approval_msrf', $data);
						$this->load->view('admin/sidebar', $data);
						$this->load->view('admin/footer');
						break;
	
					case "TRACC_REQUEST":
						// No need to repeat checkbox data
						$data['checkbox_newadd'] = $checkbox_newadd;
						$data['checkbox_update'] = $checkbox_update;
						$data['checkbox_account'] = $checkbox_account;
	
						$this->load->view('admin/header', $data);
						$this->load->view('admin/admin_TRF/tickets_approval_tracc_request', $data);
						$this->load->view('admin/sidebar', $data);
						$this->load->view('admin/footer');
						break;
	
					default:
						$this->session->set_flashdata('error', 'Invalid subject provided.');
						redirect("authentication");
				}
	
				if ($user_role == "L2") {
					$data['getTeam'] = $getTeam[1];
				}
	
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
			redirect("authentication");
		}
	}

	// DI PA ALAM FUNCTION
	public function dept_supervisor_approval() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();
			if ($user_details[0] == "ok") {

				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';

				$process = $this->Main_model->status_approval_msrf();
				if ($process[0] == 1) {
					$this->session->set_flashdata('success', 'Tickets Approved');
					redirect(base_url()."admin/list/ticket");
				} else {
					$this->session->set_flashdata('error', 'Update failed.');
					redirect(base_url()."admin/list/ticket");
				}
				
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect(base_url()."admin/login");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
            redirect(base_url()."admin/login");
		}
	}

	public function SendEmail() {
		// function email
	}

	// logout
	public function logout() {
		if ($this->session->userdata('login_data')) {
			$this->session->unset_userdata('login_data');
			redirect(base_url()."");
		} else {
			$this->session->set_flashdata('error', 'Please login first before you can access this page.');
			redirect(base_url()."");
		}
	}

	// download file 
	public function download_file($file_name) {
		// Path to the file
		$file_path = FCPATH . 'uploads/tracc_con/' . $file_name;
	
		// Check if file exists
		if (file_exists($file_path)) {
			// Load the download helper
			$this->load->helper('download');
	
			// Get the file's original name
			$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
	
			// Set the correct content type based on file extension
			switch ($file_extension) {
				case 'pdf':
					$mime_type = 'application/pdf';
					break;
				case 'jpg':
				case 'jpeg':
					$mime_type = 'image/jpeg';
					break;
				case 'png':
					$mime_type = 'image/png';
					break;
				case 'doc':
					$mime_type = 'application/msword';
					break;
				case 'docx':
					$mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
					break;
				default:
					$mime_type = 'application/octet-stream'; // Fallback for unknown types
					break;
			}
	
			// Force download
			force_download($file_path, NULL, $mime_type);
		} else {
			// Show error if file does not exist
			show_404();
		}
	}

	//checking the size of storage for upload files
	// DONE for admindashboardcontroller
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
	
	public function save_ticket(){
		$login_data = $this->session->userdata('login_data');
		$user_id = $login_data['user_id'];
		$recid = $this->input->post('recid');
		$data_module = $this->input->post('data_module');
		$data_remarks = $this->input->post('data_remarks');
		$data_status = $this->input->post('data_status');

		if($recid){
			$status = $this->Main_model->update_department_status($data_module, $recid, $data_remarks, $data_status);
			if($status[0] === 1){
				$data_array = array(
					'recid' 			=> $recid,
					'module' 			=> $data_module,
					'remarks' 			=> $data_remarks,
					'status'			=> $data_status,
					'updated_by' 		=> $user_id,
					'created_date' 		=> date('Y-m-d H:i:s')
				);

				$this->Main_model->save_data('tickets_approval_history', $data_array);
				echo json_encode(['status' => 'success', 'message' => 'Succesfully Updated!']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Failed to update Department approval status.']);
			}
		}else{
			echo json_encode(['status' => 'error', 'message' => 'Failed to update Department approval status.']);
		}
	}

	// UPDATING TICKET for HEAD
	public function update_ticket(){
		$ict_approval = $this->input->post('ict_approval', TRUE);
		$reason_rejected = $this->input->post('reason_rejected', TRUE);
		$control_number = $this->input->post('control_number', TRUE);
		$module_name = $this->input->post('module', TRUE);
		$data_id = $this->input->post('data_id', TRUE);
		
		$field = "ticket_id";
		if($module_name === "tracc-concern"){
			$table = "service_request_tracc_concern";
			$field = "control_number";
			$data = array(
				"it_approval_status" => $ict_approval,
				"reason_reject_tickets" => $reason_rejected
			);
		}else if($module_name === "tracc-request"){
			$table = "service_request_tracc_request";
			$data = array(
				"it_approval_status" => $ict_approval,
				"reason_reject_ticket" => $reason_rejected
			);
		}else{
			$table = "service_request_msrf";
			$data = array(
				"it_approval_status" => $ict_approval,
				"remarks_ict" => $reason_rejected
			);
		}
		// print_r($data);
		// die();
		if($ict_approval){
			$process = $this->Tickets_model->update_data($table, $data, $field, $data_id);
			if($process === "success"){
				echo json_encode(array("message" => "success"));
			}else{
				echo json_encode(array("message" => "failed"));
			}
			
		}
	}

	public function customer_request_form_ss_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->Main_model->get_customer_req_form_ss_details($id);
			$ticket_numbers = $this->Main_model->get_customer_shipping_setup_from_tracc_req_mf_new_add();
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['ticket_numbers'] = $ticket_numbers[0];
				$data['companies'] = explode(',', $customerReqForm[0]['company']);

				$this->load->view('users/header', $data);
				$this->load->view('users/trf_customer_shipping_setup_details', $data);
				$this->load->view('users/footer', $data);
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

	public function customer_request_form_ir_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->Main_model->get_customer_req_form_ir_details($id);
			$checkboxes = $this->Main_model->get_ticket_checkbox1_item_req_form($id);
			$checkboxes2 = $this->Main_model->get_ticket_checkbox2_item_req_form($customerReqForm[0]['ticket_id']);
			$checkboxes3 = $this->Main_model->get_ticket_checkbox3_item_req_form($customerReqForm[0]['ticket_id']);
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['companies'] = explode(',', $customerReqForm[0]['company']);
				$data['checkboxes'] = $checkboxes;
				$data['checkboxes2'] = $checkboxes2;
				$data['checkboxes3'] = $checkboxes3;	

				$this->load->view('users/header', $data);
				$this->load->view('users/trf_item_request_details', $data);
				$this->load->view('users/footer', $data);
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

	public function customer_request_form_er_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->Main_model->get_customer_req_form_er_details($id);
			$departments_result = $this->Main_model->getDepartment();
			$departments = ($departments_result[0] == "ok") ? $departments_result[1] : [];
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['departments'] = $departments;

				$this->load->view('users/header', $data);
				$this->load->view('users/trf_employee_request_details', $data);
				$this->load->view('users/footer', $data);
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

	public function customer_request_form_sr_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->Main_model->get_customer_req_form_sr_details($id);
			$checkboxes = $this->Main_model->get_ticket_checkbox_supplier_req_by_ticket_id($customerReqForm[0]['ticket_id']);
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['companies'] = explode(',', $customerReqForm[0]['company']);
				$data['checkboxes'] = $checkboxes[0];

				$this->load->view('users/header', $data);
				$this->load->view('users/trf_supplier_request_form_details', $data);
				$this->load->view('users/footer', $data);
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

	public function get_closed_tickets ($subject, $id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$msrf = $this->Main_model->getTicketsMSRF($id);
			$trc = $this->Main_model->getTraccConcernByID($id);
			$trf = $this->Main_model->getTicketsTRF($id);
			$ict_dept = $this->Main_model->get_ict();

			if($user_details[0] == "ok") {
				$data['user_details'] = $user_details[1];

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
				$data['ict_dept'] = $ict_dept;
				
				$allowed_menus = ['dashboard', 'closed_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'closed_tickets_list';
				
				$data['active_menu'] = $active_menu;
				
				switch ($subject) {
					case "MSRF":
						{
							$data['msrf'] = $msrf[1];
							$this->load->view('admin/header', $data);
							$this->load->view('admin/sidebar', $data);
							$this->load->view('admin/admin_MSRF/closed_msrf_details', $data);
							$this->load->view('admin/footer');

						}
						break;

					case "TRACC_CONCERN":
						{
							$data['trc'] = $trc[1];
							$data['checkboxes'] = $this->Main_model->get_checkbox_values($trc[1]['control_number']);
							
							$this->load->view('admin/header', $data);
							$this->load->view('admin/sidebar', $data);
							$this->load->view('admin/admin_TRC/closed_tracc_concern_details', $data);
							$this->load->view('admin/footer');
						}
						break;

					case "TRACC_REQUEST":
						{
							$data['trf'] = $trf[1];
							$data['checkbox_newadd'] = $this->Main_model->getCheckboxDataNewAdd($id);
							$data['checkbox_update'] = $this->Main_model->getCheckboxDataUpdate($id);
							$data['checkbox_account'] = $this->Main_model->getCheckboxDataAccount($id);

							$data['company'] = explode(',', $trf['1']['company']);

							$this->load->view('admin/header', $data);
							$this->load->view('admin/sidebar', $data);
							$this->load->view('admin/admin_TRF/closed_tracc_req_details', $data);
							$this->load->view('admin/footer');
						}
						break;

					default:
						{
							$this->session->flashdata('error', 'nvalid subject provided');
							redirect("authentication");
						}
				}
			}
		}
	}

	public function get_rejected_tickets($subject, $id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$msrf = $this->Main_model->getTicketsMSRF($id);
			$trc = $this->Main_model->getTraccConcernByID($id);
			$trf = $this->Main_model->getTicketsTRF($id);
			$ict_dept = $this->Main_model->get_ict();

			if($user_details[0] == "ok") {
				$data['user_details'] = $user_details[1];

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
				$data['ict_dept'] = $ict_dept;
				
				$allowed_menus = ['dashboard', 'open_tickets', 'rejected_tickets_list', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'rejected_tickets_list';
				
				$data['active_menu'] = $active_menu;

				switch ($subject) {
					case "MSRF":
						{
							$data['msrf'] = $msrf[1];
							$this->load->view('admin/header', $data);
							$this->load->view('admin/sidebar', $data);
							$this->load->view('admin/admin_MSRF/rejected_msrf_details', $data);
							$this->load->view('admin/footer');

						}
						break;

					case "TRACC_CONCERN":
						{
							$data['trc'] = $trc[1];
							$data['checkboxes'] = $this->Main_model->get_checkbox_values($trc[1]['control_number']);
							
							$this->load->view('admin/header', $data);
							$this->load->view('admin/sidebar', $data);
							$this->load->view('admin/admin_TRC/rejected_tracc_concern_details', $data);
							$this->load->view('admin/footer');
						}
						break;

					case "TRACC_REQUEST":
						{
							$data['trf'] = $trf[1];
							$data['checkbox_newadd'] = $this->Main_model->getCheckboxDataNewAdd($id);
							$data['checkbox_update'] = $this->Main_model->getCheckboxDataUpdate($id);
							$data['checkbox_account'] = $this->Main_model->getCheckboxDataAccount($id);

							$data['company'] = explode(',', $trf['1']['company']);

							$this->load->view('admin/header', $data);
							$this->load->view('admin/sidebar', $data);
							$this->load->view('admin/admin_TRF/rejected_tracc_request_details', $data);
							$this->load->view('admin/footer');
						}
						break;

					default:
						{
							$this->session->flashdata('error', 'nvalid subject provided');
							redirect("authentication");
						}
				}
			}
		}
	}
	
	public function updatePatchViews() {
		$patches = $this->input->post('patches');
		$user = $this->input->post('user_id');
		
		$this->Main_model->update_patchnotes_views($patches, $user);
	}

}