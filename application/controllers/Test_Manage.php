<?php

//require_once APPPATH . 'third_party/SES/SimpleEmailService.php';
//require_once APPPATH . 'third_party/SES/SimpleEmailServiceMessage.php';
//require_once APPPATH . 'third_party/SES/SimpleEmailServiceRequest.php';

class Test_Manage extends CI_Controller {

    public $data = array();

    public function __construct() {
            parent::__construct();
            
    }

    public function index(){

        $url = 'http://www.banana866.com';
        $contents = file_get_contents($url);
        $contents = str_replace(array("\r","\n","\t","\s"), '', $contents);   

        //取得第一個img標籤，並儲存至陣列match（regex語法與上述同義）
        preg_match('/<title>(.+)<\/title>/i', $contents, $match);
        //preg_match('/<div[^>]*id="PostContent"[^>]*>(.*?) <\/div>/si',$contents,$match);   
        //印出match
        print_r($match);



    }

}

?>