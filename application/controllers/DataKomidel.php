<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataKomidel extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_tbl_sptbs_setelah_slip_timbang','M_tbl_sptbs_setelah_slip_timbang');
        $this->load->model('backend/M_komidel_new','M_komidel_new');
        
    }

    public function index()
    {   
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman Data Nilai Komidel';
            $data['view_content']   = 'backend/datakomidel/datakomidel';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
    }


    public function data_komidel()
    {   
        if($this->isPost()){
            $this->load->model('backend/M_keraniaskep','M_keraniaskep');
            $Select = array("tbl_kerani_askep.id");
            $Where = array('tbl_kerani_askep.token' => $this->session->token);
            $result =$this->M_keraniaskep->get_result2($Select,$Where,false,false,false);
            foreach ($result as $key);
            $id=$key->id;

            $tanggal=$this->input->post('tanggal');
            $afdeling=$this->input->post('afdeling');
            $where = array(
                'tbl_sptbs_setelah_slip_timbang.tanggal'=> $tanggal,
                'tbl_sptbs_setelah_slip_timbang.id_kerani_askep'=>$id,
                'tbl_sptbs_setelah_slip_timbang.id_afdeling'=>$afdeling,
            );
            
            $join1 = array(
                array('tbl_kebun as tbl_kebun_no_1 ','tbl_kebun_no_1.id=tbl_sptbs_setelah_slip_timbang.id_kebun'),
                array('tbl_afdeling as tbl_afdeling_no_1','tbl_afdeling_no_1.id=tbl_sptbs_setelah_slip_timbang.id_afdeling'),
                array('tbl_blok as tbl_blok_no_1','tbl_blok_no_1.id=tbl_sptbs_setelah_slip_timbang.id_blok_no_1'),
            );

            $select1 = array(
                "tbl_sptbs_setelah_slip_timbang.tanggal",
                "tbl_sptbs_setelah_slip_timbang.id_kerani_askep",
                "tbl_sptbs_setelah_slip_timbang.id_kerani_kcs",
                "tbl_sptbs_setelah_slip_timbang.id_kebun",
                "tbl_sptbs_setelah_slip_timbang.id_afdeling",
                "tbl_sptbs_setelah_slip_timbang.id_blok_no_1",
                "SUM(tbl_sptbs_setelah_slip_timbang.jumlah_janjang_no_1) AS tandan_1",
                "SUM(tbl_sptbs_setelah_slip_timbang.berat_no_1) AS berat_no_1",
                "tbl_kebun_no_1.nama_kebun",
                "tbl_afdeling_no_1.nama_afdeling",
                "tbl_blok_no_1.blok",
                "tbl_blok_no_1.tahun_tanam",
                "SUM(tbl_sptbs_setelah_slip_timbang.jumlah_taksir_brondolan_1) AS jumlah_taksir_brondolan_1",
                "SUM(tbl_sptbs_setelah_slip_timbang.berondolan_timbang_1) AS berondolan_timbang_1",
            );
            $group_by1 ='id_blok_no_1';
            $data1=$this->M_tbl_sptbs_setelah_slip_timbang->get_result($select1,$where,$join1,$group_by1,false);

            $select2 = array(
                "tbl_sptbs_setelah_slip_timbang.tanggal",
                "tbl_sptbs_setelah_slip_timbang.id_kerani_askep",
                "tbl_sptbs_setelah_slip_timbang.id_kerani_kcs",
                "tbl_sptbs_setelah_slip_timbang.id_kebun",
                "tbl_sptbs_setelah_slip_timbang.id_afdeling",
                "tbl_sptbs_setelah_slip_timbang.id_blok_no_2",
                "SUM(tbl_sptbs_setelah_slip_timbang.jumlah_janjang_no_2) AS tandan_2",
                "SUM(tbl_sptbs_setelah_slip_timbang.berat_no_2) AS berat_no_2",
                "tbl_kebun_no_2.nama_kebun",
                "tbl_afdeling_no_2.nama_afdeling",
                "tbl_blok_no_2.blok",
                "tbl_blok_no_2.tahun_tanam",
                "SUM(tbl_sptbs_setelah_slip_timbang.jumlah_taksir_brondolan_2) AS jumlah_taksir_brondolan_2",
                "SUM(tbl_sptbs_setelah_slip_timbang.berondolan_timbang_2) AS berondolan_timbang_2",
            );
            $join2 = array(
                array('tbl_kebun as tbl_kebun_no_2 ','tbl_kebun_no_2.id=tbl_sptbs_setelah_slip_timbang.id_kebun'),
                array('tbl_afdeling as tbl_afdeling_no_2','tbl_afdeling_no_2.id=tbl_sptbs_setelah_slip_timbang.id_afdeling'),
                array('tbl_blok as tbl_blok_no_2','tbl_blok_no_2.id=tbl_sptbs_setelah_slip_timbang.id_blok_no_2'),
            );
            $group_by2 ='id_blok_no_2';
            $data2 =$this->M_tbl_sptbs_setelah_slip_timbang->get_result($select2,$where,$join2,$group_by2,false);

            $select3 = array(
                "tbl_sptbs_setelah_slip_timbang.tanggal",
                "tbl_sptbs_setelah_slip_timbang.id_kerani_askep",
                "tbl_sptbs_setelah_slip_timbang.id_kerani_kcs",
                "tbl_sptbs_setelah_slip_timbang.id_kebun",
                "tbl_sptbs_setelah_slip_timbang.id_afdeling",
                "tbl_sptbs_setelah_slip_timbang.id_blok_no_3",
                "SUM(tbl_sptbs_setelah_slip_timbang.jumlah_janjang_no_3) AS tandan_3",
                "SUM(tbl_sptbs_setelah_slip_timbang.berat_no_3) AS berat_no_3",
                "tbl_kebun_no_3.nama_kebun",
                "tbl_afdeling_no_3.nama_afdeling",
                "tbl_blok_no_3.blok",
                "tbl_blok_no_3.tahun_tanam",
                "SUM(tbl_sptbs_setelah_slip_timbang.jumlah_taksir_brondolan_3) AS jumlah_taksir_brondolan_3",
                "SUM(tbl_sptbs_setelah_slip_timbang.berondolan_timbang_3) AS berondolan_timbang_3",
            );
            $join3 = array(
                array('tbl_kebun as tbl_kebun_no_3','tbl_kebun_no_3.id=tbl_sptbs_setelah_slip_timbang.id_kebun'),
                array('tbl_afdeling as tbl_afdeling_no_3','tbl_afdeling_no_3.id=tbl_sptbs_setelah_slip_timbang.id_afdeling'),
                array('tbl_blok as tbl_blok_no_3','tbl_blok_no_3.id=tbl_sptbs_setelah_slip_timbang.id_blok_no_3'),
            );
            $group_by3 ='id_blok_no_3';
            $data3 =$this->M_tbl_sptbs_setelah_slip_timbang->get_result($select3,$where,$join3,$group_by3,false);
            $hasil1=array();
            $hasil2=array();
            $hasil3=array();

            if($data1){
                foreach ($data1 as $value1) {
                
                    $hasil1[]=array(
                        'tanggal'=>$value1->tanggal,
                        'id_kerani_askep'=>$value1->id_kerani_askep,
                        'id_kerani_kcs'=>$value1->id_kerani_kcs,
                        'id_kebun'=>$value1->id_kebun,
                        'id_afdeling'=>$value1->id_afdeling,
                        'id_blok'=>$value1->id_blok_no_1,
                        'kebun'=>$value1->nama_kebun,
                        'afdeling'=>$value1->nama_afdeling,
                        'blok'=>$value1->blok,
                        'tahun_tanam'=>$value1->tahun_tanam,
                        'total_janjang_blok'=>$value1->tandan_1,
                        'total_berat_blok'=>$value1->berat_no_1,
                        'bron_tak'=>$value1->jumlah_taksir_brondolan_1,
                        'bron_tim'=>$value1->berondolan_timbang_1,
                    );
                }
            }else{
                $hasil1['data']=0;
            }
            
            if($data2){
                foreach ($data2 as $value2) {
                    $hasil2[]=array(
                        'tanggal'=>$value2->tanggal,
                        'id_kerani_askep'=>$value2->id_kerani_askep,
                        'id_kerani_kcs'=>$value2->id_kerani_kcs,
                        'id_kebun'=>$value2->id_kebun,
                        'id_afdeling'=>$value2->id_afdeling,
                        'id_blok'=>$value2->id_blok_no_2,
                        'kebun'=>$value2->nama_kebun,
                        'afdeling'=>$value2->nama_afdeling,
                        'blok'=>$value2->blok,
                        'tahun_tanam'=>$value2->tahun_tanam,
                        'total_janjang_blok'=>$value2->tandan_2,
                        'total_berat_blok'=>$value2->berat_no_2,
                        'bron_tak'=>$value2->jumlah_taksir_brondolan_2,
                        'bron_tim'=>$value2->berondolan_timbang_2,
                   );
               }
            }else{
                $data2['data']=0;
            }
            
            if($data3){
                foreach ($data3 as $value3) {
                    $hasil3[]=array(
                        'tanggal'=>$value3->tanggal,
                        'id_kerani_askep'=>$value3->id_kerani_askep,
                        'id_kerani_kcs'=>$value3->id_kerani_kcs,
                        'id_kebun'=>$value3->id_kebun,
                        'id_afdeling'=>$value3->id_afdeling,
                        'id_blok'=>$value3->id_blok_no_3,
                        'kebun'=>$value3->nama_kebun,
                        'afdeling'=>$value3->nama_afdeling,
                        'blok'=>$value3->blok,
                        'tahun_tanam'=>$value3->tahun_tanam,
                        'total_janjang_blok'=>$value3->tandan_3,
                        'total_berat_blok'=>$value3->berat_no_3,
                        'bron_tak'=>$value3->jumlah_taksir_brondolan_3,
                        'bron_tim'=>$value3->berondolan_timbang_3,
                   );
               }
            }else{
                $data3['data']=0;
            }
            $hasil=array_merge($hasil1,$hasil2);
            $hasil=array_merge($hasil,$hasil3);
            $res = array();
            $res2 = array();
            $res3 = array();
            $res4 = array();
            $resultData = array();
            foreach($hasil AS $val){
                // total_janjang_blok
                if(isset($res[$val["id_blok"]])){
                    $total_janjang_blok = $res[$val["id_blok"]] = ['sum' => $res[$val["id_blok"]]['sum'] + $val["total_janjang_blok"]];
                    $val["total_janjang_blok"] = $total_janjang_blok["sum"];
                } else {
                    $total_janjang_blok = $res[$val["id_blok"]] = ['sum' => $val["total_janjang_blok"]];
                    $val["total_janjang_blok"] = $total_janjang_blok["sum"];
                }
                // total_berat_blok
                if(isset($res2[$val["id_blok"]])){
                    $total_berat_blok = $res2[$val["id_blok"]] = ['sum' => $res2[$val["id_blok"]]['sum'] + $val["total_berat_blok"]];
                    $val["total_berat_blok"] = $total_berat_blok["sum"];
                } else {
                    $total_berat_blok = $res2[$val["id_blok"]] = ['sum' => $val["total_berat_blok"]];
                    $val["total_berat_blok"] = $total_berat_blok["sum"];
                }
                // komidel
                $total_berat_blok_=$val["total_berat_blok"];
                $total_janjang_blok_=$val["total_janjang_blok"];
                $val['komidel']=$total_berat_blok_/$total_janjang_blok_;
                // bron_tak
                if(isset($res3[$val["id_blok"]])){
                    $bron_tak = $res3[$val["id_blok"]] = ['sum' => $res3[$val["id_blok"]]['sum'] + $val["bron_tak"]];
                    $val["bron_tak"] = $bron_tak["sum"];
                } else {
                    $bron_tak = $res3[$val["id_blok"]] = ['sum' => $val["bron_tak"]];
                    $val["bron_tak"] = $bron_tak["sum"];
                }
                // bron_tim
                if(isset($res4[$val["id_blok"]])){
                    $bron_tim = $res4[$val["id_blok"]] = ['sum' => $res4[$val["id_blok"]]['sum'] + $val["bron_tim"]];
                    $val["bron_tim"] = $bron_tim["sum"];
                } else {
                    $bron_tim = $res4[$val["id_blok"]] = ['sum' => $val["bron_tim"]];
                    $val["bron_tim"] = $bron_tim["sum"];
                }
                // koef
                $bron_tim_sum=0;
                $bron_tak_sum=0;
                foreach($hasil AS $val32){
                    $bron_tim_sum +=$val32['bron_tim'];
                    $bron_tak_sum +=$val32['bron_tak'];
                }
                if($bron_tim_sum==0 && $bron_tak_sum==0){
                    $val['koef']= number_format((0),2);
                }else{
                    $val['koef']=($bron_tim_sum/$bron_tak_sum)*100;
                }
                
                $resultData[$val["id_blok"]] = $val;   
            }
            sort($resultData);
        }
        self::json($resultData);
    }


    
    public function Validasi_komidel()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'tanggal[]','label'=>'tanggal','rules'=>'required'),
                array('field'=>'id_kerani_askep[]','label'=>'id_kerani_askep','rules'=>'required'),
                array('field'=>'id_kerani_kcs[]','label'=>'id_kerani_kcs','rules'=>'required'),
                array('field'=>'id_kebun[]','label'=>'id_kebun','rules'=>'required'),
                array('field'=>'id_afdeling[]','label'=>'id_afdeling','rules'=>'required'),
                array('field'=>'id_blok[]','label'=>'id_blok','rules'=>'required'),
                array('field'=>'tahun_tanam[]','label'=>'tahun_tanam','rules'=>'required'),
                array('field'=>'total_janjang_blok[]','label'=>'total_janjang_blok','rules'=>'required'),
                array('field'=>'total_berat_blok[]','label'=>'total_berat_blok','rules'=>'required'),
                array('field'=>'komidel[]','label'=>'komidel','rules'=>'required'),
                array('field'=>'bron_tak[]','label'=>'bron_tak','rules'=>'required'),
                array('field'=>'bron_tim[]','label'=>'bron_tim','rules'=>'required'),
                array('field'=>'koef[]','label'=>'koef','rules'=>'required'),
            ));
            if($data === true){
                $blok=$this->input->post('id_blok');
                $no=0;
                $index = 0;
                $number = 1;
                foreach($blok as $datablok){
                    $where=array(
                        'tanggal'=>$this->input->post('tanggal')[$no],
                        'id_kerani_askep'=>$this->input->post('id_kerani_askep')[$no],
                        'id_kerani_kcs'=>$this->input->post('id_kerani_kcs')[$no],
                        'id_kebun'=>$this->input->post('id_kebun')[$no],
                        'id_afdeling'=>$this->input->post('id_afdeling')[$no],
                        'id_blok'=>$datablok,
                        'tahun_tanam'=>$this->input->post('tahun_tanam')[$no],
                        'total_janjang_blok'=>$this->input->post('total_janjang_blok')[$no],
                        'total_berat_blok'=>$this->input->post('total_berat_blok')[$no],
                        'komidel'=>$this->input->post('komidel')[$no],
                        'bron_tak'=>$this->input->post('bron_tak')[$no],
                        'bron_tim'=>$this->input->post('bron_tim')[$no],
                        'koef'=>$this->input->post('koef')[$no],
                    );
                    if($this->M_komidel_new->validasiData($where)){
                        $sudah_ada[]='<b>'.$number.'. </b><b style="color:#fff;">Data Blok Sudah Ada Pada Tanggal :'.$where['tanggal'].' Nilai : '.$where['komidel'].'</b><br>';
                        $data=array('error'=>$sudah_ada);
                    }else{
                        $field = array();
                        array_push($field, array(
                            'tanggal'=>$this->input->post('tanggal')[$index],
                            'id_kerani_askep'=>$this->input->post('id_kerani_askep')[$index],
                            'id_kerani_kcs'=>$this->input->post('id_kerani_kcs')[$index],
                            'id_kebun'=>$this->input->post('id_kebun')[$index],
                            'id_afdeling'=>$this->input->post('id_afdeling')[$index],
                            'id_blok'=>$datablok,
                            'tahun_tanam'=>$this->input->post('tahun_tanam')[$index],
                            'total_janjang_blok'=>$this->input->post('total_janjang_blok')[$index],
                            'total_berat_blok'=>$this->input->post('total_berat_blok')[$index],
                            'komidel'=>$this->input->post('komidel')[$index],
                            'bron_tak'=>$this->input->post('bron_tak')[$index],
                            'bron_tim'=>$this->input->post('bron_tim')[$index],
                            'koef'=>$this->input->post('koef')[$index],
                        ));
                        $data=$this->M_komidel_new->insert_batch(self::xss($field));
                        $data=array('success'=>true,'type'=>'Add');
                    }
                  
                  $no++;
                  $index++;
                  $number++;
                }
            }  
        }
        self::json($data);
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
