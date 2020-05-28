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
// $route['default_controller'] = 'Welcome'; //admin login view  
$route['default_controller'] = 'Welcome'; //admin login view  
$route['404_override'] = 'page_not_found';
$route['translate_uri_dashes'] = FALSE;

$route['admin/settings'] 					= 'admin/Other/settings';

$route['admin'] 					= 'admin/Dashboard';
$route['admin/edit_profile'] 		= 'admin/login/edit_profile';
$route['admin/user_list'] 			= 'admin/login/user_list';
$route['admin/add_user'] 			= 'admin/login/add_user';
$route['admin/change_password'] 	= 'admin/login/change_password';
 
$route['admin/user/list'] 			= 'admin/User/users_list';
$route['admin/user/list/:num'] 	= 'admin/User/users_list/:num';
$route['admin/user/add'] 			= 'admin/User/add_user';
$route['admin/user/add/(:num)'] 			= 'admin/User/add_user/$1';
$route['admin/user/edit/(:num)'] 	= 'admin/User/edit_user/$1';
$route['admin/user/edit'] 			= 'admin/User/edit_user';

$route['admin/subadmin/list'] 			= 'admin/User/admin_list';
$route['admin/subadmin/list/:num'] 	= 'admin/User/admin_list/:num';
$route['admin/subadmin/add'] 			= 'admin/User/add_admin';
$route['admin/subadmin/add/(:num)'] 			= 'admin/User/add_admin/$1';
$route['admin/subadmin/edit/(:num)'] 	= 'admin/User/edit_admin/$1';
$route['admin/subadmin/edit'] 			= 'admin/User/edit_admin';

$route['admin/media/list'] 		= 'admin/Other/media_list';
$route['admin/media/list/:num'] 	= 'admin/Other/media_list/:num';
$route['admin/media/add'] 			= 'admin/Other/add_media';
$route['admin/media/edit/(:num)'] 	= 'admin/Other/edit_media/$1';
$route['admin/media/edit'] 		= 'admin/Other/edit_media';

$route['admin/categories/list'] 		= 'admin/Other/category_list';
$route['admin/categories/list/:num'] 	= 'admin/Other/category_list/:num';
$route['admin/categories/add'] 			= 'admin/Other/add_category';
$route['admin/categories/edit/(:num)'] 	= 'admin/Other/edit_category/$1';
$route['admin/categories/edit'] 		= 'admin/Other/edit_category';
 
$route['admin/sub_categories/list'] 		= 'admin/Other/subcategory_list';
$route['admin/sub_categories/list/:num'] 	= 'admin/Other/subcategory_list/:num';
$route['admin/sub_categories/add'] 			= 'admin/Other/add_subcategory';
$route['admin/sub_categories/edit/(:num)'] 	= 'admin/Other/edit_subcategory/$1';
$route['admin/sub_categories/edit'] 		= 'admin/Other/edit_subcategory';

 
$route['admin/pages/list'] 			= 'admin/Cmscontent/pages_list';
$route['admin/pages/list/:num'] 	= 'admin/Cmscontent/pages_list/:num';
$route['admin/pages/add'] 			= 'admin/Cmscontent/add_page';
$route['admin/pages/edit/(:num)'] 	= 'admin/Cmscontent/edit_page/$1';
$route['admin/pages/edit'] 			= 'admin/Cmscontent/edit_page';



$route['admin/notification/add'] 			= 'admin/Cmscontent/notification_add';
$route['admin/notification'] 			= 'admin/Cmscontent/notification';

$route['admin/user/appointment/(:num)'] 			= 'admin/User/appointment/$1';


$route['admin/user/feedback/(:num)'] 			= 'admin/User/feedback/$1';


$route['admin/user/feedback/view/(:num)'] 			= 'admin/User/feedback_view/$1';


$route['admin/advertisement/add'] 			= 'admin/Cmscontent/advertisement_add';
$route['admin/advertisement'] 			= 'admin/Cmscontent/advertisement';

// $route['admin/faq/list'] 		= 'admin/Cmscontent/faq_list';
// $route['admin/faq/list/:num'] 	= 'admin/Cmscontent/faq_list/:num';
// $route['admin/faq/add'] 		= 'admin/Cmscontent/add_faq';
// $route['admin/faq/edit/(:num)'] = 'admin/Cmscontent/edit_faq/$1';
// $route['admin/faq/edit'] 		= 'admin/Cmscontent/edit_faq';



// $route['admin/banner/image'] 			= 'admin/Cmscontent/banners_list';
// $route['admin/banner/image/:num'] 	= 'admin/Cmscontent/banners_list/:num';
// $route['admin/banner/add'] 			= 'admin/Cmscontent/add_banner';
// $route['admin/banner/edit/(:num)']	= 'admin/Cmscontent/edit_banner/$1';
// $route['admin/banner/edit'] 			= 'admin/Cmscontent/edit_banner';

 

// $route['admin/post/details/:num'] 		= 'admin/Post/post_view/:num';
  
 
$route['admin/forgot_password'] 		= 'admin/login/forgot_password';
$route['admin/reset_password'] 			= 'admin/login/reset_password';


// $route['admin/template']				= 'admin/user/template';
// $route['admin/edit_template/(:num)'] 	= 'admin/user/edit_template/$1';


$route['privacy_policy'] 	= 'Welcome/privacy_policy';



$route['contact_us'] 	= 'Welcome/contact_us';





$route['admin/logout'] 		= 'admin/login/logout';





// Front End 

$route['signup'] 		= 'Welcome/signup';
$route['check_user_mobile'] 		= 'Welcome/check_user_mobile';
$route['chat'] 		= 'User/chat';
$route['chattinglist'] 		= 'User/chattinglist';
$route['chat_history'] 		= 'User/chat_history';
$route['user_chating'] 		= 'User/user_chating';
$route['get_message'] 		= 'User/get_message';
$route['notification/list'] 		= 'User/notification_list';
$route['advertisement/list'] 		= 'User/advertisement_list';


$route['logout'] 		= 'Welcome/logout';


$route['verify_account'] 		= 'Welcome/verify_account';
$route['doctor/profile/(:num)'] 		= 'User/doctor_profile/$1';
$route['upload_media'] 		= 'User/upload_media';
$route['doctor/list/(:num)'] 		= 'User/doctor_list/$1';


$route['appointment/list'] 		= 'User/appointment_list';
$route['feedback/list'] 		= 'User/feedback_list';
$route['feedback/(:num)'] 		= 'User/feedback_view/$1';

$route['edit_profile'] 		= 'User/edit_profile';
$route['change_password'] 		= 'User/change_password';
$route['add_feedback'] 		= 'User/add_feedback';
$route['delete_question'] 		= 'User/delete_question';
$route['give_feedback/(:num)'] 		= 'User/give_feedback/$1';
$route['give_feedback_ajax'] 		= 'User/give_feedback_ajax';
$route['call/(:num)'] 		= 'User/call/$1';
$route['check_call'] 		= 'User/check_call';
$route['forgot_password'] 		= 'Welcome/forgot_password';
$route['privacy_policy'] 		= 'Welcome/privacy_policy_web';
$route['about_us'] 		= 'Welcome/about_us';
$route['term_and_condition'] 		= 'Welcome/term_and_condition';




