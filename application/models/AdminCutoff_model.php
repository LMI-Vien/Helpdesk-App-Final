<?php
defined('BASEPATH') OR exit('No direct scripts access allowed');

class AdminCutoff_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

    public function set_cutoff() {
        $this->db->get('cutoff')->row();
        
        $cutoff_time = $this->input->post('cutoff_time');
        $open_time = $this->input->post('open_time');
        
        $this->db->where('recid', 1);
        $this->db->set('cutoff_time', $cutoff_time);
        $this->db->set('open_time', $open_time);
        $this->db->update('cutoff');
    }

    public function bypass() {
        $this->db->select('bypass');
        $this->db->where('recid', 1);
        $bypass = $this->db->get('cutoff')->row();

        $new_value = $bypass->bypass == 0 ? 1 : 0;

        $this->db->set('bypass', $new_value);
        $this->db->where('recid', 1);
        $this->db->update('cutoff');
    }

    public function get_cutoff_bypass() {
        $this->db->select('*');
        $this->db->where('recid', 1);
        return $this->db->get('cutoff')->row();
    }

    public function schedule_cutoff() {
        $opentime = $this->input->post('open_time');
        $cutoff = $this->input->post('cutoff_time');
        $date = $this->input->post('date');
        $end_date = $this->input->post('end_date');

        $this->db->where('date <=', $date);
        $this->db->where('end_date >=', $date);

        if($end_date != '0000-00-00') {
            $this->db->where('date <=', $end_date);
            $this->db->where('end_date >=', $end_date);
        }

        $query = $this->db->get('cutoff')->result_array();

        if(count($query) > 0) {
            echo 'schedules cannot overlap';
        } else {
            $data = [
                "open_time" => $opentime,
                "cutoff_time" => $cutoff,
                "date" => $date,
                "end_date" => $end_date,
            ];
    
            $this->db->insert('cutoff', $data);
        }
    }

    public function get_schedule() {
        $this->db->select("*");
        return $this->db->get('cutoff')->result_array();
    }

    public function edit_schedule($recid) {
        $new_open_time = $this->input->post('new_open_time');
        $new_cutoff_time = $this->input->post('new_cutoff_time');
        $new_date = $this->input->post('new_date');
        $new_end_date = $this->input->post('new_end_date');

        $data = [
            "open_time" => $new_open_time,
            "cutoff_time" => $new_cutoff_time,
            "date" => $new_date,
            "end_date" => $new_end_date,
        ];

        $this->db->update('cutoff', $data, ['recid' => $recid]);
    }

    public function delete_schedule($recid) {
        $this->db->delete('cutoff', ['recid' =>$recid]);
    }
}