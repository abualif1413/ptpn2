<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataabsen020 extends MY_Controller {

	public function __construct(){
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('backend/M_absen','M_absen');
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        $this->load->model('backend/M_mandor','M_mandor');
        $this->load->library('Laporanfpdf');
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman Absen';
            $data['view_content'] 	= 'backend/dataabsen020/dataabsen020';
            $data['token_mandor']    = $this->session->token;
            $data["dataAbsen"]       = array();
            $data["tanggal"]         = $this->input->get("tanggal");
            if($this->input->get("tanggal") != "") {
                $this->load->database();
                $sql = "
                    SELECT
                        pemanen.barcode, pemanen.nama_pemanen, COALESCE(absen.kehadiran, 'n/a') kehadiran, COALESCE(absen.jam, '') AS jam
                    FROM
                        tbl_pemanen pemanen
                        LEFT JOIN tbl_mandor mandor ON pemanen.id_mandor = mandor.token
                        LEFT JOIN tbl_absen absen ON pemanen.barcode = absen.id_absen AND absen.tanggal = '" . $this->input->get("tanggal") . "'
                    WHERE
                        mandor.token = '" . $this->session->token . "'
                    ORDER BY
                        pemanen.nama_pemanen ASC
                ";
                $ds = $this->db->query($sql);
                $dataAbsen = array();
                foreach($ds->result() as $res) {
                    $temp = array(
                        "barcode" => $res->barcode,
                        "nama_pemanen" => $res->nama_pemanen,
                        "kehadiran" => $res->kehadiran,
                        "jam" => $res->jam
                    );
                    array_push($dataAbsen, $temp);
                }
                $this->db->close();
                $data["dataAbsen"] = $dataAbsen;
            }
			$this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
	}

	public function Add_Absen()
    {
        if(self::isPost()){
            $data = self::validations(array(
                array('field'=>'id_absen','label'=>'Id Not Found','rules'=>'required'),
            ));
            if($data === true){
                    
                    $select_pemanen = array("tbl_pemanen.nama_pemanen","tbl_pemanen.id_mandor");
                    $where_pemanen = array('tbl_pemanen.barcode'=>$this->input->post('id_absen'));
                    $result_pemanen=$this->M_DataPemanen->get_result($select_pemanen,$where_pemanen,false,false,false);
                    foreach ($result_pemanen as $key_pemanen);
                    $nama_pemanen=$key_pemanen->nama_pemanen;
                    $id_mandor=$key_pemanen->id_mandor;
                    $field= array(
                        'id_mandor' => $id_mandor,
						'id_absen'=>$this->input->post('id_absen'),
						'kehadiran'=>'H',
                        'tanggal'=>date('Y-m-d'),
                        'jam'=>date("h:i:s"),
                    );
                    $pesan='<b style="color:red;">'.$nama_pemanen.'</b> Sudah Absen';
                    $pesan2='<b style="color:green;">'.$nama_pemanen.'</b> Berhasil Absen';
					$where=array(
                        'id_absen' => $this->input->post('id_absen'),
                        'tanggal' => date('Y-m-d')
                    );
                    if($this->M_absen->validasiData($where)){
                        $data=array('success'=>false,'error'=>$pesan);
                    }else{
                        $data=$this->M_absen->insert(self::xss($field));
                        $data=array('success'=>true,'type'=>'Add','pesan'=>$pesan2);
                    }
            }  
        }
        self::json($data);
    }


    public function Data_Absen()
    {   
        if(self::isPost()){
            $data = array();
            $orderBy = array(
                null,
                "tbl_absen.id_absen",
                "tbl_absen.tanggal",
                "tbl_pemanen.nama_pemanen",
            );
            $search = array(
                "tbl_absen.id_absen",
                "tbl_absen.tanggal",
                "tbl_pemanen.nama_pemanen",
            );
            $select = array(
                "tbl_absen.*",
                "tbl_pemanen.nama_pemanen",
                "tbl_pemanen.id_mandor",
            );
            $join=array(
                array('tbl_pemanen','tbl_pemanen.barcode=tbl_absen.id_absen')
            );
            $where=array('tbl_pemanen.id_mandor' => $this->session->token);       
            $result = $this->M_absen->findDataTable($orderBy,$search,$select,$where,$join);
            foreach ($result as $item) {
                if($item->jam > '07:00:00'){
                    $item->jam = '<button class="btn btn-danger btn-outline-danger btn-mini"><i class="fa fa-pencil-square-o"></i>'.$item->jam.'</button>';
                }else{
                    $item->jam = '<button class="btn btn-success btn-outline-success btn-mini"><i class="fa fa-pencil-square-o"></i>'.$item->jam.'</button>';
                }
                $data[] = $item;
            }
            return $this->M_absen->findDataTableOutput($data,$search,$select,$where,$join);
        }
    }

    public function Laporan_Absen()
    {
        if(self::isPost()){
            $db_user="asikinon_ptpn2";
            $db_password="asikinon_ptpn2";
            $db_name ="asikinon_ptpn2";
            $db_host="localhost";
            $con=mysqli_connect ($db_host, $db_user, $db_password) or die ("tidak bisa connect");
            mysqli_select_db ($con,$db_name) or die ("salah db");

            $format_bulan = self::format_month($this->input->post('bulan'));
            $bulan = $this->input->post('bulan');
            $tahun = $this->input->post('tahun');
            $id_mandor =$this->session->token;
            //QUERY ULANGAN
            $query_absensi = mysqli_query($con,"SELECT substr(tanggal,9,2) AS tgl FROM tbl_absen 
                                        WHERE substr(tanggal,1,4) = '$tahun'
                                        AND substr(tanggal,6,2) = '$bulan'
                                        AND id_mandor = '$id_mandor'
                                        GROUP BY tanggal") or die(mysqli_error());
            $jumlah_absensi = mysqli_num_rows($query_absensi);
            //QUERY Karyawan
            $query_karyawan = mysqli_query($con,"SELECT * FROM tbl_pemanen WHERE id_mandor = '$id_mandor' GROUP BY barcode ") or die(mysqli_error());
            $jlh_karyawan = mysqli_num_rows($query_karyawan);
            if ($jumlah_absensi < 1) {
                echo "<script language='javascript'>
                                    alert('Data absensi tidak ditemukan atau masih kosong');
                                    window.location='".base_url('Dataabsen')."';
                    </script>";
            } else {
                $pdf = new FPDF();
                $pdf->AliasNbPages();
                $pdf->AddPage('L', 'A4');
                $pdf->Ln();
                //TABEL FORM
                $linespace1 = 10;

                $select = array(
                    'tbl_mandor.nama_lengkap as mandor',
                    'tbl_kebun.nama_kebun',
                    'tbl_afdeling.nama_afdeling',
                    'tbl_kerani_kcs.nama_lengkap as keranikcs'
                );
                $join = array(
                    array('tbl_kebun','tbl_kebun.id=tbl_mandor.id_kebun','left'),
                    array('tbl_afdeling','tbl_afdeling.id=tbl_mandor.id_afdeling','left'),
                    array('tbl_distrik','tbl_distrik.id=tbl_kebun.id_distrik','left'),
                    array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_mandor.id_kerani_askep','left'),
                    array('tbl_kerani_kcs','tbl_kerani_kcs.id=tbl_mandor.id_kerani_kcs','left'),
                    array('tbl_admin','tbl_admin.token=tbl_mandor.token','left'),
                );
                $where=array('tbl_mandor.token'=> $this->session->token);
                $result=$this->M_mandor->get_result($select,$where,$join,false,false);
                foreach ($result as $key => $value22);

                $pdf->Image('https://koranbumn.com/wp-content/uploads/2018/07/ptpn-2-336x330.jpg', 40, 6, 20);
                $pdf->SetFont('Times', 'BU', '15');
                $pdf->Cell(0, 10, 'PT.PERKEBUNAN NUSANTARA II', 0, 1, 'C');
                $pdf->Ln(-2);
                $pdf->SetFont('Times', 'BU', '15');
                $TEXT='REKAPITULASI ABSENSI PEMANEN BULAN ' . strtoupper($format_bulan) . ' ' . $tahun;
                $pdf->Cell(0, 5,$TEXT, 0, 1, 'C');
                $pdf->SetFont('Times', 'BU', '15');
                $detail='KEBUN '.strtoupper($value22->nama_kebun). ' '.strtoupper($value22->nama_afdeling);
                $pdf->Cell(0, 5,$detail, 0, 1, 'C');
                $pdf->Ln(10);
                //TABEL DATA
                $linespace = 10;
                $w = array(6, 15, 40, 7, 7, 15, 7, 7, 15);
                //=========0, 1,  2,  3, 4, 5, 6, 7=====//

                $pdf->setX(4);
                $pdf->SetFont('Arial', 'B', 6);

                $pdf->Cell(5, 6, '', 'TLR', 0, 'L');
                $pdf->Cell(20, 6, '', 'TLR', 0, 'C');
                $pdf->Cell(30, 6, '', 'TLR', 0, 'C');
                //PERULANGAN KOLOM TANGGAL
                $pdf->Cell(9.8 * $jumlah_absensi, 6, 'TANGGAL', 'TLR', 0, 'C');
                $pdf->Cell(8, 6, 'JML', 'TLR', 0, 'C');
                $pdf->Ln();
                $pdf->setX(4);
                $pdf->Cell(5, 6, 'NO', 'LR', 0, 'C');
                $pdf->Cell(20, 6, 'ID ABSEN', 'LR', 0, 'C');
                $pdf->Cell(30, 6, 'NAMA', 'LR', 0, 'C');
                //PERULANGAN KOLOM TANGGAL
                $nou = 0;
                while ($data_absen = mysqli_fetch_array($query_absensi)) {
                    $nou++;
                    $pdf->Cell(9.8, 6, $data_absen['tgl'], 'TLR', 0, 'C');
                }
                $pdf->Cell(8, 6, 'HADIR', 'LR', 0, 'C');
                $pdf->Ln();
                //=========0, 1,  2,  3,  4,  5,  6,  7=====//
                //Color and font restoration
                $pdf->setX(4);
                $pdf->SetFillColor(224, 235, 255);
                $pdf->SetTextColor(1);
                $pdf->SetFont('Arial', '', 6);
                //Data
                $fill = false;
                $i = 0;
                // $num = count($query_absensi);
                $data_result = array();
                while ($data = mysqli_fetch_array($query_karyawan)) {
                    $jumlah_kanan = 0;
                    $result_kehadiran = array();

                    $query_jlh_kehadiran = mysqli_query($con,"SELECT * FROM tbl_absen
                        WHERE id_absen = '$data[barcode]' 
                        AND kehadiran = 'H' 
                        AND MONTH(tanggal) = '$bulan' 
                        AND YEAR(tanggal) = '$tahun'
                        AND id_mandor = '$id_mandor' GROUP BY tanggal
                        ") or die(mysqli_error());
                    $jumlah_kehadiran = mysqli_num_rows($query_jlh_kehadiran);

                    $data_result[] = array(
                        'barcode' => $data['barcode'],
                        'nama_pemanen' => $data['nama_pemanen'],
                        'jkanan' => $jumlah_kehadiran,
                    );
                    //var_dump($data_result);die();
                }
                $j = 0;
                foreach ($data_result as $row) {
                    $pdf->setX(4);
                    $i++;
                    $pdf->Cell(5, $linespace, $i, 'TLRB', 0, 'C', $fill); //NOMOR
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(20, $linespace, $row['barcode'], 'TLRB', 0, 'L', $fill); //NIS
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(30, $linespace, ucwords(strtolower($row['nama_pemanen'])), 'TLRB', 0, 'L', $fill); //NAMA KARYAWAN

                    $pdf->SetFont('Arial', '', 8);
                    $query_tgl = mysqli_query($con,"SELECT tanggal FROM tbl_absen 
                                        WHERE substr(tanggal,1,4) = '$tahun'
                                        AND substr(tanggal,6,2) = '$bulan'
                                        AND id_mandor = '$id_mandor'
                                        GROUP BY tanggal") or die(mysqli_error());
                    $no_tgl = 0;
                    while ($data_tgl = mysqli_fetch_array($query_tgl)) {
                        $barcode=$row['barcode'];
                        $tanggal=$data_tgl['tanggal'];
                        $hadir_nip = mysqli_query($con,"SELECT * FROM tbl_absen WHERE id_absen ='$barcode' AND tanggal='$tanggal' AND id_mandor = '$id_mandor' ") or die(mysqli_error());
                        $data_hadir_nip = mysqli_fetch_array($hadir_nip);
                        if ($data_hadir_nip['kehadiran']=='H') {
                            if($data_hadir_nip['jam'] > '07:00:00'){
                                $pdf->SetTextColor(220,20,60);
                                $kehadiran = $data_hadir_nip['jam'];
                            }else{
                                $pdf->SetTextColor(0,0,0);
                                $kehadiran = $data_hadir_nip['jam'];
                            }
                        }else {
                            $kehadiran = '-';
                        }
                        $pdf->SetFont('Arial', 'B', 6);
                        $no_tgl++;
                        $pdf->Cell(9.8, $linespace, $kehadiran, 'TLRB', 0, 'C', $fill);
                        $pdf->SetTextColor(0,0,0);
                    }
                    $pdf->Cell(8, $linespace, $row['jkanan'], 'TLRB', 0, 'C', $fill); //JUMLAH KANAN
                    $fill = !$fill;
                    $pdf->Ln();
                    $j++;
                }
                
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 10);
                // footer selalu sama
                $pdf->SetX(-400);
                $pdf->Cell(0, 6, 'Mengetahui,', 0, 1, 'C');
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetX(-400);
                $pdf->Cell(0, 6, 'Kerani Kcs', 0, 0, 'C');
                $pdf->SetX(100);
                $pdf->Cell(0, 6, 'Disiapkan Oleh', 0, 1, 'C');
                $pdf->Ln(10);
                $pdf->SetFont('Arial', 'BU', 10);
                $pdf->SetX(-400);
                $pdf->Cell(0, 6,strtoupper($value22->keranikcs), 0, 0, 'C'); //NAMA DIREKTUR
                $pdf->SetX(100);
                $pdf->Cell(0, 6,strtoupper($value22->mandor), 0, 1, 'C'); //NAMA KEUANGAN
                $pdf->ln(-1);

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetX(-400);
                $pdf->Cell(0, 6, 'NIP. _______________', 0, 0, 'C'); //NIP DIREKTUR
                $pdf->SetX(100);
                $pdf->Cell(0, 6, 'NIP. _______________', 0, 1, 'C'); //NIP KEUANGAN
                $pdf->Output();
            }
        }
        
    }






























































}
