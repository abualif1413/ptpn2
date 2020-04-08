<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataPemanenkeraniaskep extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        $this->load->model('backend/M_kebun','M_kebun');
        $this->load->model('backend/M_afdeling','M_afdeling');
        $this->load->model('backend/M_mandor','M_mandor');
        $this->load->model('backend/M_keraniaskep','M_keraniaskep');
        
    }

	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Data Pemanen';
            $data['view_content'] 	= 'backend/datapemanenkeraniaskep/datapemanenkeraniaskep';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $data['afdeling']       = $this->M_afdeling->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }

    public function data_pemanen()
    {   
        if($this->isPost()){
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
            $orderBy = array(null,"tbl_pemanen.id","tbl_pemanen.nama_pemanen","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_pemanen.barcode","tbl_pemanen.keterangan","tbl_users.nama_lengkap");
            $search = array(
                "tbl_kerani_askep.nama_lengkap",
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_mandor.nama_lengkap",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.barcode");

            $select = array("tbl_kerani_askep.nama_lengkap as keraniaskep","tbl_pemanen.*","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_mandor.nama_lengkap","tbl_kerani_kcs.nama_lengkap as keranikcs");
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling'),
                array('tbl_mandor','tbl_mandor.token=tbl_pemanen.id_mandor'),
                array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_pemanen.id_kerani_askep'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_pemanen.id_kerani_kcs'),
            );
            $where=array('tbl_pemanen.id_kerani_askep' => $id_kerani_askep);
            $result = $this->M_DataPemanen->findDataTable($orderBy,$search,$select,$where,$join);
            foreach ($result as $item) {
                $item->image='<img src="assets/backend/img/photo/'.$item->photo.'" width="100px" height="100px;">';
                $item->img_barcode='<img src="assets/backend/qr/'.$item->img_barcode.'" width="100px" height="100px;">';
                $data[] = $item;
            }
            return $this->M_DataPemanen->findDataTableOutput($data,$search,$select,$where,$join);
        }
    }



























































}
