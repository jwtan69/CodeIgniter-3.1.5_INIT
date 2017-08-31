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
	    'pathname' => "user",
	    'view_foldername' => "VO_User",
	    'id_column' => "user_id",
	    'model_name' => "User_model",
	    'search_columns' => array(
	          "article_variable" => "title",
	        )
	  );

	

}
