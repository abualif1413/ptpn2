<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TargetPanenBulanan extends MY_Controller {

	public function __construct(){
        parent::__construct();
		
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Target Panen Bulanan';
            $data['view_content'] 	= 'backend/targetpanenbulanan/targetpanenbulanan';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
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

    public function tampilkan() {
        $sql = "
            SELECT
                MAX(blk.id) AS id, blk.blok, MAX(blk.tahun_tanam) AS tahun_tanam,
                MAX(COALESCE(target.target_panen, 0)) AS target_panen
            FROM
                tbl_blok blk
                LEFT JOIN tbl_target_panen_bulanan target ON blk.id = target.id_blok AND bulan = '" . $this->input->post("bulan") . "' AND tahun = '" . $this->input->post("tahun") . "'
            WHERE
                blk.id_afdeling = '" . $this->input->post("afdeling") . "'
            GROUP BY
                blk.blok
            ORDER BY
                blk.blok ASC
        ";
        $this->load->database();
        $result = array();
        $ds = $this->db->query($sql);
        foreach($ds->result() as $row) {
            $temp = array(
                "id" => $row->id,
                "blok" => $row->blok,
                "tahun_tanam" => $row->tahun_tanam,
                "target_panen" => $row->target_panen
            );
            array_push($result, $temp);
        }
        $this->db->close();
        self::json($result);
    }

    public function go_simpan() {
        $this->load->database();
        $data_input_to_string = $this->input->post("data_input_to_string");
        $data_input = json_decode($data_input_to_string, TRUE);
        $insert_string = "INSERT INTO tbl_target_panen_bulanan(id_blok, bulan, tahun, target_panen) VALUES ";
        foreach($data_input as $index => $data) {
            // Sambil menghapus data dia di bulan dan tahun ini jika ada
            $delete_string = "DELETE FROM tbl_target_panen_bulanan WHERE id_blok='" . $data["id_blok"] . "' AND bulan='" . $data["bulan"] . "' AND tahun='" . $data["tahun"] . "'";
            $this->db->query($delete_string);

            if($index == 0) {
                $insert_string .= "('" . $data["id_blok"] . "', '" . $data["bulan"] . "', '" . $data["tahun"] . "', '" . $data["target_panen"] . "')";
            } else {
                $insert_string .= ", ('" . $data["id_blok"] . "', '" . $data["bulan"] . "', '" . $data["tahun"] . "', '" . $data["target_panen"] . "')";
            }
        }
        $this->db->query($insert_string);
        $this->db->close();
    }
	
}
