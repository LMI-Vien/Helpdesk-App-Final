<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersDashboard_model extends CI_Model {
    public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}
}
?>