<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KgPanenPerPemanen extends MY_Controller {

	public function __construct(){
        parent::__construct();
		
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Prestasi panen per pemanen';
            $data['view_content'] 	= 'backend/kgpanenperpemanen/kgpanenperpemanen';
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
    
    public function go_kg_hasil_panen() {
    	$this->load->database();
    	
    	/* Mengambil bjr dan persentasi kg brondolan menggunakan algoritma dynamic programming */
    	// 1. Ambil blok-blok mana saja yang di panen oleh pemanen-pemanen yang akan diload
    	$ds_blok = $this->db->query("
    		SELECT DISTINCT
				COALESCE(blok.id, 0) AS id
			FROM
				tbl_pemanen pemanen
				INNER JOIN tbl_mandor mandor ON pemanen.id_mandor = mandor.token
				LEFT JOIN tbl_panen panen ON pemanen.id = panen.id_pemanen AND panen.tanggal = '" . $this->input->post("tanggal") . "'
				LEFT JOIN tbl_blok blok ON panen.blok = blok.id
			WHERE
				mandor.id = '" . $this->input->post("id_mandor") . "'
    	");
		$id_blok = array();
		foreach ($ds_blok->result() as &$blok) {
			if($blok->id != 0)
				array_push($id_blok, $blok->id);
		}
		
		// 2. Ambil hasil realisasi nya
		$ds_realisasi = $this->db->query("
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
				blok.id IN (" . implode(",", $id_blok) . ")
			ORDER BY
				blok.blok ASC
		");
		$dynamic_programming_realisasi = array();
		foreach ($ds_realisasi->result() as $dsr) {
			$temp = (array)$dsr;
			$dynamic_programming_realisasi[$dsr->id_blok] = $temp;
		}
    	
    	$ds_hasil = $this->db->query("
    		SELECT
				1 AS urutan, pemanen.id AS id_pemanen, MAX(pemanen.nama_pemanen) AS nama_pemanen,
				0 AS id_blok, '' AS blok,
				SUM(panen.jmlh_panen) AS jmlh_panen, SUM(panen.jmlh_brondolan) AS jmlh_brondolan
			FROM
				tbl_pemanen pemanen
				INNER JOIN tbl_mandor mandor ON pemanen.id_mandor = mandor.token
				LEFT JOIN tbl_panen panen ON pemanen.id = panen.id_pemanen AND panen.tanggal = '" . $this->input->post("tanggal") . "'
				LEFT JOIN tbl_blok blok ON panen.blok = blok.id
			WHERE
				mandor.id = '" . $this->input->post("id_mandor") . "'
			GROUP BY
				pemanen.id
			
			UNION
			
			SELECT
				2 AS urutan, pemanen.id AS id_pemanen, MAX(pemanen.nama_pemanen) AS nama_pemanen,
				panen.blok AS id_blok, MAX(blok.blok) AS blok,
				SUM(panen.jmlh_panen) AS jmlh_panen, SUM(panen.jmlh_brondolan) AS jmlh_brondolan
			FROM
				tbl_pemanen pemanen
				INNER JOIN tbl_mandor mandor ON pemanen.id_mandor = mandor.token
				LEFT JOIN tbl_panen panen ON pemanen.id = panen.id_pemanen AND panen.tanggal = '" . $this->input->post("tanggal") . "'
				LEFT JOIN tbl_blok blok ON panen.blok = blok.id
			WHERE
				mandor.id = '" . $this->input->post("id_mandor") . "'
			GROUP BY
				pemanen.id, panen.blok
			
			UNION
			
			SELECT
				3 AS urutan, pemanen.id AS id_pemanen, MAX(pemanen.nama_pemanen) AS nama_pemanen,
				0 AS id_blok, '' AS blok,
				SUM(panen.jmlh_panen) AS jmlh_panen, SUM(panen.jmlh_brondolan) AS jmlh_brondolan
			FROM
				tbl_pemanen pemanen
				INNER JOIN tbl_mandor mandor ON pemanen.id_mandor = mandor.token
				LEFT JOIN tbl_panen panen ON pemanen.id = panen.id_pemanen AND panen.tanggal = '" . $this->input->post("tanggal") . "'
				LEFT JOIN tbl_blok blok ON panen.blok = blok.id
			WHERE
				mandor.id = '" . $this->input->post("id_mandor") . "'
			GROUP BY
				pemanen.id
			
			ORDER BY
				nama_pemanen ASC, urutan ASC, blok ASC
    	");
		$hasil = array();
		foreach ($ds_hasil->result() as &$result) {
			$id_blok = ($result->urutan == 2) ? $result->id_blok : 0;
			//$realisasi_dari_panen = ($result->urutan == 2) ? $dynamic_programming_realisasi[$id_blok]["kg_hari_ini"] : 0;
			$realisasi_dari_panen = ($result->urutan == 2 && $result->id_blok != null) ? $dynamic_programming_realisasi[$id_blok]["kg_hari_ini"] : 0;
			$realisasi_dari_restan = ($result->urutan == 2 && $result->id_blok != null) ? $dynamic_programming_realisasi[$id_blok]["kg_dari_restan"] : 0;
			$all_blok_panen = ($result->urutan == 2 && $result->id_blok != null) ? $dynamic_programming_realisasi[$id_blok]["trip_blok"] : 0;
			$all_blok_restan = ($result->urutan == 2 && $result->id_blok != null) ? $dynamic_programming_realisasi[$id_blok]["restan_diangkut"] : 0;
			$bjr = (($all_blok_panen + $all_blok_restan) > 0) ? (($realisasi_dari_panen + $realisasi_dari_restan) / ($all_blok_panen + $all_blok_restan)) : 0;
			$kg_tbs = $bjr * $result->jmlh_panen;
			$realisasi_brd = ($result->urutan == 2 && $result->id_blok != null) ? ($dynamic_programming_realisasi[$id_blok]["brondolan_realisasi_all"]
																					* $dynamic_programming_realisasi[$id_blok]["brondolan_panen_blok"]
																					/ $dynamic_programming_realisasi[$id_blok]["brondolan_panen_all"]) : 0;
			$blok_brd = ($result->urutan == 2 && $result->id_blok != null) ? $dynamic_programming_realisasi[$id_blok]["brondolan_panen_blok"] : 0;
			$brd_rata_rata = ($blok_brd > 0) ? $realisasi_brd / $blok_brd : 0;
			$kg_brd = $brd_rata_rata * $result->jmlh_brondolan;
			
			$the_hasil = (array)$result;
			$the_hasil["kg_tbs"] = $kg_tbs;
			$the_hasil["kg_brd"] = $kg_brd;
			array_push($hasil, $the_hasil);
		}
    	$this->db->close();
		
		self::json($hasil);
    }
}
