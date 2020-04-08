<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kebun extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->model('backend/M_kebun','M_kebun');
       $this->load->model('backend/M_distrik','M_distrik');
   	}

	public function index()
	{  
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman Kebun';
            $data['view_content']   = 'backend/kebun/kebun';
            $data['Distrik']          = $this->M_distrik->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }   
	}

    public function Data_Kebun()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(null,"tbl_kebun.nama_kebun");
            $search = array("tbl_kebun.nama_kebun","tbl_distrik.nama_lengkap");
            $select = array(
                "tbl_kebun.nama_kebun",
                "tbl_kebun.id",
                "tbl_distrik.nama_lengkap AS distrik"
            );
            $join = array(
                array('tbl_distrik','tbl_distrik.id=tbl_kebun.id_distrik')
            );
            $result = $this->M_kebun->findDataTable($orderBy,$search,$select,false,$join);
            foreach ($result as $item) {
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_kebun->findDataTableOutput($data,$search,$select,false,$join);
        }
    }


    public function Add_Kebun()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'nama_kebun','label'=>'Nama Kebun','rules'=>'required'),
                array('field'=>'id_distrik','label'=>'Distrik','rules'=>'required'),

                
            ));
            if($data === true){
                    $field= array(
                        'nama_kebun'=>$this->input->post('nama_kebun'),
                        'id_distrik'=>$this->input->post('id_distrik'),
                    );
                    $where=array(
                        'nama_kebun' => $this->input->post('nama_kebun'),
                        'id_distrik'=>$this->input->post('id_distrik'),
                    );
                    if($this->M_kebun->validasiData($where)){
                        $data=array('success'=>false,'error'=>'Data Sudah Ada');
                    }else{
                        $data=$this->M_kebun->insert(self::xss($field));
                        $data=array('success'=>true,'type'=>'Add');
                    }
            }  
        }
        self::json($data);
    }


    public function Update_Kebun()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'nama_kebun','label'=>'Nama Kebun','rules'=>'required'),
            ));
            if($data === true){
                $field= array(
                    'nama_kebun'=>$this->input->post('nama_kebun'),
                    'id_distrik'=>$this->input->post('id_distrik'),
                );
                $primary_values=$this->input->post('id');
                $data=$this->M_kebun->update_by_id($field,$primary_values);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    public function Edit_Kebun()
    {   
        $select = array(
            "tbl_kebun.id",
            "tbl_kebun.nama_kebun",
            "tbl_distrik.id as distrik"
        );
        $join = array(
            array('tbl_distrik','tbl_distrik.id=tbl_kebun.id_distrik')
        );
        $where = array('tbl_kebun.id'=>$this->input->get('id'));
        $result =$this->M_kebun->get_where($select,$where,false,$join,false,false);
        self::json($result);
    }


    public function Delete_Kebun()
    {
        $primary_values=$this->input->get('id');
        $result = $this->M_kebun->delete_id($primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }













}
