<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersMSRF_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

    // MSRF TICKET CREATION
    public function msrf_add_ticket($file_path = null) {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$msrf_number = $this->input->post('msrf_number', true);
		$fullname = $this->input->post('name', true);
		$department_description = $this->input->post('department_description', true);
		$department_id = $this->input->post('dept_id', true);
		$date_req = $this->input->post('date_req', true);
		$date_need = $this->input->post('date_need', true);
		$asset_code = $this->input->post('asset_code', true);
		$category = $this->input->post('category', true);
		$specify = $this->input->post('specify', true);
		$concern = $this->input->post('msrf_concern', true);
		$sup_id = $this->input->post('sup_id', true);
		
		$query = $this->db->select('ticket_id')
					->where('ticket_id', $msrf_number)
					->get('service_request_msrf');
		if ($query->num_rows() > 0) {
			return array("error", "Data is Existing");
		} else {
			if ($category === "computer" || $category === "printer" || $category === "network") {
				$spec = '';
				$categ = "High";
			} else if ($category === "projector") {
				$spec = '';
				$categ = "Medium";
			} else if ($category === "others") {
				$spec = $specify;
				$categ = "Low";
			}

			$data = array(
				'ticket_id' => $msrf_number,
				'subject' => 'MSRF',
				'requestor_name' => $fullname,
				'department' => $department_description,
				'dept_id' => $department_id,
				'date_requested' => $date_req,
				'date_needed' => $date_need,
				'asset_code' => $asset_code,   
				'category' => $category,
				'specify' => $spec,
				'details_concern' => $concern,
				'status' => 'Open',
				'approval_status' => 'Pending',
				'priority' => $categ,
				'requester_id' => $user_id,
				'sup_id' => $sup_id,
				'it_dept_id' => 1,
				'it_sup_id' => '23-0001',
				'it_approval_status' => 'Pending',
				'created_at' => date("Y-m-d H:i:s")
			);
			
			if ($file_path !== null) {
				$data['file'] = $file_path;
			}
			
			$this->db->trans_start();
			$query = $this->db->insert('service_request_msrf', $data);
			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Created Ticket: ".$msrf_number."");
			} else {
				$this->db->trans_rollback();
				return array(0, "There seems to be a problem when inserting new user. Please try again.");
			}
		}
	}

    // MSRF Updating function
    public function UpdateMSRFAssign($ticket_id, $date_needed, $asset_code, $request_category, $specify, $details_concern) {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$status = $this->input->post('it_status', true);
		$status_users = $this->input->post('status_users', true);
		$status_requestor = $this->input->post('status_requestor', true);
		
		$qry = $this->db->query('SELECT * FROM service_request_msrf WHERE ticket_id = ?', [$ticket_id]);

		if ($qry->num_rows() === 0) {
			return [0, "Service request not found for ticket: " . $ticket_id];
		}

		$row = $qry->row();

		$this->db->trans_begin();

		if (isset($row->approval_status) && $row->approval_status === 'Returned') {
			$this->db->set('status', 'Open');
			$this->db->set('approval_status', 'Pending');
		}

		$this->db->set('date_needed', $date_needed);
		$this->db->set('asset_code', $asset_code);
		$this->db->set('category', $request_category);
		$this->db->set('specify', $specify);
		$this->db->set('details_concern', $details_concern);

		$this->db->where('ticket_id', $ticket_id);
		$this->db->update('service_request_msrf');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return [0, "Error updating ticket. Please try again."];
		} else {
			$this->db->trans_commit();
			return [1, "Successfully updated ticket: " . $ticket_id];
		}
	}

}
?>