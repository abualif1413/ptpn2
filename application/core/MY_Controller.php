<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct(){
	   parent::__construct();
	   $this->load->library('upload');
	   $this->load->library('session');
	   $this->load->library('form_validation');
   	}
	
	   
	// Fungsi Untuk View Json
	//  self::json($data);
	public function json($data)
	{
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}

	// Fungsi Untuk Methode Post
	// if(self::isPost()){}
	public function isPost()
	{
		if (strtoupper($this->input->server("REQUEST_METHOD")) == "POST") {
			return true;
		} else {
			$response=new stdClass();
			$response->message = "Not allowed get request!";
			$response->data = null;
			self::json($response);
			return false;
		}
	}

	//  self::xss($data);
	public function xss($data)
	{
		return $this->security->xss_clean($data);
	}

	// $validations = array(
	// 	array(
	// 		'field' => 'name',
	// 		'label' => 'Support Name',
	// 		'rules' => 'required',
	// 	),
	// );
	public function validations($validations)
	{
		$this->form_validation->set_rules($validations);
		if ($this->form_validation->run() == FALSE){
			$data['error']=false;
			$data['error'] = validation_errors('<span style="color:red;">','</span><br>');
			return $data;
		}else {
			return true;
		}
	}

	public function upload_image()
	{
		$config['upload_path']          = 'assets/backend/img/photo';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 32000;
		$config['encrypt_name']         = true;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('photo'))
		{
			$errorfoto = $this->upload->display_errors();
			return $errorfoto;
		}
		else
		{
			$datafoto = $this->upload->data();
			return $datafoto;
		}
	}

	//FUNGSI AMBIL TANGGAL SEKARANG
	public function get_now() {
		$format = 'DATE_W3C';
		$time = time();

		$waktu = standard_date($format, $time);
		$tahun = substr($waktu, 0, 4);
		$bulan = substr($waktu, 5, 2);
		$hari = substr($waktu, 8, 2);

		return $tahun . '-' . $bulan . '-' . $hari;
	}

	//FUNGSI AMBIL TANGGAL DAN WAKTU SEKARANG
	public function datetime_now() {
		date_default_timezone_set('Asia/Jakarta'); // PHP 6 mengharuskan penyebutan timezone.
		$tgl_sekarang = date("Y-m-d");
		$jam_sekarang = date("H:i:s");

		return $tgl_sekarang.' '.$jam_sekarang;
	}

	/**
	 * Year
	 *
	 * Returns an string of year by date input.  This is a helper function
	 * for various other ones in this library
	 *
	 * @access	public
	 * @param	string	date
	 * @param	bolean	sql
	 * @return	string
	 */
	public function format_year($date, $sql = TRUE) {
		if ($sql == TRUE) {
			$year = substr($date, 0, 4);
		} else {
			//indonesian format
			$year = substr($date, 6, 4);
		}
		return $year;
	}

	//FUNGSI NAMA BULAN
	public function format_month($month) {
		$indo_month = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember'
		);

		return $indo_month[$month];
	}

	//FUNGSI FORMAT TANGGAL INDONESIA (12 Mei 2013)

	public function format_date($date) {
		$hari = substr($date, 8, 2);
		$bulan = substr($date, 5, 2);
		$tahun = substr($date, 0, 4);
		return $hari . ' ' . format_month($bulan) . ' ' . $tahun;
	}

	

	//FUNGSI TANGGAL DARI SQL KE INDONESIA (2013-05-23 -> 23-05-2013)
	

	public function format_sqltoindo($date) {
		$hari = substr($date, 8, 2);
		$bulan = substr($date, 5, 2);
		$tahun = substr($date, 0, 4);
		return $hari . '/' . $bulan . '/' . $tahun;
	}

	

	//FORMAT INDONESIA TO SQL (23-05-2013 -> 2013-05-23, biasanya dipakai buat edit data)
	

	public function format_indotosql($date) {
		$hari = substr($date, 0, 2);
		$bulan = substr($date, 3, 2);
		$tahun = substr($date, 6, 4);
		return $tahun . '-' . $bulan . '-' . $hari;
	}

	
	//FORMAT TANGGAL DAN WAKTU SQL (2013-05-23 11:55:45) biasanya dipakai untuk edit data/tambah
	
	public function format_datetime_sql($datetime) {
		$hari = substr($datetime, 0, 2);
		$bulan = substr($datetime, 3, 2);
		$tahun = substr($datetime, 6, 4);
		$jam = substr($datetime, 11, 2);
		$menit = substr($datetime, 14, 2);
		$detik = '00';
		return $tahun . '-' . $bulan . '-' . $hari . ' ' . $jam . ':' . $menit . ':' . $detik;
	}

	
	//FORMAT TANGGAL DAN WAKTU SQL -> INDO (23 Mei 2013 11:55:45)

	public function format_datetime_indo($datetime) {
		$hari = substr($datetime, 8, 2);
		$bulan = substr($datetime, 5, 2);
		$tahun = substr($datetime, 0, 4);
		$jam = substr($datetime, 11, 2);
		$menit = substr($datetime, 14, 2);
		$detik = substr($datetime, 17, 2);
		return $hari . '/' . $bulan . '/' . $tahun . ' ' . $jam . ':' . $menit . ':' . $detik;
	}


	/**
	 * Money format
	 *
	 * Returns an string of money with currency (Indonesian Format).  This is a helper function
	 * for various other ones in this library
	 *
	 * @access	public
	 * @param	string	nominal
	 * @return	string
	 */

	public function format_money($nominal) {
		return 'Rp. ' . number_format($nominal, 0, ',', '.');
	}

	

	/**
	 * Money format explode to string
	 *
	 * Returns indonesian currency format to string.  This is a helper function
	 * for various other ones in this library
	 *
	 * @access	public
	 * @param	string	nominal
	 * @return	string
	 */

	public function format_money_explode($nominal) {
		$replace = str_replace(array('Rp', '.', ','), array('', '', '.'), $nominal);
		return $replace;
	}

	

	/**
	 * Romawi Format
	 *
	 * Returns an string of month (Indonesian Format) by date input.  This is a helper function
	 * for various other ones in this library
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */

	public function format_romawi($n) {
		$hasil = '';
		$iromawi = array('', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X',
			20 => 'XX', 30 => 'XXX', 40 => 'XL', 50 => 'L', 60 => 'LX', 70 => 'LXX', 80 => 'LXXX',
			90 => 'XC', 100 => 'C', 200 => 'CC', 300 => 'CCC', 400 => 'CD', 500 => 'D',
			600 => 'DC', 700 => 'DCC', 800 => 'DCCC', 900 => 'CM', 1000 => 'M',
			2000 => 'MM', 3000 => 'MMM'
		);

		if (array_key_exists($n, $iromawi)) {
			$hasil = $iromawi[$n];
		} elseif ($n >= 11 && $n <= 99) {
			$i = $n % 10;
			$hasil = $iromawi[$n - $i] . Romawi($n % 10);
		} elseif ($n >= 101 && $n <= 999) {
			$i = $n % 100;
			$hasil = $iromawi[$n - $i] . Romawi($n % 100);
		} else {
			$i = $n % 1000;
			$hasil = $iromawi[$n - $i] . Romawi($n % 1000);
		}

		return $hasil;
	}

	
















































}
