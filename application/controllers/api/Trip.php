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
class Trip extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_keranikcs','M_keranikcs');
        $this->load->model('backend/M_trip','M_trip');
        $this->load->library('session');
        
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_post()
    {
        $data = self::validations(array(
                array('field'=>'no_sptbs','label'=>'no_sptbs','rules'=>'required'),
                array('field'=>'id_kerani_askep','label'=>'id_kerani_askep','rules'=>'required'),
                array('field'=>'id_kerani_kcs','label'=>'id_kerani_kcs','rules'=>'required'),
                array('field'=>'id_kebun','label'=>'id_kebun','rules'=>'required'),
                array('field'=>'id_afdeling','label'=>'id_afdeling','rules'=>'required'),
                array('field'=>'id_blok_1','label'=>'Blok','rules'=>'required'),
                array('field'=>'id_blok_2','label'=>'Blok','rules'=>'required'),
                array('field'=>'id_blok_3','label_3'=>'Blok','rules'=>'required'),
                array('field'=>'jumlah_janjang_1','label'=>'Jumlah Janjang','rules'=>'required'),
                array('field'=>'jumlah_janjang_2','label'=>'Jumlah Janjang','rules'=>'required'),
                array('field'=>'jumlah_janjang_3','label'=>'Jumlah Janjang','rules'=>'required'),
                array('field'=>'jumlah_brondolan_1','label'=>'J.Brondolan No 1','rules'=>'required'),
                array('field'=>'jumlah_brondolan_2','label'=>'J.Brondolan No 2','rules'=>'required'),
                array('field'=>'jumlah_brondolan_3','label'=>'J.Brondolan No 3','rules'=>'required'),
                array('field'=>'nomor_polisi_trek','label'=>'Nomor Polisi Trek','rules'=>'required'),
                array('field'=>'tanggal','label'=>'tanggal','rules'=>'required'),
                array('field'=>'status','label'=>'status','rules'=>'required'),
                array('field'=>'device','label'=>'device','rules'=>'required'),
                
        ));

        if($data === true){
            $field= array(
                'sptbs'=>$this->input->post('no_sptbs'),
                'id_kerani_askep'=>$this->input->post('id_kerani_askep'),
                'id_kerani_kcs'=>$this->input->post('id_kerani_kcs'),
                'id_kebun'=>$this->input->post('id_kebun'),
                'id_afdeling'=>$this->input->post('id_afdeling'),
                'id_blok_1'=>$this->input->post('id_blok_1'),
                'id_blok_2'=>$this->input->post('id_blok_2'),
                'id_blok_3'=>$this->input->post('id_blok_3'),
                'jumlah_janjang_1'=>$this->input->post('jumlah_janjang_1'),
                'jumlah_janjang_2'=>$this->input->post('jumlah_janjang_2'),
                'jumlah_janjang_3'=>$this->input->post('jumlah_janjang_3'),
                'jumlah_taksir_brondolan_1'=>$this->input->post('jumlah_brondolan_1'),
                'jumlah_taksir_brondolan_2'=>$this->input->post('jumlah_brondolan_2'),
                'jumlah_taksir_brondolan_3'=>$this->input->post('jumlah_brondolan_3'),
                'nomor_polisi_trek'=>$this->input->post('nomor_polisi_trek'),
                'tanggal'=> $this->input->post('tanggal'),
                'status'=> $this->input->post('status'),
                'device'=>$this->input->post('device'),
            );
            $where=array(
                'sptbs'=>$this->input->post('no_sptbs')
            );
            if($this->M_trip->validasiData($where)){
                $this->response([
                    'status' => FALSE,
                    'message' => 'Data Sudah Ada'
                ], REST_Controller::HTTP_NOT_FOUND);
            }else{

                $blok = array($field['id_blok_2'], $field['id_blok_2']);
                if (in_array($field['id_blok_1'], $blok))
                {
                    $this->response([
                    'status' => FALSE,
                    'message' => 'Blok Tidak Boleh Sama'
                ], REST_Controller::HTTP_NOT_FOUND);
                }
                else
                {
                    $data=$this->M_trip->insert(self::xss($field));
                    $this->response([
                        'status' => TRUE,
                        'data' => 'Succes Add'
                    ], REST_Controller::HTTP_OK);
                }
                
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
