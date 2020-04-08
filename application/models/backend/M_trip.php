<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_trip extends MY_Model{

	public function __construct(){
		parent::__construct('tbl_trip');
    }
    
    function no_sptbs($id_kebun,$id_afdeling){
        $this->db->select('Right(tbl_trip.sptbs,3) as kode ',false);
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get('tbl_trip');
        if($query->num_rows()<>0){
            $data = $query->row();
            $kode = intval($data->kode)+1;
        }else{
            $kode = 1;
        }
        $kodemax = str_pad($kode,4,"0",STR_PAD_LEFT);
        $kodejadi  = "PTPN-II-".$id_kebun."-".$id_afdeling."-".$kodemax;
        return $kodejadi;
    }

}
