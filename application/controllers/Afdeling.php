<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Afdeling extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->model('backend/M_afdeling','M_afdeling');
       $this->load->model('backend/M_kebun','M_kebun');
   	}

	public function index()
	{  
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman Afdeling';
            $data['view_content']   = 'backend/afdeling/afdeling';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }   
	}

    public function Data_Afdeling()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(null,"tbl_afdeling.nama_afdeling");
            $search = array("tbl_afdeling.nama_afdeling","tbl_kebun.nama_kebun");
            $select = array(
                "tbl_afdeling.nama_afdeling",
                "tbl_afdeling.id",
                "tbl_kebun.nama_kebun",
            );

            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_afdeling.id_kebun'),
            );
            $result = $this->M_afdeling->findDataTable($orderBy,$search,$select,false,$join);
            foreach ($result as $item) {
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_afdeling->findDataTableOutput($data,$search,$select,false,$join );
        }
    }


    public function Add_Afdeling()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'required'),
                array('field'=>'nama_afdeling','label'=>'Nama Afdeling','rules'=>'required'),
            ));
            if($data === true){
                    $field= array(
                        'id_kebun'=>$this->input->post('id_kebun'),
                        'nama_afdeling'=>$this->input->post('nama_afdeling'),
                    );
                    $where=array(
                        'nama_afdeling' => $this->input->post('nama_afdeling'),
                        'id_kebun' => $this->input->post('id_kebun'),
                    );
                    if($this->M_afdeling->validasiData($where)){
                        $data=array('success'=>false,'error'=>'Data Sudah Ada');
                    }else{
                        $data=$this->M_afdeling->insert(self::xss($field));
                        $data=array('success'=>true,'type'=>'Add');
                    }
            }  
        }
        self::json($data);
    }


    public function Update_Afdeling()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'required'),
                array('field'=>'nama_afdeling','label'=>'Nama Afdeling','rules'=>'required'),
            ));
            if($data === true){
                $field= array(
                    'id_kebun'=>$this->input->post('id_kebun'),
                    'nama_afdeling'=>$this->input->post('nama_afdeling'),
                );
                $primary_values=$this->input->post('id');
                $data=$this->M_afdeling->update_by_id($field,$primary_values);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    public function Edit_Afdeling()
    {   
        $select = array(
            "tbl_afdeling.nama_afdeling",
            "tbl_afdeling.id",
            "tbl_afdeling.id_kebun",
            "tbl_kebun.nama_kebun",
        );

        $join = array(
            array('tbl_kebun','tbl_kebun.id=tbl_afdeling.id_kebun'),
        );

        $where = array('tbl_afdeling.id'=>$this->input->get('id'));
        $result =$this->M_afdeling->get_where($select,$where,false,$join,false,false);
        self::json($result);
    }


    public function Delete_Afdeling()
    {
        $primary_values=$this->input->get('id');
        $result = $this->M_afdeling->delete_id($primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }













}
