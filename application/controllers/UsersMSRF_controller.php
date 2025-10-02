<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersMSRF_controller extends CI_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('UsersMSRF_model');

		if($this->session->userdata('login_data')['role'] != 'L1') {
			show_404();
		}
	}

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
			$this->load->view('users/users_MSRF/service_request_form_msrf_list', $data); 
			$this->load->view('users/footer', $data); 
	
		} else { 

			$process = $this->UsersMSRF_model->msrf_add_ticket(); 

			if ($process[0] == 1) { 
				$this->session->set_flashdata('success', $process[1]); 
				redirect(base_url().'users/dashboard');
			} else {
				$this->session->set_flashdata('error', $process[1]); 
				redirect(base_url().'users/dashboard'); 
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
			$cutoff = $this->Main_model->get_cutoff();
			
			$startdate = $cutoff->date;
			$enddate = $cutoff->end_date;
			$cutofftime = $cutoff->cutoff_time;
			$opentime = $cutoff->open_time;
			$currenttime = (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('H:i:s');
			$timecomparison1 = $currenttime < $cutofftime;
			$timecomparison2 = $opentime < $currenttime;
			$bypass = (int)($cutoff->bypass ?? 0);
			
			$data['msrf'] = $msrf;
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
			
			$users_department = $users_det[1]['dept_id'];       
			$get_department = $this->Main_model->UsersDepartment($users_department); 
			$data['get_department'] = $get_department;

			$users_department = $users_det[1]['dept_id'];       
			$get_department = $this->Main_model->UsersDepartment($users_department); 
			$data['get_department'] = $get_department;

			if($enddate == '0000-00-00') {
				$enddate = $startdate;
			}

			if ($bypass === 1 || ($startdate <= date("Y-m-d") && date("Y-m-d") <= $enddate) || empty($startdate)) {
				if ($bypass === 1 || ($opentime <= $currenttime && $currenttime <= $cutofftime)) {
					$this->load->view('users/header', $data);
					$this->load->view('users/users_MSRF/service_request_form_msrf_creation', $data);
					$this->load->view('users/footer');
				} else {
					$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
					redirect('users/list/tickets/msrf');
				}
			} else {
				$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
				redirect('users/list/tickets/msrf');
			}
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
					redirect(base_url().'users/create/tickets/msrf');  
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name']; 
					echo 'Uploaded file path: ' . $file_path; 
				}
			}
			$process = $this->UsersMSRF_model->msrf_add_ticket($file_path);
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'users/list/tickets/msrf');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'users/list/tickets/msrf');
			}
		}
	}

    //MSRF details USERS
	public function service_form_msrf_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getMsrf = $this->Main_model->getTicketsMSRF($id);
			$ict_dept = $this->Main_model->get_ict();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['msrf'] = $getMsrf[1];
				$data['ict_dept'] = $ict_dept;

				$this->load->view('users/header', $data);
				$this->load->view('users/users_MSRF/service_request_form_msrf_details', $data);
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

    // Update MSRF
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

				$process = $this->UsersMSRF_model->UpdateMSRFAssign($ticket_id, $date_needed, $asset_code, $request_category, $specify, $details_concern);

				if ($process[0] == 1) {
					$this->session->set_flashdata('success', $process[1]);
					redirect(base_url()."users/list/tickets/msrf");
				} else {
					$this->session->set_flashdata('error', $process[0]);
					redirect(base_url()."users/list/tickets/msrf");
				}
			} else {
				$this->session->set_flashdata('error', 'Error fetching user information.');
				redirect("authentication");
			}
		} else {
			$this->session->set_flashdata('error', 'Error fetching user information');
            redirect(base_url()."admin/login");
		}
	}
}
?>