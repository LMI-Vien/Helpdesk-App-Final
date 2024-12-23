<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDashboardModel extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

    public function get_total_users(){
		return $this->db->count_all('users');
	}

    public function get_total_msrf_ticket(){
		$this->db->from('service_request_msrf');
		$this->db->where_in('status', ['In Progress', 'Open', 'Approved']);
		return $this->db->count_all_results();
	}

    public function get_total_departments(){
		return $this->db->count_all('departments');
	}

	public function get_total_tracc_concern_ticket(){
		$this->db->from('service_request_tracc_concern');
		$this->db->where_in('status', ['In Progress', 'Open', 'Approved']);
		return $this->db->count_all_results();
	}

	public function get_total_tracc_request_ticket(){
		$this->db->from('service_request_tracc_request');
		$this->db->where_in('status', ['In Progress', 'Open', 'Approved']);
		return $this->db->count_all_results();
	}
}
?>