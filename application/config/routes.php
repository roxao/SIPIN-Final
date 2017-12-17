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
$route['default_controller'] = 'SipinHome';
$route['submit-iin'] = 'submit_iin';
// $route['dashboard'] = 'dashboard/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['user/(:any)'] = 'SipinHome/user/$1';

$route['proses_login_admin'] = 'dashboard/proses_login';

$route['contact-us'] = 'SipinHome/contact_us';
$route['post/(:any)'] = 'SipinHome/cms_post/$1';


$route['Layanan-IIN'] = 'SipinHome/submit_application';
$route['login'] = 'SipinHome/user/login';
$route['registrasi'] = 'SipinHome/user/register';
$route['informasi-iin/file_iso_7812'] = 'SipinHome/file_iso_7812';
$route['informasi-iin/daftar-penerima-iin'] = 'SipinHome/iin_list';
$route['informasi-iin/pengaduan'] = 'SipinHome/complaint';
$route['informasi-iin/(:any)'] = 'SipinHome/cms_post/$1';

$route['penolakan'] = 'submit_iin/step_rejected';

//activation 

$route['aktivasi'] = 'SipinHome/verify';
$route['lupa-kata-sandi'] = 'SipinHome/forgot_password';
$route['reset-kata-sandi'] = 'SipinHome/reset_password';
$route['user-detail'] = 'SipinHome/user_detail';
$route['ganti-kata-sandi'] = 'SipinHome/change_password';
$route['user-login'] = 'SipinHome/login';


$route['survey/(:any)'] = 'submit_iin/survey/$1';
