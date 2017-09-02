<?php
class Role_model extends CI_Model {

      //to get a key=>value array
      public function getOptions(){

          return array(
          	'-1' => "Admin",
            '0' => "Normal User",
          );

      }


}

?>