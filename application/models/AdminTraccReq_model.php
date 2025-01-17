<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTraccReq_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

    public function status_approval_trf() {
		$ticket_id = $this->input->post('trf_number', true);
		$accomplished_by = $this->input->post('accomplished_by', true);
		$accomplished_by_date = $this->input->post('accomplished_by_date', true);
		$it_approval_stat = $this->input->post('it_app_stat', true);
		$approval_stat = $this->input->post('app_stat', true);
		$reject_reason = $this->input->post('reason_rejected', true);
		// $priority = $this->input->post('priority', true);

		$this->db->trans_start();

		$qry = $this->db->query('SELECT * FROM service_request_tracc_request WHERE ticket_id = ?', array ($ticket_id));

		if ($qry->num_rows() > 0){
			$row = $qry->row();

			if ($approval_stat == 'Rejected'){
				$this->db->set('approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');
			} else if ($approval_stat == 'Approved') {
				$this->db->set('approval_status', 'Approved');
				$this->db->set('status', 'Approved');
			} else if ($approval_stat == 'Returned') {
				$this->db->set('approval_status', 'Returned');
				$this->db->set('status', 'Returned');
			}

			if ($it_approval_stat == 'Resolved'){
				$this->db->set('it_approval_status', 'Resolved');
				$this->db->set('status', 'Resolved');
			} else if ($it_approval_stat == 'Rejected'){
				$this->db->set('it_approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');
				$this->db->set('reason_reject_ticket', $reject_reason);
			} else if ($it_approval_stat == 'Approved'){
				$this->db->set('it_approval_status', 'Approved');
				$this->db->set('status', 'In Progress');
			}

			// if ($priority == 'Low') {
			// 	$this->db->set('priority', 'Low');
			// } else if ($priority == 'Medium') {
			// 	$this->db->set('priority', 'Medium');
			// } else if ($priority == 'High') {
			// 	$this->db->set('priority', 'High');
			// }

			$this->db->set('accomplished_by', $accomplished_by);
			$this->db->set('accomplished_by_date', $accomplished_by_date);
			$this->db->set('reason_reject_ticket', $reject_reason);

			$this->db->where('ticket_id', $ticket_id);
			$this->db->update('service_request_tracc_request');

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				return array(0, "Error updating ticket, please try again.");
			} else {
				return array(1, "Successfully updated ticket: " . $ticket_id);
			}
		} else {
			return array(0, "Service request not found for ticket: " . $ticket_id);
		}
	}

	// CRF
	public function get_ticket_counts_customer_req($dept_id = null) {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_customer_req_form');

		if($dept_id != null) {
			$this->db->where('dept_id', $dept_id);
		} 

		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid'); 
		$query = $this->db->get();
		return $query->result_array();
	}

	// CRF
	public function get_ticket_checkbox_customer_req($recid){
		$query = $this->db->get_where('tracc_req_customer_req_form_del_days', ['recid' => $recid]);
		return $query->row_array();
	}

	// CRF
	public function update_crf_ticket_remarks($recid, $remarks){
		$this->db->set('remarks', $remarks); 
		$this->db->where('recid', $recid); 
		return $this->db->update('tracc_req_customer_req_form');
	}

	// CSS
	public function get_ticket_counts_customer_ship_setup($dept_id = null) {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_customer_ship_setup');

		if($dept_id != null) {
			$this->db->where('dept_id', $dept_id);
		}
		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid');
		$query = $this->db->get();
		return $query->result_array();
	}

	// CSS
	public function update_css_ticket_remarks($recid, $remarks){
		$this->db->set('remarks', $remarks); 
		$this->db->where('recid', $recid); 
		return $this->db->update('tracc_req_customer_ship_setup');
	}

	// ERF
	public function get_ticket_counts_employee_req($dept_id = null) {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_employee_req_form');

		if($dept_id != null) {
			$this->db->where('dept_id', $dept_id);
		}
		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid');
		$query = $this->db->get();
		return $query->result_array();
	}

	// ERF 
	public function update_erf_ticket_remarks($recid, $remarks){
		$this->db->set('remarks', $remarks); 
		$this->db->where('recid', $recid); 
		return $this->db->update('tracc_req_employee_req_form');
	}

	// IRF 
	public function get_ticket_counts_item_req_form($dept_id = null) {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_item_request_form');

		if($dept_id != null) {
			$this->db->where('dept_id', $dept_id);
		}
		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid');
		$query = $this->db->get();
		return $query->result_array();
	}

	// IRF 
	public function update_irf_ticket_remarks($recid, $lmi_item_code, $remarks){
		$this->db->set('lmi_item_code', $lmi_item_code);
		$this->db->set('remarks', $remarks);
		$this->db->where('recid', $recid);
		return $this->db->update('tracc_req_item_request_form');
	}

	// IRF 
	public function get_ticket_checkbox1_item_req_form($recid) {
		$query = $this->db->get_where('tracc_req_item_request_form_checkboxes', ['recid' => $recid]);
		return $query->row_array(); 
	}

	// IRF 
	public function get_ticket_checkbox2_item_req_form($ticket_id) {
		$this->db->select('*');
		$this->db->where('ticket_id', $ticket_id); 
		$query = $this->db->get('tracc_req_item_req_form_gl_setup');
		return $query->result_array(); 
	}

	// IRF 
	public function get_ticket_checkbox3_item_req_form($ticket_id) {
		$this->db->select('*');
		$this->db->where('ticket_id', $ticket_id);
		$query = $this->db->get('tracc_req_item_req_form_whs_setup');
		return $query->result_array();
	}

	// SRF
	public function get_ticket_counts_supplier_req($dept_id = null) {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_supplier_req_form');

		if($dept_id != null) {
			$this->db->where('dept_id', $dept_id);
		}
		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid');
		$query = $this->db->get();
		return $query->result_array();
	}

	// SRF
	public function get_ticket_checkbox_supplier_req_by_ticket_id($ticket_id) {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_supplier_req_form_checkboxes');
		$this->db->where('ticket_id', $ticket_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	// SRF
	public function get_ticket_checkbox_supplier_req($recid) {
		$query = $this->db->get_where('tracc_req_supplier_req_form_checkboxes', ['recid' => $recid]);
		return $query->row_array(); 
	}

	// SRF
	public function update_srf_ticket_remarks($recid, $remarks){
		$this->db->set('remarks', $remarks);
		$this->db->where('recid', $recid);
		return $this->db->update('tracc_req_supplier_req_form');
	}


	// ----------------------------------- Approving of Form ----------------------------------- //

	// Approve Customer Request Form
	public function approve_crf($approved_by, $recid){
		$data = [
			'approved_by' 		=> $approved_by,
			'approved_date' 	=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_customer_req_form', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	// Approve Customer Shipping Setup
	public function approve_css($approved_by, $recid){
		$data = [
			'approved_by' 		=> $approved_by,
			'approved_date' 	=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_customer_ship_setup', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	// Approve Employee Request Form
	public function approve_erf($approved_by, $recid){
		$data = [
			'approved_by'		=> $approved_by,
			'approved_date'		=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_employee_req_form', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	// Approve Item Request Form
	public function approve_irf($approved_by, $recid){
		$data = [
			'approved_by'		=> $approved_by,
			'approved_date'		=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_item_request_form', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	// Approve Supplier Request Form
	public function approve_srf($approved_by, $recid){
		$data = [
			'approved_by'		=> $approved_by,
			'approved_date'		=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_supplier_req_form', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	public function trf_add_ticket($file_path = null, $comp_checkbox_values = null, $checkbox_data_newadd, $checkbox_data_update, $checkbox_data_account) {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$trf_number = $this->input->post('trf_number', true);
		$fullname = $this->input->post('name', true);
		$department_description = $this->input->post('department_description', true);
		$department_id = $this->input->post('dept_id', true);
		$date_requested = $this->input->post('date_req', true);
		$date_needed = $this->input->post('date_needed', true);
		$complete_details = $this->input->post('complete_details', true);
		$acknowledge_by = $this->input->post('acknowledge_by', true);
		$acknowledge_by_date = $this->input->post('acknowledge_by_date', true);
	
		$query = $this->db->select('ticket_id')
					->where('ticket_id', $trf_number)
					->get('service_request_tracc_request');
		if($query->num_rows() > 0) {
			return array(0, "Data is Existing");
		} else {
			$priority = 'Low';
			if (
				!empty($checkbox_data_newadd['checkbox_item']) ||
				!empty($checkbox_data_newadd['checkbox_customer']) ||
				!empty($checkbox_data_newadd['checkbox_supplier']) ||
				!empty($checkbox_data_newadd['checkbox_whs']) ||
				!empty($checkbox_data_newadd['checkbox_bin']) ||
				!empty($checkbox_data_newadd['checkbox_cus_ship_setup']) ||
				!empty($checkbox_data_newadd['checkbox_employee_req_form']) ||
				!empty($checkbox_data_newadd['checkbox_others_newadd'])
			) {
				$priority = 'High';
			}
			else if (
				!empty($checkbox_data_update['checkbox_system_date_lock']) ||
				!empty($checkbox_data_update['checkbox_user_file_access']) ||
				!empty($checkbox_data_update['checkbox_item_dets']) ||
				!empty($checkbox_data_update['checkbox_customer_dets']) ||
				!empty($checkbox_data_update['checkbox_supplier_dets']) ||
				!empty($checkbox_data_update['checkbox_employee_dets']) ||
				!empty($checkbox_data_update['checkbox_others_update'])
			) {
				$priority = "Medium";
			}
			$data = array(
				'ticket_id' 				            => $trf_number,
				'subject' 					            => 'TRACC_REQUEST',
				'requested_by' 				            => $fullname,
				'department' 				            => $department_description,
                'department_id' 			            => $department_id,
                'date_requested'		 	            => $date_requested,
				'date_needed' 				            => $date_needed,
				'requested_by_id' 			            => $user_id,
				'complete_details' 			            => $complete_details,
				'priority' 					            => $priority,
				// 'acknowledge_by' 			            => $acknowledge_by,
				// 'acknowledge_by_date'		            => $acknowledge_by_date,
				'status' 					            => 'Open',
				'approval_status' 			            => 'Pending',
				'it_approval_status' 		            => 'Pending',
				'created_at' 				            => date("Y-m-d H:i:s")
			);
	
			if ($file_path !== null) {
				$data['file'] = $file_path;
			}
	
			if ($comp_checkbox_values !== null) {
				$data['company'] = $comp_checkbox_values;
			}
	
			$this->db->trans_start();
			$query = $this->db->insert('service_request_tracc_request', $data);
	
			if ($this->db->affected_rows() > 0) {
				$checkbox_entry_newadd = [
					'ticket_id'                         => $trf_number,
					'item'                              => isset($checkbox_data_newadd['checkbox_item']) ? $checkbox_data_newadd['checkbox_item'] : 0,
					'customer'                          => isset($checkbox_data_newadd['checkbox_customer']) ? $checkbox_data_newadd['checkbox_customer'] : 0,
					'supplier'                          => isset($checkbox_data_newadd['checkbox_supplier']) ? $checkbox_data_newadd['checkbox_supplier'] : 0,
					'warehouse'                         => isset($checkbox_data_newadd['checkbox_whs']) ? $checkbox_data_newadd['checkbox_whs'] : 0,
					'bin_number'                        => isset($checkbox_data_newadd['checkbox_bin']) ? $checkbox_data_newadd['checkbox_bin'] : 0,
					'customer_shipping_setup'           => isset($checkbox_data_newadd['checkbox_cus_ship_setup']) ? $checkbox_data_newadd['checkbox_cus_ship_setup'] : 0,
					'employee_request_form'             => isset($checkbox_data_newadd['checkbox_employee_req_form']) ? $checkbox_data_newadd['checkbox_employee_req_form'] : 0,
					'others'                            => isset($checkbox_data_newadd['checkbox_others_newadd']) ? $checkbox_data_newadd['checkbox_others_newadd'] : 0,
					'others_description_add'            => isset($checkbox_data_newadd['others_text_newadd']) ? $checkbox_data_newadd['others_text_newadd'] : ""
				];
				$this->db->insert('tracc_req_mf_new_add', $checkbox_entry_newadd);

				$checkbox_entry_update = [
					'ticket_id'                         => $trf_number,
					'system_date_lock'                  => isset($checkbox_data_update['checkbox_system_date_lock']) ? $checkbox_data_update['checkbox_system_date_lock'] : 0,
					'user_file_access'                  => isset($checkbox_data_update['checkbox_user_file_access']) ? $checkbox_data_update['checkbox_user_file_access'] : 0,
					'item_details'                      => isset($checkbox_data_update['checkbox_item_dets']) ? $checkbox_data_update['checkbox_item_dets'] : 0,
					'customer_details'                  => isset($checkbox_data_update['checkbox_customer_dets']) ? $checkbox_data_update['checkbox_customer_dets'] : 0,
					'supplier_details'                  => isset($checkbox_data_update['checkbox_supplier_dets']) ? $checkbox_data_update['checkbox_supplier_dets'] : 0,
					'employee_details'                  => isset($checkbox_data_update['checkbox_employee_dets']) ? $checkbox_data_update['checkbox_employee_dets'] : 0,
					'others'                            => isset($checkbox_data_update['checkbox_others_update']) ? $checkbox_data_update['checkbox_others_update'] : 0,
					'others_description_update'         => isset($checkbox_data_update['others_text_update']) ? $checkbox_data_update['others_text_update'] : ""
				];
				$this->db->insert('tracc_req_mf_update', $checkbox_entry_update);

				$checkbox_entry_account = [
					'ticket_id'                         => $trf_number,
					'tracc_orientation'                 => isset($checkbox_data_account['checkbox_tracc_orien']) ? $checkbox_data_account['checkbox_tracc_orien'] : 0,
					'lmi'                               => isset($checkbox_data_account['checkbox_create_lmi']) ? $checkbox_data_account['checkbox_create_lmi'] : 0,
					'rgdi'                              => isset($checkbox_data_account['checkbox_create_rgdi']) ? $checkbox_data_account['checkbox_create_rgdi'] : 0,
					'lpi'                               => isset($checkbox_data_account['checkbox_create_lpi']) ? $checkbox_data_account['checkbox_create_lpi'] : 0,
					'sv'                                => isset($checkbox_data_account['checkbox_create_sv']) ? $checkbox_data_account['checkbox_create_sv'] : 0,
					'gps_account'                       => isset($checkbox_data_account['checkbox_gps_account']) ? $checkbox_data_account['checkbox_gps_account'] : 0,
					'others'                            => isset($checkbox_data_account['checkbox_others_account']) ? $checkbox_data_account['checkbox_others_account'] : 0,
					'others_description_acc'            => isset($checkbox_data_account['others_text_account']) ? $checkbox_data_account['others_text_account'] : ""
				];
				$this->db->insert('tracc_req_mf_account', $checkbox_entry_account);

				$this->db->trans_commit();
				return array(1, "Successfully Created Ticket: ".$trf_number);
			} else {
				$this->db->trans_rollback();
				return array(0, "There seems to be a problem when inserting new user. Please try again.");
			}
		}
	}

}
?>