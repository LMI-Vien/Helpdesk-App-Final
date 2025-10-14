<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminTraccCon_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

    public function status_approval_tracc_concern() {
		$control_number = $this->input->post('control_number', true);
		$received_by = $this->input->post('received_by', true);
		$noted_by = $this->input->post('noted_by', true);
		// $priority = $this->input->post('priority', true);
		$approval_stat = $this->input->post('app_stat', true);
		$it_approval_stat = $this->input->post('it_app_stat', true);
		$reject_ticket_traccCon = $this->input->post('reason_rejected', true);
		$returnedTicket = $this->input->post('returnedReason', true);
		$solution = $this->input->post('tcr_solution', true);
		$resolved_by = $this->input->post('resolved_by', true);
		$resolved_date = $this->input->post('res_date', true);
		$others = $this->input->post('others', true);
		$received_by_lst = $this->input->post('received_by_lst', true);
		$date_lst = $this->input->post('date_lst', true);
		$ictAssigned = $this->input->post('ictAssigned', true);
	
		$this->db->trans_start();
	
		$qry = $this->db->query('SELECT * FROM service_request_tracc_concern WHERE control_number = ?', array($control_number));
	
		if ($qry->num_rows() > 0) {
			$row = $qry->row();

			$fields_to_update = '';
			
			if ($approval_stat == 'Rejected') {
				$this->db->set('approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');  
			} else if ($approval_stat == 'Approved') {
				$this->db->set('approval_status', 'Approved');
				$this->db->set('status', 'Approved'); 
			} else if ($approval_stat == 'Returned') {
				$this->db->set('approval_status', 'Returned');
				$this->db->set('status', 'Returned'); 
				$this->db->set('returned_ticket_reason', $returnedTicket);
			}
	
			if ($it_approval_stat == 'Resolved') {
				$this->db->set('it_approval_status', 'Resolved');
				$this->db->set('status', 'Done'); 
			} else if ($it_approval_stat == 'Closed') {
				$this->db->set('it_approval_status', 'Closed');
				$this->db->set('status', 'Closed');
			} else if ($it_approval_stat == 'Rejected') {
				$this->db->set('it_approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');
			} else if ($it_approval_stat == 'Approved') {
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

			if ($returnedReason !== null) {             
				$this->db->set('returned_ticket_reason', $returnedReason);
				$fields_to_update = true;
			}

			if (!empty($received_by)) {
				$this->db->set('received_by', $received_by);
			}
			if (!empty($noted_by)) {
				$this->db->set('noted_by', $noted_by);
			}
			if (!empty($reject_ticket_traccCon)) {
				$this->db->set('reason_reject_tickets', $reject_ticket_traccCon);
			}
			if (!empty($solution)) {
				$this->db->set('tcr_solution', $solution);
			}
			if (!empty($resolved_by)) {
				$this->db->set('resolved_by', $resolved_by);
			}
			if (!empty($resolved_date)) {
				$this->db->set('resolved_date', $resolved_date);
			}
			if (!empty($others)) {
				$this->db->set('others', $others);
			}
			if (!empty($received_by_lst)) {
				$this->db->set('received_by_lst', $received_by_lst);
			}
			if (!empty($date_lst)) {
				$this->db->set('date_lst', $date_lst);
			}

			if (!empty($ictAssigned)) {
				$this->db->set('ict_assigned', $ictAssigned);
			}

			$this->db->where('control_number', $control_number);
			$this->db->update('service_request_tracc_concern');

			$this->db->trans_complete();
	
			if ($this->db->trans_status() === FALSE) {
				return array(0, "Error updating ticket, please try again.");
			} else {
				return array(1, "Successfully updated ticket: " . $control_number);
			}
	
		} else {
			return array(0, "Tracc Concern can not found for ticket: " . $control_number);
		}
	}

    public function insert_checkbox_data($checkbox_data) {
		$this->db->where('control_number', $checkbox_data['control_number']);
		$existing_data = $this->db->get('filled_by_mis');

		if ($existing_data->num_rows() > 0){
			$this->db->where('control_number', $checkbox_data['control_number']);
			return $this->db->update('filled_by_mis', $checkbox_data);
		} else {
			return $this->db->insert('filled_by_mis', $checkbox_data);
		}
	}

	public function tracc_concern_add_ticket($file_path = null){
		$user_id = $this->session->userdata('login_data')['user_id'];
		$control_number = $this->input->post('control_number');
		$module_affected = $this->input->post('module_affected');
		$company = $this->input->post('company');
		$concern = $this->input->post('details_concern');
		$reported_by = $this->input->post('name');
		$date_rep = $this->input->post('date_rep');
		$department_description = $this->input->post('department_description', true);
		$department_id = $this->input->post('dept_id', true);

		$this->db->where('control_number', $control_number);
		$existing_control_number = $this->db->get('service_request_tracc_concern')->row();
		
		if($existing_control_number) {
			return array(0, "Control number already exists. Please use a different control number.");
		}

		$data = array(
			'control_number' 			=> $control_number,
			'subject' 					=> 'TRACC_CONCERN',
			'module_affected' 			=> $module_affected,
			'company' 					=> $company,
			'tcr_details' 				=> $concern,
			'reported_by' 				=> $reported_by,
			'reported_date' 			=> $date_rep,
			'status' 					=> 'Approved',
			'approval_status' 			=> 'Approved',
			'it_approval_status' 		=> 'Pending',
			'reported_by_id' 			=> $user_id,
			'department' 				=> $department_description,
			'dept_id' 					=> $department_id,
			'created_at' 				=> date("Y-m-d H:i:s"),
			'priority'					=> 'Medium'
		);

		if ($file_path !== null) {
			$data['file'] = $file_path;
		}

		$this->db->trans_start();
		$query = $this->db->insert('service_request_tracc_concern', $data);
		if ($this->db->affected_rows() > 0){
			$this->db->trans_commit();
			return array(1, "Successfully Created Ticket: ".$control_number."");
		}else{
			$this->db->trans_rollback();
			return array(0, "There seems to be a problem when inserting new ticket. Please try again.");
		}
	}

	public function update_tracc_concern($control_number, $data){
		$this->db->where('control_number', $control_number);
		$this->db->update('service_request_tracc_concern', $data);

		if ($this->db->affected_rows() > 0) {
			return [1, "Data updated successfully"];
		} else {
			return [0, "No changes were made or update failed"];
		}
	}

	public function AcknolwedgeAsResolved($control_number){
		$user_id = $this->session->userdata('login_data')['user_id'];
		$ack_resolved = $this->input->post('ack_as_res_by', true);
		$ack_resolved_date = $this->input->post('ack_as_res_date', true);

		$data = array(
			'ack_as_resolved' => $ack_resolved,
			'ack_as_resolved_date' => $ack_resolved_date,
			'status' => 'Closed'
		);

		$this->db->where('control_number', $control_number);
		$this->db->update('service_request_tracc_concern', $data);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('success', 'Ticket ' . $control_number . ' is acknolwedge as resolved.');
		} else {
			$this->session->set_flashdata('error', 'Error acknowledging ticket as resolved.');
		}

		redirect(base_url(). "users/list/tickets/tracc_concern");

	}


}
?>