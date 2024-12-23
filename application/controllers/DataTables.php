<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataTables extends CI_Controller { 
    public function __construct() {
        parent::__construct();
    }

    //DATATABLE for ADMIN EMPLOYEE MODULE
    public function employee() {
        $draw = intval($this->input->post("draw"));  
        $start = intval($this->input->post("start"));  
        $length = intval($this->input->post("length"));  
        $order = $this->input->post("order");  
        $search = $this->input->post("search");  
        $search = $this->db->escape_str($search['value']); 
    
        $col = 0;  
        $dir = "";  
    
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];  
                $dir = $o['dir'];  
            }
        }
    
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";  
        }
    
        $valid_columns = array(
            0 => 'disable_date',
            1 => 'stamp'
        );
    
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];  
        }
    
        // SEARCH 
        if (!empty($search)) {
            $search_query = "WHERE (emp_id LIKE '%".$search."%' OR fname LIKE '%".$search."%' OR mname LIKE '%".$search."%' OR lname LIKE '%".$search."%' OR email LIKE '%".$search."%' OR username LIKE '%".$search."%' OR role LIKE '%".$search."%' OR department_description LIKE '%".$search."%')";
        } else {
            $search_query = "WHERE 1=1";  
        }
    
        $count_array = $this->db->query("SELECT * FROM users ".$search_query." ORDER BY recid");
        $length_count = $count_array->num_rows();  
    
        $data = array();
    
        $query = $this->db->query("SELECT * FROM users ".$search_query." ORDER BY recid ".$dir." LIMIT ". $start .", ". $length ."");
    
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $rows) { 
                $bid[] = $rows->recid;
                $emp_id[] = $rows->emp_id;
                $full_name[] = $rows->fname . ' ' . $rows->mname . ' ' . $rows->lname; 
                $email[] = $rows->email;
                $position[] = $rows->position;
                $username[] = $rows->username;
                $role[] = $rows->role;
    
                if ($rows->status == 1) {
                    $btn_action[] = "<button class='btn btn-warning btn-sm btn_lock lock_btn' data-empid='".$rows->recid."'><i class='fa fa-power-off'></i> Lock Account</button>";
                } else {
                    $btn_action[] = "<button class='btn btn-warning btn-sm btn_lock unlock_btn' data-empid='".$rows->recid."'><i class='fa fa-power-off'></i> Unlock Account</button>";
                }
                $btn_edit[] = "<a href='".base_url()."sys/admin/update/employee/".$rows->recid."' class='btn btn-success btn-sm btn-edit'><i class='fa fa-pencil'></i> Update Details</a>";
                $btn_delete[] = "<button class='btn btn-danger btn-sm btn-delete' data-toggle='modal' data-target='#UsersDeleteModal' data-id='".$rows->recid."'>
                 <i class='fa fa-trash'></i> Delete</button>";

            }
    
            for ($i = 0; $i < count($bid); $i++) {
                $data[] = array($emp_id[$i], $full_name[$i], $email[$i], $position[$i], $username[$i], $role[$i], $btn_action[$i] . $btn_edit[$i] . $btn_delete[$i]);
            }
        }
    
        $output = array(
            "draw"              => $draw,  
            "recordsTotal"      => $length_count,  
            "recordsFiltered"   => $length_count,  
            "data"              => $data  
        );
        
        echo json_encode($output);
        exit();  
    }

    //DATATABLE for ADMIN Department Viewing
    public function all_departments() {
        // Fetching session data for `user_id and emp_id
        $user_id = $this->session->userdata('login_data')['user_id'];
        $emp_id = $this->session->userdata('login_data')['emp_id'];
        
        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $this->db->escape_str($search['value']);  
        
        $dir = "asc";
        $col = 0;
    
        if (!empty($order)) {
            $col = $order[0]['column'];
            $dir = $order[0]['dir'];
        }
    
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";
        }
    
        $valid_columns = array(
            0 => 'recid',         
            1 => 'dept_desc',
            2 => 'manager_id',
            3 => 'sup_id'
        );
    
        $order = isset($valid_columns[$col]) ? $valid_columns[$col] : 'recid';
    
        $search_query = "";
        if (!empty($search)) {
            $search_query = "WHERE (recid LIKE '%".$search."%' OR dept_desc LIKE '%".$search."%' OR manager_id LIKE '%".$search."%' OR sup_id LIKE '%".$search."%')";
        }
    
        // Get the total number of records in the 'departments' table
        $count_query = $this->db->query("SELECT COUNT(*) AS count FROM departments");
        $total_records = $count_query->row()->count;
    
        // Get the total number of records that match the search criteria
        $filtered_query = $this->db->query("SELECT COUNT(*) AS count FROM departments " . $search_query);
        $filtered_records = $filtered_query->row()->count;
    
        // Fetch the records based on search, order, and pagination parameters
        $data_query = $this->db->query("SELECT * FROM departments " . $search_query . " ORDER BY " . $order . " " . $dir . " LIMIT " . $start . ", " . $length);
    
        $data = array();
    
        if ($data_query->num_rows() > 0) {
            foreach ($data_query->result() as $row) {
                // Create Edit and Delete buttons
                $btn_edit = "<a href='".base_url()."sys/admin/update/department/".$row->recid."' class='btn btn-success btn-sm btn-edit'><i class='fa fa-pencil'></i> Edit</a>";
                $btn_delete = "<button class='btn btn-danger btn-sm btn-delete' data-toggle='modal' data-target='#deleteModal' data-id='".$row->recid."'>
               <i class='fa fa-trash'></i> Delete</button>";

                $data[] = array(
                    $row->recid,
                    $row->dept_desc,
                    $row->manager_id,
                    $row->sup_id,
                    $btn_edit . " " . $btn_delete
                );
            }
        }
    
        $output = array(
            "draw"              => $draw,
            "recordsTotal"      => $total_records,
            "recordsFiltered"   => $filtered_records,
            "data"              => $data
        );
        
        echo json_encode($output);
        exit();
    }

    // DATATABLE for USER (MSRF)
    // public function get_msrf_ticket() {
    //     $user_id = $this->session->userdata('login_data')['user_id'];  
    //     $emp_id = $this->session->userdata('login_data')['emp_id'];  
    //     //$dept_desc = $this->session->userdata('login_data')['dept_desc']; 
    //     $string_emp = $this->db->escape($emp_id);  
        
    //     $draw = intval($this->input->post("draw"));  
    //     $start = intval($this->input->post("start"));  
    //     $length = intval($this->input->post("length")); 
    //     $order = $this->input->post("order");  
    //     $search = $this->input->post("search");  
    //     $search = $this->db->escape_str($search['value']);
    
    //     $col = 0;  
    //     $dir = ""; 
    
    //     if (!empty($order)) {
    //         foreach ($order as $o) {
    //             $col = $o['column'];  
    //             $dir = $o['dir'];  
    //         }
    //     }
    
    //     if ($dir != "asc" && $dir != "desc") {
    //         $dir = "asc";  
    //     }
    
    //     $valid_columns = array(
    //         0 => 'disable_date',  
    //         1 => 'stamp'  
    //     );
    
    //     if (!isset($valid_columns[$col])) {
    //         $order = null;
    //     } else {
    //         $order = $valid_columns[$col]; 
    //     }

    //     $search_query = "";
    //     // print_r($string_emp);
    //     // die();

    //     if (!empty($search)) {
    //         $search_query .= "AND (ticket_id LIKE '%" . $search . "%' OR requestor_name LIKE '%" . $search . "%' OR subject LIKE '%" . $search . "%')";
    //     } 
    //     //else {
    //         // If no search term, leave the search query empty
    //     //    $search_query = "";
    //    // }
    
    //     // Count total records excluding 'Closed' status

       
    //     $sess_login_data = $this->session->userdata('login_data');
    //     $role = $sess_login_data['role'];
    //     $department_id = $sess_login_data['dept_id'];
    //     // print_r($sess_login_data);
    //     // die();
        
    //     if($role === "L1" && intval($department_id) != 1){
            
    //         $query = "status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Approved') ". $search_query = "AND assigned_it_staff =". $string_emp;;
    //         $select = "recid, status, approval_status, it_approval_status, priority, ticket_id, subject, requestor_name, department, dept_id, date_requested";
    //         $result_data = $this->Tickets_model->get_data_list("service_request_msrf", $query, 999,0, $select, null, null, null, null);
    //         $length_count = count($result_data);
    //     }else{
    //         // Count total records excluding 'Closed' status
    //         // print_r($department_id);
    //         // die();
    //         $count_array = $this->db->query("
    //             SELECT * FROM service_request_msrf 
    //             WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved')
    //             OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected') AND requester_id = " . $user_id . ") " 
    //             . $search_query
    //         );
    //         $length_count = $count_array->num_rows();  // Get the total number of rows for pagination
        
    //         // Fetch records excluding 'Closed' status
    //         $data = array();
    //         $strQry = $this->db->query("
    //             SELECT * FROM service_request_msrf 
    //             WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved')
    //             OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected') AND requester_id = " . $user_id . ") " 
    //             . $search_query . 
    //             " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length
    //         );
    //         $result_data = $strQry->result();
    //     }

    
    //     /*$strQry = $this->db->query("
    //         SELECT * FROM service_request_msrf 
    //         WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved') 
    //         AND requester_id = " . $user_id . " " 
    //         . $search_query . 
    //         " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length
    //     ); */

    //     // Check if any records are returned from the query
    //     $tickets = [];
    //     $data = [];
        
    //     if ($result_data > 0) {
    //         // Loop through each row of the result set
    //         foreach ($result_data as $rows) {

    //             // Determine the appropriate label class for the status
    //             $label_class = '';
    //             switch ($rows->status) {
    //                 case 'Open':
    //                     $label_class = 'label-primary';
    //                     break;
    //                 case 'In Progress':
    //                 case 'On going':
    //                     $label_class = 'label-warning';
    //                     break;
    //                 case 'Resolved':
    //                     $label_class = 'label-success';
    //                     break;
    //                 case 'Closed': 
    //                     $label_class = 'label-danger';
    //                     break;
    //                 case 'Rejected':
    //                     $label_class = 'label-danger';
    //                     break;
    //                 case 'On Going':
    //                     $label_class = 'label-success';
    //                     break;
    //                 case 'Approved':
    //                     $label_class = 'label-success';
    //                     break;
    //                 case 'Returned':
    //                     $label_class = 'label-warning';
    //                     break;
    //             }
    //             $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
    //             $prio_class = '';
    //             switch ($rows->priority) {
    //                 case 'Low':
    //                     $prio_class = 'label-primary';
    //                     break;
    //                 case 'Medium':
    //                     $prio_class = 'label-warning';
    //                     break;
    //                 case 'High':
    //                     $prio_class = 'label-danger';
    //                     break;
    //             }
    //             $prio_label[] = '<span class="label ' . $prio_class . '">' . $rows->priority . '</span>';
    
    //             $app_stat_class = '';
    //             switch ($rows->approval_status) {
    //                 case 'Approved':
    //                     $app_stat_class = 'label-success';
    //                     break;
    //                 case 'Pending':
    //                     $app_stat_class = 'label-warning';
    //                     break;
    //                 case 'Rejected':
    //                     $app_stat_class = 'label-danger';
    //                     break;
    //                 case 'Returned':
    //                     $app_stat_class = 'label-warning';
    //                     break;
    //             }
    //             $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
    //             $it_stat_class = '';
    //             switch ($rows->it_approval_status) {
    //                 case 'Approved':
    //                     $it_stat_class = 'label-success';
    //                     break;
    //                 case 'Pending':
    //                     $it_stat_class = 'label-warning';
    //                     break;
    //                 case 'Rejected':
    //                     $it_stat_class = 'label-danger';
    //                     break;
    //                 case 'Closed':
    //                     $it_stat_class = 'label-info';
    //                     break;
    //             }
    //             $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';
    
    //             $tickets[] = "<a href='" . base_url() . "sys/users/details/concern/msrf/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
    //             $name[] = $rows->requestor_name;
    //             $subject[] = $rows->subject;
                
    //         }
    
    //         // Combine all columns' data into a single row for each ticket
    //         if($tickets){
    //             for ($i = 0; $i < $length_count; $i++) {
    //                 $data[] = array(
    //                     $tickets[$i],       // Ticket ID
    //                     $name[$i],          // Requestor name
    //                     $subject[$i],       // Subject
    //                     $prio_label[$i],    // Priority label
    //                     $status_label[$i],  // Status label
    //                     $app_stat_label[$i],// Department approval status label
    //                     $it_stat_label[$i],  // ICT approval status label
    //                 );
    //             }
    //         }
    //     }
    
    //     $output = array(
    //         "draw" => $draw,  
    //         "recordsTotal" => $length_count,  
    //         "recordsFiltered" => $length_count,  
    //         "data" => $data  
    //     );
       
    //     // Return the output as a JSON response
    //     // print_r($data);
    //     // die();
    //     echo json_encode($output);
    //     die();
    //     exit();  // Ensure no further processing happens
    // } 
    
    //DATATABLE for USER (MSRF BACKUP)
    public function get_msrf_ticket() {
        $user_id = $this->session->userdata('login_data')['user_id'];  
        $emp_id = $this->session->userdata('login_data')['emp_id'];  
        $string_emp = $this->db->escape($emp_id);  
    
        $draw = intval($this->input->post("draw")); 
        $start = intval($this->input->post("start")); 
        $length = intval($this->input->post("length"));  
        $order = $this->input->post("order"); 
        $search = $this->input->post("search");  
        $search = $this->db->escape_str($search['value']);  
    
        $col = 0;  
        $dir = ""; 
    
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];  
                $dir = $o['dir'];  
            }
        }
    
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";  
        }
    
        $valid_columns = array(
            0 => 'disable_date',  
            1 => 'stamp'  
        );
    
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col]; 
        }

        // SEARCH
        if (!empty($search)) {
            $search_query = "AND (ticket_id LIKE '%" . $search . "%' OR requestor_name LIKE '%" . $search . "%' OR subject LIKE '%" . $search . "%')";
        } else {
            $search_query = "";
        }
    
        $count_array = $this->db->query("
            SELECT * FROM service_request_msrf 
            WHERE (status IN ('Open', 'In Progress', 'Resolved', 'Approved') AND assigned_it_staff = " . $string_emp . ") 
            OR (status IN ('Open', 'In Progress', 'Resolved', 'Rejected', 'Approved') AND requester_id = " . $user_id . ") " 
            . $search_query
        );
        $length_count = $count_array->num_rows();  
    
        $data = array();
        $strQry = $this->db->query("
            SELECT * FROM service_request_msrf 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Approved') AND assigned_it_staff = " . $string_emp . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Approved') AND requester_id = " . $user_id . ") " 
            . $search_query . 
            " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length
        );

        if ($strQry->num_rows() > 0) {
            foreach ($strQry->result() as $rows) {
                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'In Progress':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed': 
                        $label_class = 'label-danger';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'On Going':
                        $label_class = 'label-success';
                        break;
                    case 'Approved':
                        $label_class = 'label-success';
                        break;
                    case 'Returned':
                        $label_class = 'label-warning';
                        break;
                }
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
                $prio_class = '';
                switch ($rows->priority) {
                    case 'Low':
                        $prio_class = 'label-primary';
                        break;
                    case 'Medium':
                        $prio_class = 'label-warning';
                        break;
                    case 'High':
                        $prio_class = 'label-danger';
                        break;
                }
                $prio_label[] = '<span class="label ' . $prio_class . '">' . $rows->priority . '</span>';
    
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                    case 'Returned':
                        $app_stat_class = 'label-warning';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';
    
                $tickets[] = "<a href='" . base_url() . "sys/users/details/concern/msrf/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
                $name[] = $rows->requestor_name;
                $subject[] = $rows->subject;
                
            }
    
            for ($i = 0; $i < count($tickets); $i++) {
                $data[] = array(
                    $tickets[$i],       
                    $name[$i],          
                    $subject[$i],      
                    $prio_label[$i],    
                    $status_label[$i],  
                    $app_stat_label[$i],
                    $it_stat_label[$i],  
                );
            }
        }
    
        $output = array(
            "draw" => $draw,  
            "recordsTotal" => $length_count,  
            "recordsFiltered" => $length_count,  
            "data" => $data  
        );
       
        echo json_encode($output);
        exit();  
    }

    // DATATABLE for ADMIN (MSRF)
    public function all_tickets_msrf() {
        $user_id = $this->session->userdata('login_data')['user_id'];
        $emp_id = $this->session->userdata('login_data')['emp_id'];   
    
        $draw = intval($this->input->post("draw")); 
        $start = intval($this->input->post("start")); 
        $length = intval($this->input->post("length")); 
        $order = $this->input->post("order"); 
        $search = $this->input->post("search"); 
        $search = $this->db->escape_str($search['value']); 
        
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $statusFilter = $this->input->post('statusFilter');

        $col = 0; 
        $dir = "asc"; 
    
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column']; 
                $dir = $o['dir']; 
            }
        }
        
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";
        }
    
        $valid_columns = array(
            0 => 'disable_date',
            1 => 'stamp'
        );

        $order = isset($valid_columns[$col]) ? $valid_columns[$col] : null;
    
        // SEARCH 
        $search_query = "";
        if (!empty($search)) {
            $search_query = "AND (ticket_id LIKE '%" . $search . "%' OR requestor_name LIKE '%" . $search . "%' OR subject LIKE '%" . $search . "%' OR department LIKE '%" . $search . "%')";
        }
        
        $count_query = "SELECT * FROM service_request_msrf WHERE status IN ('Open', 'In Progress', 'Resolved', 'Rejected', 'Approved', 'Returned') " . $search_query . " ORDER BY recid";
        $count_array = $this->db->query($count_query);
        $length_count = $count_array->num_rows();
    
        $strQry = "SELECT * FROM service_request_msrf WHERE status IN ('Open', 'In Progress', 'Resolved', 'Rejected', 'Approved', 'Returned') AND (sup_id = " . $user_id . " OR it_sup_id = '23-0001' OR assigned_it_staff = '" . $emp_id . "') " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length;
        $data_query = $this->db->query($strQry);
        $data = array();

        if ($data_query->num_rows() > 0) {
            foreach ($data_query->result() as $rows) {
                $bid[] = $rows->recid;
                $ticket[] = $rows->ticket_id;
                $name[] = $rows->requestor_name;
                $subject[] = $rows->subject;
                $status[] = $rows->status; 
                $prio[] = $rows->priority;
                $app_stat[] = $rows->approval_status;
                $it_status[] = $rows->it_approval_status;
                $assigned_it_staff[] = $rows->assigned_it_staff;
    
                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'In Progress':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-info';
                        break;
                    case 'Closed':
                        $label_class = 'label-primary';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'Approved':
                        $label_class = 'label-success';
                        break;
                    case 'Returned':
                        $label_class = 'label-info';
                        break;
                }
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';

                $prio_class = '';
                switch ($rows->priority) {
                    case 'Low':
                        $prio_class = 'label-primary';
                        break;
                    case 'Medium':
                        $prio_class = 'label-warning';
                        break;
                    case 'High':
                        $prio_class = 'label-danger';
                        break;
                }
                $prio_label[] = '<span class="label ' . $prio_class . '">' . $rows->priority . '</span>';

                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                    case 'Returned':
                        $app_stat_class = 'label-info';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';

                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class= 'label-info';
                        break;
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';
    
                // Generate a clickable link for the ticket ID.
                $tickets[] = "<a href='" . base_url() . "sys/admin/approved/" . $rows->subject . "/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";

                if($rows->approval_status === "Pending" || $rows->approval_status === "Returned"){
                    $action[] = '<span class="label">' . '<a class="approve-ticket" data-id="'.$rows->recid.'" data-requestor="'.$rows->requestor_name.'" data-department="'.$rows->department.'" data-concern="'.$rows->details_concern.'" data-date-needed="'.$rows->date_needed.'"><i class="fa fa-check"></i></a>' . '</span>';
                }else{
                    $action[] = '<span class="label"></span>';
                }
            }

            for ($i = 0; $i < count($bid); $i++) {
                $data[] = array(
                    $tickets[$i],
                    $name[$i],
                    $subject[$i],
                    $prio_label[$i],
                    $status_label[$i],
                    $app_stat_label[$i],
                    $it_stat_label[$i],
                    $action[$i], //Action
                );
            }
        }
    
        $output = array(
            "draw"            => $draw,             
            "recordsTotal"    => $length_count,   
            "recordsFiltered" => $length_count,     
            "data"            => $data              
        );
        echo json_encode($output);
        exit();
    }

    // DATATABLE for USER (TRACC CONCERN)
    // public function get_tracc_concern_ticket() {
    //     $user_id = $this->session->userdata('login_data')['user_id'];  
    //     $emp_id = $this->session->userdata('login_data')['emp_id']; 
    //     $string_emp = $this->db->escape($emp_id);  
    
    //     $draw = intval($this->input->post("draw"));  
    //     $start = intval($this->input->post("start")); 
    //     $length = intval($this->input->post("length")); 
    //     $order = $this->input->post("order");  
    //     $search = $this->input->post("search"); 
    //     $search = $this->db->escape_str($search['value']);  
    
    //     $col = 0;  
    //     $dir = "";  
        
    //     if (!empty($order)) {
    //         foreach ($order as $o) {
    //             $col = $o['column'];  
    //             $dir = $o['dir'];  
    //         }
    //     }
    
    //     if ($dir != "asc" && $dir != "desc") {
    //         $dir = "asc";  
    //     }
    
    //     $valid_columns = array(
    //         0 => 'disable_date', 
    //         1 => 'stamp' 
    //     );
    
    //     if (!isset($valid_columns[$col])) {
    //         $order = null;
    //     } else {
    //         $order = $valid_columns[$col];  
    //     }
    
    //     // Enhanced search logic to search multiple fields
    //     $search_query = "AND reported_by_id = " . $this->db->escape($user_id);
    //     if (!empty($search)) {
    //         $search_query .= "AND (control_number LIKE '%" . $search . "%' OR reported_by LIKE '%" . $search . "%' OR subject LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%')";
    //     } 
    //     // else {
    //     //     $search_query = "AND reported_by_id = " . $this->db->escape($user_id);
    //     // }        
    
    //     $sess_login_data = $this->session->userdata('login_data');
    //     $role = $sess_login_data['role'];

    //     if($role === "L1"){
    //         $query = "status IN ('Open', 'In Progress', 'On going', 'Returned', 'Approved', 'Pending') ".$search_query;
    //         $select = "recid, control_number, subject, module_affected, company, reported_by, reported_by_id, received_by, resolved_by, ack_as_resolved, others, priority, status, approval_status, it_approval_status";
    //         $result_data = $this->Tickets_model->get_data_list("service_request_tracc_concern", $query, 999,0, $select, null, null, null, null);

    //         $length_count = count($result_data);
    //     }else{
    //         // Fetch the count of records with the search filter
    //         $count_array = $this->db->query("
    //             SELECT * FROM service_request_tracc_concern 
    //             WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND reported_by_id = " . $user_id . ") 
    //             OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Done')) 
    //             " . $search_query
    //         );
    //         $length_count = $count_array->num_rows();
        
    //         // Fetch records with pagination and search filter applied
    //         $data = array();
            
    //         $strQry = $this->db->query("
    //             SELECT * FROM service_request_tracc_concern
    //             WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Done')
    //             AND reported_by_id = " . $user_id . " " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length);
    //         $result_data = $strQry->num_rows();
    //     }

    //     $control_number = [];
    //     $data = [];
    //     if ($result_data > 0) {
    //         foreach ($result_data as $rows) {
    //             // Status label
    //             $label_class = '';
    //             switch ($rows->status) {
    //                 case 'Open':
    //                     $label_class = 'label-primary';
    //                     break;
    //                 case 'In Progress':
    //                 case 'On going':
    //                     $label_class = 'label-warning';
    //                     break;
    //                 case 'Resolved':
    //                     $label_class = 'label-success';
    //                     break;
    //                 case 'Closed': 
    //                     $label_class = 'label-info';
    //                     break;
    //                 case 'Rejected':
    //                     $label_class = 'label-danger';
    //                     break;
    //                 case 'Done':
    //                     $label_class = 'label-success';
    //                     break;
    //                 case 'Approved':
    //                     $label_class = 'label-success';
    //                     break;
    //                 case 'Returned':
    //                     $label_class = 'label-warning';
    //                     break;
    //             }
    //             $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
    //             $comp_class = '';
    //             switch ($rows->company) {
    //                 case 'LMI':
    //                     $comp_class = 'label-primary';
    //                     break;
    //                 case 'LPI':
    //                     $comp_class = 'label-warning';
    //                     break;
    //                 case 'RGDI':
    //                     $comp_class = 'label-danger';
    //                     break;
    //                 case 'SV':
    //                     $comp_class = 'label-primary';
    //                     break;
    //             }
    //             $comp_label[] = '<span class="label ' . $comp_class . '">' . $rows->company . '</span>';
    
    //             $app_stat_class = '';
    //             switch ($rows->approval_status) {
    //                 case 'Approved':
    //                     $app_stat_class = 'label-success';
    //                     break;
    //                 case 'Pending':
    //                     $app_stat_class = 'label-warning';
    //                     break;
    //                 case 'Rejected':
    //                     $app_stat_class = 'label-danger';
    //                     break;
    //                 case 'Returned':
    //                     $app_stat_class = 'label-info';
    //                     break;
    //             }
    //             $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
    //             $it_stat_class = '';
    //             switch ($rows->it_approval_status) {
    //                 case 'Approved':
    //                     $it_stat_class = 'label-success';
    //                     break;
    //                 case 'Pending':
    //                     $it_stat_class = 'label-warning';
    //                     break;
    //                 case 'Rejected':
    //                     $it_stat_class = 'label-danger';
    //                     break;
    //                 case 'Resolved':
    //                     $it_stat_class = 'label-primary';
    //                     break;
    //                 case 'Closed':
    //                     $it_stat_class = 'label-info';
    //                     break;    
    //             }
    //             $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

    //             $priority_class = '';
    //             switch($rows->priority) {
    //                 case 'High':
    //                     $priority_class = 'label-danger';
    //                     break;
    //                 case 'Medium':
    //                     $priority_class = 'label-warning';
    //                     break;
    //                 case 'Low':
    //                     $priority_class = 'label-info';
    //                     break;
    //             }
    //             $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';
    
    //             // Ticket data
    //             $control_number[] = "<a href='" . base_url() . "sys/users/details/concern/tracc_concern/" . $rows->control_number . "'>" . $rows->control_number . "</a>";
    //             $name[] = $rows->reported_by;
    //             $subject[] = $rows->subject;
    //         }
    
    //         // Create rows for the output
    //         if($control_number){
    //             for ($i = 0; $i < count($control_number); $i++) {
    //                 $data[] = array(
    //                     $control_number[$i],// Control Number   
    //                     $name[$i],          // Reported by
    //                     $subject[$i],       // Subject
    //                     $priority_label[$i],
    //                     $comp_label[$i],    // Company label
    //                     $status_label[$i],  // Status label
    //                     $app_stat_label[$i],// Approval status label
    //                     $it_stat_label[$i] // IT approval status label
    //                 );
    //             }
    //         }
    //     }

    //     $output = array(
    //         "draw" => $draw,  
    //         "recordsTotal" => $length_count,  
    //         "recordsFiltered" => $length_count, 
    //         "data" => $data 
    //     );
    
    //     echo json_encode($output);
    //     exit();
    // }   

    // DATATABLE for USER BACKUP (TRACC CONCERN)
    public function get_tracc_concern_ticket() {
        $user_id = $this->session->userdata('login_data')['user_id'];  
        $emp_id = $this->session->userdata('login_data')['emp_id']; 
        $string_emp = $this->db->escape($emp_id);  
    
        $draw = intval($this->input->post("draw"));  
        $start = intval($this->input->post("start")); 
        $length = intval($this->input->post("length")); 
        $order = $this->input->post("order");  
        $search = $this->input->post("search"); 
        $search = $this->db->escape_str($search['value']);  
    
        $col = 0;  
        $dir = "";  
        
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];  
                $dir = $o['dir'];  
            }
        }
    
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";  
        }
    
        $valid_columns = array(
            0 => 'disable_date', 
            1 => 'stamp' 
        );
    
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];  
        }
    
        // Enhanced search logic to search multiple fields
        if (!empty($search)) {
            $search_query = "AND (control_number LIKE '%" . $search . "%' OR reported_by LIKE '%" . $search . "%' OR subject LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%')";
        } else {
            $search_query = "AND reported_by_id = " . $this->db->escape($user_id);
        }        
    
        $count_array = $this->db->query("
            SELECT * FROM service_request_tracc_concern 
            WHERE (status IN ('Open', 'In Progress', 'Resolved', 'Approved', 'Returned') AND reported_by_id = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'Resolved', 'Rejected', 'Done', 'Approved', 'Returned')) 
            " . $search_query
        );
        $length_count = $count_array->num_rows();
    
        $data = array();
        
        $strQry = $this->db->query("
            SELECT * FROM service_request_tracc_concern
            WHERE status IN ('Open', 'In Progress', 'Resolved', 'Rejected', 'Done', 'Approved', 'Returned')
            AND reported_by_id = " . $user_id . " " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length);


        if ($strQry->num_rows() > 0) {
            foreach ($strQry->result() as $rows) {
                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'In Progress':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed': 
                        $label_class = 'label-info';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'Done':
                        $label_class = 'label-success';
                        break;
                    case 'Approved':
                        $label_class = 'label-success';
                        break;
                    case 'Returned':
                        $label_class = 'label-info';
                        break;
                }
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
                $comp_class = '';
                switch ($rows->company) {
                    case 'LMI':
                        $comp_class = 'label-primary';
                        break;
                    case 'LPI':
                        $comp_class = 'label-warning';
                        break;
                    case 'RGDI':
                        $comp_class = 'label-danger';
                        break;
                    case 'SV':
                        $comp_class = 'label-primary';
                        break;
                }
                $comp_label[] = '<span class="label ' . $comp_class . '">' . $rows->company . '</span>';
    
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                    case 'Returned':
                        $app_stat_class = 'label-info';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class = 'label-primary';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;    
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

                $priority_class = '';
                switch($rows->priority) {
                    case 'High':
                        $priority_class = 'label-danger';
                        break;
                    case 'Medium':
                        $priority_class = 'label-warning';
                        break;
                    case 'Low':
                        $priority_class = 'label-info';
                        break;
                }
                $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';
    
                // Ticket data
                $control_number[] = "<a href='" . base_url() . "sys/users/details/concern/tracc_concern/" . $rows->control_number . "'>" . $rows->control_number . "</a>";
                $name[] = $rows->reported_by;
                $subject[] = $rows->subject;
            }

            for ($i = 0; $i < count($control_number); $i++) {
                $data[] = array(
                    $control_number[$i],  
                    $name[$i],          
                    $subject[$i],       
                    $priority_label[$i],
                    $comp_label[$i],    
                    $status_label[$i],  
                    $app_stat_label[$i],
                    $it_stat_label[$i] 
                );
            }
        }

        $output = array(
            "draw" => $draw,  
            "recordsTotal" => $length_count,  
            "recordsFiltered" => $length_count, 
            "data" => $data 
        );
    
        echo json_encode($output);
        exit();
    }  

    // DATATABLE for ADMIN (TRACC CONCERN)
    public function all_tickets_tracc_concern() {
        $user_id = $this->session->userdata('login_data')['user_id'];  
        $emp_id = $this->session->userdata('login_data')['emp_id'];  
        $string_emp = $this->db->escape($emp_id);  

        $draw = intval($this->input->post("draw")); 
        $start = intval($this->input->post("start"));  
        $length = intval($this->input->post("length"));  
        $order = $this->input->post("order");  
        $search = $this->input->post("search"); 
        $search = $this->db->escape_str($search['value']); 
    
        $col = 0; 
        $dir = "";  

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column']; 
                $dir = $o['dir'];  
            }
        }
    
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";  
        }
    
        $valid_columns = array(
            0 => 'disable_date',  
            1 => 'stamp'  
        );
    
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];  
        }

        if (!empty($search)) {
            $search_query = "AND (control_number LIKE '%" . $search . "%' OR module_affected LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%')";
        } else {
            $search_query = "";
        }
        
        $count_array = $this->db->query("
            SELECT * FROM service_request_tracc_concern 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Approved', 'Returned') AND reported_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Done', 'Approved', 'Returned')) 
            " . $search_query
        );
        $length_count = $count_array->num_rows();

        
        $data = array();
        $strQry = $this->db->query("
            SELECT * FROM service_request_tracc_concern 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Approved', 'Returned') AND reported_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Done', 'Approved', 'Returned')) 
            " . $search_query . " 
            ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length
        );


        if ($strQry->num_rows() > 0) {
            foreach ($strQry->result() as $rows) {
                if ($rows->it_approval_status == 'Resolved') {
                    $status = 'Closed';
                }

                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'In Progress':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed': 
                        $label_class = 'label-primary';
                        break;
                    case 'Done':
                        $label_class = 'label-success';
                        break;
                    case 'Approved':
                        $label_class = 'label-success';
                        break;
                    case 'Returned':
                        $label_class = 'label-info';
                        break;
                }
    
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
                // Company label
                $comp_class = '';
                switch ($rows->company) {
                    case 'LMI':
                        $comp_class = 'label-primary';
                        break;
                    case 'LPI':
                        $comp_class = 'label-warning';
                        break;
                    case 'RGDI':
                        $comp_class = 'label-danger';
                        break;
                    case 'SV':
                        $comp_class = 'label-info';
                        break;
                }
                $comp_label[] = '<span class="label ' . $comp_class . '">' . $rows->company . '</span>';
                
                // Department approval status label
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                    case 'Returned':
                        $app_stat_class = 'label-info';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
                // ICT approval status label
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class = 'label-primary';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;
                   
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

                $priority_class = '';
                switch($rows->priority) {
                    case 'High':
                        $priority_class = 'label-danger';
                        break;
                    case 'Medium':
                        $priority_class = 'label-warning';
                        break;
                    case 'Low':
                        $priority_class = 'label-info';
                        break;
                }
                $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';
    
                $tickets[] = "<a href='" . base_url() . "sys/admin/approved/" . $rows->subject . "/" . $rows->control_number . "'>" . $rows->control_number . "</a>";
                $name[] = $rows->reported_by;
                $subject[] = $rows->subject;

                if($rows->approval_status === "Pending" || $rows->approval_status === "Returned"){
                    $action[] = '<span class="label">' . '<a class="approve-ticket" data-id="'.$rows->recid.'" data-reported-by="'.$rows->reported_by.'" data-concern="'.$rows->tcr_details.'"><i class="fa fa-check"></i></a>' . '</span>';
                }else{
                    $action[] = '<span class="label"></span>';
                }
            }
    
            for ($i = 0; $i < count($tickets); $i++) {
                $data[] = array(
                    $tickets[$i],     
                    $name[$i],          
                    $subject[$i],     
                    $priority_label[$i],
                    $comp_label[$i],    
                    $status_label[$i],  
                    $app_stat_label[$i],
                    $it_stat_label[$i], 
                    $action[$i] //Action
                );
            }   
        }

        $output = array(
            "draw" => $draw,  
            "recordsTotal" => $length_count,  
            "recordsFiltered" => $length_count, 
            "data" => $data 
        );

        echo json_encode($output);
        exit();  
    }

    // DATATABLE for USER (TRACC REQUEST FORM)
    // public function get_trf_ticket() {
    //     $user_id = $this->session->userdata('login_data')['user_id'];  
    //     $emp_id = $this->session->userdata('login_data')['emp_id']; 
    //     $string_emp = $this->db->escape($emp_id);  
    
    //     $draw = intval($this->input->post("draw"));  
    //     $start = intval($this->input->post("start")); 
    //     $length = intval($this->input->post("length")); 
    //     $order = $this->input->post("order");  
    //     $search = $this->input->post("search"); 
    //     $search = $this->db->escape_str($search['value']);  
    
    //     $col = 0;  
    //     $dir = "";  
        
    //     if (!empty($order)) {
    //         foreach ($order as $o) {
    //             $col = $o['column'];  
    //             $dir = $o['dir'];  
    //         }
    //     }
    
    //     if ($dir != "asc" && $dir != "desc") {
    //         $dir = "asc";  
    //     }
    
    //     $valid_columns = array(
    //         0 => 'disable_date', 
    //         1 => 'stamp' 
    //     );
    
    //     if (!isset($valid_columns[$col])) {
    //         $order = null;
    //     } else {
    //         $order = $valid_columns[$col];  
    //     }
    
    //     // Enhanced search logic to search multiple fields
    //     $search_query = "AND requested_by_id = " . $this->db->escape($user_id);
    //     if (!empty($search)) {
    //         $search_query .= "AND (ticket_id LIKE '%" . $search . "%' OR requested_by LIKE '%" . $search . "%' OR department LIKE '%" . $search . "%')";
    //     }
    //     //else {
    //     //    $search_query = "AND requested_by_id = " . $this->db->escape($user_id);
    //     //}        
    
    //     $sess_login_data = $this->session->userdata('login_data');
    //     $role = $sess_login_data['role'];

    //     if($role === "L1"){
    //         $query = "status IN ('Open', 'In Progress', 'On going', 'Resolved') ".$search_query;
    //         $select = "recid, ticket_id, subject, company, priority, status, approval_status, it_approval_status, requested_by";
    //         $result_data = $this->Tickets_model->get_data_list("service_request_tracc_request", $query, 999,0, $select, null, null, null, null);

    //         $length_count = count($result_data);
    //     }else{
    //         // Fetch the count of records with the search filter
    //         $count_array = $this->db->query("
    //             SELECT * FROM service_request_tracc_request 
    //             WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved') AND requested_by_id = " . $user_id . ") 
    //             OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected')) 
    //             " . $search_query
    //         );
    //         $length_count = $count_array->num_rows();
            
    //         $data = array();
            
    //         $strQry = $this->db->query("
    //             SELECT * FROM service_request_tracc_request
    //             WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected')
    //             AND requested_by_id = " . $user_id . " " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length); 
    //         $result_data = $strQry->num_rows();              
    //     }


    //     $trf_ticket = [];
    //     $data = [];
    //     if ($result_data > 0) {
    //         foreach ($result_data as $rows) {
    //             // Status label
    //             $label_class = '';
    //             switch ($rows->status) {
    //                 case 'Open':
    //                     $label_class = 'label-primary';
    //                     break;
    //                 case 'In Progress':
    //                 case 'On going':
    //                     $label_class = 'label-warning';
    //                     break;
    //                 case 'Resolved':
    //                     $label_class = 'label-success';
    //                     break;
    //                 case 'Closed': 
    //                     $label_class = 'label-info';
    //                     break;
    //                 case 'Rejected':
    //                     $label_class = 'label-danger';
    //                     break;
    //             }
    //             $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
    //             // Approval status label
    //             $app_stat_class = '';
    //             switch ($rows->approval_status) {
    //                 case 'Approved':
    //                     $app_stat_class = 'label-success';
    //                     break;
    //                 case 'Pending':
    //                     $app_stat_class = 'label-warning';
    //                     break;
    //                 case 'Rejected':
    //                     $app_stat_class = 'label-danger';
    //                     break;
    //             }
    //             $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
    //             // IT approval status label
    //             $it_stat_class = '';
    //             switch ($rows->it_approval_status) {
    //                 case 'Approved':
    //                     $it_stat_class = 'label-success';
    //                     break;
    //                 case 'Pending':
    //                     $it_stat_class = 'label-warning';
    //                     break;
    //                 case 'Rejected':
    //                     $it_stat_class = 'label-danger';
    //                     break;
    //                 case 'Resolved':
    //                     $it_stat_class = 'label-primary';
    //                     break;
    //                 case 'Closed':
    //                     $it_stat_class = 'label-info';
    //                     break;    
    //             }
    //             $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

    //             $priority_class = '';
    //             switch($rows->priority) {
    //                 case 'High':
    //                     $priority_class = 'label-danger';
    //                     break;
    //                 case 'Medium':
    //                     $priority_class = 'label-warning';
    //                     break;
    //                 case 'Low':
    //                     $priority_class = 'label-info';
    //                     break;
    //             }
    //             $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';
    //             // Ticket data
    //             $trf_ticket[] = "<a href='" . base_url() . "sys/users/details/concern/tracc_request/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
    //             $name[] = $rows->requested_by;
    //             $subject[] = $rows->subject;
    //         }
    
    //         // Create rows for the output
    //         if($trf_ticket){
    //             for ($i = 0; $i < count($trf_ticket); $i++) {
    //                 $data[] = array(
    //                     $trf_ticket[$i],// Control Number   
    //                     $name[$i],          // Reported by
    //                     $subject[$i],       // Subject
    //                     $priority_label[$i],
    //                     $status_label[$i],  // Status label
    //                     $app_stat_label[$i],// Approval status label
    //                     $it_stat_label[$i]  // IT approval status label
    //                 );
    //             }                
    //         }

    //     }
    
    //     $output = array(
    //         "draw" => $draw,  
    //         "recordsTotal" => $length_count,  
    //         "recordsFiltered" => $length_count, 
    //         "data" => $data 
    //     );
    
    //     echo json_encode($output);
    //     exit();
    // }

    // DATATABLE for USER BACKUP (TRACC REQUEST FORM)
    public function get_trf_ticket() {
        $user_id = $this->session->userdata('login_data')['user_id'];  
        $emp_id = $this->session->userdata('login_data')['emp_id']; 
        $string_emp = $this->db->escape($emp_id);  
    
        $draw = intval($this->input->post("draw"));  
        $start = intval($this->input->post("start")); 
        $length = intval($this->input->post("length")); 
        $order = $this->input->post("order");  
        $search = $this->input->post("search"); 
        $search = $this->db->escape_str($search['value']);  
    
        $col = 0;  
        $dir = "";  
        
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];  
                $dir = $o['dir'];  
            }
        }
    
        if ($dir != "asc" && $dir != "desc") {
            $dir = "asc";  
        }
    
        $valid_columns = array(
            0 => 'disable_date', 
            1 => 'stamp' 
        );
    
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];  
        }
    
        // Enhanced search logic to search multiple fields
        if (!empty($search)) {
            $search_query = "AND (ticket_id LIKE '%" . $search . "%' OR requested_by LIKE '%" . $search . "%' OR department LIKE '%" . $search . "%')";
        } else {
            $search_query = "AND requested_by_id = " . $this->db->escape($user_id);
        }        
    
        // Fetch the count of records with the search filter
        $count_array = $this->db->query("
            SELECT * FROM service_request_tracc_request 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Approved', 'Returned') AND requested_by_id = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Approved', 'Returned')) 
            " . $search_query
        );
        $length_count = $count_array->num_rows();
        
        $data = array();
        
        $strQry = $this->db->query("
            SELECT * FROM service_request_tracc_request
            WHERE status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Approved', 'Returned')
            AND requested_by_id = " . $user_id . " " . $search_query . " ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length);


        if ($strQry->num_rows() > 0) {
            foreach ($strQry->result() as $rows) {
                // Status label
                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'In Progress':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed': 
                        $label_class = 'label-info';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'Approved':
                        $label_class = 'label-success';
                        break;
                    case 'Returned':
                        $label_class = 'label-info';
                        break;
                }
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';
    
                // Approval status label
                $app_stat_class = '';
                switch ($rows->approval_status) {
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                    case 'Returned':
                        $app_stat_class = 'label-info';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';
    
                // IT approval status label
                $it_stat_class = '';
                switch ($rows->it_approval_status) {
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class = 'label-primary';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;    
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

                $priority_class = '';
                switch($rows->priority) {
                    case 'High':
                        $priority_class = 'label-danger';
                        break;
                    case 'Medium':
                        $priority_class = 'label-warning';
                        break;
                    case 'Low':
                        $priority_class = 'label-info';
                        break;
                }
                $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';
                // Ticket data
                $trf_ticket[] = "<a href='" . base_url() . "sys/users/details/concern/tracc_request/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
                $name[] = $rows->requested_by;
                $subject[] = $rows->subject;
            }
    
            for ($i = 0; $i < count($trf_ticket); $i++) {
                $data[] = array(
                    $trf_ticket[$i],
                    $name[$i],         
                    $subject[$i],       
                    $priority_label[$i],
                    $status_label[$i],  
                    $app_stat_label[$i],
                    $it_stat_label[$i] 
                );
            }
        }
    
        $output = array(
            "draw" => $draw,  
            "recordsTotal" => $length_count,  
            "recordsFiltered" => $length_count, 
            "data" => $data 
        );
    
        echo json_encode($output);
        exit();
    }

    //DATATABLE for ADMIN (TRACC REQUEST)
    public function all_tickets_tracc_request() {
        $user_id = $this->session->userdata('login_data')['user_id'];
        $emp_id = $this->session->userdata('login_data')['emp_id'];
        $string_emp = $this->db->escape($emp_id);

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $this->db->escape_str($search['value']);

        $col = 0;
        $dir = "";

        if (!empty($order)){
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc"){
            $dir = "asc";
        }

        $valid_columns = array(
            0 => 'disable_date',  
            1 => 'stamp'
        );

        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];  
        }

        if (!empty($search)) {
            $search_query = "AND (recid LIKE '%" . $search . "%' OR requested_by LIKE '%" . $search . "%' OR department LIKE '%" . $search . "%')";
        } else {
            $search_query = "";
        }

        $count_array = $this->db->query("
            SELECT * FROM service_request_tracc_request
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Returned', 'Approved') AND requested_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Returned', 'Approved')) 
            " . $search_query
        );
        $length_count = $count_array->num_rows();

        $data = array();
        $strQry = $this->db->query("
            SELECT * FROM service_request_tracc_request 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Returned', 'Approved') AND requested_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Returned', 'Approved')) 
            " . $search_query . " 
            ORDER BY recid " . $dir . " LIMIT " . $start . ", " . $length
        );

        if ($strQry->num_rows() > 0){
            foreach ($strQry->result() as $rows){
                if ($rows->it_approval_status == 'Resolved'){
                    $status = 'Closed';
                }

                $label_class = '';
                switch ($rows->status) {
                    case 'Open':
                        $label_class = 'label-primary';
                        break;
                    case 'Rejected':
                        $label_class = 'label-danger';
                        break;
                    case 'In Progress':
                        $label_class = 'label-warning';
                        break;
                    case 'Resolved':
                        $label_class = 'label-success';
                        break;
                    case 'Closed':
                        $label_class = 'label-primary';
                        break;
                    case 'Returned':
                        $label_class = 'label-info';
                        break;
                    case 'Approved':
                        $label_class = 'label-success';
                        break;
                }
                $status_label[] = '<span class="label ' . $label_class . '">' . $rows->status . '</span>';

                $app_stat_class = '';
                switch ($rows->approval_status){
                    case 'Approved':
                        $app_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $app_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $app_stat_class = 'label-danger';
                        break;
                    case 'Returned':
                        $app_stat_class = 'label-info';
                        break;
                }
                $app_stat_label[] = '<span class="label ' . $app_stat_class . '">' . $rows->approval_status . '</span>';

                $it_stat_class = '';
                switch ($rows->it_approval_status){
                    case 'Approved':
                        $it_stat_class = 'label-success';
                        break;
                    case 'Pending':
                        $it_stat_class = 'label-warning';
                        break;
                    case 'Rejected':
                        $it_stat_class = 'label-danger';
                        break;
                    case 'Resolved':
                        $it_stat_class = 'label-primary';
                        break;
                    case 'Closed':
                        $it_stat_class = 'label-info';
                        break;
                }
                $it_stat_label[] = '<span class="label ' . $it_stat_class . '">' . $rows->it_approval_status . '</span>';

                $priority_class = '';
                switch($rows->priority) {
                    case 'High':
                        $priority_class = 'label-danger';
                        break;
                    case 'Medium':
                        $priority_class = 'label-warning';
                        break;
                    case 'Low':
                        $priority_class = 'label-info';
                        break;
                }
                $priority_label[] = '<span class="label ' . $priority_class . '">' . $rows->priority . '</span>';

                $tickets[] = "<a href='" . base_url() . "sys/admin/approved/" . $rows->subject . "/" . $rows->ticket_id . "'>" . $rows->ticket_id ."</a>";
                $name[] = $rows->requested_by;
                $subject[] = $rows->subject;

                if($rows->approval_status === "Pending" || $rows->approval_status === "Returned"){
                    $action[] = '<span class="label">' . '<a class="approve-ticket" data-id="'.$rows->recid.'" data-requestor="'.$rows->requested_by.'" data-department="'.$rows->department.'" data-concern="'.$rows->complete_details.'"><i class="fa fa-check"></i></a>' . '</span>';
                }else{
                    $action[] = '<span class="label"></span>';
                }
            }

            for ($i = 0; $i < count($tickets); $i++){
                $data[] = array(
                    $tickets[$i],
                    $name[$i],
                    $subject[$i],
                    $priority_label[$i],
                    $status_label[$i],
                    $app_stat_label[$i],
                    $it_stat_label[$i],
                    $action[$i]
                );
            }
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $length_count,
            "recordsFiltered" => $length_count,
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }
    
    // Kevin's code 
    public function print_tickets_msrf() {

        // Get start and end date input.
        $startDate = $this->input->post('start_date');
        $endDate = $this->input->post('end_date');
        $status = $this->input->post('status');

        // Get columns ticket_id, requestor_name, department, date_requested, date_needed, asset_code, and status from service_request_msrf table.
        $this->db->select('ticket_id, requestor_name, department, date_requested, date_needed, asset_code, status');
        $this->db->from('service_request_msrf');

        // Check if status variable has a value.
        if($status) {
            $this->db->where('status', $status);
        }

        // If start and end date is provided, add where clauses to filter the results.
        if($startDate && $endDate) {
            $this->db->where('created_at >=', $startDate);
            $this->db->where('created_at <=', $endDate);
        } else if ($startDate) {
            $this->db->where('created_at >=', $startDate);
        } else if ($endDate) {
            $this->db->where('created_at <=', $endDate);
        }

        // Place results inside a variable.
        $query = $this->db->get();
        $data = $query->result_array();

        // Format Date. Three letter month, date, and year format. Set date to blank if the date is not given.
        $formattedData = [];
        foreach($data as $row) {
            if($row['date_needed'] == '0000-00-00') {
                $row['date_needed'] = '';
                $row['date_requested'] = date('M j, Y', strtotime($row['date_requested']));
            } elseif ($row['date_requested'] == '0000-00-00') {
                $row['date_requested'] = '';
                $row['date_needed'] = date('M j, Y', strtotime($row['date_needed']));
            } else {
                $row['date_requested'] = date('M j, Y', strtotime($row['date_requested']));
                $row['date_needed'] = date('M j, Y', strtotime($row['date_needed']));
            }

            // insert formated date to an array.
            $formattedData[] = $row;
        }


        // Place needed information inside an array variable.
        $output = array(
            "draw" => intval($this->input->post('draw')),
            "data" => $formattedData
        );

        echo json_encode($output);
        exit();
    }
    
    public function print_tickets_tracc_concern() {
        
        // Get start and end date input.
        $startDate = $this->input->post('start_date');
        $endDate = $this->input->post('end_date');
        $status = $this->input->post('status');

        // Get columns control_number, reported_by, reported_date, resolved_date, and status from service_request_tracc_concern.
        $this->db->select('control_number, reported_by, reported_date, resolved_date, status');
        $this->db->from('service_request_tracc_concern');

        // Check if status variable is not null.
        if($status) {
            $this->db->where('status', $status);
        }

        // If start and end date input exists, an additional where clause is added to the query.
        if($startDate && $endDate) {
            $this->db->where('created_at >=', $startDate);
            $this->db->where('created_at <=', $endDate);
        } elseif ($startDate) {
            $this->db->where('created_at >=', $startDate);
        } elseif ($endDate) {
            $this->db->where('created_at <=', $endDate);
        }

        // Place the values from the query to a variable.
        $query = $this->db->get();
        $data = $query->result_array();

        // Format Date. Three letter month, date, and year format. Set date to blank if date is not given.
        $formattedData = [];
        foreach($data as $row) {
            if($row['reported_date'] == '0000-00-00') {
                $row['reported_date'] = '';
                $row['resolved_date'] = date('M j, Y', strtotime($row['resolved_date']));
            } elseif ($row['resolved_date'] == '0000-00-00') {
                $row['resolved_date'] = '';
                $row['reported_date'] = date('M j, Y', strtotime($row['reported_date']));
            } else {
                $row['reported_date'] = date('M j, Y', strtotime($row['reported_date']));
                $row['resolved_date'] = date('M j, Y', strtotime($row['resolved_date']));
            }

            // Store the formated information inside an array.
            $formattedData[] = $row;
        }

        // Insert all the data from the database into an array.
        $output = array(
            "draw" => intval($this->input->post('draw')),
            "data" => $formattedData
        );

        // Return the array in json format.
        echo json_encode($output);
        exit();
    }

    public function print_tickets_tracc_request() {
        // Get start and end date.
        $startDate = $this->input->post('start_date');
        $endDate = $this->input->post('end_date');

        // Get columns ticet_id, requested_by, department, date_requested, company, complete_details, accomplished_by, accomplished_by_date from service_request_tracc_request table.
        $this->db->select('ticket_id, requested_by, department, date_requested, company, complete_details, accomplished_by, accomplished_by_date');
        $this->db->from('service_request_tracc_request');

        // Check if start and end date exists
        if ($startDate && $endDate) {
            $this->db->where('created_at >=', $startDate);
            $this->db->where('created_at <=', $endDate);
        } elseif ($startDate) {
            $this->db->where('created_at >=', $startDate);
        } elseif ($endDate) {
            $this->db->where('created_at <=', $endDate);
        }

        // Store the values from the query to a variable.
        $query = $this->db->get();
        $data = $query->result_array();

        // Format Date. Three letter month, date, and year format. Set date to blank if date is not given.
        $formattedData = [];
        foreach($data as $row) {
            if($row['date_requested'] == '0000-00-00') {
                $row['date_requested'] = '';
                $row['accomplished_by_date'] = date('M j, Y', strtotime($row['accomplished_by_date']));
            } elseif ($row['accomplished_by_date'] == '0000-00-00') {
                $row['accomplished_by_date'] = '';
                $row['date_requested'] = date('M j, Y', strtotime($row['date_requested']));
            } else {
                $row['date_requested'] = date('M j, Y', strtotime($row['date_requested']));
                $row['accomplished_by_date'] = date('M j, Y', strtotime($row['accomplished_by_date']));
            }
            
            // Set line breaks per company.
            $row['company'] = str_replace(',', '<br>', $row['company']);

            // Store data inside an array.
            $formattedData[] = $row;
        }

        // Insert all the data from the database to an array.
        $output = array(
            "draw" => intval($this->input->post('draw')),
            "data" => $formattedData
        );

        // Return the array in json format.
        echo json_encode($output);
        exit();
    }

    public function display_msrf() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $status = $this->input->post('status');

        $this->db->select('ticket_id, status');
        $this->db->from('service_request_msrf');
        $this->db->where('requester_id', $user_id);
        $this->db->order_by('created_at', 'desc');

        if($status) {
            $this->db->where('status', $status);
        }

        $totalRecords = $this->db->count_all_results('', false);

        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url() . "sys/users/details/concern/msrf/" . $row['ticket_id'] . "'>" . $row['ticket_id'] . "</a>";
            switch ($row['status']) {
                case 'Open':
                    $label_class = 'label-primary';
                    break;
                case 'In Progress':
                case 'On going':
                    $label_class = 'label-warning';
                    break;
                case 'Resolved':
                    $label_class = 'label-success';
                    break;
                case 'Closed': 
                    $label_class = 'label-danger';
                    break;
                case 'Rejected':
                    $label_class = 'label-danger';
                    break;
                case 'On Going':
                    $label_class = 'label-success';
                    break;
                case 'Approved':
                    $label_class = 'label-success';
                    break;
                case 'Returned':
                    $label_class = 'label-warning';
                    break;
            }
            $row['status'] = "<span class='label " . $label_class . "'>" . $row['status'] . "</span>";
            $formattedData[] = $row;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $formattedData,
        );

        echo json_encode($output);
        exit();
    }

    public function display_concerns() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $status = $this->input->post('status');

        $this->db->select('control_number, status');
        $this->db->from('service_request_tracc_concern');
        $this->db->where('reported_by_id', $user_id);
        $this->db->order_by('created_at', 'desc');

        if($status) {
            $this->db->where('status', $status);
        }

        $totalRecords = $this->db->count_all_results('', false);

        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['control_number'] = "<a href='" . base_url() . "sys/users/details/concern/tracc_concern/" . $row['control_number'] . "'>" . $row['control_number'] . "</a>";
            switch ($row['status']) {
                case 'Open':
                    $label_class = 'label-primary';
                    break;
                case 'In Progress':
                case 'On going':
                    $label_class = 'label-warning';
                    break;
                case 'Resolved':
                    $label_class = 'label-success';
                    break;
                case 'Closed': 
                    $label_class = 'label-danger';
                    break;
                case 'Rejected':
                    $label_class = 'label-danger';
                    break;
                case 'Done':
                    $label_class = 'label-success';
                    break;
                case 'Approved':
                    $label_class = 'label-success';
                    break;
                case 'Returned':
                    $label_class = 'label-warning';
                    break;
            }
            $row['status'] = "<span class='label " . $label_class . "'>" . $row['status'] . "</span>";
            $formattedData[] = $row;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $formattedData
        );

        echo json_encode($output);
        exit();
    }

    public function display_requests() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $status = $this->input->post('status');

        $this->db->select('ticket_id, status');
        $this->db->from('service_request_tracc_request');
        $this->db->where('requested_by_id', $user_id);
        $this->db->order_by('created_at', 'desc');

        if($status) {
            $this->db->where('status', $status);
        }

        $totalRecords = $this->db->count_all_results('', false);

        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url() . "sys/users/details/concern/tracc_request/" . $row['ticket_id'] . "'>" . $row['ticket_id'] . "</a>";
            switch ($row['status']) {
                case 'Open':
                    $label_class = 'label-primary';
                    break;
                case 'In Progress':
                case 'On going':
                    $label_class = 'label-warning';
                    break;
                case 'Resolved':
                    $label_class = 'label-success';
                    break;
                case 'Closed': 
                    $label_class = 'label-danger';
                    break;
                case 'Rejected':
                    $label_class = 'label-danger';
                    break;
                case 'On Going':
                    $label_class = 'label-success';
                    break;
                case 'Approved':
                    $label_class = 'label-success';
                    break;
                case 'Returned':
                    $label_class = 'label-warning';
                    break;
            }
            $row['status'] = "<span class='label " . $label_class . "'>" . $row['status'] . "</span>";
            $formattedData[] = $row;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $formattedData
        );

        echo json_encode($output);
        exit();
    }

    public function get_customer_request_form() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $this->db->select('fname, mname, lname');
        $this->db->where('recid', $user_id);
        $this->db->from('users');
        $user_data = $this->db->get()->result_array();

        $name = $user_data[0]['fname'] . ' ' . $user_data[0]['mname'] . ' ' . $user_data[0]['lname'];
        
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));

        $this->db->select('recid, ticket_id, remarks');
        $this->db->from('tracc_req_customer_req_form');
        $this->db->where('requested_by', $name);
        $this->db->order_by('created_at', 'desc');

        $totalRecords = $this->db->count_all_results('', false);

        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url() . "sys/users/details/concern/customer_req/" . $row['recid'] . "'>" . $row['ticket_id'] . "</a>";
            $formattedData[] = $row;
        }

        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $formattedData,
        );

        echo json_encode($output);
        exit();
    }
}