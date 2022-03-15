<?php
	class M_qr extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('date');
	}

	public function insert_qr($data, $type){
        $orig_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
		if($type=="single"){
			$result = $this->db->insert('qr_code', $data);	
		}
		else if($type=="bulk"){
			$result = $this->db->insert_batch('qr_code', $data);	
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

}
