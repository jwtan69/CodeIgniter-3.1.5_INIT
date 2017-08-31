<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class VO extends CI_Controller {
	
	public $data = array();
	
	public function __construct() {
            parent::__construct();
            $this->load->model('User_model');
            $this->load->model('Function_model');
            
            $this->data['init'] = $this->Function_model->page_init();

            $this->data['webpage'] = $this->Function_model->get_web_setting();
            $this->data['islogin'] = $this->Function_model->isLogin();
            //如果使用者是登入狀態，直接帶去後台的首頁
            if ($this->data['islogin']) {

                  $this->data['userdata'] = $this->User_model->getOne(array(
                        'user_id' => $this->data['islogin'],
                  ));

                  //不是管理者 回到首頁
                  if(!in_array($this->data['userdata']['role_id'], array("-1"))){                        
                        echo "回到首頁";
                        exit;          
                  }else{
                        redirect($this->data['init']['langu'].'/vo/index','refresh'); 
                  }


            }else{
                  redirect($this->data['init']['langu'].'/vo/login','refresh'); 
            }
      }

      public function index(){
            
      }
		
}