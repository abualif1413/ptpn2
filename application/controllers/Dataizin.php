<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataizin extends MY_Controller {

	public function __construct(){
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('backend/M_izin','M_izin');
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Izin';
			$data['view_content'] 	= 'backend/dataizin/dataizin';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
	}

    public function Data_Izin()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(
                null,
                "tbl_izin.id_absen",
                "tbl_izin.tanggal",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.keterangan",
            );
            $search = array(
                "tbl_izin.id_absen",
                "tbl_izin.tanggal",
                "tbl_pemanen.nama_pemanen",
            );
            $select = array(
                "tbl_izin.*",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.id_mandor",
            );
            $join=array(
                array('tbl_pemanen','tbl_pemanen.barcode=tbl_izin.id_absen')
            );

            $where= array('tbl_izin.id_mandor'=> $this->session->token );

            $result = $this->M_izin->findDataTable($orderBy,$search,$select,$where,$join);
            foreach ($result as $item) {
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_izin->findDataTableOutput($data,$search,$select,$where,$join);
        }
    }

    
    public function Add_Izin()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_absen','label'=>'Nama Pemanen','rules'=>'required'),
                array('field'=>'tanggal','label'=>'Tanggal','rules'=>'required'),
                array('field'=>'jenis','label'=>'Jenis','rules'=>'required'),
                array('field'=>'keterangan','label'=>'Keterangan','rules'=>'required'),
            ));
            if($data === true){

                $select_pemanen = array("tbl_pemanen.nama_pemanen","tbl_pemanen.id_mandor");
                $where_pemanen = array('tbl_pemanen.barcode'=>$this->input->post('id_absen'));
                $result_pemanen=$this->M_DataPemanen->get_result($select_pemanen,$where_pemanen,false,false,false);
                foreach ($result_pemanen as $key_pemanen);
                $nama_pemanen=$key_pemanen->nama_pemanen;
                $id_mandor=$key_pemanen->id_mandor;

                $field= array(
                    'id_mandor'=>$id_mandor,
                    'id_absen'=>$this->input->post('id_absen'),
                    'tanggal'=>$this->input->post('tanggal'),
                    'jenis'=>$this->input->post('jenis'),
                    'keterangan'=>$this->input->post('keterangan'),
                );
                $where=array(
                    'id_absen' => $this->input->post('id_absen'),
                    'tanggal' => $this->input->post('tanggal'),
                );
                if($this->M_izin->validasiData($where)){
                    $data=array('success'=>false,'error'=>'Data Sudah Ada');
                }else{
                    $data=$this->M_izin->insert(self::xss($field));
                    $data=array('success'=>true,'type'=>'Add');
                }
            }  
        }
        self::json($data);
    }


    public function Update_Izin()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_absen','label'=>'Nama Pemanen','rules'=>'required'),
                array('field'=>'tanggal','label'=>'Tanggal','rules'=>'required'),
                array('field'=>'jenis','label'=>'Jenis','rules'=>'required'),
                array('field'=>'keterangan','label'=>'Keterangan','rules'=>'required'),
            ));
            if($data === true){
                $field= array(
                    'id_absen'=>$this->input->post('id_absen'),
                    'tanggal'=>$this->input->post('tanggal'),
                    'jenis'=>$this->input->post('jenis'),
                    'keterangan'=>$this->input->post('keterangan'),
                );
                $primary_values=$this->input->post('id');
                $data=$this->M_izin->update_by_id($field,$primary_values);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    public function Edit_Izin()
    {   
        $select = array(
            "tbl_izin.*",
        );
        $where = array('tbl_izin.id'=>$this->input->get('id'));
        $result =$this->M_izin->get_where($select,$where,false,false,false,false);
        self::json($result);
    }


    public function Delete_Izin()
    {
        $primary_values=$this->input->get('id');
        $result = $this->M_izin->delete_id($primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }

    public function datapemanen()
    {   
        $select = array("tbl_pemanen.barcode","tbl_pemanen.nama_pemanen");
        $where = array('tbl_pemanen.id_mandor'=>$this->session->token);
        $result =$this->M_DataPemanen->get_result($select,$where,false,false,false,false);
        self::json($result);
    }






























































}
