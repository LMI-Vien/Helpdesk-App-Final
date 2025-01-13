<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTraccCon_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('AdminTraccCon_model');
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

		if ($this->form_validation->run() == FALSE) {
			$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
			$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
			$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

			$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';
			// print_r($active_menu);
			// die();

			$data['active_menu'] = $active_menu;
			$cutoff = $this->Main_model->get_cutoff();
			$cutofftime = $cutoff->cutoff_time;
			$opentime = $cutoff->open_time;
			$currenttime = (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('H:i:s');
			$timecomparison1 = $currenttime < $cutofftime;
			$timecomparison2 = $opentime < $currenttime;

			$data['user_details'] = $user_details[1];                   
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();  
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();  
			
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);   
			$data['get_department'] = $get_department;

			if (($timecomparison1 && $timecomparison2) || $cutoff->bypass == 1) {	
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRC/trc_creation', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
				redirect('sys/admin/list/ticket/tracc_concern');
			}

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
					redirect(base_url().'sys/admin/create/tickets/tracc_concern');  
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name']; 
				}
			}

			$process = $this->AdminTraccCon_model->tracc_concern_add_ticket($file_path);  
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/admin/list/ticket/tracc_concern');  
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/admin/create/tickets/tracc_concern');  
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
				// print_r($active_menu);
				// die();

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
					$solution = $this->input->post('solution');
					$resolved_by = $this->input->post('resolved_by');
					$resolved_date = $this->input->post('res_date');
					$others = $this->input->post('others');
					$received_by_lst = $this->input->post('received_by_lst');
					$date_lst = $this->input->post('date_lst');

					$checkbox_data = [
						'control_number'    => $control_number,
						'for_mis_concern'   => $this->input->post('checkbox_mis') ? 1 : 0,
						'for_lst_concern'   => $this->input->post('checkbox_lst') ? 1 : 0,
						'system_error'      => $this->input->post('checkbox_system_error') ? 1 : 0,
						'user_error'        => $this->input->post('checkbox_user_error') ? 1 : 0,
					];

					$process = $this->AdminTraccCon_model->status_approval_tracc_concern($control_number, $received_by, $noted_by, $priority, $approval_stat, $reject_ticket, $solution, $resolved_by, $resolved_date, $others, $received_by_lst, $date_lst);
					$process_checkbox = $this->AdminTraccCon_model->insert_checkbox_data($checkbox_data);
	
					if ($process[0] == 1 && $process_checkbox[0] == 1) {
						$this->session->set_flashdata('success', 'Tickets Approved: ' . $control_number);
					} else {
						$this->session->set_flashdata('error', 'Updated the ticket: ' . $control_number);
					}
	
					redirect(base_url()."sys/admin/list/ticket/tracc_concern");
				}

				// Load views
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRC/tickets_tracc_concern', $data);
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

	public function admin_closed_tickets($active_menu = 'closed_tickets_list') {
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
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
				$this->load->view('admin/admin_TRC/closed_tracc_concern', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}
}
?>