<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backupdb extends MY_Controller {

	public function __construct(){
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Dasboard';
			$data['view_content'] 	= 'backend/backupdb/backupdb';
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
	}
	
	public function DatabaseBackup(){
        $this->load->dbutil();
        $prefs = array('format' => 'zip','filename' => 'my_db_backup.sql');
        $backup =& $this->dbutil->backup($prefs);
        $db_name = 'backup-on-' . date("Y-m-d H:i:s") . '.zip';
        $save  = 'backup/db/' . $db_name;
        $this->load->helper('file');
        write_file($save, $backup);
        $this->load->helper('download');
        force_download($db_name, $backup);
    }
    































































}
