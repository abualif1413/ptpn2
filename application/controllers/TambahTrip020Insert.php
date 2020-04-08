<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TambahTrip020Insert extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_trip','M_trip');
        $this->load->model('backend/M_keranikcs','M_keranikcs');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
            // Mendapatkan data hasil panen. Yang muncul hanya afdeling yang dipanen
            $this->load->database();
            $sql_panen = "
                SELECT
                    tobetrip.*
                FROM
                        (
                                SELECT
                                    blok.id, MAX(blok.blok) AS blok, SUM(jmlh_panen) AS jmlh_panen, COALESCE(MAX(sudah_angkut.jmlh_janjang), 0) AS diangkut,
                                    (SUM(jmlh_panen) - COALESCE(MAX(sudah_angkut.jmlh_janjang), 0)) AS sisa
                                FROM
                                    tbl_panen panen
                                    LEFT JOIN tbl_blok blok ON panen.blok = blok.id
                                    LEFT JOIN tbl_kerani_kcs kcs ON panen.id_afdeling = kcs.id_afdeling
                                    LEFT JOIN (
                                            SELECT
                                                    trip.tanggal, tripdet.id_blok, SUM(tripdet.jumlah_janjang) jmlh_janjang
                                            FROM
                                                    tbl_trip_020_detail tripdet
                                                    INNER JOIN tbl_trip_020 trip ON tripdet.id_trip = trip.id
                                            GROUP BY
                                                    trip.tanggal, tripdet.id_blok
                                    ) sudah_angkut ON blok.id = sudah_angkut.id_blok AND sudah_angkut.tanggal = '" . $this->input->get("tanggal") . "'
                                WHERE
                                    panen.tanggal = '" . $this->input->get("tanggal") . "'
                                    AND kcs.token = '" . $this->session->userdata("token") . "'
                                GROUP BY
                                    blok.id
                                ORDER BY
                                                blok.blok ASC
                        ) tobetrip
                WHERE
                    tobetrip.sisa > 0
            ";
            $ds_result = $this->db->query($sql_panen);
            $result = array();
            foreach($ds_result->result() as $ds) {
                $temp = array(
                    "blok" => $ds->blok,
                    "jmlh_panen" => $ds->sisa,
                    "id" => $ds->id
                );
                array_push($result, $temp);
            }
            $this->db->close();

            $data['title']  		= 'Halaman Trip';
            $data['view_content'] 	= 'backend/tambahtrip020/tambahtrip020insert';
            $data['tanggal']        = $this->input->get("tanggal");
            //$data['token']        = $this->session->userdata('token');
            $data["result"]         = $result;
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }

    }

    public function GoInsert() {
        if ($this->session->userdata('email')) {
            $this->load->database();
            // Mencari data pendukung lainnya
            $id_kerani_askep = 0;
            $id_kerani_kcs = 0;
            $id_kebun = 0;
            $id_afdeling = 0;

            $sql_kcs = "SELECT * FROM tbl_kerani_kcs WHERE token='" . $this->session->userdata("token") . "'";
            $res_kcs = $this->db->query($sql_kcs);
            foreach($res_kcs->result() as $kcs) {
                $id_kerani_askep = $kcs->id_kerani_askep;
                $id_kerani_kcs = $kcs->token;
                $id_kebun = $kcs->id_kebun;
                $id_afdeling = $kcs->id_afdeling;
            }

            $no_sptbs = $this->input->post("no_sptbs");
            $no_polisi = $this->input->post("no_polisi");
            $tanggal = $this->input->post("tanggal");
            $jumlah_brondolan = $this->input->post("brondolan");
            $detail = json_decode($this->input->post("detail"), TRUE);

            // Menyimpan head nya
            $sql_head = "
                INSERT INTO tbl_trip_020(
                    sptbs,
                    id_kerani_askep, id_kerani_kcs,
                    id_kebun, id_afdeling,
                    jumlah_brondolan,
                    nomor_polisi_trek, tanggal,
                    status, device, creat_att
                ) VALUES(
                    '" . $no_sptbs . "',
                    '" . $id_kerani_askep . "', '" . $id_kerani_kcs . "',
                    '" . $id_kebun . "', '" . $id_afdeling . "',
                    '" . $jumlah_brondolan . "',
                    '" . $no_polisi . "', '" . $tanggal . "',
                    'N', 'WEB', NOW()
                )
            ";
            $this->db->query($sql_head);
            $id_nya = $this->db->insert_id();
            foreach($detail as $det) {
                $sql = "
                    INSERT INTO tbl_trip_020_detail(
                        id_trip, id_blok, jumlah_janjang, jumlah_restan
                    ) VALUES(
                        '" . $id_nya . "', '" . $det["id_blok"] . "', '" . $det["jmlh_janjang"] . "', '" . $det["jmlh_restan"] . "'
                    )
                ";
                $this->db->query($sql);
            }
            $this->db->close();

            redirect("TambahTrip020?tanggal=" . $tanggal);
        }else{
            redirect('Authadmin');
        }
    }
}
