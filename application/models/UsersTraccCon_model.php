<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersTraccCon_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

    // TRACC CONCERN TICKET CREATION
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
			'status' 					=> 'Open',
			'approval_status' 			=> 'Pending',
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