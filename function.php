<?php
	public function login()
	{
		$nik = $this->input->post('nik');
		$password = $this->input->post('password');
		$portal_auth = $this->LDAP_auth($nik,$password);
		if ($portal_auth !== 0 || $portal_auth !== 0.5){
			$namemail = explode("#un&mail#",$portal_auth);
			$exmail = explode('@',$namemail[1]);
			$session = array (
							'nik' => $nik,
							'nama' => $namemail[0],
							'email' => $namemail[1]
						);
			$this->session->set_userdata($session);
			$this->session->sess_expiration = '7000';
			$this->session->set_flashdata('welcome_message',$session['nama']); 
			redirect('Home');
		}
		else{
			$this->session->set_flashdata('flash_message', 'NIK atau Password salah');
			redirect('Login');
		}
	}
?>