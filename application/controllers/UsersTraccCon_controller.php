<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersTraccCon_controller extends CI_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('UsersTraccCon_model');

		if($this->session->userdata('login_data')['role'] != 'L1') {
			show_404();
		}
	}

	public function GenerateTRCNo($dept_id) {
		$lastTRC = $this->Main_model->getLastTRCNumber();
		$dept_code = $this->Main_model->get_department_code($dept_id);

        $lastcode = (int) substr($lastTRC, -4);
		$newNumber = $lastcode + 1;

		$newTRCNumber = $dept_code . '-' . date("Y") . '-' . sprintf('%04d', $newNumber);

		return $newTRCNumber;
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
			$this->load->view('users/users_TRC/tracc_concern_form_list', $data);
			$this->load->view('users/footer', $data);
	
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
		$trc = $this->GenerateTRCNo($user_details[1]['dept_id']);  

		if ($this->form_validation->run() == FALSE) {
			$cutoff = $this->Main_model->get_cutoff();
			
			$startdate = $cutoff->date;
			$enddate = $cutoff->end_date;
			$cutofftime = $cutoff->cutoff_time;
			$opentime = $cutoff->open_time;
			$currenttime = (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('H:i:s');
			$timecomparison1 = $currenttime < $cutofftime;
			$timecomparison2 = $opentime < $currenttime;

			$data['trc'] = $trc;
			$data['user_details'] = $user_details[1];                   
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();  
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();  
			
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);   
			$data['get_department'] = $get_department;

			if($enddate == '0000-00-00') {
				$enddate = $startdate;
			}

			if (($startdate <= date("Y-m-d") && date("Y-m-d") <= $enddate) || empty($startdate)) {
				if ($opentime <= $currenttime && $currenttime <= $cutofftime) {
					$this->load->view('users/header', $data);
					$this->load->view('users/users_TRC/tracc_concern_form_creation', $data);
					$this->load->view('users/footer');
				} else {
					$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
					redirect('users/list/tickets/tracc_concern');
				}
			} else {
				$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
				redirect('users/list/tickets/tracc_concern');
			}

			// if (($timecomparison1 && $timecomparison2) || $cutoff->bypass == 1) {	
			// 	$this->load->view('users/header', $data);
			// 	$this->load->view('users/users_TRC/tracc_concern_form_creation', $data);
			// 	$this->load->view('users/footer');
			// } else {
			// 	$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
			// 	redirect('sys/users/list/tickets/msrf');
			// }

			// if($timecomparison1 && $timecomparison2 && $cutoff->bypass == 0) {
			// 	$this->session->set_flashdata('error', 'Cutoff na');
			// 	redirect('sys/users/list/tickets/tracc_concern');
			// } else {
			// 	$this->load->view('users/header', $data);  
			// 	$this->load->view('users/users_TRC/tracc_concern_form_creation', $data);  
			// 	$this->load->view('users/footer');  
			// }
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
					redirect(base_url().'users/create/tickets/tracc_concern');  
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name']; 
				}
			}

			$process = $this->UsersTraccCon_model->tracc_concern_add_ticket($file_path);  
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'users/list/tickets/tracc_concern');  
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'users/create/tickets/tracc_concern');  
			}
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
				$this->load->view('users/users_TRC/tracc_concern_form_details', $data);
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
	
					$update_process = $this->UsersTraccCon_model->update_tracc_concern($control_number, $edit_data);
	
					if ($update_process[0] == 1) {
						$this->session->set_flashdata('success', 'Data updated successfully.');
					} else {
						$this->session->set_flashdata('error', 'Failed to update data.');
					}
	
					redirect(base_url() . "users/list/tickets/tracc_concern");
	
				} elseif ($action == 'acknowledge') {
					$acknowledge_data = [
						'ack_as_resolved' => $this->input->post('ack_as_res_by', true),
						'ack_as_resolved_date' => $this->input->post('ack_as_res_date', true)
					];
	
					$acknowledge_process = $this->UsersTraccCon_model->AcknolwedgeAsResolved($control_number, $acknowledge_data);
	
					if ($acknowledge_process[0] == 1) {
						$this->session->set_flashdata('success', 'Ticket successfully acknowledged as resolved.');
					} else {
						$this->session->set_flashdata('error', 'Failed to acknowledge ticket as resolved.');
					}
	
					redirect(base_url() . "users/list/tickets/tracc_concern");
				} else {
					$this->session->set_flashdata('error', 'Invalid action.');
					redirect(base_url() . "users/list/tickets/tracc_concern");
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
}
?>