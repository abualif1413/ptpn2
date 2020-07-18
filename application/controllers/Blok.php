<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(0);

class Blok extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->model('backend/M_blok','M_blok');
       $this->load->model('backend/M_kebun','M_kebun');
       $this->load->model('backend/M_afdeling','M_afdeling');
       
       
   	}
    
    public function index()
        { if ($this->session->userdata('email')) {
            $data['title']  		= 'Halaman Blok';
            $data['view_content'] 	= 'backend/blok/blok';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $data['afdeling']       = $this->M_afdeling->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }
    
	public function add_data_blok()
	{  
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'trim|required|numeric'),
            	array('field'=>'id_afdeling','label'=>'Afdeling','rules'=>'trim|required|numeric'),
                array('field'=>'blok','label'=>'Blok','rules'=>'trim|required'),
                array('field'=>'bt','label'=>'Batas Tugas','rules'=>'trim|required|numeric'),
                array('field'=>'p0','label'=>'P0','rules'=>'trim|required|numeric'),
                array('field'=>'p1','label'=>'P1','rules'=>'trim|required|numeric'),
                array('field'=>'p2','label'=>'P2','rules'=>'trim|required|numeric'),
                array('field'=>'p3','label'=>'P3','rules'=>'trim|required|numeric'),
                array('field'=>'rp_p0','label'=>'Rp P0','rules'=>'trim|required|numeric'),
                array('field'=>'rp_p1','label'=>'Rp P1','rules'=>'trim|required|numeric'),
                array('field'=>'rp_p2','label'=>'Rp P2','rules'=>'trim|required|numeric'),
                array('field'=>'rp_p3','label'=>'Rp P3','rules'=>'trim|required|numeric'),
                array('field'=>'status','label'=>'Status','rules'=>'trim|required'),
                array('field'=>'tahun_tanam','label'=>'Tahun Tanam','rules'=>'trim|required|numeric'),
                array('field'=>'prediksi_komidel','label'=>'Prediksi Komidel','rules'=>'trim|required|numeric'),
            ));
            if($data === true){
                $field= array(
                    'id_kebun'=>$this->input->post('id_kebun'),
                    'id_afdeling'=>$this->input->post('id_afdeling'),
                    'blok'=>$this->input->post('blok'),
                    'bt'=>$this->input->post('bt'),
                    'p0'=>$this->input->post('p0'),
                    'p1'=>$this->input->post('p1'),
                    'p2'=>$this->input->post('p2'),
                    'p3'=>$this->input->post('p3'),
                    'rp_p0'=>$this->input->post('rp_p0'),
                    'rp_p1'=>$this->input->post('rp_p1'),
                    'rp_p2'=>$this->input->post('rp_p2'),
                    'rp_p3'=>$this->input->post('rp_p3'),
                    'status'=>$this->input->post('status'),
                    'keterangan'=>$this->input->post('keterangan'),
                    'tahun_tanam'=>$this->input->post('tahun_tanam'),
                    'prediksi_komidel'=>$this->input->post('prediksi_komidel'),
                );
                $where=array(
                    'id_kebun' => $this->input->post('id_kebun'),
                    'id_afdeling'=>$this->input->post('id_afdeling'),
                    'blok'=>$this->input->post('blok'),
                    'status'=>$this->input->post('status'),
                    'keterangan'=>$this->input->post('keterangan'),
                );
                if($this->M_blok->validasiData($where)){
                    $data=array('success'=>false,'error'=>'Data Sudah Ada');
                }else{
                    $data=$this->M_blok->insert(self::xss($field));
                    $data=array('success'=>true,'type'=>'Add');
                }
            }  
        }
        self::json($data);
    }
    
    public function Data_Blok()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(null,"tbl_kebun.nama_kebun", "tbl_afdeling.nama_afdeling", "tbl_blok.blok");
            $search = array("tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling");
            $select = array(
                "tbl_blok.*",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
            );
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_blok.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_blok.id_afdeling'),
            );
            $result = $this->M_blok->findDataTable($orderBy,$search,$select,false,$join);
            foreach ($result as $item) {
                if( $item->keterangan==='All'){
                    $item->keterangan = '<button class="btn btn-success btn-outline-success btn-mini" ><i class="fa fa-calender"></i>'.$item->keterangan.' (Senin-Sabtu)</button>';
                }else if($item->keterangan==='Minggu'){
                    $item->keterangan = '<button class="btn btn-danger btn-outline-danger btn-mini" ><i class="fa fa-calender"></i>'.$item->keterangan.'</button>';
                }else if($item->keterangan==='Tanggal_Merah'){
                    $item->keterangan = '<button class="btn btn-warning btn-outline-warning btn-mini" ><i class="fa fa-calender"></i>'.$item->keterangan.'</button>';
                }
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_blok->findDataTableOutput($data,$search,$select,false,$join);
        }
    }

    public function Edit_Blok()
    {   
        $select = array(
            "tbl_blok.*",
            "tbl_kebun.nama_kebun",
            "tbl_afdeling.nama_afdeling",
        );
        $join = array(
            array('tbl_kebun','tbl_kebun.id=tbl_blok.id_kebun'),
            array('tbl_afdeling','tbl_afdeling.id=tbl_blok.id_afdeling'),
        );
        $where = array('tbl_blok.id'=>$this->input->get('id'));
        $result =$this->M_blok->get_where($select,$where,false,$join,false,false);
        self::json($result);
    }


    public function Update_Blok()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'trim|required|numeric'),
            	array('field'=>'id_afdeling','label'=>'Afdeling','rules'=>'trim|required|numeric'),
                array('field'=>'blok','label'=>'Blok','rules'=>'trim|required'),
                array('field'=>'bt','label'=>'Batas Tugas','rules'=>'trim|required|numeric'),
                array('field'=>'p0','label'=>'P0','rules'=>'trim|required|numeric'),
                array('field'=>'p1','label'=>'P1','rules'=>'trim|required|numeric'),
                array('field'=>'p2','label'=>'P2','rules'=>'trim|required|numeric'),
                array('field'=>'p3','label'=>'P3','rules'=>'trim|required|numeric'),
                array('field'=>'rp_p0','label'=>'Rp P0','rules'=>'trim|required|numeric'),
                array('field'=>'rp_p1','label'=>'Rp P1','rules'=>'trim|required|numeric'),
                array('field'=>'rp_p2','label'=>'Rp P2','rules'=>'trim|required|numeric'),
                array('field'=>'rp_p3','label'=>'Rp P3','rules'=>'trim|required|numeric'),
                array('field'=>'status','label'=>'Status','rules'=>'trim|required'),
                array('field'=>'keterangan','label'=>'keterangan','rules'=>'trim|required'),
                array('field'=>'tahun_tanam','label'=>'Tahun Tanam','rules'=>'trim|required|numeric'),
                array('field'=>'prediksi_komidel','label'=>'Prediksi Komidel','rules'=>'trim|required|numeric'),
            ));
            if($data === true){
                $field= array(
                    'id_kebun'=>$this->input->post('id_kebun'),
                    'id_afdeling'=>$this->input->post('id_afdeling'),
                    'blok'=>$this->input->post('blok'),
                    'bt'=>$this->input->post('bt'),
                    'p0'=>$this->input->post('p0'),
                    'p1'=>$this->input->post('p1'),
                    'p2'=>$this->input->post('p2'),
                    'p3'=>$this->input->post('p3'),
                    'rp_p0'=>$this->input->post('rp_p0'),
                    'rp_p1'=>$this->input->post('rp_p1'),
                    'rp_p2'=>$this->input->post('rp_p2'),
                    'rp_p3'=>$this->input->post('rp_p3'),
                    'status'=>$this->input->post('status'),
                    'keterangan'=>$this->input->post('keterangan'),
                    'tahun_tanam'=>$this->input->post('tahun_tanam'),
                    'prediksi_komidel'=>$this->input->post('prediksi_komidel'),
                );
                $primary_values=$this->input->post('id');
                $data=$this->M_blok->update_by_id(self::xss($field),$primary_values);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    


    public function Delete_Blok()
    {
        $primary_values=$this->input->get('id');
        $result = $this->M_blok->delete_id($primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }


    public function afdeling()
	{
        $select = array(
            "tbl_afdeling.id",
            "tbl_afdeling.nama_afdeling",
        );
        $where = array('tbl_afdeling.id_kebun'=>$this->input->post('id'));
        $result =$this->M_afdeling->get_result($select,$where,false,false,false);
		print"<option value=''>Pilih</option>";
		foreach ($result as $key) {
			print"<option value='".$key->id."'>".$key->nama_afdeling."</option>";
		}
	}













}
