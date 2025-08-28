<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDashboard_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library(['session','user_agent']);
	}

	private function currentRole() {
        $d = $this->session->userdata('login_data') ?? [];
        return $d['role'] ?? null;
    }

    private function currentUserId() {
        $d = $this->session->userdata('login_data') ?? [];
        return $d['user_id'] ?? null;
    }

	private function statusesForRole($role) {
		if ($role === 'L2') {
			return ['Open', 'Returned'];
		}
		
		return ['In Progress', 'Open', 'Approved'];
	}

    public function get_total_users(){
		return $this->db->count_all('users');
	}

	public function get_total_departments(){
		return $this->db->count_all('departments');
	}

    public function get_total_msrf_ticket(){
		$role = $this->currentRole();
		$this->db->from('service_request_msrf');
		$this->db->where_in('status', $this->statusesForRole($role));
		return $this->db->count_all_results();
	}

	public function get_total_tracc_concern_ticket(){
		$role = $this->currentRole();
		$this->db->from('service_request_tracc_concern');
		$this->db->where_in('status', $this->statusesForRole($role));
		return $this->db->count_all_results();
	}

	public function get_total_tracc_request_ticket(){
		$role = $this->currentRole();
		$this->db->from('service_request_tracc_request');
		$this->db->where_in('status', $this->statusesForRole($role));
		return $this->db->count_all_results();
	}
}
?>