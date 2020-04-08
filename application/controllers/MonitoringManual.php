<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MonitoringManual extends MY_Controller {

	public function __construct(){
        parent::__construct();
		
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Monitoring Manual';
            $data['view_content'] 	= 'backend/monitoringmanual/monitoringmanual';
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
    
    public function get_data() {
    	$this->load->database();
    	$data = array();
		$ds_data = $this->db->query("
			SELECT
				blok.id AS id_blok, blok.blok,
				blok.id_afdeling,
				COALESCE(hasil_kg.kg, 0) AS kg_hari_ini,
				COALESCE(hasil_kg_restan.kg, 0) AS kg_dari_restan,
				COALESCE(brondolan_realisasi_all.brondolan, 0) AS brondolan_realisasi_all,
				COALESCE(brondolan_panen_all.brondolan, 0) AS brondolan_panen_all,
				COALESCE(brondolan_panen_blok.brondolan, 0) AS brondolan_panen_blok,
				COALESCE(panen_blok.janjang, 0) AS panen_blok,
				COALESCE(trip_blok.janjang, 0) AS trip_blok,
				COALESCE(restan_diangkut.jumlah_restan, 0) AS restan_diangkut
			FROM
				tbl_blok blok
				LEFT JOIN (
					SELECT
						setdet.id_blok, SUM(setdet.hasil_kg) AS kg
					FROM
						tbl_trip_setelah_timbang_detail setdet
						LEFT JOIN tbl_trip_020_detail tripdet ON setdet.id_trip_detail = tripdet.id
						LEFT JOIN tbl_trip_020 trip ON tripdet.id_trip = trip.id
					WHERE
						trip.tanggal = '" . $this->input->post("tanggal") . "'
					GROUP BY
						setdet.id_blok
				) hasil_kg ON blok.id = hasil_kg.id_blok
				LEFT JOIN (
					SELECT
						setdet.id_blok, SUM(hasil_kg_restan) AS kg
					FROM
						tbl_trip_setelah_timbang_detail setdet
						LEFT JOIN tbl_trip_020_detail tripdet ON setdet.id_trip_detail = tripdet.id
					WHERE
						tripdet.tgl_restan = '" . $this->input->post("tanggal") . "'
					GROUP BY
						setdet.id_blok
				) hasil_kg_restan ON blok.id = hasil_kg_restan.id_blok
				LEFT JOIN (
					SELECT
						trip.id_afdeling, SUM(sett.timbang_brondolan) AS brondolan
					FROM
						tbl_trip_setelah_timbang sett
						INNER JOIN tbl_trip_020 trip ON sett.id_trip = trip.id
					WHERE
						trip.tanggal = '" . $this->input->post("tanggal") . "'
					GROUP BY
						trip.id_afdeling
				) brondolan_realisasi_all ON blok.id_afdeling = brondolan_realisasi_all.id_afdeling
				LEFT JOIN (
					SELECT
						panen.id_afdeling, SUM(panen.jmlh_brondolan) AS brondolan
					FROM
						tbl_panen panen
					WHERE
						panen.tanggal = '" . $this->input->post("tanggal") . "'
					GROUP BY
						panen.id_afdeling
				) brondolan_panen_all ON blok.id_afdeling = brondolan_panen_all.id_afdeling
				LEFT JOIN (
					SELECT
						panen.id_afdeling, panen.blok AS id_blok, SUM(panen.jmlh_brondolan) AS brondolan
					FROM
						tbl_panen panen
					WHERE
						panen.tanggal = '" . $this->input->post("tanggal") . "'
					GROUP BY
						panen.id_afdeling, panen.blok
				) brondolan_panen_blok ON
					blok.id_afdeling = brondolan_panen_blok.id_afdeling
					AND blok.id = brondolan_panen_blok.id_blok
				LEFT JOIN (
					SELECT
						panen.id_afdeling, panen.blok AS id_blok, SUM(panen.jmlh_panen) AS janjang
					FROM
						tbl_panen panen
					WHERE
						panen.tanggal = '" . $this->input->post("tanggal") . "'
					GROUP BY
						panen.id_afdeling, panen.blok
				) panen_blok ON
					blok.id_afdeling = panen_blok.id_afdeling
					AND blok.id = panen_blok.id_blok
				LEFT JOIN (
					SELECT
						tripdet.id_blok, SUM(tripdet.jumlah_janjang) AS janjang
					FROM
						tbl_trip_020_detail tripdet
						INNER JOIN tbl_trip_020 trip ON tripdet.id_trip = trip.id
					WHERE
						trip.tanggal = '" . $this->input->post("tanggal") . "'
					GROUP BY
						tripdet.id_blok
				) trip_blok ON blok.id = trip_blok.id_blok
				LEFT JOIN (
					SELECT
						tripdet.id_blok, SUM(tripdet.jumlah_restan) AS jumlah_restan
					FROM
						tbl_trip_020_detail tripdet
						LEFT JOIN tbl_trip_020 trip ON tripdet.id_trip = trip.id
					WHERE
						tripdet.tgl_restan = '" . $this->input->post("tanggal") . "'
					GROUP BY
						tripdet.id_blok
				) restan_diangkut ON blok.id = restan_diangkut.id_blok
			WHERE
				blok.id_afdeling = '" . $this->input->post("id_afdeling") . "'
			ORDER BY
				blok.blok ASC
		");
		foreach ($ds_data->result() as $dsd) {
			$temp = array(
				"id_blok"					=> $dsd->id_blok,
				"blok"						=> $dsd->blok,
				"id_afdeling"				=> $dsd->id_afdeling,
				"kg_hari_ini"				=> $dsd->kg_hari_ini,
				"kg_dari_restan"			=> $dsd->kg_dari_restan,
				"brondolan_realisasi_all"	=> $dsd->brondolan_realisasi_all,
				"brondolan_panen_all"		=> $dsd->brondolan_panen_all,
				"brondolan_panen_blok"		=> $dsd->brondolan_panen_blok,
				"panen_blok"				=> $dsd->panen_blok,
				"trip_blok"					=> $dsd->trip_blok,
				"restan_diangkut"			=> $dsd->restan_diangkut
			);
			array_push($data, $temp);
		}
    	$this->db->close();
    	
    	echo json_encode($data);
    }
	
}
