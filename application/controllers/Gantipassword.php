<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gantipassword extends MY_Controller {

	public function __construct(){
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('backend/M_admin','M_admin');
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Ganti Password';
			$data['view_content'] 	= 'backend/gantipassword/gantipassword';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
	}
	
	public function ubahpassword()
	{
	    if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'password_baru','label'=>'Password Baru','rules'=>'required|min_length[8]|max_length[50]'),
            ));
            if($data === true){
                $field= array('password'=>password_hash($this->input->post('password_baru'),PASSWORD_BCRYPT));
                $user=$this->session->userdata('user');
                extract($user);
                $primary_values=$id;
                $data=$this->M_admin->update_by_id($field,$primary_values);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
	}
	
	































































}
