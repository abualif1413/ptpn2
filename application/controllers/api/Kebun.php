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
class Kebun extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_kebun','M_kebun');
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_get()
    {
        $id=$this->xss_clean($this->get('id'));
        $id_distrik=$this->xss_clean($this->get('id_distrik'));
        if (!empty($id)||!empty($id_distrik))  {
            $select =array(
                    'tbl_kebun.id',
                    'tbl_kebun.nama_kebun',
                    'tbl_kebun.id_distrik');
            if($id){
                $where  = array('id'=>$id);
            }
            if($id_distrik){
                $where  = array('id_distrik'=>$id_distrik);
            }
            $kebun=$this->M_kebun->get_result($select,$where,false,false,false);
        }else{
            $query=$this->M_kebun->getAll();
            foreach($query->result() as $data){
                $kebun[]=array(
                    'id' => $data->id,
                    'nama_kebun' => $data->nama_kebun,
                    'id_distrik' => $data->id_distrik,
                    
                );
            }
        }
        if ($kebun) {
            $this->response([
                'status' => TRUE,
                'data' => $kebun
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Id Not Found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }







































































}
