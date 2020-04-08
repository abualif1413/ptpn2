<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataqcode extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        $this->load->model('backend/M_blok','M_blok');
        $this->load->model('backend/M_datahasilpanen','M_datahasilpanen');
        $this->load->model('backend/M_komidel','M_komidel');
    }

	public function index()
	{   if(self::isPost()){
            $where=array('barcode' => $this->input->post('barcode'));
            if(!$this->M_DataPemanen->validasiData($where)){
                $result=array('success'=>false,'error'=>'Data Tidak Ada');
            }else{
                $select = array("tbl_pemanen.*","tbl_kerani_kcs.nama_lengkap AS keranikcs");
                $join = array(
                    array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_pemanen.id_kerani_kcs')
                );
                $where = array('tbl_pemanen.barcode'=>$this->input->post('barcode'));
                $result =$this->M_DataPemanen->get_where($select,$where,false,$join,false,false);
            }
        self::json($result);
        }
    }


    public function blokpemananen()
    {   
        if(self::isPost()){
            date_default_timezone_set('Asia/jakarta');
            $day=date ('l');
            $listhari = array(			
                'Sunday'   =>'Minggu',
                'Monday'   =>'Senin',
                'Tuesday'  =>'Selasa',
                'Wednesday'=>'Rabu',
                'Thursday' =>'Kamis',
                'Friday'   =>'Jumat',
                'Saturday' =>'Sabtu'
            );
            $hari=$listhari[$day];
            $select = array("tbl_blok.*");
            if($hari=='Minggu'){
                $where = array(
                    'tbl_blok.id_kebun'=>$this->input->post('id_kebun'),
                    'tbl_blok.id_afdeling'=>$this->input->post('id_afdeling'),
                    'tbl_blok.keterangan'=>'Minggu',
                );
            }else{
                $where = array(
                    'tbl_blok.id_kebun'=>$this->input->post('id_kebun'),
                    'tbl_blok.id_afdeling'=>$this->input->post('id_afdeling'),
                    'tbl_blok.keterangan'=>'All',
                );
            }
            $result =$this->M_blok->get_result($select,$where,false,false,false);
            
            self::json($result);
        }
    }

    public function addpanen()
	{  
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_pemanen','label'=>'Nama Pemanen','rules'=>'required'),
                array('field'=>'jmlh_panen','label'=>'Jumlah Panen','rules'=>'required'),
                array('field'=>'tph','label'=>'TPH','rules'=>'required'),
                array('field'=>'blok','label'=>'BLOK','rules'=>'required'),
                array('field'=>'id_alat','label'=>'Pengganti Alat Premi','rules'=>'required'),
                array('field'=>'jmlh_brondolan','label'=>'Brondolan','rules'=>'required'),
                array('field'=>'id_kerani_askep','label'=>'Kerani Askep','rules'=>'required'),
                array('field'=>'id_kerani_kcs','label'=>'Kerani KCS','rules'=>'required'),
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'required'),
                array('field'=>'id_afdeling','label'=>'Afdeling','rules'=>'required'),
            ));

            if($data === true){
                $field= array(
                    'id_kerani_askep'=>$this->input->post('id_kerani_askep'),
                    'id_kerani_kcs'=>$this->input->post('id_kerani_kcs'),
                    'id_kebun'=>$this->input->post('id_kebun'),
                    'id_afdeling'=>$this->input->post('id_afdeling'),
                    'id_pemanen'=>$this->input->post('id_pemanen'),
                    'jmlh_panen'=>$this->input->post('jmlh_panen'),
                    'tph'=>$this->input->post('tph'),
                    'blok'=>$this->input->post('blok'),
                    'id_alat'=>$this->input->post('id_alat'),
                    'jmlh_brondolan'=>$this->input->post('jmlh_brondolan'),
                    'tanggal'=> date("Y-m-d H:i:s"),
                );
                $where=array(
                    'id_kerani_kcs' => $this->input->post('id_kerani_kcs'),
                    'id_kebun' => $this->input->post('id_kebun'),
                    'id_afdeling' => $this->input->post('id_afdeling'),
                    'tanggal_panen'=> date("Y-m-d"),
                );
                if($this->M_komidel->validasiData($where)){
                    $data=array('success'=>false,'error'=>'Data Sudah Di Validasi Dengan Admin Hari Ini, Jadi Kamu Tidak Bisa Input Panen,');
                }else{
                    $where=array('id_pemanen' => $this->input->post('id_pemanen'),'tanggal'=> date("Y-m-d"));
                    if($this->M_datahasilpanen->validasiData($where)){
                        $data=array('success'=>false,'error'=>'Data Sudah Ada');
                    }else{
                        $data=$this->M_datahasilpanen->insert(self::xss($field));
                        $data=array('success'=>true,'type'=>'Add');
                    }
                }
            }  
        }
        self::json($data);
    }
    


  
    




























































}
