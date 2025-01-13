<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMSRF_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
		$this->load->model('AdminMSRF_model');
    }

	public function GenerateMSRFNo() {
		$lastMSRF = $this->Main_model->getLastMSRFNumber();

        // Increment the last MSRF number
        $lastNumber = (int) substr($lastMSRF, -3);
        $newNumber = $lastNumber + 1;

        // Format the new MSRF number
        $newMSRFNumber = 'MSRF-' . sprintf('%03d', $newNumber);

        return $newMSRFNumber;
	}

	public function admin_creation_tickets_msrf() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('upload');
	
		$this->form_validation->set_rules('msrf_number', 'Ticket ID', 'trim|required');

		$user_details = $this->Main_model->user_details();        
		$getdepartment = $this->Main_model->GetDepartmentID();     
		$users_det = $this->Main_model->users_details_put($id); 
	
		if ($this->form_validation->run() == FALSE) {
			$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';
			$data['active_menu'] = $active_menu;

			$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
			$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
			$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
			$msrf = $this->GenerateMSRFNo();
			$cutoff = $this->Main_model->get_cutoff();
			$cutofftime = $cutoff->cutoff_time;
			$opentime = $cutoff->open_time;
			$currenttime = (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('H:i:s');
			$timecomparison1 = $currenttime < $cutofftime;
			$timecomparison2 = $opentime < $currenttime;
			
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

			if (($timecomparison1 && $timecomparison2) || $cutoff->bypass == 1) {	
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_MSRF/msrf_creation', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
				redirect('sys/admin/create/tickets/msrf');
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
					redirect(base_url().'sys/admin/create/tickets/msrf');  
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name']; 
					echo 'Uploaded file path: ' . $file_path; 
				}
			}
			$process = $this->AdminMSRF_model->msrf_admin_add_ticket($file_path);
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/admin/list/ticket/msrf');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/admin/list/ticket/msrf');
			}
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
				
				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
	
				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';
				// print_r($active_menu);
				// die();
	
				$data['active_menu'] = $active_menu;
	
				if ($this->input->post()) {
					$msrf_number = $this->input->post('msrf_number');
					$approval_stat = $this->input->post('approval_stat');
					$rejecttix = $this->input->post('rejecttix');
					
					$process = $this->AdminMSRF_model->status_approval_msrf($msrf_number, $approval_stat, $rejecttix);
					
					if (isset($process[0]) && $process[0] == 1) {
						//Tickets Approved
						$this->session->set_flashdata('success', "Ticket's " . $msrf_number . " has been Updated");
					} else {
						$this->session->set_flashdata('error', 'Update failed.');
					}
					redirect(base_url()."sys/admin/list/ticket/msrf");
				}
	
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_MSRF/tickets_msrf', $data);
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
    
	public function admin_closed_tickets() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

				$allowed_menus = ['dashboard', 'closed_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'closed_tickets_list';

				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_MSRF/closed_msrf', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}
}
?>