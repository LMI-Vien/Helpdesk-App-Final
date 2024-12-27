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
		$priority = $this->input->post('priority', true);

		$this->db->trans_start();

		$qry = $this->db->query('SELECT * FROM service_request_tracc_request WHERE ticket_id = ?', array ($ticket_id));

		if ($qry->num_rows() > 0){
			$row = $qry->row();

			if ($approval_stat == 'Rejected'){
				$this->db->set('approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');
			} else if ($approval_stat == 'Approved') {
				$this->db->set('approval_status', 'Approved');
				$this->db->set('status', 'In Progress');
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

			if ($priority == 'Low') {
				$this->db->set('priority', 'Low');
			} else if ($priority == 'Medium') {
				$this->db->set('priority', 'Medium');
			} else if ($priority == 'High') {
				$this->db->set('priority', 'High');
			}

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
}
?>