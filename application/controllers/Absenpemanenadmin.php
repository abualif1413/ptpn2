<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absenpemanenadmin extends MY_Controller {

	public function __construct(){
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->model('backend/M_absen','M_absen');
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Absen';
			$data['view_content'] 	= 'backend/absenpemanenadmin/absenpemanenadmin';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
	}

    public function Data_Absen()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(
                null,
                "tbl_absen.id_absen",
                "tbl_absen.tanggal",
                "tbl_pemanen.nama_pemanen",
            );
            $search = array(
                "tbl_absen.id_absen",
                "tbl_absen.tanggal",
                "tbl_pemanen.nama_pemanen",
            );
            $select = array(
                "tbl_absen.*",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.id_mandor",
            );
            $join=array(
                array('tbl_pemanen','tbl_pemanen.barcode=tbl_absen.id_absen')
            );
            
            $where=array('tbl_absen.id_mandor'=> $this->session->token );
            $result = $this->M_absen->findDataTable($orderBy,$search,$select,$where,$join);
            foreach ($result as $item) {
                $data[] = $item;
            }
            return $this->M_absen->findDataTableOutput($data,$search,$select,$where,$join);
        }
    }

    public function Data_Absen_Admin()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(
                null,
                "tbl_absen.id_absen",
                "tbl_absen.tanggal",
                "tbl_pemanen.nama_pemanen",
            );
            $search = array(
                "tbl_absen.id_absen",
                "tbl_absen.tanggal",
                "tbl_pemanen.nama_pemanen",
            );
            $select = array(
                "tbl_absen.*",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.id_mandor",
            );
            $join=array(
                array('tbl_pemanen','tbl_pemanen.barcode=tbl_absen.id_absen')
            );
        
            $result = $this->M_absen->findDataTable($orderBy,$search,$select,false,$join);
            foreach ($result as $item) {
                if($item->jam > '07:00:00'){
                    $item->jam = '<button class="btn btn-danger btn-outline-danger btn-mini"><i class="fa fa-pencil-square-o"></i>'.$item->jam.'</button>';
                }else{
                    $item->jam = '<button class="btn btn-success btn-outline-success btn-mini"><i class="fa fa-pencil-square-o"></i>'.$item->jam.'</button>';
                }
                $data[] = $item;
            }
            return $this->M_absen->findDataTableOutput($data,$search,$select,false,$join);
        }
    }






























































}
