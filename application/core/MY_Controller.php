<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
      
      public $data = array(
        'display_name' => "default",
        'pathname' => "default",
        'view_foldername' => "vo_default",
        'id_column' => "id",
        'model_name' => "Default_model",
        'search_columns' => array(
              "title" => "Title"
            )
      );
      
      public function __construct() {
            parent::__construct();
            $this->load->model($this->data['model_name']);            
            $this->load->model('Function_model');
            $this->load->model('Role_model');
            $this->load->model('User_model');
            $this->load->model('User_login_token_model');

            $this->data['init'] = $this->Function_model->page_init();

            //This section is all about user logged in information
            //we do it in constructor, so every method will call this once
            //and use in every pages
            $this->data['item_per_page'] = $this->Function_model->item_per_page();
            $this->data['webpage'] = $this->Function_model->get_web_setting();
            $this->data['islogin'] = $this->Function_model->isLogin();
            $this->data['rolelist'] = $this->Role_model->getOptions();  
            
            
            if ($this->data['islogin']) {

              $this->data['userdata'] = $this->User_model->getOne(array(
                'user_id' => $this->data['islogin'],
              ));

              //不是管理者 回到首頁
              if(!in_array($this->data['userdata']['role_id'], array("-1"))){                        
                echo "回到首頁";
                exit;       
              }

            }else{
                redirect($this->data['init']['langu'].'/vo/login','refresh'); 
            }
            
            

            //What admin can search for?      
            $this->data['search_columns'] = $this->data['search_columns'];


            $this->load->library('ckeditor');
            $this->load->library('ckfinder');
            $this->ckeditor = new CKEditor();
            $this->ckeditor->basePath = base_url().'assets/ckeditor/';
            $this->ckeditor->config['toolbar'] = 'Full';
            $this->ckeditor->config['height'] = '300px';
            CKFinder::SetupCKEditor($this->ckeditor, base_url().'assets/ckfinder/');  


      }
      
      //For listing
      public function index($search_column="ALL", $keyword="ALL", $page=1)
      {

            $this->data['search_column'] = $search_column;
            $this->data['keyword'] = $keyword;
            
            $this->data['page'] = $page;
            $limit_start = ($page-1)*$this->data['item_per_page'];    

            $sql_where = array();
            $sql_where['is_deleted'] = 0;            

            $sql_like = array();
            if($search_column!="ALL" && $keyword!="ALL") {
              $sql_like[$search_column] = $keyword;
            }

            $this->data["total"] = $this->{$this->data['model_name']}->record_count($sql_where, $sql_like);
            
            $results = array();
            $results = $this->{$this->data['model_name']}->fetch($this->data['item_per_page'], $limit_start, $sql_where, $sql_like);
            $this->data["results"] = $results;
            
            //print_r($results);exit;

            $url = base_url().$this->data['init']['langu'].'/vo/'.$this->data['pathname'].'/list/'.$search_column."/".$keyword."/";
            $this->data['paging'] = $this->Function_model->get_paging($this->data['item_per_page'],10,$this->data['total'],$page,$url);
            
            //資料開始/結束號碼
            $this->data['data_start_no'] = count($results)!=0?($page-1)*$this->data['item_per_page']+1:0;
            $this->data['data_end_no'] = count($results)==$this->data['item_per_page']?count($results)*$page:($page-1)*$this->data['item_per_page']+count($results);


            $this->load->view('VO_Header', $this->data);
            $this->load->view($this->data['view_foldername']."/list", $this->data);
            $this->load->view('VO_Footer', $this->data);
      }
      
      public function add()
      {
      
          $this->data['mode'] = 'Add';
          $this->load->view('VO_Header', $this->data);
          $this->load->view($this->data['view_foldername']."/add", $this->data);
          $this->load->view('VO_Footer', $this->data);
      }
      
      public function edit($id)
      {

          $this->data['mode'] = 'Edit';
          
          $this->data['results'] = $this->{$this->data['model_name']}->getOne(array(
            $this->data['id_column'] => $id,
          ));          
          
          $this->load->view('VO_Header', $this->data);
          $this->load->view($this->data['view_foldername']."/add", $this->data);
          $this->load->view('VO_Footer', $this->data);
      }
      
      public function Submit()
      {


          $mode = isset($_POST['mode'])?$_POST['mode']:'';
          $id = isset($_POST['id'])?$_POST['id']:'';
                    
          
          $array = array(
             'title'  => $this->input->post("title", true),       
              //your code
          );
          
          
          if($mode == 'Add'){
              $array['created_date'] = date("Y-m-d H:i:s");
              $this->{$this->data['model_name']}->insert($array);
          }else{
              $array['modified_date'] = date("Y-m-d H:i:s");
              $this->{$this->data['model_name']}->update(array(
                $this->data['id_column'] => $id,
              ),$array);
          }                 
          
          redirect(base_url($this->data['init']['langu'].'/vo/'.$this->data['pathname'].'/list'), 'refresh');
      }
      
      
      public function delete($id)
      {
          $this->{$this->data['model_name']}->delete(array(
            $this->data['id_column'] => $id,
          ));
          redirect(base_url($this->data['init']['langu'].'/vo/'.$this->data['pathname'].'/list'), 'refresh');
      }
  
  


}

?>
