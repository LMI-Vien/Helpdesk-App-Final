<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTraccReq_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('upload'); // Load the upload library
    	$this->load->helper('form'); // Load form helper
		$this->load->library('session');
        $this->load->model('AdminTraccReq_model');

		if($this->session->userdata('login_data')['role'] == 'L1') {
			show_404();
		}
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


    //TRACC REQUEST List of Ticket for ADMIN
	public function admin_list_tracc_request(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		if ($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok"){
				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'system_tickets_list';

				$data['active_menu'] = $active_menu;

				if ($this->input->post()) {
					$trf_number = $this->input->post('trf_number');
					$approval_stat = $this->input->post('app_stat');

					$process = $this->AdminTraccReq_model->status_approval_trf($trf_number, $app_stat);
					
					if (isset($process[0]) && $process[0] == 1) {
						$this->session->set_flashdata('success', "Ticket " . $trf_number . " has been Updated");
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

				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

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
				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

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
	public function cus_req_form_JTabs($dept_id){
		$user_role = $this->session->userdata('login_data')['role'];

		$dept = $user_role == 'L3' ? null : $dept_id;

		$tickets = $this->AdminTraccReq_model->get_ticket_counts_customer_req($dept);

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
				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

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
	public function cus_ship_setup_JTtabs($dept_id){
		$user_role = $this->session->userdata('login_data')['role'];

		$dept = $user_role == 'L3' ? null : $dept_id;

		$tickets = $this->AdminTraccReq_model->get_ticket_counts_customer_ship_setup($dept);
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
				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

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
	public function emp_req_form_JTabs($dept_id){
		$user_role = $this->session->userdata('login_data')['role'];

		$dept = $user_role == 'L3' ? null : $dept_id;

		$tickets = $this->AdminTraccReq_model->get_ticket_counts_employee_req($dept);

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
				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

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
	public function item_req_form_JTabs($dept_id){
		$user_role = $this->session->userdata('login_data')['role'];

		$dept = $user_role == 'L3' ? null : $dept_id;

		$tickets = $this->AdminTraccReq_model->get_ticket_counts_item_req_form($dept);

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
		$lmi_item_code = $this->input->post('lmi_item_code'); 
		// print_r($lmi_item_code);
		// die();

		$result = $this->AdminTraccReq_model->update_irf_ticket_remarks($recid, $lmi_item_code, 'Done'); 

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
				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

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
	public function sup_req_form_JTabs($dept_id){
		$user_role = $this->session->userdata('login_data')['role'];

		$dept = $user_role == 'L3' ? null : $dept_id;

		$tickets = $this->AdminTraccReq_model->get_ticket_counts_supplier_req($dept);

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

	 // DATATABLE na makikita ni Admin sa get_tracc_request_form
	 public function tracc_request_list(){
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
			$this->load->view('admin/header', $data);
			$this->load->view('admin/sidebar', $data);
			$this->load->view('admin/admin_TRF/admin_list_tracc_request_creation', $data);
			$this->load->view('admin/footer');
	
		} else {
			$process = $this->UsersTraccReq_model->trf_add_ticket();

			if ($process[0] == 1){
				$this->session->set_flashdata('success', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url().'sys/users/dashboard');
			}

		}
	}

	public function admin_creation_tickets_tracc_request() {
		$id = $this->session->userdata('login_data')['user_id'];
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('upload');
	
		$this->form_validation->set_rules('trf_number', 'Ticket Number', 'trim|required');
	
		$user_details = $this->Main_model->user_details();
		$getdepartment = $this->Main_model->GetDepartmentID();
		$users_det = $this->Main_model->users_details_put($id);
	
		$cutoff = $this->Main_model->get_cutoff();
		$cutofftime = $cutoff->cutoff_time;
		$opentime = $cutoff->open_time;
		$currenttime = (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('H:i:s');
		$timecomparison1 = $currenttime < $cutofftime;
		$timecomparison2 = $opentime < $currenttime;

		if ($this->form_validation->run() == FALSE) {
			$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
			$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
			$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

			$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu', 'admin_creation_ticket'];
			$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'admin_creation_ticket';

			$data['active_menu'] = $active_menu;
			
			$trf = $this->GenerateTRFNo();
			$data['trf'] = $trf;
			$data['user_details'] = $user_details[1];
			$data['users_det'] = isset($users_det[1]) ? $users_det[1] : array();
			$data['getdept'] = isset($getdepartment[1]) ? $getdepartment[1] : array();
	
			$users_department = $users_det[1]['dept_id'];
			$get_department = $this->Main_model->UsersDepartment($users_department);
			$data['get_department'] = $get_department;
	
			if (($timecomparison1 && $timecomparison2) || $cutoff->bypass == 1) {	
				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF/trf_creation', $data);
				$this->load->view('admin/footer');
			} else {
				$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
				redirect('sys/admin/list/creation_tickets/tracc_request');
			}
			
			// if($timecomparison1 && $timecomparison2 && $cutoff->bypass == 0) {
			// 	$this->session->set_flashdata('error', '<strong style="color:red;">⚠️ Cutoff Alert:</strong> This is the cutoff point.');
			// 	redirect('sys/users/dashboard');
			// } else {
			// 	$this->load->view('users/header', $data);
			// 	$this->load->view('users/users_TRF/tracc_request_form_creation', $data);
			// 	$this->load->view('users/footer');
			// }
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
					redirect(base_url() . 'sys/admin/create/tickets/tracc_request');
				} else {
					$file_data = $this->upload->data();
					$file_path = $file_data['file_name'];
				}
			}
	
			$checkbox_data_newadd = [
				'checkbox_item'                 => $this->input->post('checkbox_item') ? 1 : 0,
				'checkbox_customer'             => $this->input->post('checkbox_customer') ? 1 : 0,
				'checkbox_supplier'             => $this->input->post('checkbox_supplier') ? 1 : 0,
				'checkbox_whs'                  => $this->input->post('checkbox_whs') ? 1 : 0,
				'checkbox_bin'                  => $this->input->post('checkbox_bin') ? 1 : 0,
				'checkbox_cus_ship_setup'       => $this->input->post('checkbox_cus_ship_setup') ? 1 : 0,
				'checkbox_employee_req_form'    => $this->input->post('checkbox_employee_req_form') ? 1 : 0,
				'checkbox_others_newadd'        => $this->input->post('checkbox_others_newadd') ? 1 : 0, 
				'others_text_newadd'            => $this->input->post('others_text_newadd')
			];

			$checkbox_data_update = [
				'checkbox_system_date_lock'     => $this->input->post('checkbox_system_date_lock') ? 1 : 0,
				'checkbox_user_file_access'     => $this->input->post('checkbox_user_file_access') ? 1 : 0,
				'checkbox_item_dets'            => $this->input->post('checkbox_item_dets') ? 1 : 0,
				'checkbox_customer_dets'        => $this->input->post('checkbox_customer_dets') ? 1 : 0,
				'checkbox_supplier_dets'        => $this->input->post('checkbox_supplier_dets') ? 1 : 0,
				'checkbox_employee_dets'        => $this->input->post('checkbox_employee_dets') ? 1 : 0,
				'checkbox_others_update'        => $this->input->post('checkbox_others_update') ? 1 : 0,
				'others_text_update'            => $this->input->post('others_text_update')
			]; 

			$checkbox_data_account = [
				'checkbox_tracc_orien'          => $this->input->post('checkbox_tracc_orien') ? 1 : 0,
				'checkbox_create_lmi'           => $this->input->post('checkbox_create_lmi') ? 1 : 0,
				'checkbox_create_lpi'           => $this->input->post('checkbox_create_lpi') ? 1 : 0,
				'checkbox_create_rgdi'          => $this->input->post('checkbox_create_rgdi') ? 1 : 0,
				'checkbox_create_sv'            => $this->input->post('checkbox_create_sv') ? 1 : 0,
				'checkbox_gps_account'          => $this->input->post('checkbox_gps_account') ? 1 : 0,
				'checkbox_others_account'       => $this->input->post('checkbox_others_account') ? 1 : 0,
				'others_text_account'           => $this->input->post('others_text_account')
			];
	
			$comp_checkbox_values = isset($_POST['comp_checkbox_value']) ? $_POST['comp_checkbox_value'] : [];
			$imploded_values = implode(',', $comp_checkbox_values);
	
			$process = $this->AdminTraccReq_model->trf_add_ticket($file_path, $imploded_values, $checkbox_data_newadd, $checkbox_data_update, $checkbox_data_account);

			$newadd = [
				'Item Request Form'             => $checkbox_data_newadd['checkbox_item'],
				'Customer Request Form'         => $checkbox_data_newadd['checkbox_customer'],
				'Supplier Request Form'         => $checkbox_data_newadd['checkbox_supplier'],
				'Customer Shipping Setup'       => $checkbox_data_newadd['checkbox_cus_ship_setup'],
				'Employee Request Form'         => $checkbox_data_newadd['checkbox_employee_req_form'],
			];
	
			if ($process[0] == 1) {
				$this->session->set_flashdata('success', $process[1]);
				$this->session->set_userdata('data', ['checkbox_data' => $newadd, 'expires_at' => time() + (5 * 60)]);
				redirect(base_url() . 'sys/admin/list/creation_tickets/tracc_request');
			} else {
				$this->session->set_flashdata('error', $process[1]);
				redirect(base_url() . 'sys/admin/list/creation_tickets/tracc_request');
			}
		}
	}

	//Tracc Request details ADMIN
	public function admin_tracc_request_details($id) {
		if($this->session->userdata('login_data')) {
			$user_details = $this->Main_model->user_details();
			$getdepartment = $this->Main_model->GetDepartmentID();
			$getTRF = $this->Main_model->getTicketsTRF($id);
			$getCheckboxDataNewAdd = $this->Main_model->getCheckboxDataNewAdd($id);
			$getCheckeboxDataUpdate = $this->Main_model->getCheckboxDataUpdate($id);
			$getCheckboxDataAccount = $this->Main_model->getCheckboxDataAccount($id);

			if ($user_details[0] == "ok") {
				$data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();

				$allowed_menus = ['dashboard', 'system_tickets_list', 'open_tickets', 'other_menu', 'admin_creation_ticket'];
				$active_menu = ($this->uri->segment(3) && in_array($this->uri->segment(3), $allowed_menus)) ? $this->uri->segment(3) : 'admin_creation_ticket';
				$data['active_menu'] = 'admin_creation_ticket';

				$sid = $this->session->session_id;
				$data['user_details'] = $user_details[1];
				$data['getdept'] = $getdepartment[1];
				$data['trf'] = $getTRF[1];
				$data['checkbox_data_newadd'] = $getCheckboxDataNewAdd;
				$data['checkbox_data_update'] = $getCheckeboxDataUpdate;
				$data['checkbox_data_account'] = $getCheckboxDataAccount;

				$selected_companies = isset($data['trf']['company']) ? explode(',', $data['trf']['company']) : [];
				$data['selected_companies'] = $selected_companies; 

				$this->load->view('admin/header', $data);
				$this->load->view('admin/sidebar', $data);
				$this->load->view('admin/admin_TRF/admin_tracc_request_details', $data);
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

	public function update_status_tracc_request(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$trf_number = $this->input->post('trf_number', true);
		$action = $this->input->post('action', true); // Get the action (edit or acknowledge)

		// print_r($action);
		// die();

		if ($this->session->userdata('login_data')) {
			$user_id = $this->session->userdata('login_data')['user_id'];
			$user_details = $this->Main_model->user_details();

			if ($user_details[0] == "ok") {
				$sid = $this->session->session_id;

				if ($action == 'edit') {
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

					if ($process[0] == 1 || $process1[0] == 1 || $process2[0] == 1 || $process3[0] == 1) {
						$this->session->set_flashdata('success', $process[1]);
						redirect(base_url()."sys/admin/list/creation_tickets/tracc_request");
					} else {
						$this->session->set_flashdata('error', $process[0]);
						redirect(base_url()."sys/admin/list/creation_tickets/tracc_request");
					}

				}  elseif ($action == 'acknowledge') {
					$data_update_status = [
						'acknowledge_by' => $this->input->post('acknowledge_by', true),
						'acknowledge_by_date' => $this->input->post('acknowledge_by_date', true),
					];

					$acknowledge_process = $this->Main_model->Acknowledge_as_resolved($trf_number);

					// print_r($acknowledge_process);
					// die();

					if ($acknowledge_process) {
						$this->session->set_flashdata('success', 'Ticket ' . $trf_number . ' has successfully acknowledged as resolved');
					} else {
						$this->session->set_flashdata('error', 'Failed to acknowledge ticket as resolved.');
					}
	
					redirect(base_url() . "sys/admin/list/creation_tickets/tracc_request");
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

}
?>