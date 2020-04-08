<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historybyrpanen extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('backend/M_history_panen','M_history_panen');
        $this->load->model('backend/M_keraniaskep','M_keraniaskep');
        $this->load->model('backend/M_kebun','M_kebun');
        $this->load->model('backend/M_afdeling','M_afdeling');
        $this->load->model('backend/M_mandor','M_mandor');
        $this->load->model('backend/M_DataPemanen','M_DataPemanen');
        $this->load->library('Laporanfpdf');
        
    }

    public function index()
	{   
        if ($this->session->userdata('email')) {
			$data['title']  		= 'Halaman History Premi Panen';
            $data['view_content'] 	= 'backend/historybyrpanen/historybyrpanen';
            $data['kebun']          = $this->M_kebun->getAll()->result();
            $this->load->view('backend/layout/dashboard',$data);
        }else{
            redirect('Authadmin');
        }
		
    }

    public function datahistorypanen()
    {
        if(self::isPost()){
            $user=$this->session->userdata('user');
            extract($user);
            $Select = array(
                "tbl_kerani_askep.id",
            );
            $Where = array('tbl_kerani_askep.token'=>$token);
            $result =$this->M_keraniaskep->get_result2($Select,$Where,false,false,false);
            foreach ($result as $key);
            $id_kerani_askep=$key->id;

            $data = array();
            $orderBy = array(null,"tbl_history_panen.id");
            $search = array(
                "tbl_history_panen.id",
                "tbl_kerani_kcs.nama_lengkap",
            
            );
            $select = array(
                "tbl_history_panen.*",
                "tbl_kerani_kcs.nama_lengkap as kenarikcs",
                "tbl_pemanen.nama_pemanen",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_blok.blok as kode_blok",
            );

            $join = array(
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_history_panen.id_kerani_kcs'),
                array('tbl_pemanen','tbl_pemanen.id=tbl_history_panen.id_pemanen'),
                array('tbl_kebun','tbl_kebun.id=tbl_pemanen.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_pemanen.id_afdeling'),
                array('tbl_blok','tbl_blok.id=tbl_history_panen.blok'),
            );

            $group_by='tbl_history_panen.tanggal,tbl_history_panen.id_pemanen';

            $where=array('tbl_history_panen.id_kerani_askep'=> $id_kerani_askep);
            $result = $this->M_history_panen->findDataTable($orderBy,$search,$select,$where,$join,$group_by);
            foreach ($result as $item) {

                $item->total=$item->tbs+$item->premi_alat+$item->premi_brondolan;
                $item->tbs1="Rp. " . number_format($item->tbs,0,',','.');
                $item->premi_alat1="Rp. " . number_format($item->premi_alat,0,',','.');
                $item->premi_brondolan1="Rp. " . number_format($item->premi_brondolan,0,',','.');
                $item->total1="Rp. " . number_format($item->total,0,',','.');
                $data[] = $item;
            }
            return $this->M_history_panen->findDataTableOutput($data,$search,$select,$where,$join,$group_by);
        }
    }

    public function afdeling()
    {
        $select = array("tbl_afdeling.*");
        $where = array('tbl_afdeling.id_kebun'=>$this->input->post('id_kebun'));
        $result =$this->M_afdeling->get_result($select,$where,false,false,false,false);
        print"<option value=''>Pilih</option>";
        foreach ($result as $key => $value) {
            print"<option value=".$value->id.">".$value->nama_afdeling."</option>";
        }
    }

    public function pemanen()
    {
        $select = array("tbl_pemanen.*");
        $where = array(
            'tbl_pemanen.id_kebun'=>$this->input->post('id_kebun'),
            'tbl_pemanen.id_afdeling'=>$this->input->post('id_afdeling'),
        );
        $result =$this->M_DataPemanen->get_result($select,$where,false,false,false,false);
        print"<option value=''>Pilih</option>";
        foreach ($result as $value) {
            print"<option value=".$value->id.">".$value->nama_pemanen."</option>";
        }

    }

    public function laporan()
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
            $kebun = $this->input->post('id_kebun');
            $afdeling = $this->input->post('id_afdeling');
            //QUERY ULANGAN
            $query_absensi = mysqli_query($con,"SELECT substr(tanggal,9,2) AS tgl FROM tbl_history_panen 
                                        WHERE substr(tanggal,1,4) = '$tahun'
                                        AND substr(tanggal,6,2) = '$bulan'
                                        AND id_kebun = '$kebun'
                                        AND id_afdeling = '$afdeling'
                                        GROUP BY tanggal") or die(mysqli_error());
            $jumlah_absensi = mysqli_num_rows($query_absensi);
            
            //QUERY Karyawan
            $query_karyawan = mysqli_query($con,"SELECT * FROM tbl_pemanen WHERE 
            id_kebun = '$kebun' 
            AND id_afdeling = '$afdeling'
            GROUP BY id ") or die(mysqli_error());
            
            $jlh_karyawan = mysqli_num_rows($query_karyawan);
            
            
            if ($jumlah_absensi < 1) {
                echo "<script language='javascript'>
                                alert('Data absensi tidak ditemukan atau masih kosong');
                                window.location='".base_url('Historybyrpanen')."';
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
                $where=array(
                    'tbl_mandor.id_kebun'=> $kebun,
                    'tbl_mandor.id_afdeling'=> $afdeling,
                );
                $result=$this->M_mandor->get_result($select,$where,$join,false,false);
               
                foreach ($result as $key => $value22);

                $pdf->Image('https://koranbumn.com/wp-content/uploads/2018/07/ptpn-2-336x330.jpg', 40, 6, 20);
                $pdf->SetFont('Times', 'BU', '15');
                $pdf->Cell(0, 10, 'PT.PERKEBUNAN NUSANTARA II', 0, 1, 'C');
                $pdf->Ln(-2);
                $pdf->SetFont('Times', 'BU', '15');
                $TEXT='REKAPITULASI PREMI PEMANEN BULAN ' . strtoupper($format_bulan) . ' ' . $tahun;
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
                $pdf->Cell(20, 6, 'ID CARD', 'LR', 0, 'C');
                $pdf->Cell(30, 6, 'NAMA', 'LR', 0, 'C');
                //PERULANGAN KOLOM TANGGAL

                
                $nou = 0;
                while ($data_absen = mysqli_fetch_array($query_absensi)) {
                    $nou++;
                    $pdf->Cell(9.8, 6, $data_absen['tgl'], 'TLR', 0, 'C');
                }
                $pdf->Cell(8, 6, 'PREMI', 'LR', 0, 'C');
                $pdf->Ln();
                //=========0, 1,  2,  3,  4,  5,  6,  7=====//
                //Color and font restoration
                $pdf->setX(4);
                $pdf->SetFillColor(224, 235, 155);
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
                    $id_pemanen=$data['id'];

                    
                    $query_jlh_kehadiran = mysqli_query($con,"SELECT * FROM tbl_history_panen
                        WHERE id_pemanen = '$id_pemanen'
                        AND MONTH(tanggal) = '$bulan' 
                        AND YEAR(tanggal) = '$tahun'
                        AND id_kebun = '$kebun'
                        AND id_afdeling = '$afdeling' GROUP BY tanggal
                        ") or die(mysqli_error());
                    $jumlah_kehadiran = mysqli_num_rows($query_jlh_kehadiran);
                    // var_dump($jumlah_kehadiran);die();

                    $res = mysqli_query($con,"SELECT SUM(total) FROM tbl_history_panen 
                    WHERE id_pemanen = '$id_pemanen'
                        AND MONTH(tanggal) = '$bulan' 
                        AND YEAR(tanggal) = '$tahun'
                        AND id_kebun = '$kebun'
                        AND id_afdeling = '$afdeling' ") or die(mysqli_error());
                    $rows = mysqli_fetch_row($res);
                    $sum = $rows[0];
                    $data_result[] = array(
                        'barcode' => $data['barcode'],
                        'id_pemanen' => $data['id'],
                        'nama_pemanen' => $data['nama_pemanen'],
                        'jkanan' => number_format($sum),
                    );
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
                    $id_pemanen=$row['id_pemanen'];
                    $query_tgl = mysqli_query($con,"SELECT tanggal FROM tbl_history_panen 
                                        WHERE substr(tanggal,1,4) = '$tahun'
                                        AND substr(tanggal,6,2) = '$bulan'
                                        AND id_kebun = '$kebun'
                                        AND id_afdeling = '$afdeling'
                                        GROUP BY tanggal") or die(mysqli_error());
                    
                    $no_tgl = 0;
                    while ($data_tgl = mysqli_fetch_array($query_tgl)) {
                        $id_pemanen=$row['id_pemanen'];
                        $tanggal=$data_tgl['tanggal'];
                        $hadir_nip = mysqli_query($con,"SELECT * FROM tbl_history_panen WHERE id_pemanen ='$id_pemanen' AND tanggal='$tanggal' 
                        AND id_kebun = '$kebun'
                        AND id_afdeling = '$afdeling'
                        ") or die(mysqli_error());
                        $data_hadir_nip = mysqli_fetch_array($hadir_nip);

                        if ($data_hadir_nip['status']=='Y') {
                            $kehadiran = number_format($data_hadir_nip['total']);
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

    public function laporandetail()
    {
        if(self::isPost()){
            $select = array(
                "tbl_history_panen.*",
                "tbl_pemanen.nama_pemanen",
                "tbl_kebun.nama_kebun",
                "tbl_afdeling.nama_afdeling",
                "tbl_blok.blok",
                "tbl_kerani_askep.nama_lengkap as askep",
                "tbl_kerani_kcs.nama_lengkap as kcs",
            );
            $where = array(
                'tbl_history_panen.id_kebun'=>$this->input->post('id_kebun'),
                'tbl_history_panen.id_afdeling'=>$this->input->post('id_afdeling'),
                'tbl_history_panen.id_pemanen'=>$this->input->post('id_pemanen'),
                'MONTH(tbl_history_panen.tanggal)'=>$this->input->post('bulan'),
                'YEAR(tbl_history_panen.tanggal)'=>$this->input->post('tahun'),
            );
            $join = array(
                array('tbl_pemanen','tbl_pemanen.id=tbl_history_panen.id_pemanen'),
                array('tbl_kebun','tbl_kebun.id=tbl_history_panen.id_kebun'),
                array('tbl_afdeling','tbl_afdeling.id=tbl_history_panen.id_afdeling'),
                array('tbl_blok','tbl_blok.id=tbl_history_panen.blok'),
                array('tbl_kerani_askep','tbl_kerani_askep.id=tbl_history_panen.id_kerani_askep'),
                array('tbl_kerani_kcs','tbl_kerani_kcs.token=tbl_history_panen.id_kerani_kcs','left'),
            );
            $result =$this->M_history_panen->get_result($select,$where,$join,false,false,false);
            foreach ($result as $key => $value2);
            $pdf = new FPDF();
            $pdf->AddPage('L', 'legal');
            $pdf->Ln();
            $pdf->Image('https://koranbumn.com/wp-content/uploads/2018/07/ptpn-2-336x330.jpg', 40, 6, 20);
            $pdf->SetFont('Times', 'BU', '15');
            $pdf->Cell(0, 10, 'PT.PERKEBUNAN NUSANTARA II', 0, 1, 'C');
            $pdf->Ln(-2);
            $pdf->SetFont('Times', 'BU', '15');
            $TEXT='REKAPITULASI PREMI PEMANEN BULAN ' . strtoupper($this->input->post('bulan')) . ' TAHUN ' .$this->input->post('tahun');
            $pdf->Cell(0, 5,$TEXT, 0, 1, 'C');
            $pdf->SetFont('Times', 'BU', '15');
            $detail='KEBUN '.strtoupper($value2->nama_kebun). ' '.strtoupper($value2->nama_afdeling);
            $pdf->Cell(0, 5,$detail, 0, 1, 'C');
            $pdf->Ln(10);

                    
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(7,5,'NO',1,0);
            $pdf->Cell(15,5,'TANGGAL',1,0);
            $pdf->Cell(40,5,'PEMANEN',1,0);
            $pdf->Cell(20,5,'KEBUN',1,0);
            $pdf->Cell(17,5,'AFFDELING',1,0);
            $pdf->Cell(10,5,'BLOK',1,0);
            $pdf->Cell(14,5,'JANJANG',1,0);
            $pdf->Cell(14,5,'KOMIDEL',1,0);
            $pdf->Cell(15,5,'PRESTASI',1,0);
            $pdf->Cell(16,5,'B.TAKSIR',1,0);
            $pdf->Cell(16,5,'B.TIMBANG',1,0);
            $pdf->Cell(16,5,'P.PROPORSI',1,0);
            $pdf->Cell(60,5,'ALAT',1,0);
            $pdf->Cell(14,5,'P.TBS',1,0);
            $pdf->Cell(14,5,'P.ALAT',1,0);
            $pdf->Cell(20,5,'P.BRONDOLAN',1,0);
            $pdf->Cell(15,5,'P.TOTAL',1,1);
            
            $pdf->SetFont('Arial','',7);
            // data
            $no=1;
            $jmlh_panen     =0;
            $nilai_komidel  =0;
            $prestasi       =0;
            $jmlh_brondolan =0;
            $bron_tim       =0;
            $koef           =0;
            $tbs            =0;
            $premi_alat     =0;
            $premi_brondolan=0;
            $total          =0;
            foreach ($result as $key => $value) {
                $pdf->Cell(7,5,$no,1,0);
                $pdf->Cell(15,5,$value->tanggal,1,0);
                $pdf->Cell(40,5,$value->nama_pemanen,1,0);
                $pdf->Cell(20,5,$value->nama_kebun,1,0);
                $pdf->Cell(17,5,$value->nama_afdeling,1,0);
                $pdf->Cell(10,5,$value->blok,1,0);
                $pdf->Cell(14,5,$value->jmlh_panen,1,0);
                $pdf->Cell(14,5,$value->nilai_komidel.' kg',1,0);
                $pdf->Cell(15,5,$value->prestasi.' kg',1,0);
                $pdf->Cell(16,5,$value->jmlh_brondolan.' kg',1,0);
                $pdf->Cell(16,5,$value->bron_tim.' kg',1,0);
                $pdf->Cell(16,5,$value->koef.' %',1,0);
                $pdf->Cell(60,5,$value->nama_alat,1,0);
                $pdf->Cell(14,5,'Rp.'.number_format($value->tbs),1,0);
                $pdf->Cell(14,5,'Rp.'.number_format($value->premi_alat),1,0);
                $pdf->Cell(20,5,'Rp.'.number_format($value->premi_brondolan),1,0);
                $pdf->Cell(15,5,'Rp.'.number_format($value->total),1,1);
                $no++;
                $jmlh_panen     +=$value->jmlh_panen;
                $nilai_komidel  +=$value->nilai_komidel;
                $prestasi       +=$value->prestasi;
                $jmlh_brondolan +=$value->jmlh_brondolan;
                $bron_tim       +=$value->bron_tim;
                $koef           +=$value->koef;
                $tbs            +=$value->tbs;
                $premi_alat     +=$value->premi_alat;
                $premi_brondolan+=$value->premi_brondolan;
                $total          +=$value->total;

            }
                $pdf->Cell(109,5,'GRAND TOTAL',1,0,'C');
                $pdf->Cell(14,5,$jmlh_panen,1,0);
                $pdf->Cell(14,5,$nilai_komidel.' kg',1,0);
                $pdf->Cell(15,5,$prestasi.' kg',1,0);
                $pdf->Cell(16,5,$jmlh_brondolan.' kg',1,0);
                $pdf->Cell(16,5,$bron_tim.' kg',1,0);
                $pdf->Cell(16,5,$koef.' %',1,0);
                $pdf->Cell(60,5,'',1,0);
                $pdf->Cell(14,5,'Rp.'.number_format($tbs),1,0);
                $pdf->Cell(14,5,'Rp.'.number_format($premi_alat),1,0);
                $pdf->Cell(20,5,'Rp.'.number_format($premi_brondolan),1,0);
                $pdf->Cell(15,5,'Rp.'.number_format($total),1,1);

            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 10);
            // footer selalu sama
            $pdf->SetX(-400);
            $pdf->Cell(0, 6, 'Mengetahui,', 0, 1, 'C');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetX(-400);
            $pdf->Cell(0, 6, 'Kerani Askep', 0, 0, 'C');
            $pdf->SetX(100);
            $pdf->Cell(0, 6, 'Disiapkan Oleh', 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'BU', 10);
            $pdf->SetX(-400);
            $pdf->Cell(0, 6,strtoupper($value2->askep), 0, 0, 'C'); //NAMA DIREKTUR
            $pdf->SetX(100);
            $pdf->Cell(0, 6,strtoupper($value2->kcs), 0, 1, 'C'); //NAMA KEUANGAN
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
