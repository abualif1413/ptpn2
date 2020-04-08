<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inputkomidelnew extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_trip','M_trip');
        $this->load->model('backend/M_keraniaskep','M_keraniaskep');

        
        $this->load->model('backend/M_tbl_sptbs_setelah_slip_timbang','M_tbl_sptbs_setelah_slip_timbang');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Input Nilai Komidel';
			$data['view_content'] 	= 'backend/inputkomidelnew/inputkomidelnew';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
    }
    

    public function data_komidel_new()
    {   
        if($this->isPost()){
            $data = array();
            $orderBy = array(null,"tbl_trip.id");
            $search = array(
                "tbl_trip.id",
                "tbl_trip.nomor_polisi_trek",
                "tbl_trip.sptbs",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_kerani_kcs.nama_lengkap",
            );
            $select = array(
                "tbl_trip.*",
                "tbl_kebun.nama_kebun as kebun",
                "tbl_afdeling.nama_afdeling as afdeling",
                "tbl_kerani_kcs.nama_lengkap as keranikcs",

                "tbl_blok_1.blok as blok_1",
                "tbl_blok_1.tahun_tanam as tahun_tanam_1 ",
                "tbl_blok_1.prediksi_komidel as prediksi_komidel_1 ",


                "tbl_blok_2.blok as blok_2",
                "tbl_blok_2.tahun_tanam as tahun_tanam_2",
                "tbl_blok_2.prediksi_komidel as prediksi_komidel_2",

                "tbl_blok_3.blok as blok_3",
                "tbl_blok_3.tahun_tanam as tahun_tanam_3",
                "tbl_blok_3.prediksi_komidel as prediksi_komidel_3",

            );
            
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_trip.id_kebun','left'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_trip.id_afdeling','left'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_trip.id_kerani_kcs','left'),
                array('tbl_blok as tbl_blok_1','tbl_blok_1.id=tbl_trip.id_blok_1','left'),
                array('tbl_blok as tbl_blok_2','tbl_blok_2.id=tbl_trip.id_blok_2','left'),
                array('tbl_blok as tbl_blok_3','tbl_blok_3.id=tbl_trip.id_blok_3','left'),
            );
            
            $group_by='tbl_trip.sptbs';
            
            $Select = array("tbl_kerani_askep.id");
            $Where = array('tbl_kerani_askep.token'=>$this->session->token);
            $result2 =$this->M_keraniaskep->get_result2($Select,$Where,false,false,false);
            foreach ($result2 as $key);
            $id=$key->id;

            $from=$this->input->post('from');
            $to=$this->input->post('to');
            $afdeling=$this->input->post('afdeling');
            if($from==''||$to==''){
                $where=array('tbl_trip.id_kerani_askep'=>$id,'tbl_trip.status'=>'N');
            }else{
                $where=array(
                    'tbl_trip.status'=>'N',
                    'tbl_trip.tanggal >='=>$this->input->post('from'),
                    'tbl_trip.tanggal <='=>$this->input->post('to'),
                    'tbl_trip.id_afdeling'=>$this->input->post('afdeling'),
                    'tbl_trip.id_kerani_askep'=>$id,
                );
            }
            $result = $this->M_trip->findDataTable($orderBy,$search,$select,$where,$join,$group_by);
            foreach ($result as $item) {

                if(empty($item->blok_1)){
                        $item->blok_1='0';
                }
                if(empty($item->tahun_tanam_1)){
                        $item->tahun_tanam_1='0';
                }

                if(empty($item->blok_2)){
                        $item->blok_2='0';
                }
                if(empty($item->tahun_tanam_2)){
                        $item->tahun_tanam_2='0';
                }

                if(empty($item->blok_3)){
                        $item->blok_3='0';
                }
                if(empty($item->tahun_tanam_3)){
                        $item->tahun_tanam_3='0';
                }
                $btnAction = '
                <form  method="POST" action="Inputkomidelnew/insertkomidelnew">
                    <input type="hidden" name="id" value="'.$item->id.'">
                    <input type="hidden" name="tahun_tanam_1" value="'.$item->tahun_tanam_1.'">
                    <input type="hidden" name="tahun_tanam_2" value="'.$item->tahun_tanam_2.'">
                    <input type="hidden" name="tahun_tanam_3" value="'.$item->tahun_tanam_3.'">
                    <input type="hidden" name="id_kerani_askep" value="'.$item->id_kerani_askep.'">
                    <input type="hidden" name="id_kerani_kcs" value="'.$item->id_kerani_kcs.'">
                    <input type="hidden" name="id_kebun" value="'.$item->id_kebun.'">
                    <input type="hidden" name="id_afdeling" value="'.$item->id_afdeling.'">
                    <input type="hidden" name="id_blok_no_1" value="'.$item->id_blok_1.'">
                    <input type="hidden" name="id_blok_no_2" value="'.$item->id_blok_2.'">
                    <input type="hidden" name="id_blok_no_3" value="'.$item->id_blok_3.'">
                    <input type="hidden" name="jumlah_janjang_no_1" value="'.$item->jumlah_janjang_1.'">
                    <input type="hidden" name="jumlah_janjang_no_2" value="'.$item->jumlah_janjang_2.'">
                    <input type="hidden" name="jumlah_janjang_no_3" value="'.$item->jumlah_janjang_3.'">
                    <input type="hidden" name="jumlah_taksir_brondolan_1" value="'.$item->jumlah_taksir_brondolan_1.'">
                    <input type="hidden" name="jumlah_taksir_brondolan_2" value="'.$item->jumlah_taksir_brondolan_2.'">
                    <input type="hidden" name="jumlah_taksir_brondolan_3" value="'.$item->jumlah_taksir_brondolan_3.'">
                    <input type="hidden" name="nomor_polisi_trek" value="'.$item->nomor_polisi_trek.'">
                    <input type="hidden" name="sptbs" value="'.$item->sptbs.'">
                    <input type="hidden" name="tanggal" value="'.$item->tanggal.'">
                    <input type="hidden" name="prediksi_komidel_1" value="'.$item->prediksi_komidel_1.'">
                    <input type="hidden" name="prediksi_komidel_2" value="'.$item->prediksi_komidel_2.'">
                    <input type="hidden" name="prediksi_komidel_3" value="'.$item->prediksi_komidel_3.'">
                    <div class="form-group">
                        <label for="">Input Berat</label>
                        <input type="text" class="form-control" name="berat_total_tbs" required>
                    </div>
                    <div class="form-group">
                        <label for="">Berat Brondol</label>
                        <input type="text" class="form-control" name="berat_brondolan" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
                ';            
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_trip->findDataTableOutput($data,$search,$select,$where,$join,$group_by);
        }
    }


    public function insertkomidelnew()
    {
        $tahun_tanam_1      =$this->input->post('tahun_tanam_1');
        $tahun_tanam_2      =$this->input->post('tahun_tanam_2');
        $tahun_tanam_3      =$this->input->post('tahun_tanam_3');
        $id_kerani_askep    =$this->input->post('id_kerani_askep');
        $id_kerani_kcs      =$this->input->post('id_kerani_kcs');
        $id_kebun           =$this->input->post('id_kebun');
        $id_afdeling        =$this->input->post('id_afdeling');
        $id_blok_no_1       =$this->input->post('id_blok_no_1');
        $id_blok_no_2       =$this->input->post('id_blok_no_2');
        $id_blok_no_3       =$this->input->post('id_blok_no_3');
        $jumlah_janjang_no_1=$this->input->post('jumlah_janjang_no_1');
        $jumlah_janjang_no_2=$this->input->post('jumlah_janjang_no_2');
        $jumlah_janjang_no_3=$this->input->post('jumlah_janjang_no_3');

        $berat_brondolan    =$this->input->post('berat_brondolan');
        
        $jumlah_taksir_brondolan_1    =$this->input->post('jumlah_taksir_brondolan_1');
        $jumlah_taksir_brondolan_2    =$this->input->post('jumlah_taksir_brondolan_2');
        $jumlah_taksir_brondolan_3    =$this->input->post('jumlah_taksir_brondolan_3');

        if($jumlah_taksir_brondolan_1 == '0' && $jumlah_taksir_brondolan_2 == '0' && $jumlah_taksir_brondolan_3 == '0'){
            $berondolan_timbang_1=0;
            $berondolan_timbang_2=0;
            $berondolan_timbang_3=0;
        }else{
            $berondolan_timbang_1=($berat_brondolan/($jumlah_taksir_brondolan_1+$jumlah_taksir_brondolan_2+$jumlah_taksir_brondolan_3))*$jumlah_taksir_brondolan_1;
            $berondolan_timbang_2=($berat_brondolan/($jumlah_taksir_brondolan_1+$jumlah_taksir_brondolan_2+$jumlah_taksir_brondolan_3))*$jumlah_taksir_brondolan_2;
            $berondolan_timbang_3=($berat_brondolan/($jumlah_taksir_brondolan_1+$jumlah_taksir_brondolan_2+$jumlah_taksir_brondolan_3))*$jumlah_taksir_brondolan_3;
        }
        
        $nomor_polisi_trek  =$this->input->post('nomor_polisi_trek');
        $sptbs              =$this->input->post('sptbs');
        $tanggal            =$this->input->post('tanggal');
        $berat_TBS          =$this->input->post('berat_total_tbs');

        $prediksi_komidel_1 =$this->input->post('prediksi_komidel_1')*$jumlah_janjang_no_1;
        $prediksi_komidel_2 =$this->input->post('prediksi_komidel_2')*$jumlah_janjang_no_2;
        $prediksi_komidel_3 =$this->input->post('prediksi_komidel_3')*$jumlah_janjang_no_3;
        
        $total_prediksi     =$prediksi_komidel_1+$prediksi_komidel_2+$prediksi_komidel_3;
        $berat_total        =$berat_TBS+$berat_brondolan;

        if(($tahun_tanam_2 == "" || $tahun_tanam_2 == 0) && ($tahun_tanam_3 == "" || $tahun_tanam_3 == 0) ) {
            
            $berat_no_1         =($jumlah_janjang_no_1/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
            $berat_no_2         =($jumlah_janjang_no_2/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
            $berat_no_3         =($jumlah_janjang_no_3/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;

            $field= array(
                'id_kerani_askep'=>$id_kerani_askep,
                'id_kerani_kcs'=>$id_kerani_kcs,
                'id_kebun'=>$id_kebun,
                'id_afdeling'=>$id_afdeling,
                'id_blok_no_1'=>$id_blok_no_1,
                'id_blok_no_2'=>$id_blok_no_2,
                'id_blok_no_3'=>$id_blok_no_3,
                'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                'berat_no_1'=>$berat_no_1,
                'berat_no_2'=>$berat_no_2,
                'berat_no_3'=>$berat_no_3,
                'berat_TBS'=>$berat_TBS,
                'berat_brondolan'=>$berat_brondolan,

                'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,

                'berondolan_timbang_1'=>$berondolan_timbang_1,
                'berondolan_timbang_2'=>$berondolan_timbang_2,
                'berondolan_timbang_3'=>$berondolan_timbang_3,

                'berat_total'=>$berat_total,
                'brt_prediksi_no_1'=>0,
                'brt_prediksi_no_2'=>0,
                'brt_prediksi_no_3'=>0,
                'total_prediksi'=>0,
                'sptbs'=>$sptbs,
                'nomor_polisi_trek'=>$nomor_polisi_trek,
                'tanggal'=>$tanggal,
            );
            $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
            $where=array('id' => $this->input->post('id'));
            $this->db->set('tbl_trip.status', 'Y');
            $this->M_trip->update_where(self::xss($where,false));

            $this->session->set_flashdata('success','TRUE (RUMUS BIASA) KASUS 1'); // KASUS 1
            redirect(base_url('/Inputkomidelnew'));
        }else if(($tahun_tanam_2 != "" || $tahun_tanam_2 != 0) && ($tahun_tanam_3 == "" || $tahun_tanam_3 == 0)){
            if($tahun_tanam_1==$tahun_tanam_2){

                $berat_no_1         =($jumlah_janjang_no_1/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
                $berat_no_2         =($jumlah_janjang_no_2/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
                $berat_no_3         =($jumlah_janjang_no_3/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;

                $field= array(
                    'id_kerani_askep'=>$id_kerani_askep,
                    'id_kerani_kcs'=>$id_kerani_kcs,
                    'id_kebun'=>$id_kebun,
                    'id_afdeling'=>$id_afdeling,
                    'id_blok_no_1'=>$id_blok_no_1,
                    'id_blok_no_2'=>$id_blok_no_2,
                    'id_blok_no_3'=>$id_blok_no_3,
                    'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                    'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                    'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                    'berat_no_1'=>$berat_no_1,
                    'berat_no_2'=>$berat_no_2,
                    'berat_no_3'=>$berat_no_3,
                    'berat_TBS'=>$berat_TBS,
                    'berat_brondolan'=>$berat_brondolan,

                    'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                    'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                    'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,

                    'berondolan_timbang_1'=>$berondolan_timbang_1,
                    'berondolan_timbang_2'=>$berondolan_timbang_2,
                    'berondolan_timbang_3'=>$berondolan_timbang_3,

                    'berat_total'=>$berat_total,
                    'brt_prediksi_no_1'=>0,
                    'brt_prediksi_no_2'=>0,
                    'brt_prediksi_no_3'=>0,
                    'total_prediksi'=>0,
                    'sptbs'=>$sptbs,
                    'nomor_polisi_trek'=>$nomor_polisi_trek,
                    'tanggal'=>$tanggal,
                );
                $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                $where=array('id' => $this->input->post('id'));
                $this->db->set('tbl_trip.status', 'Y');
                $this->M_trip->update_where(self::xss($where,false));

                $this->session->set_flashdata('success','TRUE 1 (RUMUS BIASA) KASUS 2'); // KASUS 2
                redirect(base_url('/Inputkomidelnew'));
            }else{

                $berat_no_1=($prediksi_komidel_1/$total_prediksi)*$berat_TBS;
                $berat_no_2=($prediksi_komidel_2/$total_prediksi)*$berat_TBS;
                $berat_no_3=($prediksi_komidel_3/$total_prediksi)*$berat_TBS;
                $field= array(
                    'id_kerani_askep'=>$id_kerani_askep,
                    'id_kerani_kcs'=>$id_kerani_kcs,
                    'id_kebun'=>$id_kebun,
                    'id_afdeling'=>$id_afdeling,
                    'id_blok_no_1'=>$id_blok_no_1,
                    'id_blok_no_2'=>$id_blok_no_2,
                    'id_blok_no_3'=>$id_blok_no_3,
                    'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                    'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                    'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                    'berat_no_1'=>$berat_no_1,
                    'berat_no_2'=>$berat_no_2,
                    'berat_no_3'=>$berat_no_3,
                    'berat_TBS'=>$berat_TBS,
                    'berat_brondolan'=>$berat_brondolan,

                    'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                    'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                    'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,
    
                    'berondolan_timbang_1'=>$berondolan_timbang_1,
                    'berondolan_timbang_2'=>$berondolan_timbang_2,
                    'berondolan_timbang_3'=>$berondolan_timbang_3,

                    'berat_total'=>$berat_total,
                    'brt_prediksi_no_1'=>$prediksi_komidel_1,
                    'brt_prediksi_no_2'=>$prediksi_komidel_2,
                    'brt_prediksi_no_3'=>$prediksi_komidel_3,
                    'total_prediksi'=>$total_prediksi,
                    'sptbs'=>$sptbs,
                    'nomor_polisi_trek'=>$nomor_polisi_trek,
                    'tanggal'=>$tanggal,
                );
                $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                $where=array('id' => $this->input->post('id'));
                $this->db->set('tbl_trip.status', 'Y');
                $this->M_trip->update_where(self::xss($where,false));

                $this->session->set_flashdata('error','FALSE 1 (RUMUS PREDIKSI) KASUS 3'); // KASUS 3
                redirect(base_url('/Inputkomidelnew'));
            }
        }else if(($tahun_tanam_2 == "" || $tahun_tanam_2 == 0) && ($tahun_tanam_3 != "" || $tahun_tanam_3 != 0)){
            if($tahun_tanam_1==$tahun_tanam_3){


                $berat_no_1         =($jumlah_janjang_no_1/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
                $berat_no_2         =($jumlah_janjang_no_2/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
                $berat_no_3         =($jumlah_janjang_no_3/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;

                $field= array(
                    'id_kerani_askep'=>$id_kerani_askep,
                    'id_kerani_kcs'=>$id_kerani_kcs,
                    'id_kebun'=>$id_kebun,
                    'id_afdeling'=>$id_afdeling,
                    'id_blok_no_1'=>$id_blok_no_1,
                    'id_blok_no_2'=>$id_blok_no_2,
                    'id_blok_no_3'=>$id_blok_no_3,
                    'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                    'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                    'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                    'berat_no_1'=>$berat_no_1,
                    'berat_no_2'=>$berat_no_2,
                    'berat_no_3'=>$berat_no_3,
                    'berat_TBS'=>$berat_TBS,
                    'berat_brondolan'=>$berat_brondolan,

                    'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                    'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                    'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,

                    'berondolan_timbang_1'=>$berondolan_timbang_1,
                    'berondolan_timbang_2'=>$berondolan_timbang_2,
                    'berondolan_timbang_3'=>$berondolan_timbang_3,

                    'berat_total'=>$berat_total,
                    'brt_prediksi_no_1'=>0,
                    'brt_prediksi_no_2'=>0,
                    'brt_prediksi_no_3'=>0,
                    'total_prediksi'=>0,
                    'sptbs'=>$sptbs,
                    'nomor_polisi_trek'=>$nomor_polisi_trek,
                    'tanggal'=>$tanggal,
                );
                $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                $where=array('id' => $this->input->post('id'));
                $this->db->set('tbl_trip.status', 'Y');
                $this->M_trip->update_where(self::xss($where,false));

                $this->session->set_flashdata('success','TRUE 2 (RUMUS BIASA) KASUS 2'); // KASUS 2
                redirect(base_url('/Inputkomidelnew'));
            }else{

                $berat_no_1=($prediksi_komidel_1/$total_prediksi)*$berat_TBS;
                $berat_no_2=($prediksi_komidel_2/$total_prediksi)*$berat_TBS;
                $berat_no_3=($prediksi_komidel_3/$total_prediksi)*$berat_TBS;
                $field= array(
                    'id_kerani_askep'=>$id_kerani_askep,
                    'id_kerani_kcs'=>$id_kerani_kcs,
                    'id_kebun'=>$id_kebun,
                    'id_afdeling'=>$id_afdeling,
                    'id_blok_no_1'=>$id_blok_no_1,
                    'id_blok_no_2'=>$id_blok_no_2,
                    'id_blok_no_3'=>$id_blok_no_3,
                    'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                    'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                    'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                    'berat_no_1'=>$berat_no_1,
                    'berat_no_2'=>$berat_no_2,
                    'berat_no_3'=>$berat_no_3,
                    'berat_TBS'=>$berat_TBS,
                    'berat_brondolan'=>$berat_brondolan,

                    'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                    'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                    'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,
    
                    'berondolan_timbang_1'=>$berondolan_timbang_1,
                    'berondolan_timbang_2'=>$berondolan_timbang_2,
                    'berondolan_timbang_3'=>$berondolan_timbang_3,

                    'berat_total'=>$berat_total,
                    'brt_prediksi_no_1'=>$prediksi_komidel_1,
                    'brt_prediksi_no_2'=>$prediksi_komidel_2,
                    'brt_prediksi_no_3'=>$prediksi_komidel_3,
                    'total_prediksi'=>$total_prediksi,
                    'sptbs'=>$sptbs,
                    'nomor_polisi_trek'=>$nomor_polisi_trek,
                    'tanggal'=>$tanggal,
                );
                $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                $where=array('id' => $this->input->post('id'));
                $this->db->set('tbl_trip.status', 'Y');
                $this->M_trip->update_where(self::xss($where,false));

                $this->session->set_flashdata('error','FALSE 2 (RUMUS PREDIKSI) KASUS 3'); // KASUS 3
                redirect(base_url('/Inputkomidelnew'));
            }
        }else if($tahun_tanam_1 > 0 && $tahun_tanam_2 > 0 && $tahun_tanam_3 > 0 ){
            $variabelrray=array($tahun_tanam_2,$tahun_tanam_3);
            if(in_array($tahun_tanam_1,$variabelrray)){
                if($tahun_tanam_1==$tahun_tanam_2 && ($tahun_tanam_3 !=$tahun_tanam_1 || $tahun_tanam_3 !=$tahun_tanam_2)){
                    
                    $berat_no_1=($prediksi_komidel_1/$total_prediksi)*$berat_TBS;
                    $berat_no_2=($prediksi_komidel_2/$total_prediksi)*$berat_TBS;
                    $berat_no_3=($prediksi_komidel_3/$total_prediksi)*$berat_TBS;
                    $field= array(
                        'id_kerani_askep'=>$id_kerani_askep,
                        'id_kerani_kcs'=>$id_kerani_kcs,
                        'id_kebun'=>$id_kebun,
                        'id_afdeling'=>$id_afdeling,
                        'id_blok_no_1'=>$id_blok_no_1,
                        'id_blok_no_2'=>$id_blok_no_2,
                        'id_blok_no_3'=>$id_blok_no_3,
                        'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                        'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                        'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                        'berat_no_1'=>$berat_no_1,
                        'berat_no_2'=>$berat_no_2,
                        'berat_no_3'=>$berat_no_3,
                        'berat_TBS'=>$berat_TBS,
                        'berat_brondolan'=>$berat_brondolan,

                        'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                        'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                        'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,
        
                        'berondolan_timbang_1'=>$berondolan_timbang_1,
                        'berondolan_timbang_2'=>$berondolan_timbang_2,
                        'berondolan_timbang_3'=>$berondolan_timbang_3,

                        'berat_total'=>$berat_total,
                        'brt_prediksi_no_1'=>$prediksi_komidel_1,
                        'brt_prediksi_no_2'=>$prediksi_komidel_2,
                        'brt_prediksi_no_3'=>$prediksi_komidel_3,
                        'total_prediksi'=>$total_prediksi,
                        'sptbs'=>$sptbs,
                        'nomor_polisi_trek'=>$nomor_polisi_trek,
                        'tanggal'=>$tanggal,
                    );
                    $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                    $where=array('id' => $this->input->post('id'));
                    $this->db->set('tbl_trip.status', 'Y');
                    $this->M_trip->update_where(self::xss($where,false));
                    
                    $this->session->set_flashdata('error','FALSE 5 (RUMUS PREDIKSI) KASUS 5'); // KASUS 5
                    redirect(base_url('/Inputkomidelnew'));
                }else{
                    if($tahun_tanam_1==$tahun_tanam_3 && ($tahun_tanam_2 !=$tahun_tanam_1 || $tahun_tanam_2 !=$tahun_tanam_3)){
                        
                        $berat_no_1=($prediksi_komidel_1/$total_prediksi)*$berat_TBS;
                        $berat_no_2=($prediksi_komidel_2/$total_prediksi)*$berat_TBS;
                        $berat_no_3=($prediksi_komidel_3/$total_prediksi)*$berat_TBS;
                        $field= array(
                            'id_kerani_askep'=>$id_kerani_askep,
                            'id_kerani_kcs'=>$id_kerani_kcs,
                            'id_kebun'=>$id_kebun,
                            'id_afdeling'=>$id_afdeling,
                            'id_blok_no_1'=>$id_blok_no_1,
                            'id_blok_no_2'=>$id_blok_no_2,
                            'id_blok_no_3'=>$id_blok_no_3,
                            'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                            'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                            'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                            'berat_no_1'=>$berat_no_1,
                            'berat_no_2'=>$berat_no_2,
                            'berat_no_3'=>$berat_no_3,
                            'berat_TBS'=>$berat_TBS,
                            'berat_brondolan'=>$berat_brondolan,

                            'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                            'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                            'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,
            
                            'berondolan_timbang_1'=>$berondolan_timbang_1,
                            'berondolan_timbang_2'=>$berondolan_timbang_2,
                            'berondolan_timbang_3'=>$berondolan_timbang_3,

                            'berat_total'=>$berat_total,
                            'brt_prediksi_no_1'=>$prediksi_komidel_1,
                            'brt_prediksi_no_2'=>$prediksi_komidel_2,
                            'brt_prediksi_no_3'=>$prediksi_komidel_3,
                            'total_prediksi'=>$total_prediksi,
                            'sptbs'=>$sptbs,
                            'nomor_polisi_trek'=>$nomor_polisi_trek,
                            'tanggal'=>$tanggal,
                        );
                        $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                        $where=array('id' => $this->input->post('id'));
                        $this->db->set('tbl_trip.status', 'Y');
                        $this->M_trip->update_where(self::xss($where,false));

                        $this->session->set_flashdata('error','FALSE 6 (RUMUS PREDIKSI) KASUS 6'); // KASUS 5
                        redirect(base_url('/Inputkomidelnew'));
                    }else{

                        $berat_no_1         =($jumlah_janjang_no_1/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
                        $berat_no_2         =($jumlah_janjang_no_2/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
                        $berat_no_3         =($jumlah_janjang_no_3/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;

                        $field= array(
                            'id_kerani_askep'=>$id_kerani_askep,
                            'id_kerani_kcs'=>$id_kerani_kcs,
                            'id_kebun'=>$id_kebun,
                            'id_afdeling'=>$id_afdeling,
                            'id_blok_no_1'=>$id_blok_no_1,
                            'id_blok_no_2'=>$id_blok_no_2,
                            'id_blok_no_3'=>$id_blok_no_3,
                            'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                            'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                            'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                            'berat_no_1'=>$berat_no_1,
                            'berat_no_2'=>$berat_no_2,
                            'berat_no_3'=>$berat_no_3,
                            'berat_TBS'=>$berat_TBS,
                            'berat_brondolan'=>$berat_brondolan,

                            'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                            'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                            'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,

                            'berondolan_timbang_1'=>$berondolan_timbang_1,
                            'berondolan_timbang_2'=>$berondolan_timbang_2,
                            'berondolan_timbang_3'=>$berondolan_timbang_3,

                            'berat_total'=>$berat_total,
                            'brt_prediksi_no_1'=>0,
                            'brt_prediksi_no_2'=>0,
                            'brt_prediksi_no_3'=>0,
                            'total_prediksi'=>0,
                            'sptbs'=>$sptbs,
                            'nomor_polisi_trek'=>$nomor_polisi_trek,
                            'tanggal'=>$tanggal,
                        );
                        $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                        $where=array('id' => $this->input->post('id'));
                        $this->db->set('tbl_trip.status', 'Y');
                        $this->M_trip->update_where(self::xss($where,false));

                        $this->session->set_flashdata('success','TRUE 4 (RUMUS BIASA) KASUS 4'); // KASUS 4
                        redirect(base_url('/Inputkomidelnew'));
                    }
                }
            }else{
                if($tahun_tanam_2==$tahun_tanam_3){
                    if($tahun_tanam_2==$tahun_tanam_3 && ($tahun_tanam_1 !=$tahun_tanam_2 || $tahun_tanam_1 !=$tahun_tanam_3)){
                        
                        $berat_no_1=($prediksi_komidel_1/$total_prediksi)*$berat_TBS;
                        $berat_no_2=($prediksi_komidel_2/$total_prediksi)*$berat_TBS;
                        $berat_no_3=($prediksi_komidel_3/$total_prediksi)*$berat_TBS;
                        $field= array(
                            'id_kerani_askep'=>$id_kerani_askep,
                            'id_kerani_kcs'=>$id_kerani_kcs,
                            'id_kebun'=>$id_kebun,
                            'id_afdeling'=>$id_afdeling,
                            'id_blok_no_1'=>$id_blok_no_1,
                            'id_blok_no_2'=>$id_blok_no_2,
                            'id_blok_no_3'=>$id_blok_no_3,
                            'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                            'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                            'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                            'berat_no_1'=>$berat_no_1,
                            'berat_no_2'=>$berat_no_2,
                            'berat_no_3'=>$berat_no_3,
                            'berat_TBS'=>$berat_TBS,
                            'berat_brondolan'=>$berat_brondolan,

                            'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                            'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                            'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,
            
                            'berondolan_timbang_1'=>$berondolan_timbang_1,
                            'berondolan_timbang_2'=>$berondolan_timbang_2,
                            'berondolan_timbang_3'=>$berondolan_timbang_3,

                            'berat_total'=>$berat_total,
                            'brt_prediksi_no_1'=>$prediksi_komidel_1,
                            'brt_prediksi_no_2'=>$prediksi_komidel_2,
                            'brt_prediksi_no_3'=>$prediksi_komidel_3,
                            'total_prediksi'=>$total_prediksi,
                            'sptbs'=>$sptbs,
                            'nomor_polisi_trek'=>$nomor_polisi_trek,
                            'tanggal'=>$tanggal,
                        );
                        $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                        $where=array('id' => $this->input->post('id'));
                        $this->db->set('tbl_trip.status', 'Y');
                        $this->M_trip->update_where(self::xss($where,false));

                        $this->session->set_flashdata('error','FALSE 7 (RUMUS PREDIKSI) KASUS 7'); // KASUS 7
                        redirect(base_url('/Inputkomidelnew'));
                    }else{

                        $berat_no_1         =($jumlah_janjang_no_1/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
                        $berat_no_2         =($jumlah_janjang_no_2/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;
                        $berat_no_3         =($jumlah_janjang_no_3/($jumlah_janjang_no_1+$jumlah_janjang_no_2+$jumlah_janjang_no_3))*$berat_TBS;

                        $field= array(
                            'id_kerani_askep'=>$id_kerani_askep,
                            'id_kerani_kcs'=>$id_kerani_kcs,
                            'id_kebun'=>$id_kebun,
                            'id_afdeling'=>$id_afdeling,
                            'id_blok_no_1'=>$id_blok_no_1,
                            'id_blok_no_2'=>$id_blok_no_2,
                            'id_blok_no_3'=>$id_blok_no_3,
                            'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                            'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                            'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                            'berat_no_1'=>$berat_no_1,
                            'berat_no_2'=>$berat_no_2,
                            'berat_no_3'=>$berat_no_3,
                            'berat_TBS'=>$berat_TBS,
                            'berat_brondolan'=>$berat_brondolan,

                            'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                            'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                            'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,

                            'berondolan_timbang_1'=>$berondolan_timbang_1,
                            'berondolan_timbang_2'=>$berondolan_timbang_2,
                            'berondolan_timbang_3'=>$berondolan_timbang_3,

                            'berat_total'=>$berat_total,
                            'brt_prediksi_no_1'=>0,
                            'brt_prediksi_no_2'=>0,
                            'brt_prediksi_no_3'=>0,
                            'total_prediksi'=>0,
                            'sptbs'=>$sptbs,
                            'nomor_polisi_trek'=>$nomor_polisi_trek,
                            'tanggal'=>$tanggal,
                        );
                        $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                        $where=array('id' => $this->input->post('id'));
                        $this->db->set('tbl_trip.status', 'Y');
                        $this->M_trip->update_where(self::xss($where,false));

                        $this->session->set_flashdata('success','TRUE 4 (RUMUS BIASA)');
                        redirect(base_url('/Inputkomidelnew'));
                    }
                    
                }else{

                    $berat_no_1=($prediksi_komidel_1/$total_prediksi)*$berat_TBS;
                    $berat_no_2=($prediksi_komidel_2/$total_prediksi)*$berat_TBS;
                    $berat_no_3=($prediksi_komidel_3/$total_prediksi)*$berat_TBS;
                    $field= array(
                        'id_kerani_askep'=>$id_kerani_askep,
                        'id_kerani_kcs'=>$id_kerani_kcs,
                        'id_kebun'=>$id_kebun,
                        'id_afdeling'=>$id_afdeling,
                        'id_blok_no_1'=>$id_blok_no_1,
                        'id_blok_no_2'=>$id_blok_no_2,
                        'id_blok_no_3'=>$id_blok_no_3,
                        'jumlah_janjang_no_1'=>$jumlah_janjang_no_1,
                        'jumlah_janjang_no_2'=>$jumlah_janjang_no_2,
                        'jumlah_janjang_no_3'=>$jumlah_janjang_no_3,
                        'berat_no_1'=>$berat_no_1,
                        'berat_no_2'=>$berat_no_2,
                        'berat_no_3'=>$berat_no_3,
                        'berat_TBS'=>$berat_TBS,
                        'berat_brondolan'=>$berat_brondolan,

                        'jumlah_taksir_brondolan_1'=>$jumlah_taksir_brondolan_1,
                        'jumlah_taksir_brondolan_2'=>$jumlah_taksir_brondolan_2,
                        'jumlah_taksir_brondolan_3'=>$jumlah_taksir_brondolan_3,
        
                        'berondolan_timbang_1'=>$berondolan_timbang_1,
                        'berondolan_timbang_2'=>$berondolan_timbang_2,
                        'berondolan_timbang_3'=>$berondolan_timbang_3,

                        'berat_total'=>$berat_total,
                        'brt_prediksi_no_1'=>$prediksi_komidel_1,
                        'brt_prediksi_no_2'=>$prediksi_komidel_2,
                        'brt_prediksi_no_3'=>$prediksi_komidel_3,
                        'total_prediksi'=>$total_prediksi,
                        'sptbs'=>$sptbs,
                        'nomor_polisi_trek'=>$nomor_polisi_trek,
                        'tanggal'=>$tanggal,
                    );
                    $this->M_tbl_sptbs_setelah_slip_timbang->insert(self::xss($field));
                    $where=array('id' => $this->input->post('id'));
                    $this->db->set('tbl_trip.status', 'Y');
                    $this->M_trip->update_where(self::xss($where,false));

                    $this->session->set_flashdata('error','FALSE 8 (RUMUS PREDIKSI) KASUS 8'); //KASUS 8
                    redirect(base_url('/Inputkomidelnew'));
                }
            }
        }
            
    }

    
    public function insertkomidelnew____________()
    {
        $tahun_tanam_1      =$this->input->post('tahun_tanam_1');
        $tahun_tanam_2      =$this->input->post('tahun_tanam_2');
        $tahun_tanam_3      =$this->input->post('tahun_tanam_3');
        $id_kerani_askep    =$this->input->post('id_kerani_askep');
        $id_kerani_kcs      =$this->input->post('id_kerani_kcs');
        $id_kebun           =$this->input->post('id_kebun');
        $id_afdeling        =$this->input->post('id_afdeling');
        $id_blok_no_1       =$this->input->post('id_blok_no_1');
        $id_blok_no_2       =$this->input->post('id_blok_no_2');
        $id_blok_no_3       =$this->input->post('id_blok_no_3');
        $jumlah_janjang_no_1=$this->input->post('jumlah_janjang_no_1');
        $jumlah_janjang_no_2=$this->input->post('jumlah_janjang_no_2');
        $jumlah_janjang_no_3=$this->input->post('jumlah_janjang_no_3');

        $berat_brondolan    =$this->input->post('berat_brondolan');
        
        $jumlah_taksir_brondolan_1    =$this->input->post('jumlah_taksir_brondolan_1');
        $jumlah_taksir_brondolan_2    =$this->input->post('jumlah_taksir_brondolan_2');
        $jumlah_taksir_brondolan_3    =$this->input->post('jumlah_taksir_brondolan_3');

        $berondolan_timbang_1=($berat_brondolan/($jumlah_taksir_brondolan_1+$jumlah_taksir_brondolan_2+$jumlah_taksir_brondolan_3))*$jumlah_taksir_brondolan_1;
        $berondolan_timbang_2=($berat_brondolan/($jumlah_taksir_brondolan_1+$jumlah_taksir_brondolan_2+$jumlah_taksir_brondolan_3))*$jumlah_taksir_brondolan_2;
        $berondolan_timbang_3=($berat_brondolan/($jumlah_taksir_brondolan_1+$jumlah_taksir_brondolan_2+$jumlah_taksir_brondolan_3))*$jumlah_taksir_brondolan_3;

        $nomor_polisi_trek  =$this->input->post('nomor_polisi_trek');
        $sptbs              =$this->input->post('sptbs');
        $tanggal            =$this->input->post('tanggal');
        $berat_TBS          =$this->input->post('berat_total_tbs');

        $prediksi_komidel_1 =$this->input->post('prediksi_komidel_1')*$jumlah_janjang_no_1;
        $prediksi_komidel_2 =$this->input->post('prediksi_komidel_2')*$jumlah_janjang_no_2;
        $prediksi_komidel_3 =$this->input->post('prediksi_komidel_3')*$jumlah_janjang_no_3;
        
        $total_prediksi     =$prediksi_komidel_1+$prediksi_komidel_2+$prediksi_komidel_3;
        $berat_total        =$berat_TBS+$berat_brondolan;

        if(($tahun_tanam_2 == "" || $tahun_tanam_2 == 0) && ($tahun_tanam_3 == "" || $tahun_tanam_3 == 0) ) {
            $this->session->set_flashdata('success','TRUE (RUMUS BIASA) KASUS 1'); // KASUS 1
            redirect(base_url('/Inputkomidelnew'));
        }else if(($tahun_tanam_2 != "" || $tahun_tanam_2 != 0) && ($tahun_tanam_3 == "" || $tahun_tanam_3 == 0)){
            if($tahun_tanam_1==$tahun_tanam_2){
                $this->session->set_flashdata('success','TRUE 1 (RUMUS BIASA) KASUS 2'); // KASUS 2
                redirect(base_url('/Inputkomidelnew'));
            }else{
                $this->session->set_flashdata('error','FALSE 1 (RUMUS PREDIKSI) KASUS 3'); // KASUS 3
                redirect(base_url('/Inputkomidelnew'));
            }
        }else if(($tahun_tanam_2 == "" || $tahun_tanam_2 == 0) && ($tahun_tanam_3 != "" || $tahun_tanam_3 != 0)){
            if($tahun_tanam_1==$tahun_tanam_3){
                $this->session->set_flashdata('success','TRUE 2 (RUMUS BIASA) KASUS 2'); // KASUS 2
                redirect(base_url('/Inputkomidelnew'));
            }else{
                $this->session->set_flashdata('error','FALSE 2 (RUMUS PREDIKSI) KASUS 3'); // KASUS 3
                redirect(base_url('/Inputkomidelnew'));
            }
        }else if($tahun_tanam_1 > 0 && $tahun_tanam_2 > 0 && $tahun_tanam_3 > 0 ){
            $variabelrray=array($tahun_tanam_2,$tahun_tanam_3);
            if(in_array($tahun_tanam_1,$variabelrray)){
                if($tahun_tanam_1==$tahun_tanam_2 && ($tahun_tanam_3 !=$tahun_tanam_1 || $tahun_tanam_3 !=$tahun_tanam_2)){
                    $this->session->set_flashdata('error','FALSE 5 (RUMUS PREDIKSI) KASUS 5'); // KASUS 5
                    redirect(base_url('/Inputkomidelnew'));
                }else{
                    if($tahun_tanam_1==$tahun_tanam_3 && ($tahun_tanam_2 !=$tahun_tanam_1 || $tahun_tanam_2 !=$tahun_tanam_3)){
                        $this->session->set_flashdata('error','FALSE 6 (RUMUS PREDIKSI) KASUS 6'); // KASUS 5
                        redirect(base_url('/Inputkomidelnew'));
                    }else{
                        $this->session->set_flashdata('success','TRUE 4 (RUMUS BIASA) KASUS 4'); // KASUS 4
                        redirect(base_url('/Inputkomidelnew'));
                    }
                }
            }else{
                if($tahun_tanam_2==$tahun_tanam_3){
                    if($tahun_tanam_2==$tahun_tanam_3 && ($tahun_tanam_1 !=$tahun_tanam_2 || $tahun_tanam_1 !=$tahun_tanam_3)){
                        $this->session->set_flashdata('error','FALSE 7 (RUMUS PREDIKSI) KASUS 7'); // KASUS 7
                        redirect(base_url('/Inputkomidelnew'));
                    }else{
                        $this->session->set_flashdata('success','TRUE 4 (RUMUS BIASA)');
                        redirect(base_url('/Inputkomidelnew'));
                    }
                    
                }else{
                    $this->session->set_flashdata('error','FALSE 8 (RUMUS PREDIKSI) KASUS 8'); //KASUS 8
                    redirect(base_url('/Inputkomidelnew'));
                }
            }
        }
            
    }


















































}
