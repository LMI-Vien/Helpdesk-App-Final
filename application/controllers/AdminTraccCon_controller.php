<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTraccCon_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('AdminTraccCon_model');

		if($this->session->userdata('login_data')['role'] == 'L1') {
			show_404();
		}
    }

	public function GenerateTRCNo($dept_id) {
		$lastTRC = $this->Main_model->getLastTRCNumber();
		$dept_code = $this->Main_model->get_department_code($dept_id);

		$year = date("Y");

		if ($lastTRC === null) {
			$newNumber = 1;
		} else {
			$parts = explode('-', $lastTRC);
			$lastCode = (int) end($parts);
			$newNumber = $lastCode + 1;
		}

		$counterStr = sprintf('%04d', $newNumber);

		$newTRCNumber = $dept_code . '-' . $year . '-' . $counterStr;

		return $newTRCNumber;
	}

	public function admin_creation_tickets_tracc_concern() {
		$id = $this->session->userdata('login_data')['user_id'];

		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('upload'); 
	
		$this->form_validation->set_rules('control_number', 'Control Number', 'trim|required');

		$user_details = $this->Main_model->user_details();              
		$getdepartment = $this->Main_model->GetDepartmentID();          
		$users_det = $this->Main_model->users_details_put($id);
		$trc = $this->GenerateTRCNo($user_details[1]['dept_id']); 
		$cutoff = $this->Main_model->get_cutoff();     

		if ($this->form_validation->run() == FALSE) {
			$data['trc'] = $trc;
			$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
			$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
			$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

			$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'admin_creation_ticket';

			$data['active_menu'] = $active_menu;

			$data['user_details'] = $user_details[1];                   
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();  
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();  
			
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);   
			$data['get_department'] = $get_department;
			$startdate = $cutoff->date;
			$enddate = $cutoff->end_date;
			$cutofftime = $cutoff->cutoff_time;
			$opentime = $cutoff->open_time;
			$currenttime = (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('H:i:s');
			$timecomparison1 = $currenttime < $cutofftime;
			$timecomparison2 = $opentime < $currenttime;
			$bypass = (int)($cutoff->bypass ?? 0);
	
			if ($bypass === 1 || ($startdate <= date("Y-m-d") && date("Y-m-d") <= $enddate) || empty($startdate)) {
				if ($bypass === 1 || ($opentime <= $currenttime && $currenttime <= $cutofftime)) {
					$this->load->view('admin/header', $data);
					$this->load->view('admin/sidebar', $data);
					$this->load->view('admin/admin_TRC/trc_creation', $data);
					$this->load->view('admin/footer');
				} else {
					$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
					redirect('admin/list/creation_tickets/tracc_concern');
				}
			} else {
				$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
				redirect('admin/list/creation_tickets/tracc_concern');
			}
		} else {
			// Check if file is uploaded
			$file_path = null; // Initialize file path
			if (!empty($_FILES['uploaded_photo']['name'])) {
				// File upload configuration
				$config['upload_path'] = FCPATH . 'uploads/tracc_concern/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png|doc|docx|xls|xlsx|csv|txt'; 
				$config['max_size'] = 5048; 
				$config['file_name'] = time() . '_' . $_FILES['uploaded_photo']['name']; 

				$this->upload->initialize($config);
	
				if (!$this->upload->do_upload('uploaded_photo')) {
					$this->session->set_flashdata('error', $this->upload->display_errors());
					redirect(base_url().'admin/create/tickets/tracc_concern');  
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name']; 
				}
			}

			$process = $this->AdminTraccCon_model->tracc_concern_add_ticket($file_path);  
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'admin/list/creation_tickets/tracc_concern');  
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'admin/list/creation_tickets/tracc_concern');  
			}
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

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
	
				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';

				$data['active_menu'] = $active_menu;
				
				$data['checkboxes'] = [
					'for_mis_concern'       => 0,
					'for_lst_concern'       => 0,
					'system_error'          => 0,
					'user_error'            => 0
					];

				if ($this->input->post()) {
					$control_number = $this->input->post('control_number');
					$data['checkboxes'] = $this->Main_model->get_checkbox_values($control_number);
					$received_by = $this->input->post('received_by');
					$noted_by = $this->input->post('noted_by');
					$priority = $this->input->post('priority');
					$approval_stat = $this->input->post('app_stat');
					$reject_ticket = $this->input->post('reason_rejected');
					$returnedTicket = $this->input->post('returnedReason');
					$solution = $this->input->post('solution');
					$resolved_by = $this->input->post('resolved_by');
					$resolved_date = $this->input->post('res_date');
					$others = $this->input->post('others');
					$received_by_lst = $this->input->post('received_by_lst');
					$date_lst = $this->input->post('date_lst');
					$ictAssigned = $this->input->post('ictAssigned');

					$checkbox_data = [
						'control_number'    => $control_number,
						'for_mis_concern'   => $this->input->post('checkbox_mis') ? 1 : 0,
						'for_lst_concern'   => $this->input->post('checkbox_lst') ? 1 : 0,
						'system_error'      => $this->input->post('checkbox_system_error') ? 1 : 0,
						'user_error'        => $this->input->post('checkbox_user_error') ? 1 : 0,
					];

					$process = $this->AdminTraccCon_model->status_approval_tracc_concern($control_number, $received_by, $noted_by, $priority, $approval_stat, $reject_ticket, $solution, $resolved_by, $resolved_date, $others, $received_by_lst, $date_lst, $ictAssigned);
					$process_checkbox = $this->AdminTraccCon_model->insert_checkbox_data($checkbox_data);
	
					if ($process[0] == 1 && $process_checkbox[0] == 1) {
						$this->session->set_flashdata('success', 'Tickets Approved: ' . $control_number);
					} else {
						$this->session->set_flashdata('error', 'Updated the ticket: ' . $control_number);
					}
	
					redirect(base_url()."admin/list/ticket/tracc_concern");
				}

				// Load views
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRC/tickets_tracc_concern', $data);
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

	public function admin_closed_tickets($active_menu = 'closed_tickets_list') {
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$ict_dept = $this->Main_model->get_ict();

			if($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['ict_dept'] = $ict_dept;

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

				$allowed_menus = ['dashboard', 'closed_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'closed_tickets_list';

				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRC/closed_tracc_concern', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->flashdata('error', 'Session expired. Please login again.');
			redirect("authentication");
		}
	}

	// DATATABLE na nakikita ni Admin TRACC CONCERN
	public function tracc_concern_list() {
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

			$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu', 'admin_creation_ticket'];
			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'admin_creation_ticket';
			$data['active_menu'] = 'admin_creation_ticket';

			$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
			$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
			$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
			
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
			$this->load->view('admin/header', $data);
			$this->load->view('admin/sidebar', $data);
			$this->load->view('admin/admin_TRC/admin_list_tracc_concern_creation', $data);
			$this->load->view('admin/footer');
	
		} else {
	
			$process = $this->UsersTraccCon_model->tracc_concern_add_ticket();

			if ($process[0] == 1) { 
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'users/dashboard'); 
			} else {
				$this->session->set_flashdata('error', $process[1]); 
				redirect(base_url().'users/dashboard');
			}
		}
	}

	//Tracc concern details USERS
	public function admin_tracc_concern_details($id) {
		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getTraccCon = $this->Main_model->getTraccConcernByID($id);
	
			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['tracc_con'] = $getTraccCon[1];

				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu', 'admin_creation_ticket'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'admin_creation_ticket';
				$data['active_menu'] = 'admin_creation_ticket';

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
			

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
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRC/admin_tracc_concern_details', $data);
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

	// Acknowledging the form as resolved
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
	
					$update_process = $this->AdminTraccCon_model->update_tracc_concern($control_number, $edit_data);
	
					if ($update_process[0] == 1) {
						$this->session->set_flashdata('success', 'Data updated successfully.');
					} else {
						$this->session->set_flashdata('error', 'Failed to update data.');
					}
	
					redirect(base_url() . "admin/list/creation_tickets/tracc_concern");
	
				} elseif ($action == 'acknowledge') {
					$acknowledge_data = [
						'ack_as_resolved' => $this->input->post('ack_as_res_by', true),
						'ack_as_resolved_date' => $this->input->post('ack_as_res_date', true)
					];
	
					$acknowledge_process = $this->AdminTraccCon_model->AcknolwedgeAsResolved($control_number, $acknowledge_data);
	
					if ($acknowledge_process[0] == 1) {
						$this->session->set_flashdata('success', 'Ticket successfully acknowledged as resolved.');
					} else {
						$this->session->set_flashdata('error', 'Failed to acknowledge ticket as resolved.');
					}
	
					redirect(base_url() . "admin/list/creation_tickets/tracc_concern");
				} else {
					$this->session->set_flashdata('error', 'Invalid action.');
					redirect(base_url() . "admin/list/creation_tickets/tracc_concern");
				}
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
			redirect(base_url() . "admin/login");
		}
	}

	public function admin_rejected_tickets($active_menu = 'rejected_tickets_list') {
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$ict_dept = $this->Main_model->get_ict();

			if($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['ict_dept'] = $ict_dept;

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

				$allowed_menus = ['dashboard', 'rejected_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'rejected_tickets_list';

				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRC/rejected_tracc_concern', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->flashdata('error', 'Session expired. Please login again.');
			redirect("authentication");
		}
	}
}
?>