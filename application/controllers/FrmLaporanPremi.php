<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmLaporanPremi extends MY_Controller {

	public function __construct(){
        parent::__construct();
		
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Laporan premi panen';
            $data['view_content'] 	= 'backend/frmlaporanpremi/frmlaporanpremi';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
	}

    public function afdeling() {
        $this->load->database();
        $afdeling_data = array();
        $ds_kebun = $this->db->query("
            SELECT DISTINCT
                kbn.id, kbn.nama_kebun
            FROM
                tbl_kebun kbn
                INNER JOIN tbl_afdeling afd ON kbn.id = afd.id_kebun
            ORDER BY
                nama_kebun ASC
        ");
        foreach($ds_kebun->result() as $row_kebun) {
            $temp_kebun = array(
                "id" => $row_kebun->id,
                "nama_kebun" => $row_kebun->nama_kebun,
                "afdeling" => array()
            );
            $sql_afdeling = "SELECT id, nama_afdeling FROM tbl_afdeling WHERE id_kebun = '" . $row_kebun->id . "' ORDER BY nama_afdeling ASC";
            $ds_afdeling = $this->db->query($sql_afdeling);
            foreach($ds_afdeling->result() as $row_afdeling) {
                $temp_afdeling = array(
                    "id" => $row_afdeling->id,
                    "nama_afdeling" => $row_afdeling->nama_afdeling
                );
                array_push($temp_kebun["afdeling"], $temp_afdeling);
            }
            array_push($afdeling_data, $temp_kebun);
        }
        $this->db->close();
        self::json($afdeling_data);
    }
    
    public function mandor() {
    	$this->load->database();
    	$ds_mandor = $this->db->query("SELECT id, nama_lengkap FROM tbl_mandor WHERE id_afdeling = '" . $this->input->post("id_afdeling") . "'");
		$mandor = array();
		foreach ($ds_mandor->result() as $ds) {
			array_push($mandor, $ds);
		}
    	$this->db->close();
		
		self::json($mandor);
    }
    
    public function bulan() {
        $bulans = array(
            array("angka" => "01", "nama" => "Januari"),
            array("angka" => "02", "nama" => "Februari"),
            array("angka" => "03", "nama" => "Maret"),
            array("angka" => "04", "nama" => "April"),
            array("angka" => "05", "nama" => "Mei"),
            array("angka" => "06", "nama" => "Juni"),
            array("angka" => "07", "nama" => "Juli"),
            array("angka" => "08", "nama" => "Agustus"),
            array("angka" => "09", "nama" => "September"),
            array("angka" => "10", "nama" => "Oktober"),
            array("angka" => "11", "nama" => "November"),
            array("angka" => "12", "nama" => "Desember")
        );

        self::json($bulans);
    }
}
