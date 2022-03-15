<?php
	class M_user extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('date');
	}

	public function get_user($data=null){
		if($data){
			$this->db->where($data);
		}
        $query = $this->db->get('user');

        return ($query->num_rows() > 0)?$query->result_array():FALSE;
	}

	public function update_user($where=null,$data=null){
        $orig_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        if($where){
			$this->db->where($where);
		}

		// print_r($where);return;
		$this->db->update('user', $data);
		$db_error = $this->db->error();

		if ($db_error) {
			$this->db->db_debug = $orig_debug;
		   	return $db_error;
		} 
		else {
		   	$this->db->db_debug = $orig_debug;
		   	return 1;
		}
	}

	public function insert_user($data, $type){
        $orig_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
		if($type=="single"){
			$result = $this->db->insert('user', $data);	
		}
		else if($type=="bulk"){
			$result = $this->db->insert_batch('user', $data);	
		}

	    $db_error = $this->db->error();

		if ($db_error) {
			$this->db->db_debug = $orig_debug;
		   	return $db_error;
		} 
		else {
		   	$this->db->db_debug = $orig_debug;
		   	return 1;
		}
	}

	public function delete_user($where=null,$data=null){
        $orig_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
		if($where){
			$this->db->where($where);
		}
		$this->db->delete('user');
		$db_error = $this->db->error();
		if ($db_error) {
			$this->db->db_debug = $orig_debug;
		   	return $db_error;
		} 
		else {
		   	$this->db->db_debug = $orig_debug;
		   	return 1;
		}
	}

	public function fetch_user($limit, $start, $where=null, $like) {
        // $this->db->select(*);
        $this->db->from('user');
        // $this->db->where(array('regional' => 'Regional 5'));

        if($where){
        	$this->db->where($where);
        }
        if($like or $like != ''){
        	$this->db->like($like);
        	$this->db->order_by('nik');
        }
        $this->db->order_by('nik');
        
        if($start && $limit){
            $this->db->limit($limit,$start);
        }
        else if(!$start && $limit){
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        // print_r($query->result_array());return;
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
   	}
	
}
