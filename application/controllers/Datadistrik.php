<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datadistrik extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->helper('string');
       $this->load->model('backend/M_distrik','M_distrik');
       $this->load->model('backend/M_admin','M_admin');
   	}

	public function index()
	{  
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman Distrik';
            $data['view_content']   = 'backend/distrik/distrik';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }   
	}

    public function Data_Distrik()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(
                null,
                "tbl_distrik.nama_lengkap",
                "tbl_distrik.email",
            );
            
            $search = array(
                "tbl_distrik.nama_lengkap",
                "tbl_distrik.email",
            );

            $select = array(
                "tbl_distrik.*",
            );
       
            $result = $this->M_distrik->findDataTable($orderBy,$search,$select,false,false);
            foreach ($result as $item) {
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'" data-token="'.$item->token.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'" data-token="'.$item->token.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $item->image='<img src="assets/backend/img/photo/'.$item->photo.'" width="100px" height="100px;">';
                $data[] = $item;
            }
            return $this->M_distrik->findDataTableOutput($data,$search,$select,false,false);
        }
    }


    public function add_distrik()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'email','label'=>'Email','rules'=>'required'),
                array('field'=>'nama_distrik','label'=>'Nama Distrik','rules'=>'required'),
            ));

            $config['upload_path']          = 'assets/backend/img/photo';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['max_size']             = 32000;
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('photo'))
            {
                $errorfoto =$this->upload->display_errors();
            }
            else
            {
                $datafoto=$this->upload->data();  
            }
            if($data === true){
                if (!isset($errorfoto)) {

                    $token=random_string('numeric',5);
                    $field= array(
                        'email'=>$this->input->post('email'),
                        'nama_lengkap'=>$this->input->post('nama_distrik'),
                        'photo'=>$datafoto['file_name'],
                        'token'=>$token,
                    );

                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_distrik'),
                        'email'=>$this->input->post('email'),
                        'password'=>password_hash($this->input->post('email'),PASSWORD_BCRYPT),
                        'role'=>'distrik',
                        'status'=>'Y',
                        'photo'=>$datafoto['file_name'],
                        'token'=>$token,
                    );
                    
                    $where=array(
                        'nama_lengkap' => $this->input->post('nama_distrik')
                        );
                    if($this->M_admin->validasiData($where)){
                        $data=array('success'=>false,'error'=>'Data Distrik Sudah Ada');
                    }else{
                        $data=$this->M_distrik->insert(self::xss($field));
                        $data=$this->M_admin->insert(self::xss($field2));
                        $data=array('success'=>true,'type'=>'Add');
                    }
                }else{
                    $data=array('error'=>$errorfoto);
                }
            }  
        }
        self::json($data);
    }


    public function Update_Distrik()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'email','label'=>'Email','rules'=>'required'),
                array('field'=>'nama_distrik','label'=>'Nama Distrik','rules'=>'required'),
            ));

            $config['upload_path']          = 'assets/backend/img/photo';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['max_size']             = 32000;
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('photo'))
            {
                $errorfoto =$this->upload->display_errors();
            }
            else
            {
                $datafoto=$this->upload->data();  
            }
            if($data === true){
                if (!isset($errorfoto)) {
                    
                    $field= array(
                        'email'=>$this->input->post('email'),
                        'nama_lengkap'=>$this->input->post('nama_distrik'),
                        'photo'=>$datafoto['file_name'],
                    );

                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_distrik'),
                        'email'=>$this->input->post('email'),
                        'photo'=>$datafoto['file_name'],
                    );

                }else{
                    
                    $field= array(
                        'email'=>$this->input->post('email'),
                        'nama_lengkap'=>$this->input->post('nama_distrik'),
                    );

                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_distrik'),
                        'email'=>$this->input->post('email'),
                    );
                }
                
                $primary_values=$this->input->post('token');
                $primary_key='token';
                $data=$this->M_distrik->update_by_id2($field,$primary_values,$primary_key);
                $data=$this->M_admin->update_by_id2($field2,$primary_values,$primary_key);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    public function Edit_Distrik()
    {   
        $select = array(
            "tbl_distrik.*"
        );
        $where = array('tbl_distrik.token'=> $this->input->get('token'));
        $result =$this->M_distrik->get_where($select,$where,false,false,false,false);
        self::json($result);
    }


    public function Delete_Distrik()
    {
        $primary_values=$this->input->get('token');
        $primary_key='token';
        $result = $this->M_distrik->delete_id2($primary_key,$primary_values);
        $result = $this->M_admin->delete_id2($primary_key,$primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }














}
