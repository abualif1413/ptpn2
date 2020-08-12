<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataDenda extends MY_Controller {

    public function __construct(){
        parent::__construct();
        
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
    }

	public function index()
	{   
        
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Data Denda';
            $data['view_content'] 	= 'backend/datadenda/datadenda';
            $data['token_mandor']   = $this->session->token;
            $data["dataDenda"]      = array();
            $data["tanggal"]        = $this->input->get("tanggal");

            $this->load->database();
            $sql = "
                SELECT
                    denda.id, pemanen.nama_pemanen, kriteria.kriteria,
                    denda.qty, kriteria.satuan, kriteria.denda AS nilai,
                    denda.mandor_panen, denda.kapv_controll, denda.mandor_1
                FROM
                    tbl_denda denda
                    LEFT JOIN tbl_mandor mandor ON denda.id_mandor = mandor.id
                    LEFT JOIN tbl_pemanen pemanen ON denda.id_pemanen = pemanen.id
                    LEFT JOIN tbl_kriteria_denda kriteria ON denda.id_kriteria_denda = kriteria.id
                WHERE
                    mandor.token = '" . $data['token_mandor'] . "' AND denda.tanggal = '" . $this->input->get("tanggal") . "'
                ORDER BY
                    pemanen.nama_pemanen ASC, denda.id ASC
            ";
            $ds = $this->db->query($sql);
            $dataDenda = array();
            foreach ($ds->result() as $res) {
                $temp = (array)$res;

                $yangKena = array("Pemanen");
                if($temp["mandor_panen"] == 1)
                    array_push($yangKena, "Mandor panen");
                if($temp["kapv_controll"] == 1)
                    array_push($yangKena, "Kapv controll");
                if($temp["mandor_1"] == 1)
                    array_push($yangKena, "Mandor 1");
                
                $temp["yangKena"] = implode(", ", $yangKena);

                array_push($dataDenda, $temp);
            }
            $this->db->close();
            $data["dataDenda"] = $dataDenda;

            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }


    




























































}
