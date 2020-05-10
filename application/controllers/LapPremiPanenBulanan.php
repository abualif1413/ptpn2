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

class LapPremiPanenBulanan extends MY_Controller {

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
			
			$tgl_pertama = $this->input->get("tahun") . "-" . $this->input->get("bulan") . "-01";
			$ds_tanggal = $this->db->query("SELECT tanggal FROM rentang_tanggal WHERE tanggal BETWEEN '" . $tgl_pertama . "' AND LAST_DAY('" . $tgl_pertama . "')");
			//$ds_tanggal = $this->db->query("SELECT tanggal FROM rentang_tanggal WHERE tanggal BETWEEN '" . $tgl_pertama . "' AND '2020-04-05'");
			$tanggal_format = array();
			$tanggal = array();
			foreach ($ds_tanggal->result() as $ds) {
				array_push($tanggal_format, parent::format_sqltoindo($ds->tanggal));
				array_push($tanggal, $ds->tanggal);
			}
			
			$mandor = "";
			$data_hasil = array();
			$ds_pemanen = $this->db->query("
				SELECT
					pemanen.id AS id_pemanen, mandor.nama_lengkap AS nama_mandor, pemanen.nama_pemanen
				FROM
					tbl_pemanen pemanen
					INNER JOIN tbl_mandor mandor ON pemanen.id_mandor = mandor.token
				WHERE
					mandor.id = '" . $this->input->get("id_mandor") . "'
				ORDER BY
					pemanen.nama_pemanen ASC
			");
			foreach ($ds_pemanen->result() as $ds_pemanen) {
				$data_pemanen = (array)$ds_pemanen;
				$data_pemanen["bulanan"] = array();
				foreach ($tanggal as $tgl) {
					//$premi_pemanen = self::premi_pemanen($data_pemanen["id_pemanen"], $tgl);
					//$premi_pemanen = 1000;
					
					// Menghitung premi
					$sql = "
						SELECT
							SUM(hasil.tbs_p1 + hasil.tbs_p2 + hasil.tbs_p3 + hasil.tbs_p4 + hasil.brd_p + hasil.premi_alat) AS total_premi
						FROM
							tbl_hasil_kg_per_pemanen hasil
						WHERE
							hasil.id_pemanen = '" . $data_pemanen["id_pemanen"] . "' AND hasil.tanggal = '" . $tgl . "'
					";
					$ds = $this->db->query($sql);
					$premi_pemanen = 0;
					foreach ($ds->result() as $dsres) {
						$premi_pemanen += $dsres->total_premi;
					}
					
					array_push($data_pemanen["bulanan"], $premi_pemanen);
				}
				
				$mandor = $data_pemanen["nama_mandor"];
				array_push($data_hasil, $data_pemanen);
			}
			
			
			$this->db->close();
			
			$data['title']  		= 'Prestasi panen per pemanen';
			$data["bulan"]			= parent::format_month($this->input->get("bulan"));
			$data["tahun"]			= $this->input->get("tahun");
			$data["tanggal_format"]	= $tanggal_format;
			$data["tanggal"]		= $tanggal;
			$data["nama_mandor"]	= $mandor;
			$data["data"]			= $data_hasil;
			$this->load->view('backend/lappremipanenbulanan/lappremipanenbulanan',$data);
			
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
