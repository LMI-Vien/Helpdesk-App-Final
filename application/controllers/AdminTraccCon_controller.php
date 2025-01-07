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
						$this->session->set_flashdata('success', 'Tickets Approved');
					} else {
						$this->session->set_flashdata('error', 'Updated.');
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