<?php
defined('BASEPATH') OR exit('No direct scripts access allowed');

class AdminCutoff_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

    public function set_cutoff() {
        $query = $this->db->get('cutoff')->row();

        $time = $this->input->post('cutoff');
        $open = $this->input->post('open_ticket');

        $this->db->set('time', $time);
        $this->db->set('open_time', $open);
        $this->db->update('cutoff');
    }

    public function bypass() {
        $this->db->select('bypass');
        $bypass = $this->db->get('cutoff')->row();

        $new_value = $bypass->bypass == 0 ? 1 : 0;

        $this->db->set('bypass', $new_value);
        $this->db->update('cutoff');
    }

    public function get_cutoff_bypass() {
        $this->db->select('*');
        $query = $this->db->get('cutoff')->row();
        return $query;
    }
}