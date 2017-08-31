<?php
class Function_model extends CI_Model{

	public function __construct()
	{
            
        $this->load->model('Settings_model');
        $this->load->model('User_model');
        $this->load->model('User_login_token_model');  
              
        //$this->load->model('Article_model');        
            
	}

    public function insertCrontab($data=array()) {            
            $this->db->insert('crontab', $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
    }
    
    public function page_init()
    {   

        $langlist = array();
        $langlist["en"] = "English";
        $langlist["cn"] = "简体中文"; 
        $langlist["zh"] = "繁體中文"; 

        $lngls = array();
        foreach($langlist as $k=>$v) {
            if($k!=$this->lang->lang()) {
                $lngls[$k] = $v;
            }
        }

        //print_r($this->lang->lang());exit;

        /*
        $token = $this->cookie->get("token");
        if(empty($token)) {
            $token = "";
        }
        */
        
        $this->lang->load('langfile');
		$array = array(
            'langu'             => $this->lang->lang(),
            'lang'              => $this->lang->language,
            'lang_js'           => json_encode($this->lang->language),
            'current_url'       => $this->get_lang_chg_url($this->lang->lang()),
            'lang_type'         => ($this->lang->lang() == 'en')?'_en':'',
            'web_data'			=> $this->get_web_setting(),    
            'langname'          => $langlist[$this->lang->lang()],
            'langlist'          => $lngls,
            //'token'             => $token,
		);

        //print_r($array);
        //exit;
        
		return $array;
	}

    
    
    public function get_current_url() {

        $protocol = 'http';
        if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) {
            $protocol .= 's';
            $protocol_port = $_SERVER['SERVER_PORT'];
        } else {
            $protocol_port = 80;
        }

        $host = $_SERVER['HTTP_HOST'];
        $port = $_SERVER['SERVER_PORT'];
        $request = $_SERVER['PHP_SELF'];
        //$query = isset($_SERVER['argv']) ? substr($_SERVER['argv'][0], strpos($_SERVER['argv'][0], ';') + 1) : '';

        $toret = $protocol . '://' . $host . ($port == $protocol_port ? '' : ':' . $port) . $request . (empty($query) ? '' : '?' . $query);

        return $toret;
    }
    
    public function get_lang_chg_url($langu){
        $url = array();
        $url = explode('/',$_SERVER['REQUEST_URI']);        
       
        $string = '';
        foreach($url as $v):
            if($v == $langu){
                $string .= $langu.'/';
            }else if($string != ''){
                $string .= $v.'/';
            }
        endforeach;
        
        return $string;
    }
    
    public function item_per_page()
    {
        return 12;
    }
	
	public function isLogin(){

        $token = $this->cookie->get("token");

        if(!empty($token)) {

            $tokenData = $this->User_login_token_model->getOne(array(
                'token' => $token,
                'expired >' => time(),
            ));
                                 
    		if( !empty($tokenData) ){                      
    			return $tokenData['user_id'];	
    		} else {
    			return false;	
    		}

        } else {
            return false;
        }
	}
	
	public function get_menu() {
		$data = array();
		$data = $this->category_model->get_parent_category('display');
		if(!empty($data)){
			foreach ($data as $k=>$v):
				$data[$k]['submenu'] =  $this->category_model->get_child_category($v['category_id'],'display');
				foreach ($data[$k]['submenu'] as $t=>$a):
					$article = $this->article_model->get_article_use_category($a['category_id']);
					$data[$k]['submenu'][$t]['article'] = $article;
				endforeach;
			endforeach;
		}
        return $data;
    }
	
	public function get_web_setting() {


        

		$array = array();
		$array = array(
			'web_title'			=> $this->Settings_model->get_web_title(),
			'web_footer'		=> $this->Settings_model->get_web_footer(),
			'web_meta'			=> $this->Settings_model->get_web_meta(),
			'web_mobile'		=> $this->Settings_model->get_web_mobile(),
            'web_fax'           => $this->Settings_model->get_web_fax(),
			'web_address'		=> $this->Settings_model->get_web_address(),
			'web_email'			=> $this->Settings_model->get_web_email(),
			'backend_title'	    => $this->Settings_model->get_backend_title(),
            'privacy'           => $this->Settings_model->get_settings(10),
            'terms'           => $this->Settings_model->get_settings(9),
            'offical_fb'        => $this->Settings_model->get_settings(11),   
            'offical_gplus'        => $this->Settings_model->get_settings(12),   
            'offical_youtube'        => $this->Settings_model->get_settings(13),   
            'offical_flickr'        => $this->Settings_model->get_settings(14),   
            'offical_pinterest'        => $this->Settings_model->get_settings(15),   
            //'brief'        => $this->getArticle("BRIEF_INTRODUCTION"),   
            //'findus_title'        => $this->getArticle("FINDUS"),   
            //'findus_desc'        => $this->getArticle("FINDUS_DESC"),   
            'businesshour'        => $this->Settings_model->get_web_bh(),
		);
		return $array;
	}

    public function getArticle($variable) {

        $tmp = $this->Article_model->getOne(array(
            'article_variable' => $variable,
        ));
        return $tmp['content_'.$this->lang->lang()];        

    }
	
	
	public function set_breadcrumb($array, $name, $link)
	{
		$array[] = array(
			'name'	=> $name,
			'link'	=> $link,
		);
		
		return $array;
	}
    
    public function get_paging($item_per_page,$pagenum,$total_item,$page,$url)
	{
	
		$start = (int)(($page-1)/$pagenum)*$pagenum+1;
		$end = $start+$pagenum-1;
		$next = $page+1;
		$pre = $page-1;
		
		$total_page = ceil( $total_item / $item_per_page );
                $paging = '';
		if($total_item > $item_per_page){
                    $paging .= '<ul class="pagination">';

                    if($page > 1){
                            $paging .= '<li><a href="'.$url.'1">&laquo;</a></li>';
                            $paging .= '<li><a href="'.$url.$pre.'">&lsaquo;</li>';
                    }

                    if($total_page <= $pagenum){

                            for($i=$start;$i<=$total_page;$i++){
                                    if($i == $page){

                                            $paging .= '<li class="active"><a href="javascript:void(0)">'.$i.'</a></li>';
                                    }else{

                                            $paging .= '<li><a href="'.$url.$i.'">'.$i.'</a></li>';
                                    }
                            }
                    }else{
                            if($page > 5){
                                    $end = $page+5;
                                    if($end > $total_page){
                                            $end = $total_page;
                                    }

                                    $start = $end - ($pagenum - 1);

                                    for($i=$start;$i<=$end;$i++){
                                            if($i == $page){
                                                    $paging .= '<li class="active"><a href="javascript:void(0)">'.$i.'</a></li>';
                                            }else{
                                                    $paging .= '<li><a href="'.$url.$i.'">'.$i.'</a></li>';
                                            }
                                    }
                            }else{
                                    if($end > $total_page){
                                            $end = $total_page;
                                    }

                                    for($i=$start;$i<=$end;$i++){
                                            if($i == $page){
                                                    $paging .= '<li class="active"><a href="javascript:void(0)">'.$i.'</a></li>';
                                            }else{
                                                    $paging .= '<li><a href="'.$url.$i.'">'.$i.'</a></li>';
                                            }
                                    }
                            }	
                    }

                    if($page < $total_page){
                            $paging .= '<li><a href="'.$url.$next.'">&rsaquo;</a></li>';
                            $paging .= '<li><a href="'.$url.$total_page.'">&raquo;</a></li>';
                    }

                    $paging .= '</ul>';
                }
		
		return $paging;
	}

    public function get_paging_fronted($item_per_page,$pagenum,$total_item,$page,$url)
    {
    
        $start = (int)(($page-1)/$pagenum)*$pagenum+1;
        $end = $start+$pagenum-1;
        $next = $page+1;
        $pre = $page-1;
        
        $total_page = ceil( $total_item / $item_per_page );
                $paging = '';
        if($total_item > $item_per_page){
                    $paging .= '<ul class="pagination pagination-lg">';

                    if($page > 1){
                            $paging .= '<li><a href="'.$url.'1">&laquo;</a></li>';
                            $paging .= '<li><a href="'.$url.$pre.'">&lsaquo;</li>';
                    }

                    if($total_page <= $pagenum){

                            for($i=$start;$i<=$total_page;$i++){
                                    if($i == $page){

                                            $paging .= '<li class="active"><a href="javascript:void(0)">'.$i.'</a></li>';
                                    }else{

                                            $paging .= '<li><a href="'.$url.$i.'">'.$i.'</a></li>';
                                    }
                            }
                    }else{
                            if($page > 5){
                                    $end = $page+5;
                                    if($end > $total_page){
                                            $end = $total_page;
                                    }

                                    $start = $end - ($pagenum - 1);

                                    for($i=$start;$i<=$end;$i++){
                                            if($i == $page){
                                                    $paging .= '<li class="active"><a href="javascript:void(0)">'.$i.'</a></li>';
                                            }else{
                                                    $paging .= '<li><a href="'.$url.$i.'">'.$i.'</a></li>';
                                            }
                                    }
                            }else{
                                    if($end > $total_page){
                                            $end = $total_page;
                                    }

                                    for($i=$start;$i<=$end;$i++){
                                            if($i == $page){
                                                    $paging .= '<li class="active"><a href="javascript:void(0)">'.$i.'</a></li>';
                                            }else{
                                                    $paging .= '<li><a href="'.$url.$i.'">'.$i.'</a></li>';
                                            }
                                    }
                            }   
                    }

                    if($page < $total_page){
                            $paging .= '<li><a href="'.$url.$next.'">&rsaquo;</a></li>';
                            $paging .= '<li><a href="'.$url.$total_page.'">&raquo;</a></li>';
                    }

                    $paging .= '</ul>';
                }
        
        return $paging;
    }
    
    function get_current_page(){
        $url = $_SERVER['REQUEST_URI'];
        $array = array();
        $array = explode('/', $url);

        $three_sections = array(
            "renodo.local",
            "renodocom.my",
            "renodo.my",
            "renodo.aipi2.com",
        );

        if(in_array($_SERVER['HTTP_HOST'], $three_sections)) {
            return $array[2];
        } else {
            return $array[3];
        }

        
    }
	
	public function upload_pdf($filename,$postname)
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'pdf|doc|docx';
        $config['file_name'] = $filename;
        $config['overwrite'] = TRUE;
        $config['max_size'] = '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';   

        $this->load->library('upload', $config);

        if(!is_dir($config['upload_path'])){
            mkdir($config['upload_path'], 0755, TRUE);
        }

        if (!$this->upload->do_upload($postname)){ //Upload file
            redirect("errorhandler"); //If error, redirect to an error page
        }else{
            return $this->upload->data();
        }
    }
    
    public function upload($filename,$postname)
    {

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|ico';
        $config['file_name'] = $filename;
        $config['overwrite'] = TRUE;
        $config['max_size'] = '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';   

        $this->load->library('upload', $config);
        $this->upload->display_errors('<p>', '</p>');

        if(!is_dir($config['upload_path'])){
            mkdir($config['upload_path'], 0755, TRUE);
        }

        $result = $this->upload->do_upload($postname); 

        //echo $result;exit;

        if(!$result) {
            echo $this->upload->display_errors(); 
            //redirect("errorhandler"); //If error, redirect to an error page
            //show_error($result);

        }else{

            return $this->upload->data();
        }


    }

    /*public function multi_upload($filename,$postname)
    {
        
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name'] = $filename;
        $config['overwrite'] = TRUE;
        $config['max_size'] = '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';   
        
        $this->load->library('upload');
        $this->upload->initialize($config);
        
        
        if(!is_dir($config['upload_path'])){
            mkdir($config['upload_path'], 0755, TRUE);
        }
        
        if (!$this->upload->do_multi_upload($postname, TRUE)){ //Upload file
            
            redirect("errorhandler"); //If error, redirect to an error page
        }else{
            
            return $this->upload->get_multi_upload_data();
        }
    }*/
         
    public function img_resize($upload_data,$nwidth,$nheight,$new_path){
        $image_config["image_library"] = "gd2";
        $image_config["source_image"] = $upload_data["full_path"];
        $image_config['create_thumb'] = FALSE;
        $image_config['maintain_ratio'] = false;
        $image_config['new_image'] = $new_path;
        $image_config['quality'] = "100%";
        $image_config['width'] = $nwidth;
        $image_config['height'] = $nheight;
        $dim = (intval($upload_data["image_width"]) / intval($upload_data["image_height"])) - ($image_config['width'] / $image_config['height']);
        $image_config['master_dim'] = ($dim > 0)? "height" : "width";

        $this->load->library('image_lib');
        $this->image_lib->initialize($image_config);

        if(!$this->image_lib->resize()){ //Resize image
            show_error("您上傳的照片無法被壓縮, 請確認您的照片是JPG或PNG的格式."); //If error, redirect to an error page
        }

    }
    
    public function cut_content($string,$length){
        $string = strip_tags($string); //去除HTML標籤
        $sub_content = mb_substr($string, 0, $length, 'UTF-8'); //擷取子字串

        //顯示 "......"
        if (strlen($string) > strlen($sub_content)) $sub_content."...";

        return $sub_content;
    }
    
    public function DateDiff($part, $begin, $end)
    {
        $diff = strtotime($end) - strtotime($begin) ;
       
        $hour=round(($diff)/3600);
        
        return $hour;
    }

    public function dateFormat($date, $type="")
    {
        $tempDate = explode('-', substr($date,0,10));

        $monthFormat = array(
            '01'    => 'January',
            '02'    => 'February',
            '03'    => 'March',
            '04'    => 'April',
            '05'    => 'May',
            '06'    => 'June',
            '07'    => 'July',
            '08'    => 'August',
            '09'    => 'September',
            '10'    => 'October',
            '11'    => 'November',
            '12'    => 'December',
        );

        return $monthFormat[$tempDate[1]].' '.$tempDate[2].'th, '.$tempDate[0];

    }

    public function get_country()
    {
        $country_string = '<option value="AF">Afghanistan</option>
                        <option value="AX">Aland Islands</option>
                        <option value="AL">Albania</option>
                        <option value="DZ">Algeria</option>
                        <option value="AS">American Samoa</option>
                        <option value="AD">Andorra</option>
                        <option value="AO">Angola</option>
                        <option value="AI">Anguilla</option>
                        <option value="AQ">Antarctica</option>
                        <option value="AG">Antigua and Barbuda</option>
                        <option value="AR">Argentina</option>
                        <option value="AM">Armenia</option>
                        <option value="AW">Aruba</option>
                        <option value="AU">Australia</option>
                        <option value="AT">Austria</option>
                        <option value="AZ">Azerbaijan</option>
                        <option value="BS">Bahamas</option>
                        <option value="BH">Bahrain</option>
                        <option value="BD">Bangladesh</option>
                        <option value="BB">Barbados</option>
                        <option value="BY">Belarus</option>
                        <option value="BE">Belgium</option>
                        <option value="BZ">Belize</option>
                        <option value="BJ">Benin</option>
                        <option value="BM">Bermuda</option>
                        <option value="BT">Bhutan</option>
                        <option value="BO">Bolivia</option>
                        <option value="BA">Bosnia and Herzegovina</option>
                        <option value="BW">Botswana</option>
                        <option value="BV">Bouvet Island</option>
                        <option value="BR">Brazil</option>
                        <option value="IO">British Indian Ocean Territory</option>
                        <option value="VG">British Virgin Islands</option>
                        <option value="BN">Brunei</option>
                        <option value="BG">Bulgaria</option>
                        <option value="BF">Burkina Faso</option>
                        <option value="BI">Burundi</option>
                        <option value="KH">Cambodia</option>
                        <option value="CM">Cameroon</option>
                        <option value="CA">Canada</option>
                        <option value="CV">Cape Verde</option>
                        <option value="BQ">Caribbean Netherlands</option>
                        <option value="KY">Cayman Islands</option>
                        <option value="CF">Central African Republic</option>
                        <option value="TD">Chad</option>
                        <option value="CL">Chile</option>
                        <option value="CN">China</option>
                        <option value="CX">Christmas Island</option>
                        <option value="CC">Cocos (Keeling) Islands</option>
                        <option value="CO">Colombia</option>
                        <option value="KM">Comoros</option>
                        <option value="CG">Congo (Brazzaville)</option>
                        <option value="CD">Congo (Kinshasa)</option>
                        <option value="CK">Cook Islands</option>
                        <option value="CR">Costa Rica</option>
                        <option value="HR">Croatia</option>
                        <option value="CU">Cuba</option>
                        <option value="CW">Curaçao</option>
                        <option value="CY">Cyprus</option>
                        <option value="CZ">Czech Republic</option>
                        <option value="DK">Denmark</option>
                        <option value="DJ">Djibouti</option>
                        <option value="DM">Dominica</option>
                        <option value="DO">Dominican Republic</option>
                        <option value="EC">Ecuador</option>
                        <option value="EG">Egypt</option>
                        <option value="SV">El Salvador</option>
                        <option value="GQ">Equatorial Guinea</option>
                        <option value="ER">Eritrea</option>
                        <option value="EE">Estonia</option>
                        <option value="ET">Ethiopia</option>
                        <option value="FK">Falkland Islands</option>
                        <option value="FO">Faroe Islands</option>
                        <option value="FJ">Fiji</option>
                        <option value="FI">Finland</option>
                        <option value="FR">France</option>
                        <option value="GF">French Guiana</option>
                        <option value="PF">French Polynesia</option>
                        <option value="TF">French Southern Territories</option>
                        <option value="GA">Gabon</option>
                        <option value="GM">Gambia</option>
                        <option value="GE">Georgia</option>
                        <option value="DE">Germany</option>
                        <option value="GH">Ghana</option>
                        <option value="GI">Gibraltar</option>
                        <option value="GR">Greece</option>
                        <option value="GL">Greenland</option>
                        <option value="GD">Grenada</option>
                        <option value="GP">Guadeloupe</option>
                        <option value="GU">Guam</option>
                        <option value="GT">Guatemala</option>
                        <option value="GG">Guernsey</option>
                        <option value="GN">Guinea</option>
                        <option value="GW">Guinea-Bissau</option>
                        <option value="GY">Guyana</option>
                        <option value="HT">Haiti</option>
                        <option value="HM">Heard Island and McDonald Islands</option>
                        <option value="HN">Honduras</option>
                        <option value="HK">Hong Kong S.A.R., China</option>
                        <option value="HU">Hungary</option>
                        <option value="IS">Iceland</option>
                        <option value="IN">India</option>
                        <option value="ID">Indonesia</option>
                        <option value="IR">Iran</option>
                        <option value="IQ">Iraq</option>
                        <option value="IE">Ireland</option>
                        <option value="IM">Isle of Man</option>
                        <option value="IL">Israel</option>
                        <option value="IT">Italy</option>
                        <option value="CI">Ivory Coast</option>
                        <option value="JM">Jamaica</option>
                        <option value="JP">Japan</option>
                        <option value="JE">Jersey</option>
                        <option value="JO">Jordan</option>
                        <option value="KZ">Kazakhstan</option>
                        <option value="KE">Kenya</option>
                        <option value="KI">Kiribati</option>
                        <option value="KW">Kuwait</option>
                        <option value="KG">Kyrgyzstan</option>
                        <option value="LA">Laos</option>
                        <option value="LV">Latvia</option>
                        <option value="LB">Lebanon</option>
                        <option value="LS">Lesotho</option>
                        <option value="LR">Liberia</option>
                        <option value="LY">Libya</option>
                        <option value="LI">Liechtenstein</option>
                        <option value="LT">Lithuania</option>
                        <option value="LU">Luxembourg</option>
                        <option value="MO">Macao S.A.R., China</option>
                        <option value="MK">Macedonia</option>
                        <option value="MG">Madagascar</option>
                        <option value="MW">Malawi</option>
                        <option value="MY">Malaysia</option>
                        <option value="MV">Maldives</option>
                        <option value="ML">Mali</option>
                        <option value="MT">Malta</option>
                        <option value="MH">Marshall Islands</option>
                        <option value="MQ">Martinique</option>
                        <option value="MR">Mauritania</option>
                        <option value="MU">Mauritius</option>
                        <option value="YT">Mayotte</option>
                        <option value="MX">Mexico</option>
                        <option value="FM">Micronesia</option>
                        <option value="MD">Moldova</option>
                        <option value="MC">Monaco</option>
                        <option value="MN">Mongolia</option>
                        <option value="ME">Montenegro</option>
                        <option value="MS">Montserrat</option>
                        <option value="MA">Morocco</option>
                        <option value="MZ">Mozambique</option>
                        <option value="MM">Myanmar</option>
                        <option value="NA">Namibia</option>
                        <option value="NR">Nauru</option>
                        <option value="NP">Nepal</option>
                        <option value="NL">Netherlands</option>
                        <option value="AN">Netherlands Antilles</option>
                        <option value="NC">New Caledonia</option>
                        <option value="NZ">New Zealand</option>
                        <option value="NI">Nicaragua</option>
                        <option value="NE">Niger</option>
                        <option value="NG">Nigeria</option>
                        <option value="NU">Niue</option>
                        <option value="NF">Norfolk Island</option>
                        <option value="MP">Northern Mariana Islands</option>
                        <option value="KP">North Korea</option>
                        <option value="NO">Norway</option>
                        <option value="OM">Oman</option>
                        <option value="PK">Pakistan</option>
                        <option value="PW">Palau</option>
                        <option value="PS">Palestinian Territory</option>
                        <option value="PA">Panama</option>
                        <option value="PG">Papua New Guinea</option>
                        <option value="PY">Paraguay</option>
                        <option value="PE">Peru</option>
                        <option value="PH">Philippines</option>
                        <option value="PN">Pitcairn</option>
                        <option value="PL">Poland</option>
                        <option value="PT">Portugal</option>
                        <option value="PR">Puerto Rico</option>
                        <option value="QA">Qatar</option>
                        <option value="RE">Reunion</option>
                        <option value="RO">Romania</option>
                        <option value="RU">Russia</option>
                        <option value="RW">Rwanda</option>
                        <option value="BL">Saint Barthélemy</option>
                        <option value="SH">Saint Helena</option>
                        <option value="KN">Saint Kitts and Nevis</option>
                        <option value="LC">Saint Lucia</option>
                        <option value="MF">Saint Martin (French part)</option>
                        <option value="PM">Saint Pierre and Miquelon</option>
                        <option value="VC">Saint Vincent and the Grenadines</option>
                        <option value="WS">Samoa</option>
                        <option value="SM">San Marino</option>
                        <option value="ST">Sao Tome and Principe</option>
                        <option value="SA">Saudi Arabia</option>
                        <option value="SN">Senegal</option>
                        <option value="RS">Serbia</option>
                        <option value="SC">Seychelles</option>
                        <option value="SL">Sierra Leone</option>
                        <option value="SG">Singapore</option>
                        <option value="SX">Sint Maarten</option>
                        <option value="SK">Slovakia</option>
                        <option value="SI">Slovenia</option>
                        <option value="SB">Solomon Islands</option>
                        <option value="SO">Somalia</option>
                        <option value="ZA">South Africa</option>
                        <option value="GS">South Georgia and the South Sandwich Islands</option>
                        <option value="KR">South Korea</option>
                        <option value="SS">South Sudan</option>
                        <option value="ES">Spain</option>
                        <option value="LK">Sri Lanka</option>
                        <option value="SD">Sudan</option>
                        <option value="SR">Suriname</option>
                        <option value="SJ">Svalbard and Jan Mayen</option>
                        <option value="SZ">Swaziland</option>
                        <option value="SE">Sweden</option>
                        <option value="CH">Switzerland</option>
                        <option value="SY">Syria</option>
                        <option value="TW">Taiwan</option>
                        <option value="TJ">Tajikistan</option>
                        <option value="TZ">Tanzania</option>
                        <option value="TH">Thailand</option>
                        <option value="TL">Timor-Leste</option>
                        <option value="TG">Togo</option>
                        <option value="TK">Tokelau</option>
                        <option value="TO">Tonga</option>
                        <option value="TT">Trinidad and Tobago</option>
                        <option value="TN">Tunisia</option>
                        <option value="TR">Turkey</option>
                        <option value="TM">Turkmenistan</option>
                        <option value="TC">Turks and Caicos Islands</option>
                        <option value="TV">Tuvalu</option>
                        <option value="VI">U.S. Virgin Islands</option>
                        <option value="UG">Uganda</option>
                        <option value="UA">Ukraine</option>
                        <option value="AE">United Arab Emirates</option>
                        <option value="GB">United Kingdom</option>
                        <option value="US">United States</option>
                        <option value="UM">United States Minor Outlying Islands</option>
                        <option value="UY">Uruguay</option>
                        <option value="UZ">Uzbekistan</option>
                        <option value="VU">Vanuatu</option>
                        <option value="VA">Vatican</option>
                        <option value="VE">Venezuela</option>
                        <option value="VN">Vietnam</option>
                        <option value="WF">Wallis and Futuna</option>
                        <option value="EH">Western Sahara</option>
                        <option value="YE">Yemen</option>
                        <option value="ZM">Zambia</option>
                        <option value="ZW">Zimbabwe</option>';

                $matches = array();
                $result = array();

                if(preg_match_all('/value="(.*)".*?>(.*)<\\/option>/', $country_string, $matches)){

                    foreach($matches[1] as $i => $key){
                        $key = html_entity_decode($key);
                        $val = html_entity_decode($matches[2][$i]);
                        $result[$key] = $val;
                    }
                }


                return $result;
    }

    

    public function imageType($type, $url)
    {

        $imgUrl = "";

        if($url != ""){
            //get only image url
            $urltype = explode("uploads/", $url);

            

            switch($type)
            {
                case 'original':
                    if (strpos($urltype[1], '_ORIGINAL') !== false) {
                        $imgUrl = $urltype[1];
                    }else{
                        $temp = explode('.', $urltype[1]);
                        $imgUrl = $temp[0].'_ORIGINAL.'.$temp[1];
                    }
                break;
                case 'thumb':

                    if (strpos($urltype[1], '_thumb') !== false) {
                        $imgUrl = $urltype[1];
                    }else{
                        $temp = explode('.', $urltype[1]);
                        $imgUrl = $temp[0].'_thumb.'.$temp[1];
                    }
                    //print_r($imgUrl);
                break;
                case 'resize':
                    if (strpos($urltype[1], '_thumb') !== false || strpos($urltype[1], '_ORIGINAL') !== false) {
                        $imgUrl = str_replace('_thumb', '', $urltype[1]);
                        $imgUrl = str_replace('_ORIGINAL', '', $urltype[1]);
                    }else{
                        $imgUrl = $urltype[1];
                    }
                break;
            }

            //image does not exist
            if (@getimagesize('uploads/'.$imgUrl) === FALSE){
                $imgUrl = $urltype[1];
            }

            $imgUrl = $urltype[0].'uploads/'.$imgUrl;
        }
        

        return $imgUrl;
    }

    public function accountNo($user_id, $type="U", $stringlength=8){

        $zero = str_repeat("0", $stringlength-strlen($user_id));
        return $type.$zero.$user_id;


    }

    public function file_upload($data,$unlink_ori,$unlink_thumb){

            //把縮圖上到S3
            $this->load->library('s3'); 
                        
            $result = $this->s3->putObject($data['input'], "testbucket696", $data['output_file'], "public-read", array(), array(
                'Content-Type' => 'image/jpeg',
            ));

            if($result){                        
                $public_url = "http://s3-ap-southeast-1.amazonaws.com/testbucket696/".$data['output_file'];
                
                if($unlink_thumb == 1){
                //上了S3之後就刪掉縮圖
                    $result = unlink($data['input']['file']);
                    if(!$result){
                        show_error('cannot delete thumb');  
                    }
                }

                if($unlink_ori == 1){  
                    $result = unlink($data['filename_oripath']);
                    if(!$result){
                        show_error('cannot delete original');   
                    }

                }

                return $public_url;
                
            } else {
                show_error("s3 FAilure");
                return false;
            }



    }

    public function sendMail($from,$to,$subject,$content){

        $this->load->library('email');
        $this->email->clear();  
        $this->email->from($from);
        $this->email->to($to); 
        $this->email->subject($subject);
        $this->email->message($content); 
        $this->email->set_newline("\r\n");              
        $this->email->send();
        echo $this->email->print_debugger();

    }
	
}
?>