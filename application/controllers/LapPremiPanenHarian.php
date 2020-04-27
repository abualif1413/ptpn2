<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(0);
require_once 'dompdf/lib/html5lib/Parser.php';
require_once 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'dompdf/src/Autoloader.php';

Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

class LapPremiPanenHarian extends MY_Controller {

	public function __construct(){
        parent::__construct();
		
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
        	ob_start();
			
			$this->load->database();
			$ds_data = $this->db->query("
				SELECT
					pemanen.id AS id_pemanen, mandor.nama_lengkap AS nama_mandor, pemanen.nama_pemanen, blok.blok,
					hasil.bt, hasil.kg_tbs, hasil.kg_brd,
					hasil.tbs_p1, hasil.tbs_p2, hasil.tbs_p3, hasil.tbs_p4, (hasil.tbs_p1 + hasil.tbs_p2 + hasil.tbs_p3 + hasil.tbs_p4) AS hasil_tbs_p,
					hasil.brd_p,
					(hasil.tbs_p1 + hasil.tbs_p2 + hasil.tbs_p3 + hasil.tbs_p4 + hasil.brd_p) AS total_premi
				FROM
					tbl_pemanen pemanen
					INNER JOIN tbl_mandor mandor ON pemanen.id_mandor = mandor.token
					LEFT JOIN tbl_hasil_kg_per_pemanen hasil ON pemanen.id = hasil.id_pemanen AND tanggal = '" . $this->input->get("tanggal") . "'
					LEFT JOIN tbl_blok blok ON hasil.id_blok = blok.id
				WHERE
					mandor.id = '" . $this->input->get("id_mandor") . "'
				ORDER BY
					pemanen.nama_pemanen ASC
			");
			$nama_mandor = "";
			$data_show = array();
			foreach ($ds_data->result() as $ds) {
				$arr_ds = (array)$ds;
				array_push($data_show, $arr_ds);
				$nama_mandor = $arr_ds["nama_mandor"];
			}
			$this->db->close();
			
			$data['title']  		= 'Prestasi panen per pemanen';
			$data["data"]			= $data_show;
			$data["tanggal"]		= parent::format_sqltoindo($this->input->get("tanggal"));
			$data["nama_mandor"]	= $nama_mandor;
			$this->load->view('backend/lappremipanenharian/lappremipanenharian',$data);
			
			$output = ob_get_contents();
    		ob_end_clean();
    		
    		//echo $output;
    		
		    // instantiate and use the dompdf class
			$dompdf = new Dompdf();
			$dompdf->loadHtml($output);
			
			// (Optional) Setup the paper size and orientation
			//$customPaper = array(0,0,,360);
			//$dompdf->setPaper($customPaper, 'landscape');
			
			// Render the HTML as PDF
			$dompdf->render();
			
			// Output the generated PDF to Browser
		    $dompdf->stream("laporan.pdf", array("Attachment" => false));
		    
		    //echo $output;
        }else{
            redirect('Authadmin');
        }
		
	}
}
