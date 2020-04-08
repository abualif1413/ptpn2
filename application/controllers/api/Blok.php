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
class Blok extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_blok','M_blok');
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_get()
    {
        $id=$this->xss_clean($this->get('id'));
        $id_kebun=$this->xss_clean($this->get('id_kebun'));
        $id_afdeling=$this->xss_clean($this->get('id_afdeling'));
        if (!empty($id)||!empty($id_kebun)||!empty($id_afdeling))  {
            $select =array(
                    'tbl_blok.*',
                );
            if($id){
                $where  = array('id'=>$id);
            }
            if($id_kebun){
                $where  = array('id_kebun'=>$id_kebun);
            }
            if($id_afdeling){
                $where  = array('id_afdeling'=>$id_afdeling);
            }
            $blok=$this->M_blok->get_result($select,$where,false,false,false);
        }else{
            $query=$this->M_blok->getAll();
            foreach($query->result() as $data){
                $blok[]=array(
                    'id' => $data->id,
                    'id_kebun' => $data->id_kebun,
                    'id_afdeling' => $data->id_afdeling,
                    'blok' => $data->blok,
                    'bt' => $data->bt,
                    'p0' => $data->p0,
                    'p1' => $data->p1,
                    'p2' => $data->p2,
                    'p3' => $data->p3,
                    'rp_p0' => $data->rp_p0,
                    'rp_p1' => $data->rp_p1,
                    'rp_p2' => $data->rp_p2,
                    'rp_p3' => $data->rp_p3,
                    'tahun_tanam' => $data->tahun_tanam,
                    'prediksi_komidel' => $data->prediksi_komidel,
                    'status' => $data->status,
                    'keterangan' => $data->keterangan,
                );
            }
        }
        if ($blok) {
            $this->response([
                'status' => TRUE,
                'data' => $blok
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Id Not Found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }







































































}
