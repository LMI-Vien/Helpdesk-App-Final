<?php
defined('BASEPATH') OR exit('No direct scripts access allowed');

class AdminCutoff_controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('AdminCutoff_model');
    }

    public function admin_cutoff($active_menu = 'cutoff') {
        if($this->session->userdata('login_data')) {
            $user_details = $this->Main_model->user_details();

            if ($user_details[0] == "ok") {
                $sid = $this->session->session_id;
                $data['user_details'] = $user_details[1];
                $data['bypass'] = $this->AdminCutoff_model->get_cutoff_bypass();

                $data['unopenedMSRF'] = $this->Main_model->get_unopened_msrf_tickets();
				$data['unopenedTraccConcern'] = $this->Main_model->get_unopened_tracc_concerns();
				$data['unopenedTraccRequest'] = $this->Main_model->get_unopened_tracc_request();
                
                $allowed_menus = ['dashboard', 'system_administration', 'other_menu'];

                $data['active_menu'] = $active_menu;
                $data['date'] = date("Y-m-d");
                $data['schedule'] = $this->AdminCutoff_model->get_schedule();

                $this->load->view('admin/header', $data);
                $this->load->view('admin/sidebar', $data);
                $this->load->view('admin/admin_Cutoff/admin_cutoff', $data);
                $this->load->view('admin/footer');
            } else {
                $this->session->Set_flashdata('error', 'Error fetching user information');
                redirect("sys/authentication");
            }
        } else {
            $this->session->sess_destroy();
            $this->session->Set_flashdata('error', 'Session expired. Please login again.');
            redirect("sys/authentication");
        }

    }

    public function set_cutoff() {
        $this->AdminCutoff_model->set_cutoff();
        redirect('sys/admin/cutoff');
    }
    
    public function bypass() {
        $this->AdminCutoff_model->bypass();
        redirect('sys/admin/cutoff');
    }

    public function schedule_cutoff() {
        $this->AdminCutoff_model->schedule_cutoff();
        redirect('sys/admin/cutoff');
    }

    public function edit_schedule($recid) {
        $this->AdminCutoff_model->edit_schedule($recid);
        redirect('sys/admin/cutoff');
    }

    public function delete_schedule($recid) {
        $this->AdminCutoff_model->delete_schedule($recid);
        redirect('sys/admin/cutoff');
    }
}