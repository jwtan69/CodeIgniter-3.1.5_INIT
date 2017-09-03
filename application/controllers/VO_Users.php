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
            "mobile" => "Mobile",
	    )
	);

    //For listing
    public function index($role="ALL",$search_column="ALL", $keyword="ALL",$activated="ALL", $page=1,$pdf=0){

            //activated list
            $this->data['activated_list']  = array(
                'ALL' => 'ALL',
                '1' => 'Yes',
                '0' => 'No',
            );

            $this->data['role'] = $role;
            $this->data['search_column'] = $search_column;
            $this->data['keyword'] = $keyword;
            $this->data['activated'] = $activated;
            
            $this->data['page'] = $page;
            $limit_start = ($page-1)*$this->data['item_per_page'];    

            $sql_where = array();
            $sql_where['is_deleted'] = 0;            

            $sql_like = array();
            //role
            if($role!="ALL"){
                $sql_where['role_id'] = $role;
            }
            //keywords
            if($search_column!="ALL" && $keyword!="ALL") {
                $sql_like[$search_column] = $keyword;
            }

            //activated
            if($activated != 'ALL'){
                $sql_where['activated'] = $activated;
            }

            $this->data["total"] = $this->{$this->data['model_name']}->record_count($sql_where, $sql_like);
            
            $results = array();
            $results = $this->{$this->data['model_name']}->fetch($this->data['item_per_page'], $limit_start, $sql_where, $sql_like);
            $this->data["results"] = $results;
            

            //generate pdf
            if($pdf != 0){
                $this->exportPdf($results);
                exit;
            }

            //echo $this->db->last_query();exit;
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

    private function exportPdf($data){

        // create new PDF document
        $this->load->library("tcpdf");
    
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('');
        $pdf->SetTitle('Users PDF');
        $pdf->SetSubject('');
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // remove default header/footer
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '');
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        //import font
        $fontname = TCPDF_FONTS::addTTFfont('application/libraries/tcpdf/fonts/DroidSansFallback.ttf');


        // set font
        $pdf->SetFont($fontname, '', 9, '', false);
        //$pdf->SetFont('helvetica', '', 7, '', false);


        // add a page
        $pdf->AddPage('P');

        $html = '';

        //header
        $html .= '<table border="1" width="100%" cellpadding="3">';
        $html .= '<tr>';
        $html .= '<th>NO</th>';
        $html .= '<th>Avatar</th>';
        $html .= '<th>Name</th>';
        $html .= '<th>Role</th>';
        $html .= '<th>Email</th>';
        $html .= '<th>Mobile</th>';
        $html .= '<th>Activated</th>';
        $html .= '<th>Created date<br>Modified date</th>';
        $html .= '</tr>';



        foreach($data as $k => $v){
            $html .= '<tr>';
            $html .= '<td>'.($k+1).'</td>';
            $html .= '<td><img width="100px" src="'.$v["avatar"].'"></td>';
            $html .= '<td>'.$v["name"].'</td>';
            $html .= '<td>'.$this->data['rolelist'][$v["role_id"]].'</td>';
            $html .= '<td>'.$v["email"].'</td>';
            $html .= '<td>'.$v["mobile"].'</td>';
            $html .= '<td>'.($v["activated"]==1?'Yes':'No').'</td>';
            $html .= '<td>'.$v["created_date"].'<br><?=$v["modified_date"]?></td>';
            $html .= '</tr>';
        }

        $html .= '</table>'; 

        // print a block of text using Write()
        $pdf->writeHTML($html, true, false, true, false, '');
        

        
        // ---------------------------------------------------------

        //Close and output PDF document
        $output_file = "policy".date("YmdHis").rand(1000,9999).".pdf";
        $pdf->Output($output_file, 'I'); 
        //$pdf->Output("./uploads/".$output_file, 'F'); 

        print_r($data);exit;

    }

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
