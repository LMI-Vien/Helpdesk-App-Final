<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tickets_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->library('user_agent');
	}

	function get_data_list($table = NULL, $query = null, $limit = NULL, $start = NULL, $select = NULL, $order_field = null, $order_type = asc, $join = null, $group = null){
		$this->db->limit($limit, $start);
		$this->db->select($select);
		if($query != null){
			$this->db->where($query);
		}
		
		$this->db->from($table);

		if($join != null){
			foreach ($join as $key => $vl) {
				$this->db->join($vl['table'],$vl['query'],$vl['type']);
			};
		}
		if($order_field != null){
			$this->db->order_by($order_field, $order_type);
		}
		if($group != null){
			$this->db->group_by($group);
		}
		$q = $this->db->get();
		return $q->result();
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

	function update_data($table = NULL,$data = NULL,$field = NULL,$where = NULL){
		$this->db->where($field, $where);
		$this->db->update($table, $data);
		$updated_status = $this->db->affected_rows();
		if($updated_status):
		    return "success";
		else:
		    return "failed";
		endif;
	}
}
?>