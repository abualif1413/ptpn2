<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datakomideladmin extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        $this->load->model('backend/M_datahasilpanen','M_datahasilpanen');
        $this->load->model('backend/M_komidel','M_komidel');
        $this->load->model('backend/M_keraniaskep','M_keraniaskep');
    }

	public function index()
	{   
        
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Data Nilai Komidel';
            $data['view_content'] 	= 'backend/datakomideladmin/datakomideladmin';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }


    
    public function data_komidel()
    {   
        if($this->isPost()){
            $data = array();
            $orderBy = array(null,"tbl_komidel.id");
            $search = array(
                "tbl_komidel.id",
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_komidel.tanggal_panen",
                "tbl_komidel.jmlh_panen",
                "tbl_komidel.kg",
                
            );
            $select = array(
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_kebun.nama_kebun",
                "tbl_kebun.id as kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_afdeling.id as afdeling",
                "tbl_komidel.id",
                "tbl_komidel.id_kerani_kcs",
                "tbl_komidel.jmlh_panen",
                "tbl_komidel.kg",
                "tbl_komidel.nilai_komidel",
                "tbl_komidel.tanggal_panen",
                "tbl_komidel.id_kebun",
                "tbl_komidel.id_afdeling",
                "tbl_komidel.create_att",
                "tbl_kerani_askep.nama_lengkap as keraniaskep"
            );
            $join = array(
                array('tbl_panen','tbl_panen.id_kerani_kcs=tbl_komidel.id_kerani_kcs'),
                array('tbl_kebun','tbl_kebun.id=tbl_komidel.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_komidel.id_afdeling'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_komidel.id_kerani_kcs'),
                array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_komidel.id_kerani_askep'),
            );
            $group_by=array(
                'tbl_komidel.id_kerani_kcs',
                'kebun',
                'afdeling',
                'tbl_komidel.tanggal_panen'
            );
            $result = $this->M_komidel->findDataTable($orderBy,$search,$select,false,$join,$group_by);
            foreach ($result as $item) {
                $item->tanggal_panen= date('d-m-Y', strtotime($item->tanggal_panen));
                $item->kg= $item->kg. ' Kg';
                $item->nilai_komidel= number_format($item->nilai_komidel,2). ' Kg';
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Detail(this)" data-id="'.$item->id.'" data-id_kebun="'.$item->id_kebun.'" data-id_afdeling="'.$item->id_afdeling.'" data-tanggal="'.$item->tanggal_panen.'"  data-id_kerani_kcs="'.$item->id_kerani_kcs.'"><i class="fa fa-pencil-square-o"></i>Show Detail</button>';
                $btnAction .= '';        
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_komidel->findDataTableOutput($data,$search,$select,false,$join,$group_by);
        }
    }

    



















































}
