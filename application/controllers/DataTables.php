<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataTables extends CI_Controller { 
    public function __construct() {
        parent::__construct();
        $this->load->database();
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
            $search_query = "WHERE active != 0 AND (emp_id LIKE '%".$search."%' OR fname LIKE '%".$search."%' OR mname LIKE '%".$search."%' OR lname LIKE '%".$search."%' OR email LIKE '%".$search."%' OR username LIKE '%".$search."%' OR role LIKE '%".$search."%' OR department_description LIKE '%".$search."%')";
        } else {
            $search_query = "WHERE active != 0";  
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

                $rawRole = strtoupper(trim($rows->role)); 
                switch ($rawRole) {
                    case 'L1':
                        $mapped_role = 'User';
                        break;
                    case 'L2':
                        $mapped_role = 'Supervisors/Managers';
                        break;
                    case 'L3':
                        $mapped_role = 'ICT';
                        break;
                    default:
                        $mapped_role = $rows->role;
                }
                $role[] = $mapped_role;
    
                if ($rows->status == 1) {
                    $btn_action[] = "<button class='btn btn-warning btn-sm btn_lock lock_btn' data-empid='".$rows->recid."'><i class='fa fa-power-off'></i> Lock Account</button>";
                } else {
                    $btn_action[] = "<button class='btn btn-warning btn-sm btn_lock unlock_btn' data-empid='".$rows->recid."'><i class='fa fa-power-off'></i> Unlock Account</button>";
                }
                $btn_edit[] = "<a href='".base_url()."admin/update/employee/".$rows->recid."' class='btn btn-success btn-sm btn-edit'><i class='fa fa-pencil'></i> Update Details</a>";
                // $btn_delete[] = "<button class='btn btn-danger btn-sm btn-delete' data-toggle='modal' data-target='#UsersDeleteModal' data-id='".$rows->recid."'>
                //  <i class='fa fa-trash'></i> Delete</button>";
                $btn_active[] = "<button class='btn btn-info btn-sm btn-delete' data-toggle='modal' data-target='#UsersUpdateActiveModal' data-id='".$rows->recid."'>
                <i class='fa fa-pen'></i>InActive</button>";

            }
    
            for ($i = 0; $i < count($bid); $i++) {
                $data[] = array($emp_id[$i], $full_name[$i], $email[$i], $position[$i], $username[$i], $role[$i], $btn_action[$i] . $btn_edit[$i] . $btn_active[$i]);
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
            2 => 'dept_code',
            3 => 'manager_id',
            4 => 'sup_id',
            5 => 'status'
        );
    
        $order = isset($valid_columns[$col]) ? $valid_columns[$col] : 'recid';

        $base_where = "WHERE status = 1";
    
        $search_query = "";
        if (!empty($search)) {
            $search_query = "WHERE (recid LIKE '%".$search."%' OR dept_desc LIKE '%".$search."%' OR manager_id LIKE '%".$search."%' OR sup_id LIKE '%".$search."%')";
        }
    
        // Get the total number of records in the 'departments' table
        $count_query = $this->db->query("SELECT COUNT(*) AS count FROM departments {$base_where}");
        $total_records = $count_query->row()->count;
    
        // Get the total number of records that match the search criteria
        $filtered_query = $this->db->query("SELECT COUNT(*) AS count FROM departments {$base_where} " . $search_query);
        $filtered_records = $filtered_query->row()->count;
    
        // Fetch the records based on search, order, and pagination parameters
        $data_query = $this->db->query("SELECT * FROM departments {$base_where} " . $search_query . " ORDER BY " . $order . " " . $dir . " LIMIT " . $start . ", " . $length);
    
        $data = array();
    
        if ($data_query->num_rows() > 0) {
            foreach ($data_query->result() as $row) {
                // Create Edit and Delete buttons
                $btn_edit = "<a href='".base_url()."admin/update/department/".$row->recid."' class='btn btn-success btn-sm btn-edit'><i class='fa fa-pencil'></i> Edit</a>";
                $btn_delete = "<button class='btn btn-danger btn-sm btn-delete' data-toggle='modal' data-target='#deleteModal' data-id='".$row->recid."'>
               <i class='fa fa-trash'></i> Delete</button>";

                $data[] = array(
                    $row->recid,
                    $row->dept_desc,
                    $row->dept_code,
                    $row->manager_id,
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
            WHERE (status IN ('Open', 'In Progress', 'Resolved', 'Approved', 'Returned') AND assigned_it_staff = " . $string_emp . ") 
            OR (status IN ('Open', 'In Progress', 'Resolved', 'Rejected', 'Approved', 'Returned') AND requester_id = " . $user_id . ") " 
            . $search_query
        );
        $length_count = $count_array->num_rows();  
    
        $data = array();
        $strQry = $this->db->query("
            SELECT * FROM service_request_msrf 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Approved', 'Returned') AND assigned_it_staff = " . $string_emp . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Rejected', 'Approved', 'Returned') AND requester_id = " . $user_id . ") " 
            . $search_query . 
            " ORDER BY recid DESC LIMIT " . $start . ", " . $length
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
    
                $tickets[] = "<a href='" . base_url() . "users/details/concern/msrf/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
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
        $user_details = $this->Main_model->user_details();
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

        $dept = "";
        if ($user_details[1]['role'] != "L3") {
            $dept = " AND dept_id = '" . $user_details[1]['dept_id'] . "'";
        } else {
            $dept = " AND status != 'Open'";
        }
    
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
            0 => 'ticket_id',
            1 => 'date_requested',
            2 => 'requestor_name',
            3 => 'subject',
            4 => 'priority',
            5 => 'status',
            6 => 'approval_status',
            7 => 'it_approval_status',
        );

        $order_col = isset($valid_columns[$col]) ? $valid_columns[$col] : 'recid';
        if ($order_col === null) { 
            $order_col = 'recid';
            $dir = 'desc';
        }
    
        // SEARCH 
        $search_query = "";
        if (!empty($search)) {
            $search_query = "AND (ticket_id LIKE '%" . $search . "%' OR requestor_name LIKE '%" . $search . "%' OR subject LIKE '%" . $search . "%' OR department LIKE '%" . $search . "%')";
        }
        
        $count_query = "SELECT * FROM service_request_msrf WHERE status IN ('Open', 'In Progress', 'Resolved', 'Approved', 'Returned') " . $search_query . $dept . " ORDER BY recid";
        $count_array = $this->db->query($count_query);
        $length_count = $count_array->num_rows();
    
        $strQry = "SELECT * FROM service_request_msrf 
           WHERE status IN ('Open', 'In Progress', 'Resolved', 'Approved', 'Returned') " 
           . $search_query . $dept . 
           " ORDER BY " . $order_col . " " . $dir . ", recid DESC 
             LIMIT " . $start . ", " . $length;


        $data_query = $this->db->query($strQry);
        $data = array();

        if ($data_query->num_rows() > 0) {
            foreach ($data_query->result() as $rows) {
                $opened = explode(',', $rows->opened);

                if(!in_array($user_id, $opened)) {
                    $date_requested[] = "<b>" . date('M-d-Y', strtotime($rows->date_requested)) . "</b>";
                    $name[] = "<b>" . $rows->requestor_name . "</b>";
                    $subject[] = "<b>" . $rows->subject . "</b>";
                    $status[] = "<b>" . $rows->status . "</b>";
                    
                    // Generate a clickable link for the ticket ID.
                    $tickets[] = "<a href='" . base_url() . "admin/approved/" . $rows->subject . "/" . $rows->ticket_id . "'><b>" . $rows->ticket_id . "</b></a>";
                } else {
                    $date_requested[] = date('M-d-Y', strtotime($rows->date_requested));
                    $name[] = $rows->requestor_name;
                    $subject[] = $rows->subject;
                    $status[] = $rows->status; 
                    
                    // Generate a clickable link for the ticket ID.
                    $tickets[] = "<a href='" . base_url() . "admin/approved/" . $rows->subject . "/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
                }

                $bid[] = $rows->recid;
                $ticket[] = $rows->ticket_id;
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

                if($rows->approval_status === "Pending" || $rows->approval_status === "Returned"){
                    $action[] = '<span class="label">' . '<a class="approve-ticket" data-id="'.$rows->recid.'" data-module="msrf" data-requestor="'.$rows->requestor_name.'" data-department="'.$rows->department.'" data-concern="'.htmlentities($rows->details_concern).'" data-date-needed="'.$rows->date_needed.'"><i class="fa fa-check"></i></a>' . '</span>';
                }else{
                    $action[] = '<span class="label"></span>';
                }
            }

            for ($i = 0; $i < count($bid); $i++) {
                $data[] = array(
                    $tickets[$i],
                    $date_requested[$i],
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
            AND reported_by_id = " . $user_id . " " . $search_query . " ORDER BY recid DESC LIMIT " . $start . ", " . $length);


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
                $control_number[] = "<a href='" . base_url() . "users/details/concern/tracc_concern/" . $rows->control_number . "'>" . $rows->control_number . "</a>";
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
        $user_details = $this->Main_model->user_details();
        $user_id = $this->session->userdata('login_data')['user_id'];  
        $emp_id = $this->session->userdata('login_data')['emp_id'];  
        $string_emp = $this->db->escape($emp_id);  

        $include_assignee = isset($user_details[1]['role']) && $user_details[1]['role'] === 'L3';

        $draw = intval($this->input->post("draw")); 
        $start = intval($this->input->post("start"));  
        $length = intval($this->input->post("length"));  
        $order = $this->input->post("order");  
        $search = $this->input->post("search"); 
        $search = $this->db->escape_str($search['value']); 
    
        $col = 0; 
        $dir = "";
        $dept = "";

        if ($user_details[1]['role'] != "L3") {
            $dept = " AND dept_id = '" . $user_details[1]['dept_id'] . "'";
        } else {
            $dept = " AND status != 'Open'";
        }

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
            0 => 'control_number', 
            1 => 'reported_date',
            2 => 'reported_by',
            3 => 'subject',
            4 => 'priority',
            5 => 'company',
            6 => 'status',
            7 => 'approval_status',
            8 => 'it_approval_status',
        );

        if ($include_assignee) {
            $valid_columns[9] = 'ict_assigned';
        }

        $action_index = $include_assignee ? 10 : 9;

        $order_col = 'recid';
        if (isset($valid_columns[$col]) && $col !== $action_index) {
            $order_col = $valid_columns[$col];
        }
    
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
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Approved', 'Returned') AND reported_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Done', 'Approved', 'Returned')) 
            " . $search_query . $dept
        );
        $length_count = $count_array->num_rows();

        
        $data = array();
        $strQry = $this->db->query("
            SELECT * FROM service_request_tracc_concern 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Approved', 'Returned') AND reported_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Done', 'Approved', 'Returned')) 
            " . $search_query . $dept . " 
            ORDER BY " . $order_col . " " . $dir . ", recid DESC 
            LIMIT " . $start . ", " . $length
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

                if (!empty($rows->ict_assigned)) {
                    $ict_assigned_label[] = '<span class="label label-default">' . htmlspecialchars($rows->ict_assigned) . '</span>';
                } else {
                    $ict_assigned_label[] = '<span class="label label-default">Unassigned</span>';
                }
    
                $opened = explode(',', $rows->opened);

                if(!in_array($user_id, $opened)) {
                    $tickets[] = "<a href='" . base_url() . "admin/approved/" . $rows->subject . "/" . $rows->control_number . "'><b>" . $rows->control_number . "</b></a>";
                    $name[] = "<b>" . $rows->reported_by . "</b>";
                    $subject[] = "<b>" . $rows->subject . "</b>";
                    $reported_date[] = "<b>" . date('M-d-Y', strtotime($rows->reported_date)) . "</b>";
                } else {
                    $tickets[] = "<a href='" . base_url() . "admin/approved/" . $rows->subject . "/" . $rows->control_number . "'>" . $rows->control_number . "</a>";
                    $name[] = $rows->reported_by;
                    $subject[] = $rows->subject;
                    $reported_date[] = date('M-d-Y', strtotime($rows->reported_date));
                }

                if($rows->approval_status === "Pending" || $rows->approval_status === "Returned"){
                    $action[] = '<span class="label">' . '<a class="approve-ticket" data-id="'.$rows->recid.'" data-module="tracc_concern" data-reported-by="'.$rows->reported_by.'" data-concern="'.htmlentities($rows->tcr_details).'"><i class="fa fa-check"></i></a>' . '</span>';
                }else{
                    $action[] = '<span class="label"></span>';
                }
            }
    
            for ($i = 0; $i < count($tickets); $i++) {
                $row = array(
                    $tickets[$i],
                    $reported_date[$i],
                    $name[$i],
                    $subject[$i],
                    $priority_label[$i],
                    $comp_label[$i],
                    $status_label[$i],
                    $app_stat_label[$i],
                    $it_stat_label[$i]
                );

                if ($include_assignee) {
                    $row[] = $ict_assigned_label[$i];
                }

                // always append Action last
                $row[] = $action[$i];

                // push row into data
                $data[] = $row;
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
            AND requested_by_id = " . $user_id . " " . $search_query . " ORDER BY recid DESC LIMIT " . $start . ", " . $length);


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
                $trf_ticket[] = "<a href='" . base_url() . "users/details/concern/tracc_request/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
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
        $user_details = $this->Main_model->user_details();
        $user_id = $this->session->userdata('login_data')['user_id'];
        $emp_id = $this->session->userdata('login_data')['emp_id'];
        $string_emp = $this->db->escape($emp_id);

        $include_assignee = isset($user_details[1]['role']) && $user_details[1]['role'] === 'L3';

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $this->db->escape_str($search['value']);

        $col = 0;
        $dir = "";
        $dept = "";

        if($user_details[1]['role'] != "L3") {
            $dept = " AND department_id = '" . $user_details[1]['dept_id'] . "'";
        } else {
            $dept = " AND status != 'Open'";
        }

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
            0 => 'ticket_id',  
            1 => 'date_requested',
            2 => 'requested_by',
            3 => 'subject',
            4 => 'priority',
            5 => 'status',
            6 => 'approval_status',
            7 => 'it_approval_status',
        );

        if ($include_assignee) {
            $valid_columns[8] = 'ict_assigned';
        }

        $action_index = $include_assignee ? 9 : 8;

        $order_col = 'recid';
        if (isset($valid_columns[$col]) && $col !== $action_index) {
            $order_col = $valid_columns[$col];
        }

        if (!empty($search)) {
            $search_query = "AND (recid LIKE '%" . $search . "%' OR requested_by LIKE '%" . $search . "%' OR department LIKE '%" . $search . "%')";
        } else {
            $search_query = "";
        }

        $count_array = $this->db->query("
            SELECT * FROM service_request_tracc_request
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Returned', 'Approved') AND requested_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Returned', 'Approved')) 
            " . $search_query . $dept
        );
        $length_count = $count_array->num_rows();

        $data = array();
        $strQry = $this->db->query("
            SELECT * FROM service_request_tracc_request 
            WHERE (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Returned', 'Approved') AND requested_by = " . $user_id . ") 
            OR (status IN ('Open', 'In Progress', 'On going', 'Resolved', 'Returned', 'Approved')) 
            " . $search_query . $dept . " 
            ORDER BY " . $order_col . " " . $dir . ", recid DESC 
            LIMIT " . $start . ", " . $length
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

                if (!empty($rows->ict_assigned)) {
                    $ict_assigned_label[] = '<span class="label label-default">' . htmlspecialchars($rows->ict_assigned) . '</span>';
                } else {
                    $ict_assigned_label[] = '<span class="label label-default">Unassigned</span>';
                }

                $opened = explode(',', $rows->opened);

                if(!in_array($user_id, $opened)) {
                    $tickets[] = "<a href='" . base_url() . "admin/approved/" . $rows->subject . "/" . $rows->ticket_id . "'><b>" . $rows->ticket_id ."</b></a>";
                    $name[] = "<b>" . $rows->requested_by . "</b>";
                    $subject[] = "<b>" . $rows->subject . "</b>";
                    $date_requested[] = "<b>" . date('M-d-Y', strtotime($rows->date_requested)) . "</b>";
                } else {
                    $tickets[] = "<a href='" . base_url() . "admin/approved/" . $rows->subject . "/" . $rows->ticket_id . "'>" . $rows->ticket_id ."</a>";
                    $name[] = $rows->requested_by;
                    $subject[] = $rows->subject;
                    $date_requested[] = date('M-d-Y', strtotime($rows->date_requested));
                }

                if($rows->approval_status === "Pending" || $rows->approval_status === "Returned"){
                    $action[] = '<span class="label">' . '<a class="approve-ticket" data-id="'.$rows->recid.'" data-module="tracc_request" data-requestor="'.$rows->requested_by.'" data-department="'.$rows->department.'" data-concern="'.htmlentities($rows->complete_details).'"><i class="fa fa-check"></i></a>' . '</span>';
                }else{
                    $action[] = '<span class="label"></span>';
                }
            }

            for ($i = 0; $i < count($tickets); $i++){
                $row = array(
                    $tickets[$i],
                    $date_requested[$i],
                    $name[$i],
                    $subject[$i],
                    $priority_label[$i],
                    $status_label[$i],
                    $app_stat_label[$i],
                    $it_stat_label[$i],
                );

                if ($include_assignee) {
                    $row[] = $ict_assigned_label[$i];
                }

                $row[] = $action[$i];

                $data[] = $row;
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
        $this->db->select('ticket_id, requestor_name, department, date_requested, date_needed, asset_code, status, category, details_concern, assigned_it_staff');
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
        $this->db->select('control_number, module_affected, tcr_details, reported_by, department, reported_date, resolved_by, resolved_date, status');
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
        $this->db->select('ticket_id, requested_by, department, date_requested, company, complete_details, accomplished_by, accomplished_by_date, status');
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
            $row['ticket_id'] = "<a href='" . base_url() . "users/details/concern/msrf/" . $row['ticket_id'] . "'>" . $row['ticket_id'] . "</a>";
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
            $row['control_number'] = "<a href='" . base_url() . "users/details/concern/tracc_concern/" . $row['control_number'] . "'>" . $row['control_number'] . "</a>";
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
            $row['ticket_id'] = "<a href='" . base_url() . "users/details/concern/tracc_request/" . $row['ticket_id'] . "'>" . $row['ticket_id'] . "</a>";
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

    public function get_customer_request_form_rf() {
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
            $row['ticket_id'] = "<a href='" . base_url() . "users/details/concern/customer_req_form/" . $row['recid'] . "'>" . $row['ticket_id'] . "</a>";
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

    public function get_customer_request_form_ss() {
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
        $this->db->from('tracc_req_customer_ship_setup');
        $this->db->where('requested_by', $name);
        $this->db->order_by('created_at', 'desc');

        $totalRecords = $this->db->count_all_results('', false);

        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url() . "users/details/concern/customer_req_ship_setup/" . $row['recid'] . "'>" . $row['ticket_id'] . "</a>";
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

    public function get_customer_request_form_ir() {
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
        $this->db->from('tracc_req_item_request_form');
        $this->db->where('requested_by', $name);
        $this->db->order_by('created_at', 'desc');

        $totalRecords = $this->db->count_all_results('', false);

        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url() . "users/details/concern/customer_req_item_req/" . $row['recid'] . "'>" . $row['ticket_id'] . "</a>";
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

    public function get_customer_request_form_er() {
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
        $this->db->from('tracc_req_employee_req_form');
        $this->db->where('requested_by', $name);
        $this->db->order_by('created_at', 'desc');

        $totalRecords = $this->db->count_all_results('', false);

        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url() . "users/details/concern/customer_req_employee_req/" . $row['recid'] . "'>" . $row['ticket_id'] . "</a>";
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

    public function get_customer_request_form_sr() {
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
        $this->db->from('tracc_req_supplier_req_form');
        $this->db->where('requested_by', $name);
        $this->db->order_by('created_at', 'desc');

        $totalRecords = $this->db->count_all_results('', false);

        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url() . "users/details/concern/customer_req_supplier_req/" . $row['recid'] . "'>" . $row['ticket_id'] . "</a>";
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

    
    public function get_closed_msrf() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search');
        $search = $this->db->escape_str($search['value']);

        $order = $this->input->post('order');
        $columns = $this->input->post('columns');
        $columnIndex = isset($order[0]['column']) ? intval($order[0]['column']) : 0;
        $columnName = isset($columns[$columnIndex]['data']) ? $columns[$columnIndex]['data'] : 'ticket_id';
        $columnSortOrder = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc';

        $this->db->select('*');
        $this->db->from('service_request_msrf');
        $this->db->where('status', 'Closed');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('ticket_id', $search);
            $this->db->or_like('requestor_name', $search);
            $this->db->or_like('priority', $search);
            $this->db->or_like('status', $search);
            $this->db->group_end();
        }

        $totalRecords = $this->db->count_all_results('', false);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url('admin/list/closed_tickets/MSRF/') . $row['ticket_id'] . "'>" . $row['ticket_id'] . "</a>";
            switch ($row['status']) {
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
                case 'Approved':
                    $label_class = 'label-success';
                    break;
                case 'Returned':
                    $label_class = 'label-warning';
                    break;
            }
            $row['status'] = "<span class='label " . $label_class . "'>" . $row['status'] . "</span>";

            switch ($row['priority']) {
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
            $row['priority'] = '<span class="label ' . $priority_class . '">' . $row['priority'] . '</span>';

            switch ($row['approval_status']){
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
            $row['approval_status'] = '<span class="label ' . $app_stat_class . '">' . $row['approval_status'] . '</span>';

            switch ($row['it_approval_status']){
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
            $row['it_approval_status'] = '<span class="label ' . $it_stat_class . '">' . $row['it_approval_status'] . '</span>';

            $formattedData[] = $row;
        }

        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $formattedData
        );

        echo json_encode($output);
        exit();
    }

    public function get_closed_tracc_concern() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search');
        $search = $this->db->escape_str($search['value']);

        $order = $this->input->post('order');
        $columns = $this->input->post('columns');
        $columnIndex = isset($order[0]['column']) ? intval($order[0]['column']) : 0;
        $columnName = isset($columns[$columnIndex]['data']) ? $columns[$columnIndex]['data'] : 'ticket_id';
        $columnSortOrder = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc';

        $this->db->select('*');
        $this->db->from('service_request_tracc_concern');
        $this->db->where('status', 'Closed');
        $this->db->order_by('resolved_date', 'desc');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('control_number', $search);
            $this->db->or_like('reported_by', $search);
            $this->db->or_like('priority', $search);
            $this->db->or_like('approval_status', $search);
            $this->db->group_end();
        }

        $totalRecords = $this->db->count_all_results('', false);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['control_number'] = "<a href='" . base_url('admin/list/closed_tickets/TRACC_CONCERN/') . $row['control_number'] . "'>" . $row['control_number'] . "</a>";
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

            switch ($row['priority']) {
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
            $row['priority'] = '<span class="label ' . $priority_class . '">' . $row['priority'] . '</span>';

            switch ($row['approval_status']){
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
            $row['approval_status'] = '<span class="label ' . $app_stat_class . '">' . $row['approval_status'] . '</span>';

            switch ($row['it_approval_status']){
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
            $row['it_approval_status'] = '<span class="label ' . $it_stat_class . '">' . $row['it_approval_status'] . '</span>';
            
            $formattedData[] = $row;
        }

        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $formattedData
        );

        echo json_encode($output);
        exit();
    }

    public function get_closed_tracc_request() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search');
        $search = $this->db->escape_str($search['value']);

        $order = $this->input->post('order');
        $columns = $this->input->post('columns');
        $columnIndex = isset($order[0]['column']) ? intval($order[0]['column']) : 0;
        $columnName = isset($columns[$columnIndex]['data']) ? $columns[$columnIndex]['data'] : 'ticket_id';
        $columnSortOrder = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc';

        $this->db->select('*');
        $this->db->from('service_request_tracc_request');
        $this->db->where('status', 'Closed');
        $this->db->order_by('accomplished_by_date', 'desc');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('ticket_id', $search);
            $this->db->or_like('requested_by', $search);
            $this->db->or_like('priority', $search);
            $this->db->or_like('approval_status', $search);
            $this->db->group_end();
        }

        $totalRecords = $this->db->count_all_results('', false);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach ($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url('admin/list/closed_tickets/TRACC_REQUEST/') . $row['ticket_id'] . "'>" . $row['ticket_id'] . "</a>";

            switch ($row['status']) {
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
            $row['status'] = "<span class='label " . $label_class . "'>" . $row['status'] . "</span>";

            switch ($row['priority']) {
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
            $row['priority'] = '<span class="label ' . $priority_class . '">' . $row['priority'] . '</span>';

            switch ($row['approval_status']){
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
            $row['approval_status'] = '<span class="label ' . $app_stat_class . '">' . $row['approval_status'] . '</span>';

            switch ($row['it_approval_status']){
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
                    $it_stat_class = 'label-danger';
                    break;
            }
            $row['it_approval_status'] = '<span class="label ' . $it_stat_class . '">' . $row['it_approval_status'] . '</span>';

            $formattedData[] = $row;
        }
        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $formattedData
        );

        echo json_encode($output);
        exit();
    }

    // Admin Creation Ticket for MSRF
    public function create_msrf_ticket_admin() {
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
            " ORDER BY recid DESC LIMIT " . $start . ", " . $length
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
    
                $tickets[] = "<a href='" . base_url() . "admin/details/concern/msrf/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
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

    // DATATABLE for Admin BACKUP (TRACC CONCERN)
    public function create_tracc_concern_ticket_admin() {
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
            AND reported_by_id = " . $user_id . " " . $search_query . " ORDER BY recid DESC LIMIT " . $start . ", " . $length);


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
                $control_number[] = "<a href='" . base_url() . "admin/details/concern/tracc_concern/" . $rows->control_number . "'>" . $rows->control_number . "</a>";
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

    // DATATABLE for USER BACKUP (TRACC REQUEST FORM)
    public function create_tracc_request_ticket_admin() {
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
            AND requested_by_id = " . $user_id . " " . $search_query . " ORDER BY recid DESC LIMIT " . $start . ", " . $length);


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
                $trf_ticket[] = "<a href='" . base_url() . "admin/details/concern/tracc_request/" . $rows->ticket_id . "'>" . $rows->ticket_id . "</a>";
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

    public function get_rejected_msrf() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search');
        $search = $this->db->escape_str($search['value']);

        $order = $this->input->post('order');
        $columns = $this->input->post('columns');
        $columnIndex = isset($order[0]['column']) ? intval($order[0]['column']) : 0;
        $columnName = isset($columns[$columnIndex]['data']) ? $columns[$columnIndex]['data'] : 'ticket_id';
        $columnSortOrder = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc';

        $this->db->select('*');
        $this->db->from('service_request_msrf');
        $this->db->where('status', 'Rejected');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('ticket_id', $search);
            $this->db->or_like('requestor_name', $search);
            $this->db->or_like('priority', $search);
            $this->db->or_like('status', $search);
            $this->db->group_end();
        }

        $totalRecords = $this->db->count_all_results('', false);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url('admin/list/rejected_tickets/MSRF/') . $row['ticket_id'] . "'>" . $row['ticket_id'] . "</a>";
            switch ($row['status']) {
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
                case 'Approved':
                    $label_class = 'label-success';
                    break;
                case 'Returned':
                    $label_class = 'label-warning';
                    break;
            }
            $row['status'] = "<span class='label " . $label_class . "'>" . $row['status'] . "</span>";

            switch ($row['priority']) {
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
            $row['priority'] = '<span class="label ' . $priority_class . '">' . $row['priority'] . '</span>';

            switch ($row['approval_status']){
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
            $row['approval_status'] = '<span class="label ' . $app_stat_class . '">' . $row['approval_status'] . '</span>';

            switch ($row['it_approval_status']){
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
            $row['it_approval_status'] = '<span class="label ' . $it_stat_class . '">' . $row['it_approval_status'] . '</span>';

            $formattedData[] = $row;
        }

        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $formattedData
        );

        echo json_encode($output);
        exit();
    }

        public function get_rejected_tracc_concern() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search');
        $search = $this->db->escape_str($search['value']);

        $order = $this->input->post('order');
        $columns = $this->input->post('columns');
        $columnIndex = isset($order[0]['column']) ? intval($order[0]['column']) : 0;
        $columnName = isset($columns[$columnIndex]['data']) ? $columns[$columnIndex]['data'] : 'ticket_id';
        $columnSortOrder = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc';

        $this->db->select('*');
        $this->db->from('service_request_tracc_concern');
        $this->db->where('status', 'Rejected');
        $this->db->order_by('resolved_date', 'desc');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('control_number', $search);
            $this->db->or_like('reported_by', $search);
            $this->db->or_like('priority', $search);
            $this->db->or_like('approval_status', $search);
            $this->db->group_end();
        }

        $totalRecords = $this->db->count_all_results('', false);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach($data as $row) {
            $row['control_number'] = "<a href='" . base_url('admin/list/rejected_tickets/TRACC_CONCERN/') . $row['control_number'] . "'>" . $row['control_number'] . "</a>";
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

            switch ($row['priority']) {
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
            $row['priority'] = '<span class="label ' . $priority_class . '">' . $row['priority'] . '</span>';

            switch ($row['approval_status']){
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
            $row['approval_status'] = '<span class="label ' . $app_stat_class . '">' . $row['approval_status'] . '</span>';

            switch ($row['it_approval_status']){
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
            $row['it_approval_status'] = '<span class="label ' . $it_stat_class . '">' . $row['it_approval_status'] . '</span>';
            
            $formattedData[] = $row;
        }

        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $formattedData
        );

        echo json_encode($output);
        exit();
    }

    public function get_rejected_tracc_request() {
        $user_id = $this->session->userdata('login_data')['user_id'];

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search');
        $search = $this->db->escape_str($search['value']);

        $order = $this->input->post('order');
        $columns = $this->input->post('columns');
        $columnIndex = isset($order[0]['column']) ? intval($order[0]['column']) : 0;
        $columnName = isset($columns[$columnIndex]['data']) ? $columns[$columnIndex]['data'] : 'ticket_id';
        $columnSortOrder = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc';

        $this->db->select('*');
        $this->db->from('service_request_tracc_request');
        $this->db->where('status', 'Rejected');
        $this->db->order_by('accomplished_by_date', 'desc');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('ticket_id', $search);
            $this->db->or_like('requested_by', $search);
            $this->db->or_like('priority', $search);
            $this->db->or_like('approval_status', $search);
            $this->db->group_end();
        }

        $totalRecords = $this->db->count_all_results('', false);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($length, $start);
        $data = $this->db->get()->result_array();

        $formattedData = [];
        foreach ($data as $row) {
            $row['ticket_id'] = "<a href='" . base_url('admin/list/rejected_tickets/TRACC_REQUEST/') . $row['ticket_id'] . "'>" . $row['ticket_id'] . "</a>";

            switch ($row['status']) {
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
            $row['status'] = "<span class='label " . $label_class . "'>" . $row['status'] . "</span>";

            switch ($row['priority']) {
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
            $row['priority'] = '<span class="label ' . $priority_class . '">' . $row['priority'] . '</span>';

            switch ($row['approval_status']){
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
            $row['approval_status'] = '<span class="label ' . $app_stat_class . '">' . $row['approval_status'] . '</span>';

            switch ($row['it_approval_status']){
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
                    $it_stat_class = 'label-danger';
                    break;
            }
            $row['it_approval_status'] = '<span class="label ' . $it_stat_class . '">' . $row['it_approval_status'] . '</span>';

            $formattedData[] = $row;
        }
        $output = array(
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $formattedData
        );

        echo json_encode($output);
        exit();
    }

}