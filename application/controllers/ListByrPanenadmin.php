<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListByrPanenadmin extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_datahasilpanen','M_datahasilpanen');
        $this->load->model('backend/M_blok','M_blok');
        $this->load->model('backend/M_history_panen','M_history_panen');
    }

    public function index()
	{   
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Premi Panen';
            $data['view_content'] 	= 'backend/listbyrpanenadmin/listbyrpanenadmin';
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }

    public function datalistbayarpanenadmin()
    {   
        if($this->isPost()){
            $this->load->model('backend/M_libur','M_libur');
            $select_hari=array("tbl_libur.tanggal_libur");
            $where_hari=array('tbl_libur.tanggal_libur'=>date('Y-m-d'));
            $result_hari=$this->M_libur->get_where($select_hari,$where_hari,false,false,false);
            $data = array();
            $orderBy = array(null,"tbl_panen.id");
            $search = array(
                "tbl_panen.id",
            );
            $select = array(
                "tbl_panen.id",
                "tbl_panen.id_kerani_kcs",
                "tbl_panen.tph",
                "tbl_panen.id_pemanen",
                "tbl_panen.jmlh_panen as janjang",
                "tbl_panen.tanggal",
                "tbl_panen.jmlh_brondolan",
                "tbl_panen.id_kebun",
                "tbl_panen.id_afdeling",
                "tbl_kerani_kcs.nama_lengkap as keranikcs",
                "tbl_pemanen.nama_pemanen",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_alat.premi_alat",  
                "tbl_alat.nama_alat",
                "tbl_panen.blok",
                "tbl_blok.blok as kode_blok",
                "tbl_blok.id as id_blok",
                "tbl_blok.keterangan",
                "tbl_kerani_askep.nama_lengkap as kenariaskep"
            );

            $join = array(
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_panen.id_kerani_kcs'),
                array('tbl_pemanen','tbl_pemanen.id=tbl_panen.id_pemanen'),
                array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling'),
                array('tbl_alat','tbl_alat.id=tbl_panen.id_alat'),
                array('tbl_blok','tbl_blok.id=tbl_panen.blok'),
                array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_panen.id_kerani_askep'),
            );

            $where=array('tbl_panen.approve'=>'Y');
            $group_by='tbl_panen.tanggal,tbl_panen.id_pemanen';
            $result = $this->M_datahasilpanen->findDataTable($orderBy,$search,$select,$where,$join,$group_by);
            foreach ($result as $item) {

                // FUNGSI MANGGIL KOMIDEL
                $this->load->model('backend/M_komidel_new','M_komidel_new');
                $select_komidel = array("tbl_komidel_new.komidel","tbl_komidel_new.koef");
                $where_komidel = array(
                    'tbl_komidel_new.id_kebun'=>$item->id_kebun,
                    'tbl_komidel_new.id_afdeling'=>$item->id_afdeling,
                    'tbl_komidel_new.id_blok'=>$item->blok,
                    'tbl_komidel_new.tanggal'=>$item->tanggal
                );
                $result_komidel=$this->M_komidel_new->get_result($select_komidel,$where_komidel,false,false,false);
                if(is_array($result_komidel)){
                    foreach ($result_komidel as $key_komidel);
                    $item->nilai_komidel = $key_komidel->komidel;
                    $item->koef = $key_komidel->koef;
                }else{
                    $item->nilai_komidel = 0;
                    $item->koef = 0;
                }

                $item->brondolan_timbang = ($item->jmlh_brondolan * $item->koef)/100;
                $item->tanggal= date('d-m-Y', strtotime($item->tanggal));
                $item->premi_alat1="Rp. " . number_format($item->premi_alat,0,',','.');
                $item->premi_brondolan1="Rp. " . number_format($item->brondolan_timbang * 150,0,',','.');
                
                $Select=array('tbl_blok.*');
                 // KONDISI LIBUR MINGGU
                if($item->keterangan=='Minggu'){
                    $Where=array(
                        'tbl_blok.id_kebun' => $item->id_kebun,
                        'tbl_blok.id_afdeling' => $item->id_afdeling,
                        'tbl_blok.id' => $item->id_blok,
                        'tbl_blok.status' => 'Y',
                        'tbl_blok.keterangan' =>'Minggu'
                    );
                }else{
                    // FUNGSI LIBUR DINAMIS
                    if($result_hari!==null){
                        $Where=array(
                            'tbl_blok.id_kebun' => $item->id_kebun,
                            'tbl_blok.id_afdeling' => $item->id_afdeling,
                            'tbl_blok.id' => $item->id_blok,
                            'tbl_blok.status' => 'Y',
                            'tbl_blok.keterangan' => 'Tanggal_Merah'
                        );

                    }else{

                        $Where=array(
                            'tbl_blok.id_kebun' => $item->id_kebun,
                            'tbl_blok.id_afdeling' => $item->id_afdeling,
                            'tbl_blok.id' => $item->id_blok,
                            'tbl_blok.status' => 'Y',
                            'tbl_blok.keterangan' => 'All'
                        );
                    }
                }

                $Blok=$this->M_blok->get_row($Select,$Where,false,false,false);
                $bt=$Blok['bt'];
                $p0=$Blok['p0'];
                $p1=$Blok['p1'];
                $p2=$Blok['p2'];
                $p3=$Blok['p3'];
                $rp_p0=$Blok['rp_p0'];
                $rp_p1=$Blok['rp_p1'];
                $rp_p2=$Blok['rp_p2'];
                $rp_p3=$Blok['rp_p3'];
                $Libur=$Blok['keterangan'];
                $BT=$item->janjang * $item->nilai_komidel;
                $item->kg_fix_byr = $BT;
                $item->prestasi=number_format($item->kg_fix_byr,0);

                if(($BT <$p1)){
                    if($Libur=='Minggu'){
                        $P['P0']=$BT*$rp_p1;
                    }else{
                        if($result_hari!==null){
                            $P['P0']=$BT*$rp_p1;
                        }else{
                            if(($BT >=$p1)){
                                $premi=$p1-0;
                                $P['P0']=$premi * $rp_p0;
                            }else{
                                $premi=$item->kg_fix_byr - 0;
                                $P['P0']=$premi * $rp_p0;
                            }
                        }
                    }

                }else{
                    $P['P0']=0;
                }

                if(($BT >=$p1)){
                    if($Libur=='Minggu'){
                        $P['P1']=$BT*$rp_p1;
                    }else{
                        if($result_hari!==null){
                            $P['P1']=$BT*$rp_p1;
                        }else{
                            if(($BT >=$p2)){
                                $premi=$p2 - $p1;
                                $P['P1']=$premi * $rp_p1;
                            }else{
                                $premi=$item->kg_fix_byr - $p1;
                                $P['P1']=$premi * $rp_p1;
                            }
                        }
                        
                    }
                }else{
                    $P['P1']=0;
                }

                if(($BT >=$p2)){
                    if($Libur=='Minggu'){
                        $P['P2']=$BT*$rp_p1;
                    }else{
                        if($result_hari!==null){
                            $P['P2']=$BT*$rp_p1;
                        }else{
                            if(($BT >=$p3)){
                                $premi=$p3 - $p2;
                                $P['P2']=$premi * $rp_p2;
                            }else{
                                $premi=$item->kg_fix_byr - $p2;
                                $P['P2']=$premi * $rp_p2;
                            } 
                        }
                    }               
                }else{
                    $P['P2']=0;
                }

                if(($BT >=$p3)){
                    $premi=$item->kg_fix_byr - $p3;
                    $P['P3']=$premi * $rp_p3;
                }else{
                    $P['P3']=0;
                }

                $item->premi = "Rp. " . number_format($P['P0']+$P['P1']+$P['P2']+$P['P3'],0,',','.');
                $premi=$P['P0']+$P['P1']+$P['P2']+$P['P3'];
                $brondolan=$item->brondolan_timbang * 150;
                $item->total= "Rp. " . number_format($premi + $item->premi_alat + $brondolan,0,',','.');
                $item->bt=$bt;
                $data[] = $item;
            }
            return $this->M_datahasilpanen->findDataTableOutput($data,$search,$select,$where,$join,$group_by);
        }
    }



    public function validasipanen()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_kerani_kcs','label'=>'id_kerani_kcs','rules'=>'required'),
                array('field'=>'id_kebun','label'=>'id_kebun','rules'=>'required'),
                array('field'=>'id_afdeling','label'=>'id_afdeling','rules'=>'required'),
                array('field'=>'tph','label'=>'tph','rules'=>'required'),
                array('field'=>'blok','label'=>'blok','rules'=>'required'),
                array('field'=>'jmlh_panen','label'=>'jmlh_panen','rules'=>'required'),
                array('field'=>'premi_brondolan','label'=>'premi_brondolan','rules'=>'required'),
                array('field'=>'jmlh_brondolan','label'=>'jmlh_brondolan','rules'=>'required'),
                array('field'=>'nama_alat','label'=>'nama_alat','rules'=>'required'),
                array('field'=>'tbs','label'=>'tbs','rules'=>'required'),
                array('field'=>'premi_alat','label'=>'premi_alat','rules'=>'required'),
                array('field'=>'premi_brondolan','label'=>'premi_brondolan','rules'=>'required'),
                array('field'=>'nilai_komidel','label'=>'nilai_komidel','rules'=>'required'),
                array('field'=>'prestasi','label'=>'prestasi','rules'=>'required'),
                array('field'=>'id_pemanen','label'=>'id_pemanen','rules'=>'required'),
                array('field'=>'tanggal','label'=>'tanggal','rules'=>'required'),
            ));
            if($data === true){
                $field= array(
                    'id_kerani_kcs'=>$this->input->post('id_kerani_kcs'),
                    'id_kebun'=>$this->input->post('id_kebun'),
                    'id_afdeling'=>$this->input->post('id_afdeling'),
                    'id_pemanen'=>$this->input->post('id_pemanen'),
                    'tph'=>$this->input->post('tph'),
                    'blok'=>$this->input->post('blok'),
                    'jmlh_panen'=>$this->input->post('jmlh_panen'),
                    'premi_brondolan'=>$this->input->post('premi_brondolan'),
                    'jmlh_brondolan'=>$this->input->post('jmlh_brondolan'),
                    'nama_alat'=>$this->input->post('nama_alat'),
                    'tbs'=>$this->input->post('tbs'),
                    'premi_alat'=>$this->input->post('premi_alat'),
                    'premi_brondolan'=>$this->input->post('premi_brondolan'),
                    'nilai_komidel'=>$this->input->post('nilai_komidel'),
                    'prestasi'=>$this->input->post('prestasi'),
                    'tanggal'=>date('Y-m-d', strtotime($this->input->post('tanggal'))),
                    'id_panen'=>$this->input->post('id'),
                );

                $data1= array('approve'=>'Y');
                $where=array('id'=>$this->input->post('id'));
                $data=$this->M_datahasilpanen->update($data1,$where,false);
                $data=array('success'=>true,'type'=>'Update');

                $data=$this->M_history_panen->insert(self::xss($field));
                $data=array('success'=>true,'type'=>'Add');
            }  
        }
        self::json($data);
    }


    public function day()
    {
        date_default_timezone_set('Asia/jakarta');
        $day=date ('l');
        $listhari = array(			
            'Sunday'   =>'Minggu',
            'Monday'   =>'Senin',
            'Tuesday'  =>'Selasa',
            'Wednesday'=>'Rabu',
            'Thursday' =>'Kamis',
            'Friday'   =>'Jumat',
            'Saturday' =>'Sabtu'
        );
        $hari=$listhari[$day];
        if($hari=='Senin'){
            $data['hari']='Senin';
        }else{
            $data['hari']='All';
        }
        self::json($data);
    }






















































}
