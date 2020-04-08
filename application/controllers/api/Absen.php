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
class Absen extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_absen','M_absen');
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_post()
    {
            $data = self::validations(array(
                array('field'=>'id_mandor','label'=>'Id Mandor Not Found','rules'=>'required'),
                array('field'=>'id_absen','label'=>'Id Not Found','rules'=>'required'),
                array('field'=>'kehadiran','label'=>'Kehadiran','rules'=>'required'),
                array('field'=>'tanggal','label'=>'Tanggal','rules'=>'required'),
                array('field'=>'jam','label'=>'Jam','rules'=>'required'),
                array('field'=>'device','label'=>'Device','rules'=>'required'),
            ));
            if($data === true){
                $select_pemanen = array("tbl_pemanen.nama_pemanen","tbl_pemanen.id_mandor");
                $where_pemanen = array('tbl_pemanen.barcode'=>$this->input->post('id_absen'));
                $result_pemanen=$this->M_DataPemanen->get_result($select_pemanen,$where_pemanen,false,false,false);
                foreach ($result_pemanen as $key_pemanen);
                $nama_pemanen=$key_pemanen->nama_pemanen;
                $id_mandor=$key_pemanen->id_mandor;
                $field= array(
                    'id_mandor' => $this->input->post('id_mandor'),
                    'id_absen'=>$this->input->post('id_absen'),
                    'kehadiran'=>$this->input->post('kehadiran'),
                    'tanggal'=>$this->input->post('tanggal'),
                    'jam'=>$this->input->post('jam'),
                    'device'=>$this->input->post('device'),
                );
                
                $pesan='<b style="color:red;">'.$nama_pemanen.'</b> Sudah Absen';
                $pesan2='<b style="color:green;">'.$nama_pemanen.'</b> Berhasil Absen';
                $where=array(
                    'id_mandor' => $this->input->post('id_mandor'),
                    'id_absen'=>$this->input->post('id_absen'),
                    'kehadiran'=>$this->input->post('kehadiran'),
                    'tanggal'=>$this->input->post('tanggal'),
                    'device'=>$this->input->post('device'),
                );
                if($this->M_absen->validasiData($where)){
                    $this->response([
                        'status' => FALSE,
                        'message' => $pesan
                    ], REST_Controller::HTTP_NOT_FOUND);
                }else{
                    $data=$this->M_absen->insert(self::xss($field));
                    $this->response([
                        'status' => TRUE,
                        'message' => $pesan2
                    ], REST_Controller::HTTP_OK);
                }
            }  
        
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
