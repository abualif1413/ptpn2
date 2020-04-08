<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SlideShowMonitoringPanen extends MY_Controller {

	public function __construct(){
        parent::__construct();
		
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
	}
	
	public function index()
	{
        if ($this->session->userdata('email')) {
            $this->load->database();

            // Mencari nama kebun dan afdeling
            $ds_kbn_afd = $this->db->query("
                SELECT
                    kbn.nama_kebun, afd.nama_afdeling
                FROM
                    tbl_afdeling afd
                    LEFT JOIN tbl_kebun kbn ON afd.id_kebun = kbn.id
                WHERE
                    afd.id = '" . $this->input->get("afdeling") . "'
            ");
            $nama_kebun = "";
            $nama_afdeling = "";
            foreach($ds_kbn_afd->result() as $kbnafd) {
                $nama_kebun = $kbnafd->nama_kebun;
                $nama_afdeling = $kbnafd->nama_afdeling;
            }

            // Mencari sampai tanggal berapa slide show ditampilkan
            $ds_per_tgl = $this->db->query("
                SELECT
                    per_tgl.per_tgl, DATE_FORMAT(per_tgl.per_tgl,'%d-%m-%Y') AS sampai_tgl
                FROM
                    (
                        SELECT
                        CASE
                            WHEN LAST_DAY('" . $this->input->get("tahun") . "-" . $this->input->get("bulan") . "-01') > CURDATE() THEN CURDATE()
                            ELSE LAST_DAY('" . $this->input->get("tahun") . "-" . $this->input->get("bulan") . "-01')
                        END AS per_tgl
                    ) per_tgl
            ");
            $per_tgl = "";
            $per_tgl_tostring = "";
            foreach($ds_per_tgl->result() as $tgl) {
                $per_tgl = $tgl->per_tgl;
                $per_tgl_tostring = $tgl->sampai_tgl;
            }

            // Mencari semua blok pada afdeling yang dipilih
            $blok = array();
            if($this->input->get("afdeling") != "0") {
                $ds_blok = $this->db->query("
                    SELECT
                        MAX(blk.id) AS id, blk.blok, MAX(blk.tahun_tanam) AS tahun_tanam
                    FROM
                        tbl_blok blk
                    WHERE
                        blk.id_afdeling = '" . $this->input->get("afdeling") . "'
                    GROUP BY
                        blk.blok
                    ORDER BY
                        blk.blok ASC
                ");
            } else {
                $ds_blok = $this->db->query("
                    SELECT
                        blok.id, blok.blok, blok.tahun_tanam
                    FROM
                        tbl_blok blok
                        LEFT JOIN tbl_afdeling afdeling ON blok.id_afdeling = afdeling.id
                        LEFT JOIN tbl_kebun kebun ON afdeling.id_kebun = kebun.id
                    ORDER BY
                        kebun.nama_kebun ASC, afdeling.nama_afdeling ASC, blok.blok ASC
                ");
            }
            foreach($ds_blok->result() as $blk) {
                $temp_blok = array(
                    "id" => $blk->id,
                    "blok" => $blk->blok,
                    "tahun_tanam" => $blk->tahun_tanam
                );
                array_push($blok, $temp_blok);
            }

            $data['title']  		    = 'Monitoring hasil panen';
            $data['bulan']              = $this->input->get("bulan");
            $data['tahun']              = $this->input->get("tahun");
            $data['afdeling']           = $this->input->get("afdeling");
            $data['per_tgl']            = $per_tgl;
            $data['per_tgl_tostring']   = $per_tgl_tostring;
            $data['blok']               = $blok;
            $data['nama_kebun']         = $nama_kebun;
            $data['nama_afdeling']      = $nama_afdeling;
            $this->db->close();
			$this->load->view('backend/slideshowmonitoringpanen/slideshowmonitoringpanen',$data);
        }else{
            redirect('Authadmin');
        }
		
    }

    function get_data_monitoring() {
        $this->load->database();

        // Mencari target panen
        $target_panen = 0;
        $ds_target_panen = $this->db->query("
            SELECT
                target_panen
            FROM
                tbl_target_panen_bulanan
            WHERE
                id_blok = '" . $this->input->post("id_blok") . "' AND bulan = '" . $this->input->post("bulan") . "' AND tahun = '" . $this->input->post("tahun") . "'
        ");
        foreach($ds_target_panen->result() as $ds) {
            $target_panen = $ds->target_panen;
        }

        // Mencari total hari
        $hari = 0;
        $jumlah_hari = 0;
        $ds_total_hari = $this->db->query("
            SELECT
                DAY(LAST_DAY('" . $this->input->post("per_tgl") . "')) AS jumlah_hari,
                DAY('" . $this->input->post("per_tgl") . "') AS hari;
        ");
        foreach($ds_total_hari->result() as $ds) {
            $hari = $ds->hari;
            $jumlah_hari = $ds->jumlah_hari;
        }

        // Mencari target panen per tanggal ini
        $target_panen_sd_hari_ini = ($target_panen / $jumlah_hari) * $hari;

        // Mencari jumlah panen dalam janjang
        $dari = $this->input->post("tahun") . "-" . $this->input->post("bulan") . "-01";
        $sampai = $this->input->post("per_tgl");
        $jumlah_tbs_janjang = 0;
        $ds_jumlah_tbs_janjang = $this->db->query("
            SELECT jmlh_panen FROM tbl_panen WHERE blok = '" . $this->input->post("id_blok") . "' AND tanggal BETWEEN '" . $dari . "' AND '" . $sampai . "'
        ");
        foreach($ds_jumlah_tbs_janjang->result() as $ds) {
            $jumlah_tbs_janjang += doubleval($ds->jmlh_panen);
        }

        // Mencari jumlah panen dalam Kg
        $jumlah_tbs_kg = 0;
        $ds_jumlah_tbs_kg = $this->db->query("
            SELECT
                (tripstdet.hasil_kg + tripstdet.hasil_kg_restan) AS hasil
            FROM
                tbl_trip_020 trip
                INNER JOIN tbl_trip_020_detail tripdet ON trip.id = tripdet.id_trip
                INNER JOIN tbl_trip_setelah_timbang_detail tripstdet ON tripdet.id = tripstdet.id_trip_detail
            WHERE
                trip.tanggal BETWEEN '" . $dari . "' AND '" . $sampai . "'
                AND tripdet.id_blok = '" . $this->input->post("id_blok") . "'
        ");
        foreach($ds_jumlah_tbs_kg->result() as $ds) {
            $jumlah_tbs_kg += $ds->hasil;
        }

        // Mencari persentase realisasi TBS
        $persen_tbs = ($target_panen_sd_hari_ini == 0) ? 0 : ($jumlah_tbs_kg * 100) / $target_panen_sd_hari_ini;

        // Mencari data id afdeling nya
        $ds_data_kebun_afdeling = $this->db->query("
            SELECT
                afdeling.id AS id_afdeling, kebun.nama_kebun, afdeling.nama_afdeling
            FROM
                tbl_blok blok
                LEFT JOIN tbl_afdeling afdeling ON blok.id_afdeling = afdeling.id
                LEFT JOIN tbl_kebun kebun ON afdeling.id_kebun = kebun.id
            WHERE
                blok.id = '" . $this->input->post("id_blok") . "'
        ");
        $id_afdeling = 0;
        $nama_kebun = "";
        $nama_afdeling = "";
        foreach($ds_data_kebun_afdeling->result() as $ds) {
            $id_afdeling = $ds->id_afdeling;
            $nama_kebun = $ds->nama_kebun;
            $nama_afdeling = $ds->nama_afdeling;
        }

        // Mencari jumlah brondolan yang di SPTBS kan
        $ds_brondolan = $this->db->query("
            SELECT
                brd.tanggal, brd.kg_brondolan_all, brd.panen_brondolan_all, brd.panen_brondolan_blok,
                COALESCE((brd.kg_brondolan_all * brd.panen_brondolan_blok / brd.panen_brondolan_all), 0) AS brondolan
            FROM
                (
                    SELECT
                        tgl.tanggal,
                        COALESCE(kg_brondolan.brondolan, 0) AS kg_brondolan_all,
                        COALESCE(panen_brondolan.jmlh_brondolan, 0) AS panen_brondolan_all,
                        COALESCE(panen_brondolan_blok.jmlh_brondolan, 0) AS panen_brondolan_blok
                    FROM
                        rentang_tanggal tgl
                        LEFT JOIN (
                            SELECT
                                trip.tanggal, SUM(tripst.timbang_brondolan) AS brondolan
                            FROM
                                tbl_trip_020 trip
                                INNER JOIN tbl_trip_setelah_timbang tripst ON trip.id = tripst.id_trip
                            WHERE
                                trip.id_afdeling = '" . $id_afdeling . "'
                            GROUP BY
                                trip.tanggal
                        ) kg_brondolan ON tgl.tanggal = kg_brondolan.tanggal
                        LEFT JOIN (
                            SELECT
                                panen.tanggal, SUM(panen.jmlh_brondolan) AS jmlh_brondolan
                            FROM
                                tbl_panen panen
                            WHERE
                                panen.id_afdeling = '" . $id_afdeling . "'
                            GROUP BY
                                panen.tanggal
                        ) panen_brondolan ON tgl.tanggal = panen_brondolan.tanggal
                        LEFT JOIN (
                            SELECT
                                panen.tanggal, SUM(panen.jmlh_brondolan) AS jmlh_brondolan
                            FROM
                                tbl_panen panen
                            WHERE
                                panen.blok = '" . $this->input->post("id_blok") . "'
                            GROUP BY
                                panen.tanggal
                        ) panen_brondolan_blok ON tgl.tanggal = panen_brondolan_blok.tanggal
                    WHERE
                        tgl.tanggal BETWEEN '" . $dari . "' AND '" . $sampai . "'
                ) brd
        ");
        $jumlah_brondolan = 0;
        foreach($ds_brondolan->result() as $ds) {
            $jumlah_brondolan += $ds->brondolan;
        }

        $persen_brondolan = ($jumlah_tbs_kg == 0) ? 0 : (($jumlah_brondolan * 100) / $jumlah_tbs_kg);

        // Mencari data absen nya
        $ds_rekap_absen = $this->db->query("
            SELECT
                CASE
                    WHEN absen.kehadiran = 'n/a' THEN 'Tidak absen'
                    WHEN absen.kehadiran = 'h' THEN 'Hadir (Panen)'
                    WHEN absen.kehadiran = 'ht' THEN 'Hadir (Tunasan)'
                    WHEN absen.kehadiran = 's' THEN 'Sakit'
                    WHEN absen.kehadiran = 'c' THEN 'Cuti'
                    WHEN absen.kehadiran = 'p1' THEN 'Ijin P1'
                    WHEN absen.kehadiran = 'p2' THEN 'Ijin P2'
                    WHEN absen.kehadiran = 'p3' THEN 'Ijin P3'
                    WHEN absen.kehadiran = 'h1' THEN 'Ijin H1'
                    WHEN absen.kehadiran = 's1' THEN 'Ijin S1'
                    WHEN absen.kehadiran = 's2' THEN 'Ijin S2'
                END AS status_kehadiran, COUNT(absen.kehadiran) AS jumlah
            FROM
                (
                    SELECT
                        pemanen.barcode, pemanen.nama_pemanen, COALESCE(absen.kehadiran, 'n/a') kehadiran, COALESCE(absen.jam, '') AS jam
                    FROM
                        tbl_pemanen pemanen
                        LEFT JOIN tbl_mandor mandor ON pemanen.id_mandor = mandor.token
                        LEFT JOIN tbl_absen absen ON pemanen.barcode = absen.id_absen AND absen.tanggal = '" . $sampai . "'
                    WHERE
                        mandor.id_afdeling = '" . $id_afdeling . "'
                    ORDER BY
                        pemanen.nama_pemanen ASC
                ) absen
            GROUP BY
                absen.kehadiran
        ");
        $rekap_absen = array();
        foreach($ds_rekap_absen->result() as $ds) {
            $temp = array(
                "status_kehadiran" => $ds->status_kehadiran,
                "jumlah" => $ds->jumlah
            );
            array_push($rekap_absen, $temp);
        }

        $data_monitoring = array(
            "nama_kebun"                => $nama_kebun,
            "nama_afdeling"             => $nama_afdeling,
            "target_panen"              => $target_panen,
            "target_panen_sd_hari_ini"  => $target_panen_sd_hari_ini,
            "jumlah_tbs_janjang"        => $jumlah_tbs_janjang,
            "jumlah_tbs_kg"             => $jumlah_tbs_kg,
            "jumlah_tbs_persen"         => $persen_tbs,
            "jumlah_brondolan_kg"       => $jumlah_brondolan,
            "jumlah_brondolan_persen"   => $persen_brondolan,
            "rekap_absen"               => $rekap_absen
        );

        $this->db->close();

        echo json_encode($data_monitoring);
    }
    
    function get_data() {
        // Mencari target panen
        $this->load->database();
        $target_panen = "";
        $ds_target_panen = $this->db->query("
            SELECT
                MAX(blk.id) AS id, blk.blok, MAX(blk.tahun_tanam) AS tahun_tanam,
                MAX(COALESCE(target.target_panen, 0)) AS target_panen
            FROM
                tbl_blok blk
                LEFT JOIN tbl_target_panen_bulanan target ON blk.id = target.id_blok AND bulan = '" . $this->input->post("bulan") . "'
                AND tahun = '" . $this->input->post("tahun") . "'
            WHERE
                blk.id_afdeling = '" . $this->input->post("id_afdeling") . "' AND blk.blok = '" . $this->input->post("blok") . "'
            GROUP BY
                blk.blok
            ORDER BY
                blk.blok ASC
        ");
        foreach($ds_target_panen->result() as $target) {
            $target_panen = $target->target_panen;
        }

        // Mencari sampai tanggal berapa slide show ditampilkan
        $ds_per_tgl = $this->db->query("
            SELECT
                per_tgl.per_tgl, DATE_FORMAT(per_tgl.per_tgl,'%d-%m-%Y') AS sampai_tgl,
                DATE_FORMAT(per_tgl.per_tgl,'%d') AS jumlah_hari, DATE_FORMAT(per_tgl.total_hari,'%d') AS total_hari
            FROM
                (
                    SELECT
                        CASE
                            WHEN LAST_DAY('" . $this->input->post("tahun") . "-" . $this->input->post("bulan") . "-01') > CURDATE() THEN CURDATE()
                            ELSE LAST_DAY('" . $this->input->post("tahun") . "-" . $this->input->post("bulan") . "-01')
                        END AS per_tgl,
                        LAST_DAY('" . $this->input->post("tahun") . "-" . $this->input->post("bulan") . "-01') AS total_hari
                ) per_tgl
        ");
        $dari_tgl = $this->input->post("tahun") . "-" . $this->input->post("bulan") . "-01";
        $per_tgl = "";
        $jumlah_hari = 0;
        $total_hari = 0;
        foreach($ds_per_tgl->result() as $tgl) {
            $per_tgl = $tgl->per_tgl;
            $jumlah_hari = $tgl->jumlah_hari;
            $total_hari = $tgl->total_hari;
        }

        // Mencari realisasi panen
        $realisasi_tbs = 0;
        $realisasi_brondolan = 0;
        $ds_realisasi_panen = $this->db->query("
            SELECT
                sptbs.id, sptbs.id_blok_no_1, blok.blok,
                sptbs.berat_no_1 AS berat_tbs, sptbs.berondolan_timbang_1 AS berat_brondolan
            FROM
                tbl_sptbs_setelah_slip_timbang sptbs
                LEFT JOIN tbl_blok blok ON sptbs.id_blok_no_1 = blok.id
            WHERE
                sptbs.tanggal BETWEEN '" . $dari_tgl . "' AND '" . $per_tgl . "'
                AND blok.id_afdeling = '" . $this->input->post("id_afdeling") . "' AND blok.blok = '" . $this->input->post("blok") . "'
            
            UNION ALL
            
            SELECT
                sptbs.id, sptbs.id_blok_no_2, blok.blok,
                sptbs.berat_no_2, sptbs.berondolan_timbang_2
            FROM
                tbl_sptbs_setelah_slip_timbang sptbs
                LEFT JOIN tbl_blok blok ON sptbs.id_blok_no_2 = blok.id
            WHERE
                sptbs.tanggal BETWEEN '" . $dari_tgl . "' AND '" . $per_tgl . "'
                AND blok.id_afdeling = '" . $this->input->post("id_afdeling") . "' AND blok.blok = '" . $this->input->post("blok") . "'
            
            UNION ALL
            
            SELECT
                sptbs.id, sptbs.id_blok_no_3, blok.blok,
                sptbs.berat_no_3, sptbs.berondolan_timbang_3
            FROM
                tbl_sptbs_setelah_slip_timbang sptbs
                LEFT JOIN tbl_blok blok ON sptbs.id_blok_no_3 = blok.id
            WHERE
                sptbs.tanggal BETWEEN '" . $dari_tgl . "' AND '" . $per_tgl . "'
                AND blok.id_afdeling = '" . $this->input->post("id_afdeling") . "' AND blok.blok = '" . $this->input->post("blok") . "'
        ");
        foreach($ds_realisasi_panen->result() as $realisasi) {
            $realisasi_tbs += $realisasi->berat_tbs;
            $realisasi_brondolan += $realisasi->berat_brondolan;
        }

        $this->db->close();

        $result_data = array();
        if($target_panen == 0) {
            $result_data = array(
                "target_panen" => "N/A",
                "target_sd_hari_ini" => "N/A",
                "hasil_tbs_kg" => "N/A",
                "hasil_tbs_persen" => "N/A",
                "hasil_brondolan_kg" => "N/A",
                "hasil_brondolan_persen" => "N/A"
            );
        } else {
            $target_sd_hari_ini = ($target_panen / $total_hari) * $jumlah_hari;
            $persen_tbs = ($realisasi_tbs * 100) / $target_sd_hari_ini;
            $persen_brondolan = ($realisasi_tbs == 0) ? 0 : (($realisasi_brondolan * 100) / $realisasi_tbs);
            $result_data = array(
                "target_panen" => number_format($target_panen, 2),
                "target_sd_hari_ini" => number_format($target_sd_hari_ini, 2),
                "hasil_tbs_kg" => number_format($realisasi_tbs, 2),
                "hasil_tbs_persen" => number_format($persen_tbs, 2),
                "hasil_brondolan_kg" => number_format($realisasi_brondolan, 2),
                "hasil_brondolan_persen" => number_format($persen_brondolan, 2)
            );
        }
        

        self::json($result_data);
    }
    
}
