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
	public function get_ticket_checkbox_supplier_req_by_ticket_id($ticket_id) {
		$this->db->select('*, COUNT(ticket_id) as count');
		$this->db->from('tracc_req_supplier_req_form_checkboxes');
		$this->db->where('ticket_id', $ticket_id);
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