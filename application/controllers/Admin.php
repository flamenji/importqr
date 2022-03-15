<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct(){
        //return;
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->model('m_vote');
        #$this->load->model('m_user');
	if (null === $this->session->userdata('nik') or $this->session->userdata('nik') != 'admin') {
                        redirect('main');
                }
        
	}

	public function index()
	{
        $result_1 = $this->m_vote->get_officer_votes();
        $result_2 = $this->m_vote->get_manager_votes();
        $result_3 = $this->m_vote->get_all_votes_data();

        $data = array(
                'vote_officer'  => $result_1,
                'vote_manager'  => $result_2,
                'data'          => $result_3
            );

		$this->load->view('admin', $data);
	}
}   
