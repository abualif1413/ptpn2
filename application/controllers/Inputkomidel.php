<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inputkomidel extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('string');
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        $this->load->model('backend/M_datahasilpanen','M_datahasilpanen');
        $this->load->model('backend/M_komidel','M_komidel');
        $this->load->model('backend/M_keraniaskep','M_keraniaskep');
        
        
    }

	public function index()
	{   
        
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Input Nilai Berat Timbangan (Nilai Komidel)';
            $data['view_content'] 	= 'backend/inputkomidel/inputkomidel';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }

    public function add_data_hasil_panen()
	{  
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_pemanen','label'=>'Nama Pemanen','rules'=>'required'),
                array('field'=>'jmlh_panen','label'=>'Jumlah Panen','rules'=>'required'),
                array('field'=>'tph','label'=>'TPH','rules'=>'required'),
                array('field'=>'blok','label'=>'BLOK','rules'=>'required'),
            ));

            $user=$this->session->userdata('user');
			extract($user);
            if($data === true){
                
                $field= array(
                    'id_mandor'=> $id,
                    'id_pemanen'=>$this->input->post('id_pemanen'),
                    'jmlh_panen'=>$this->input->post('jmlh_panen'),
                    'tph'=>$this->input->post('tph'),
                    'blok'=>$this->input->post('blok'),
                    'tanggal'=> date("Y-m-d H:i:s"),
                );

                $where=array('id_pemanen' => $this->input->post('id_pemanen'),'tanggal'=> date("Y-m-d"));
                if($this->M_datahasilpanen->validasiData($where)){
                    $data=array('success'=>false,'error'=>'Data Sudah Ada');
                }else{
                    $data=$this->M_datahasilpanen->insert(self::xss($field));
                    $data=array('success'=>true,'type'=>'Add');
                }
            }  
        }
        self::json($data);
    }
    

    public function data_input_berat()
    {   
        if($this->isPost()){
            $user=$this->session->userdata('user');
            extract($user);
            $Select = array(
                "tbl_kerani_askep.id",
            );
            $Where = array('tbl_kerani_askep.token'=>$token);
            $result =$this->M_keraniaskep->get_result2($Select,$Where,false,false,false);
            foreach ($result as $key);
            $id_kerani_askep=$key->id;

            $data = array();
            $orderBy = array(null,
                "tbl_pemanen.id",
                "tbl_pemanen.nama_pemanen",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_pemanen.barcode",
                "tbl_pemanen.keterangan",
                "tbl_kerani_kcs.nama_lengkap",
            );
            $search = array(
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_panen.tanggal",
            );
            $select = array(
                "tbl_panen.*",
                "SUM(tbl_panen.jmlh_panen) AS total",
                "tbl_kebun.nama_kebun",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.barcode",
                "tbl_afdeling.nama_afdeling",
                "tbl_kerani_kcs.nama_lengkap",
                "tbl_mandor.nama_lengkap as mandor"
            );
            $join = array(
                array('tbl_pemanen','tbl_pemanen.id=tbl_panen.id_pemanen'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_panen.id_kerani_kcs'),
                array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun'),
                array('tbl_mandor','tbl_mandor.token=tbl_pemanen.id_mandor'),
            );
            
            $where=array('tbl_panen.status' => 'N','tbl_panen.id_kerani_askep' => $id_kerani_askep);
            $group_by=array('tbl_pemanen.id_kerani_kcs','tbl_afdeling.id','tbl_afdeling.id','tbl_panen.tanggal');
            $result = $this->M_datahasilpanen->findDataTable($orderBy,$search,$select,$where,$join,$group_by);
            foreach ($result as $item) {
                $item->tanggal= date('d-m-Y', strtotime($item->tanggal));
                $btnAction ='
                <form method="POST" action="Inputkomidel/insertKomidel">
                    <input type="hidden" class="form-control" name="id_kerani_askep" value="'.$item->id_kerani_askep.'">
                    <input type="hidden" class="form-control" name="id_kerani_kcs" value="'.$item->id_kerani_kcs.'">
                    <input type="hidden" class="form-control" name="id_kebun" value="'.$item->id_kebun.'">
                    <input type="hidden" class="form-control" name="id_afdeling" value="'.$item->id_afdeling.'">
                    <input type="hidden" class="form-control" name="jmlh_panen" value="'.$item->total.'">
                    <input type="hidden" class="form-control" name="tanggal" value="'.date('Y-m-d', strtotime($item->tanggal)).'">
                    <div class="form-group">
                        <label >Kilo Gram</label>
                        <input type="number" name="kg" class="form-control" placeholder="Kg" required style="width:110px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                     ';
                $btnDetail = '<button class="btn btn-warning btn-outline-warning btn-mini"  onClick="Detail(this)" data-id="'.$item->id.'" data-id_kebun="'.$item->id_kebun.'" data-id_afdeling="'.$item->id_afdeling.'" data-tanggal="'.$item->tanggal.'" data-id_pemanen="'.$item->id_pemanen.'" data-id_kerani_kcs="'.$item->id_kerani_kcs.'"><i class="fa fa-pencil-square-o"></i>Show Detail</button>';
                $item->button_action = $btnAction;
                $item->button_detail = $btnDetail;
                $data[] = $item;
            }
            return $this->M_datahasilpanen->findDataTableOutput($data,$search,$select,$where,$join,$group_by);
        }
    }

    


    public function insertKomidel()
    {
        $a=$this->input->post('jmlh_panen');
        $b=$this->input->post('kg');
        $komidel=$b/$a;
        $kode=random_string('alnum',10);
        $field= array(
            'id_kerani_askep'=> $this->input->post('id_kerani_askep'),
            'id_kerani_kcs'=> $this->input->post('id_kerani_kcs'),
            'id_kebun'=>$this->input->post('id_kebun'),
            'id_afdeling'=>$this->input->post('id_afdeling'),
            'jmlh_panen'=>$this->input->post('jmlh_panen'),
            'tanggal_panen'=>$this->input->post('tanggal'),
            'kg'=>$this->input->post('kg'),
            'nilai_komidel'=>$komidel,
            'kode'=>$kode,
        );

        $where=array(
            'id_kerani_askep' => $field['id_kerani_askep'],
            'id_kerani_kcs' => $field['id_kerani_kcs'],
            'id_kebun' => $field['id_kebun'],
            'id_afdeling' => $field['id_afdeling'],
            'tanggal_panen' => $field['tanggal_panen'],
        );

        if($this->M_komidel->validasiData($where)){
            $this->session->set_flashdata('warning','Data Sudah Pernah Di Input');
            redirect(base_url('/DataKomidel'));
        }else{
            $data=$this->M_komidel->insert(self::xss($field));
            
            if($data){
                $where=array(
                    'id_kerani_askep' => $field['id_kerani_askep'],
                    'id_kerani_kcs' => $field['id_kerani_kcs'],
                    'id_kebun' => $field['id_kebun'],
                    'id_afdeling' => $field['id_afdeling'],
                    'tanggal' => $field['tanggal_panen'],
                );
                $this->db->set('status', 'Y');
                $this->db->set('kode', $kode);
                $this->M_datahasilpanen->update_where(self::xss($where,false));
                $this->session->set_flashdata('success','Berhasil Input Berat Kilo Gram');
                redirect(base_url('/DataKomidel'));
            }else{
                $this->session->set_flashdata('error','Insert Gagal');
                redirect(base_url('/DataKomidel'));
            }
            
        }
        

    }



    public function detail_hasil_panen()
    {   
        $select = array(
            "tbl_panen.*",
            "tbl_alat.premi_alat",
            "tbl_kebun.nama_kebun",
            "tbl_pemanen.nama_pemanen",
            "tbl_pemanen.barcode",
            "tbl_afdeling.nama_afdeling",
            "tbl_kerani_kcs.nama_lengkap",
            "tbl_blok.blok as kode_blok",
            "tbl_mandor.nama_lengkap as mandor",
        );
        
        $join = array(
            array('tbl_pemanen','tbl_pemanen.id=tbl_panen.id_pemanen'),
            array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_panen.id_kerani_kcs'),
            array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun'),
            array('tbl_afdeling','tbl_afdeling.id=tbl_panen.id_afdeling'),
            array('tbl_alat','tbl_alat.id=tbl_panen.id_alat'),
            array('tbl_blok','tbl_blok.id=tbl_panen.blok'),
            array('tbl_mandor','tbl_mandor.token=tbl_pemanen.id_mandor'),
            
        );
        $where=array(
            'tbl_panen.id_kerani_kcs'=>$this->input->post('id_kerani_kcs'),
            'tbl_panen.id_kebun'=>$this->input->post('id_kebun'),
            'tbl_panen.id_afdeling'=>$this->input->post('id_afdeling'),
            'tbl_panen.tanggal'=>date('Y-m-d', strtotime($this->input->post('tanggal'))),
        );

        $group_by='tbl_panen.tanggal,tbl_panen.id_pemanen';
        $result =$this->M_datahasilpanen->get_result($select,$where,$join,$group_by,false);
        self::json($result);
		
    }

    




























































}
