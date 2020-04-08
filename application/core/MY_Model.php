<?php
	/**
	*   @param Create Core Imam Wasmawi, S.Kom
	*	@param Handphone 082165561175
	*	@param Email imam@uma.ac.id
	*	@param Release 17/03/2019
	*	@param Dont's Copyright
	*					
	*/
class MY_Model extends CI_Model
{
	protected $_table;
	protected $_primary_key = 'id';

	public function __construct($table)
	{
		$this->_table = $table;
	}


	
	public function validasiData($where=false){
		if ($where) {
			$this->db->where($where);
		}
		$query=self::getAll();
        if($query->num_rows > 0)
        {
            return $query->result();
        }else{
            return $query->result();
        }
    }


	// Fungsi Core Sukses checkRows
	/**
	*   @param $Select = array('user.email','user.name','user.id');
	*
	*	@param $Where = array('id'=>$id,'nama'=>$nama);
	*
	*	@param $Or_where = array('id'=>$id,'nama'=>$nama);
	*					
	*/

	public function checkRows($Select=false,$Where=false,$Or_where=false){
		if ($Select) {
			$this->db->select($Select);
		}else{
			$this->db->select("*");
		}

		if ($Where) {
			$this->db->where($Where);
		}

		if ($Or_where) {
			$this->db->or_where($Or_where);
		}

		$query=$this->db->get($this->_table);
		if($query->num_rows() > 0)
		{
			return $query->row();
		}else{
			return false;
		}
  	}

  	// Fungsi Core Sukses get_where
	/**
	*   @param $select = array('user.email','user.name','user.id');
	*
	*	@param $where = array('id'=>$id,'nama'=>$nama);
	*
	*	@param $or_where = array('id'=>$id,'nama'=>$nama);
	*
	*	@param $join = array(
	*						array('table','tabel.id_user=user.id','left'),
	*						array('table','tabel.id_user=user.id')
	*						);
	*						
	*	@param $group_by ='id';	
	*
	*	@param $order_by = array(
	*						array('id','DESC'),
	*						array('id')
	*						);						
	*/

	public function get_where($select=false,$where=false,$or_where=false,$join=false,$group_by=false,$order_by=false){

		if ($select) {
			$this->db->select($select);
		}else{
			$this->db->select("*");
		}

		if ($where) {
			$this->db->where($where);
		}

		if ($or_where) {
			$this->db->or_where($or_where);
		}

		if ($join) {
			foreach ($join as $value) {
				if (isset($value[2])) {
					$this->db->join($value[0],$value[1],$value[2]);
				}else{
					$this->db->join($value[0],$value[1]);
				}
			}
		}

		if ($group_by) {
			$this->db->group_by($group_by);
		}

		if ($order_by) {
			foreach ($order_by as $value) {
				if (isset($value[1])) {
					$this->db->order_by($value[0],$value[1]);
				}else{
					$this->db->order_by($value[0]);
				}
			}
		}
		return $this->db->get_where($this->_table)->row_array();
	}

	// Fungsi Core Sukses insert
	/**
	*   @param Fungsi Model Insert Data One Row;
	*						
	*/

	public function insert($data)
	{
		$insert = $this->db->insert($this->_table, $data);
		if ($insert) {
			return $this->db->insert_id();
		}
	}

	// Fungsi Core Sukses insert_batch
	/**
	*   @param Fungsi Model Insert Data Multiple Row;
	*						
	*/

	public function insert_batch($data)
	{
		$this->db->trans_start();
		$insert = $this->db->insert_batch($this->_table, $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}

	// Fungsi Core Sukses insert_multi_table
	/**
	*   @param Fungsi Model Insert Multiple table atau insert lebih dari 1 table;
	*
	*	@param $insert = array(
	*						array('$table','$data'),
	*						array('$table','$data')
	*						);	
	*/

	public function insert_multi_table($insert)
	{
		$this->db->trans_start();
		if ($insert) {
			foreach ($insert as $value) {
				$this->db->insert($value[0], $value[1]);
			}
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}


	public function update_where($where=false,$or_where=false)
	{

		if ($where) {
			$this->db->where($where);
		}

		if ($or_where) {
			$this->db->or_where($or_where);
		}

		$this->db->update($this->_table);
		if ($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function update($data,$where=false,$or_where=false)
	{
		if ($where) {
			$this->db->where($where);
		}

		if ($or_where) {
			$this->db->or_where($or_where);
		}

		$this->db->update($this->_table,$data);
		if ($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}


	// Fungsi Core Sukses update_by_id
	/**
	*   @param Fungsi Model Update Data Berdasarkan Id;
	*						
	*/

	public function update_by_id($field,$primary_values)
	{
		$this->db->where($this->_primary_key,$primary_values);
		$this->db->update($this->_table,$field);
		if ($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function update_by_id2($field,$primary_values,$primary_key)
	{
		$this->db->where($primary_key,$primary_values);
		$this->db->update($this->_table,$field);
		if ($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	// Fungsi Core Sukses delete_id
	/**
	*   @param Fungsi Model Update Data Berdasarkan Id;
	*	@param $where = array('id'=>$id,'nama'=>$nama);				
	*/

	public function deleteWhere($where=false)
	{
		if ($where) {
			$this->db->where($where);
		}
		$this->db->delete($this->_table);
		if ($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function delete_id($primary_values)
	{
		$this->db->where($this->_primary_key,$primary_values);
		$this->db->delete($this->_table);
		if ($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function delete_id2($primary_key,$primary_values)
	{
		$this->db->where($primary_key,$primary_values);
		$this->db->delete($this->_table);
		if ($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	// Fungsi Core Sukses
	/**
	*   @param Get All Data;
	*						
	*/

	public function getAll()
	{
		return $this->db->get($this->_table);  
	}


	public function getAllWhere($where=false)
	{
		if ($where) {
			$this->db->where($where);
		}

		return $this->db->get($this->_table);  
	}

	// Fungsi Core Sukses get_result
	/**
	*   @param $select = array('user.email','user.name','user.id');
	*
	*	@param $where = array('id'=>$id,'nama'=>$nama);
	*
	*	@param $join = array(
	*						array('table','tabel.id_user=user.id','left'),
	*						array('table','tabel.id_user=user.id')
	*						);
	*						
	*	@param $group_by ='id';	
	*
	*	@param $order_by = array(
	*						array('id','DESC'),
	*						array('id')
	*						);						
	*/

	public function get_result($select=false,$where=false,$join=false,$group_by=false,$order_by=false)
	{	
		if ($select) {
			$this->db->select($select);
		}else{
			$this->db->select("*");
		}

		if ($where) {
			$this->db->where($where);
		}

		if ($join) {
			foreach ($join as $value) {
				if (isset($value[2])) {
					$this->db->join($value[0],$value[1],$value[2]);
				}else{
					$this->db->join($value[0],$value[1]);
				}
			}
		}

		if ($group_by) {
			$this->db->group_by($group_by);
		}

		if ($order_by) {
			foreach ($order_by as $value) {
				if (isset($value[1])) {
					$this->db->order_by($value[0],$value[1]);
				}else{
					$this->db->order_by($value[0]);
				}
			}
		}

		$data=self::getAll();
		if($data->num_rows() > 0){
			foreach ($data->result() as $value) {
				$data_result[]=$value;
			}
			return $data_result;
		}
	}


	
	public function get_result2($Select=false,$Where=false,$Join=false,$Group_by=false,$Order_by=false)
	{	
		if ($Select) {
			$this->db->select($Select);
		}else{
			$this->db->select("*");
		}

		if ($Select) {
			$this->db->where($Where);
		}

		if ($Join) {
			foreach ($Join as $value) {
				if (isset($value[2])) {
					$this->db->join($value[0],$value[1],$value[2]);
				}else{
					$this->db->join($value[0],$value[1]);
				}
			}
		}

		if ($Group_by) {
			$this->db->group_by($Group_by);
		}

		if ($Order_by) {
			foreach ($Order_by as $value) {
				if (isset($value[1])) {
					$this->db->order_by($value[0],$value[1]);
				}else{
					$this->db->order_by($value[0]);
				}
			}
		}

		$data=self::getAll();
		if($data->num_rows() > 0){
			foreach ($data->result() as $value) {
				$data_result[]=$value;
			}
			return $data_result;
		}
	}


	
	public function get_row($select=false,$where=false,$join=false,$group_by=false,$order_by=false)
	{	
		if ($select) {
			$this->db->select($select);
		}else{
			$this->db->select("*");
		}

		if ($where) {
			$this->db->where($where);
		}

		if ($join) {
			foreach ($join as $value) {
				if (isset($value[2])) {
					$this->db->join($value[0],$value[1],$value[2]);
				}else{
					$this->db->join($value[0],$value[1]);
				}
			}
		}

		if ($group_by) {
			$this->db->group_by($group_by);
		}

		if ($order_by) {
			foreach ($order_by as $value) {
				if (isset($value[1])) {
					$this->db->order_by($value[0],$value[1]);
				}else{
					$this->db->order_by($value[0]);
				}
			}
		}

		$data=self::getAll();
		return $data->row_array();
	}


	// Fungsi Core Sukses get_id
	/**
	*   @param Get Data By Id;
	*						
	*/
	public function get_id($primary_values)
	{
		$this->db->where($this->_primary_key,$primary_values);
		$data = self::getAll();
		if($data->num_rows() > 0){
			foreach ($data->result() as $value) {
				$data_result[]=$value;
			}
			return $data_result;
		}
	}

	// Fungsi Core Sukses count_all
	/**
	*   @param Get Count_all_results;
	*						
	*/

	public function count_all()
	{
		return $this->db->count_all_results($this->_table);;
	}

	// Fungsi Core Sukses getCountWhere
	/**
	*   @param $select = array('user.email','user.name','user.id');
	*	
	*	@param $where = array('id'=>$id,'nama'=>$nama);
	*	
	*	@param $join = array(
	*						array('table','tabel.id_user=user.id','left'),
	*						array('table','tabel.id_user=user.id')
	*						);
	*						
	*	@param $group_by ='id';	
	*
	*	@param $order_by = array(
	*						array('id','DESC'),
	*						array('id')
	*						);						
	*/

	public function getCountWhere($select=false,$where=false,$join=false,$group_by=false,$order_by=false)
	{
		if ($select) {
			$this->db->select($select);
		}else{
			$this->db->select("*");
		}

		if ($where) {
			$this->db->where($where);
		}

		if ($join) {
			foreach ($join as $value) {
				if (isset($value[2])) {
					$this->db->join($value[0],$value[1],$value[2]);
				}else{
					$this->db->join($value[0],$value[1]);
				}
			}
		}

		if ($group_by) {
			$this->db->group_by($group_by);
		}

		if ($order_by) {
			foreach ($order_by as $value) {
				if (isset($value[1])) {
					$this->db->order_by($value[0],$value[1]);
				}else{
					$this->db->order_by($value[0]);
				}
			}
		}

		return self::count_all($select=false,$where=false,$join=false,$group_by=false,$order_by=false);
	}

	// Fungsi Core Sukses

	/**
	*	@param Library By Imam wasmawi
	*
	*   @param $select = array('user.email','user.name','user.id');
	*	
	*	@param $where = array('id'=>$id,'nama'=>$nama);
	*	
	*	@param $join = array(
	*						array('table','tabel.id_user=user.id','left'),
	*						array('table','tabel.id_user=user.id')
	*						);
	*						
	*					
	*/

	public function setQueryDataTable($search,$select=false,$where=false,$join=false,$group_by=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		
		if ($select) {
			$this->db->select($select);
		}else{
			$this->db->select("*");
		}

		if ($where) {
			$this->db->where($where);
		}

		if ($group_by) {
			$this->db->group_by($group_by);
		}

		if ($join) {
			foreach ($join as $value) {
				if (isset($value[2])) {
					$this->db->join($value[0],$value[1],$value[2]);
				}else{
					$this->db->join($value[0],$value[1]);
				}
			}
		}
		// QUERY DINAMIS
		$this->db->from($this->_table);
		$this->db->group_start()->or_like($search)->group_end();

	}

	// Fungsi Core Sukses

	/**
	*	@param Library By Imam wasmawi				
	*					
	*/
	public function findDataTable($columnsOrderBy,$search,$select=false,$where=false,$join=false,$group_by=false)
	{
		$input = $this->input;	
		$orderBy = false;
		if (isset($_POST['order'])) {
			$valColumnName = $columnsOrderBy[$_POST['order']['0']['column']];
			$valKeyword = $_POST['order']['0']['dir'];
			$orderBy = array($valColumnName." ".$valKeyword);
			$orderBy = implode(",", $orderBy);
		}
		self::setQueryDataTable($search,$select,$where,$join,$group_by);
		$this->db->order_by($orderBy);
		$this->db->limit($input->post("length"),$input->post("start"));
		$data = $this->db->get()->result();
		$no = $input->post("start");
		foreach ($data as $item) {
			$no++;
			$item->no = $no;
		}
		return $data;
	}

	// Fungsi Core Sukses

	/**
	*	@param Library By Imam wasmawi				
	*					
	*/

	public function findDataTableOutput($data=null,$search,$select=false,$where=false,$join=false,$group_by=false)
	{
		$input = $this->input;
		self::setQueryDataTable($search,$select,$where,$join,$group_by);
		$getCount = $this->db->count_all_results();
		$response = new stdClass();
		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $getCount;
		$response->recordsFiltered = $getCount;
		$response->data = $data;
		self::json($response);
	}

	public function json($data = null)
	{
    	$this->output->set_header("Content-Type: application/json; charset=utf-8");
    	$this->output->set_content_type('application/json');
	    $this->output->set_output(json_encode($data));
	}



    



	




















































}