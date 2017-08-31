<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VO_Login extends CI_Controller {

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

	public function __construct()
	{
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Function_model');

        $this->data['init'] = $this->Function_model->page_init();
        //This section is all about user logged in information
        //we do it in constructor, so every method will call this once
        //and use in every pages
        $this->data['webpage'] = $this->Function_model->get_web_setting();
        $this->data['islogin'] = $this->Function_model->isLogin();

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

        }

    }

	public function index()
	{

        //print_r($this->data['init']);
        //print_r(get_config());
        //exit;

		$this->load->view('VO_Login', $this->data);
	}


}
