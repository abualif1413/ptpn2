<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('backend/M_chart','M_chart');
		
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Dasboard';
			$data['view_content'] 	= 'backend/home/home';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
	}


	public function grafik()
	{
		self::json($this->M_chart->model_grafik());
	}

	public function profile_admin()
    {   
		$this->load->model('backend/M_admin','M_admin');
        $select = array(
			'tbl_admin.nama_lengkap',
			'tbl_admin.role',
			'tbl_admin.photo',
        );
        $where = array('tbl_admin.token'=> $this->session->token);
        $result =$this->M_admin->get_result($select,$where,false,false,false);
        self::json($result);
	}

	public function profile_kerani_askep()
    {   
		$this->load->model('backend/M_keraniaskep','M_keraniaskep');
        $select = array(
			'tbl_distrik.nama_lengkap as distrik',
            'tbl_kerani_askep.nama_lengkap as keraniaskep',
			'tbl_kebun.nama_kebun',
			'tbl_admin.role',
			'tbl_kerani_askep.photo',
        );
        $join = array(
			array('tbl_kebun','tbl_kebun.id=tbl_kerani_askep.id_kebun','left'),
			array('tbl_distrik','tbl_distrik.id=tbl_kebun.id_distrik','left'),
			array('tbl_admin','tbl_admin.token=tbl_kerani_askep.token','left'),
        );
        $where = array('tbl_kerani_askep.token'=> $this->session->token);
        $result =$this->M_keraniaskep->get_result($select,$where,$join,false,false);
        self::json($result);
	}
	
	public function profile_kerani_kcs()
    {   
		$this->load->model('backend/M_keranikcs','M_keranikcs');
        $select = array(
			'tbl_distrik.nama_lengkap as distrik',
			'tbl_kerani_askep.nama_lengkap as keraniaskep',
            'tbl_kerani_kcs.nama_lengkap as keraniakcs',
			'tbl_kebun.nama_kebun',
			'tbl_afdeling.nama_afdeling',
			'tbl_admin.role',
			'tbl_kerani_kcs.photo',
        );
        $join = array(
			array('tbl_kebun','tbl_kebun.id=tbl_kerani_kcs.id_kebun','left'),
			array('tbl_afdeling','tbl_afdeling.id=tbl_kerani_kcs.id_afdeling','left'),
			array('tbl_distrik','tbl_distrik.id=tbl_kebun.id_distrik','left'),
			array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_kerani_kcs.id_kerani_askep','left'),
			array('tbl_admin','tbl_admin.token=tbl_kerani_kcs.token','left'),
        );
        $where=array('tbl_kerani_kcs.token'=> $this->session->token);
        $result =$this->M_keranikcs->get_result($select,$where,$join,false,false);
        self::json($result);
	}
	
	
	public function profile_mandor()
    {   
		$this->load->model('backend/M_mandor','M_mandor');
        $select = array(
			'tbl_distrik.nama_lengkap as distrik',
			'tbl_kerani_askep.nama_lengkap as keraniaskep',
            'tbl_kerani_kcs.nama_lengkap as keraniakcs',
			'tbl_mandor.nama_lengkap as mandor',
			'tbl_kebun.nama_kebun',
			'tbl_afdeling.nama_afdeling',
			'tbl_admin.role',
			'tbl_mandor.photo',
		);
		
        $join = array(
			array('tbl_kebun','tbl_kebun.id=tbl_mandor.id_kebun','left'),
			array('tbl_afdeling','tbl_afdeling.id=tbl_mandor.id_afdeling','left'),
			array('tbl_distrik','tbl_distrik.id=tbl_kebun.id_distrik','left'),
			array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_mandor.id_kerani_askep','left'),
			array('tbl_kerani_kcs','tbl_kerani_kcs.id=tbl_mandor.id_kerani_kcs','left'),
			array('tbl_admin','tbl_admin.token=tbl_mandor.token','left'),
		);
		
        $where=array('tbl_mandor.token'=> $this->session->token);
        $result =$this->M_mandor->get_result($select,$where,$join,false,false);
        self::json($result);
    }































































}
