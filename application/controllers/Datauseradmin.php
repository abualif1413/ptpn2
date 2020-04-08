<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datauseradmin extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->helper('string');
       $this->load->model('backend/M_kebun','M_kebun');
       $this->load->model('backend/M_afdeling','M_afdeling');
       $this->load->model('backend/M_admin','M_admin');
       $this->load->model('backend/M_mandor','M_mandor');
       $this->load->model('backend/M_keraniaskep','M_keraniaskep');
       $this->load->model('backend/M_distrik','M_distrik');
       
   	}

	public function index()
	{  
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman User';
            $data['view_content']   = 'backend/datauseradmin/datauseradmin';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }   
	}

    public function Data_user_admin()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(
                null,
                "tbl_admin.nama_lengkap",
                "tbl_admin.email",
                "tbl_admin.role",
            );
            $search = array(
                "tbl_admin.nama_lengkap",
                "tbl_admin.email",
                "tbl_admin.status",
                "tbl_admin.role",
            );
            $select = array("tbl_admin.*");
            $result = $this->M_admin->findDataTable($orderBy,$search,$select,false,false);
            foreach ($result as $item) {
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'" ><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-token="'.$item->token.'" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-outline-success btn-mini" onClick="Resetpassword(this)" data-email="'.$item->email.'" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Reset Password</button>';
                $item->button_action = $btnAction;
                $item->image='<img src="assets/backend/img/photo/'.$item->photo.'" width="100px" height="100px;">';
                $data[] = $item;
            }
            return $this->M_admin->findDataTableOutput($data,$search,$select,false,false);
        }
    }


    public function add_datauseradmin()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'email','label'=>'Email','rules'=>'required|valid_email'),
                array('field'=>'password','label'=>'Password','rules'=>'required'),
                array('field'=>'nama_lengkap','label'=>'Nama Lengkap','rules'=>'required'),
                array('field'=>'status','label'=>'Status','rules'=>'required'),
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
                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'email'=>$this->input->post('email'),
                        'password'=>password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                        'role'=>'s_admin',
                        'status'=>$this->input->post('status'),
                        'photo'=>$datafoto['file_name'],
                        'token'=>$token,
                    );
                    $where=array('email' => $this->input->post('email'));
                    if($this->M_admin->validasiData($where)){
                        $data=array('success'=>false,'error'=>'Data Sudah Ada');
                    }else{
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


    public function update_datauseradmin()
    {   
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'email','label'=>'Email','rules'=>'required|valid_email'),
                array('field'=>'nama_lengkap','label'=>'Nama Lengkap','rules'=>'required'),
                array('field'=>'status','label'=>'Status','rules'=>'required'),
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
                        'status'=>$this->input->post('status'),
                        'photo'=>$datafoto['file_name'],
                        'password'=>password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                    );

                    $field2= array(
                        'email'=>$this->input->post('email'),
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'photo'=>$datafoto['file_name'],
                    );

                }else{
                    $field= array(
                        'email'=>$this->input->post('email'),
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'status'=>$this->input->post('status'),
                        'password'=>password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                    );

                    $field2= array(
                        'email'=>$this->input->post('email'),
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                    );
                }
                $token=$this->input->post('token');
                $where=array('token' => $token);
                if($this->M_mandor->validasiData($where)){
                        $primary_key='token';
                        $primary_values=$this->input->post('token');
                        $data=$this->M_mandor->update_by_id2($field2,$primary_values,$primary_key);
                        $primary_values=$this->input->post('token');
                        $data=$this->M_admin->update_by_id2($field,$primary_values,$primary_key);
                        $data=array('success'=>true,'type'=>'Update');
                }else{
                    $where=array('token' => $token);
                    if($this->M_keraniaskep->validasiData($where)){
                        $primary_key='token';
                        $primary_values=$this->input->post('token');
                        $data=$this->M_keraniaskep->update_by_id2($field2,$primary_values,$primary_key);
                        $primary_values=$this->input->post('token');
                        $data=$this->M_admin->update_by_id2($field,$primary_values,$primary_key);
                        $data=array('success'=>true,'type'=>'Update');
                    }else{

                        if($this->M_distrik->validasiData($where)){
                            $primary_key='token';
                            $primary_values=$this->input->post('token');
                            $data=$this->M_distrik->update_by_id2($field2,$primary_values,$primary_key);
                            $primary_values=$this->input->post('token');
                            $data=$this->M_admin->update_by_id2($field,$primary_values,$primary_key);
                            $data=array('success'=>true,'type'=>'Update');
                        }else{
                            $primary_key='token';
                            $primary_values=$this->input->post('token');
                            $data=$this->M_admin->update_by_id2($field,$primary_values,$primary_key);
                            $data=array('success'=>true,'type'=>'Update');
                        }
                        
                    }
                }
            }  
        }
        self::json($data);
    }

    public function Edit_datauseradmin()
    {   
        $select = array("tbl_admin.*");
        $where = array('tbl_admin.id'=>$this->input->get('id'));
        $result =$this->M_admin->get_where($select,$where,false,false,false,false);
        self::json($result);
    }


    public function Delete_datauseradmin()
    {
        $primary_values=$this->input->post('token');
        $where=array('token' => $primary_values);
        if($this->M_mandor->validasiData($where)){
                $primary_key='token';
                $primary_values=$this->input->post('token');
                $data=$this->M_mandor->delete_id2($primary_key,$primary_values);
                $primary_values=$this->input->post('token');
                $data=$this->M_admin->delete_id2($primary_key,$primary_values);
                $data=array('success'=>true,'type'=>'Delete');
        }else{
            $where=array('token' => $primary_values);
            if($this->M_keraniaskep->validasiData($where)){
                $primary_key='token';
                $primary_values=$this->input->post('token');
                $data=$this->M_keraniaskep->delete_id2($primary_key,$primary_values);
                $primary_values=$this->input->post('token');
                $data=$this->M_admin->delete_id2($primary_key,$primary_values);
                $data=array('success'=>true,'type'=>'Delete');
            }else{
                $primary_key='token';
                $primary_values=$this->input->post('token');
                $data=$this->M_admin->delete_id2($primary_key,$primary_values);
                $data=array('success'=>true,'type'=>'Delete');
            }
        }
        self::json($data);
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
	
	
	
	public function Resetpassword()
	{
        if(self::isPost()){
            $field= array('password'=>password_hash($this->input->post('email'),PASSWORD_BCRYPT));
            $primary_values=$this->input->post('id');
            $data=$this->M_admin->update_by_id($field,$primary_values);
            $data=array('success'=>true,'type'=>'Update'); 
        }
        self::json($data);
	}













}
