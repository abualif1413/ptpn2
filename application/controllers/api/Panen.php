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
class Panen extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_datahasilpanen','M_datahasilpanen');
        
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_post()
    {
            $data = self::validations(array(
                array('field'=>'id_kerani_askep','label'=>'id_kerani_askep','rules'=>'required'),
                array('field'=>'id_kerani_kcs','label'=>'id_kerani_kcs','rules'=>'required'),
                array('field'=>'id_kebun','label'=>'id_kebun','rules'=>'required'),
                array('field'=>'id_afdeling','label'=>'id_afdeling','rules'=>'required'),
                array('field'=>'id_pemanen','label'=>'id_pemanen','rules'=>'required'),
                array('field'=>'tph','label'=>'tph','rules'=>'required'),
                array('field'=>'blok','label'=>'blok','rules'=>'required'),
                array('field'=>'jmlh_panen','label'=>'jmlh_panen','rules'=>'required'),
                array('field'=>'jmlh_brondolan','label'=>'jmlh_brondolan','rules'=>'required'),
                array('field'=>'id_alat','label'=>'id_alat','rules'=>'required'),
                array('field'=>'tanggal','label'=>'tanggal','rules'=>'required'),
                array('field'=>'status','label'=>'status','rules'=>'required'),
                array('field'=>'approve','label'=>'approve','rules'=>'required'),
                array('field'=>'kode','label'=>'kode','rules'=>'required'),
                array('field'=>'device','label'=>'device','rules'=>'required'),
            ));
            if($data === true){
                $field= array(
                    'id_kerani_askep' => $this->input->post('id_kerani_askep'),
                    'id_kerani_kcs' => $this->input->post('id_kerani_kcs'),
                    'id_kebun' => $this->input->post('id_kebun'),
                    'id_afdeling' => $this->input->post('id_afdeling'),
                    'id_pemanen' => $this->input->post('id_pemanen'),
                    'tph' => $this->input->post('tph'),
                    'blok' => $this->input->post('blok'),
                    'jmlh_panen' => $this->input->post('jmlh_panen'),
                    'jmlh_brondolan' => $this->input->post('jmlh_brondolan'),
                    'id_alat' => $this->input->post('id_alat'),
                    'tanggal' => $this->input->post('tanggal'),
                    'status' => $this->input->post('status'),
                    'approve' => $this->input->post('approve'),
                    'kode' => $this->input->post('kode'),
                    'device' => $this->input->post('device'),
                );
                
                $where=array(
                    'id_pemanen'=>$this->input->post('id_pemanen'),
                    'tanggal'=>$this->input->post('tanggal'),
                );
                if($this->M_datahasilpanen->validasiData($where)){
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Failed'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }else{
                    $data=$this->M_datahasilpanen->insert(self::xss($field));
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Succes'
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
