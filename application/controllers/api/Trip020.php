<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Trip020 extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_keranikcs','M_keranikcs');
        $this->load->model('backend/M_trip','M_trip');
        $this->load->library('session');
        
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_post()
    {
        $data_json = $this->input->post('data');
        $data = json_decode($data_json, TRUE);

        $this->load->database();
        // Mencari data pendukung lainnya
        $id_kerani_askep = 0;
        $id_kerani_kcs = 0;
        $id_kebun = 0;
        $id_afdeling = 0;

        $no_sptbs = $data["sptbs"];
        $no_polisi = $data["nomor_polisi_trek"];
        $tanggal = $data["tanggal"];
        $jumlah_brondolan = $data["jumlah_brondolan"];
        $id_kerani_askep = $data["id_kerani_askep"];
        $id_kerani_kcs = $data["id_kerani_kcs"];
        $id_kebun = $data["id_kebun"];
        $id_afdeling = $data["id_afdeling"];
        $detail = $data["details"];
        
        // Menghapus jika dia sudah pernah ada
        $sql_hapus = "DELETE FROM tbl_trip_020 WHERE sptbs='" . $no_sptbs . "' AND id_kerani_kcs='" . $id_kerani_kcs . "' AND tanggal = '" . $tanggal . "'";
        $this->db->query($sql_hapus);

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
                'N', 'ANDROID', NOW()
            )
        ";
        $this->db->query($sql_head);
        $id_nya = $this->db->insert_id();
        foreach($detail as $det) {
            $sql = "
                INSERT INTO tbl_trip_020_detail(
                    id_trip, id_blok, jumlah_janjang, jumlah_restan
                ) VALUES(
                    '" . $id_nya . "', '" . $det["id_blok"] . "', '" . $det["jumlah_janjang"] . "', '" . $det["jumlah_restan"] . "'
                )
            ";
            $this->db->query($sql);
        }
        $this->db->close();

        $this->response([
            'status' => TRUE,
            'data' => "Sukses upload"
        ], REST_Controller::HTTP_OK);
    }

    public function validations($validations)
	{
		$this->form_validation->set_rules($validations);
		if ($this->form_validation->run() == FALSE){
            $data= validation_errors('<span style="color:red;">','</span><br>');
            $this->response([
                'status' => FALSE,
                'message' => $data
            ], REST_Controller::HTTP_NOT_FOUND);
			return $data;
		}else {
			return true;
		}
    }
    
    public function xss($data)
	{
		return $this->security->xss_clean($data);
	}







































































}
