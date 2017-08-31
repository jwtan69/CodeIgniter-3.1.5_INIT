<?php
//This is the default model that used for every new model
class MY_Model extends CI_Model {

      protected $table_name = "default";

      public function __construct() {
            $this->load->database();              
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

      public function getSUM($column="oz", $where=array()){

        $this->db->select("SUM(".$column.") AS total");
        $query = $this->db->get_where($this->table_name, $where);
        $data = $query->row_array();
        if(!empty($data['total'])) {
          return $data['total'];
        } else {
          return 0;
        }

      }


}

?>