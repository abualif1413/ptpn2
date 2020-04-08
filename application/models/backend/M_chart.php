<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_chart extends MY_Model{

	public function __construct(){
		parent::__construct('tbl_history_panen');
    }
    
    public function model_grafik(){
        $query=$this->db->select('tbl_kebun.nama_kebun,tbl_afdeling.nama_afdeling,SUM(tbl_history_panen.prestasi) as prestasi')
                       ->from('tbl_history_panen')
                       ->join('tbl_kebun','tbl_kebun.id=tbl_history_panen.id_kebun')
                       ->join('tbl_afdeling','tbl_afdeling.id=tbl_history_panen.id_afdeling')
                       ->group_by('tbl_history_panen.id_kebun,tbl_history_panen.id_afdeling')
                       ->order_by('tbl_kebun.id,tbl_afdeling.id','ASC')
                       ->where('MONTH(tbl_history_panen.tanggal)', date('m'))
                       ->get();
        if($query->num_rows() > 0){
            $result[]=array('Kebun & Afdeling', '');
            foreach($query->result() as $data){
                $gabung=$data->nama_kebun.'-'.$data->nama_afdeling;
                $result[]=array($gabung,intval($data->prestasi));
            }
            return $result;
        }
    }

}
