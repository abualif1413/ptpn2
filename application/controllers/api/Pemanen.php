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
class Pemanen extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_get()
    {
        $id=$this->xss_clean($this->get('id'));
        $id_mandor=$this->xss_clean($this->get('id_mandor'));
        $id_kerani_askep=$this->xss_clean($this->get('id_kerani_askep'));
        $id_kerani_kcs=$this->xss_clean($this->get('id_kerani_kcs'));
        $id_kebun=$this->xss_clean($this->get('id_kebun'));
        $id_afdeling=$this->xss_clean($this->get('id_afdeling'));
        if (!empty($id)||!empty($id_mandor)||!empty($id_kerani_askep)||!empty($id_kerani_kcs)||!empty($id_kebun)||!empty($id_afdeling))  {
            $select =array('tbl_kerani_kcs.*');
            if($id){
                $where  = array('id'=>$id);
            }
            if($id_mandor){
                $where  = array('id_mandor'=>$id_mandor);
            }
            if($id_kerani_askep){
                $where  = array('id_kerani_askep'=>$id_kerani_askep);
            }
            if($id_kerani_kcs){
                $where  = array('id_kerani_kcs'=>$id_kerani_kcs);
            }
            if($id_kebun){
                $where  = array('id_kebun'=>$id_kebun);
            }
            if($id_afdeling){
                $where  = array('id_afdeling'=>$id_afdeling);
            }
            $pemanen=$this->M_DataPemanen->get_result($select,$where,false,false,false);
        }else{
            $query=$this->M_DataPemanen->getAll();
            foreach($query->result() as $data){
                $pemanen[]=array(
                    'id' => $data->id,
                    'nama_pemanen' => $data->nama_pemanen,
                    'id_kerani_askep' => $data->id_kerani_askep,
                    'id_kerani_kcs'  => $data->id_kerani_kcs,
                    'id_mandor'=> $data->id_mandor,
                    'id_kebun'=> $data->id_kebun,
                    'id_afdeling'=> $data->id_afdeling,
                    'barcode' => $data->barcode,
                    'img_barcode'=> 'assets/backend/qr/'.$data->img_barcode,
                    'photo' => 'assets/backend/img/photo/'.$data->photo,
                    'keterangan' => $data->keterangan,
                );
            }
        }
        if ($pemanen) {
            $this->response([
                'status' => TRUE,
                'data' => $pemanen
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Id Not Found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }







































































}
