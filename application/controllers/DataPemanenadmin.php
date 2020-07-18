<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataPemanenadmin extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        $this->load->model('backend/M_kebun','M_kebun');
        $this->load->model('backend/M_afdeling','M_afdeling');
        $this->load->model('backend/M_mandor','M_mandor');
    }

	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Data Pemanen';
            $data['view_content'] 	= 'backend/datapemanenadmin/datapemanenadmin';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $data['afdeling']       = $this->M_afdeling->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }

    public function add_data_pemanen()
	{  
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'barcode','label'=>'Barcode','rules'=>'required'),
                array('field'=>'nama_pemanen','label'=>'Nama Pemanen','rules'=>'required'),
                array('field'=>'keterangan','label'=>'Keterangan','rules'=>'required'),
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
            $user=$this->session->userdata('user');
			extract($user);
            if($data === true){
                if (!isset($errorfoto)) {

                    $select = array(
                        "tbl_mandor.token",
                        "tbl_mandor.id_kebun",
                        "tbl_mandor.id_afdeling",
                        "tbl_mandor.id_kerani_askep"
                    );
                    $where = array('tbl_mandor.token'=>$token);
                    $result =$this->M_mandor->get_result($select,$where,false,false,false);
                    foreach ($result as $key);
                    $token=$key->token;
                    $id_kebun=$key->id_kebun;
                    $id_afdeling=$key->id_afdeling;
                    $id_kerani_askep=$key->id_kerani_askep;

                    $field= array(
                        'id_mandor_kebun'=>$token,
                        'barcode'=>$this->input->post('barcode'),
                        'nama_pemanen'=>$this->input->post('nama_pemanen'),
                        'id_kerani_askep'=>$id_kerani_askep,
                        'id_kebun'=>$id_kebun,
                        'id_afdeling'=>$id_afdeling,
                        'keterangan'=>$this->input->post('keterangan'),
                        'photo'=>$datafoto['file_name'],
                    );
                    $data=$this->M_DataPemanen->insert(self::xss($field));
                    $data=array('success'=>true,'type'=>'Add');

                }else{
                    $data=array('error'=>$errorfoto);
                }
            }  
        }
        self::json($data);
    }
    
    public function barcode()
    {
        $kebun=$this->input->post('kebun');
        $afdeling=$this->input->post('afdeling');
        $data['barcode']=$this->M_DataPemanen->barcode($kebun,$afdeling);
        self::json($data);
    }

    public function data_pemanen()
    {   
        if($this->isPost()){
            $data = array();
            $orderBy = array(null,"tbl_pemanen.id","tbl_pemanen.nama_pemanen","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_pemanen.barcode","tbl_pemanen.keterangan","tbl_users.nama_lengkap");
            $search = array(
                "tbl_kerani_askep.nama_lengkap",
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.no_sap",
                "tbl_pemanen.barcode");
            $select = array("tbl_pemanen.*","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_kerani_kcs.nama_lengkap","tbl_kerani_askep.nama_lengkap as keraniaskep");
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_pemanen.id_kerani_kcs'),
                array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_pemanen.id_kerani_askep'),
            );
            $result = $this->M_DataPemanen->findDataTable($orderBy,$search,$select,false,$join);
            foreach ($result as $item) {
                $item->image='<img src="assets/backend/img/photo/'.$item->photo.'" width="100px" height="100px;">';
                $item->img_barcode='<img src="assets/backend/qr/'.$item->img_barcode.'" width="100px" height="100px;">';
                $data[] = $item;
            }
            return $this->M_DataPemanen->findDataTableOutput($data,$search,$select,false,$join);
        }
    }


    public function Edit_Pemanen()
    {   
        $select = array("tbl_pemanen.*","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_users.nama_lengkap");
        $join = array(
            array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun','left'),
            array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling','left'),
            array('tbl_users','tbl_users.id=tbl_pemanen.id_mandor_kebun','left')
        );
        $where = array('tbl_pemanen.id'=>$this->input->get('id'));
        $result =$this->M_DataPemanen->get_where($select,$where,false,$join,false,false);
        self::json($result);
    }

    public function update_Pemanen()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'barcode','label'=>'Barcode','rules'=>'required'),
                array('field'=>'nama_pemanen','label'=>'Nama Pemanen','rules'=>'required'),
                array('field'=>'keterangan','label'=>'Keterangan','rules'=>'required'),
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

            $user=$this->session->userdata('user');
            extract($user);
            
            $Select = array(
                "tbl_mandor.id_kebun",
                "tbl_mandor.id_afdeling",
            );
            $Where = array('tbl_mandor.token'=>$token);
            $result2 =$this->M_mandor->get_result2($Select,$Where,false,false,false);
            foreach ($result2 as $key);
            $id_kebun=$key->id_kebun;
            $id_afdeling=$key->id_afdeling;

            
            if($data === true){
                if (!isset($errorfoto)) {
                    
                    $field= array(
                        'barcode'=>$this->input->post('barcode'),
                        'nama_pemanen'=>$this->input->post('nama_pemanen'),
                        'id_kebun'=>$id_kebun,
                        'id_afdeling'=>$id_afdeling,
                        'keterangan'=>$this->input->post('keterangan'),
                        'photo'=>$datafoto['file_name'],
                    );
                }else{
                    $field= array(
                        'barcode'=>$this->input->post('barcode'),
                        'nama_pemanen'=>$this->input->post('nama_pemanen'),
                        'id_kebun'=>$id_kebun,
                        'id_afdeling'=>$id_afdeling,
                        'keterangan'=>$this->input->post('keterangan'),
                    );
                }
                
                $primary_values=$this->input->post('id');
                $data=$this->M_DataPemanen->update_by_id($field,$primary_values);
                $data=array('success'=>true,'type'=>'Update');
            }  
        }
        self::json($data);
    }

    public function Delete_Pemanen()
    {
        $primary_values=$this->input->get('id');
        $result = $this->M_DataPemanen->delete_id($primary_values);
        $msg['success'] = true;
        if ($result) {
            $msg['success'] = false;
        }
        self::json($msg);
    }

    




























































}
