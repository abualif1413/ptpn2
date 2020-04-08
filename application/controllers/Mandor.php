<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mandor extends MY_Controller {
	
	public function __construct(){
       parent::__construct();
       $this->load->helper('string');
       $this->load->model('backend/M_mandor','M_mandor');
       $this->load->model('backend/M_kebun','M_kebun');
       $this->load->model('backend/M_afdeling','M_afdeling');
       $this->load->model('backend/M_admin','M_admin');
       $this->load->model('backend/M_keraniaskep','M_keraniaskep');
       $this->load->model('backend/M_keranikcs','M_keranikcs');
   	}

	public function index()
	{  
        if ($this->session->userdata('email')) {
            $data['title']          = 'Halaman Data Mandor';
            $data['view_content']   = 'backend/mandor/mandor';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $data['afdeling']       = $this->M_afdeling->getAll()->result();
            $data['keraniaskep']    = $this->M_keraniaskep->getAll()->result();
            $data['keranikcs']      = $this->M_keranikcs->getAll()->result();
            
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }   
	}

    public function Data_Mandor()
    {   
        if(self::isPost()){

            $user=$this->session->userdata('user');
            extract($user);
            $Select = array(
                "tbl_kerani_kcs.id",
            );
            $Where = array('tbl_kerani_kcs.token'=>$token);
            $result =$this->M_keranikcs->get_result2($Select,$Where,false,false,false);
            foreach ($result as $key);
            $id_kerani_askep=$key->id;


            $data = array();
            $orderBy = array(
                null,
                "tbl_mandor.nama_lengkap",
                "tbl_mandor.email",
            );
            
            $search = array(
                "tbl_kerani_askep.nama_lengkap",
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_mandor.nama_lengkap",
                "tbl_mandor.email",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
            );

            $select = array(
                "tbl_mandor.id",
                "tbl_mandor.email",
                "tbl_mandor.token",
                "tbl_mandor.nama_lengkap",
                "tbl_kerani_kcs.nama_lengkap as keranikcs",
                "tbl_jabatan.jabatan",
                "tbl_mandor.id_kebun",
                "tbl_mandor.id_afdeling",
                "tbl_mandor.photo",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_kerani_askep.nama_lengkap as keraniaskep",
            );
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_mandor.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_mandor.id_afdeling'),
                array('tbl_jabatan','tbl_jabatan.id=tbl_mandor.id_jabatan'),
                array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_mandor.id_kerani_askep'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.id=tbl_mandor.id_kerani_kcs'),
            );
            $where=array('tbl_mandor.id_kerani_kcs'=>$id_kerani_askep);
            $result = $this->M_mandor->findDataTable($orderBy,$search,$select,$where,$join);
            foreach ($result as $item) {
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'" data-token="'.$item->token.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'" data-token="'.$item->token.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $item->image='<img src="assets/backend/img/photo/'.$item->photo.'" width="100px" height="100px;">';
                $data[] = $item;
            }
            return $this->M_mandor->findDataTableOutput($data,$search,$select,$where,$join);
        }
    }


    public function add_mandor()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'email','label'=>'Email','rules'=>'required|valid_email'),
                array('field'=>'nama_lengkap','label'=>'Nama Lengkap','rules'=>'required'),
                array('field'=>'id_kebun','label'=>'Kebun','rules'=>'required'),
                array('field'=>'id_afdeling','label'=>'Afdeling','rules'=>'required'),
                array('field'=>'id_kerani_askep','label'=>'Kerani Askep','rules'=>'required'),
                array('field'=>'id_kerani_kcs','label'=>'Kerani Kcs','rules'=>'required'),
                
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
                        'id_kerani_askep'=>$this->input->post('id_kerani_askep'),
                        'id_kerani_kcs'=>$this->input->post('id_kerani_kcs'),
                        'id_kebun'=>$this->input->post('id_kebun'),
                        'id_afdeling'=>$this->input->post('id_afdeling'),
                        'id_jabatan'=>'2',
                        'photo'=>$datafoto['file_name'],
                        'token'=>$token,
                    );

                    $field2= array(
                        'nama_lengkap'=>$this->input->post('nama_lengkap'),
                        'email'=>$this->input->post('email'),
                        'password'=>password_hash($this->input->post('email'),PASSWORD_BCRYPT),
                        'role'=>'m_kebun',
                        'status'=>'Y',
                        'photo'=>$datafoto['file_name'],
                        'token'=>$token,
                    );
                    
                    $where=array(
                        'id_kerani_askep' => $this->input->post('id_kerani_askep'),
                        'id_kerani_kcs'=>$this->input->post('id_kerani_kcs'),
                        'id_kebun'=>$this->input->post('id_kebun'),
                        'id_afdeling'=>$this->input->post('id_afdeling')
                        );
                    if($this->M_mandor->validasiData($where)){
                        $data=array('success'=>false,'error'=>'Data Sudah Ada');
                    }else{
                        $data=$this->M_mandor->insert(self::xss($field));
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


    public function update_mandor()
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
                $data=$this->M_mandor->update_by_id2($field,$primary_values,$primary_key);
                $data=$this->M_admin->update_by_id2($field2,$primary_values,$primary_key);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    public function Edit_mandor()
    {   
        $select = array(
            "tbl_mandor.id",
            "tbl_mandor.token",
            "tbl_mandor.email",
            "tbl_mandor.id_kerani_askep",
            "tbl_mandor.nama_lengkap",
            "tbl_jabatan.jabatan",
            "tbl_mandor.id_kebun",
            "tbl_mandor.id_afdeling",
            "tbl_mandor.photo",
            "tbl_kebun.nama_kebun",
            "tbl_afdeling.nama_afdeling",
            "tbl_kerani_askep.nama_lengkap as keraniaskep",
        );
        $join = array(
            array('tbl_kebun','tbl_kebun.id=tbl_mandor.id_kebun'),
            array('tbl_afdeling','tbl_afdeling.id=tbl_mandor.id_afdeling'),
            array('tbl_jabatan','tbl_jabatan.id=tbl_mandor.id_jabatan'),
            array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_mandor.id_kerani_askep'),
        );
        $where = array('tbl_mandor.token'=>$this->input->get('token'));
        $result =$this->M_mandor->get_where($select,$where,false,$join,false,false);
        self::json($result);
    }


    public function Delete_Mandor()
    {
        $primary_values=$this->input->get('token');
        $primary_key='token';
        $result = $this->M_mandor->delete_id2($primary_key,$primary_values);
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
	
	public function keraniAskep() {
		$this->load->database();
		$ds_askep = $this->db->query("SELECT id, nama_lengkap FROM tbl_kerani_askep WHERE id_kebun = '" . $this->input->post("id_kebun") . "' ORDER BY nama_lengkap ASC");
		echo "<option value=''>Pilih</option>";
		foreach ($ds_askep->result() as $key) {
			echo "<option value='" . $key->id . "'>" . $key->nama_lengkap . "</option>";
		}
		
		$this->db->close();
	}
}
