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
					'message' => 'Validation failed',
					'errors' => $this->form_validation->error_array()
				);
				echo json_encode($response);
				exit;
			} else {
				$process = $this->Main_model->login();
	
				if ($process[0] == 1 && $process[1]['status'] == 1) {
					$role = $process[1]['role'];
					$this->session->set_userdata(array('login_data' => $process[1]));
	
					// Set redirect URL based on role
					//$redirect_url = ($role == "L2") ? base_url().'sys/admin/dashboard' : base_url().'sys/users/dashboard';
					if ($role == "L2"){
						$redirect_url = base_url().'sys/admin/dashboard';
					} else if ($role == "L3"){
						$redirect_url = base_url().'sys/admin/dashboard';
					} else {
						$redirect_url = base_url().'sys/users/dashboard';
					}
					$response = array(
						'status' => 'success',
						'message' => 'Login successful',
						'redirect_url' => $redirect_url
					);
					echo json_encode($response);
				} else {
					$response = array(
						'status' => 'error',
						'message' => isset($process['message']) ? $process['message'] : 'Invalid Login Credentials'
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
	
		$page_data['get_departments'] = $this->Main_model->get_departments();
	
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$process = $this->Main_model->user_registration();
	
			if ($process[0] == 1) {
				$response = array(
					'status' => 'success',
					'message' => $process[1]
				);
			} else {
				$response = array(
					'status' => 'error',
					'message' => $process[1]
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
                    redirect(base_url()."sys/admin/users");
                } else {
                    redirect(base_url()."sys/admin/users");
                }
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
                    redirect(base_url()."sys/admin/users");
                } else {
                    redirect(base_url()."sys/admin/users");
                }
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
   	
	// ADMIN APPROVAL for ALL TICKETS
	public function admin_approval_list($subject, $id) {
		if ($this->session->userdata('login_data')) {
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
						redirect("sys/authentication");
				}
	
				if ($user_role == "L2") {
					$data['getTeam'] = $getTeam[1];
				}
	
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
			redirect("sys/authentication");
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
					redirect(base_url()."sys/admin/list/ticket");
				} else {
					$this->session->set_flashdata('error', 'Update failed.');
					redirect(base_url()."sys/admin/list/ticket");
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

	// Users Dashboard
	public function users_dashboard() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$msrfTickets = $this->Main_model->get_msrf($user_details[1]['recid']);
			$traccConcerns = $this->Main_model->get_tracc_concerns($user_details[1]['recid']);
			$traccRequests = $this->Main_model->get_tracc_requests($user_details[1]['recid']);
			$name = $user_details[1]['fname'] . ' ' . $user_details[1]['mname'] . ' ' . $user_details[1]['lname'];

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['msrf'] = $msrfTickets;
				$data['concerns'] = $traccConcerns;
				$data['requests'] = $traccRequests;

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

	// ADMIN FORM for Supplier Request form (PDF ni mam hanna)
	public function supplier_request_form_pdf_view($active_menu = 'supplier_request_form_pdf') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['supplier_request_form_pdf', 'system_administration', 'other_menus'];
				if(!in_array($active_menu, $allowed_menus)) {
					$active_menu = 'dashboard';
				}
				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF_pdf/pdf_supplier_request_form', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->setflashdata('error', 'Error fetching user information.');
				redirect('sys/authentication');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect('sys/authentication');
		}
	}

	// JQuery TABS for Supplier Request Form
	public function sup_req_form_JTabs(){
		$user_role = $this->session->userdata('login_data')['role'];
		$this->load->model('Main_model');
		$tickets = $this->Main_model->get_ticket_counts_supplier_req();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {

				$companies = explode(',', $ticket['company']);

				$checkbox_data = $this->Main_model->get_ticket_checkbox_supplier_req($ticket['recid']); 
				
				$formData = [
					'recid' 						=> $ticket['recid'],
					'ticket_id' 					=> $ticket['ticket_id'],
					'requested_by' 					=> $ticket['requested_by'],
					'companies' 					=> $companies,
					'date' 							=> $ticket['date'],
					'supplier_code' 				=> $ticket['supplier_code'],
					'supplier_account_group' 		=> $ticket['supplier_account_group'],
					'supplier_name' 				=> $ticket['supplier_name'],
					'country_origin' 				=> $ticket['country_origin'],
					'supplier_address' 				=> $ticket['supplier_address'],
					'office_tel'	 				=> $ticket['office_tel'],
					'zip_code' 						=> $ticket['zip_code'],
					'contact_person' 				=> $ticket['contact_person'],
					'terms' 						=> $ticket['terms'],
					'tin_no' 						=> $ticket['tin_no'],
					'pricelist' 					=> $ticket['pricelist'],
					'ap_account' 					=> $ticket['ap_account'],
					'ewt' 							=> $ticket['ewt'],
					'advance_account' 				=> $ticket['advance_account'],
					'vat' 							=> $ticket['vat'],
					'non_vat' 						=> $ticket['non_vat'],
					'payee_1' 						=> $ticket['payee_1'],
					'payee_2' 						=> $ticket['payee_2'],
					'payee_3' 						=> $ticket['payee_3'],
					'driver_name' 					=> $ticket['driver_name'],
					'driver_contact_no' 			=> $ticket['driver_contact_no'],
					'driver_fleet' 					=> $ticket['driver_fleet'],
					'driver_plate_no' 				=> $ticket['driver_plate_no'],
					'helper_name' 					=> $ticket['helper_name'],
					'helper_contact_no' 			=> $ticket['helper_contact_no'],
					'helper_rate_card'		 		=> $ticket['helper_rate_card'],
					'approved_by'					=> $ticket['approved_by'],
					'approved_date'  				=> $ticket['approved_date'],
					'checkbox_data' 				=> $checkbox_data,
				];
				
				$formHtml = $this->load->view('admin/admin_TRF_pdf/trf_supplier_request_form_admin', $formData, TRUE);

				$data[] = [
					'tab_id' 						=> "tabs-" . $ticket['ticket_id'],
					'ticket_id' 					=> $ticket['ticket_id'],
					'count' 						=> $ticket['count'],
					'recid' 						=> $ticket['recid'],
					'form_html' 					=> $formHtml,
				];
			}

			echo json_encode(['message' => 'success', 'data' => $data, 'user_role' => $user_role]);
		} else {
			echo json_encode(['message' => 'failed', 'data' => [], 'user_role' => $user_role]);
		}
	}

	// ADMIN FORM for Employee Request form (PDF ni mam hanna)
	public function employee_request_form_pdf_view($active_menu = 'employee_request_form_pdf') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['employee_request_form_pdf', 'system_administration', 'other_menus'];
				if(!in_array($active_menu, $allowed_menus)) {
					$active_menu = 'dashboard';
				}
				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF_pdf/pdf_employee_request_form', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->setflashdata('error', 'Error fetching user information.');
				redirect('sys/authentication');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect('sys/authentication');
		}
	}

	// JQuery TABS for Employee Request Form
	public function emp_req_form_JTabs(){
		$user_role = $this->session->userdata('login_data')['role'];
		$this->load->model('Main_model');
		$tickets = $this->Main_model->get_ticket_counts_employee_req();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {
				
				$formData = [
					'recid' 					=> $ticket['recid'],
					'ticket_id' 				=> $ticket['ticket_id'],
					'requested_by' 				=> $ticket['requested_by'],
					'name' 						=> $ticket['name'],
					'department' 				=> $ticket['department'],
					'department_desc' 			=> $ticket['department_desc'],
					'position' 					=> $ticket['position'],
					'address' 					=> $ticket['address'],
					'tel_no_mob_no' 			=> $ticket['tel_no_mob_no'],
					'tin_no' 					=> $ticket['tin_no'],
					'contact_person' 			=> $ticket['contact_person'],
					'created_at' 				=> $ticket['created_at'],
					'approved_by' 				=> $ticket['approved_by'],
					'approved_date' 			=> $ticket['approved_date'],
				];
				
				$formHtml = $this->load->view('admin/admin_TRF_pdf/trf_employee_request_form_admin', $formData, TRUE);
				// print_r($formData);
				// die();
			

				$data[] = [
					'tab_id' 					=> "tabs-" . $ticket['ticket_id'],
					'ticket_id' 				=> $ticket['ticket_id'],
					'count' 					=> $ticket['count'],
					'recid' 					=> $ticket['recid'],
					'form_html' 				=> $formHtml,
				];
			}

			echo json_encode(['message' => 'success', 'data' => $data, 'user_role' => $user_role]);
		} else {
			echo json_encode(['message' => 'failed', 'data' => [], 'user_role' => $user_role]);
		}
	}

	// ADMIN FORM for Customer Request form (PDF ni mam hanna)
	public function customer_request_form_pdf_view($active_menu = 'customer_request_form_pdf') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			
			if($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['customer_request_form_pdf', 'system_administration', 'other_menus'];
				if(!in_array($active_menu, $allowed_menus)) {
					$active_menu = 'dashboard';
				}
				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF_pdf/pdf_customer_request_form', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->setflashdata('error', 'Error fetching user information.');
				redirect('sys/authentication');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect('sys/authentication');
		}
	}

	// JQuery TABS for Customer Request Form
	public function cus_req_form_JTabs(){
		$user_role = $this->session->userdata('login_data')['role'];
		$this->load->model('Main_model');
		$tickets = $this->Main_model->get_ticket_counts_customer_req();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {

				$companies = explode(',', $ticket['company']);

				$checkbox_data = $this->Main_model->get_ticket_checkbox_customer_req($ticket['recid']); 

				$formData = [
					'recid' 						=> $ticket['recid'],
					'ticket_id'						=> $ticket['ticket_id'],
					'requested_by' 					=> $ticket['requested_by'],
					'companies' 					=> $companies,
					'date' 							=> $ticket['date'],
					'customer_code' 				=> $ticket['customer_code'],
					'customer_name' 				=> $ticket['customer_name'],
					'tin_no' 						=> $ticket['tin_no'],
					'terms' 						=> $ticket['terms'],
					'customer_address' 				=> $ticket['customer_address'],
					'contact_person' 				=> $ticket['contact_person'],
					'office_tel_no' 				=> $ticket['office_tel_no'],
					'pricelist' 					=> $ticket['pricelist'],
					'payment_group' 				=> $ticket['payment_group'],
					'contact_no' 					=> $ticket['contact_no'],
					'territory'			 			=> $ticket['territory'],
					'salesman' 						=> $ticket['salesman'], 
					'business_style' 				=> $ticket['business_style'], 
					'email' 						=> $ticket['email'],
					'shipping_code' 				=> $ticket['shipping_code'],
					'route_code' 					=> $ticket['route_code'],
					'customer_shipping_address' 	=> $ticket['customer_shipping_address'],
					'landmark' 						=> $ticket['landmark'],
					'window_time_start' 			=> $ticket['window_time_start'],
					'window_time_end' 				=> $ticket['window_time_end'],
					'special_instruction' 			=> $ticket['special_instruction'],
					'created_at' 					=> $ticket['created_at'],
					'approved_by' 					=> $ticket['approved_by'],
					'approved_date' 				=> $ticket['approved_date'],
					'checkbox_data'		 			=> $checkbox_data,
				];

				$formHtml = $this->load->view('admin/admin_TRF_pdf/trf_customer_request_form_admin', $formData, TRUE);			
				$data[] = [
					'tab_id' 						=> "tabs-" . $ticket['ticket_id'],
					'ticket_id' 					=> $ticket['ticket_id'],
					'count' 						=> $ticket['count'],
					'recid'							=> $ticket['recid'],
					'form_html'	 					=> $formHtml,
				];  	
			}
			echo json_encode(['message' => 'success', 'data' => $data, 'user_role' => $user_role]);
		} else {
			echo json_encode(['message' => 'failed', 'data' => [], 'user_role' => $user_role]);
		}
	}

	// ADMIN FORM for Customer Shipping Setup form (PDF ni mam hanna)
	public function customer_shipping_setup_pdf_view($active_menu = 'customer_shipping_setup_pdf') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			
			if($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['customer_shipping_setup_pdf', 'system_administration', 'other_menus'];
				if(!in_array($active_menu, $allowed_menus)) {
					$active_menu = 'dashboard';
				}
				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF_pdf/pdf_customer_shipping_setup_form', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->setflashdata('error', 'Error fetching user information.');
				redirect('sys/authentication');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect('sys/authentication');
		}
	}

	// JQuery TABS for Customer Shipping Setup
	public function cus_ship_setup_JTtabs(){
		$user_role = $this->session->userdata('login_data')['role'];
		$this->load->model('Main_model');
		$tickets = $this->Main_model->get_ticket_counts_customer_ship_setup();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {

				$companies = explode(',', $ticket['company']);

				$formData = [
					'recid' 					=> $ticket['recid'],
					'ticket_id' 				=> $ticket['ticket_id'],
					'requested_by'	 			=> $ticket['requested_by'],
					'companies' 				=> $companies,
					'shipping_code' 			=> $ticket['shipping_code'],
					'route_code' 				=> $ticket['route_code'],
					'customer_address' 			=> $ticket['customer_address'],
					'landmark' 					=> $ticket['landmark'],
					'window_time_start' 		=> $ticket['window_time_start'],
					'window_time_end' 			=> $ticket['window_time_end'],
					'special_instruction' 		=> $ticket['special_instruction'],
					'monday' 					=> $ticket['monday'],
					'tuesday' 					=> $ticket['tuesday'],
					'wednesday' 				=> $ticket['wednesday'],
					'thursday' 					=> $ticket['thursday'],
					'friday' 					=> $ticket['friday'],
					'saturday' 					=> $ticket['saturday'],
					'sunday'					=> $ticket['sunday'],
					'created_at' 				=> $ticket['created_at'],
					'approved_by' 				=> $ticket['approved_by'],
					'approved_date' 			=> $ticket['approved_date'],
				];

				$formHtml = $this->load->view('admin/admin_TRF_pdf/trf_customer_shipping_setup_form_admin', $formData, TRUE);			
				$data[] = [
					'tab_id' 					=> "tabs-" . $ticket['ticket_id'],
					'ticket_id' 				=> $ticket['ticket_id'],
					'count' 					=> $ticket['count'],
					'recid' 					=> $ticket['recid'],
					'form_html' 				=> $formHtml,
				];  	
			}
			echo json_encode(['message' => 'success', 'data' => $data, 'user_role' => $user_role]);
		} else {
			echo json_encode(['message' => 'failed', 'data' => [], 'user_role' => $user_role]);
		}
	}

	// ADMIN FORM for Item Request form (PDF ni mam hanna)
	public function item_request_form_pdf_view($active_menu = 'item_request_form_pdf'){
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			
			if($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['item_request_form_pdf', 'system_administration', 'other_menus'];
				if(!in_array($active_menu, $allowed_menus)) {
					$active_menu = 'dashboard';
				}
				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF_pdf/pdf_item_request_form', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->setflashdata('error', 'Error fetching user information.');
				redirect('sys/authentication');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect('sys/authentication');
		}
	}

	// JQuery TABS for Item Request Form 
	public function item_req_form_JTabs(){
		$user_role = $this->session->userdata('login_data')['role'];
		$this->load->model('Main_model');
		$tickets = $this->Main_model->get_ticket_counts_item_req_form();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {

				$companies = explode(',', $ticket['company']);

				$checkbox_data1 = $this->Main_model->get_ticket_checkbox1_item_req_form($ticket['recid']);
				$checkbox_data2 = $this->Main_model->get_ticket_checkbox2_item_req_form($ticket['ticket_id']);
				$checkbox_data3 = $this->Main_model->get_ticket_checkbox3_item_req_form($ticket['ticket_id']);
				// print_r($checkbox_data3);
				// die();
				$formData = [
					'recid' 						=> $ticket['recid'],
					'ticket_id' 					=> $ticket['ticket_id'],
					'requested_by' 					=> $ticket['requested_by'],
					'companies' 					=> $companies,
					'date' 							=> $ticket['date'],
					'lmi_item_code' 				=> $ticket['lmi_item_code'],
					'long_description' 				=> $ticket['long_description'],
					'short_description' 			=> $ticket['short_description'],
					'item_classification' 			=> $ticket['item_classification'],
					'item_sub_classification' 		=> $ticket['item_sub_classification'],
					'department' 					=> $ticket['department'],
					'merch_category' 				=> $ticket['merch_category'],
					'brand' 						=> $ticket['brand'],
					'supplier_code' 				=> $ticket['supplier_code'],
					'supplier_name' 				=> $ticket['supplier_name'],
					'class' 						=> $ticket['class'],
					'tag' 							=> $ticket['tag'],
					'source' 						=> $ticket['source'],
					'hs_code' 						=> $ticket['hs_code'],
					'unit_cost' 					=> $ticket['unit_cost'],
					'selling_price' 				=> $ticket['selling_price'],
					'major_item_group' 				=> $ticket['major_item_group'],
					'item_sub_group' 				=> $ticket['item_sub_group'],
					'account_type' 					=> $ticket['account_type'],
					'sales' 						=> $ticket['sales'],
					'sales_return' 					=> $ticket['sales_return'],
					'purchases' 					=> $ticket['purchases'],
					'purchase_return' 				=> $ticket['purchase_return'],
					'cgs' 							=> $ticket['cgs'],
					'inventory' 					=> $ticket['inventory'],
					'sales_disc' 					=> $ticket['sales_disc'],
					'gl_department' 				=> $ticket['gl_department'],
					'capacity_per_pallet' 			=> $ticket['capacity_per_pallet'],
					'created_at' 					=> $ticket['created_at'],
					'approved_by' 					=> $ticket['approved_by'],
					'approved_date' 				=> $ticket['approved_date'],
					'checkbox_data1' 				=> $checkbox_data1,
					'checkbox_data2' 				=> $checkbox_data2,
					'checkbox_data3' 				=> $checkbox_data3,
				];

				$formHtml = $this->load->view('admin/admin_TRF_pdf/trf_item_request_form_admin', $formData, TRUE);			
				$data[] = [
					'tab_id' 						=> "tabs-" . $ticket['ticket_id'],
					'ticket_id' 					=> $ticket['ticket_id'],
					'count' 						=> $ticket['count'],
					'recid' 						=> $ticket['recid'],
					'form_html' 					=> $formHtml,
				];  	
			}
			// print_r($checkbox_data2);
			// die();
			echo json_encode(['message' => 'success', 'data' => $data, 'user_role' => $user_role]);
		} else {
			echo json_encode(['message' => 'failed', 'data' => [], 'user_role' => $user_role]);
		}
	}

	public function update_crf_ticket_remarks() {
		$recid = $this->input->post('recid'); 

		$this->load->model('Main_model');
		$result = $this->Main_model->update_crf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
		}
	}

	public function update_css_ticket_remarks() {
		$recid = $this->input->post('recid'); 

		$this->load->model('Main_model');
		$result = $this->Main_model->update_css_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
		}
	}

	public function update_erf_ticket_remarks() {
		$recid = $this->input->post('recid'); 

		$this->load->model('Main_model');
		$result = $this->Main_model->update_erf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
		}
	}

	public function update_irf_ticket_remarks() {
		$recid = $this->input->post('recid'); 

		$this->load->model('Main_model');
		$result = $this->Main_model->update_irf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
		}
	}

	public function update_srf_ticket_remarks() {
		$recid = $this->input->post('recid'); 

		$this->load->model('Main_model');
		$result = $this->Main_model->update_srf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
		}
	}

	public function customer_request_form_rf_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->Main_model->get_customer_req_form_rf_details($id);
			$ticket_numbers = $this->UsersTraccReq_model->get_customer_from_tracc_req_details();
			$form_del_days = $this->Main_model->get_ticket_checkbox_customer_req($id);
			
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['reqForm'] = $customerReqForm[0];
				$data['ticket_numbers'] = $ticket_numbers[0];
				$data['companies'] = explode(',', $customerReqForm[0]['company']);
				$data['del_days'] = $form_del_days;

				$this->load->view('users/header', $data);
				$this->load->view('users/trf_customer_request_form_details', $data);
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
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
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
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
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
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
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
				redirect("sys/authentication");
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	public function approve_crf(){			
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->Main_model->approve_crf($approved_by, $recid);
		
		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/customer_request_form_pdf");
	}

	public function approve_css(){
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->Main_model->approve_css($approved_by, $recid);

		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/customer_shipping_setup_pdf");
	}

	public function approve_erf(){
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->Main_model->approve_erf($approved_by, $recid);

		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/employee_request_form_pdf");
	}

	public function approve_irf(){
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->Main_model->approve_irf($approved_by, $recid);

		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/item_request_form_pdf");
	}

	public function approve_srf(){
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->Main_model->approve_srf($approved_by, $recid);

		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/supplier_request_form_pdf");
	}

	public function get_closed_tickets ($subject, $id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$msrf = $this->Main_model->getTicketsMSRF($id);
			$trc = $this->Main_model->getTraccConcernByID($id);
			$trf = $this->Main_model->getTicketsTRF($id);

			if($user_details[0] == "ok") {
				$data['user_details'] = $user_details[1];
				
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
							redirect("sys/authentication");
						}
				}
			}
		}
	}

	// Edit function (with Bug)
	public function user_edit_customer_request_form_pdf($id) {
		$trf_comp_checkbox_value = isset($_POST['trf_comp_checkbox_value']) ? $_POST['trf_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $trf_comp_checkbox_value);

	// 	$checkbox_cus_req_form_del = [
	// 		'checkbox_outright' => isset($_POST['checkbox_outright']) ? 1 : 0,
	// 		'checkbox_consignment' => isset($_POST['checkbox_consignment']) ? 1 : 0,
	// 		'checkbox_cus_a_supplier' => isset($_POST['checkbox_cus_a_supplier']) ? 1 : 0,
	// 		'checkbox_online' => isset($_POST['checkbox_online']) ? 1 : 0,
	// 		'checkbox_walkIn' => isset($_POST['checkbox_walkIn']) ? 1 : 0,
	// 		'checkbox_monday' => isset($_POST['checkbox_monday']) ? 1 : 0,
	// 		'checkbox_tuesday' => isset($_POST['checkbox_tuesday']) ? 1 : 0,
	// 		'checkbox_wednesday' => isset($_POST['checkbox_wednesday']) ? 1 : 0,
	// 		'checkbox_thursday' => isset($_POST['checkbox_thursday']) ? 1 : 0,
	// 		'checkbox_friday' => isset($_POST['checkbox_friday']) ? 1 : 0,
	// 		'checkbox_saturday' => isset($_POST['checkbox_saturday']) ? 1 : 0,
	// 		'checkbox_sunday' => isset($_POST['checkbox_sunday']) ? 1 : 0,
	// 	];

		$process = $this->Main_model->edit_customer_request_form_pdf($imploded_values, $checkbox_cus_req_form_del, $id);

		$this->session->set_flashdata('editTR', $process[1]);
		redirect(base_url() . 'sys/users/dashboard');
	}
}