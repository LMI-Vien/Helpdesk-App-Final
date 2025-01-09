<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminUsers_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

    // Adding Users/Employee
    public function add_employee() {
		$emp_id = $this->input->post('emp_id', true);
		$fname = $this->input->post('fname', true);
		$mname = $this->input->post('mname', true);
		$lname = $this->input->post('lname', true);
		$email = $this->input->post('email', true);
		$department = $this->input->post('department', true);
		$position = $this->input->post('position', true);
		$role = $this->input->post('role', true);
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);
		$cpassword = $this->input->post('cpassword', true);
	
		if (!empty($password) && $password !== $cpassword) {
			return array(0, "Password and Confirm Password do not match.");
		}
	
		$new_password = password_hash($password, PASSWORD_DEFAULT);
	
		$sup_id = $this->Main_model->getSupervisorByDepartment($department);
	
		list($status, $department_details) = $this->Main_model->get_department_details($department);

		if ($status === "error") {
			return array(0, $department_details); 
		}

		$department_description = $department_details['dept_desc']; 
	
		$data = array(
			'emp_id' => $emp_id,
			'fname' => $fname,
			'mname' => $mname,
			'lname' => $lname,
			'email' => $email,
			'position' => $position,
			'username' => $username,
			'password' => $new_password,
			//'s_password' => $password,
			'api_password' => $new_password,
			//'s_api_password' => $password,
			'dept_id' => $department, 
			'department_description' => $department_description, 
			'sup_id' => $sup_id,
			'role' => $role,
			'status' => 1,
			'failed_attempts' => 1,
			'created_at' => date("Y-m-d H:i:s")
		);
	
		$this->db->trans_start(); 
		$query = $this->db->insert('users', $data);

		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();  
			return array(1, "Successfully added employee."); 
		} else {
			$this->db->trans_rollback();  
			return array(0, "Error adding employee. Please try again."); 
		}
	}

    // Updating Employee
    public function update_employee() {
		$id = $this->input->post('id', true);
		$emp_id = $this->input->post('emp_id', true);
		$fname = $this->input->post('fname', true);
		$mname = $this->input->post('mname', true);
		$lname = $this->input->post('lname', true);
		$email = $this->input->post('email', true);
		$department = $this->input->post('department', true);
		$position = $this->input->post('position', true);
		$role = $this->input->post('role', true);
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);
		$cpassword = $this->input->post('cpassword', true);
		
		if (!empty($password) && $password !== $cpassword) {
			return array(0, "Passwords do not match.");
		}

		$department_details = $this->Main_model->get_department_details($department);  
		if ($department_details[0] == "ok") {
			$department_description = $department_details[1]['dept_desc']; 
		} else {
			return array(0, "Department not found.");
		}

		$this->db->trans_start();
		 
		$data = [
			'emp_id' => $emp_id,
			'fname' => $fname,
			'mname' => $mname,
			'lname' => $lname,
			'email' => $email,
			'position' => $position,
			'username' => $username,
			'dept_id' => $department,
			'department_description' => $department_description,
			'role' => $role,
			'status' => 1,
			'failed_attempts' => 1,
			'updated_at' => date("Y-m-d H:i:s")
		];
	
		// If a new password is provided, hash and update it
		if (!empty($password)) {
			$data['password'] = password_hash($password, PASSWORD_DEFAULT);
			//$data['s_password'] = $password; // If you need to store the plain text password for some reason
			$data['api_password'] = password_hash($password, PASSWORD_DEFAULT);
			//$data['s_api_password'] = $password;
		}
	
		$this->db->where('recid', $id);
		$this->db->update('users', $data);
	
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return array(0, "Error updating Users. Please try again.");
		} elseif ($this->db->affected_rows() > 0) {
			return array(1, "Successfully updated User ID: ". $id);
		} else {
			return array(1, "No changes were made for User ID: " . $id);
		}
	}

    // Deleting Employee
    public function delete_employee($id) {
		$this->db->where('recid', $id);
		$this->db->delete('users');

		if($this->db->affected_rows() >= 0){
			return array(1, "Employee deleted successfully.");
		}else{
			return array(0, "No changes were made to the Employee.");
		}
	}
    
}