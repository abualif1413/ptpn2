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
class Keraniaskep extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_keraniaskep','M_keraniaskep');
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_get()
    {
        $id=$this->xss_clean($this->get('id'));
        $token=$this->xss_clean($this->get('token'));
        $id_kebun=$this->xss_clean($this->get('id_kebun'));
        if (!empty($id)||!empty($token)||!empty($id_kebun))  {
            $select =array('tbl_kerani_askep.*');
            if($id){
                $where  = array('id'=>$id);
            }
            if($token){
                $where  = array('token'=>$token);
            }
            if($id_kebun){
                $where  = array('id_kebun'=>$id_kebun);
            }
            $askep=$this->M_keraniaskep->get_result($select,$where,false,false,false);
        }else{
            $query=$this->M_keraniaskep->getAll();
            foreach($query->result() as $data){
                $askep[]=array(
                    'id' => $data->id,
                    'email' => $data->email,
                    'nama_lengkap' => $data->nama_lengkap,
                    'id_kebun'=> $data->id_kebun,
                    'id_jabatan' => $data->id_jabatan,
                    'photo' => 'assets/backend/img/photo/'.$data->photo,
                    'token' => $data->token,
                );
            }
        }
        if ($askep) {
            $this->response([
                'status' => TRUE,
                'data' => $askep
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Id Not Found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }







































































}
