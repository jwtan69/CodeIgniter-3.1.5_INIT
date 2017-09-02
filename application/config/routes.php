<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['^(en|cn)/phpinfo'] = 'welcome/phpinfo';

//VO
$route['^(en|cn)/vo'] = "VO";
$route['^(en|cn)/vo/login'] = 'VO_Login/index';
$route['^(en|cn)/vo/index'] = 'VO_Index/index';

//Upload handle
$route['^(en|cn)/vo/upload_handler'] = "VO_Upload_Handler"; 

//Users
$route['^(en|cn)/vo/users/list'] = 'VO_Users/index';
$route['^(en|cn)/vo/users/list/(:any)/(:any)'] = "VO_Users/index/$2/$3";
$route['^(en|cn)/vo/users/list/(:any)/(:any)/(:num)'] = "VO_Users/index/$2/$3/$4";
$route['^(en|cn)/vo/users/add'] = "VO_Users/add"; 
$route['^(en|cn)/vo/users/edit/(:num)'] = "VO_Users/edit/$2";
$route['^(en|cn)/vo/users/submit'] = "VO_Users/submit"; 
$route['^(en|cn)/vo/users/delete/(:num)'] = "VO_Users/delete/$2";

//API
$route['^(en|cn)/api/login'] = "API_Manage/login";
$route['^(en|cn)/api/logout/(:any)'] = "API_Manage/logout/$2";