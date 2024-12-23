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
	
	// code ni sir gilbert
	/*public function login() {
		$this->load->helper('form');
		$this->load->library('session');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]|max_length[12]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[2]|max_length[150]');
		
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('login');
		} else {
			$process = $this->Main_model->login();
	
			if ($process[0] == 1 && $process[1]['status'] == 1) {
				$role = $process[1]['role'];
				$this->session->set_userdata(array('login_data' => $process[1]));
				
				if ($role == "L2") {
					$this->session->set_flashdata('success', 'Login successful!');
					redirect(base_url().'sys/admin/dashboard');
				} else {
					$this->session->set_flashdata('success', 'Login successful!');
					redirect(base_url().'sys/users/dashboard');
				}
			} else {
				$this->session->set_flashdata('error', $process['message']);
				redirect("sys/authentication");
			}
		}
	}*/
	
	// LOGIN 
	public function login() {
		// Load helpers and libraries
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('form_validation');
		
		// Check if the request is a POST request
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			// Set validation rules
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
	
			if ($this->form_validation->run() == FALSE) {
				// Return validation errors in JSON format if validation fails
				$response = array(
					'status' => 'error',
					'message' => 'Validation failed',
					'errors' => $this->form_validation->error_array()
				);
				echo json_encode($response);
				exit; // Ensure no further code execution
			} else {
				// Process login
				$process = $this->Main_model->login();
	
				if ($process[0] == 1 && $process[1]['status'] == 1) {
					// Successful login
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
					// Return success response with redirect URL
					$response = array(
						'status' => 'success',
						'message' => 'Login successful',
						'redirect_url' => $redirect_url
					);
					echo json_encode($response);
				} else {
					// Login failed, return error message
					$response = array(
						'status' => 'error',
						'message' => isset($process['message']) ? $process['message'] : 'Invalid Login Credentials'
					);
					echo json_encode($response);
				}
				exit; // Ensure no further code execution
			}
		} else {
			// If not a POST request, load the login view (initial page load)
			$this->load->view('login');
		}
	}
	
	//REGISTRATION
	public function registration() {
		$this->load->helper('form');
		$this->load->library('session');
	
		// Fetch departments data
		$page_data['get_departments'] = $this->Main_model->get_departments();
	
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$process = $this->Main_model->user_registration();
	
			if ($process[0] == 1) {
				// Successful registration
				$response = array(
					'status' => 'success',
					'message' => $process[1]
				);
			} else {
				// Registration failed
				$response = array(
					'status' => 'error',
					'message' => $process[1]
				);
			}
	
			// Return JSON response
			echo json_encode($response);
			exit; // Prevent loading the view after response
		}
	
		// If not POST request, load the registration view
		$this->load->view('registration', $page_data);
	}
	
	
	// ADMIN Dashboard, Viewing of ADMIN Dashboard
	/* public function admin_dashboard($active_menu = 'dashboard') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				// Fetch total users count
				$data['total_users'] = $this->Main_model->get_total_users();
				// Fetch total msrf ticket count
				$data['total_msrf_tickets'] = $this->Main_model->get_total_msrf_ticket();
				// Fetch total departments count
				$data['total_departments'] = $this->Main_model->get_total_departments();
				// Fetch total tracc concern count
				$data['total_tracc_concern_tickets'] = $this->Main_model->get_total_tracc_concern_ticket();
				// Fetch total tracc request count
				$data['total_tracc_request_tickets'] = $this->Main_model->get_total_tracc_request_ticket();
				
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
				redirect("sys/authentication");
			}

		} else {
			$this->session->sess_destroy();
        	$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	} */

	// VIEWING of ADMIN for GENERATING REPORTS
	/* public function admin_print_report($active_menu = 'print') {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['print', 'system_administration', 'other_menus'];
				if(!in_array($active_menu, $allowed_menus)) {
					$active_menu = 'dashboard';
				}

				$data['active_menu'] = $active_menu;

				$this->check_upload_size();
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/reports_table', $data);
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
	} */

	//VIEWING of USERS/EMPLOYEES for ADMIN Datatable
	/* public function admin_users() {
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
				$this->load->view('admin/users', $data);
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
	} */

	//VIEWING of DEPARTMENT for ADMIN Datatable
	/* public function admin_team() {
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
				$this->load->view('admin/admin_team', $data);
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
	} */

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

	//TRACC CONCERN List of Ticket for ADMIN
	public function admin_list_tracc_concern($active_menu = 'system_tickets_list'){
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
	
				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';
				// print_r($active_menu);
				// die();

				$data['active_menu'] = $active_menu;
				
				$data['checkboxes'] = [
					'for_mis_concern' => 0,
					'for_lst_concern' => 0,
					'system_error' => 0,
					'user_error' => 0
					];

				if ($this->input->post()) {
					$control_number = $this->input->post('control_number');
					$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);
					$received_by = $this->input->post('received_by');
					$noted_by = $this->input->post('noted_by');
					$priority = $this->input->post('priority');
					$approval_stat = $this->input->post('app_stat');
					$reject_ticket = $this->input->post('reason_rejected');
					$solution = $this->input->post('solution');
					$resolved_by = $this->input->post('resolved_by');
					$resolved_date = $this->input->post('res_date');
					$others = $this->input->post('others');
					$received_by_lst = $this->input->post('received_by_lst');
					$date_lst = $this->input->post('date_lst');

					$checkbox_data = [
						'control_number' => $control_number,
						'for_mis_concern' => $this->input->post('checkbox_mis') ? 1 : 0,
						'for_lst_concern' => $this->input->post('checkbox_lst') ? 1 : 0,
						'system_error' => $this->input->post('checkbox_system_error') ? 1 : 0,
						'user_error' => $this->input->post('checkbox_user_error') ? 1 : 0,
					];

					$process = $this->Main_model->status_approval_tracc_concern($control_number, $received_by, $noted_by, $priority, $approval_stat, $reject_ticket, $solution, $resolved_by, $resolved_date, $others, $received_by_lst, $date_lst);
					$process_checkbox = $this->Main_model->insert_checkbox_data($checkbox_data);
	
					if ($process[0] == 1 && $process_checkbox[0] == 1) {
						$this->session->set_flashdata('success', 'Tickets Approved');
					} else {
						$this->session->set_flashdata('error', 'Updated.');
					}
	
					redirect(base_url()."sys/admin/list/ticket/tracc_concern");
				}

				// Load views
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/tickets_tracc_concern', $data);
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

	//TRACC REQUEST List of Ticket for ADMIN
	public function admin_list_tracc_request(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';

				$data['active_menu'] = $active_menu;

				if ($this->input->post()) {
					$trf_number = $this->input->post('trf_number');
					$approval_stat = $this->input->post('app_stat');

					$process = $this->Main_model->status_approval_trf($trf_number, $app_stat);
					print_r($process);
					
					if (isset($process[0]) && $process[0] == 1) {
						$this->session->set_flashdata('success', "Ticket's been Updated");
					} else {
						$this->session->set_flashdata('error', 'Update failed.');
					}

					redirect(base_url()."sys/admin/list/ticket/tracc_request");
				}
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/tickets_tracc_request', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	//MSRF List of Ticket for ADMIN
	public function admin_list_tickets($active_menu = 'system_tickets_list') {
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
	
				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';
				// print_r($active_menu);
				// die();
	
				$data['active_menu'] = $active_menu;
	
				if ($this->input->post()) {
					$msrf_number = $this->input->post('msrf_number');
					$approval_stat = $this->input->post('approval_stat');
					$rejecttix = $this->input->post('rejecttix');
					
					$process = $this->Main_model->status_approval_msrf($msrf_number, $approval_stat, $rejecttix);
					
					if (isset($process[0]) && $process[0] == 1) {
						//Tickets Approved
						$this->session->set_flashdata('success', "Ticket's been Updated");
					} else {
						$this->session->set_flashdata('error', 'Update failed.');
					}
					redirect(base_url()."sys/admin/list/ticket/msrf");
				}
	
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/tickets_msrf', $data);
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
	/* public function admin_list_department() {
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
				$this->load->view('admin/add_department', $data);
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
	} */


	//ADDING FORM Employee in ADMIN
	/* public function admin_list_employee() {
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
					$data['departments'] = $department_data[1];
				} else {
					$data['departments'] = array();
					echo "No departments found.";
				}
	
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/add_employee', $data);
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
	} */
	
	// Adding employee FUNCTION ADMIN
	/* public function employee_add() {
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
			$process = $this->Main_model->add_employee();

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
	} */

	//Updating Employee FUNCTION for ADMIN
	/* public function employee_update() {
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		// Retrieve the employee ID from the submitted form data, with XSS filtering
		$id = $this->input->post('id', true);
	
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();
	
			$department_data = $this->Main_model->getDepartment();
	
			if ($user_details[0] == "ok") {
				$process = $this->Main_model->update_employee();
			
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
	} */

	// Deleting Employee FUCNTION for ADMIN
	/* public function employee_delete($id){
		if (is_numeric($id)){
			$status = $this->Main_model->delete_employee($id);
				if($status){
					echo json_encode(['status' => 'success', 'message' => 'Succesfully Deleted']);
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Failed to delete the Employee.']);
				}
		}else{
			echo json_encode(['status' => 'error', 'message' => 'Invalid Employee ID.']);
		}
	} */

	// FORM of Updating Details of a employee/users FOR ADMIN
	/* public function list_update_employee($id) {
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$department_data = $this->Main_model->getDepartment();
			$users_det = $this->Main_model->users_details_put($id);

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
	
				$data['user_details'] = $user_details[1];
				$data['department_data'] = $department_data;
				$data['users_det'] = $users_det[1];
	
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
				$this->load->view('admin/update_employee', $data);
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
	} */
	
	/* public function admin_approval_list($subject, $id) {
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

				// Handle TRACC Concern data
				if ($subject == "TRACC_CONCERN") {
					if ($getTraccCon[0] == "ok") {
						// Access control_number safely
						$control_number = $getTraccCon[1]['control_number'];
						$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);
						$data['tracc_con'] = $getTraccCon[1]; // Store TRACC concern data
					} else {
						// Handle the case where no TRACC Concern data was found
						$data['checkboxes'] = []; // Set to empty array if no data
						$data['tracc_con'] = []; // Set to empty array if no data
						$this->session->set_flashdata('error', $getTraccCon[1]); // Use the error message returned
					}
	
					// Load TRACC Concern views
					$this->load->view('admin/header', $data);
					$this->load->view('admin/tickets_approval_tracc_concern', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/footer');
	
				} else if ($subject == "MSRF") {
					$this->load->view('admin/header', $data);
					$this->load->view('admin/tickets_approval_msrf', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/footer');

				} else if ($subject == "TRACC_REQUEST"){
					$checkbox_lmi = 0;
					$checkbox_rgdi = 0;
					$checkbox_lpi = 0;
					$checkbox_sv = 0;

					if (isset($trf_tickets[1]['company'])) {
						// Check if 'company' is a string and explode it into an array
						$company_values = (is_string($trf_tickets[1]['company'])) ? explode(',', $trf_tickets[1]['company']) : $trf_tickets[1]['company'];
			
						// Ensure that $company_values is an array
						if (is_array($company_values)) {
							$checkbox_lmi = in_array('LMI', $company_values) ? 1 : 0;
							$checkbox_rgdi = in_array('RGDI', $company_values) ? 1 : 0;
							$checkbox_lpi = in_array('LPI', $company_values) ? 1 : 0;
							$checkbox_sv = in_array('SV', $company_values) ? 1 : 0;
						}
					}
					// Passing data to view
					$data['checkbox_lmi'] = $checkbox_lmi;
					$data['checkbox_rgdi'] = $checkbox_rgdi;
					$data['checkbox_lpi'] = $checkbox_lpi;
					$data['checkbox_sv'] = $checkbox_sv;

					$data['checkbox_newadd'] = $checkbox_newadd;
					$data['checkbox_update'] = $checkbox_update;
					$data['checkbox_account'] = $checkbox_account;

					$this->load->view('admin/header', $data);
					$this->load->view('admin/tickets_approval_tracc_request', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/footer');
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
	} */
   	
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
						$this->load->view('admin/tickets_approval_tracc_concern', $data);
						$this->load->view('admin/sidebar', $data);
						$this->load->view('admin/footer');
						break;
	
					case "MSRF":
						$this->load->view('admin/header', $data);
						$this->load->view('admin/tickets_approval_msrf', $data);
						$this->load->view('admin/sidebar', $data);
						$this->load->view('admin/footer');
						break;
	
					case "TRACC_REQUEST":
						// No need to repeat checkbox data
						$data['checkbox_newadd'] = $checkbox_newadd;
						$data['checkbox_update'] = $checkbox_update;
						$data['checkbox_account'] = $checkbox_account;
	
						$this->load->view('admin/header', $data);
						$this->load->view('admin/tickets_approval_tracc_request', $data);
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
	

	/* public function admin_approval_list($subject, $id) {
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_role = $this->session->userdata('login_data')['role'];
			$user_dept = $this->session->userdata('login_data')['sup_id'];
			$dept_id = $this->session->userdata('login_data')['dept_id'];
			$user_details = $this->Main_model->user_details();
			$msrf_tickets = $this->Main_model->getTicketsMSRF($id);
			$getTraccCon = $this->Main_model->getTraccConcernByID($id);
			$ict = $this->Main_model->GetICTSupervisor();
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['msrf'] = $msrf_tickets[1];
				$data['ict'] = $ict;
				$emp_id = $user_details[1]["emp_id"];
				$getTeam = $this->Main_model->GetTeam($dept_id);
				$data['pages'] = 'tickets';
	
				// Handle the active menu logic
				$allowed_menus = ['dashboard', 'approved_tickets', 'users', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'approved_tickets';
				$data['active_menu'] = $active_menu;
	
				// Handle TRACC Concern data
				if ($subject == "TRACC_CONCERN") {
					if ($getTraccCon[0] == "ok") {
						// Access control_number safely
						$control_number = $getTraccCon[1]['control_number'];
						$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);
						$data['tracc_con'] = $getTraccCon[1]; // Store TRACC concern data
					} else {
						// Handle the case where no TRACC Concern data was found
						$data['checkboxes'] = []; // Set to empty array if no data
						$data['tracc_con'] = []; // Set to empty array if no data
						$this->session->set_flashdata('error', $getTraccCon[1]); // Use the error message returned
					}
	
					// Load TRACC Concern views
					$this->load->view('admin/header', $data);
					$this->load->view('admin/tickets_approval_tracc_concern', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/footer');
	
				} else if ($subject == "MSRF") {
					$this->load->view('admin/header', $data);
					$this->load->view('admin/tickets_approval_msrf', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/footer');
				}
	
				// Other logic for L2 user role if applicable
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
	} */

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

	// ADDING Department FUNCTION
	/* public function department_add() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
	
		$this->form_validation->set_rules('dept_desc', 'Department Name', 'trim|required');
	
		if ($this->input->is_ajax_request()) {
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(array('status' => 'error', 'message' => validation_errors()));
				return;
			}
	
			$process = $this->Main_model->add_department();
	
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
	} */
	
	// UPDATING Department FUNCTION
	/* public function department_update($id) {
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

			$status = $this->Main_model->update_department($update_data, $id);
	
			if ($status[0] == 1) {
				echo json_encode(array('status' => 'success', 'message' => $status[1]));
			} else {
				echo json_encode(array('status' => 'error', 'message' => $status[1]));
			}
			return; 
		}
	
		$this->load->view('admin/update_department', $data);
	} */
	
	// DELETING Department FUNCTION
	/* public function department_delete($id) {
		if (is_numeric($id)) {
			$status = $this->Main_model->delete_department($id);
				if ($status) {
					echo json_encode(['status' => 'success', 'message' => 'Successfully Deleted']);
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Failed to delete the department.']);
				}
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Invalid department ID.']);
			}
	} */

	// UPDATING FORM for Department ADMIN
	/* public function list_update_department($id) {
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
				$this->load->view('admin/update_department', $data);
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
	} */


	/* public function details_tickets_list() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			$this->load->view('admin/header', $data);
			$this->load->view('admin/sidebar', $data);
			$this->load->view('admin/tickets_msrf');
			$this->load->view('admin/footer');

		} else {
			$this->session->sess_destroy();
        	$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	} */

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

	 /*public function users_creation_tickets_msrf() {
	 	$id = $this->session->userdata('login_data')['user_id'];
	 	$this->load->helper('form');
         $this->load->library('session');
	 	$this->form_validation->set_rules('trn_number', 'Ticket ID', 'trim|required');
	 	$user_details = $this->Main_model->user_details();
	 	$department_data = $this->Main_model->getDepartment();
	 	$getdepartment = $this->Main_model->GetDepartmentID();
	 	$users_det = $this->Main_model->users_details_put($id);
		
	 	if ($this->form_validation->run() == FALSE) {
	 		$data['user_details'] = $user_details[1];
	 		$msrf = $this->GenerateMSRFNo();
			
	 		$data['getdept'] = $getdepartment[1];
	 		$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();

	 		$data['msrf'] = $msrf;
	 		$data['department_data'] = $department_data;
	 		$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();

	 		if ($department_data[0] == "ok") {
	 			$data['departments'] = $department_data[1];
	 		} else {
	 			$data['departments'] = array();
	 			echo "No departments found.";
	 		}

	 		$this->load->view('users/header', $data);
	 		$this->load->view('users/service_request_form_msrf_creation', $data);
	 		$this->load->view('users/footer');
	 	} else {
	 		$process = $this->Main_model->msrf_add_ticket();
	 		if ($process[0] == 1) {
	 			$this->session->set_flashdata('success', $process[1]);
	 			redirect(base_url().'sys/users/list/tickets/msrf');
	 		} else {
	 			$this->session->set_flashdata('error', $process[1]);
	 			redirect(base_url().'sys/users/list/tickets/msrf');
	 		}
	 	}
	 }*/

	/*public function user_creation_tickets_tracc_concern() {
		// Get the logged-in user's ID from session data
		$id = $this->session->userdata('login_data')['user_id'];
	
		// Load necessary helpers and libraries
		$this->load->helper('form');
		$this->load->library('session');
	
		// Set form validation for 'control_number' field (user-inputted, required)
		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required');
	
		// Retrieve user details and department information
		$user_details = $this->Main_model->user_details();              // Get logged-in user's basic details
		$getdepartment = $this->Main_model->GetDepartmentID();          // Get department ID for the user
		$users_det = $this->Main_model->users_details_put($id);         // Get detailed user info based on the user ID
	
		// Check if form validation failed
		if ($this->form_validation->run() == FALSE) {
			// Prepare data for the view, including user and department info
			$data['user_details'] = $user_details[1];                   // Store user details in the data array
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();  // Get detailed user info
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();  // Get department info
	
			// Get department information based on the user's department ID
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);   // Fetch department details
			$data['get_department'] = $get_department;  // Store the department details in the data array
	
			// Load the form view with prefilled user and department data
			$this->load->view('users/header', $data);  // Load header
			$this->load->view('users/tracc_concern_form_creation', $data);  // Load the form view
			$this->load->view('users/footer');  // Load footer
	
		} else {
			// Form validation passed, process the form submission
			// All form fields except for requestor, date requested, and department are user-inputted
	
			// Process the form and insert into the database using model function
			$process = $this->Main_model->tracc_concern_add_ticket();  // Adjust model call to add TRACC concern ticket
	
			// Check if the process was successful
			if ($process[0] == 1) {
				// Set a success message in session and redirect to the appropriate page
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/list/tickets/tracc_concern');  // Redirect to ticket list after successful submission
			} else {
				// Set an error message in session and redirect back to the form
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/create/tickets/tracc_concern');  // Redirect back to form in case of error
			}
		}
	}*/

	// CREATION TICKET FORM for tracc concern
	public function user_creation_tickets_tracc_concern() {
		$id = $this->session->userdata('login_data')['user_id'];

		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('upload'); 
	
		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required');

		$user_details = $this->Main_model->user_details();              
		$getdepartment = $this->Main_model->GetDepartmentID();          
		$users_det = $this->Main_model->users_details_put($id);         

		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];                   
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();  
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();  
			
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);   
			$data['get_department'] = $get_department;  

			$this->load->view('users/header', $data);  
			$this->load->view('users/tracc_concern_form_creation', $data);  
			$this->load->view('users/footer');  
		} else {
			// Check if file is uploaded
			$file_path = null; // Initialize file path
			if (!empty($_FILES['uploaded_photo']['name'])) {
				// File upload configuration
				$config['upload_path'] = FCPATH . 'uploads/tracc_concern/';
				$config['allowed_types'] = 'pdf|jpg|png|doc|docx|jpeg'; 
				$config['max_size'] = 5048; 
				$config['file_name'] = time() . '_' . $_FILES['uploaded_photo']['name']; 

				$this->upload->initialize($config);
	
				if (!$this->upload->do_upload('uploaded_photo')) {
					$this->session->set_flashdata('error', $this->upload->display_errors());
					redirect(base_url().'sys/users/create/tickets/tracc_concern');  
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name']; 
				}
			}

			$process = $this->Main_model->tracc_concern_add_ticket($file_path);  
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/list/tickets/tracc_concern');  
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/create/tickets/tracc_concern');  
			}
		}
	}
	
	// CREATION TICKET FORM for msrf
	public function users_creation_tickets_msrf() {
		$id = $this->session->userdata('login_data')['user_id'];
	
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('upload');
	
		$this->form_validation->set_rules('msrf_number', 'Ticket ID', 'trim|required');

		$user_details = $this->Main_model->user_details();        
		$getdepartment = $this->Main_model->GetDepartmentID();     
		$users_det = $this->Main_model->users_details_put($id);      
	
		if ($this->form_validation->run() == FALSE) {
			$msrf = $this->GenerateMSRFNo();
			
			$data['msrf'] = $msrf;                             
			$data['user_details'] = $user_details[1];                
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array(); 
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();  
	
			$users_department = $users_det[1]['dept_id'];            
			$get_department = $this->Main_model->UsersDepartment($users_department); 
			$data['get_department'] = $get_department;              

			$this->load->view('users/header', $data);
			$this->load->view('users/service_request_form_msrf_creation', $data);
			$this->load->view('users/footer');
	
		} else {
			$file_path = null; // Initialize file path
			if (!empty($_FILES['uploaded_file']['name'])) {
				// File upload configuration
				$config['upload_path'] = FCPATH . 'uploads/msrf/';
				$config['allowed_types'] = 'pdf|jpg|png|doc|docx|jpeg'; 
				$config['max_size'] = 5048; 
				$config['file_name'] = time() . '_' . $_FILES['uploaded_file']['name']; 
	
				// Load the upload library with configuration
				$this->upload->initialize($config);
	
				if (!$this->upload->do_upload('uploaded_file')) {
					$this->session->set_flashdata('error', $this->upload->display_errors());
					redirect(base_url().'sys/users/create/tickets/msrf');  
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name']; 
					echo 'Uploaded file path: ' . $file_path; 
				}
			}
			$process = $this->Main_model->msrf_add_ticket($file_path);
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/list/tickets/msrf');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/list/tickets/msrf');
			}
		}
	}
	
	// DATATABLE na nakikita ni USER (MSRF)
	public function service_form_msrf_list() {
		$id = $this->session->userdata('login_data')['user_id']; 
		$dept_id = $this->session->userdata('login_data')['dept_id']; 
	
		$this->load->helper('form'); 
		$this->load->library('session'); 
	
		// Set validation rule for the form input 'msrf_number'
		$this->form_validation->set_rules('msrf_number', 'Ticket ID', 'trim|required'); 
	
		$user_details = $this->Main_model->user_details(); 
		$department_data = $this->Main_model->getDepartment(); 
		$users_det = $this->Main_model->users_details_put($id); 
		$getdepartment = $this->Main_model->GetDepartmentID(); 

		if ($this->form_validation->run() == FALSE) { 
			// If validation fails, it will proceed to generate the MSRF number
			$msrfNumber = $this->GenerateMSRFNo(); // Calls a function to generate a new MSRF number
	
			$data['user_details'] = $user_details[1]; 
			$data['department_data'] = $department_data; 
			$data['users_det'] = $users_det[1]; 
			$data['dept_id'] = $dept_id; 

			if ($department_data[0] == "ok") { 
				$data['departments'] = $department_data[1]; 
			} else {
				$data['departments'] = array(); 
				echo "No departments found."; 
			}
	
			$data['getdept'] = $getdepartment[1]; 
			
			// Add a form type to differentiate between MSRF and TRACC
			$data['form_type'] = 'msrf';

			$data['msrfNumber'] = $msrfNumber; 
			$this->load->view('users/header', $data); 
			$this->load->view('users/service_request_form_msrf_list', $data); 
			$this->load->view('users/footer', $data); 
	
		} else { 

			$process = $this->Main_model->msrf_add_ticket(); 

			if ($process[0] == 1) { 
				$this->session->set_flashdata('success', $process[1]); 
				redirect(base_url().'sys/users/dashboard');
			} else {
				$this->session->set_flashdata('error', $process[1]); 
				redirect(base_url().'sys/users/dashboard'); 
			}
		}
	}

	// DATATABLE na nakikita ni USER TRACC CONCERN
	public function service_form_tracc_concern_list() {
		$id = $this->session->userdata('login_data')['user_id']; 
		$dept_id = $this->session->userdata('login_data')['dept_id']; 

		$this->load->helper('form'); 
		$this->load->library('session'); 

		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required'); 
	
		$user_details = $this->Main_model->user_details(); 
		$department_data = $this->Main_model->getDepartment(); 
		$users_det = $this->Main_model->users_details_put($id); 
		$getdepartment = $this->Main_model->GetDepartmentID(); 
	
		if ($this->form_validation->run() == FALSE) { 
			
			$data['user_details'] = $user_details[1]; 
			$data['department_data'] = $department_data; 
			$data['users_det'] = $users_det[1]; 
			$data['dept_id'] = $dept_id; 
			$control_number = $this->session->userdata('control_number');
	
			if ($department_data[0] == "ok") { 
				$data['departments'] = $department_data[1]; 
			} else {
				$data['departments'] = array(); 
				echo "No departments found."; 
			}
	
			$data['getdept'] = $getdepartment[1]; 
			
			//$data['form_type'] = 'tracc_concern';

			$data['control_number'] = $control_number; 
			$this->load->view('users/header', $data); 
			$this->load->view('users/tracc_concern_form_list', $data);
			$this->load->view('users/footer', $data);
	
		} else {
	
			$process = $this->Main_model->tracc_concern_add_ticket();

			if ($process[0] == 1) { 
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/dashboard'); 
			} else {
				$this->session->set_flashdata('error', $process[1]); 
				redirect(base_url().'sys/users/dashboard');
			}
		}

	}

	/*public function service_form_tracc_concern_list() {
		$id = $this->session->userdata('login_data')['user_id']; // Logged-in user ID
		$dept_id = $this->session->userdata('login_data')['dept_id']; // Logged-in user dept ID
		
		// Load form and session libraries/helpers for validation
		$this->load->helper('form');
		$this->load->library('session');
	
		// Set validation rule for 'control_number'
		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required');
	
		// Fetch user and department details
		$user_details = $this->Main_model->user_details();
		$department_data = $this->Main_model->getDepartment();
		$users_det = $this->Main_model->users_details_put($id);
		$getdepartment = $this->Main_model->GetDepartmentID();
	
		// Check if form validation failed
		if ($this->form_validation->run() == FALSE) {
			
			// Populate data array
			$data['user_details'] = $user_details[1]; 
			$data['department_data'] = $department_data;
			$data['users_det'] = $users_det[1];
			$data['dept_id'] = $dept_id;
	
			// Fetch the control numbers created by this specific user
			$control_number = $this->session->userdata('control_number');
			$user_tickets = $this->Main_model->get_tickets_by_user($id); // Add this logic in the model
	
			// Only show tickets if they belong to the logged-in user
			if ($user_tickets) {
				$data['tickets'] = $user_tickets;
			} else {
				$data['tickets'] = array(); // No tickets found
				echo "No tickets found for this user.";
			}
	
			// Load views
			$this->load->view('users/header', $data);
			$this->load->view('users/tracc_concern_form_list', $data);
			$this->load->view('users/footer', $data);
	
		} else {
			$process = $this->Main_model->tracc_concern_add_ticket();
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			}
		}
	}*/

	//Generate MSRF Number
	public function GenerateMSRFNo() {
		$lastMSRF = $this->Main_model->getLastMSRFNumber();

        // Increment the last MSRF number
        $lastNumber = (int) substr($lastMSRF, -3);
        $newNumber = $lastNumber + 1;

        // Format the new MSRF number
        $newMSRFNumber = 'MSRF-' . sprintf('%03d', $newNumber);

        return $newMSRFNumber;
	}
	
	//Generate TRF Number
	public function GenerateTRFNo() {
		$lastTRF = $this->Main_model->getLastTRFNumber();

        // Increment the last MSRF number
        $lastNumber = (int) substr($lastTRF, -4);
        $newNumber = $lastNumber + 1;

        // Format the new MSRF number
        $newTRFNumber = 'TRF-' . sprintf('%04d', $newNumber);

        return $newTRFNumber;
	}

	

	/*public function tracc_concern_form_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getTraccCon = $this->Main_model->getTraccConcernByID($id);

			if ($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['tracccon'] = $getTraccCon[1];
				
				$this->load->view('users/header', $data);
				$this->load->view('users/tracc_concern_form_details', $data);
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
	}*/

	//MSRF details USERS
	public function service_form_msrf_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getMsrf = $this->Main_model->getTicketsMSRF($id);

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['msrf'] = $getMsrf[1];

				$this->load->view('users/header', $data);
				$this->load->view('users/service_request_form_msrf_details', $data);
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

	//Tracc concern details USERS
	public function tracc_concern_form_details($id) {
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getTraccCon = $this->Main_model->getTraccConcernByID($id);
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['tracc_con'] = $getTraccCon[1];

				if (isset($getTraccCon[1])) {
					$control_number = $getTraccCon[1]['control_number'];
					$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);  
					$data['tracc_con'] = $getTraccCon[1];
				} else {
					$data['checkboxes'] = [];
					$data['tracc_con'] = [];
					$this->session->set_flashdata('error', 'TRACC concern data not found.');
				}
				// Load the views and pass the data
				$this->load->view('users/header', $data);
				$this->load->view('users/tracc_concern_form_details', $data);
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

	//Tracc Request details USERS
	public function tracc_request_form_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getTRF = $this->Main_model->getTicketsTRF($id);
			$getCheckboxDataNewAdd = $this->Main_model->getCheckboxDataNewAdd($id);
			$getCheckeboxDataUpdate = $this->Main_model->getCheckboxDataUpdate($id);
			$getCheckboxDataAccount = $this->Main_model->getCheckboxDataAccount($id);

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['trf'] = $getTRF[1];
				$data['checkbox_data_newadd'] = $getCheckboxDataNewAdd;
				$data['checkbox_data_update'] = $getCheckeboxDataUpdate;
				$data['checkbox_data_account'] = $getCheckboxDataAccount;

				$selected_companies = isset($data['trf']['company']) ? explode(',', $data['trf']['company']) : [];
				$data['selected_companies'] = $selected_companies; 

				$this->load->view('users/header', $data);
				$this->load->view('users/tracc_request_form_details', $data);
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

	/*public function update_status_msrf_assign() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$ticket_id = $this->input->post('msrf_number', true);
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$process = $this->Main_model->UpdateMSRFAssign($ticket_id, $date_needed, $asset_code, $request_category, $specify, $details_concern);
				if ($process[0] == 1) {
					$this->session->set_flashdata('success', $process[1]);
					redirect(base_url()."sys/users/create/tickets/msrf");
				} else {
					$this->session->set_flashdata('error', $process[0]);
					redirect(base_url()."sys/users/create/tickets/msrf");
				}
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
            redirect(base_url()."admin/login");
		}
	}*/

	public function update_status_tracc_request(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$trf_number = $this->input->post('trf_number', true);
		$date_need = $this->input->post('date_need', true);
		$complete_details = $this->input->post('complete_details', true);
		$selected_companies = $this->input->post('comp_checkbox_value', true);

		$new_add_data = [
			'item' 							=> $this->input->post('checkbox_item', true) ? 1 : 0,
			'customer' 						=> $this->input->post('checkbox_customer', true) ? 1 : 0,
			'supplier' 						=> $this->input->post('checkbox_supplier', true) ? 1 : 0,
			'warehouse' 					=> $this->input->post('checkbox_whs', true) ? 1 : 0,
			'bin_number' 					=> $this->input->post('checkbox_bin', true) ? 1 : 0,
			'customer_shipping_setup' 		=> $this->input->post('checkbox_cus_ship_setup', true) ? 1 : 0,
			'employee_request_form' 		=> $this->input->post('checkbox_employee_req_form', true) ? 1 : 0,
			'others' 						=> $this->input->post('checkbox_others_newadd', true) ? 1 : 0,
			'others_description_add' 		=> $this->input->post('others_text_newadd', true),
		];

		$data_update = [
			'system_date_lock'				=> $this->input->post('checkbox_system_date_lock', true) ? 1 : 0,
			'user_file_access'				=> $this->input->post('checkbox_user_file_access', true) ? 1 : 0,
			'item_details'					=> $this->input->post('checkbox_item_dets', true) ? 1 : 0,
			'customer_details'				=> $this->input->post('checkbox_customer_dets', true) ? 1 : 0,
			'supplier_details'				=> $this->input->post('checkbox_supplier_dets', true) ? 1 : 0,
			'employee_details'				=> $this->input->post('checkbox_employee_dets', true) ? 1 : 0,
			'others'						=> $this->input->post('checkbox_others_update', true) ? 1 : 0,
			'others_description_update'		=> $this->input->post('others_text_update', true),
		];

		$data_account = [
			'tracc_orientation'				=> $this->input->post('checkbox_tracc_orien', true) ? 1 : 0,
			'lmi'							=> $this->input->post('checkbox_create_lmi', true) ? 1 : 0,
			'rgdi'							=> $this->input->post('checkbox_create_rgdi', true) ? 1 : 0,
			'lpi'							=> $this->input->post('checkbox_create_lpi', true) ? 1 : 0,
			'sv'							=> $this->input->post('checkbox_create_sv', true) ? 1 : 0,
			'gps_account'					=> $this->input->post('checkbox_gps_account', true) ? 1 : 0,
			'others'						=> $this->input->post('checkbox_others', true) ? 1 : 0,
			'others_description_acc'		=> $this->input->post('others_text_acc', true),
			
		];

		$process = $this->Main_model->UpdateTraccReq($trf_number, $date_need, $complete_details, $selected_companies);
		$process1 = $this->Main_model->UpdateTRNewAdd($trf_number, $new_add_data);
		$process2 = $this->Main_model->UpdateTRUpdate($trf_number, $data_update);
		$process3 = $this->Main_model->UpdateTRAccount($trf_number, $data_account);
		// print_r($process1);
		// die();

		if ($process[0] == 1 || $process1[0] == 1 || $process2[0] == 1 || $process3[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url()."sys/users/list/tickets/tracc_request");
		} else {
			$this->session->set_flashdata('error', $process[0]);
			redirect(base_url()."sys/users/list/tickets/tracc_request");
		}
	}

	public function update_status_msrf_assign() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$ticket_id = $this->input->post('msrf_number', true);
		$date_needed = $this->input->post('date_need', true);
		$asset_code = $this->input->post('asset_code', true);
		$request_category = $this->input->post('category', true);
		$specify = $this->input->post('msrf_specify', true);
		$details_concern = $this->input->post('concern', true);

		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$process = $this->Main_model->UpdateMSRFAssign($ticket_id, $date_needed, $asset_code, $request_category, $specify, $details_concern);

				if ($process[0] == 1) {
					$this->session->set_flashdata('success', $process[1]);
					redirect(base_url()."sys/users/list/tickets/msrf");
				} else {
					$this->session->set_flashdata('error', $process[0]);
					redirect(base_url()."sys/users/list/tickets/msrf");
				}
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
            redirect(base_url()."admin/login");
		}
	}

	// Acknowledging the form as resolved
	/* public function acknowledge_as_resolved() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$control_number = $this->input->post('control_number', true);
		if ($this->session->userdata('login_data')){
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$process = $this->Main_model->AcknolwedgeAsResolved($control_number);
				if ($process[0] == 1){
					$this->session->set_flashdata('success', $process[1]);
					redirect(base_url()."sys/users/list/tickets/tracc_concern");
				} else {
					$this->session->set_flashdata('error', $process[0]);
					redirect(base_url()."sys/users/list/tickets/tracc_concern");
				}
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
			redirect(base_url()."admin/login");
		}
	} */

	public function acknowledge_as_resolved() {
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		$control_number = $this->input->post('control_number', true);
		$action = $this->input->post('action', true); // Get the action (edit or acknowledge)
	
		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
	
				if ($action == 'edit') {
					// Logic to update fields without closing the ticket
					$edit_data = [
						'module_affected' => $this->input->post('module_affected', true),
						'company' => $this->input->post('company', true),
						'tcr_details' => $this->input->post('concern', true)
					];
	
					$update_process = $this->Main_model->update_tracc_concern($control_number, $edit_data);
	
					if ($update_process[0] == 1) {
						$this->session->set_flashdata('success', 'Data updated successfully.');
					} else {
						$this->session->set_flashdata('error', 'Failed to update data.');
					}
	
					redirect(base_url() . "sys/users/list/tickets/tracc_concern");
	
				} elseif ($action == 'acknowledge') {
					$acknowledge_data = [
						'ack_as_resolved' => $this->input->post('ack_as_res_by', true),
						'ack_as_resolved_date' => $this->input->post('ack_as_res_date', true)
					];
	
					$acknowledge_process = $this->Main_model->AcknolwedgeAsResolved($control_number, $acknowledge_data);
	
					if ($acknowledge_process[0] == 1) {
						$this->session->set_flashdata('success', 'Ticket successfully acknowledged as resolved.');
					} else {
						$this->session->set_flashdata('error', 'Failed to acknowledge ticket as resolved.');
					}
	
					redirect(base_url() . "sys/users/list/tickets/tracc_concern");
				} else {
					$this->session->set_flashdata('error', 'Invalid action.');
					redirect(base_url() . "sys/users/list/tickets/tracc_concern");
				}
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("sys/authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
			redirect(base_url() . "admin/login");
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

	// DATATABLE na makikita ni USERS sa get_tracc_request_form
	public function service_form_tracc_request_list(){
		$id = $this->session->userdata('login_data')['user_id'];
		$dept_id = $this->session->userdata('login_data')['dept_id'];

		$this->load->helper('form');
		$this->load->library('session');

		$this->form_validation->set_rules('trf_number', 'Ticket Number', 'trim|required');

		$user_details = $this->Main_model->user_details();
		$department_data = $this->Main_model->getDepartment();
		$users_det = $this->Main_model->users_details_put($id);
		$getdepartment = $this->Main_model->GetDepartmentID();

		if ($this->form_validation->run() == FALSE){
			$trfNumber = $this->GenerateTRFNo();

			$data['user_details'] = $user_details[1];
			$data['department_data'] = $department_data;
			$data['users_det'] = $users_det[1];
			$data['dept_id'] = $dept_id;

			if($department_data[0] == "ok"){
				$data['departments'] = $department_data[1];
			} else {
				$data['departments'] = array();
				echo "No departments found.";
			}

			if(time() > $this->session->userdata('data')['expires_at']) {
				$this->session->unset_userdata('data');
			}

			$data['getdept'] = $getdepartment[1];

			$data['trfNumber'] = $trfNumber; 
			$this->load->view('users/header', $data); 
			$this->load->view('users/tracc_request_form_list', $data); 
			$this->load->view('users/footer', $data); 
	
		} else {
			$process = $this->Main_model->trf_add_ticket();

			if ($process[0] == 1){
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			}

		}
	}

	// USER CREATION of TICKET for TRACC REQUEST
	public function user_creation_tickets_tracc_request() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('upload');
	
		$this->form_validation->set_rules('trf_number', 'Ticket Number', 'trim|required');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
	
		if ($this->form_validation->run() == FALSE) {
			$trf = $this->GenerateTRFNo();
	
			$data['trf'] = $trf;
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
	
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);
			$data['get_department'] = $get_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/tracc_request_form_creation', $data);
			$this->load->view('users/footer');
		} else {
			$file_path = null;
			if (!empty($_FILES['uploaded_files']['name'])) {
				$config['upload_path'] = FCPATH . 'uploads/tracc_request/';
				$config['allowed_types'] = 'pdf|jpg|png|doc|docx|jpeg';
				$config['max_size'] = 5048;
				$config['file_name'] = time() . '_' . $_FILES['uploaded_files']['name'];
	
				$this->upload->initialize($config);
	
				if (!$this->upload->do_upload('uploaded_files')) {
					$this->session->set_flashdata('error', $this->upload->display_errors());
					redirect(base_url() . 'sys/users/create/tickets/tracc_request');
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name'];
				}
			}
	
			$checkbox_data_newadd = [
				'checkbox_item' => $this->input->post('checkbox_item') ? 1 : 0,
				'checkbox_customer' => $this->input->post('checkbox_customer') ? 1 : 0,
				'checkbox_supplier' => $this->input->post('checkbox_supplier') ? 1 : 0,
				'checkbox_whs' => $this->input->post('checkbox_whs') ? 1 : 0,
				'checkbox_bin' => $this->input->post('checkbox_bin') ? 1 : 0,
				'checkbox_cus_ship_setup' => $this->input->post('checkbox_cus_ship_setup') ? 1 : 0,
				'checkbox_employee_req_form' => $this->input->post('checkbox_employee_req_form') ? 1 : 0,
				'checkbox_others_newadd' => $this->input->post('checkbox_others_newadd') ? 1 : 0, 
				'others_text_newadd' => $this->input->post('others_text_newadd')
			];

			$checkbox_data_update = [
				'checkbox_system_date_lock' => $this->input->post('checkbox_system_date_lock') ? 1 : 0,
				'checkbox_user_file_access' => $this->input->post('checkbox_user_file_access') ? 1 : 0,
				'checkbox_item_dets' => $this->input->post('checkbox_item_dets') ? 1 : 0,
				'checkbox_customer_dets' => $this->input->post('checkbox_customer_dets') ? 1 : 0,
				'checkbox_supplier_dets' => $this->input->post('checkbox_supplier_dets') ? 1 : 0,
				'checkbox_employee_dets' => $this->input->post('checkbox_employee_dets') ? 1 : 0,
				'checkbox_others_update' => $this->input->post('checkbox_others_update') ? 1 : 0,
				'others_text_update' => $this->input->post('others_text_update')
			]; 

			$checkbox_data_account = [
				'checkbox_tracc_orien' => $this->input->post('checkbox_tracc_orien') ? 1 : 0,
				'checkbox_create_lmi' => $this->input->post('checkbox_create_lmi') ? 1 : 0,
				'checkbox_create_lpi' => $this->input->post('checkbox_create_lpi') ? 1 : 0,
				'checkbox_create_rgdi' => $this->input->post('checkbox_create_rgdi') ? 1 : 0,
				'checkbox_create_sv' => $this->input->post('checkbox_create_sv') ? 1 : 0,
				'checkbox_gps_account' => $this->input->post('checkbox_gps_account') ? 1 : 0,
				'checkbox_others_account' => $this->input->post('checkbox_others_account') ? 1 : 0,
				'others_text_account' => $this->input->post('others_text_account')
			];
	
			$comp_checkbox_values = isset($_POST['comp_checkbox_value']) ? $_POST['comp_checkbox_value'] : [];
			$imploded_values = implode(',', $comp_checkbox_values);
	
			$process = $this->Main_model->trf_add_ticket($file_path, $imploded_values, $checkbox_data_newadd, $checkbox_data_update, $checkbox_data_account);

			$newadd = [
				'Item Request Form' => $checkbox_data_newadd['checkbox_item'],
				'Customer Request Form' => $checkbox_data_newadd['checkbox_customer'],
				'Supplier Request Form' => $checkbox_data_newadd['checkbox_supplier'],
				'Customer Shipping Setup' => $checkbox_data_newadd['checkbox_cus_ship_setup'],
				'Employee Request Form' => $checkbox_data_newadd['checkbox_employee_req_form'],
			];
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				$this->session->set_userdata('data', ['checkbox_data' => $newadd, 'expires_at' => time() + (5 * 60)]);
				redirect(base_url() . 'sys/users/list/tickets/tracc_request');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url() . 'sys/users/create/tickets/tracc_request');
			}
		}
	}

	// USER CREATION FORM of CUSTOMER REQUEST FORM TMS (pdf ni mam hanna)
	public function user_creation_tickets_customer_request_forms_tms() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->Main_model->get_customer_from_tracc_req_mf_new_add();

		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);
			$data['get_department'] = $get_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/trf_customer_request_form_creation', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

	// USER CREATION FUNCTION of CUSTOMER REQUEST FORM TMS (pdf ni mam hanna)
	public function user_creation_customer_request_form_pdf() {
		$crf_comp_checkbox_values = isset($_POST['crf_comp_checkbox_value']) ? $_POST['crf_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $crf_comp_checkbox_values);

		$checkbox_cus_req_form_del = [
			'checkbox_outright' => isset($_POST['checkbox_outright']) ? 1 : 0,
			'checkbox_consignment' => isset($_POST['checkbox_consignment']) ? 1 : 0,
			'checkbox_cus_a_supplier' => isset($_POST['checkbox_cus_a_supplier']) ? 1 : 0,
			'checkbox_online' => isset($_POST['checkbox_online']) ? 1 : 0,
			'checkbox_walkIn' => isset($_POST['checkbox_walkIn']) ? 1 : 0,
			'checkbox_monday' => isset($_POST['checkbox_monday']) ? 1 : 0,
			'checkbox_tuesday' => isset($_POST['checkbox_tuesday']) ? 1 : 0,
			'checkbox_wednesday' => isset($_POST['checkbox_wednesday']) ? 1 : 0,
			'checkbox_thursday' => isset($_POST['checkbox_thursday']) ? 1 : 0,
			'checkbox_friday' => isset($_POST['checkbox_friday']) ? 1 : 0,
			'checkbox_saturday' => isset($_POST['checkbox_saturday']) ? 1 : 0,
			'checkbox_sunday' => isset($_POST['checkbox_sunday']) ? 1 : 0,
		];
	
		$process = $this->Main_model->add_customer_request_form_pdf($imploded_values, $checkbox_cus_req_form_del);

		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_request_form_tms');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_request_form_tms'); 
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

	// USER CREATION FORM of CUSTOMER SHIPPING SETUP (pdf ni mam hanna)
	public function user_creation_tickets_customer_shipping_setup() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->Main_model->get_customer_shipping_setup_from_tracc_req_mf_new_add();
	
		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);
			$data['get_department'] = $get_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/trf_customer_shipping_setup', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

	// USER CREATION FUCNTION of CUSTOMER SHIPPING SETUP (pdf ni mam hanna)
	public function user_creation_customer_shipping_setup_pdf() {
		$css_comp_checkbox_value = isset($_POST['css_comp_checkbox_value']) ? $_POST['css_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $css_comp_checkbox_value);

		$checkbox_cus_ship_setup = [
			'checkbox_monday' => isset($_POST['checkbox_monday']) ? 1 : 0,
			'checkbox_tuesday' => isset($_POST['checkbox_tuesday']) ? 1 : 0,
			'checkbox_wednesday' => isset($_POST['checkbox_wednesday']) ? 1 : 0,
			'checkbox_thursday' => isset($_POST['checkbox_thursday']) ? 1 : 0,
			'checkbox_friday' => isset($_POST['checkbox_friday']) ? 1 : 0,
			'checkbox_saturday' => isset($_POST['checkbox_saturday']) ? 1 : 0,
			'checkbox_sunday' => isset($_POST['checkbox_sunday']) ? 1 : 0,
		];

		$process = $this->Main_model->add_customer_shipping_setup_pdf($imploded_values, $checkbox_cus_ship_setup);

		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_shipping_setup');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_customer_shipping_setup');  
		}
	}

	// USER CREATION FORM of EMPLOYEE REQUEST FORM (pdf ni mam hanna)
	public function user_creation_tickets_employee_request_form() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->Main_model->get_employee_request_form_from_tracc_req_mf_new_add();
	
		// New: Fetch all departments for the dropdown
		$departments_result = $this->Main_model->getDepartment();
		$departments = ($departments_result[0] == "ok") ? $departments_result[1] : [];
	
		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			// New: Pass departments for the dropdown
			$data['departments'] = $departments;
	
			$users_department = $users_det[1]['dept_id'] ?? null;
			$data['selected_department'] = $users_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/trf_employee_request_form', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

	// USER CREATION FUCNTION of EMPLOYEE REQUEST FORM (pdf ni mam hanna)
	public function user_creation_employee_request_form_pdf() {
		$process = $this->Main_model->add_employee_request_form_pdf();

		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_employee_request_form');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_employee_request_form');  
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

	// USER CREATION FORM of ITEM REQUEST FORM (pdf ni mam hanna)
	public function user_creation_tickets_item_request_form() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->Main_model->get_item_request_form_from_tracc_req_mf_new_add();

		$departments_result = $this->Main_model->getDepartment();
		$departments = ($departments_result[0] == "ok") ? $departments_result[1] : [];

		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			// New: Pass departments for the dropdown
			$data['departments'] = $departments;
	
			$users_department = $users_det[1]['dept_id'] ?? null;
			$data['selected_department'] = $users_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/trf_item_request_form', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

	// USER CREATION FUCNTION of ITEM REQUEST FORM (pdf ni mam hanna)
	public function user_creation_item_request_form_pdf() {
		$trf_number = $this->input->post('trf_number', true);
		$irf_comp_checkbox_value = isset($_POST['irf_comp_checkbox_value']) ? $_POST['irf_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $irf_comp_checkbox_value);

		$checkbox_item_req_form = [
			'checkbox_inventory' => isset($_POST['checkbox_inventory']) ? 1 : 0,
			'checkbox_non_inventory' => isset($_POST['checkbox_non_inventory']) ? 1 : 0,
			'checkbox_services' => isset($_POST['checkbox_services']) ? 1 : 0,
			'checkbox_charges' => isset($_POST['checkbox_charges']) ? 1 : 0,
			'checkbox_watsons' => isset($_POST['checkbox_watsons']) ? 1 : 0,
			'checkbox_other_accounts' => isset($_POST['checkbox_other_accounts']) ? 1 : 0,
			'checkbox_online' => isset($_POST['checkbox_online']) ? 1 : 0,
			'checkbox_all_accounts' => isset($_POST['checkbox_all_accounts']) ? 1 : 0,
			'checkbox_trade' => isset($_POST['checkbox_trade']) ? 1 : 0,
			'checkbox_non_trade' => isset($_POST['checkbox_non_trade']) ? 1 : 0,
			'checkbox_batch_required_yes' => isset($_POST['checkbox_batch_required_yes']) ? 1 : 0,
			'checkbox_batch_required_no' => isset($_POST['checkbox_batch_required_no']) ? 1 : 0,
 
		];

		$process = $this->Main_model->add_item_request_form_pdf($imploded_values, $checkbox_item_req_form);

		$rows_data = $this->input->post('rows_gl', true);

		if ($process[0] == 1 && !empty($rows_data)) {
			// Prepare structured data for rows insertion
			$insert_data_gl_setup = [];
			foreach ($rows_data as $row) {
				if (!empty($row['uom']) && !empty($row['barcode'])) { // Basic validation
					$insert_data_gl_setup[] = [
						'ticket_id' => $trf_number,
						'uom' => $row['uom'],
						'barcode' => $row['barcode'],
						'length' => $row['length'],
						'height' => $row['height'],
						'width' => $row['width'],
						'weight' => $row['weight'],
					];
				}
			}

			if (!empty($insert_data_gl_setup)) {
				$this->Main_model->insert_batch_rows_gl_setup($insert_data_gl_setup);
			}
		}

		$rows_data = $this->input->post('rows_whs',true);

		if ($process[0] == 1 && !empty($rows_data)) {
			$insert_data_whs_setup = [];
			foreach ($rows_data as $row){
				if(!empty($row['warehouse']) && !empty($row['warehouse_no'])) {
					$insert_data_wh_setup[] = [
						'ticket_id' => $trf_number,
						'warehouse' => $row['warehouse'],
						'warehouse_no' => $row['warehouse_no'],
						'storage_location' => $row['storage_location'],
						'storage_type' => $row['storage_type'],
						'fixed_bin' => $row['fixed_bin'],
						'min_qty' => $row['min_qty'],
						'max_qty' => $row['max_qty'],
						'replen_qty' => $row['replen_qty'],
						'control_qty' => $row['control_qty'],
						'round_qty' => $row['round_qty'],
						'uom' => $row['uom'],
					];
				}
			}

			if (!empty($insert_data_wh_setup)) {
				$this->Main_model->insert_batch_rows_whs_setup($insert_data_wh_setup);
			}
		}


		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_item_request_form');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_item_request_form');  
		}
	}
	
	// USER CREATION FORM of SUPPLIER REQUEST FORM (pdf ni mam hanna)
	public function user_creation_tickets_supplier_request_form_tms() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
		$ticket_numbers = $this->Main_model->get_supplier_from_tracc_req_mf_new_add();

		$departments_result = $this->Main_model->getDepartment();
		$departments = ($departments_result[0] == "ok") ? $departments_result[1] : [];

		if ($this->form_validation->run() == FALSE) {
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			$data['ticket_numbers'] = $ticket_numbers;
	
			$data['departments'] = $departments;
	
			$users_department = $users_det[1]['dept_id'] ?? null;
			$data['selected_department'] = $users_department;
	
			$this->load->view('users/header', $data);
			$this->load->view('users/trf_supplier_request_form', $data);
			$this->load->view('users/footer');
		} else {
			return;
		}
	}

	// USER CREATION FUCNTION of SUPPLIER REQUEST FORM (pdf ni mam hanna)
	public function user_creation_supplier_request_form_pdf() {
		$trf_comp_checkbox_value = isset($_POST['trf_comp_checkbox_value']) ? $_POST['trf_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $trf_comp_checkbox_value);

		$checkbox_non_vat = isset($_POST['checkbox_non_vat']) ? 1 : 0;

		$checkbox_supplier_req_form = [
			'local_supplier_grp' => isset($_POST['local_supplier_grp']) ? 1 : 0,
			'foreign_supplier_grp' => isset($_POST['foreign_supplier_grp']) ? 1 : 0,
			'supplier_trade' => isset($_POST['supplier_trade']) ? 1 : 0,
			'supplier_non_trade' => isset($_POST['supplier_non_trade']) ? 1 : 0,
			'trade_type_goods' => isset($_POST['trade_type_goods']) ? 1 : 0,
			'trade_type_services' => isset($_POST['trade_type_services']) ? 1 : 0,
			'trade_type_GoodsServices' => isset($_POST['trade_type_GoodsServices']) ? 1 : 0,
			'major_grp_local_trade_ven' => isset($_POST['major_grp_local_trade_ven']) ? 1 : 0,
			'major_grp_local_nontrade_ven' => isset($_POST['major_grp_local_nontrade_ven']) ? 1 : 0,
			'major_grp_foreign_trade_ven' => isset($_POST['major_grp_foreign_trade_ven']) ? 1 : 0,
			'major_grp_foreign_nontrade_ven' => isset($_POST['major_grp_foreign_nontrade_ven']) ? 1 : 0,
			'major_grp_local_broker_forwarder' => isset($_POST['major_grp_local_broker_forwarder']) ? 1 : 0,
			'major_grp_rental' => isset($_POST['major_grp_rental']) ? 1 : 0,
			'major_grp_bank' => isset($_POST['major_grp_bank']) ? 1 : 0,
			'major_grp_one_time_supplier' => isset($_POST['major_grp_one_time_supplier']) ? 1 : 0,
			'major_grp_government_offices' => isset($_POST['major_grp_government_offices']) ? 1 : 0,
			'major_grp_insurance' => isset($_POST['major_grp_insurance']) ? 1 : 0,
			'major_grp_employees' => isset($_POST['major_grp_employees']) ? 1 : 0,
			'major_grp_subs_affiliates' => isset($_POST['major_grp_subs_affiliates']) ? 1 : 0,
			'major_grp_utilities' => isset($_POST['major_grp_utilities']) ? 1 : 0,
		];
	
		$process = $this->Main_model->add_supplier_request_form_pdf($imploded_values, $checkbox_non_vat,$checkbox_supplier_req_form);

		if ($process[0] == 1) {
			$this->session->set_flashdata('success', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_supplier_request_form_tms');  
		} else {
			$this->session->set_flashdata('error', $process[1]);
			redirect(base_url().'sys/users/create/tickets/trf_supplier_request_form_tms');  
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
				$this->load->view('admin/pdf_supplier_request_form', $data);
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
				
				$formHtml = $this->load->view('admin/trf_supplier_request_form_admin', $formData, TRUE);

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
				$this->load->view('admin/pdf_employee_request_form', $data);
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
				
				$formHtml = $this->load->view('admin/trf_employee_request_form_admin', $formData, TRUE);
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
				$this->load->view('admin/pdf_customer_request_form', $data);
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

				$formHtml = $this->load->view('admin/trf_customer_request_form_admin', $formData, TRUE);			
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
				$this->load->view('admin/pdf_customer_shipping_setup_form', $data);
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

				$formHtml = $this->load->view('admin/trf_customer_shipping_setup_form_admin', $formData, TRUE);			
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
				$this->load->view('admin/pdf_item_request_form', $data);
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

				$formHtml = $this->load->view('admin/trf_item_request_form_admin', $formData, TRUE);			
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

	public function customer_request_form_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$customerReqForm = $this->Main_model->get_customer_req_form_details($id);
			$ticket_numbers = $this->Main_model->get_customer_from_tracc_req_mf_new_add();
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
	
	// Edit function (with Bug)
	public function user_edit_customer_request_form_pdf($id) {
		$trf_comp_checkbox_value = isset($_POST['trf_comp_checkbox_value']) ? $_POST['trf_comp_checkbox_value'] : [];
		$imploded_values = implode(',', $trf_comp_checkbox_value);

		$checkbox_cus_req_form_del = [
			'checkbox_outright' => isset($_POST['checkbox_outright']) ? 1 : 0,
			'checkbox_consignment' => isset($_POST['checkbox_consignment']) ? 1 : 0,
			'checkbox_cus_a_supplier' => isset($_POST['checkbox_cus_a_supplier']) ? 1 : 0,
			'checkbox_online' => isset($_POST['checkbox_online']) ? 1 : 0,
			'checkbox_walkIn' => isset($_POST['checkbox_walkIn']) ? 1 : 0,
			'checkbox_monday' => isset($_POST['checkbox_monday']) ? 1 : 0,
			'checkbox_tuesday' => isset($_POST['checkbox_tuesday']) ? 1 : 0,
			'checkbox_wednesday' => isset($_POST['checkbox_wednesday']) ? 1 : 0,
			'checkbox_thursday' => isset($_POST['checkbox_thursday']) ? 1 : 0,
			'checkbox_friday' => isset($_POST['checkbox_friday']) ? 1 : 0,
			'checkbox_saturday' => isset($_POST['checkbox_saturday']) ? 1 : 0,
			'checkbox_sunday' => isset($_POST['checkbox_sunday']) ? 1 : 0,
		];

		$process = $this->Main_model->edit_customer_request_form_pdf($imploded_values, $checkbox_cus_req_form_del, $id);

		$this->session->set_flashdata('editTR', $process[1]);
		redirect(base_url() . 'sys/users/dashboard');
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
}