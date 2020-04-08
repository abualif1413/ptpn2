<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helpdesk extends MY_Controller {

	public function __construct(){
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
            $data['title']  		= 'Halaman Help Desk';
            $data['view_content'] 	= 'backend/helpdesk/helpdesk';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
                        
	}





























































}
