<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MSRFAdminModel extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}
}

?>