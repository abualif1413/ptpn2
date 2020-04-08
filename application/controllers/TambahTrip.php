<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TambahTrip extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_trip','M_trip');
        $this->load->model('backend/M_keranikcs','M_keranikcs');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
            $data['title']  		= 'Halaman Trip';
            $data['view_content'] 	= 'backend/tambahtrip/tambahtrip';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }

    }
        
    public function add_trip()
    {  
    if(self::isPost()){
            $data = self::validations(array(
                    array('field'=>'id_blok_1','label'=>'Blok','rules'=>'required'),
                    array('field'=>'jumlah_janjang_1','label'=>'Jumlah Janjang','rules'=>'required'),
                    array('field'=>'id_blok_2','label'=>'Blok','rules'=>'required'),
                    array('field'=>'jumlah_janjang_2','label'=>'Jumlah Janjang','rules'=>'required'),
                    array('field'=>'id_blok_3','label_3'=>'Blok','rules'=>'required'),
                    array('field'=>'jumlah_janjang_3','label'=>'Jumlah Janjang','rules'=>'required'),
                    array('field'=>'jumlah_brondolan_1','label'=>'J.Brondolan No 1','rules'=>'required'),
                    array('field'=>'jumlah_brondolan_2','label'=>'J.Brondolan No 2','rules'=>'required'),
                    array('field'=>'jumlah_brondolan_3','label'=>'J.Brondolan No 3','rules'=>'required'),
                    array('field'=>'nomor_polisi_trek','label'=>'Nomor Polisi Trek','rules'=>'required'),
            ));

            if($data === true){
            $user=$this->session->userdata('user');
            extract($user);
            $Select = array(
                    "tbl_kerani_kcs.token as id_kerani_kcs",
                    "tbl_kerani_kcs.id_kebun",
                    "tbl_kerani_kcs.id_afdeling",
                    "tbl_kerani_kcs.id_kerani_askep"
            );
            $Where = array('tbl_kerani_kcs.token' => $token);
            $result =$this->M_keranikcs->get_result2($Select,$Where,false,false,false);
            foreach ($result as $key);
            $id_kerani_kcs=$key->id_kerani_kcs;
            $id_kebun=$key->id_kebun;
            $id_afdeling=$key->id_afdeling;
            $id_kerani_askep=$key->id_kerani_askep;

            $field= array(
                'id_kerani_askep'=>$id_kerani_askep,
                'id_kerani_kcs'=>$token,
                'id_kebun'=>$id_kebun,
                'id_afdeling'=>$id_afdeling,
                'id_blok_1'=>$this->input->post('id_blok_1'),
                'id_blok_2'=>$this->input->post('id_blok_2'),
                'id_blok_3'=>$this->input->post('id_blok_3'),
                'jumlah_janjang_1'=>$this->input->post('jumlah_janjang_1'),
                'jumlah_janjang_2'=>$this->input->post('jumlah_janjang_2'),
                'jumlah_janjang_3'=>$this->input->post('jumlah_janjang_3'),
                'jumlah_taksir_brondolan_1'=>$this->input->post('jumlah_brondolan_1'),
                'jumlah_taksir_brondolan_2'=>$this->input->post('jumlah_brondolan_2'),
                'jumlah_taksir_brondolan_3'=>$this->input->post('jumlah_brondolan_3'),
                'sptbs'=>$this->input->post('no_sptbs'),
                'nomor_polisi_trek'=>$this->input->post('nomor_polisi_trek'),
                'tanggal'=> date("Y-m-d H:i:s"),
            );
                $where=array(
                    'sptbs'=>$this->input->post('no_sptbs')
                );
                if($this->M_trip->validasiData($where)){
                    $data=array('success'=>false,'error'=>'Data Sudah Ada');
                }else{

                    $blok = array($field['id_blok_2'], $field['id_blok_2']);
                    if (in_array($field['id_blok_1'], $blok))
                    {
                       $data=array('success'=>false,'error'=>'Blok Tidak Boleh Sama');
                    }
                    else
                    {
                        $data=$this->M_trip->insert(self::xss($field));
                        $data=array('success'=>true,'type'=>'Add');
                    }
                   
                }
                    
            }  
        }
        self::json($data);
    }



    public function data_trip()
    {   
        if($this->isPost()){
            $data = array();
            $orderBy = array(null,"tbl_trip.id");
            $search = array(
                "tbl_trip.id",
                "tbl_trip.sptbs",
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
            );
            $select = array(
                "tbl_trip.*",
                "tbl_kebun.nama_kebun as kebun",
                "tbl_afdeling.nama_afdeling as afdeling",
                "tbl_kerani_kcs.nama_lengkap as keranikcs",
                "tbl_blok_1.blok as blok_1",
                "tbl_blok_1.tahun_tanam as tahun_tanam_1 ",
                "tbl_blok_2.blok as blok_2",
                "tbl_blok_2.tahun_tanam as tahun_tanam_2",
                "tbl_blok_3.blok as blok_3",
                "tbl_blok_3.tahun_tanam as tahun_tanam_3",

            );
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_trip.id_kebun','left'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_trip.id_afdeling','left'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_trip.id_kerani_kcs','left'),
                array('tbl_blok as tbl_blok_1','tbl_blok_1.id=tbl_trip.id_blok_1','left'),
                array('tbl_blok as tbl_blok_2','tbl_blok_2.id=tbl_trip.id_blok_2','left'),
                array('tbl_blok as tbl_blok_3','tbl_blok_3.id=tbl_trip.id_blok_3','left'),
            );

            $where=array('tbl_trip.id_kerani_kcs'=>$this->session->token);
            $result = $this->M_trip->findDataTable($orderBy,$search,$select,$where,$join,false);
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
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Hapus</button>';             
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_trip->findDataTableOutput($data,$search,$select,$where,$join,false);
        }
    }

    public function Edit_Trip()
    {   
        $select = array(
        "tbl_trip.*",
        "tbl_kebun.nama_kebun as kebun",
        "tbl_afdeling.nama_afdeling as afdeling",
        "tbl_kerani_kcs.nama_lengkap as keranikcs",

        "tbl_blok_1.blok as blok_1",
        "tbl_blok_1.tahun_tanam as tahun_tanam_1 ",

        "tbl_blok_2.blok as blok_2",
        "tbl_blok_2.tahun_tanam as tahun_tanam_2",

        "tbl_blok_3.blok as blok_3",
        "tbl_blok_3.tahun_tanam as tahun_tanam_3",

        );
        $join = array(
        array('tbl_kebun','tbl_kebun.id=tbl_trip.id_kebun','left'),
        array('tbl_afdeling','tbl_afdeling.id=tbl_trip.id_afdeling','left'),
        array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_trip.id_kerani_kcs','left'),
        array('tbl_blok as tbl_blok_1','tbl_blok_1.id=tbl_trip.id_blok_1','left'),
        array('tbl_blok as tbl_blok_2','tbl_blok_2.id=tbl_trip.id_blok_2','left'),
        array('tbl_blok as tbl_blok_3','tbl_blok_3.id=tbl_trip.id_blok_3','left'),
        );
        $where = array('tbl_trip.id'=>$this->input->post('id'));
        $result =$this->M_trip->get_where($select,$where,false,$join,false,false);
        self::json($result);
    }


    public function Update_Trip()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_blok_1','label'=>'Blok','rules'=>'required'),
                array('field'=>'jumlah_janjang_1','label'=>'Jumlah Janjang','rules'=>'required'),
                array('field'=>'id_blok_2','label'=>'Blok','rules'=>'required'),
                array('field'=>'jumlah_janjang_2','label'=>'Jumlah Janjang','rules'=>'required'),
                array('field'=>'id_blok_3','label_3'=>'Blok','rules'=>'required'),
                array('field'=>'jumlah_janjang_3','label'=>'Jumlah Janjang','rules'=>'required'),
                array('field'=>'jumlah_brondolan_1','label'=>'J.Brondolan No 1','rules'=>'required'),
                array('field'=>'jumlah_brondolan_2','label'=>'J.Brondolan No 2','rules'=>'required'),
                array('field'=>'jumlah_brondolan_3','label'=>'J.Brondolan No 3','rules'=>'required'),
                array('field'=>'nomor_polisi_trek','label'=>'Nomor Polisi Trek','rules'=>'required'),
            ));
            if($data === true){
                $field= array(
                        'id_blok_1'=>$this->input->post('id_blok_1'),
                        'id_blok_2'=>$this->input->post('id_blok_2'),
                        'id_blok_3'=>$this->input->post('id_blok_3'),
                        'jumlah_janjang_1'=>$this->input->post('jumlah_janjang_1'),
                        'jumlah_janjang_2'=>$this->input->post('jumlah_janjang_2'),
                        'jumlah_janjang_3'=>$this->input->post('jumlah_janjang_3'),
                        'jumlah_taksir_brondolan_1'=>$this->input->post('jumlah_brondolan_1'),
                        'jumlah_taksir_brondolan_2'=>$this->input->post('jumlah_brondolan_2'),
                        'jumlah_taksir_brondolan_3'=>$this->input->post('jumlah_brondolan_3'),
                        'nomor_polisi_trek'=>$this->input->post('nomor_polisi_trek'),
                        'tanggal'=>$this->input->post('tanggal'),
                );
                $primary_values=$this->input->post('id');
                $data=$this->M_trip->update_by_id(self::xss($field),$primary_values);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }


    public function Delete_Trip()
    {
        $primary_values=$this->input->post('id');
        $result = $this->M_trip->delete_id($primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }

    public function nomor_sptbs()
    {   
        $user=$this->session->userdata('user');
        extract($user);
        $Select = array(
                "tbl_kerani_kcs.id_kebun",
                "tbl_kerani_kcs.id_afdeling",
        );
        $Where = array('tbl_kerani_kcs.token' => $token);
        $result =$this->M_keranikcs->get_result2($Select,$Where,false,false,false);
        foreach ($result as $key);
        $id_kebun=$key->id_kebun;
        $id_afdeling=$key->id_afdeling;
        $data['kode']=$this->M_trip->no_sptbs($id_kebun,$id_afdeling);
        self::json($data);
    }
















}
