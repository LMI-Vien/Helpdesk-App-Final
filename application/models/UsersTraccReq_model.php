<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersTraccReq_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
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
			$priority = 'Low';
			if (
				!empty($checkbox_data_newadd['checkbox_item']) ||
				!empty($checkbox_data_newadd['checkbox_customer']) ||
				!empty($checkbox_data_newadd['checkbox_supplier']) ||
				!empty($checkbox_data_newadd['checkbox_whs']) ||
				!empty($checkbox_data_newadd['checkbox_bin']) ||
				!empty($checkbox_data_newadd['checkbox_cus_ship_setup']) ||
				!empty($checkbox_data_newadd['checkbox_employee_req_form']) ||
				!empty($checkbox_data_newadd['checkbox_others_newadd'])
			) {
				$priority = 'High';
			}
			else if (
				!empty($checkbox_data_update['checkbox_system_date_lock']) ||
				!empty($checkbox_data_update['checkbox_user_file_access']) ||
				!empty($checkbox_data_update['checkbox_item_dets']) ||
				!empty($checkbox_data_update['checkbox_customer_dets']) ||
				!empty($checkbox_data_update['checkbox_supplier_dets']) ||
				!empty($checkbox_data_update['checkbox_employee_dets']) ||
				!empty($checkbox_data_update['checkbox_others_update'])
			) {
				$priority = "Medium";
			}
			$data = array(
				'ticket_id' 				            => $trf_number,
				'subject' 					            => 'TRACC_REQUEST',
				'requested_by' 				            => $fullname,
				'department' 				            => $department_description,
                'department_id' 			            => $department_id,
                'date_requested'		 	            => $date_requested,
				'date_needed' 				            => $date_needed,
				'requested_by_id' 			            => $user_id,
				'complete_details' 			            => $complete_details,
				'priority' 					            => $priority,
				// 'acknowledge_by' 			            => $acknowledge_by,
				// 'acknowledge_by_date'		            => $acknowledge_by_date,
				'status' 					            => 'Open',
				'approval_status' 			            => 'Pending',
				'it_approval_status' 		            => 'Pending',
				'created_at' 				            => date("Y-m-d H:i:s")
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
					'ticket_id'                         => $trf_number,
					'item'                              => isset($checkbox_data_newadd['checkbox_item']) ? $checkbox_data_newadd['checkbox_item'] : 0,
					'customer'                          => isset($checkbox_data_newadd['checkbox_customer']) ? $checkbox_data_newadd['checkbox_customer'] : 0,
					'supplier'                          => isset($checkbox_data_newadd['checkbox_supplier']) ? $checkbox_data_newadd['checkbox_supplier'] : 0,
					'warehouse'                         => isset($checkbox_data_newadd['checkbox_whs']) ? $checkbox_data_newadd['checkbox_whs'] : 0,
					'bin_number'                        => isset($checkbox_data_newadd['checkbox_bin']) ? $checkbox_data_newadd['checkbox_bin'] : 0,
					'customer_shipping_setup'           => isset($checkbox_data_newadd['checkbox_cus_ship_setup']) ? $checkbox_data_newadd['checkbox_cus_ship_setup'] : 0,
					'employee_request_form'             => isset($checkbox_data_newadd['checkbox_employee_req_form']) ? $checkbox_data_newadd['checkbox_employee_req_form'] : 0,
					'others'                            => isset($checkbox_data_newadd['checkbox_others_newadd']) ? $checkbox_data_newadd['checkbox_others_newadd'] : 0,
					'others_description_add'            => isset($checkbox_data_newadd['others_text_newadd']) ? $checkbox_data_newadd['others_text_newadd'] : ""
				];
				$this->db->insert('tracc_req_mf_new_add', $checkbox_entry_newadd);

				$checkbox_entry_update = [
					'ticket_id'                         => $trf_number,
					'system_date_lock'                  => isset($checkbox_data_update['checkbox_system_date_lock']) ? $checkbox_data_update['checkbox_system_date_lock'] : 0,
					'user_file_access'                  => isset($checkbox_data_update['checkbox_user_file_access']) ? $checkbox_data_update['checkbox_user_file_access'] : 0,
					'item_details'                      => isset($checkbox_data_update['checkbox_item_dets']) ? $checkbox_data_update['checkbox_item_dets'] : 0,
					'customer_details'                  => isset($checkbox_data_update['checkbox_customer_dets']) ? $checkbox_data_update['checkbox_customer_dets'] : 0,
					'supplier_details'                  => isset($checkbox_data_update['checkbox_supplier_dets']) ? $checkbox_data_update['checkbox_supplier_dets'] : 0,
					'employee_details'                  => isset($checkbox_data_update['checkbox_employee_dets']) ? $checkbox_data_update['checkbox_employee_dets'] : 0,
					'others'                            => isset($checkbox_data_update['checkbox_others_update']) ? $checkbox_data_update['checkbox_others_update'] : 0,
					'others_description_update'         => isset($checkbox_data_update['others_text_update']) ? $checkbox_data_update['others_text_update'] : ""
				];
				$this->db->insert('tracc_req_mf_update', $checkbox_entry_update);

				$checkbox_entry_account = [
					'ticket_id'                         => $trf_number,
					'tracc_orientation'                 => isset($checkbox_data_account['checkbox_tracc_orien']) ? $checkbox_data_account['checkbox_tracc_orien'] : 0,
					'lmi'                               => isset($checkbox_data_account['checkbox_create_lmi']) ? $checkbox_data_account['checkbox_create_lmi'] : 0,
					'rgdi'                              => isset($checkbox_data_account['checkbox_create_rgdi']) ? $checkbox_data_account['checkbox_create_rgdi'] : 0,
					'lpi'                               => isset($checkbox_data_account['checkbox_create_lpi']) ? $checkbox_data_account['checkbox_create_lpi'] : 0,
					'sv'                                => isset($checkbox_data_account['checkbox_create_sv']) ? $checkbox_data_account['checkbox_create_sv'] : 0,
					'gps_account'                       => isset($checkbox_data_account['checkbox_gps_account']) ? $checkbox_data_account['checkbox_gps_account'] : 0,
					'others'                            => isset($checkbox_data_account['checkbox_others_account']) ? $checkbox_data_account['checkbox_others_account'] : 0,
					'others_description_acc'            => isset($checkbox_data_account['others_text_account']) ? $checkbox_data_account['others_text_account'] : ""
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

    public function add_customer_request_form_pdf($crf_comp_checkbox_values = null, $checkbox_cus_req_form_del, $id) {
		$trf_number = $this->input->post('trf_number', true);
		
		$data = array(
			'ticket_id'                                 => $trf_number,
			'requested_by'                              => $this->input->post('requested_by', true),
			'requested_by_id'							=> $id,
			'date'                                      => $this->input->post('date', true),
			'customer_code'                             => $this->input->post('customer_code', true),
			'customer_name'                             => $this->input->post('customer_name', true),
			'tin_no'                                    => $this->input->post('tin_no', true),
			'terms'                                     => $this->input->post('terms', true),
			'customer_address'                          => $this->input->post('customer_address', true),
			'contact_person'                            => $this->input->post('contact_person', true),
			'office_tel_no'                             => $this->input->post('office_tel_no', true),
			'pricelist'                                 => $this->input->post('pricelist', true),
			'payment_group'                             => $this->input->post('payment_grp', true),
			'contact_no'                                => $this->input->post('contact_no', true),
			'territory'                                 => $this->input->post('territory', true),
			'salesman'                                  => $this->input->post('salesman', true),
			'business_style'                            => $this->input->post('business_style', true),
			'email'                                     => $this->input->post('email', true),
			'shipping_code'                             => $this->input->post('shipping_code', true),
			'route_code'                                => $this->input->post('route_code', true),
			'customer_shipping_address'                 => $this->input->post('customer_shipping_address', true),
			'landmark'                                  => $this->input->post('landmark', true),
			'window_time_start'                         => $this->input->post('window_time_start', true),
			'window_time_end'                           => $this->input->post('window_time_end', true),
			'special_instruction'                       => $this->input->post('special_instruction', true),
			'created_at'                                => date("Y-m-d H:i:s"),
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
				'requested_by_id'						=> $id,
				'ticket_id'                             => $trf_number,
				'outright'                              => isset($checkbox_cus_req_form_del['checkbox_outright']) ? $checkbox_cus_req_form_del['checkbox_outright'] : 0,
				'consignment'                           => isset($checkbox_cus_req_form_del['checkbox_consignment']) ? $checkbox_cus_req_form_del['checkbox_consignment'] : 0,
				'customer_is_also_a_supplier'           => isset($checkbox_cus_req_form_del['checkbox_cus_a_supplier']) ? $checkbox_cus_req_form_del['checkbox_cus_a_supplier'] : 0,
				'online'                                => isset($checkbox_cus_req_form_del['checkbox_online']) ? $checkbox_cus_req_form_del['checkbox_online'] : 0,
				'walk_in'                               => isset($checkbox_cus_req_form_del['checkbox_walkIn']) ? $checkbox_cus_req_form_del['checkbox_walkIn'] : 0,
				'monday'                                => isset($checkbox_cus_req_form_del['checkbox_monday']) ? $checkbox_cus_req_form_del['checkbox_monday'] : 0,
				'tuesday'                               => isset($checkbox_cus_req_form_del['checkbox_tuesday']) ? $checkbox_cus_req_form_del['checkbox_tuesday'] : 0,
				'wednesday'                             => isset($checkbox_cus_req_form_del['checkbox_wednesday']) ? $checkbox_cus_req_form_del['checkbox_wednesday'] : 0,
				'thursday'                              => isset($checkbox_cus_req_form_del['checkbox_thursday']) ? $checkbox_cus_req_form_del['checkbox_thursday'] : 0,
				'friday'                                => isset($checkbox_cus_req_form_del['checkbox_friday']) ? $checkbox_cus_req_form_del['checkbox_friday'] : 0,
				'saturday'                              => isset($checkbox_cus_req_form_del['checkbox_saturday']) ? $checkbox_cus_req_form_del['checkbox_saturday'] : 0,
				'sunday'                                => isset($checkbox_cus_req_form_del['checkbox_sunday']) ? $checkbox_cus_req_form_del['checkbox_sunday'] : 0,
				'created_at'                            => date("Y-m-d H:i:s"),
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

    public function add_customer_shipping_setup_pdf($css_comp_checkbox_values = null, $checkbox_cus_ship_setup, $id) {
		$trf_number = $this->input->post('trf_number', true);
	
		$data = array(
			'ticket_id'                                 => $trf_number,
			'requested_by'                              => $this->input->post('requested_by', true),
			'requested_by_id'							=> $id,
			'shipping_code'                             => $this->input->post('shipping_code', true),
			'route_code'                                => $this->input->post('route_code', true),
			'customer_address'                          => $this->input->post('customer_address', true),
			'landmark'                                  => $this->input->post('landmark', true),
			'window_time_start'                         => $this->input->post('window_time_start', true),
			'window_time_end'                           => $this->input->post('window_time_end', true),
			'special_instruction'                       => $this->input->post('special_instruction', true),
			'created_at'                                => date("Y-m-d H:i:s"),
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

    public function add_employee_request_form_pdf($id) {
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
			'ticket_id'                                 => $trf_number,
			'requested_by'                              => $this->input->post('requested_by', true),
			'requested_by_id'							=> $id,
			'name'                                      => $this->input->post('employee_name', true),
			'department'                                => $department_id, 
        	'department_desc'                           => $department_desc,
			'position'                                  => $this->input->post('position', true),
			'address'                                   => $this->input->post('address', true),
			'tel_no_mob_no'                             => $this->input->post('tel_mobile_no', true),
			'tin_no'                                    => $this->input->post('tin_no', true),
			'contact_person'                            => $this->input->post('contact_person', true),
			'created_at'                                => date("Y-m-d H:i:s"),
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

    public function add_item_request_form_pdf($irf_comp_checkbox_value = null, $checkbox_item_req_form, $id) {
		$trf_number = $this->input->post('trf_number', true);

		$data = array(
			'ticket_id'                                 => $trf_number,
			'requested_by'                              => $this->input->post('requested_by', true),
			'requested_by_id'							=> $id,
			'date'                                      => $this->input->post('date', true),
			// 'lmi_item_code'                             => $this->input->post('lmi_item_code', true),
			'long_description'                          => $this->input->post('long_description', true),
			'short_description'                         => $this->input->post('short_description', true),
			'item_classification'                       => $this->input->post('item_classification', true),
			'item_sub_classification'                   => $this->input->post('item_sub_classification', true),
			'department'                                => $this->input->post('department', true),
			'merch_category'                            => $this->input->post('merch_cat', true),
			'brand'                                     => $this->input->post('brand', true),
			'supplier_code'                             => $this->input->post('supplier_code', true),
			'supplier_name'                             => $this->input->post('supplier_name', true),
			'class'                                     => $this->input->post('class', true),
			'tag'                                       => $this->input->post('tag', true),
			'source'                                    => $this->input->post('source', true),
			'hs_code'                                   => $this->input->post('hs_code', true),
			'unit_cost'                                 => $this->input->post('unit_cost', true),
			'selling_price'                             => $this->input->post('selling_price', true),
			'major_item_group'                          => $this->input->post('major_item_group', true),
			'item_sub_group'                            => $this->input->post('item_sub_group', true),
			'account_type'                              => $this->input->post('account_type', true),
			'sales'                                     => $this->input->post('sales', true),
			'sales_return'                              => $this->input->post('sales_return', true),
			'purchases'                                 => $this->input->post('purchases', true),
			'purchase_return'                           => $this->input->post('purchase_return', true),
			'cgs'                                       => $this->input->post('cgs', true),
			'inventory'                                 => $this->input->post('inventory', true),
			'sales_disc'                                => $this->input->post('sales_disc', true),
			'gl_department'                             => $this->input->post('gl_dept', true),
			'capacity_per_pallet'                       => $this->input->post('capacity_per_pallet', true),
			'created_at'                                => date("Y-m-d H:i:s"),
		);

		if ($irf_comp_checkbox_value !== null) {
			$data['company'] = $irf_comp_checkbox_value;
		}

		$this->db->trans_begin();
		$this->db->insert('tracc_req_item_request_form', $data);

		if ($this->db->affected_rows() > 0) {
			$checkboxes_item_req_form = [
				'ticket_id'                             => $trf_number,
				'inventory'                             => isset($checkbox_item_req_form['checkbox_inventory']) ? $checkbox_item_req_form['checkbox_inventory'] : 0,
				'non_inventory'                         => isset($checkbox_item_req_form['checkbox_non_inventory']) ? $checkbox_item_req_form['checkbox_non_inventory'] : 0,
				'services'                              => isset($checkbox_item_req_form['checkbox_services']) ? $checkbox_item_req_form['checkbox_services'] : 0,
				'charges'                               => isset($checkbox_item_req_form['checkbox_charges']) ? $checkbox_item_req_form['checkbox_charges'] : 0,
				'watsons'                               => isset($checkbox_item_req_form['checkbox_watsons']) ? $checkbox_item_req_form['checkbox_watsons'] : 0,
				'other_accounts'                        => isset($checkbox_item_req_form['checkbox_other_accounts']) ? $checkbox_item_req_form['checkbox_other_accounts'] : 0,
				'online'                                => isset($checkbox_item_req_form['checkbox_online']) ? $checkbox_item_req_form['checkbox_online'] : 0,
				'all_accounts'                          => isset($checkbox_item_req_form['checkbox_all_accounts']) ? $checkbox_item_req_form['checkbox_all_accounts'] : 0,
				'trade'                                 => isset($checkbox_item_req_form['radio_trade_type']) && $checkbox_item_req_form['radio_trade_type'] === 'trade' ? 1 : 0,  					
				'yes'                                   => isset($checkbox_item_req_form['radio_batch_required']) && $checkbox_item_req_form['radio_batch_required'] === 'yes' ? 1 : 0,
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

    public function add_supplier_request_form_pdf($trf_comp_checkbox_value = null, $checkbox_non_vat = 0, $checkbox_supplier_req_form, $id) {
		$trf_number = $this->input->post('trf_number', true);

		$data = array(
			'ticket_id'                                 => $trf_number,
			'requested_by'                              => $this->input->post('requested_by', true),
			'requested_by_id'							=> $id,
			'date'                                      => $this->input->post('date', true),
			'supplier_code'                             => $this->input->post('supplier_code', true),
			'supplier_account_group'                    => $this->input->post('supplier_account_group', true),
			'supplier_name'                             => $this->input->post('supplier_name', true),
			'country_origin'                            => $this->input->post('country_origin', true),
			'supplier_address'                          => $this->input->post('supplier_address', true),
			'office_tel'                                => $this->input->post('office_tel_no', true),
			'zip_code'                                  => $this->input->post('zip_code', true),
			'contact_person'                            => $this->input->post('contact_person', true),
			'terms'                                     => $this->input->post('terms', true),
			'tin_no'                                    => $this->input->post('tin_no', true),
			'pricelist'                                 => $this->input->post('pricelist', true),
			'ap_account'                                => $this->input->post('ap_account', true),
			'ewt'                                       => $this->input->post('ewt', true),
			'advance_account'                           => $this->input->post('advance_acc', true),
			'vat'                                       => $this->input->post('vat', true),
			'non_vat'                                   => $checkbox_non_vat,
			'payee_1'                                   => $this->input->post('payee1', true),
			'payee_2'                                   => $this->input->post('payee2', true),
			'payee_3'                                   => $this->input->post('payee3', true),
			'driver_name'                               => $this->input->post('driver_name', true),
			'driver_contact_no'                         => $this->input->post('driver_contact_no', true),
			'driver_fleet'                              => $this->input->post('driver_fleet', true),
			'driver_plate_no'                           => $this->input->post('driver_plate_no', true),
			'helper_name'                               => $this->input->post('helper_name', true),
			'helper_contact_no'                         => $this->input->post('helper_contact_no', true),
			'helper_rate_card'                          => $this->input->post('helper_rate_card', true),
			'created_at'                                => date("Y-m-d H:i:s"),
		);
	
		if ($trf_comp_checkbox_value !== null) {
			$data['company'] = $trf_comp_checkbox_value;
		}

		$this->db->trans_begin();
		$this->db->insert('tracc_req_supplier_req_form', $data);	
	
		if ($this->db->affected_rows() > 0) {
			$checkboxes_sup_req_form = [
				'requested_by_id'						=> $id,
				'ticket_id'                             => $trf_number,
				'supplier_group_local'                  => isset($checkbox_supplier_req_form['local_supplier_grp']) ? $checkbox_supplier_req_form['local_supplier_grp'] : 0,
				'supplier_group_foreign'                => isset($checkbox_supplier_req_form['foreign_supplier_grp']) ? $checkbox_supplier_req_form['foreign_supplier_grp'] : 0,
				'supplier_trade'                        => isset($checkbox_supplier_req_form['supplier_trade']) ? $checkbox_supplier_req_form['supplier_trade'] : 0, 
				'supplier_non_trade'                    => isset($checkbox_supplier_req_form['supplier_non_trade']) ? $checkbox_supplier_req_form['supplier_non_trade'] : 0,
				'trade_type_goods'                      => isset($checkbox_supplier_req_form['trade_type_goods']) ? $checkbox_supplier_req_form['trade_type_goods'] : 0, 
				'trade_type_services'                   => isset($checkbox_supplier_req_form['trade_type_services']) ? $checkbox_supplier_req_form['trade_type_services'] : 0,
				'trade_type_goods_services'             => isset($checkbox_supplier_req_form['trade_type_GoodsServices']) ? $checkbox_supplier_req_form['trade_type_GoodsServices'] : 0,
				'major_grp_local_trade_vendor'          => isset($checkbox_supplier_req_form['major_grp_local_trade_ven']) ? $checkbox_supplier_req_form['major_grp_local_trade_ven'] : 0,
				'major_grp_local_non_trade_vendor'      => isset($checkbox_supplier_req_form['major_grp_local_nontrade_ven']) ? $checkbox_supplier_req_form['major_grp_local_nontrade_ven'] : 0,
				'major_grp_foreign_trade_vendors'       => isset($checkbox_supplier_req_form['major_grp_foreign_trade_ven']) ? $checkbox_supplier_req_form['major_grp_foreign_trade_ven'] : 0,
				'major_grp_foreign_non_trade_vendors'   => isset($checkbox_supplier_req_form['major_grp_foreign_nontrade_ven']) ? $checkbox_supplier_req_form['major_grp_foreign_nontrade_ven'] : 0,
				'major_grp_local_broker_forwarder'      => isset($checkbox_supplier_req_form['major_grp_local_broker_forwarder']) ? $checkbox_supplier_req_form['major_grp_local_broker_forwarder'] : 0,
				'major_grp_rental'                      => isset($checkbox_supplier_req_form['major_grp_rental']) ? $checkbox_supplier_req_form['major_grp_rental'] : 0,
				'major_grp_bank'                        => isset($checkbox_supplier_req_form['major_grp_bank']) ? $checkbox_supplier_req_form['major_grp_bank'] : 0,
				'major_grp_ot_supplier'                 => isset($checkbox_supplier_req_form['major_grp_one_time_supplier']) ? $checkbox_supplier_req_form['major_grp_one_time_supplier'] : 0,
				'major_grp_government_offices'          => isset($checkbox_supplier_req_form['major_grp_government_offices']) ? $checkbox_supplier_req_form['major_grp_government_offices'] : 0,
				'major_grp_insurance'                   => isset($checkbox_supplier_req_form['major_grp_insurance']) ? $checkbox_supplier_req_form['major_grp_insurance'] : 0,
				'major_grp_employees'                   => isset($checkbox_supplier_req_form['major_grp_employees']) ? $checkbox_supplier_req_form['major_grp_employees'] : 0,
				'major_grp_sub_aff_intercompany'        => isset($checkbox_supplier_req_form['major_grp_subs_affiliates']) ? $checkbox_supplier_req_form['major_grp_subs_affiliates'] : 0,
				'major_grp_utilities'                   => isset($checkbox_supplier_req_form['major_grp_utilities']) ? $checkbox_supplier_req_form['major_grp_utilities'] : 0,
			];
			$this->db->insert('tracc_req_supplier_req_form_checkboxes', $checkboxes_sup_req_form);

			$this->db->trans_commit();
			return array(1, "Successfully Created Item Request Form for: " . $data['ticket_id']);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not insert data. Please try again.");
		}
	}

    public function get_customer_from_tracc_req_details() {
		$this->db->select('*');
		$this->db->from('tracc_req_mf_new_add');
		$this->db->where('customer', 1); 
		$query = $this->db->get();
		return $query->result_array();
	}

    public function get_customer_from_tracc_req_mf_new_add($user_id) {
        $this->db->select('tracc_req_mf_new_add.ticket_id');
        $this->db->from('tracc_req_mf_new_add');
        $this->db->join(
            'service_request_tracc_request', 
            'service_request_tracc_request.ticket_id = tracc_req_mf_new_add.ticket_id'
        );
        $this->db->where('tracc_req_mf_new_add.customer', '1');
        $this->db->where('service_request_tracc_request.status !=', 'closed');
		$this->db->where('service_request_tracc_request.requested_by_id', $user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    // public function get_customer_shipping_setup_from_tracc_req_mf_new_add(){
	// 	$this->db->select('ticket_id');
	// 	$this->db->from('tracc_req_mf_new_add');
	// 	$this->db->where('customer_shipping_setup', 1); 
	// 	$query = $this->db->get();
	// 	return $query->result_array();
	// }

    public function get_customer_shipping_setup_from_tracc_req_mf_new_add($user_id) {
        $this->db->select('tracc_req_mf_new_add.ticket_id');
        $this->db->from('tracc_req_mf_new_add');
        $this->db->join(
            'service_request_tracc_request', 
            'service_request_tracc_request.ticket_id = tracc_req_mf_new_add.ticket_id'
        );
        $this->db->where('tracc_req_mf_new_add.customer_shipping_setup', '1');
        $this->db->where('service_request_tracc_request.status !=', 'closed');
		$this->db->where('service_request_tracc_request.requested_by_id', $user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    // public function get_employee_request_form_from_tracc_req_mf_new_add(){
	// 	$this->db->select('ticket_id');
	// 	$this->db->from('tracc_req_mf_new_add');
	// 	$this->db->where('employee_request_form', 1); 
	// 	$query = $this->db->get();
	// 	return $query->result_array();
	// }

    public function get_employee_request_form_from_tracc_req_mf_new_add($user_id) {
        $this->db->select('tracc_req_mf_new_add.ticket_id');
        $this->db->from('tracc_req_mf_new_add');
        $this->db->join(
            'service_request_tracc_request', 
            'service_request_tracc_request.ticket_id = tracc_req_mf_new_add.ticket_id'
        );
        $this->db->where('tracc_req_mf_new_add.employee_request_form', '1');
        $this->db->where('service_request_tracc_request.status !=', 'closed');
		$this->db->where('service_request_tracc_request.requested_by_id', $user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    // public function get_item_request_form_from_tracc_req_mf_new_add(){
	// 	$this->db->select('ticket_id');
	// 	$this->db->from('tracc_req_mf_new_add');
	// 	$this->db->where('item', 1); 
	// 	$query = $this->db->get();
	// 	return $query->result_array();
	// }

    public function get_item_request_form_from_tracc_req_mf_new_add() {
        $this->db->select('tracc_req_mf_new_add.ticket_id');
        $this->db->from('tracc_req_mf_new_add');
        $this->db->join(
            'service_request_tracc_request', 
            'service_request_tracc_request.ticket_id = tracc_req_mf_new_add.ticket_id'
        );
        $this->db->where('tracc_req_mf_new_add.item', '1');
        $this->db->where('service_request_tracc_request.status !=', 'closed');
        $query = $this->db->get();
        return $query->result_array();
    }

    // public function get_supplier_from_tracc_req_mf_new_add(){
	// 	$this->db->select('ticket_id');
	// 	$this->db->from('tracc_req_mf_new_add');
	// 	$this->db->where('supplier', 1); 
	// 	$query = $this->db->get();
	// 	return $query->result_array();
	// }

    public function get_supplier_from_tracc_req_mf_new_add() {
        $this->db->select('tracc_req_mf_new_add.ticket_id');
        $this->db->from('tracc_req_mf_new_add');
        $this->db->join(
            'service_request_tracc_request', 
            'service_request_tracc_request.ticket_id = tracc_req_mf_new_add.ticket_id'
        );
        $this->db->where('tracc_req_mf_new_add.supplier', '1');
        $this->db->where('service_request_tracc_request.status !=', 'closed');
        $query = $this->db->get();
        return $query->result_array();
    }

	// Kevin: Query customer request form details by ID
	public function get_customer_req_form_rf_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_customer_req_form');

		$formattedData = array();
		$data = $query->result_array();

		return $query->result_array();
	}

	// Kevin: Query shipping setup details by ID
	public function get_customer_req_form_ss_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_customer_ship_setup');
		return $query->result_array();
	}

	public function update_ss($id, $css_comp_checkbox_values = null, $checkbox_cus_ship_setup) {
		$trf_number = $this->input->post('trf_number', true);
	
		$data = array(
			'ticket_id'                                 => $trf_number,
			'requested_by'                              => $this->input->post('requested_by', true),
			'shipping_code'                             => $this->input->post('shipping_code', true),
			'route_code'                                => $this->input->post('route_code', true),
			'customer_address'                          => $this->input->post('customer_address', true),
			'landmark'                                  => $this->input->post('landmark', true),
			'window_time_start'                         => $this->input->post('window_time_start', true),
			'window_time_end'                           => $this->input->post('window_time_end', true),
			'special_instruction'                       => $this->input->post('special_instruction', true),
			'created_at'                                => date("Y-m-d H:i:s"),
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
	
		$this->db->update('tracc_req_customer_ship_setup', $data, ['recid' => $id]);
	
		if ($this->db->affected_rows() > 0) {
			$this->db->trans_commit();
			return array(1, "Successfully Edited Customer Shipping Setup for: " . $data['ticket_id']);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not edit data.");
		}
	}

	// Kevin: Query item request details by ID
	public function get_customer_req_form_ir_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_item_request_form');
		return $query->result_array();
	}

	// Kevin: Query employee request details by ID
	public function get_customer_req_form_er_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_employee_req_form');
		return $query->result_array();
	}

	public function update_er($id) {
		$department_id = $this->input->post('department', true);

		$this->db->select('*');
		$this->db->from('tracc_req_employee_req_form');
		$this->db->where('recid', $id);
		$remark = $this->db->get()->row()->remarks;

		$this->db->select('dept_desc');
		$this->db->from('departments');
		$this->db->where('recid', $department_id);
		$department_description = $this->db->get()->row()->dept_desc;

		if($remark == "Done") {
			return array(0, "Error: Could not update data.");
		} else {
			$data = array(
				'ticket_id'	=> $this->input->post('trf_number', true),
				'name' => $this->input->post('employee_name', true),
				'department' => $department_id,
				'department_desc' => $department_description,
				'position' => $this->input->post('position', true),
				'address' => $this->input->post('address', true),
				'tel_no_mob_no' => $this->input->post('tel_mobile_no', true),
				'tin_no' => $this->input->post('tin_no', true),
				'contact_person' => $this->input->post('contact_person', true),
				'created_at' => date("Y-m-d H:i:s"),
			 );
	
			$this->db->trans_begin();
	
			$this->db->update('tracc_req_employee_req_form', $data, ['recid' => $id]);
	
			if($this->db->affected_rows() > 0) {
				$this->db->trans_commit();
				return array(1, "Successfully Updated Employee Request Form for: " . $data['ticket_id']);
			} else {
				$this->db->trans_rollback();
				return array(0, "Error: Could not update data. Please try again.");
			}
		}
	}

	// Kevin: Query supplier request details by ID
	public function get_customer_req_form_sr_details($id) {
		$this->db->select('*');
		$this->db->where('recid', $id);
		$query = $this->db->get('tracc_req_supplier_req_form');
		return $query->result_array();
	}

	// Edit function for customer request form
	// public function edit_customer_request_form_pdf($id, $selected_companies, $date, $customer_code, $tin_no, $customer_name, $terms, $customer_address, $contact_person, $pricelist, $office_tel_no, $payment_group, $contact_no, $territory, $salesman, $business_style, $email, $shipping_code, $route_code, $customer_shipping_address, $landmark, $window_time_start, $window_time_end, $special_instruction) {
	// 	$qry = $this->db->query('SELECT * FROM tracc_req_customer_req_form WHERE recid = ?', [$id]);

	// 	if ($qry->num_rows() > 0){
	// 		$row = $qry->row();

	// 		$this->db->set('date', $date);
	// 		$this->db->set('customer_code', $customer_code);
	// 		$this->db->set('tin_no', $tin_no);
	// 		$this->db->set('terms', $terms);
	// 		$this->db->set('customer_address', $customer_address);
	// 		$this->db->set('contact_person', $contact_person);
	// 		$this->db->set('office_tel_no', $office_tel_no);
	// 		$this->db->set('pricelist', $pricelist);
	// 		$this->db->set('payment_group', $payment_group);
	// 		$this->db->set('contact_no', $contact_no);
	// 		$this->db->set('territory', $territory);
	// 		$this->db->set('salesman', $salesman);
	// 		$this->db->set('business_style', $business_style);
	// 		$this->db->set('email', $email);
	// 		$this->db->set('shipping_code', $shipping_code);
	// 		$this->db->set('route_code', $route_code);
	// 		$this->db->set('customer_shipping_address', $customer_shipping_address);
	// 		$this->db->set('landmark', $landmark);
	// 		$this->db->set('window_time_start', $window_time_start);
	// 		$this->db->set('window_time_end', $window_time_end);
	// 		$this->db->set('special_instruction', $special_instruction);

	// 		if (!empty($selected_companies)) {
	// 			$this->db->set('company', implode(',', $selected_companies));
	// 		} else {
	// 			$this->db->set('company', null); // Clear the value if no checkbox is selected
	// 		}

	// 		$this->db->where('recid', $id);
	// 		$this->db->update('tracc_req_customer_req_form');

	// 		if ($this->db->affected_rows() > 0) {
	// 			$this->db->trans_commit();
	// 			return array(1, "Successfully Updating Tickets: " . $id);
	// 		} else {
	// 			$this->db->trans_rollback();
	// 			return array(0, "Successfully Updating Tickets: " . $id);
	// 		}
	// 	} else {
	// 		return array(0, "Service request not found for ticket: " . $id);
	// 	}
	// }

	// public function edit_customer_request_form_pdf_del_days($id, $del_days){
	// 	$qry = $this->db->get_where('tracc_req_customer_req_form_del_days', ['recid' => $id]);

	// 	if ($qry->num_rows() > 0) {
	// 		$this->db->where('recid', $id);
	// 		$this->db->update('tracc_req_customer_req_form_del_days', $del_days);

	// 		if ($this->db->affected_rows() > 0) {
	// 			$this->db->trans_commit();
	// 			return array(1, "Successfully Updating Tickets: " . $id);
	// 		} else {
	// 			$this->db->trans_rollback();
	// 			return array(0, "Successfully Updating Tickets: " . $id);
	// 		}
	// 	} else {
	// 		return [0, "No existing 'New/Add' data found for ticket: " . $trf_number];
	// 	}
	// }

	public function edit_customer_request_form_pdf($id, $selected_companies, $date, $customer_code, $tin_no, $customer_name, $terms, $customer_address, $contact_person, $pricelist, $office_tel_no, $payment_group, $contact_no, $territory, $salesman, $business_style, $email, $shipping_code, $route_code, $customer_shipping_address, $landmark, $window_time_start, $window_time_end, $special_instruction, $del_days) {
		// Start transaction
		$this->db->trans_start();
	
		// Update customer request form
		$qry = $this->db->get_where('tracc_req_customer_req_form', ['recid' => $id]);
		
		if ($qry->num_rows() > 0) {
			$this->db->set('date', $date);
			$this->db->set('customer_code', $customer_code);
			$this->db->set('tin_no', $tin_no);
			$this->db->set('terms', $terms);
			$this->db->set('customer_address', $customer_address);
			$this->db->set('contact_person', $contact_person);
			$this->db->set('office_tel_no', $office_tel_no);
			$this->db->set('pricelist', $pricelist);
			$this->db->set('payment_group', $payment_group);
			$this->db->set('contact_no', $contact_no);
			$this->db->set('territory', $territory);
			$this->db->set('salesman', $salesman);
			$this->db->set('business_style', $business_style);
			$this->db->set('email', $email);
			$this->db->set('shipping_code', $shipping_code);
			$this->db->set('route_code', $route_code);
			$this->db->set('customer_shipping_address', $customer_shipping_address);
			$this->db->set('landmark', $landmark);
			$this->db->set('window_time_start', $window_time_start);
			$this->db->set('window_time_end', $window_time_end);
			$this->db->set('special_instruction', $special_instruction);
	
			// Handle selected companies
			if (!empty($selected_companies)) {
				$this->db->set('company', implode(',', $selected_companies));
			} else {
				$this->db->set('company', null); // Clear the value if no checkbox is selected
			}
	
			$this->db->where('recid', $id);
			$this->db->update('tracc_req_customer_req_form');
		} else {
			// If no record found for the customer request form
			$this->db->trans_rollback();
			return array(0, "Service request not found for ticket: " . $id);
		}
	
		// Update delivery days
		$qry_del_days = $this->db->get_where('tracc_req_customer_req_form_del_days', ['recid' => $id]);
		
		if ($qry_del_days->num_rows() > 0) {
			$this->db->where('recid', $id);
			$this->db->update('tracc_req_customer_req_form_del_days', $del_days);
		} else {
			// If no record found for the delivery days
			$this->db->trans_rollback();
			return array(0, "No existing 'New/Add' data found for ticket: " . $id);
		}
	
		// Commit the transaction if both updates are successful
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return array(0, "Error updating ticket: " . $id);
		} else {
			$this->db->trans_commit();
			return array(1, "Successfully updated ticket: " . $id);
		}
	}
	

	public function update_sr($id, $trf_comp_checkbox_value = null, $checkbox_non_vat = 0, $checkbox_supplier_req_form) {
		$trf_number = $this->input->post('trf_number', true);

		$data = array(
			'ticket_id'                                 => $trf_number,
			'requested_by'                              => $this->input->post('requested_by', true),
			'date'                                      => $this->input->post('date', true),
			'supplier_code'                             => $this->input->post('supplier_code', true),
			'supplier_account_group'                    => $this->input->post('supplier_account_group', true),
			'supplier_name'                             => $this->input->post('supplier_name', true),
			'country_origin'                            => $this->input->post('country_origin', true),
			'supplier_address'                          => $this->input->post('supplier_address', true),
			'office_tel'                                => $this->input->post('office_tel_no', true),
			'zip_code'                                  => $this->input->post('zip_code', true),
			'contact_person'                            => $this->input->post('contact_person', true),
			'terms'                                     => $this->input->post('terms', true),
			'tin_no'                                    => $this->input->post('tin_no', true),
			'pricelist'                                 => $this->input->post('pricelist', true),
			'ap_account'                                => $this->input->post('ap_account', true),
			'ewt'                                       => $this->input->post('ewt', true),
			'advance_account'                           => $this->input->post('advance_acc', true),
			'vat'                                       => $this->input->post('vat', true),
			'non_vat'                                   => $checkbox_non_vat,
			'payee_1'                                   => $this->input->post('payee1', true),
			'payee_2'                                   => $this->input->post('payee2', true),
			'payee_3'                                   => $this->input->post('payee3', true),
			'driver_name'                               => $this->input->post('driver_name', true),
			'driver_contact_no'                         => $this->input->post('driver_contact_no', true),
			'driver_fleet'                              => $this->input->post('driver_fleet', true),
			'driver_plate_no'                           => $this->input->post('driver_plate_no', true),
			'helper_name'                               => $this->input->post('helper_name', true),
			'helper_contact_no'                         => $this->input->post('helper_contact_no', true),
			'helper_rate_card'                          => $this->input->post('helper_rate_card', true),
			'created_at'                                => date("Y-m-d H:i:s"),
		);

		if ($trf_comp_checkbox_value !== null) {
			$data['company'] = $trf_comp_checkbox_value;
		}

		$this->db->trans_begin();
		$this->db->update('tracc_req_supplier_req_form', $data, ['recid' => $id]);

		if ($this->db->affected_rows() > 0) {
			$checkboxes_sup_req_form = [
				'ticket_id'                             => $trf_number,
				'supplier_group_local'                  => isset($checkbox_supplier_req_form['local_supplier_grp']) ? $checkbox_supplier_req_form['local_supplier_grp'] : 0,
				'supplier_group_foreign'                => isset($checkbox_supplier_req_form['foreign_supplier_grp']) ? $checkbox_supplier_req_form['foreign_supplier_grp'] : 0,
				'supplier_trade'                        => isset($checkbox_supplier_req_form['supplier_trade']) ? $checkbox_supplier_req_form['supplier_trade'] : 0, 
				'supplier_non_trade'                    => isset($checkbox_supplier_req_form['supplier_non_trade']) ? $checkbox_supplier_req_form['supplier_non_trade'] : 0,
				'trade_type_goods'                      => isset($checkbox_supplier_req_form['trade_type_goods']) ? $checkbox_supplier_req_form['trade_type_goods'] : 0, 
				'trade_type_services'                   => isset($checkbox_supplier_req_form['trade_type_services']) ? $checkbox_supplier_req_form['trade_type_services'] : 0,
				'trade_type_goods_services'             => isset($checkbox_supplier_req_form['trade_type_GoodsServices']) ? $checkbox_supplier_req_form['trade_type_GoodsServices'] : 0,
				'major_grp_local_trade_vendor'          => isset($checkbox_supplier_req_form['major_grp_local_trade_ven']) ? $checkbox_supplier_req_form['major_grp_local_trade_ven'] : 0,
				'major_grp_local_non_trade_vendor'      => isset($checkbox_supplier_req_form['major_grp_local_nontrade_ven']) ? $checkbox_supplier_req_form['major_grp_local_nontrade_ven'] : 0,
				'major_grp_foreign_trade_vendors'       => isset($checkbox_supplier_req_form['major_grp_foreign_trade_ven']) ? $checkbox_supplier_req_form['major_grp_foreign_trade_ven'] : 0,
				'major_grp_foreign_non_trade_vendors'   => isset($checkbox_supplier_req_form['major_grp_foreign_nontrade_ven']) ? $checkbox_supplier_req_form['major_grp_foreign_nontrade_ven'] : 0,
				'major_grp_local_broker_forwarder'      => isset($checkbox_supplier_req_form['major_grp_local_broker_forwarder']) ? $checkbox_supplier_req_form['major_grp_local_broker_forwarder'] : 0,
				'major_grp_rental'                      => isset($checkbox_supplier_req_form['major_grp_rental']) ? $checkbox_supplier_req_form['major_grp_rental'] : 0,
				'major_grp_bank'                        => isset($checkbox_supplier_req_form['major_grp_bank']) ? $checkbox_supplier_req_form['major_grp_bank'] : 0,
				'major_grp_ot_supplier'                 => isset($checkbox_supplier_req_form['major_grp_one_time_supplier']) ? $checkbox_supplier_req_form['major_grp_one_time_supplier'] : 0,
				'major_grp_government_offices'          => isset($checkbox_supplier_req_form['major_grp_government_offices']) ? $checkbox_supplier_req_form['major_grp_government_offices'] : 0,
				'major_grp_insurance'                   => isset($checkbox_supplier_req_form['major_grp_insurance']) ? $checkbox_supplier_req_form['major_grp_insurance'] : 0,
				'major_grp_employees'                   => isset($checkbox_supplier_req_form['major_grp_employees']) ? $checkbox_supplier_req_form['major_grp_employees'] : 0,
				'major_grp_sub_aff_intercompany'        => isset($checkbox_supplier_req_form['major_grp_subs_affiliates']) ? $checkbox_supplier_req_form['major_grp_subs_affiliates'] : 0,
				'major_grp_utilities'                   => isset($checkbox_supplier_req_form['major_grp_utilities']) ? $checkbox_supplier_req_form['major_grp_utilities'] : 0,
			];
			$this->db->update('tracc_req_supplier_req_form_checkboxes', $checkboxes_sup_req_form, ['recid' => $id]);

			$this->db->trans_commit();
			return array(1, "Successfully CreatedItem Request Form for: " . $data['ticket_id']);
		} else {
			$this->db->trans_rollback();
			return array(0, "Error: Could not edit data.");
		}
	}
}
?>