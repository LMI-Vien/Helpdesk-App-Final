<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTraccReq_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('AdminTraccReq_model');
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

					$process = $this->AdminTraccReq_model->status_approval_trf($trf_number, $app_stat);
					
					if (isset($process[0]) && $process[0] == 1) {
						$this->session->set_flashdata('success', "Ticket's been Updated");
					} else {
						$this->session->set_flashdata('error', 'Update failed.');
					}

					redirect(base_url()."sys/admin/list/ticket/tracc_request");
				}
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF/tickets_tracc_request', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->sess_destroy();
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
		}
	}

	public function admin_closed_tickets() {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if($user_details[0] == "ok") {
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$allowed_menus = ['dashboard', 'closed_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'closed_tickets_list';

				$data['active_menu'] = $active_menu;

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF/closed_tracc_req', $data);
				$this->load->view('admin/footer');
			}
		} else {
			$this->session->flashdata('error', 'Session expired. Please login again.');
			redirect("sys/authentication");
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
		$tickets = $this->AdminTraccReq_model->get_ticket_counts_customer_req();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {

				$companies = explode(',', $ticket['company']);

				$checkbox_data = $this->AdminTraccReq_model->get_ticket_checkbox_customer_req($ticket['recid']); 

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

	// Update CRF Ticket Remarks
	public function update_crf_ticket_remarks() {
		$recid = $this->input->post('recid'); 
		$result = $this->AdminTraccReq_model->update_crf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
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
		$tickets = $this->AdminTraccReq_model->get_ticket_counts_customer_ship_setup();
		// print_r($tickets); die();

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

	// Update CSS Ticket Remarks
	public function update_css_ticket_remarks() {
		$recid = $this->input->post('recid'); 
		$result = $this->AdminTraccReq_model->update_css_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
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
		$tickets = $this->AdminTraccReq_model->get_ticket_counts_employee_req();

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

	public function update_erf_ticket_remarks() {
		$recid = $this->input->post('recid'); 

		$result = $this->AdminTraccReq_model->update_erf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
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
		$tickets = $this->AdminTraccReq_model->get_ticket_counts_item_req_form();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {

				$companies = explode(',', $ticket['company']);

				$checkbox_data1 = $this->AdminTraccReq_model->get_ticket_checkbox1_item_req_form($ticket['recid']);
				$checkbox_data2 = $this->AdminTraccReq_model->get_ticket_checkbox2_item_req_form($ticket['ticket_id']);
				$checkbox_data3 = $this->AdminTraccReq_model->get_ticket_checkbox3_item_req_form($ticket['ticket_id']);
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

	public function update_irf_ticket_remarks() {
		$recid = $this->input->post('recid'); 

		$result = $this->AdminTraccReq_model->update_irf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
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
		$tickets = $this->AdminTraccReq_model->get_ticket_counts_supplier_req();

		if ($tickets) {
			$data = [];

			foreach ($tickets as $ticket) {

				$companies = explode(',', $ticket['company']);

				$checkbox_data = $this->AdminTraccReq_model->get_ticket_checkbox_supplier_req($ticket['recid']); 
				
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

	// SRF
	public function update_srf_ticket_remarks() {
		$recid = $this->input->post('recid'); 

		$result = $this->AdminTraccReq_model->update_srf_ticket_remarks($recid, 'Done'); 
	
		if ($result) {
			echo json_encode(['message' => 'success']);
		} else {
			echo json_encode(['message' => 'error', 'error' => 'Database update failed.']);
		}
	}


	// ----------------------------------- Approving of Form ----------------------------------- //

	// Approve Customer Request Form
	public function approve_crf(){			
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->AdminTraccReq_model->approve_crf($approved_by, $recid);
		
		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/customer_request_form_pdf");
	}

	// Approve Customer Shipping Setup
	public function approve_css(){
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->AdminTraccReq_model->approve_css($approved_by, $recid);

		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/customer_shipping_setup_pdf");
	}

	// Approve Employee Request Form
	public function approve_erf(){
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->AdminTraccReq_model->approve_erf($approved_by, $recid);

		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/employee_request_form_pdf");
	}

	// Approve Item Request Form
	public function approve_irf(){
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->AdminTraccReq_model->approve_irf($approved_by, $recid);

		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/item_request_form_pdf");
	}

	// Approve Supplier Request Form
	public function approve_srf(){
		$approved_by = $this->input->post('approved_by');
		$recid = $this->input->post('recid');

		$process = $this->AdminTraccReq_model->approve_srf($approved_by, $recid);

		if (isset($process[0]) && $process[0] == 1) {
			$this->session->set_flashdata('success', "It's Approved");
		} else {
			$this->session->set_flashdata('error', 'Update failed.');
		}
		redirect(base_url()."sys/admin/supplier_request_form_pdf");
	}

}
?>