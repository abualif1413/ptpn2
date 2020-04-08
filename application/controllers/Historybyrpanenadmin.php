<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historybyrpanenadmin extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_history_panen','M_history_panen');
    }

    public function index()
	{   
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman History Premi Panen';
            $data['view_content'] 	= 'backend/historybyrpanenadmin/historybyrpanenadmin';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }

    public function datahistorypanen()
    {
        if(self::isPost()){
            $data = array();
            $orderBy = array(null,"tbl_history_panen.id");
            $search = array("tbl_history_panen.id");
            $select = array(
                "tbl_history_panen.*",
                "tbl_kerani_kcs.nama_lengkap as keranikcs",
                "tbl_pemanen.nama_pemanen",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_blok.blok as kode_blok",
                "tbl_kerani_askep.nama_lengkap as keraniaskep"
            );

            $join = array(
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_history_panen.id_kerani_kcs'),
                array('tbl_pemanen','tbl_pemanen.id=tbl_history_panen.id_pemanen'),
                array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling'),
                array('tbl_blok','tbl_blok.id=tbl_history_panen.blok'),
                array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_history_panen.id_kerani_askep'),
            );

            $group_by='tbl_history_panen.tanggal,tbl_history_panen.id_pemanen';
            $result = $this->M_history_panen->findDataTable($orderBy,$search,$select,false,$join,$group_by);
            foreach ($result as $item) {

                $item->total=$item->tbs+$item->premi_alat+$item->premi_brondolan;
                $item->tbs1="Rp. " . number_format($item->tbs,0,',','.');
                $item->premi_alat1="Rp. " . number_format($item->premi_alat,0,',','.');
                $item->premi_brondolan1="Rp. " . number_format($item->premi_brondolan,0,',','.');
                $item->total1="Rp. " . number_format($item->total,0,',','.');
                $data[] = $item;
            }
            return $this->M_history_panen->findDataTableOutput($data,$search,$select,false,$join,$group_by);
        }
    }



























































}
