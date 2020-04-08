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
class Distrik extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_distrik','M_distrik');
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_get()
    {
        $id=$this->xss_clean($this->get('id'));
        $token=$this->xss_clean($this->get('token'));
        if (!empty($id)||!empty($token))  {
            $select =array(
                    'tbl_distrik.id',
                    'tbl_distrik.email',
                    'tbl_distrik.photo',
                    'tbl_distrik.nama_lengkap',
                    'tbl_distrik.token ');
            if($id){
                $where  = array('id'=>$id);
            }
            if($token){
                $where  = array('token'=>$token);
            }
            $distrik=$this->M_distrik->get_result($select,$where,false,false,false);
        }else{
            $query=$this->M_distrik->getAll();
            foreach($query->result() as $data){
                $distrik[]=array(
                    'id' => $data->id,
                    'nama_lengkap' => $data->nama_lengkap,
                    'email' => $data->email,
                    'photo' => 'assets/backend/img/photo/'.$data->photo,
                    'token' => $data->token,
                    
                );
            }
        }
        if ($distrik) {
            $this->response([
                'status' => TRUE,
                'data' => $distrik
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Id Not Found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }







































































}
