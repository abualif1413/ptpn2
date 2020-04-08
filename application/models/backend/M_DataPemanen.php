<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_DataPemanen extends MY_Model{

	public function __construct(){
		parent::__construct('tbl_pemanen');
	}

	function barcode(){
        /*$this->db->select('Right(tbl_pemanen.barcode,3) as kode ',false);
        $this->db->order_by('CAST(SUBSTRING_INDEX(barcode,\'-\',-1) AS signed)', 'desc');
        $this->db->limit(1);
        $query = $this->db->get('tbl_pemanen');
        if($query->num_rows()<>0){
            $data = $query->row();
            $kode = intval($data->kode)+1;
        }else{
            $kode = 1;
        }
        $kodemax = str_pad($kode,4,"0",STR_PAD_LEFT);
        $kodejadi  = "PTPN-II-".$kodemax;
        return $kodejadi;*/
        
        $sql = "
        	SELECT
				barcode as kode,
				CAST(SUBSTRING_INDEX(barcode,'-',-1) AS signed) AS ujung
			FROM
				tbl_pemanen
			ORDER BY
				CAST(SUBSTRING_INDEX(barcode,'-',-1) AS signed) DESC
			LIMIT
				0, 1
        ";
		$ds = $this->db->query($sql);
		$ujung = 0;
		foreach($ds->result() as $res) {
			$ujung = $res->ujung;
			break;
		}
		$ujung++;
		$kodemax = str_pad($ujung, 4, "0", STR_PAD_LEFT);
        $kodejadi  = "PTPN-II-".$kodemax;
        return $kodejadi;
    }

}
