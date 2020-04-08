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
class Panen020 extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_keranikcs','M_keranikcs');
        $this->load->model('backend/M_trip','M_trip');
        $this->load->model('backend/M_datahasilpanen','M_datahasilpanen');
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
        
        // Mencari tanggal dan id_kerani_kcs nya
        $tanggal = "";
        $token_kerani_kcs = "";
        foreach($data as $dt) {
            $tanggal = $dt["tanggal"];
            $id_kerani_kcs = $dt["id_kerani_kcs"];
        }
        $sql_kerani_kcs = "SELECT * FROM tbl_kerani_kcs WHERE id='" . $id_kerani_kcs . "'";
        $ds_kerani_kcs = $this->db->query($sql_kerani_kcs);
        foreach($ds_kerani_kcs->result() as $res_kerani_kcs) {
            $token_kerani_kcs = $res_kerani_kcs->token;
        }

        // Kemudian menghapus semua data upload-an si kerani KCS pada tanggal itu, supaya semua perubahan bisa diupdate
        $sql_hapus = "DELETE FROM tbl_panen WHERE id_kerani_kcs='" . $token_kerani_kcs . "' AND tanggal='" . $tanggal . "'";
        $this->db->query($sql_hapus);

        // Kemudian menyimpan yang baru
        foreach($data as $dt) {
            $field= array(
                'id_kerani_askep' => $dt['id_kerani_askep'],
                'id_kerani_kcs' => $token_kerani_kcs,
                'id_kebun' => $dt['id_kebun'],
                'id_afdeling' => $dt['id_afdeling'],
                'id_pemanen' => $dt['id_pemanen'],
                'tph' => $dt['tph'],
                'blok' => $dt['blok'],
                'jmlh_panen' => $dt['jmlh_panen'],
                'jmlh_brondolan' => $dt['jmlh_brondolan'],
                'id_alat' => $dt['id_alat'],
                'tanggal' => $dt['tanggal'],
                'status' => $dt['status'],
                'approve' => "N",
                'kode' => "N",
                'device' => "ANDROID",
            );
            $data=$this->M_datahasilpanen->insert(self::xss($field));
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
