<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
// require APPPATH . 'libraries/CreatorJwt.php';
require APPPATH . '/libraries/JWT.php';
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
class Afdeling extends REST_Controller {
    private $secretkey = '1234567890qwertyuiopmnbvcxzasdfghjkl'; //ubah dengan kode rahasia
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_afdeling','M_afdeling');
        // $this->objOfJwt = new CreatorJwt();
        header('Content-Type: application/json');
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_get()
    {
        $id=$this->xss_clean($this->get('id'));
        $id_kebun=$this->xss_clean($this->get('id_kebun'));
        if (!empty($id)||!empty($id_kebun))  {
            $select =array(
                    'tbl_afdeling.id',
                    'tbl_afdeling.id_kebun',
                    'tbl_afdeling.nama_afdeling');
            if($id){
                $where  = array('id'=>$id);
            }
            if($id_kebun){
                $where  = array('id_kebun'=>$id_kebun);
            }
            $afdeling=$this->M_afdeling->get_result($select,$where,false,false,false);
        }else{
            $query=$this->M_afdeling->getAll();
            foreach($query->result() as $data){
                $afdeling[]=array(
                    'id' => $data->id,
                    'id_kebun' => $data->id_kebun,
                    'nama_afdeling' => $data->nama_afdeling
                );
            }
        }
        if ($afdeling) {
            $this->response([
                'status' => TRUE,
                'data' => $afdeling
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Id Not Found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function LoginToken_post()
    {
        $date = new DateTime();
        $tokenData['uniqueId'] = 'aaaaaaaa';
        $tokenData['role'] = 'imam wasmawi';
        $tokenData['iat'] = $date->getTimestamp();
        $tokenData['exp'] = $date->getTimestamp() + 60*60*5; //To here is to generate token
        $jwtToken=JWT::encode($tokenData,$this->secretkey);
        $this->response(['Token' => $jwtToken], REST_Controller::HTTP_OK);
    }
     

































































}
