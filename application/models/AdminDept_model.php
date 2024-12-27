<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDept_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}
    
    public function add_department(){		
		$this->db->trans_begin();
	
		$dept_desc = $this->input->post('dept_desc', true);
		$manager_id = $this->input->post('manager_id', true);
		$sup_id = $this->input->post('sup_id', true);
	
		$data = array(
			'dept_desc' => $dept_desc,
			'manager_id' => $manager_id,
			'sup_id' => $sup_id
		);
	
		$query = $this->db->insert('departments', $data);
	
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback(); 
			return array(0, "Error adding department. Please try again."); 
		} else {
			$this->db->trans_commit();  
			return array(1, "Successfully added department."); 
		}
	}

    public function update_department($data, $id) {
		$id = (int) $id;

		if (empty($data)) {
			return array(0, "No data provided for update.");
		}

		$this->db->where('recid', $id);
		$this->db->update('departments', $data);
	
		if ($this->db->affected_rows() > 0) {
			return array(1, "Department updated successfully.");
		} else {
			$this->db->where('recid', $id);
			$query = $this->db->get('departments');
			
			if ($query->num_rows() > 0) {
				return array(0, "No changes were made.");
			} else {
				return array(0, "Department not found.");
			}
		}
	}

    public function delete_department($id) {
        $this->db->where('recid', $id);
        $this->db->delete('departments');
    
        if ($this->db->affected_rows() >= 0) {
            return array(1, "Department deleted successfully.");
        } else {
            return array(0, "No changes were made to the department.");
        }
    }
}