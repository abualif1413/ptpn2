<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sdhsliptimbang extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->model('backend/M_tbl_sptbs_setelah_slip_timbang','M_tbl_sptbs_setelah_slip_timbang');
       $this->load->model('backend/M_keraniaskep','M_keraniaskep');
       
   	}

	public function index()
	{  
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman Sudah Slip Timbang';
            $data['view_content']   = 'backend/sdhsliptimbang/sdhsliptimbang';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }   
	}

    public function Data_sdhsliptimbang()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(null,"tbl_sptbs_setelah_slip_timbang.nomor_polisi_trek");
            $search = array(
                "tbl_sptbs_setelah_slip_timbang.nomor_polisi_trek",
                "tbl_sptbs_setelah_slip_timbang.tanggal",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_kerani_kcs.nama_lengkap",
            );
            $select = array(
                "tbl_sptbs_setelah_slip_timbang.*",
                "tbl_kebun.nama_kebun as kebun",
                "tbl_afdeling.nama_afdeling as afdeling",
                "tbl_kerani_kcs.nama_lengkap as keranikcs",
                "tbl_blok_1.blok as blok_1",
                "tbl_blok_2.blok as blok_2",
                "tbl_blok_3.blok as blok_3",
                "tbl_blok_1.tahun_tanam as tahun_tanam_1",
                "tbl_blok_2.tahun_tanam as tahun_tanam_2",
                "tbl_blok_3.tahun_tanam as tahun_tanam_3",
            );
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_sptbs_setelah_slip_timbang.id_kebun','left'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_sptbs_setelah_slip_timbang.id_afdeling','left'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_sptbs_setelah_slip_timbang.id_kerani_kcs','left'),
                array('tbl_blok as tbl_blok_1','tbl_blok_1.id=tbl_sptbs_setelah_slip_timbang.id_blok_no_1','left'),
                array('tbl_blok as tbl_blok_2','tbl_blok_2.id=tbl_sptbs_setelah_slip_timbang.id_blok_no_2','left'),
                array('tbl_blok as tbl_blok_3','tbl_blok_3.id=tbl_sptbs_setelah_slip_timbang.id_blok_no_3','left'),
            );
            
            $group_by='tbl_sptbs_setelah_slip_timbang.sptbs';
            
            $Select = array("tbl_kerani_askep.id");
            $Where = array('tbl_kerani_askep.token'=>$this->session->token);
            $result2 =$this->M_keraniaskep->get_result2($Select,$Where,false,false,false);
            foreach ($result2 as $key);
            $id=$key->id;

            $from=$this->input->post('from');
            $to=$this->input->post('to');
            $afdeling=$this->input->post('afdeling');
            if($from==''||$to==''){
                $where=array('tbl_sptbs_setelah_slip_timbang.id_kerani_askep'=>$id);
            }else{
                $where=array(
                    'tbl_sptbs_setelah_slip_timbang.tanggal >='=>$this->input->post('from'),
                    'tbl_sptbs_setelah_slip_timbang.tanggal <='=>$this->input->post('to'),
                    'tbl_sptbs_setelah_slip_timbang.id_afdeling'=>$this->input->post('afdeling'),
                    'tbl_sptbs_setelah_slip_timbang.id_kerani_askep'=>$id,
                );
            }
            
            $result = $this->M_tbl_sptbs_setelah_slip_timbang->findDataTable($orderBy,$search,$select,$where,$join,$group_by);
            foreach ($result as $item) {
                $item->berat_no_1=number_format($item->berat_no_1,2,",","");
                $item->berat_no_2=number_format($item->berat_no_2,2,",","");
                $item->berat_no_3=number_format($item->berat_no_3,2,",","");
                $item->brt_prediksi_no_1=number_format($item->brt_prediksi_no_1,2,",","");
                $item->brt_prediksi_no_2=number_format($item->brt_prediksi_no_2,2,",","");
                $item->brt_prediksi_no_3=number_format($item->brt_prediksi_no_3,2,",","");
                $data[] = $item;
            }
            return $this->M_tbl_sptbs_setelah_slip_timbang->findDataTableOutput($data,$search,$select,$where,$join,$group_by);
        }
    }


    public function afdeling()
    {   
        $this->load->model('backend/M_keraniaskep','M_keraniaskep');
        $this->load->model('backend/M_afdeling','M_afdeling');
        $Select = array("tbl_kerani_askep.id_kebun");
        $Where = array('tbl_kerani_askep.token' => $this->session->token);
        $result =$this->M_keraniaskep->get_result2($Select,$Where,false,false,false);
        foreach ($result as $key);
        $id_kebun=$key->id_kebun;
        $select = array(
            "tbl_afdeling.nama_afdeling",
            "tbl_afdeling.id_kebun",
            "tbl_afdeling.id",
        );
        $where = array('tbl_afdeling.id_kebun'=>$id_kebun);
        $result =$this->M_afdeling->get_result($select,$where,false,false,false,false);
        self::json($result);
    }




}
