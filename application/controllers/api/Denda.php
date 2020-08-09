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
class Denda extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_denda','M_denda');
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_post()
    {
        $tanggal = "";
        $id_mandor = 0;
        $data = json_decode($this->input->post('data'), TRUE);
        foreach($data as $dt) {
            $tanggal = $dt["tanggal"];
            $id_mandor = $dt["id_mandor"];
        }

        $this->load->database();
        // Kemudian menghapus semua data upload-an si mandor pada tanggal itu, supaya semua perubahan bisa diupdate
        $sql_hapus = "DELETE FROM tbl_denda WHERE id_mandor='" . $id_mandor . "' AND tanggal='" . $tanggal . "'";
        $this->db->query($sql_hapus);

        // Kemudian insertkan datanya
        foreach($data as $dt) {
            $field = array(
                "tanggal" => $dt["tanggal"],
                "id_pemanen" => $dt["id_pemanen"],
                "id_mandor" => $dt["id_mandor"],
                "id_kriteria_denda" => $dt["id_kriteria_denda"],
                "qty" => $dt["qty"],
                "mandor_panen" => $dt["mandor_panen"],
                "mandor_1" => $dt["mandor_1"],
                "kapv_controll" => $dt["kapv_controll"]
            );
            $data=$this->M_denda->insert(self::xss($field));
        }

        $this->db->close();

        $this->response([
            'status' => TRUE,
            'data' => "Sukses upload"
        ], REST_Controller::HTTP_OK);
    }

    public function xss($data)
	{
		return $this->security->xss_clean($data);
	}







































































}
