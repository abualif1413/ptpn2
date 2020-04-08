<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authadmin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('backend/M_admin','M_admin');
		$this->load->library('session');
	}

	public function json($data)
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

	public function index()
	{
        if ($this->session->userdata('email')) {
            redirect('Dashboard');
        }else{
            $this->load->view('backend/login/login');
        }
	}

	public function login()
	{
		$this->form_validation->set_rules('email', 'email / Username *', 'required');
		$this->form_validation->set_rules('password', 'password *','required');
        if ($this->form_validation->run() == FALSE){
			$output['error']= validation_errors('<span style="color:red;">','</span><br>');
        }else{
        	$output = array('error' => false);
			$email = $this->security->xss_clean($this->input->post('email'));
			$password = $this->security->xss_clean($this->input->post('password'));
			$select=array(
				'tbl_admin.email',
				'tbl_admin.password',
				'tbl_admin.role',
				'tbl_admin.id',
				'tbl_admin.nama_lengkap',
				'tbl_admin.photo',
				'tbl_admin.token',
			);
			$where=array('email'=>$email);
			$or_where=array('email'=>$email,'nama_lengkap'=>$email);
			$data=$this->M_admin->get_where($select,$where,$or_where,false,false,false);
			if(password_verify($password,$data['password']))
			{	
				$Select=array('tbl_admin.status');
				$Where=array('status'=>'Y','id'=> $data['id']);
				if ($this->M_admin->checkRows($Select,$Where,false)){
					$this->session->set_userdata('email',$email);
					$this->session->set_userdata('role',$data['role']);
					$this->session->set_userdata('token',$data['token']);
					$this->session->set_userdata('user',$data);
				}else{
					$output['error'] = true;
					$output['error'] = 'Akun Kamu Belum Aktif';
				}
			}
			else{
				$output['error'] = true;
				$output['error'] = 'Login Invalid. User not found';
			}
        }
		self::json($output);
	}

	public function logout() 
	{
		$this->session->unset_userdata('email');
		$this->session->sess_destroy();
		redirect('Authadmin');
	}
	  
}
