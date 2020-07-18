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
class KriteriaDenda extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        // $this->load->model('backend/M_blok','M_blok');
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_get()
    {
        $this->load->database();
        $ds_denda = $this->db->query("SELECT * FROM tbl_kriteria_denda ORDER BY id ASC");
        $kriteria = array();
        foreach($ds_denda->result() as $ds) {
            array_push($kriteria, $ds);
        }
        $this->db->close();

        $this->response([
            'status' => TRUE,
            'data' => $kriteria
        ], REST_Controller::HTTP_OK);
    }







































































}
