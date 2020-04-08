<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataHasilPanen extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        $this->load->model('backend/M_datahasilpanen','M_datahasilpanen');
        $this->load->model('backend/M_kebun','M_kebun');
        $this->load->model('backend/M_afdeling','M_afdeling');
        $this->load->model('backend/M_alat','M_alat');
        $this->load->model('backend/M_blok','M_blok');
        $this->load->model('backend/M_komidel','M_komidel');
        $this->load->model('backend/M_mandor','M_mandor');
        $this->load->model('backend/M_keranikcs','M_keranikcs');

        
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
    }

	public function index()
	{   
        
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Data Hasil Panen';
            $data['view_content'] 	= 'backend/datahasilpanen/datahasilpanen';
            $where=array('id_kerani_kcs'=>$this->session->token);
            $data['pemanen']        = $this->M_DataPemanen->getAllWhere($where)->result();
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $data['afdeling']       = $this->M_afdeling->getAll()->result();
            $data['alat']       = $this->M_alat->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }

    public function add_data_hasil_panen()
	{  
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_pemanen','label'=>'Nama Pemanen','rules'=>'required'),
                array('field'=>'jmlh_panen','label'=>'Jumlah Panen','rules'=>'required'),
                array('field'=>'tph','label'=>'TPH','rules'=>'required'),
                array('field'=>'blok','label'=>'BLOK','rules'=>'required'),
                array('field'=>'id_alat','label'=>'Pengganti Alat Premi','rules'=>'required'),
                array('field'=>'jmlh_brondolan','label'=>'Brondolan','rules'=>'required'),
            ));

            if($data === true){
                $user=$this->session->userdata('user');
                extract($user);
                $Select = array(
                    "tbl_kerani_kcs.token",
                    "tbl_kerani_kcs.id_kebun",
                    "tbl_kerani_kcs.id_afdeling",
                    "tbl_kerani_kcs.id_kerani_askep"
                );
                $Where = array('tbl_kerani_kcs.token' => $token);
                $result =$this->M_keranikcs->get_result2($Select,$Where,false,false,false);
                foreach ($result as $key);
                $token=$key->token;
                $id_kebun=$key->id_kebun;
                $id_afdeling=$key->id_afdeling;
                $id_kerani_askep=$key->id_kerani_askep;

                $field= array(
                    'id_kerani_askep'=>$id_kerani_askep,
                    'id_kerani_kcs'=>$token,
                    'id_kebun'=>$id_kebun,
                    'id_afdeling'=>$id_afdeling,
                    'id_pemanen'=>$this->input->post('id_pemanen'),
                    'jmlh_panen'=>$this->input->post('jmlh_panen'),
                    'tph'=>$this->input->post('tph'),
                    'blok'=>$this->input->post('blok'),
                    'id_alat'=>$this->input->post('id_alat'),
                    'jmlh_brondolan'=>$this->input->post('jmlh_brondolan'),
                    'tanggal'=> date("Y-m-d H:i:s"),
                );
                $where=array(
                    'id_kerani_kcs' => $token,
                    'id_kebun' => $id_kebun,
                    'id_afdeling' => $id_afdeling,
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
    

    public function data_hasil_panen()
    {   
        if($this->isPost()){
            $Select = array(
                "tbl_kerani_kcs.token",
            );
            $Where = array('tbl_kerani_kcs.token'=>$this->session->token);
            $result =$this->M_keranikcs->get_result2($Select,$Where,false,false,false);
            foreach ($result as $key);
            $token=$key->token;


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
                "tbl_pemanen.id_mandor",
                "tbl_afdeling.nama_afdeling",
                "tbl_kerani_kcs.nama_lengkap as keranikcs",
                "tbl_blok.blok as kode_blok",
                "tbl_mandor.nama_lengkap as mandor",
            );
            $join = array(
                array('tbl_pemanen','tbl_pemanen.id=tbl_panen.id_pemanen','left'),
                array('tbl_mandor','tbl_mandor.token=tbl_pemanen.id_mandor','left'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_panen.id_kerani_kcs','left'),
                array('tbl_kebun','tbl_kebun.id=tbl_panen.id_kebun','left'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_panen.id_afdeling','left'),
                array('tbl_alat','tbl_alat.id=tbl_panen.id_alat','left'),
                array('tbl_blok','tbl_blok.id=tbl_panen.blok','left'),
            );
            $where=array('tbl_panen.id_kerani_kcs' => $token);
            $group_by='tbl_panen.tanggal,tbl_panen.id_pemanen';
            $result = $this->M_datahasilpanen->findDataTable($orderBy,$search,$select,$where,$join,$group_by);
            
            foreach ($result as $item) {
                $item->tanggal= date('d-m-Y', strtotime($item->tanggal));

                $item->jmlh_brondolan= $item->jmlh_brondolan.' Kg';
                $item->premi_alat="Rp " . number_format($item->premi_alat,0,',','.');
                if($item->status=='Y'){
                    $btnAction = '<button class="btn btn-success btn-outline-success btn-mini"><i class="fa fa-pencil-square-o"></i>Verifikasi</button>';
                }else{
                    $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                    $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                }
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_datahasilpanen->findDataTableOutput($data,$search,$select,$where,$join,$group_by);
        }
    }


    public function Edit_hasil_panen()
    {   
        $select = array("tbl_panen.*","tbl_kebun.nama_kebun","tbl_pemanen.nama_pemanen","tbl_pemanen.barcode","tbl_afdeling.nama_afdeling","tbl_kerani_kcs.nama_lengkap");
        $join = array(
            array('tbl_pemanen','tbl_pemanen.id=tbl_panen.id_pemanen','left'),
            array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_panen.id_kerani_kcs','left'),
            array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun','left'),
            array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling','left'),
        );
        $where = array('tbl_panen.id'=>$this->input->get('id'));
        $result =$this->M_datahasilpanen->get_where($select,$where,false,$join,false,false);
        self::json($result);
    }

    public function update_hasil_panen()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_pemanen','label'=>'Nama Pemanen','rules'=>'required'),
                array('field'=>'jmlh_panen','label'=>'Jumlah Panen','rules'=>'required'),
                array('field'=>'tph','label'=>'TPH','rules'=>'required'),
                array('field'=>'blok','label'=>'BLOK','rules'=>'required'),
                array('field'=>'id_alat','label'=>'Pengganti Alat Premi','rules'=>'required'),
                array('field'=>'jmlh_brondolan','label'=>'Brondolan','rules'=>'required'),
            ));

            if($data === true){
                $user=$this->session->userdata('user');
                extract($user);
                
                $field= array(
                    'id_pemanen'=>$this->input->post('id_pemanen'),
                    'jmlh_panen'=>$this->input->post('jmlh_panen'),
                    'tph'=>$this->input->post('tph'),
                    'blok'=>$this->input->post('blok'),
                    'id_alat'=>$this->input->post('id_alat'),
                    'jmlh_brondolan'=>$this->input->post('jmlh_brondolan'),
                );
                $primary_values=$this->input->post('id');
                $data=$this->M_datahasilpanen->update_by_id($field,$primary_values);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    public function Delete_hasil_panen()
    {
        $primary_values=$this->input->get('id');
        $result = $this->M_datahasilpanen->delete_id($primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }


    public function blok()
    {   
        $this->load->model('backend/M_libur','M_libur');
        $select_hari=array("tbl_libur.tanggal_libur");
        $where_hari=array('tbl_libur.tanggal_libur'=>date('Y-m-d'));
        $result_hari=$this->M_libur->get_where($select_hari,$where_hari,false,false,false);


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
        $user=$this->session->userdata('user');
        extract($user);
        $Select = array(
            "tbl_kerani_kcs.id_kebun",
            "tbl_kerani_kcs.id_afdeling",
        );
        $Where = array('tbl_kerani_kcs.token'=>$token);
        $result2 =$this->M_keranikcs->get_result2($Select,$Where,false,false,false);
        foreach ($result2 as $key);
        $id_kebun=$key->id_kebun;
        $id_afdeling=$key->id_afdeling;
        $select = array("tbl_blok.blok","tbl_blok.id");
        if($hari=='Minggu'){
            $where = array(
                'tbl_blok.id_kebun'=>$id_kebun,
                'tbl_blok.id_afdeling'=>$id_afdeling,
                'tbl_blok.keterangan'=>'Minggu',
            );
        }else{
            if($result_hari!==null){
                $where = array(
                    'tbl_blok.id_kebun'=>$id_kebun,
                    'tbl_blok.id_afdeling'=>$id_afdeling,
                    'tbl_blok.keterangan'=>'Tanggal_Merah',
                );
            }else{
                $where = array(
                    'tbl_blok.id_kebun'=>$id_kebun,
                    'tbl_blok.id_afdeling'=>$id_afdeling,
                    'tbl_blok.keterangan'=>'All',
                );
            }
        }
        
        $result =$this->M_blok->get_result($select,$where,false,false,false);
        self::json($result);
    }
    
    public function test()
    {
        $this->load->model('backend/M_libur','M_libur');
        $select_hari=array("tbl_libur.tanggal_libur");
        $where_hari=array('tbl_libur.tanggal_libur'=>date('Y-m-d'));
        $result_hari=$this->M_libur->get_where($select_hari,$where_hari,false,false,false);
        self::json($result_hari);
    }


    




























































}
