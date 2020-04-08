<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keraniaskep extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->helper('string');
       $this->load->model('backend/M_keraniaskep','M_keraniaskep');
       $this->load->model('backend/M_kebun','M_kebun');
       $this->load->model('backend/M_afdeling','M_afdeling');
       $this->load->model('backend/M_admin','M_admin');
   	}

	public function index()
	{  
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman Kerani Askep';
            $data['view_content']   = 'backend/keraniaskep/keraniaskep';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }   
	}

    public function Data_Keraniaskep()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(
                null,
                "tbl_kerani_askep.nama_lengkap",
                "tbl_kerani_askep.email",
                "tbl_kebun.nama_kebun",
                "tbl_jabatan.jabatan",
            );
            
            $search = array(
                "tbl_kerani_askep.nama_lengkap",
                "tbl_kerani_askep.email",
                "tbl_kebun.nama_kebun",
                "tbl_jabatan.jabatan",
            );

            $select = array(
                "tbl_kerani_askep.*",
                "tbl_kebun.nama_kebun",
                "tbl_jabatan.jabatan",
            );
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_kerani_askep.id_kebun'),
                array('tbl_jabatan','tbl_jabatan.id=tbl_kerani_askep.id_jabatan'),
            );
            $result = $this->M_keraniaskep->findDataTable($orderBy,$search,$select,false,$join);
            foreach ($result as $item) {
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'" data-token="'.$item->token.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'" data-token="'.$item->token.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $item->image='<img src="assets/backend/img/photo/'.$item->photo.'" width="100px" height="100px;">';
                $data[] = $item;
            }
            return $this->M_keraniaskep->findDataTableOutput($data,$search,$select,false,$join);
        }
    }


    public function add_keraniaskep()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'email','label'=>'Email','rules'=>'required|valid_email'),
                array('field'=>'nama_lengkap','label'=>'Nama Lengkap','rules'=>'required'),
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'required'),
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
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'id_kebun'=>$this->input->post('id_kebun'),
                        'id_jabatan'=>'1',
                        'photo'=>$datafoto['file_name'],
                        'token'=>$token,
                    );

                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'email'=>$this->input->post('email'),
                        'password'=>password_hash($this->input->post('email'),PASSWORD_BCRYPT),
                        'role'=>'k_askep',
                        'status'=>'Y',
                        'photo'=>$datafoto['file_name'],
                        'token'=>$token,
                    );
                    
                    $where=array(
                        'id_kebun'=>$this->input->post('id_kebun')
                        );
                    if($this->M_keraniaskep->validasiData($where)){
                        $data=array('success'=>false,'error'=>'Kebun Sudah Ada Kerani Askep');
                    }else{
                        $data=$this->M_keraniaskep->insert(self::xss($field));
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


    public function update_keraniaskep()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'email','label'=>'Email','rules'=>'required|valid_email'),
                array('field'=>'nama_lengkap','label'=>'Nama Lengkap','rules'=>'required'),
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'required'),
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
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'id_kebun'=>$this->input->post('id_kebun'),
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
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'id_kebun'=>$this->input->post('id_kebun'),
                    );

                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'email'=>$this->input->post('email'),
                    );
                }
                
                $primary_values=$this->input->post('token');
                $primary_key='token';
                $data=$this->M_keraniaskep->update_by_id2($field,$primary_values,$primary_key);
                $data=$this->M_admin->update_by_id2($field2,$primary_values,$primary_key);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    public function Edit_Keraniaskep()
    {   
        $select = array(
            "tbl_kerani_askep.*",
            "tbl_kebun.nama_kebun",
        );
        $join = array(
            array('tbl_kebun','tbl_kebun.id=tbl_kerani_askep.id_kebun'),
        );
        $where = array('token'=> $this->input->get('token'));
        $result =$this->M_keraniaskep->get_where($select,$where,false,$join,false,false);
        self::json($result);
    }


    public function Delete_Keraniaskep()
    {
        $primary_values=$this->input->get('token');
        $primary_key='token';
        $result = $this->M_keraniaskep->delete_id2($primary_key,$primary_values);
        $result = $this->M_admin->delete_id2($primary_key,$primary_values);
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
