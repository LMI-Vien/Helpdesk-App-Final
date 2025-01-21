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

			$employee_id = $this->input->post('employee_id', TRUE);
			$username = $this->input->post('username', TRUE);

			$this->db->where('emp_id', $employee_id);
			$this->db->or_where('username', $username);
			$existing_user = $this->db->get('users')->row();

			// if ($existing_user) {
			// 	$response = array (
			// 		'status' => 'error',
			// 		'message' => 'Employee ID is already taken!'
			// 	);
			// 	echo json_encode($response);
			// 	exit;
			// }

			if ($existing_user) {
				$message = '';
				if ($existing_user->emp_id === $employee_id) {
					$message .= 'Employee ID is already taken! ';
				}
				if ($existing_user->username === $username) {
					$message .= 'Username is already taken!';
				}
			
				// Send response
				$response = array(
					'status' => 'error',
					'message' => trim($message)
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
				'active'						=> 1,
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

	public function getLastTRCNumber() {
		$this->db->select('control_number');
		$this->db->order_by('recid', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('service_request_tracc_concern');
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->control_number;
		} else {
			return 'TRC-0000';
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

	public function get_department_code($id) {
		$this->db->where('recid', $id);
		$query = $this->db->get('departments');

		return $query->row()->dept_code;
	}


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

	public function Acknowledge_as_resolved($trf_number){
		$user_id = $this->session->userdata('login_data')['user_id'];
		$acknowledge_by = $this->input->post('acknowledge_by', true);
		$acknowledge_by_date = $this->input->post('acknowledge_by_date', true);

		$data = array(
			'acknowledge_by' => $acknowledge_by,
			'acknowledge_by_date' => $acknowledge_by_date,
			'status' => 'Closed'
		);

		$this->db->where('ticket_id', $trf_number);
		$this->db->update('service_request_tracc_request', $data);

		// $affected_rows = $this->db->affected_rows();
		// // print_r($affected_rows);
		// // die();

		// if ($this->db->affected_rows() >= 0) {
		// 	$this->session->set_flashdata('success', 'Ticket acknolwedge as resolved.');
		// } else {
		// 	$this->session->set_flashdata('error', 'Error acknowledging ticket as resolved.');
		// }

		return $this->db->affected_rows() > 0;
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

	public function get_customer_req_form_rf_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_customer_req_form');

		$formattedData = array();
		$data = $query->result_array();
		foreach($data as $row) {
			// if ($row['remarks'] == 'Done') {
			// 	$row['remarks'] == "<p style='background-color: black;'>" . $row['remarks']; . "</p>";
			// }
		}

		return $query->result_array();
	}

	public function get_customer_req_form_ss_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_customer_ship_setup');
		return $query->result_array();
	}


	public function get_customer_req_form_ir_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_item_request_form');
		return $query->result_array();
	}

	public function get_customer_req_form_er_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_employee_req_form');
		return $query->result_array();
	}

	public function get_customer_req_form_sr_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_supplier_req_form');
		return $query->result_array();
	}

	public function get_unopened_msrf_tickets() {
		$user = $this->Main_model->user_details();

		if($user[1]['role'] != "L3") {
			$this->db->where('dept_id', $user[1]['dept_id']);
		}
		
		$this->db->where('status !=', 'Closed');
		$data = $this->db->get('service_request_msrf')->result_array();

		$table = array();
		$count = 0;
		foreach($data as $row) {
			$opened = explode(',', $row['opened']);

			if(in_array($user[1]['recid'], $opened)) {
				$count += 1;
			}
		}

		$this->db->select('COUNT(*) AS count');
		$this->db->where('status !=', 'Closed');
		if($user[1]['role'] != "L3") {
			$this->db->where('dept_id', $user[1]['dept_id']);
		}
		$all_count = $this->db->get('service_request_msrf')->row()->count;

		return $all_count - $count;
	}

	public function open_msrf_ticket($recid) {
		$user_id = $this->Main_model->user_details();
		$this->db->where('ticket_id', $recid);
		$table = $this->db->get('service_request_msrf')->row();

		if($table != null) {
			$data = explode(',', $table->opened);

			if(!in_array($user_id[1]['recid'], $data)) {
				$data[] = $user_id[1]['recid'];
				$opened = implode(',', $data);

				$this->db->where('ticket_id', $recid);
				$this->db->set('opened', $opened);
				$this->db->update('service_request_msrf');
			}
		} else {
			$opened = $user_id;

			$this->db->where('ticket_id', $recid);
			$this->db->set('opened', $opened);
			$this->db->update('service_request_msrf');
		}
	}

	public function get_unopened_tracc_concerns() {
		$user = $this->Main_model->user_details();

		if($user[1]['role'] != "L3") {
			$this->db->where('dept_id', $user[1]['dept_id']);
		}
		
		$this->db->where('status !=', 'Closed');
		$data = $this->db->get('service_request_tracc_concern')->result_array();

		$table = array();
		$count = 0;
		foreach($data as $row) {
			$opened = explode(',', $row['opened']);

			if(in_array($user[1]['recid'], $opened)) {
				$count += 1;
			}
		}

		$this->db->select('COUNT(*) AS count');
		$this->db->where('status !=', 'Closed');
		if($user[1]['role'] != "L3") {
			$this->db->where('dept_id', $user[1]['dept_id']);
		}
		$all_count = $this->db->get('service_request_tracc_concern')->row()->count;

		return $all_count - $count;
	}

	public function open_tracc_concern($recid) {
		$user_id = $this->Main_model->user_details()[1]['recid'];
		$this->db->where('control_number', $recid);
		$table = $this->db->get('service_request_tracc_concern')->row();

		if($table != null) {
			$data = explode(',', $table->opened);

			if(!in_array($user_id, $data)) {
				$data[] = $user_id;
				$opened = implode(',', $data);

				$this->db->where('control_number', $recid);
				$this->db->set('opened', $opened);
				$this->db->update('service_request_tracc_concern');
			}
		} else {
			$opened = $user_id;

			$this->db->where('control_number', $recid);
			$this->db->set('opened', $opened);
			$this->db->update('service_request_tracc_concern');
		}

	}

	public function get_unopened_tracc_request() {
		$user = $this->Main_model->user_details()[1];

		if($user['role'] != "L3") {
			$this->db->where('department_id', $user['dept_id']);
		}

		$this->db->where('status !=', 'Closed');
		$data = $this->db->get('service_request_tracc_request')->result_array();

		$table = array();
		$count = 0;
		foreach($data as $row) {
			$opened = explode(',', $row['opened']);

			if(in_array($user['recid'], $opened)) {
				$count += 1;
			}
		}

		$this->db->select('COUNT(*) AS count');
		$this->db->where('status !=', 'Closed');
		
		if($user['role'] != 'L3') {
			$this->db->where('department_id', $user['dept_id']);
		}
		$all_count = $this->db->get('service_request_tracc_request')->row()->count;

		return $all_count - $count;
	}

	public function open_tracc_request($recid) {
		$user_id = $this->Main_model->user_details()[1]['recid'];
		$this->db->where('ticket_id', $recid);
		$table = $this->db->get('service_request_tracc_request')->row();

		if($table != null) {
			$data = explode(',', $table->opened);

			if(!in_array($user_id, $data)) {
				$data[] = $user_id;
				$opened = implode(',', $data);

				$this->db->where('ticket_id', $recid);
				$this->db->set('opened', $opened);
				$this->db->update('service_request_tracc_request');
			}
		} else {
			$opened = $user_id;

			$this->db->where('ticket_id', $recid);
			$this->db->set('opened', $opened);
			$this->db->update('service_request_tracc_request');
		}
	}

	public function Acknolwedge_as_resolved($trf_number){
		$user_id = $this->session->userdata('login_data')['user_id'];
		$acknowledge_by = $this->input->post('acknowledge_by', true);
		$acknowledge_by_date = $this->input->post('acknowledge_by_date', true);

		$data = array(
			'acknowledge_by' => $acknowledge_by,
			'acknowledge_by_date' => $acknowledge_by_date,
			'status' => 'Resolved'
		);

		// print_r($data);
		// die();

		$this->db->where('ticket_id', $trf_number);
		$this->db->update('service_request_tracc_request', $data);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('success', 'Ticket acknolwedge as resolved.');
		} else {
			$this->session->set_flashdata('error', 'Error acknowledging ticket as resolved.');
		}

		redirect(base_url(). "sys/users/list/tickets/tracc_request");
	}

	public function get_cutoff() {
		$this->db->where('date <=', date("Y-m-d"));
		$this->db->where('end_date >=', date("Y-m-d"));
		$query = $this->db->get('cutoff')->row();

		if(!empty($query)) {
			return $query;
		} else {
			$this->db->where('recid', 1);
			return $this->db->get('cutoff')->row();
		}
	}
}
?>