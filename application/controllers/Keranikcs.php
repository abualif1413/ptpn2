<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keranikcs extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->helper('string');
       $this->load->model('backend/M_keranikcs','M_keranikcs');
       $this->load->model('backend/M_kebun','M_kebun');
       $this->load->model('backend/M_afdeling','M_afdeling');
       $this->load->model('backend/M_admin','M_admin');
       $this->load->model('backend/M_keraniaskep','M_keraniaskep');
   	}

	public function index()
	{  
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman Kerani KCS';
            $data['view_content']   = 'backend/keranikcs/keranikcs';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $data['afdeling']       = $this->M_afdeling->getAll()->result();

            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }   
	}

    public function Data_Keranikcs()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(
                null,
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_kerani_kcs.email",
            );
            
            $search = array(
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_kerani_kcs.email",
                "tbl_kerani_kcs.no_sap",
                "tbl_kerani_askep.nama_lengkap",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
            );

            $select = array(
                "tbl_kerani_kcs.id",
                "tbl_kerani_kcs.email",
                "tbl_kerani_kcs.no_sap",
                "tbl_kerani_kcs.token",
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_jabatan.jabatan",
                "tbl_kerani_kcs.id_kebun",
                "tbl_kerani_kcs.id_afdeling",
                "tbl_kerani_kcs.photo",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_kerani_askep.nama_lengkap as keraniaskep",
            );
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_kerani_kcs.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_kerani_kcs.id_afdeling'),
                array('tbl_jabatan','tbl_jabatan.id=tbl_kerani_kcs.id_jabatan'),
                array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_kerani_kcs.id_kerani_askep'),
            );

         
            $result = $this->M_keranikcs->findDataTable($orderBy,$search,$select,false,$join);
            foreach ($result as $item) {
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'" data-token="'.$item->token.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'" data-token="'.$item->token.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $item->image='<img src="assets/backend/img/photo/'.$item->photo.'" width="100px" height="100px;">';
                $data[] = $item;
            }
            return $this->M_keranikcs->findDataTableOutput($data,$search,$select,false,$join);
        }
    }


    public function add_keranikcs()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'email','label'=>'Email','rules'=>'required|valid_email'),
                array('field'=>'nama_lengkap','label'=>'Nama Lengkap','rules'=>'required'),
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'required'),
                array('field'=>'id_afdeling','label'=>'Afdeling','rules'=>'required'),
                array('field'=>'id_kerani_askep','label'=>'Kerani Askep','rules'=>'required'),
                
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
                        'no_sap'=>$this->input->post('no_sap'),
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'id_kerani_askep'=>$this->input->post('id_kerani_askep'),
                        'id_kebun'=>$this->input->post('id_kebun'),
                        'id_afdeling'=>$this->input->post('id_afdeling'),
                        'id_jabatan'=>'5',
                        'photo'=>$datafoto['file_name'],
                        'token'=>$token,
                    );

                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'email'=>$this->input->post('email'),
                        'password'=>password_hash($this->input->post('email'),PASSWORD_BCRYPT),
                        'role'=>'kerani_kcs',
                        'status'=>'Y',
                        'photo'=>$datafoto['file_name'],
                        'token'=>$token,
                    );
                    
                    $where=array(
						'email'=>$this->input->post('email')
					);
                    if($this->M_keranikcs->validasiData($where)){
                        $data=array('success'=>false,'error'=>'Data Sudah Ada');
                    }else{
                        $data=$this->M_keranikcs->insert(self::xss($field));
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


    public function update_keranikcs()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'email','label'=>'Email','rules'=>'required|valid_email'),
                array('field'=>'nama_lengkap','label'=>'Nama Lengkap','rules'=>'required'),
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'required'),
                array('field'=>'id_afdeling','label'=>'Afdeling','rules'=>'required'),
                array('field'=>'id_kerani_askep','label'=>'Kerani Askep','rules'=>'required'),
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
                        'no_sap'=>$this->input->post('no_sap'),
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'id_kerani_askep'=>$this->input->post('id_kerani_askep'),
                        'id_kebun'=>$this->input->post('id_kebun'),
                        'id_afdeling'=>$this->input->post('id_afdeling'),
                        'photo'=>$datafoto['file_name'],
                    );

                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'email'=>$this->input->post('email'),
                        'photo'=>$datafoto['file_name'],
                    );

                }else{
                    $field= array(
                        'email'=>$this->input->post('email'),
                        'no_sap'=>$this->input->post('no_sap'),
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'id_kerani_askep'=>$this->input->post('id_kerani_askep'),
                        'id_kebun'=>$this->input->post('id_kebun'),
                        'id_afdeling'=>$this->input->post('id_afdeling'),
                    );

                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'email'=>$this->input->post('email'),
                    );
                }
                
                $primary_values=$this->input->post('token');
                $primary_key='token';
                $data=$this->M_keranikcs->update_by_id2($field,$primary_values,$primary_key);
                $data=$this->M_admin->update_by_id2($field2,$primary_values,$primary_key);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    public function edit_keranikcs()
    {   
        $select = array(
            "tbl_kerani_kcs.id",
            "tbl_kerani_kcs.token",
            "tbl_kerani_kcs.email",
            "tbl_kerani_kcs.no_sap",
            "tbl_kerani_kcs.id_kerani_askep",
            "tbl_kerani_kcs.nama_lengkap",
            "tbl_jabatan.jabatan",
            "tbl_kerani_kcs.id_kebun",
            "tbl_kerani_kcs.id_afdeling",
            "tbl_kerani_kcs.photo",
            "tbl_kebun.nama_kebun",
            "tbl_afdeling.nama_afdeling",
            "tbl_kerani_askep.nama_lengkap as keraniaskep",
        );
        $join = array(
            array('tbl_kebun','tbl_kebun.id=tbl_kerani_kcs.id_kebun'),
            array('tbl_afdeling','tbl_afdeling.id=tbl_kerani_kcs.id_afdeling'),
            array('tbl_jabatan','tbl_jabatan.id=tbl_kerani_kcs.id_jabatan'),
            array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_kerani_kcs.id_kerani_askep'),
        );
        $where = array('tbl_kerani_kcs.token'=>$this->input->get('token'));
        $result =$this->M_keranikcs->get_where($select,$where,false,$join,false,false);
        self::json($result);
    }


    public function delete_keranikcs()
    {
        $primary_values=$this->input->get('token');
        $primary_key='token';
        $result = $this->M_keranikcs->delete_id2($primary_key,$primary_values);
        $result = $this->M_admin->delete_id2($primary_key,$primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }














}
