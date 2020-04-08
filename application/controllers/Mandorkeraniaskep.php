<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mandorkeraniaskep extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->helper('string');
       $this->load->model('backend/M_mandor','M_mandor');
       $this->load->model('backend/M_kebun','M_kebun');
       $this->load->model('backend/M_afdeling','M_afdeling');
       $this->load->model('backend/M_admin','M_admin');
       $this->load->model('backend/M_keraniaskep','M_keraniaskep');
   	}

	public function index()
	{  
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman Data Mandor';
            $data['view_content']   = 'backend/mandorkeraniaskep/mandorkeraniaskep';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $data['afdeling']       = $this->M_afdeling->getAll()->result();
            $data['keraniaskep']    = $this->M_keraniaskep->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }   
	}

    public function Data_Mandor()
    {   
        if(self::isPost()){
            $user=$this->session->userdata('user');
            extract($user);
            $Select = array(
                "tbl_kerani_askep.id",
            );
            $Where = array('tbl_kerani_askep.token'=>$token);
            $result =$this->M_keraniaskep->get_result2($Select,$Where,false,false,false);
            foreach ($result as $key);
            $id_kerani_askep=$key->id;


            $data = array();
            $orderBy = array(
                null,
                "tbl_mandor.nama_lengkap",
                "tbl_mandor.email",
            );
            $search = array(
                "tbl_kerani_askep.nama_lengkap",
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_mandor.nama_lengkap",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_mandor.email",
            );
            $select = array(
                "tbl_mandor.id",
                "tbl_mandor.email",
                "tbl_mandor.token",
                "tbl_mandor.nama_lengkap",
                "tbl_mandor.id_kerani_askep",
                "tbl_jabatan.jabatan",
                "tbl_mandor.id_kebun",
                "tbl_mandor.id_afdeling",
                "tbl_mandor.photo",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_kerani_askep.nama_lengkap as keraniaskep",
                "tbl_kerani_kcs.nama_lengkap as keranikcs",
            );
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_mandor.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_mandor.id_afdeling'),
                array('tbl_jabatan','tbl_jabatan.id=tbl_mandor.id_jabatan'),
                array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_mandor.id_kerani_askep'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.id=tbl_mandor.id_kerani_kcs'),
            );
            $where=array('tbl_mandor.id_kerani_askep'=>$id_kerani_askep);
            $result = $this->M_mandor->findDataTable($orderBy,$search,$select,$where,$join);
            foreach ($result as $item) {
                $item->image='<img src="assets/backend/img/photo/'.$item->photo.'" width="100px" height="100px;">';
                $data[] = $item;
            }
            return $this->M_mandor->findDataTableOutput($data,$search,$select,$where,$join);
        }
    }














}
