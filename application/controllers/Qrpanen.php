<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qrpanen extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_chart','M_chart');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman QR Absen';
			$data['view_content'] 	= 'backend/qrpanen/qrpanen';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
	}





























































}
