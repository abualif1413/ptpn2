<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataHariLibur extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_chart','M_chart');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('backend/M_libur','M_libur');
	}
	
	public function index()
	{
        if ($this->session->userdata('email')){
			$data['title']  		= 'Halaman Data Libur';
			$data['view_content'] 	= 'backend/dataharilibur/dataharilibur';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
    }
    
    public function Data_daftar_libur()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(null,"tbl_libur.tanggal_libur","tbl_libur.keterangan");
            $search = array("tbl_libur.tanggal_libur","tbl_libur.keterangan");
            $select = array("tbl_libur.*");
            $result = $this->M_libur->findDataTable($orderBy,$search,$select,false,false);
            foreach ($result as $item) {
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_libur->findDataTableOutput($data,$search,$select,false,false );
        }
    }


    public function Add_Hari_Libur()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'tanggal_libur','label'=>'Tanggal','rules'=>'required'),
                array('field'=>'keterangan','label'=>'Keterangan','rules'=>'required'),
            ));

            if($data === true){
                $field= array(
                    'tanggal_libur'=>$this->input->post('tanggal_libur'),
                    'keterangan'=>$this->input->post('keterangan'),
                );
                $where=array(
                    'tanggal_libur' => $this->input->post('tanggal_libur'),
                );
                if($this->M_libur->validasiData($where)){
                    $data=array('success'=>false,'error'=>'Data Sudah Ada');
                }else{
                    $data=$this->M_libur->insert(self::xss($field));
                    $data=array('success'=>true,'type'=>'Add');
                }
            }  
        }
        self::json($data);
    }


    public function Edit_Hari_Libur()
    {   
        $select = array("tbl_libur.*");
        $where = array('tbl_libur.id'=>$this->input->get('id'));
        $result =$this->M_libur->get_where($select,$where,false,false,false,false);
        self::json($result);
    }


    public function Update_Hari_Libur()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'tanggal_libur','label'=>'Tanggal','rules'=>'required'),
                array('field'=>'keterangan','label'=>'Keterangan','rules'=>'required'),
            ));

            if($data === true){
                $field= array(
                    'tanggal_libur'=>$this->input->post('tanggal_libur'),
                    'keterangan'=>$this->input->post('keterangan'),
                );
                $primary_values=$this->input->post('id');
                $data=$this->M_libur->update_by_id(self::xss($field),$primary_values);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }


    public function Delete_Hari_Libur()
    {
        $primary_values=$this->input->get('id');
        $result = $this->M_libur->delete_id($primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }






























































}
