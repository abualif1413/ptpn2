<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inputkomidelnew020 extends MY_Controller {

	public function __construct(){
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Input Nilai Komidel';
            $data['view_content'] 	= 'backend/inputkomidelnew020/inputkomidelnew020';
            $data['token']          = $this->session->token;
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
    }

    public function get_sptbs_list() {
        $this->load->database();

        $sql = "
            SELECT
                trip.id, MAX(trip.sptbs) AS sptbs, MAX(trip.nomor_polisi_trek) AS nomor_polisi_trek, MAX(status) AS status,
                COUNT(tripdet.id_trip) AS jlh_anak
            FROM
                tbl_trip_020 trip
                LEFT JOIN tbl_kerani_askep askep ON trip.id_kerani_askep = askep.id
                LEFT JOIN tbl_trip_020_detail tripdet ON trip.id = tripdet.id_trip
            WHERE
                trip.tanggal = '" . $this->input->post("tanggal") . "' AND askep.token = '" . $this->session->token . "'
            GROUP BY
                trip.id
            ORDER BY
                trip.sptbs ASC
        ";
        $ds = $this->db->query($sql);
        $data = array();
        foreach($ds->result() as $ds) {
            $sql_detail = "
                SELECT
                    trip.id AS id_trip_setelah_timbang, tripdet.id AS id_trip_setelah_timbang_detail,
                    trip.timbang_pks, trip.timbang_brondolan,
                    blok.blok, blok.tahun_tanam, tripdet.hasil_kg, tripdet.hasil_kg_restan,
					det.tgl_restan
                FROM
                    tbl_trip_setelah_timbang trip
                    LEFT JOIN tbl_trip_setelah_timbang_detail tripdet ON trip.id = tripdet.id_trip_setelah_timbang
                    LEFT JOIN tbl_blok blok ON tripdet.id_blok = blok.id
                    LEFT JOIN tbl_trip_020_detail det ON tripdet.id_trip_detail = det.id
                WHERE
                    trip.id_trip = '" . $ds->id . "'
            ";
            $ds_detail = $this->db->query($sql_detail);
            $detail = array();
            foreach($ds_detail->result() as $dsd) {
                $temp = array(
                    "timbang_pks"   	=> $dsd->timbang_pks,
                    "timbang_brd"   	=> $dsd->timbang_brondolan,
                    "blok"          	=> $dsd->blok,
                    "tahun_tanam"   	=> $dsd->tahun_tanam,
                    "hasil_kg"      	=> $dsd->hasil_kg,
                    "hasil_kg_restan"   => $dsd->hasil_kg_restan,
                    "tgl_restan"   		=> $dsd->tgl_restan
                );
                array_push($detail, $temp);
            }

            $temp = array(
                "id"                => $ds->id,
                "sptbs"             => $ds->sptbs,
                "nomor_polisi_trek" => $ds->nomor_polisi_trek,
                "status"            => $ds->status,
                "timbang_pks"       => (count($detail) > 0) ? $detail[0]["timbang_pks"] : 0,
                "timbang_brd"       => (count($detail) > 0) ? $detail[0]["timbang_brd"] : 0,
                "jlh_anak"          => $ds->jlh_anak,
                "detail"            => $detail
            );
            array_push($data, $temp);
        }
        $this->db->close();

        echo json_encode($data);
    }

    public function get_detail_sptbs_list() {
        $this->load->database();

        // Tahap 1 : Pertama mencari apakah dia berada pada tahun tanam yang sama atau tidak
        $sql_tahap_1 = "
            SELECT
                blok.tahun_tanam
            FROM
                tbl_trip_020_detail tripdet
                LEFT JOIN tbl_blok blok ON tripdet.id_blok = blok.id
                LEFT JOIN tbl_afdeling afd ON blok.id_afdeling = afd.id
                LEFT JOIN tbl_kebun kbn ON afd.id_kebun = kbn.id
            WHERE
                tripdet.id_trip = '" . $this->input->post("id_trip") . "'
            GROUP BY
                blok.tahun_tanam
        ";
        $ds_tahap_1 = $this->db->query($sql_tahap_1);
        $ada_berapa_tahun_tanam = 0;
        foreach($ds_tahap_1->result() as $ds_tahap_1) {
            $ada_berapa_tahun_tanam++;
        }

        // Tahap 2 : Memproses data Kg nya
        if($ada_berapa_tahun_tanam == 1) {
            // Artinya dia cuma 1 tahun tanam alias semua berada pada tahun tanam yang sama

            // Mencari BJR nya dulu
            $sql_cari_bjr = "SELECT SUM(jumlah_janjang + jumlah_restan) AS jumlah_janjang FROM tbl_trip_020_detail WHERE id_trip = '" . $this->input->post("id_trip") . "'";
            $ds_cari_bjr = $this->db->query($sql_cari_bjr);
            $bjr = 0;
            foreach($ds_cari_bjr->result() as $ds_cari_bjr) {
                $bjr = $this->input->post("timbang_pks") / $ds_cari_bjr->jumlah_janjang;
            }

            $sql = "
                SELECT
                    tripdet.id, tripdet.id_trip, kbn.nama_kebun, afd.nama_afdeling, blok.id AS id_blok, blok.blok, blok.tahun_tanam,
                    tripdet.jumlah_janjang, tripdet.jumlah_restan, tripdet.tgl_restan, '" . $this->input->post("timbang_pks") . "' AS timbang_pks, '" . $this->input->post("timbang_brd") . "' AS timbang_brd,
                    '" . $bjr . "' AS bjr,
                    (tripdet.jumlah_janjang * " . $bjr . ") AS hasil,
                    (tripdet.jumlah_restan * " . $bjr . ") AS hasil_restan
                FROM
                    tbl_trip_020_detail tripdet
                    LEFT JOIN tbl_blok blok ON tripdet.id_blok = blok.id
                    LEFT JOIN tbl_afdeling afd ON blok.id_afdeling = afd.id
                    LEFT JOIN tbl_kebun kbn ON afd.id_kebun = kbn.id
                WHERE
                    tripdet.id_trip = '" . $this->input->post("id_trip") . "'
                ORDER BY
                    tripdet.id ASC
            ";
            $ds = $this->db->query($sql);
            $data = array();
            foreach($ds->result() as $ds) {
                $temp = array(
                    "id"                => $ds->id,
                    "id_trip"           => $ds->id_trip,
                    "nama_kebun"        => $ds->nama_kebun,
                    "nama_afdeling"     => $ds->nama_afdeling,
                    "id_blok"           => $ds->id_blok,
                    "blok"              => $ds->blok,
                    "tahun_tanam"       => $ds->tahun_tanam,
                    "jumlah_janjang"    => $ds->jumlah_janjang,
                    "jumlah_restan"    	=> $ds->jumlah_restan,
                    "tgl_restan"    	=> $ds->tgl_restan,
                    "timbang_pks"       => $ds->timbang_pks,
                    "timbang_brd"       => $ds->timbang_brd,
                    "bjr"               => $ds->bjr,
                    "hasil"             => $ds->hasil,
                    "hasil_restan"      => $ds->hasil_restan
                );
                array_push($data, $temp);
            }

            echo json_encode($data);
        } else {
            // Artinya dia berada pada tahun tanam yang nyampur-nyampur

            // Mencari BJR nya dulu
            $sql_cari_bjr = "
                SELECT
                    (MAX(prediksi.timbang_pks) / SUM(prediksi.kg_prediksi)) AS komidel_real
                FROM
                    (
                        SELECT
                            tripdet.id, kbn.nama_kebun, afd.nama_afdeling, blok.blok, blok.tahun_tanam,
                            tripdet.jumlah_janjang, tripdet.jumlah_restan, blok.prediksi_komidel,
                            ((tripdet.jumlah_janjang + tripdet.jumlah_restan) * blok.prediksi_komidel) AS kg_prediksi,
                            '" . $this->input->post("timbang_pks") . "' AS timbang_pks
                        FROM
                            tbl_trip_020_detail tripdet
                            LEFT JOIN tbl_blok blok ON tripdet.id_blok = blok.id
                            LEFT JOIN tbl_afdeling afd ON blok.id_afdeling = afd.id
                            LEFT JOIN tbl_kebun kbn ON afd.id_kebun = kbn.id
                        WHERE
                            tripdet.id_trip = '" . $this->input->post("id_trip") . "'
                        ORDER BY
                            tripdet.id ASC
                    ) prediksi
            ";

            $ds_cari_bjr = $this->db->query($sql_cari_bjr);
            $komidel_real = 0;
            foreach($ds_cari_bjr->result() as $ds_cari_bjr) {
                $komidel_real = $ds_cari_bjr->komidel_real;
            }

            $sql = "
                SELECT
                    tripdet.id, tripdet.id_trip, kbn.nama_kebun, afd.nama_afdeling, blok.id AS id_blok, blok.blok, blok.tahun_tanam,
                    tripdet.jumlah_janjang, tripdet.jumlah_restan, tripdet.tgl_restan, '" . $this->input->post("timbang_pks") . "' AS timbang_pks, '" . $this->input->post("timbang_brd") . "' AS timbang_brd,
                    (blok.prediksi_komidel * " . $komidel_real . ") AS bjr,
                    (tripdet.jumlah_janjang * (blok.prediksi_komidel * " . $komidel_real . ")) AS hasil,
                    (tripdet.jumlah_restan * (blok.prediksi_komidel * " . $komidel_real . ")) AS hasil_restan
                FROM
                    tbl_trip_020_detail tripdet
                    LEFT JOIN tbl_blok blok ON tripdet.id_blok = blok.id
                    LEFT JOIN tbl_afdeling afd ON blok.id_afdeling = afd.id
                    LEFT JOIN tbl_kebun kbn ON afd.id_kebun = kbn.id
                WHERE
                    tripdet.id_trip = '" . $this->input->post("id_trip") . "'
                ORDER BY
                    tripdet.id ASC
            ";
            $ds = $this->db->query($sql);
            $data = array();
            foreach($ds->result() as $ds) {
                $temp = array(
                    "id"                => $ds->id,
                    "id_trip"           => $ds->id_trip,
                    "nama_kebun"        => $ds->nama_kebun,
                    "nama_afdeling"     => $ds->nama_afdeling,
                    "id_blok"           => $ds->id_blok,
                    "blok"              => $ds->blok,
                    "tahun_tanam"       => $ds->tahun_tanam,
                    "jumlah_janjang"    => $ds->jumlah_janjang,
                    "jumlah_restan"    	=> $ds->jumlah_restan,
                    "tgl_restan"    	=> $ds->tgl_restan,
                    "timbang_pks"       => $ds->timbang_pks,
                    "timbang_brd"       => $ds->timbang_brd,
                    "bjr"               => $ds->bjr,
                    "hasil"             => $ds->hasil,
                    "hasil_restan"      => $ds->hasil_restan
                );
                array_push($data, $temp);
            }

            echo json_encode($data);
        }

        $this->db->close();
    }

    public function proses_dan_simpan() {
        $this->load->database();

        // Mencari data kerani askep
        $sql_kerani_askep = "SELECT id FROM tbl_kerani_askep WHERE token = '" . $this->session->token . "'";
        $ds_kerani_askep = $this->db->query($sql_kerani_askep);
        $id_kerani_askep = 0;
        foreach($ds_kerani_askep->result() as $ds_kerani_askep) {
            $id_kerani_askep = $ds_kerani_askep->id;
        }

        $data_array = $this->input->post("data");
        $data = json_decode($data_array, TRUE);

         // Kemudian ubah status tbl_trip_020 nya menjadi Y
         $sql_ubah_status = "UPDATE tbl_trip_020 SET status = 'Y' WHERE id='" . $data[0]["id_trip"] . "'";
         $this->db->query($sql_ubah_status);

        // Insert ke table tbl_trip_setelah_timbang
        $sql_insert_head = "
            INSERT INTO tbl_trip_setelah_timbang(id_trip, id_kerani_askep, timbang_pks, timbang_brondolan, creat_att)
            VALUES('" . $data[0]["id_trip"] . "', '" . $id_kerani_askep . "', '" . $data[0]["timbang_pks"] . "', '" . $data[0]["timbang_brd"] . "', NOW())
        ";
        $this->db->query($sql_insert_head);
        $id_trip_setelah_timbang = $this->db->insert_id();

        // Kemudian insert ke tbl_trip_setelah_timbang_detail
        foreach($data as $data) {
            $sql_insert_detail = "
                INSERT INTO tbl_trip_setelah_timbang_detail(
                    id_trip_setelah_timbang, id_trip_detail, id_blok,
                    bjr, hasil_kg, hasil_kg_brondolan,
                    hasil_kg_restan
                ) VALUES(
                    '" . $id_trip_setelah_timbang . "', '" . $data["id"] . "', '" . $data["id_blok"] . "',
                    '" . $data["bjr"] . "', '" . $data["hasil"] . "', '0',
                    '" . $data["hasil_restan"] . "'
                )
            ";
            $this->db->query($sql_insert_detail);
        }

        $this->db->close();
    }

    public function proses_dan_simpan_hanya_brondolan() {
        $this->load->database();

        // Mencari data kerani askep
        $sql_kerani_askep = "SELECT id FROM tbl_kerani_askep WHERE token = '" . $this->session->token . "'";
        $ds_kerani_askep = $this->db->query($sql_kerani_askep);
        $id_kerani_askep = 0;
        foreach($ds_kerani_askep->result() as $ds_kerani_askep) {
            $id_kerani_askep = $ds_kerani_askep->id;
        }

         // Kemudian ubah status tbl_trip_020 nya menjadi Y
         $sql_ubah_status = "UPDATE tbl_trip_020 SET status = 'Y' WHERE id='" . $this->input->post("id_trip") . "'";
         $this->db->query($sql_ubah_status);

        // Insert ke table tbl_trip_setelah_timbang
        $sql_insert_head = "
            INSERT INTO tbl_trip_setelah_timbang(id_trip, id_kerani_askep, timbang_pks, timbang_brondolan, creat_att)
            VALUES('" . $this->input->post("id_trip") . "', '" . $id_kerani_askep . "', '" . $this->input->post("timbang_pks") . "', '" . $this->input->post("timbang_brd") . "', NOW())
        ";
        $this->db->query($sql_insert_head);

        $this->db->close();
    }
}
