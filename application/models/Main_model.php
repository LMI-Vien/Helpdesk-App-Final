<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}
	
	public function user_registration() {
		$password = $this->input->post('password');
		$conpassword = $this->input->post('conpassword');
	
		if ($password != $conpassword) {
			$response = array(
				'status' => 'error',
				'message' => 'Passwords do not match!'
			);
			echo json_encode($response);
			exit;
		} else {
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$dept_id = $this->input->post('dept_name');
			$department_details = $this->Main_model->get_department_details($dept_id);
			
			if ($department_details[0] == "ok") {
				$department_description = $department_details[1]['dept_desc']; 
			} else {
				$department_description = '';
			}

			$employee_id = $this->input->post('employee_id');

			$this->db->where('emp_id', $employee_id);
			$existing_user = $this->db->get('users')->row();

			if ($existing_user) {
				$response = array (
					'status' => 'error',
					'message' => 'Employee ID is already taken!'
				);
				echo json_encode($response);
				exit;
			}
	
			$data = array(
				"emp_id" 						=> $this->input->post('employee_id'),
				"fname" 						=> $this->input->post('firstname'),
				"mname" 						=> $this->input->post('middlename'),
				"lname" 						=> $this->input->post('lastname'),
				"email" 						=> $this->input->post('email'),
				"dept_id" 						=> $dept_id,
            	"department_description" 		=> $department_description,
				"position" 						=> $this->input->post('position'),
				"username" 						=> $this->input->post('username'),
				"password" 						=> $hashed_password,
				"api_password" 					=> $hashed_password,
				"role" 							=> 'L1',
				'status' 						=> 1,
				'failed_attempts' 				=> 1,
				'created_at' 					=> date("Y-m-d H:i:s")
			);

			$this->db->insert('users', $data);
	
			if ($this->db->affected_rows() > 0) {
				$response = array(
					'status' => 'success',
					'message' => 'Registration successful!'
				);
			} else {
				$response = array(
					'status' => 'error',
					'message' => 'Registration failed. Please try again.'
				);
			}
	
			echo json_encode($response);
			exit; 
		}
	}
	
	public function login() {
	    $username = trim($this->input->post('username', true));
	    $input_pw = $this->input->post('password');
	    $new_pw = substr(sha1($input_pw), 0, 200);

	    $sql = "SELECT * FROM users WHERE BINARY username = ?";
		$res = $this->db->query($sql, array($username));
	    if ($res->num_rows() > 0) {
	        $t = $res->row_array();
	        $user_id = $t['recid'];
			$emp_id = $t['emp_id'];
	        $session_id = $t['api_password'];
	        $stored_pw = $t['password'];
	        $role = $t['role'];
	        $dept_id = $t['dept_id'];
			$sup_id = $t['sup_id'];
	        $sid = $this->session->session_id;

			// Check if password matches
	        if (password_verify($input_pw, $stored_pw)) {

				// If passwords match, the whole row would be retrieved.
				$query = $this->db->where('username', $username)->get('users')->row();

				// Check 'status' field. If 'status' field equals 0, the account is locked due to brute force password attempt (up to 3 attempts limit).
				if($query->status == 0) {
					return array(0, 'message' => 'You Account is Locked. Contact ICT Department for assistance.');
				} else {
					// If the 'status' field is 1, the 'failed_attempts' would be reset to 1.
					$this->db->set('failed_attempts', 1);
					$this->db->where('username', $username);
					$this->db->update('users');

					// Return the user details
					return array(1, array('emp_id' => $emp_id, 'user_id' => $user_id, 'role' => $role, 'status' => 1, 'dept_id' => $dept_id, 'sup_id' => $sup_id));
				}
	        } else {
	            $query = $this->db->where('username', $username)->get('users');

	            if ($query->num_rows() > 0) {
	                $row = $query->row();

					// Check if the user has 3 failed attempts
	                if ($row->failed_attempts == 3) {

						// If user has 3 failed attempts, set the 'status' field to 0 to lock the account.
	                    $this->db->set('status', 0);
	                    $this->db->where('username', $username);
	                    $this->db->update('users');

	                    return array(0, 'message' => "Your Account is Locked.");
	                } else {

						// If user has not yet reached 3 failed attempts, increment 'failed_attempts' field only
	                	$attempts = $this->db->where('username', $username)->get('users')->row()->failed_attempts;
	                    $this->db->where('username', $username);
				        $this->db->set('failed_attempts', ($attempts + 1), FALSE);
				        $this->db->update('users');

						// Return "Wrong username/password" message
				        return array('status' => 0, 'message' => "Your Username/Password is Invalid Please Try Again.");
	                }
	            } else {
	                return array('status' => 0, 'message' => "User not found");
	            }
	        }
	    } else {
	        log_message("error", "[Login] - Callback passed but can't retrieve data.");
	        return array('status' => 0, 'message' => "Failed to retrieve your data. Please try again or reset your account.");
	    }
	}
	
	//fetch details of the currently logged-in user from 'user' table
	public function user_details() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$this->db->where('recid', $user_id);
		$query = $this->db->get('users');
		
		if ($query->num_rows() > 0) {
			$user_data = $query->row_array();
			
			$this->session->set_userdata('user_role', $user_data['role']);
			return array("ok", $user_data);
		} else {
			return array("error", "No data was fetched.");
		}
	}	

	public function users_details_put($id) {
		$this->db->where('recid', $id);
		$query = $this->db->get('users');
		
		if ($query->num_rows() > 0) {
			return array("ok", $query->row_array());
		} else {
			return array("error", "No data was fetched.");
		}
	}

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
		$concern = $this->input->post('concern', true);
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

	public function getLastMSRFNumber() {
		$this->db->select('ticket_id');
		$this->db->order_by('recid', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('service_request_msrf');
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->ticket_id;
		} else {
			return 'MSRF-000';
		}
	}

	public function getLastTRFNumber() {
		$this->db->select('ticket_id');
		$this->db->order_by('recid', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('service_request_tracc_request');
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->ticket_id;
		} else {
			return 'TRN-0000';
		}
	}

	public function getDepartmentUsers() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		if ($query = $this->db->query("SELECT * FROM department WHERE recid = ". $user_id ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t);
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function UsersDepartment($id) {
		if ($query = $this->db->query("SELECT * FROM departments WHERE recid = ". $id ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t);
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function department() {
        $this->db->select('recid, dept_desc');
        $this->db->from('department');
        $query = $this->db->get();
        return $query->result_array();
	}

	public function updated_lock() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$id = $this->input->post('id', true);

		$this->db->set('status', 0);
		$this->db->where('recid', $id);
		$this->db->update('users');

		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			return array(1, "Successfully Details Keywords's: ". $id);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error updating Keywords's status. Please try again.");
		}
	}

	public function updated_unlock() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$id = $this->input->post('id', true);

		$this->db->set(['status' => 1, 'failed_attempts' => 1]);
		$this->db->where('recid', $id);
		$this->db->update('users');

		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			return array(1, "Successfully Details Keywords's: ". $id);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error updating Keywords's status. Please try again.");
		}
	}

	public function getSupervisorByDepartment($dept_id) {
		$this->db->select('sup_id');
		$this->db->from('departments');
		$this->db->where('recid', $dept_id);
		$query = $this->db->get();
	
		if ($query->num_rows() > 0) {
			return $query->row()->sup_id; 
		} else {
			return null;
		}
	}
	
	public function get_departments(){
		$qry = $this->db->get('departments');
		return $qry->result_array();
	}

	public function getDepartment() {
		$query = $this->db->query("SELECT * FROM departments");
	
		if ($query) {
			if ($query->num_rows() > 0) { 
				$departments = $query->result_array();
				return array("ok", $departments);
			} else { 
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	/* public function add_department(){		
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
	} */

	public function get_department_details($id) {
		$id = (int) $id;
	
		$this->db->where('recid', $id);
		$query = $this->db->get('departments');
	
		if ($query->num_rows() > 0) {
			return array("ok", $query->row_array());
		} else {
			return array("error", "No data found.");
		}
	}

	/* public function update_department($data, $id) {
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
	} */
	
	/* public function delete_employee($id) {
		$this->db->where('recid', $id);
		$this->db->delete('users');

		if($this->db->affected_rows() >= 0){
			return array(1, "Employee deleted successfully.");
		}else{
			return array(0, "No changes were made to the Employee.");
		}
	} */

	public function update_department_status($data_module, $id, $data_remarks, $data_status) {
	    $id = (int) $id;  
	    
	    if($data_status === "Rejected"){
	    	$this->db->set('approval_status', 'Rejected');
	    	$this->db->set('status', 'Rejected');
	    }else if($data_status === "Approved"){
	    	$this->db->set('approval_status', 'Approved');
	    	$this->db->set('status', 'Approved');
	    }else{
	    	$this->db->set('approval_status', 'Returned');
	    	$this->db->set('status', 'Returned');
	    }
	    $this->db->where('recid', $id); 
	    if(strtolower($data_module) === "tracc-concern"){
	    	$update_success = $this->db->update('service_request_tracc_concern');
	    }else if(strtolower($data_module) === "tracc-request"){
	    	$update_success = $this->db->update('service_request_tracc_request');
	    }else{
	    	$update_success = $this->db->update('service_request_msrf');
	    }

	    if ($update_success && $this->db->affected_rows() > 0) {
	        return array(1, "Department approval status updated successfully.");
	    } else {
	        return array(0, "Department approval status not found or update failed.");
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
	
	/* public function add_employee() {
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
	
		list($status, $department_details) = $this->get_department_details($department);

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
	} */
	
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

		$department_details = $this->get_department_details($department);  
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
	

	public function getSupervisorId($recid) {
		$query = $this->db->select('sup_id')
						->where('recid', $recid)
						->get('users');
		if ($query->num_rows() == 1) {
			$result = $query->row_array();
			return $result['sup_id'];
		} else {
			return false;
		}
	}

	public function getTraccConcernByID($id) {
		$strQry = "'$id'";
		if ($query = $this->db->query("SELECT * FROM service_request_tracc_concern WHERE control_number = " . $strQry ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t); 
			} else {
				return array("error", "No data was fetched.");
			}
		}else{
			return array("error", "Internal error. Please try again.");
		}
	}
	
	public function getTicketsMSRF($id) {
		$strQry = "'$id'";
		if ($query = $this->db->query("SELECT * FROM service_request_msrf WHERE ticket_id = ". $strQry ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t);
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function getTicketsTRF($id){
		$strQry = "'$id'";
		if ($query = $this->db->query("SELECT * FROM service_request_tracc_request WHERE ticket_id = ". $strQry ."")){
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t);
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function status_approval_tracc_concern() {
		$control_number = $this->input->post('control_number', true);
		$received_by = $this->input->post('received_by', true);
		$noted_by = $this->input->post('noted_by', true);
		$priority = $this->input->post('priority', true);
		$approval_stat = $this->input->post('app_stat', true);
		$it_approval_stat = $this->input->post('it_app_stat', true);
		$reject_ticket_traccCon = $this->input->post('reason_rejected', true);
		$solution = $this->input->post('tcr_solution', true);
		$resolved_by = $this->input->post('resolved_by', true);
		$resolved_date = $this->input->post('res_date', true);
		$others = $this->input->post('others', true);
		$received_by_lst = $this->input->post('received_by_lst', true);
		$date_lst = $this->input->post('date_lst', true);
	
		$this->db->trans_start();
	
		$qry = $this->db->query('SELECT * FROM service_request_tracc_concern WHERE control_number = ?', array($control_number));
	
		if ($qry->num_rows() > 0) {
			$row = $qry->row();
			
			if ($approval_stat == 'Rejected') {
				$this->db->set('approval_status', 'Rejected');
				$this->db->set('status', 'Rejected');  
			} else if ($approval_stat == 'Approved') {
				$this->db->set('approval_status', 'Approved');
				$this->db->set('status', 'In Progress'); 
			} else if ($approval_stat == 'Pending') {
				$this->db->set('approval_status', 'Pending');
				$this->db->set('status', 'Pending'); 
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

			if ($priority == 'Low') {
				$this->db->set('priority', 'Low');
			} else if ($priority == 'Medium') {
				$this->db->set('priority', 'Medium');
			} else if ($priority == 'High') {
				$this->db->set('priority', 'High');
			}
	
			$this->db->set('received_by', $received_by);
			$this->db->set('noted_by', $noted_by);
			$this->db->set('reason_reject_tickets', $reject_ticket_traccCon);
			$this->db->set('tcr_solution', $solution);
			$this->db->set('resolved_by', $resolved_by);
			$this->db->set('resolved_date', $resolved_date);
			$this->db->set('others', $others);
			$this->db->set('received_by_lst', $received_by_lst);
			$this->db->set('date_lst', $date_lst);

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

	public function get_checkbox_values($control_number){
		$this->db->where('control_number', $control_number);
		$query = $this->db->get('filled_by_mis');

		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return [
				'for_mis_concern' => 0,
				'for_lst_concern' => 0,
				'system_error' => 0,
				'user_error' => 0
			];
		}
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
	
	public function status_approval_msrf() {
		$ticket_id = $this->input->post('msrf_number', true);
		$it_approval_stat = $this->input->post('it_approval_stat', true);
		$assign_staff = $this->input->post('assign_to', true);
		$approval_stat = $this->input->post('approval_stat', true);
		$reject_reason = $this->input->post('rejecttix', true);
	
		$this->db->trans_start();

		$qry = $this->db->query('SELECT * FROM service_request_msrf WHERE ticket_id = ?', array($ticket_id));
	
		if ($qry->num_rows() > 0) {
			$row = $qry->row();
	
			if ($approval_stat == 'Rejected') {
				$this->db->set('approval_status', 'Rejected');
				$this->db->set('status', 'Rejected'); 
			} else if ($approval_stat == 'Approved') {
				$this->db->set('approval_status', 'Approved');
				$this->db->set('status', 'In Progress'); 
			} else if ($approval_stat == 'Returned') {
				$this->db->set('approval_status', 'Returned');
				$this->db->set('status', 'Returned');
			}
	
			
			if ($it_approval_stat == 'Resolved') {
				$this->db->set('it_approval_status', 'Resolved');
				$this->db->set('status', 'Closed'); 
			} else if ($it_approval_stat == 'Rejected') {
				$this->db->set('it_approval_status', 'Rejected');
				$this->db->set('remarks_ict', $reject_reason); 
				$this->db->set('status', 'Rejected'); 
			} else if ($it_approval_stat == 'Approved') {
				$this->db->set('it_approval_status', 'Approved');
				$this->db->set('status', 'In Progress'); 
			}
	
			if (!empty($assign_staff)) {
				$this->db->set('assigned_it_staff', $assign_staff);
			}
	
			$this->db->where('ticket_id', $ticket_id);
			$this->db->update('service_request_msrf');
	
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
	

	public function GetICTSupervisor() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		if ($query = $this->db->query("SELECT a.emp_id FROM users a JOIN departments b ON a.emp_id = b.sup_id WHERE a.recid = ". $user_id ."")) {
			if ($query->num_rows() > 0) {
				$t = $query->row_array();
				return array("ok", $t);
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function GetTeam($dept_id) {
		if ($query = $this->db->query("SELECT * FROM users WHERE dept_id = ". $dept_id ." AND role = 'L1'")) {
			if ($query->num_rows() > 0) {
				return array("ok", $query->result_array());
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}
	
	public function GetDepartmentID() {
		$user_id = $this->session->userdata('login_data')['user_id'];
		if ($query = $this->db->query("SELECT * FROM users WHERE recid = ".$user_id."")) {
			if ($query->num_rows() > 0) {
				return array("ok", $query->result_array());
			} else {
				return array("error", "No data was fetched.");
			}
		} else {
			return array("error", "Internal error. Please try again.");
		}
	}

	public function AcknolwedgeAsResolved($control_number){
		$user_id = $this->session->userdata('login_data')['user_id'];
		$ack_resolved = $this->input->post('ack_as_res_by', true);
		$ack_resolved_date = $this->input->post('ack_as_res_date', true);

		$data = array(
			'ack_as_resolved' => $ack_resolved,
			'ack_as_resolved_date' => $ack_resolved_date,
			'status' => 'Resolved'
		);

		$this->db->where('control_number', $control_number);
		$this->db->update('service_request_tracc_concern', $data);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('success', 'Ticket acknolwedge as resolved.');
		} else {
			$this->session->set_flashdata('error', 'Error acknowledging ticket as resolved.');
		}

		redirect(base_url(). "sys/users/list/tickets/tracc_concern");

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

	public function UpdateTraccReq($trf_number, $date_need, $complete_details, $selected_companies){
		$user_id = $this->session->userdata('login_data')['user_id'];

		$qry = $this->db->query('SELECT * FROM service_request_tracc_request WHERE ticket_id = ?', [$trf_number]);

		if ($qry->num_rows() > 0){
			$row = $qry->row();

			$this->db->set('date_needed', $date_need);
			$this->db->set('complete_details', $complete_details);

			if (!empty($selected_companies)) {
				$this->db->set('company', implode(',', $selected_companies));
			} else {
				$this->db->set('company', null); // Clear the value if no checkbox is selected
			}

			$this->db->where('ticket_id', $trf_number);
			$this->db->update('service_request_tracc_request');

			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Updating Tickets: " . $trf_number);
			} else {
				$this->db->trans_rollback();
				return array(0, "Successfully Updating Tickets: " . $trf_number);
			}
		} else {
			return array(0, "Service request not found for ticket: " . $trf_number);
		}
	}

	public function UpdateTRNewAdd($trf_number, $new_add_data){
		$user_id = $this->session->userdata('login_data')['user_id'];
		
		$qry = $this->db->get_where('tracc_req_mf_new_add', ['ticket_id' => $trf_number]);

		if ($qry->num_rows() > 0) {
			$this->db->where('ticket_id', $trf_number);
			$this->db->update('tracc_req_mf_new_add', $new_add_data);

			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Updating Tickets: " . $trf_number);
			} else {
				$this->db->trans_rollback();
				return array(0, "Successfully Updating Tickets: " . $trf_number);
			}
		} else {
			return [0, "No existing 'New/Add' data found for ticket: " . $trf_number];
		}
	}

	public function UpdateTRUpdate($trf_number, $data_update){
		$user_id = $this->session->userdata('login_data')['user_id'];

		$qry = $this->db->get_where('tracc_req_mf_update', ['ticket_id' => $trf_number]);

		if ($qry->num_rows() > 0) {
			$this->db->where('ticket_id', $trf_number);
			$this->db->update('tracc_req_mf_update', $data_update);

			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Updating Tickets: " . $trf_number);
			} else {
				$this->db->trans_rollback();
				return array(0, "Successfully Updating Tickets: " . $trf_number);
			}
		} else {
			return [0, "No existing 'New/Add' data found for ticket: " . $trf_number];
		}
	}

	public function UpdateTRAccount($trf_number, $data_account){
		$user_id = $this->session->userdata('login_data')['user_id'];

		$qry = $this->db->get_where('tracc_req_mf_account', ['ticket_id' => $trf_number]);

		if ($qry->num_rows() > 0) {
			$this->db->where('ticket_id', $trf_number);
			$this->db->update('tracc_req_mf_account', $data_account);

			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Updating Tickets: " . $trf_number);
			} else {
				$this->db->trans_rollback();
				return array(0, "Successfully Updating Tickets: " . $trf_number);
			}
		} else {
			return [0, "No existing 'New/Add' data found for ticket: " . $trf_number];
		}
	}
	
	public function UpdateMSRFAssign($ticket_id, $date_needed, $asset_code, $request_category, $specify, $details_concern) {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$status = $this->input->post('it_status', true);
		$status_users = $this->input->post('status_users', true);
		$status_requestor = $this->input->post('status_requestor', true);
		
		$qry = $this->db->query('SELECT * FROM service_request_msrf WHERE ticket_id = ?', [$ticket_id]);
	
		if ($qry->num_rows() > 0) {
			$row = $qry->row();
	
			// Determine which status to set based on the current status
			/*if ($row->status == 'In Progress') {
				$this->db->set('status', $status);
			} else if ($row->status == 'Resolved') {
				$this->db->set('status', $status_requestor);
			} else {
				$this->db->set('status', $status_users);
			}*/
			
			// Update the additional fields
			$this->db->set('date_needed', $date_needed);
			$this->db->set('asset_code', $asset_code);
			$this->db->set('category', $request_category);
			$this->db->set('specify', $specify);
			$this->db->set('details_concern', $details_concern);
	
			// Update only status and additional fields in the database
			$this->db->where('ticket_id', $ticket_id);
			$this->db->update('service_request_msrf');
		
			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Updating Tickets: " . $ticket_id);
			} else {
				$this->db->trans_rollback();
				return array(0, "Error updating Keywords's status. Please try again.");
			}
		} else {
			return array(0, "Service request not found for ticket: " . $ticket_id);
		}
		
	}
	

	//kinukuha yung name ng it assigned keysa id ang dinidisplay hindi tapos
	/*public function getTicketWithAssignedIT($ticket_id){
		$this->db->select('msrf.ticket_id, CONCAT(users.fname, " ", users.mname, " ", users.lname) as assigned_it_name');
		$this->db->from('service_request_msrf as msrf');
		$this->db->join('users', 'msrf.assigned_it_staff = users.recid', 'left');  // Join with the users table
		$this->db->where('msrf.ticket_id', $ticket_id);
		$query = $this->db->get();
	
		return $query->row_array();  // Return the result as an array
	}*/

	// public function get_total_users(){
	// 	return $this->db->count_all('users');
	// }

	// public function get_total_msrf_ticket(){
	// 	$this->db->from('service_request_msrf');
	// 	$this->db->where_in('status', ['In Progress', 'Open', 'Approved']);
	// 	return $this->db->count_all_results();
	// }

	// public function get_total_departments(){
	// 	return $this->db->count_all('departments');
	// }

	// public function get_total_tracc_concern_ticket(){
	// 	$this->db->from('service_request_tracc_concern');
	// 	$this->db->where_in('status', ['In Progress', 'Open', 'Approved']);
	// 	return $this->db->count_all_results();
	// }

	// public function get_total_tracc_request_ticket(){
	// 	$this->db->from('service_request_tracc_request');
	// 	$this->db->where_in('status', ['In Progress', 'Open', 'Approved']);
	// 	return $this->db->count_all_results();
	// }

	public function tracc_concern_add_ticket($file_path = null){
		$user_id = $this->session->userdata('login_data')['user_id'];
		$control_number = $this->input->post('control_number');
		$module_affected = $this->input->post('module_affected');
		$company = $this->input->post('company');
		$concern = $this->input->post('details_concern');
		$reported_by = $this->input->post('name');
		$date_rep = $this->input->post('date_rep');

		$this->db->where('control_number', $control_number);
		$existing_control_number = $this->db->get('service_request_tracc_concern')->row();
		
		if($existing_control_number) {
			return array(0, "Control number already exists. Please use a different control number.");
		}

		$data = array(
			'control_number' => $control_number,
			'subject' => 'TRACC_CONCERN',
			'module_affected' => $module_affected,
			'company' => $company,
			'tcr_details' => $concern,
			'reported_by' => $reported_by,
			'reported_date' => $date_rep,
			'status' => 'Open',
			'approval_status' => 'Pending',
			'it_approval_status' => 'Pending',
			'reported_by_id' => $user_id,
			'created_at' => date("Y-m-d H:i:s")
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

	public function trf_add_ticket($file_path = null, $comp_checkbox_values = null, $checkbox_data_newadd, $checkbox_data_update, $checkbox_data_account) {
		$user_id = $this->session->userdata('login_data')['user_id'];
		$trf_number = $this->input->post('trf_number', true);
		$fullname = $this->input->post('name', true);
		$department_description = $this->input->post('department_description', true);
		$department_id = $this->input->post('dept_id', true);
		$date_requested = $this->input->post('date_req', true);
		$date_needed = $this->input->post('date_needed', true);
		$complete_details = $this->input->post('complete_details', true);
		$acknowledge_by = $this->input->post('acknowledge_by', true);
		$acknowledge_by_date = $this->input->post('acknowledge_by_date', true);
	
		$query = $this->db->select('ticket_id')
					->where('ticket_id', $trf_number)
					->get('service_request_tracc_request');
		if($query->num_rows() > 0) {
			return array(0, "Data is Existing");
		} else {
			$data = array(
				'ticket_id' 				=> $trf_number,
				'subject' 					=> 'TRACC_REQUEST',
				'requested_by' 				=> $fullname,
				'department' 				=> $department_description,
				'department_id' 			=> $department_id,
				'date_requested'		 	=> $date_requested,
				'date_needed' 				=> $date_needed,
				'requested_by_id' 			=> $user_id,
				'complete_details' 			=> $complete_details,
				'acknowledge_by' 			=> $acknowledge_by,
				'acknowledge_by_date'		=> $acknowledge_by_date,
				'status' 					=> 'Open',
				'approval_status' 			=> 'Pending',
				'it_approval_status' 		=> 'Pending',
				'created_at' 				=> date("Y-m-d H:i:s")
			);
	
			if ($file_path !== null) {
				$data['file'] = $file_path;
			}
	
			if ($comp_checkbox_values !== null) {
				$data['company'] = $comp_checkbox_values;
			}
	
			$this->db->trans_start();
			$query = $this->db->insert('service_request_tracc_request', $data);
	
			if ($this->db->affected_rows() > 0) {
				$checkbox_entry_newadd = [
					'ticket_id' => $trf_number,
					'item' => isset($checkbox_data_newadd['checkbox_item']) ? $checkbox_data_newadd['checkbox_item'] : 0,
					'customer' => isset($checkbox_data_newadd['checkbox_customer']) ? $checkbox_data_newadd['checkbox_customer'] : 0,
					'supplier' =>  isset($checkbox_data_newadd['checkbox_supplier']) ? $checkbox_data_newadd['checkbox_supplier'] : 0,
					'warehouse' => isset($checkbox_data_newadd['checkbox_whs']) ? $checkbox_data_newadd['checkbox_whs'] : 0,
					'bin_number' => isset($checkbox_data_newadd['checkbox_bin']) ? $checkbox_data_newadd['checkbox_bin'] : 0,
					'customer_shipping_setup' => isset($checkbox_data_newadd['checkbox_cus_ship_setup']) ? $checkbox_data_newadd['checkbox_cus_ship_setup'] : 0,
					'employee_request_form' => isset($checkbox_data_newadd['checkbox_employee_req_form']) ? $checkbox_data_newadd['checkbox_employee_req_form'] : 0,
					'others' => isset($checkbox_data_newadd['checkbox_others_newadd']) ? $checkbox_data_newadd['checkbox_others_newadd'] : 0,
					'others_description_add' => isset($checkbox_data_newadd['others_text_newadd']) ? $checkbox_data_newadd['others_text_newadd'] : ""
				];
				$this->db->insert('tracc_req_mf_new_add', $checkbox_entry_newadd);

				$checkbox_entry_update = [
					'ticket_id' => $trf_number,
					'system_date_lock' => isset($checkbox_data_update['checkbox_system_date_lock']) ? $checkbox_data_update['checkbox_system_date_lock'] : 0,
					'user_file_access' => isset($checkbox_data_update['checkbox_user_file_access']) ? $checkbox_data_update['checkbox_user_file_access'] : 0,
					'item_details' => isset($checkbox_data_update['checkbox_item_dets']) ? $checkbox_data_update['checkbox_item_dets'] : 0,
					'customer_details' => isset($checkbox_data_update['checkbox_customer_dets']) ? $checkbox_data_update['checkbox_customer_dets'] : 0,
					'supplier_details' => isset($checkbox_data_update['checkbox_supplier_dets']) ? $checkbox_data_update['checkbox_supplier_dets'] : 0,
					'employee_details' => isset($checkbox_data_update['checkbox_employee_dets']) ? $checkbox_data_update['checkbox_employee_dets'] : 0,
					'others' => isset($checkbox_data_update['checkbox_others_update']) ? $checkbox_data_update['checkbox_others_update'] : 0,
					'others_description_update' => isset($checkbox_data_update['others_text_update']) ? $checkbox_data_update['others_text_update'] : ""
				];
				$this->db->insert('tracc_req_mf_update', $checkbox_entry_update);

				$checkbox_entry_account = [
					'ticket_id' => $trf_number,
					'tracc_orientation' => isset($checkbox_data_account['checkbox_tracc_orien']) ? $checkbox_data_account['checkbox_tracc_orien'] : 0,
					'lmi' => isset($checkbox_data_account['checkbox_create_lmi']) ? $checkbox_data_account['checkbox_create_lmi'] : 0,
					'rgdi' => isset($checkbox_data_account['checkbox_create_rgdi']) ? $checkbox_data_account['checkbox_create_rgdi'] : 0,
					'lpi' => isset($checkbox_data_account['checkbox_create_lpi']) ? $checkbox_data_account['checkbox_create_lpi'] : 0,
					'sv' => isset($checkbox_data_account['checkbox_create_sv']) ? $checkbox_data_account['checkbox_create_sv'] : 0,
					'gps_account' => isset($checkbox_data_account['checkbox_gps_account']) ? $checkbox_data_account['checkbox_gps_account'] : 0,
					'others' => isset($checkbox_data_account['checkbox_others_account']) ? $checkbox_data_account['checkbox_others_account'] : 0,
					'others_description_acc' => isset($checkbox_data_account['others_text_account']) ? $checkbox_data_account['others_text_account'] : ""
				];
				$this->db->insert('tracc_req_mf_account', $checkbox_entry_account);

				$this->db->trans_commit();
				return array(1, "Successfully Created Ticket: ".$trf_number);
			} else {
				$this->db->trans_rollback();
				return array(0, "There seems to be a problem when inserting new user. Please try again.");
			}
		}
	}

	public function getCheckboxDataNewAdd($ticket_id) {
		$this->db->where('ticket_id', $ticket_id);
		$query = $this->db->get('tracc_req_mf_new_add'); 
	
		if ($query->num_rows() > 0) {
			return $query->row_array(); 
		}
	
		return [];
	}

	public function getCheckboxDataUpdate($ticket_id) {
		$this->db->where('ticket_id', $ticket_id);
		$query = $this->db->get('tracc_req_mf_update');

		if ($query->num_rows() > 0){
			return $query->row_array();
		}

		return[];
	}

	public function getCheckboxDataAccount($ticket_id) {
		$this->db->where('ticket_id', $ticket_id);
		$query = $this->db->get('tracc_req_mf_account');

		if ($query->num_rows() > 0){
			return $query->row_array();
		}

		return[];
	}

	public function get_all_trf_tickets(){
		$this->db->select('ticket_id'); 
		$query = $this->db->get('service_request_tracc_request'); 
		return $query->result_array();
	}
	
	public function add_customer_request_form_pdf($crf_comp_checkbox_values = null, $checkbox_cus_req_form_del) {
		$trf_number = $this->input->post('trf_number', true);
		
		$data = array(
			'ticket_id' => $trf_number,
			'requested_by' => $this->input->post('requested_by', true),
			'date' => $this->input->post('date', true),
			'customer_code' => $this->input->post('customer_code', true),
			'customer_name' => $this->input->post('customer_name', true),
			'tin_no' => $this->input->post('tin_no', true),
			'terms' => $this->input->post('terms', true),
			'customer_address' => $this->input->post('customer_address', true),
			'contact_person' => $this->input->post('contact_person', true),
			'office_tel_no' => $this->input->post('office_tel_no', true),
			'pricelist' => $this->input->post('pricelist', true),
			'payment_group' => $this->input->post('payment_grp', true),
			'contact_no' => $this->input->post('contact_no', true),
			'territory' => $this->input->post('territory', true),
			'salesman' => $this->input->post('salesman', true),
			'business_style' => $this->input->post('business_style', true),
			'email' => $this->input->post('email', true),
			'shipping_code' => $this->input->post('shipping_code', true),
			'route_code' => $this->input->post('route_code', true),
			'customer_shipping_address' => $this->input->post('customer_shipping_address', true),
			'landmark' => $this->input->post('landmark', true),
			'window_time_start' => $this->input->post('window_time_start', true),
			'window_time_end' => $this->input->post('window_time_end', true),
			'special_instruction' => $this->input->post('special_instruction', true),
			'created_at' => date("Y-m-d H:i:s"),
		);

		// Add checkbox values if available
		if ($crf_comp_checkbox_values !== null) {
			$data['company'] = $crf_comp_checkbox_values;
		}

		// Start transaction
		$this->db->trans_start();
		$this->db->insert('tracc_req_customer_req_form', $data);
		
		if ($this->db->affected_rows() > 0) {
			$checkbox_cus_req_form_del_days = [
				'ticket_id' => $trf_number,
				'outright' => isset($checkbox_cus_req_form_del['checkbox_outright']) ? $checkbox_cus_req_form_del['checkbox_outright'] : 0,
				'consignment' => isset($checkbox_cus_req_form_del['checkbox_consignment']) ? $checkbox_cus_req_form_del['checkbox_consignment'] : 0,
				'customer_is_also_a_supplier' =>  isset($checkbox_cus_req_form_del['checkbox_cus_a_supplier']) ? $checkbox_cus_req_form_del['checkbox_cus_a_supplier'] : 0,
				'online' => isset($checkbox_cus_req_form_del['checkbox_online']) ? $checkbox_cus_req_form_del['checkbox_online'] : 0,
				'walk_in' => isset($checkbox_cus_req_form_del['checkbox_walkIn']) ? $checkbox_cus_req_form_del['checkbox_walkIn'] : 0,
				'monday' => isset($checkbox_cus_req_form_del['checkbox_monday']) ? $checkbox_cus_req_form_del['checkbox_monday'] : 0,
				'tuesday' => isset($checkbox_cus_req_form_del['checkbox_tuesday']) ? $checkbox_cus_req_form_del['checkbox_tuesday'] : 0,
				'wednesday' => isset($checkbox_cus_req_form_del['checkbox_wednesday']) ? $checkbox_cus_req_form_del['checkbox_wednesday'] : 0,
				'thursday' => isset($checkbox_cus_req_form_del['checkbox_thursday']) ? $checkbox_cus_req_form_del['checkbox_thursday'] : 0,
				'friday' => isset($checkbox_cus_req_form_del['checkbox_friday']) ? $checkbox_cus_req_form_del['checkbox_friday'] : 0,
				'saturday' => isset($checkbox_cus_req_form_del['checkbox_saturday']) ? $checkbox_cus_req_form_del['checkbox_saturday'] : 0,
				'sunday' => isset($checkbox_cus_req_form_del['checkbox_sunday']) ? $checkbox_cus_req_form_del['checkbox_sunday'] : 0,
				'created_at' => date("Y-m-d H:i:s"),
			];
			$this->db->insert('tracc_req_customer_req_form_del_days', $checkbox_cus_req_form_del_days);

			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Created Customer Request Form for: " . $data['ticket_id']);
			} else {
				$this->db->trans_rollback();
				return array(0, "Error: Could not insert delivery days data.");
			}
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not insert data. Please try again.");
		}

	}

	public function get_customer_from_tracc_req_mf_new_add() {
		$this->db->select('*');
		$this->db->from('tracc_req_mf_new_add');
		$this->db->where('customer', 1); 
		$query = $this->db->get();
		return $query->result_array();
	}


	public function add_customer_shipping_setup_pdf($css_comp_checkbox_values = null, $checkbox_cus_ship_setup) {
		$trf_number = $this->input->post('trf_number', true);
	
		$data = array(
			'ticket_id' => $trf_number,
			'requested_by' => $this->input->post('requested_by', true),
			'shipping_code' => $this->input->post('shipping_code', true),
			'route_code' => $this->input->post('route_code', true),
			'customer_address' => $this->input->post('customer_address', true),
			'landmark' => $this->input->post('landmark', true),
			'window_time_start' => $this->input->post('window_time_start', true),
			'window_time_end' => $this->input->post('window_time_end', true),
			'special_instruction' => $this->input->post('special_instruction', true),
			'created_at' => date("Y-m-d H:i:s"),
		);

		if ($css_comp_checkbox_values !== null) {
			$data['company'] = $css_comp_checkbox_values;
		}
	
		$data['monday'] = isset($checkbox_cus_ship_setup['checkbox_monday']) ? $checkbox_cus_ship_setup['checkbox_monday'] : 0;
		$data['tuesday'] = isset($checkbox_cus_ship_setup['checkbox_tuesday']) ? $checkbox_cus_ship_setup['checkbox_tuesday'] : 0;
		$data['wednesday'] = isset($checkbox_cus_ship_setup['checkbox_wednesday']) ? $checkbox_cus_ship_setup['checkbox_wednesday'] : 0;
		$data['thursday'] = isset($checkbox_cus_ship_setup['checkbox_thursday']) ? $checkbox_cus_ship_setup['checkbox_thursday'] : 0;
		$data['friday'] = isset($checkbox_cus_ship_setup['checkbox_friday']) ? $checkbox_cus_ship_setup['checkbox_friday'] : 0;
		$data['saturday'] = isset($checkbox_cus_ship_setup['checkbox_saturday']) ? $checkbox_cus_ship_setup['checkbox_saturday'] : 0;
		$data['sunday'] = isset($checkbox_cus_ship_setup['checkbox_sunday']) ? $checkbox_cus_ship_setup['checkbox_sunday'] : 0;
	
		$this->db->trans_begin();
	
		$this->db->insert('tracc_req_customer_ship_setup', $data);
	
		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			return array(1, "Successfully Created Customer Shipping Setup for: " . $data['ticket_id']);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not insert data. Please try again.");
		}
	}

	public function get_customer_shipping_setup_from_tracc_req_mf_new_add(){
		$this->db->select('ticket_id');
		$this->db->from('tracc_req_mf_new_add');
		$this->db->where('customer_shipping_setup', 1); 
		$query = $this->db->get();
		return $query->result_array();
	}

	public function add_employee_request_form_pdf() {
		$trf_number = $this->input->post('trf_number', true);

		$department_id = $this->input->post('department', true);

		// Fetch the department description from the departments table
		$this->db->select('dept_desc');
		$this->db->from('departments');  // Assuming your table name is 'departments'
		$this->db->where('recid', $department_id);
		$query = $this->db->get();

		$department_desc = '';
		if ($query->num_rows() > 0) {
			$department_desc = $query->row()->dept_desc;  // Get the department description
		}

		$data = array(
			'ticket_id' => $trf_number,
			'requested_by' => $this->input->post('requested_by', true),
			'name' => $this->input->post('employee_name', true),
			'department' => $department_id, 
        	'department_desc' => $department_desc,
			'position' => $this->input->post('position', true),
			'address' => $this->input->post('address', true),
			'tel_no_mob_no' => $this->input->post('tel_mobile_no', true),
			'tin_no' => $this->input->post('tin_no', true),
			'contact_person' => $this->input->post('contact_person', true),
			'created_at' => date("Y-m-d H:i:s"),
		);

		$this->db->trans_begin();

		$this->db->insert('tracc_req_employee_req_form', $data);

		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			return array(1, "Successfully Created Customer Shipping Setup for: " . $data['ticket_id']);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not insert data. Please try again.");
		}
	}

	public function get_employee_request_form_from_tracc_req_mf_new_add(){
		$this->db->select('ticket_id');
		$this->db->from('tracc_req_mf_new_add');
		$this->db->where('employee_request_form', 1); 
		$query = $this->db->get();
		return $query->result_array();
	}

	public function add_item_request_form_pdf($irf_comp_checkbox_value = null, $checkbox_item_req_form) {
		$trf_number = $this->input->post('trf_number', true);

		$data = array(
			'ticket_id' => $trf_number,
			'requested_by' => $this->input->post('requested_by', true),
			'date' => $this->input->post('date', true),
			'lmi_item_code' => $this->input->post('lmi_item_code', true),
			'long_description' => $this->input->post('long_description', true),
			'short_description' => $this->input->post('short_description', true),
			'item_classification' => $this->input->post('item_classification', true),
			'item_sub_classification' => $this->input->post('item_sub_classification', true),
			'department' => $this->input->post('department', true),
			'merch_category' => $this->input->post('merch_cat', true),
			'brand' => $this->input->post('brand', true),
			'supplier_code' => $this->input->post('supplier_code', true),
			'supplier_name' => $this->input->post('supplier_name', true),
			'class' => $this->input->post('class', true),
			'tag' => $this->input->post('tag', true),
			'source' => $this->input->post('source', true),
			'hs_code' => $this->input->post('hs_code', true),
			'unit_cost' => $this->input->post('unit_cost', true),
			'selling_price' => $this->input->post('selling_price', true),
			'major_item_group' => $this->input->post('major_item_group', true),
			'item_sub_group' => $this->input->post('item_sub_group', true),
			'account_type' => $this->input->post('account_type', true),
			'sales' => $this->input->post('sales', true),
			'sales_return' => $this->input->post('sales_return', true),
			'purchases' => $this->input->post('purchases', true),
			'purchase_return' => $this->input->post('purchase_return', true),
			'cgs' => $this->input->post('cgs', true),
			'inventory' => $this->input->post('inventory', true),
			'sales_disc' => $this->input->post('sales_disc', true),
			'gl_department' => $this->input->post('gl_dept', true),
			'capacity_per_pallet' => $this->input->post('capacity_per_pallet', true),
			'created_at' => date("Y-m-d H:i:s"),
		);

		if ($irf_comp_checkbox_value !== null) {
			$data['company'] = $irf_comp_checkbox_value;
		}

		$this->db->trans_begin();
		$this->db->insert('tracc_req_item_request_form', $data);

		if ($this->db->affected_rows() > 0) {
			$checkboxes_item_req_form = [
				'ticket_id' => $trf_number,
				'inventory' => isset($checkbox_item_req_form['checkbox_inventory']) ? $checkbox_item_req_form['checkbox_inventory'] : 0,
				'non_inventory' => isset($checkbox_item_req_form['checkbox_non_inventory']) ? $checkbox_item_req_form['checkbox_non_inventory'] : 0,
				'services' => isset($checkbox_item_req_form['checkbox_services']) ? $checkbox_item_req_form['checkbox_services'] : 0,
				'charges' => isset($checkbox_item_req_form['checkbox_charges']) ? $checkbox_item_req_form['checkbox_charges'] : 0,
				'watsons' => isset($checkbox_item_req_form['checkbox_watsons']) ? $checkbox_item_req_form['checkbox_watsons'] : 0,
				'other_accounts' => isset($checkbox_item_req_form['checkbox_other_accounts']) ? $checkbox_item_req_form['checkbox_other_accounts'] : 0,
				'online' => isset($checkbox_item_req_form['checkbox_online']) ? $checkbox_item_req_form['checkbox_online'] : 0,
				'all_accounts' => isset($checkbox_item_req_form['checkbox_all_accounts']) ? $checkbox_item_req_form['checkbox_all_accounts'] : 0,
				'trade' => isset($checkbox_item_req_form['checkbox_trade']) ? $checkbox_item_req_form['checkbox_trade'] : 0,
				'non_trade' => isset($checkbox_item_req_form['checkbox_non_trade']) ? $checkbox_item_req_form['checkbox_non_trade'] : 0,
				'yes' => isset($checkbox_item_req_form['checkbox_batch_required_yes']) ? $checkbox_item_req_form['checkbox_batch_required_yes'] : 0,
				'no' => isset($checkbox_item_req_form['checkbox_batch_required_no']) ? $checkbox_item_req_form['checkbox_batch_required_no'] : 0,
			];
			$this->db->insert('tracc_req_item_request_form_checkboxes', $checkboxes_item_req_form);

			$this->db->trans_commit();
			return array(1, "Successfully Created Item Request Form for: " . $data['ticket_id']);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not insert data. Please try again.");
		}
	}

	public function insert_batch_rows_gl_setup($insert_data_gl_setup) {
		if (!empty($insert_data_gl_setup)) {
			$this->db->insert_batch('tracc_req_item_req_form_gl_setup', $insert_data_gl_setup);
		}
	}

	public function insert_batch_rows_whs_setup($insert_data_wh_setup) {
		if (!empty($insert_data_wh_setup)) {
			$this->db->insert_batch('tracc_req_item_req_form_whs_setup', $insert_data_wh_setup);
		}
	}

	public function get_item_request_form_from_tracc_req_mf_new_add(){
		$this->db->select('ticket_id');
		$this->db->from('tracc_req_mf_new_add');
		$this->db->where('item', 1); 
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function add_supplier_request_form_pdf($trf_comp_checkbox_value = null, $checkbox_non_vat = 0, $checkbox_supplier_req_form) {
		$trf_number = $this->input->post('trf_number', true);

		$data = array(
			'ticket_id' => $trf_number,
			'requested_by'  => $this->input->post('requested_by', true),
			'date' => $this->input->post('date', true),
			'supplier_code' => $this->input->post('supplier_code', true),
			'supplier_account_group' => $this->input->post('supplier_account_group', true),
			'supplier_name' => $this->input->post('supplier_name', true),
			'country_origin' => $this->input->post('country_origin', true),
			'supplier_address' => $this->input->post('supplier_address', true),
			'office_tel' => $this->input->post('office_tel_no', true),
			'zip_code' => $this->input->post('zip_code', true),
			'contact_person' => $this->input->post('contact_person', true),
			'terms' => $this->input->post('terms', true),
			'tin_no' => $this->input->post('tin_no', true),
			'pricelist' => $this->input->post('pricelist', true),
			'ap_account' => $this->input->post('ap_account', true),
			'ewt' => $this->input->post('ewt', true),
			'advance_account' => $this->input->post('advance_acc', true),
			'vat' => $this->input->post('vat', true),
			'non_vat' => $checkbox_non_vat,
			'payee_1' => $this->input->post('payee1', true),
			'payee_2' => $this->input->post('payee2', true),
			'payee_3' => $this->input->post('payee3', true),
			'driver_name' => $this->input->post('driver_name', true),
			'driver_contact_no' => $this->input->post('driver_contact_no', true),
			'driver_fleet' => $this->input->post('driver_fleet', true),
			'driver_plate_no' => $this->input->post('driver_plate_no', true),
			'helper_name' => $this->input->post('helper_name', true),
			'helper_contact_no' => $this->input->post('helper_contact_no', true),
			'helper_rate_card' => $this->input->post('helper_rate_card', true),
			'created_at' => date("Y-m-d H:i:s"),
		);
	
		if ($trf_comp_checkbox_value !== null) {
			$data['company'] = $trf_comp_checkbox_value;
		}

		$this->db->trans_begin();
		$this->db->insert('tracc_req_supplier_req_form', $data);	
	
		if ($this->db->affected_rows() > 0) {
			$checkboxes_sup_req_form = [
				'ticket_id' => $trf_number,
				'supplier_group_local' => isset($checkbox_supplier_req_form['local_supplier_grp']) ? $checkbox_supplier_req_form['local_supplier_grp'] : 0,
				'supplier_group_foreign' => isset($checkbox_supplier_req_form['foreign_supplier_grp']) ? $checkbox_supplier_req_form['foreign_supplier_grp'] : 0,
				'supplier_trade' => isset($checkbox_supplier_req_form['supplier_trade']) ? $checkbox_supplier_req_form['supplier_trade'] : 0, 
				'supplier_non_trade' => isset($checkbox_supplier_req_form['supplier_non_trade']) ? $checkbox_supplier_req_form['supplier_non_trade'] : 0,
				'trade_type_goods' => isset($checkbox_supplier_req_form['trade_type_goods']) ? $checkbox_supplier_req_form['trade_type_goods'] : 0, 
				'trade_type_services' => isset($checkbox_supplier_req_form['trade_type_services']) ? $checkbox_supplier_req_form['trade_type_services'] : 0,
				'trade_type_goods_services' => isset($checkbox_supplier_req_form['trade_type_GoodsServices']) ? $checkbox_supplier_req_form['trade_type_GoodsServices'] : 0,
				'major_grp_local_trade_vendor' => isset($checkbox_supplier_req_form['major_grp_local_trade_ven']) ? $checkbox_supplier_req_form['major_grp_local_trade_ven'] : 0,
				'major_grp_local_non_trade_vendor' => isset($checkbox_supplier_req_form['major_grp_local_nontrade_ven']) ? $checkbox_supplier_req_form['major_grp_local_nontrade_ven'] : 0,
				'major_grp_foreign_trade_vendors' => isset($checkbox_supplier_req_form['major_grp_foreign_trade_ven']) ? $checkbox_supplier_req_form['major_grp_foreign_trade_ven'] : 0,
				'major_grp_foreign_non_trade_vendors' => isset($checkbox_supplier_req_form['major_grp_foreign_nontrade_ven']) ? $checkbox_supplier_req_form['major_grp_foreign_nontrade_ven'] : 0,
				'major_grp_local_broker_forwarder' => isset($checkbox_supplier_req_form['major_grp_local_broker_forwarder']) ? $checkbox_supplier_req_form['major_grp_local_broker_forwarder'] : 0,
				'major_grp_rental' => isset($checkbox_supplier_req_form['major_grp_rental']) ? $checkbox_supplier_req_form['major_grp_rental'] : 0,
				'major_grp_bank' => isset($checkbox_supplier_req_form['major_grp_bank']) ? $checkbox_supplier_req_form['major_grp_bank'] : 0,
				'major_grp_ot_supplier' => isset($checkbox_supplier_req_form['major_grp_one_time_supplier']) ? $checkbox_supplier_req_form['major_grp_one_time_supplier'] : 0,
				'major_grp_government_offices' => isset($checkbox_supplier_req_form['major_grp_government_offices']) ? $checkbox_supplier_req_form['major_grp_government_offices'] : 0,
				'major_grp_insurance' => isset($checkbox_supplier_req_form['major_grp_insurance']) ? $checkbox_supplier_req_form['major_grp_insurance'] : 0,
				'major_grp_employees' => isset($checkbox_supplier_req_form['major_grp_employees']) ? $checkbox_supplier_req_form['major_grp_employees'] : 0,
				'major_grp_sub_aff_intercompany' => isset($checkbox_supplier_req_form['major_grp_subs_affiliates']) ? $checkbox_supplier_req_form['major_grp_subs_affiliates'] : 0,
				'major_grp_utilities' => isset($checkbox_supplier_req_form['major_grp_utilities']) ? $checkbox_supplier_req_form['major_grp_utilities'] : 0,
			];
			$this->db->insert('tracc_req_supplier_req_form_checkboxes', $checkboxes_sup_req_form);

			$this->db->trans_commit();
			return array(1, "Successfully Created Item Request Form for: " . $data['ticket_id']);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not insert data. Please try again.");
		}
	}
	

	public function get_supplier_from_tracc_req_mf_new_add(){
		$this->db->select('ticket_id');
		$this->db->from('tracc_req_mf_new_add');
		$this->db->where('supplier', 1); 
		$query = $this->db->get();
		return $query->result_array();
	}
	

	public function save_data($table = NULL, $data = NULL)
	{
		$this->db->insert($table, $data);
		$insertId = $this->db->insert_id();
			if($insertId):
				return $insertId;
			else:
			    return "failed";
			endif;
	}

	// SRF
	public function get_ticket_counts_supplier_req() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_supplier_req_form');
		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid');
		$query = $this->db->get();
		return $query->result_array();
	}

	// SRF
	public function get_ticket_checkbox_supplier_req($recid) {
		$query = $this->db->get_where('tracc_req_supplier_req_form_checkboxes', ['recid' => $recid]);
		return $query->row_array(); 
	}

	// SRF
	public function update_srf_ticket_remarks($recid, $remarks){
		$this->db->set('remarks', $remarks); 
		$this->db->where('recid', $recid); 
		return $this->db->update('tracc_req_supplier_req_form');
	}

	// ERF
	public function get_ticket_counts_employee_req() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_employee_req_form');
		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid');
		$query = $this->db->get();
		return $query->result_array();
	}

	// ERF 
	public function update_erf_ticket_remarks($recid, $remarks){
		$this->db->set('remarks', $remarks); 
		$this->db->where('recid', $recid); 
		return $this->db->update('tracc_req_employee_req_form');
	}

	// CRF
	public function get_ticket_counts_customer_req() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_customer_req_form');
		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid'); 
		$query = $this->db->get();
		return $query->result_array();
	}

	// CRF
	public function get_ticket_checkbox_customer_req($recid){
		$query = $this->db->get_where('tracc_req_customer_req_form_del_days', ['recid' => $recid]);
		return $query->row_array();
	}

	// CRF
	public function update_crf_ticket_remarks($recid, $remarks){
		$this->db->set('remarks', $remarks); 
		$this->db->where('recid', $recid); 
		return $this->db->update('tracc_req_customer_req_form');
	}

	// CSS
	public function get_ticket_counts_customer_ship_setup() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_customer_ship_setup');
		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid');
		$query = $this->db->get();
		return $query->result_array();
	}

	// CSS
	public function update_css_ticket_remarks($recid, $remarks){
		$this->db->set('remarks', $remarks); 
		$this->db->where('recid', $recid); 
		return $this->db->update('tracc_req_customer_ship_setup');
	}

	// IRF 
	public function get_ticket_counts_item_req_form() {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_item_request_form');
		$this->db->where('remarks !=', 'Done');
		$this->db->group_by('recid');
		$query = $this->db->get();
		return $query->result_array();
	}

	// IRF 
	public function update_irf_ticket_remarks($recid, $remarks){
		$this->db->set('remarks', $remarks);
		$this->db->where('recid', $recid);
		return $this->db->update('tracc_req_item_request_form');
	}

	// IRF 
	public function get_ticket_checkbox1_item_req_form($recid) {
		$query = $this->db->get_where('tracc_req_item_request_form_checkboxes', ['recid' => $recid]);
		return $query->row_array(); 
	}

	// IRF 
	public function get_ticket_checkbox2_item_req_form($ticket_id) {
		$this->db->select('*');
		$this->db->where('ticket_id', $ticket_id); 
		$query = $this->db->get('tracc_req_item_req_form_gl_setup');
		return $query->result_array(); 
	}

	// IRF 
	public function get_ticket_checkbox3_item_req_form($ticket_id) {
		$this->db->select('*');
		$this->db->where('ticket_id', $ticket_id);
		$query = $this->db->get('tracc_req_item_req_form_whs_setup');
		return $query->result_array();
	}
	
	public function get_msrf($id) {
		$data = [];
		$this->db->from('service_request_msrf');
		$this->db->where('requester_id', $id);
		$count = $this->db->count_all_results();
		$data['count'] = $count;
		$statuses = ['Open', 'Approved', 'In Progress', 'On Going', 'Resolved', 'Closed', 'Rejected', 'Returned'];

		foreach($statuses as $status) {
			$this->db->from('service_request_msrf');
			$this->db->where('status', $status);
			$this->db->where('requester_id', $id);
			$count = $this->db->count_all_results();
			$data[$status] = $count;
		}

		return $data;
	}

	public function get_tracc_concerns($id) {
		$data = [];
		$this->db->from('service_request_tracc_concern');
		$this->db->where('reported_by_id', $id);
		$count = $this->db->count_all_results();
		$data['count'] = $count;
		$statuses = ['Open', 'Approved', 'Done', 'In Progress', 'Resolved', 'Closed', 'Rejected', 'Returned'];

		foreach($statuses as $status) {
			$this->db->from('service_request_tracc_concern');
			$this->db->where('status', $status);
			$this->db->where('reported_by_id', $id);
			$count = $this->db->count_all_results();
			$data[$status] = $count;
		}

		return $data;
	}

	public function get_tracc_requests($id) {
		$data = [];
		$this->db->from('service_request_tracc_request');
		$this->db->where('requested_by_id', $id);
		$count = $this->db->count_all_results();
		$data['count'] = $count;
		$statuses = ['Open', 'Approved', 'In Progress', 'Rejected', 'Resolved', 'Closed', 'Returned'];

		foreach($statuses as $status) {
			$this->db->from('service_request_tracc_request');
			$this->db->where('status', $status);
			$this->db->where('requested_by_id', $id);
			$count = $this->db->count_all_results();
			$data[$status] = $count;
		}

		return $data;
	}

	public function get_customer_req_form_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_customer_req_form');
		return $query->result_array();
	}

	public function approve_crf($approved_by, $recid){
		$data = [
			'approved_by' 		=> $approved_by,
			'approved_date' 	=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_customer_req_form', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	public function approve_css($approved_by, $recid){
		$data = [
			'approved_by' 		=> $approved_by,
			'approved_date' 	=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_customer_ship_setup', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	public function approve_erf($approved_by, $recid){
		$data = [
			'approved_by'		=> $approved_by,
			'approved_date'		=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_employee_req_form', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	public function approve_irf($approved_by, $recid){
		$data = [
			'approved_by'		=> $approved_by,
			'approved_date'		=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_item_request_form', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	public function approve_srf($approved_by, $recid){
		$data = [
			'approved_by'		=> $approved_by,
			'approved_date'		=> date('Y-m-d H:i:s')
		];

		$this->db->where('recid', $recid);
		if ($this->db->update('tracc_req_supplier_req_form', $data)) {
			return [1]; 
		} else {
			return [0];
		}
	}

	// Edit function (with Bug)
	public function edit_customer_request_form_pdf($trf_comp_checkbox_values = null, $checkbox_cus_req_form_del, $id) {

		$data = array(
			'ticket_id' => $this->input->post('trf_number', true),
			'requested_by' => $this->input->post('requested_by', true),
			'date' => $this->input->post('date', true),
			'customer_code' => $this->input->post('customer_code', true),
			'customer_name' => $this->input->post('customer_name', true),
			'tin_no' => $this->input->post('tin_no', true),
			'terms' => $this->input->post('terms', true),
			'customer_address' => $this->input->post('customer_address', true),
			'contact_person' => $this->input->post('contact_person', true),
			'office_tel_no' => $this->input->post('office_tel_no', true),
			'pricelist' => $this->input->post('pricelist', true),
			'payment_group' => $this->input->post('payment_grp', true),
			'contact_no' => $this->input->post('contact_no', true),
			'territory' => $this->input->post('territory', true),
			'salesman' => $this->input->post('salesman', true),
			'business_style' => $this->input->post('business_style', true),
			'email' => $this->input->post('email', true),
			'shipping_code' => $this->input->post('shipping_code', true),
			'route_code' => $this->input->post('route_code', true),
			'customer_shipping_address' => $this->input->post('customer_shipping_address', true),
			'landmark' => $this->input->post('landmark', true),
			'window_time_start' => $this->input->post('window_time_start', true),
			'window_time_end' => $this->input->post('window_time_end', true),
			'special_instruction' => $this->input->post('special_instruction', true),
			'created_at' => date("Y-m-d H:i:s"),
		);

		if($trf_comp_checkbox_values !== null) {
			$data['company'] = $trf_comp_checkbox_values;
		}

		$this->db->trans_start();
		$this->db->where('recid', $id);
		$this->db->update('tracc_req_customer_req_form', $data);

		if ($this->db->affected_rows() > 0) {
			$checkbox_cus_req_form_del_days = [
				'ticket_id' => $this->input->post('trf_number', true),
				'outright' => isset($checkbox_cus_req_form_del['checkbox_outright']) ? $checkbox_cus_req_form_del['checkbox_outright'] : 0,
				'consignment' => isset($checkbox_cus_req_form_del['checkbox_consignment']) ? $checkbox_cus_req_form_del['checkbox_consignment'] : 0,
				'customer_is_also_a_supplier' =>  isset($checkbox_cus_req_form_del['checkbox_cus_a_supplier']) ? $checkbox_cus_req_form_del['checkbox_cus_a_supplier'] : 0,
				'online' => isset($checkbox_cus_req_form_del['checkbox_online']) ? $checkbox_cus_req_form_del['checkbox_online'] : 0,
				'walk_in' => isset($checkbox_cus_req_form_del['checkbox_walkIn']) ? $checkbox_cus_req_form_del['checkbox_walkIn'] : 0,
				'monday' => isset($checkbox_cus_req_form_del['checkbox_monday']) ? $checkbox_cus_req_form_del['checkbox_monday'] : 0,
				'tuesday' => isset($checkbox_cus_req_form_del['checkbox_tuesday']) ? $checkbox_cus_req_form_del['checkbox_tuesday'] : 0,
				'wednesday' => isset($checkbox_cus_req_form_del['checkbox_wednesday']) ? $checkbox_cus_req_form_del['checkbox_wednesday'] : 0,
				'thursday' => isset($checkbox_cus_req_form_del['checkbox_thursday']) ? $checkbox_cus_req_form_del['checkbox_thursday'] : 0,
				'friday' => isset($checkbox_cus_req_form_del['checkbox_friday']) ? $checkbox_cus_req_form_del['checkbox_friday'] : 0,
				'saturday' => isset($checkbox_cus_req_form_del['checkbox_saturday']) ? $checkbox_cus_req_form_del['checkbox_saturday'] : 0,
				'sunday' => isset($checkbox_cus_req_form_del['checkbox_sunday']) ? $checkbox_cus_req_form_del['checkbox_sunday'] : 0,
				'created_at' => date("Y-m-d H:i:s"),
			];
			$this->db->update('tracc_req_customer_req_form_del_days', $checkbox_cus_req_form_del_days);

			if ($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Edited Customer Request Form for: " . $data['ticket_id']);
			} else {
				$this->db->trans_rollback();
				return array(0, "Error: Could not edit delivery days data.");
			}
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not edit data. Please try again.");
		}
	}
}
?>