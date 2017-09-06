<?php

class User_model extends CI_Model {

      private $table_name = "user";

      public function __construct() {
            $this->load->database();
            date_default_timezone_set("Asia/Taipei");
      }

      public function record_count($where=array(),$where_like=array()) {

          $this->db->select("COUNT(*) AS total");

          if(!empty($where)) {
            $this->db->where($where);
          }

          if(!empty($where_like)) {
            foreach($where_like as $k=>$v) {
              $this->db->like($k,$v);
            }
          }

          $query = $this->db->get($this->table_name);
          $row = $query->row_array();

          return $row['total'];
      }

      public function get_where($where=array(),$where_like=array(), $order_by="user.created_date", $ascdesc="DESC")
      {

          if(!empty($where)) {
            $this->db->where($where);
          }

          if(!empty($where_like)) {
            foreach($where_like as $k=>$v) {
              $this->db->like($k,$v);
            }
          }


          $this->db->order_by($order_by,$ascdesc);
          $query = $this->db->get($this->table_name);
          if ($query->num_rows() > 0) {
              return $query->result_array();
          } else {
              return array();
          }
           
      }

      public function getOne($where=array())
      {
          if(!empty($where)) {
            $this->db->where($where);
          }
          $query = $this->db->get($this->table_name);
          if ($query->num_rows() > 0) {
              return $query->row_array();
          } else {
              return array();
          }
           
      }
      
      public function fetch($limit, $start, $where=array(), $where_like=array(), $order_by="created_date", $ascdesc="ASC") {
          
          $this->db->select("*");

          if(isset($where['supplier_type_relation.is_deleted'])){
             $this->db->join('supplier_type_relation', 'supplier_type_relation.supplier_id= user.user_id');
          }

          if(!empty($where)) {
            $this->db->where($where);
          }

          if(!empty($where_like)) {
            foreach($where_like as $k=>$v) {
              $this->db->like($k,$v);
            }
          }
          
          $this->db->order_by($order_by,$ascdesc);
          $this->db->limit($limit, $start);
          $query = $this->db->get($this->table_name);
            
          if ($query->num_rows() > 0) {
            return $query->result_array();
          } else {
            return array();
          }

      }

      //insert a new user
      public function insert($insert_array) {
          $this->db->insert($this->table_name, $insert_array);
          $insert_id = $this->db->insert_id();
          return $insert_id;
      }

      //update a data
      public function update($where=array(),$update_array) {

          $this->db->where($where);
			    $this->db->update($this->table_name, $update_array);
          
      }

      //delete a data
      public function delete($id){
        $data = array(
            'is_deleted' => 1,
        );
        $this->db->where($id);
        $this->db->update($this->table_name, $data);
      }

      public function generateHash($password) {

        /*
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
          $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
          return crypt($password, $salt);
        } 
        */

        $options = [
            'cost' => 10,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);

      }

      public function verify($password, $hashedPassword) {

        //return crypt($password, $hashedPassword) == $hashedPassword;
        return password_verify($password, $hashedPassword);

      }

      //generate code for serial usage
      public function randCodeGen() {
        return rand(10000, 99999);
      }

      //To confirm whether the code and the id is match
      public function getPwdResetCode($code, $user_id) {
            $query = $this->get_where(array(
                'user_id' => $user_id,    
                'pwd_retrieval'    => $code,
                   ));


            if(!empty($query)) {
                  return TRUE;
            } else {
                  return FALSE;
            }
      }
      
      //To set the retrieval code of a user
      public function updatePwdResetCode($pwd_retrieval, $user_id){
             $data = array(                
                'pwd_retrieval' => $pwd_retrieval,
                'modified_date' => date("Y-m-d H:i:s"),
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('user', $data);
      }
      
      //To activate a user
      public function activateUser($user_id){
             $data = array(                
                'activated' => 1,
                'modified_date' => date("Y-m-d H:i:s"),
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('user', $data);
      }

      //to get a key=>value array
      public function getOptions($where=array(), $key="user_id", $value="name", $addicolumn="usd_account_no", $order_by="created_date", $ascdec="DESC"){

          $this->db->order_by($order_by,$ascdec);
          $list = $this->get_where($where);

          $tmp = array();

          if(!empty($list)) {
            foreach($list as $v) {
              $tmp[$v[$key]] = $v[$value].": ".$v[$addicolumn];
            }
          }

          return $tmp;

      }


	  

}

?>