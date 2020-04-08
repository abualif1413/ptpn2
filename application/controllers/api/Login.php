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
class Login extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('backend/M_admin','M_admin');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function xss_clean($data)
    {
        return $this->security->xss_clean($data);
    }

    public function index_post()
    {
        $this->form_validation->set_rules('email', 'email / Username *', 'required');
		$this->form_validation->set_rules('password', 'password *','required');
        if ($this->form_validation->run() == FALSE){
            $output['error']= validation_errors('<span style="color:red;">','</span><br>');
            $this->response([
                'status' => FALSE,
                'message' => $output
            ], REST_Controller::HTTP_NOT_FOUND);
        }else{
        	// $output = array('error' => false);
			$email = $this->security->xss_clean($this->input->post('email'));
			$password = $this->security->xss_clean($this->input->post('password'));
			$select=array(
				'tbl_admin.email',
				'tbl_admin.password',
				'tbl_admin.role',
				'tbl_admin.id',
				'tbl_admin.nama_lengkap',
				'tbl_admin.photo',
				'tbl_admin.token',
			);
			$where=array('email'=>$email);
			$or_where=array('email'=>$email,'nama_lengkap'=>$email);
			$data=$this->M_admin->get_where($select,$where,$or_where,false,false,false);
			if(password_verify($password,$data['password']))
			{	
				$Select=array('tbl_admin.status');
				$Where=array('status'=>'Y','id'=> $data['id']);
				if ($this->M_admin->checkRows($Select,$Where,false)){
					$this->session->set_userdata('email',$email);
					$this->session->set_userdata('role',$data['role']);
					$this->session->set_userdata('token',$data['token']);
                    $this->session->set_userdata('user',$data);
                    $this->response([
                        'status' => TRUE,
                        'data' => $data
                    ], REST_Controller::HTTP_OK);

				}else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Akun Kamu Belum Aktif'
                    ], REST_Controller::HTTP_NOT_FOUND);
				}
			}
			else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'Login Invalid. User not found'
                ], REST_Controller::HTTP_NOT_FOUND);
			}
        }
    }







































































}
