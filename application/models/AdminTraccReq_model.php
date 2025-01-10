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
				$this->db->set('status', 'Closed');
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
	public function get_ticket_counts_customer_req() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_customer_req_form');
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
	public function get_ticket_counts_customer_ship_setup() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_customer_ship_setup');
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
	public function get_ticket_counts_employee_req() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_employee_req_form');
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
	public function get_ticket_counts_item_req_form() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_item_request_form');
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
	public function get_ticket_counts_supplier_req() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_supplier_req_form');
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

}
?>