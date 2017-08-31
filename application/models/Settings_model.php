<?php
class Settings_model extends CI_Model{

	private $table_name = "settings";

	public function __construct()
	{
		$this->load->database();
        date_default_timezone_set("Asia/Taipei");
	}
	
	//for counting total data
      public function record_count($where=array(), $like=array()) {

          $this->db->select("COUNT(*) AS total");
          $this->db->where($where);

          if(!empty($like)) {
            foreach($like as $k=>$v) {
              $this->db->like($k, $v);
            }
          }

          $query = $this->db->get($this->table_name);
          $row = $query->row_array();
          return $row['total'];

      }

      //for pagination
      public function fetch($limit, $start, $where=array(), $like=array(), $order_by="created_date", $ascdec="DESC") {
          
          if(!empty($like)) {
            foreach($like as $k=>$v) {
              $this->db->like($k, $v);
            }
          }  
          $this->db->where($where);          
          $this->db->order_by($order_by,$ascdec);
          $this->db->limit($limit, $start);
          $query = $this->db->get($this->table_name);
              
          if ($query->num_rows() > 0) {
            return $query->result_array();
          } else {
            return array();
          }

      }

      //insert a new data
      public function insert($data=array()) {            
            $this->db->insert($this->table_name, $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
      }

      //update a data
      public function update($where=array(),$array=array()) {
            $this->db->where($where);
			      $result = $this->db->update($this->table_name, $array);
            return $result;
      }

      //"Delete" a data
      public function delete($where=array()){
        $data = array(
            'is_deleted' => 1,
        );
        $this->db->where($where);
        $this->db->update($this->table_name, $data);
      }
      
      //get one data
      public function getOne($where=array(), $order_by="created_date", $ascdec="DESC") {
          $this->db->order_by($order_by,$ascdec);
          $query = $this->db->get_where($this->table_name, $where);
          return $query->row_array();
      }

      //get more data
      public function get_where($where=array(), $order_by="created_date", $ascdec="DESC") {
          $this->db->order_by($order_by,$ascdec);
          $query = $this->db->get_where($this->table_name, $where);
          return $query->result_array();
      }

      //to get a key=>value array
      public function getOptions($where=array(), $key="id", $value="title", $order_by="created_date", $ascdec="DESC"){

          $this->db->order_by($order_by,$ascdec);
          $list = $this->get_where($where);

          $tmp = array();

          if(!empty($list)) {
            foreach($list as $v) {
              $tmp[$v[$key]] = $v[$value];
            }
          }

          return $tmp;

      }
	
	public function get_settings($settings_id = FALSE)
	{
		if ($settings_id === FALSE)
		{
			$query = $this->db->get('settings');
			return $query->result_array();
		}
		
		$query = $this->db->get_where('settings', array('id' => $settings_id));
		return $query->row_array();
	}
	
	public function fetch_settings($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("settings");
 
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
	
	public function get_web_title()
	{
		$query = $this->db->get_where('settings', array('id' => 1));
		$data = $query->row_array();
		return $data['value'];
	}
	
	public function get_web_footer()
	{
		$query = $this->db->get_where('settings', array('id' => 2));
		$data = $query->row_array();
		return $data['value'];
	}
	
	public function get_web_meta()
	{
		$query = $this->db->get_where('settings', array('id' => 3));
		$data = $query->row_array();
		return $data['value'];
	}
	
	public function get_web_mobile()
	{
		$query = $this->db->get_where('settings', array('id' => 4));
		$data = $query->row_array();
		return $data['value'];
	}

  public function get_web_fax()
  {
    $query = $this->db->get_where('settings', array('id' => 7));
    $data = $query->row_array();
    return $data['value'];
  }

  public function get_web_bh()
  {
    $query = $this->db->get_where('settings', array('id' => 45));
    $data = $query->row_array();
    return $data['value'];
  }
	
	public function get_web_address()
	{
		$query = $this->db->get_where('settings', array('id' => 5));
		$data = $query->row_array();
		return $data['value'];
	}
	
	public function get_web_email()
	{
		$query = $this->db->get_where('settings', array('id' => 6));
		$data = $query->row_array();
		return $data['value'];
	}
	
	public function get_backend_title()
	{
		$query = $this->db->get_where('settings', array('id' => 3));
		$data = $query->row_array();
		return $data['value'];
	}
    
}
?>