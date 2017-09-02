<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VO_Users extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public $data = array(
	    'display_name' => "Users",
	    'pathname' => "users",
	    'view_foldername' => "VO_User",
	    'id_column' => "user_id",
	    'model_name' => "User_model",
	    'search_columns' => array(
	    	"ALL" => "ALL",
	        "name" => "Name",
            "email" => "Email",
	    )
	);

	public function submit(){

        $mode = isset($_POST['mode'])?$_POST['mode']:'';
        $id = isset($_POST['id'])?$_POST['id']:'';
                    
        $array = array(
		  	'role_id'	=> $this->input->post("role_id", true),			               
            'email'     => isset($_POST['email'])?$_POST['email']:'',
            'mobile'    => isset($_POST['mobile'])?$_POST['mobile']:'',                          
            'gender'    => isset($_POST['gender'])?$_POST['gender']:'',
            'name'      => $this->input->post("name", true),    
            'avatar'    => $this->input->post("avatar", true),                  
        );

        if(isset($_POST['password']) && !empty($_POST['password']) && $_POST['password'] == $_POST['repassword']){
            $array['password'] = $this->User_model->generateHash($_POST['password']);
        }
          
        $array['activated'] = $this->input->post("activated", true);

        if($mode == 'Add'){
        	$array['created_date'] = date("Y-m-d H:i:s");
            $this->User_model->insert($array);
            redirect(base_url($this->data['init']['langu'].'/vo/users/list'), 'refresh');
        }else{

        	$array['modified_date'] = date("Y-m-d H:i:s");

            $userdata = $this->User_model->getOne(array(
                'user_id' => $id,
            ));

            if($userdata['role_id'] == 0 && !empty($userdata['become'])) {
                $array['become'] = NULL;
            }

            $this->User_model->update(array('user_id'=>$id),$array);

            $lastpage = $this->session->userdata("lastpage");
            if(!empty($lastpage)) {
                redirect(base_url($lastpage), 'refresh');
            } else {
                redirect(base_url($this->data['init']['langu'].'/vo/users/list'), 'refresh');
            }

        }		  		  		  
             
    }      

	

}
