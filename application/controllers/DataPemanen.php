<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataPemanen extends MY_Controller {

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
            $data['view_content'] 	= 'backend/datapemanen/datapemanen';
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
            // GENERATE QRCODE
            $this->load->library('ciqrcode');
            $qr_image=$this->input->post('barcode').'.png';
            $params['data'] = $this->input->post('barcode');
            $params['level'] = 'H';
            $params['size'] = 8;
            $params['savename'] =FCPATH."assets/backend/qr/".$qr_image;
            $this->ciqrcode->generate($params);

            
            if($data === true){
                if (!isset($errorfoto)) {
                    $select = array(
                        "tbl_mandor.token",
                        "tbl_mandor.id_kebun",
                        "tbl_mandor.id_afdeling",
                        "tbl_mandor.id_kerani_askep",
                        "tbl_mandor.id_kerani_kcs",
                        "tbl_kerani_kcs.token as tokenkeranikcs"
                    );
                    $where = array('tbl_mandor.token'=> $this->session->token);
                    $join=array(array('tbl_kerani_kcs','tbl_kerani_kcs.id=tbl_mandor.id_kerani_kcs'));
                    $result =$this->M_mandor->get_result($select,$where,$join,false,false);
                    foreach ($result as $key);
                    $id_kerani_kcs=$key->tokenkeranikcs;
                    $id_mandor=$key->token;
                    $id_kebun=$key->id_kebun;
                    $id_afdeling=$key->id_afdeling;
                    $id_kerani_askep=$key->id_kerani_askep;
                    $field= array(
                        'id_kerani_kcs'=>$id_kerani_kcs,
                        'id_mandor'=>$id_mandor,
                        'barcode'=>$this->input->post('barcode'),
                        'img_barcode'=>$qr_image,
                        'nama_pemanen'=>$this->input->post('nama_pemanen'),
                        'id_kerani_askep'=>$id_kerani_askep,
                        'id_kebun'=>$id_kebun,
                        'id_afdeling'=>$id_afdeling,
                        'keterangan'=>$this->input->post('keterangan'),
                        'photo'=>$datafoto['file_name'],
                    );

                    $wherevalidasi=array(
                        'nama_pemanen' => $this->input->post('nama_pemanen')
                        );

                    if($this->M_DataPemanen->validasiData($wherevalidasi)){
                        $data=array('success'=>false,'error'=>'Data Sudah Ada');
                    }else{
                        $data=$this->M_DataPemanen->insert(self::xss($field));
                        $data=array('success'=>true,'type'=>'Add');
                    }

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
            $user=$this->session->userdata('user');
			extract($user);
            $data = array();
            $orderBy = array(null,"tbl_pemanen.id","tbl_pemanen.nama_pemanen","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_pemanen.barcode","tbl_pemanen.keterangan","tbl_mandor.nama_lengkap");
            $search = array("tbl_mandor.nama_lengkap","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_pemanen.nama_pemanen","tbl_pemanen.barcode");
            $select = array("tbl_pemanen.*","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_mandor.nama_lengkap","tbl_kerani_kcs.nama_lengkap as keranikcs");
            $join = array(
                array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling'),
                array('tbl_mandor','tbl_mandor.token=tbl_pemanen.id_mandor'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_pemanen.id_kerani_kcs')
            );
            $where=array('id_mandor' => $token);
            $result = $this->M_DataPemanen->findDataTable($orderBy,$search,$select,$where,$join);
            foreach ($result as $item) {
                $item->image='<img src="assets/backend/img/photo/'.$item->photo.'" width="100px" height="100px;">';
                $item->img_barcode='<img src="assets/backend/qr/'.$item->img_barcode.'" width="100px" height="100px;">';
                $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Update(this)" data-id="'.$item->id.'"><i class="fa fa-pencil-square-o"></i>Edit</button>';
                $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onClick="Delete(this)" data-id="'.$item->id.'"><i class="fa fa-trash-o"></i>Hapus</button>';
                $item->button_action = $btnAction;
                $data[] = $item;
            }
            return $this->M_DataPemanen->findDataTableOutput($data,$search,$select,$where,$join);
        }
    }


    public function Edit_Pemanen()
    {   
        $select = array("tbl_pemanen.*","tbl_kebun.nama_kebun","tbl_afdeling.nama_afdeling","tbl_mandor.nama_lengkap");
        $join = array(
            array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun','left'),
            array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling','left'),
            array('tbl_mandor','tbl_mandor.token=tbl_pemanen.id_mandor','left')
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
