<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataHasilPanenkeraniaskep extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_datahasilpanen','M_datahasilpanen');
        $this->load->model('backend/M_keraniaskep','M_keraniaskep');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
    }

	public function index()
	{   
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Data Hasil Panen';
            $data['view_content'] 	= 'backend/datahasilpanenkenariaskep/datahasilpanenkenariaskep';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }

    public function data_hasil_panen()
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
            $orderBy = array(null,"tbl_pemanen.id","tbl_pemanen.nama_pemanen","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_pemanen.barcode","tbl_pemanen.keterangan","tbl_kerani_kcs.nama_lengkap","tbl_panen.jmlh_panen","tbl_panen.tanggal");
            $search = array(
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.barcode",
                "tbl_panen.tanggal");
            $select = array(
                "tbl_panen.*",
                "tbl_alat.premi_alat",
                "tbl_kebun.nama_kebun",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.barcode",
                "tbl_afdeling.nama_afdeling",
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_blok.blok as kode_blok",
                "tbl_mandor.nama_lengkap as mandor",
            
            );
            $join = array(
                array('tbl_pemanen','tbl_pemanen.id=tbl_panen.id_pemanen'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_panen.id_kerani_kcs'),
                array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling'),
                array('tbl_alat','tbl_alat.id=tbl_panen.id_alat'),
                array('tbl_blok','tbl_blok.id=tbl_panen.blok'),
                array('tbl_mandor','tbl_mandor.token=tbl_pemanen.id_mandor'),
            );

            $where=array('tbl_panen.id_kerani_askep' => $id_kerani_askep);
            $group_by='tbl_panen.tanggal,tbl_panen.id_pemanen';
            $result = $this->M_datahasilpanen->findDataTable($orderBy,$search,$select,$where,$join,$group_by);
            
            foreach ($result as $item) {
                $item->tanggal= date('d-m-Y', strtotime($item->tanggal));

                $item->jmlh_brondolan= $item->jmlh_brondolan.' Kg';
                $item->premi_alat="Rp " . number_format($item->premi_alat,0,',','.');
                if($item->status=='Y'){
                    $btnAction = '<button class="btn btn-success btn-outline-success btn-mini"><i class="fa fa-pencil-square-o"></i>Sudah Verifikasi</button>';
                }else{
                    $btnAction = '<button class="btn btn-danger btn-outline-danger btn-mini"><i class="fa fa-pencil-square-o"></i>Belum Verifikasi</button>';
                }
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_datahasilpanen->findDataTableOutput($data,$search,$select,$where,$join,$group_by);
        }
    }


    




























































}
