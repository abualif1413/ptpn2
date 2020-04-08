<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TambahTrip020 extends MY_Controller {

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
            $this->load->database();
            $sql = "
                SELECT
                	trip.id, trip.sptbs, trip.nomor_polisi_trek,
                	COALESCE(tripdet.id, 0) AS id_detail, blok.blok, blok.tahun_tanam, tripdet.jumlah_restan, tripdet.tgl_restan, tripdet.jumlah_janjang, trip.jumlah_brondolan
                FROM
                	tbl_trip_020 trip
                	LEFT JOIN tbl_trip_020_detail tripdet ON trip.id = tripdet.id_trip
                	LEFT JOIN tbl_blok blok ON tripdet.id_blok = blok.id
                	LEFT JOIN tbl_kerani_kcs kcs ON trip.id_kerani_kcs = kcs.id
                WHERE
                    trip.tanggal = '" . $this->input->get("tanggal") . "'
                    AND kcs.token = '" . $this->session->token . "'
                ORDER BY
                    trip.sptbs ASC, trip.id ASC, tripdet.id ASC
            ";
            $ds = $this->db->query($sql);
            $dataTampil = array();
            foreach($ds->result() as $res) {
                $temp = array(
                    "id" => $res->id,
                    "id_detail" => $res->id_detail,
                    "sptbs" => $res->sptbs,
                    "nomor_polisi_trek" => $res->nomor_polisi_trek,
                    "blok" => $res->blok,
                    "tahun_tanam" => $res->tahun_tanam,
                    "jumlah_restan" => $res->jumlah_restan,
                    "tgl_restan" => $res->tgl_restan,
                    "jumlah_janjang" => $res->jumlah_janjang,
                    "jumlah_brondolan" => $res->jumlah_brondolan
                );
                array_push($dataTampil, $temp);
            }
            $this->db->close();
            $data['title']  		= 'Halaman Trip';
            $data['view_content'] 	= 'backend/tambahtrip020/tambahtrip020';
            $data['tanggal']        = $this->input->get("tanggal");
            $data['dataTampil']     = $dataTampil;
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }

    }
}
