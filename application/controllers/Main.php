<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	function __construct(){
        //return;
        parent::__construct();
        // $this->clear_cache();

        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->model('m_qr');
        $this->load->model('m_user');
        // $this->session->sess_destroy();
        // echo $this->session->userdata('nik');

        if (null !== $this->session->userdata('username')) {
	        redirect('home');
        }
        $this->is_logged_in();
        $this->clear_cache();
	}

	function is_logged_in() 
    {
        if ($this->session->userdata('nik'))
        {
            redirect('home');
        }
    }

	function clear_cache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }

	public function index()
	{
		$this->load->view('login');
	}

	public function login_func()
	{
		$nik = $this->input->post('nik');
		$password = $this->input->post('password');

		if($nik == "daman" and $password == 'namad'){
			$session = array (
							'username' 	=> 'DAMAN',
							'name' 	=> 'DAMAN Yang Ganteng',
							'email' => 'apapun@email.com',
							'privilege' => '1'
						);

			$this->session->set_userdata($session);
			echo "<script type='text/javascript'>

			        location = '".site_url()."home';
			      </script>";

		}
		else{
				echo "<script>alert('GAGAL');</script>";
				echo "<script type='text/javascript'>
				        alert('Wrong username or password Please try again');
				        location = '".site_url()."main';
				      </script>";
		}
	}

	public function logout() {
		$this->session->sess_destroy();
		$this->session->unset_userdata('nik');
		$this->session->unset_userdata('nama');
		$this->session->unset_userdata('email');
  		$this->session->sess_destroy();

		echo 	"<script type='text/javascript'>
		        location = '".site_url()."main';
		      	</script>";
    }

}
