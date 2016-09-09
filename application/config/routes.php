<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

$route['myvip'] = "redirect";
$route['myvip/(:any)'] = "redirect";

$route['default_controller'] = "home";
$route['404_override'] = 'pages/error_404';

$route['language'] = "pages/language";

$route['login/admin/(:num)'] = "login/impersonate/$1";
$route['login'] = "login/index";
$route['logout'] = "profile/logout";
$route['signup/linkedin/authorized'] = "signup/linkedin_authorized";
$route['signup/photo/(:any)'] = "signup/photo/$1";
$route['signup/(:any)'] = "signup/index/$1";

$route['password/remind/sent'] = "reminders/remind_sent";
$route['password/remind'] = "reminders/remind";
$route['password/reset/(:any)'] = "reminders/reset/$1";

/*
 * Projects
 */
//$route['projects']                  = "projects/listing";
$route['projects/edit/(:any)']		= "projects/edit/$1";
$route['projects/create']			= "projects/create";
$route['projects/updatename/(:any)']	= "projects/updatename/$1";

// Follow/Followig rules for projects
$route['projects/follow']    = "projects/follow";
$route['projects/unfollow']  = "projects/unfollow";
$route['projects/isfollowing/(:num)']    = "projects/isfollowing/$1";

$route['projects/add_executive/(:any)']	= "projects/add_executive/$1";
$route['projects/update_executive/(:any)']	= "projects/update_executive/$1";
$route['projects/delete_executive/(:any)']	= "projects/delete_executive/$1";

$route['projects/add_organization/(:any)']	= "projects/add_organization/$1";
$route['projects/update_organization/(:any)']	= "projects/update_organization/$1";
$route['projects/delete_organization/(:any)']	= "projects/delete_organization/$1";

$route['projects/add_engineering/(:any)']		= "projects/add_engineering/$1";
$route['projects/update_engineering/(:any)']	= "projects/update_engineering/$1";
$route['projects/delete_engineering/(:any)']	= "projects/delete_engineering/$1";

$route['projects/update_map_/(:any)']	= "projects/update_engineering/$1";
$route['projects/delete_engineering/(:any)']	= "projects/delete_engineering/$1";

$route['projects/add_map_point/(:any)']	= "projects/add_map_point/$1";
$route['projects/update_map_point/(:any)']	= "projects/update_map_point/$1";
$route['projects/delete_map_point/(:any)']	= "projects/delete_map_point/$1";

$route['projects/update_map_draw/(:any)']	= "projects/update_map_draw/$1";

$route['projects/add_design_issue/(:any)']	= "projects/add_design_issue/$1";
$route['projects/update_design_issue/(:any)']	= "projects/update_design_issue/$1";
$route['projects/delete_design_issue/(:any)']	= "projects/delete_design_issue/$1";

$route['projects/add_environment/(:any)']	= "projects/add_environment/$1";
$route['projects/update_environment/(:any)']	= "projects/update_environment/$1";
$route['projects/delete_environment/(:any)']	= "projects/delete_environment/$1";

$route['projects/add_studies/(:any)']		= "projects/add_studies/$1";
$route['projects/update_studies/(:any)']	= "projects/update_studies/$1";
$route['projects/delete_studies/(:any)']	= "projects/delete_studies/$1";

$route['projects/add_fund_sources/(:any)']		= "projects/add_fund_sources/$1";
$route['projects/update_fund_sources/(:any)']	= "projects/update_fund_sources/$1";
$route['projects/delete_fund_sources/(:any)']	= "projects/delete_fund_sources/$1";

$route['projects/add_roi/(:any)']		= "projects/add_roi/$1";
$route['projects/update_roi/(:any)']	= "projects/update_roi/$1";
$route['projects/delete_roi/(:any)']	= "projects/delete_roi/$1";

$route['projects/add_critical_participants/(:any)']		= "projects/add_critical_participants/$1";
$route['projects/update_critical_participants/(:any)']	= "projects/update_critical_participants/$1";
$route['projects/delete_critical_participants/(:any)']	= "projects/delete_critical_participants/$1";

$route['projects/add_regulatory/(:any)']		= "projects/add_regulatory/$1";
$route['projects/update_regulatory/(:any)']	= "projects/update_regulatory/$1";
$route['projects/delete_regulatory/(:any)']	= "projects/delete_regulatory/$1";

$route['projects/add_participants_public/(:any)']		= "projects/add_participants_public/$1";
$route['projects/update_participants_public/(:any)']	= "projects/update_participants_public/$1";
$route['projects/delete_participants_public/(:any)']	= "projects/delete_participants_public/$1";

$route['projects/add_participants_political/(:any)']		= "projects/add_participants_political/$1";
$route['projects/update_participants_political/(:any)']	= "projects/update_participants_political/$1";
$route['projects/delete_participants_political/(:any)']	= "projects/delete_participants_political/$1";

$route['projects/add_participants_companies/(:any)']		= "projects/add_participants_companies/$1";
$route['projects/update_participants_companies/(:any)']	= "projects/update_participants_companies/$1";
$route['projects/delete_participants_companies/(:any)']	= "projects/delete_participants_companies/$1";

$route['projects/add_participants_owners/(:any)']		= "projects/add_participants_owners/$1";
$route['projects/update_participants_owners/(:any)']	= "projects/update_participants_owners/$1";
$route['projects/delete_participants_owners/(:any)']	= "projects/delete_participants_owners/$1";

$route['projects/add_machinery/(:any)']		= "projects/add_machinery/$1";
$route['projects/update_machinery/(:any)']	= "projects/update_machinery/$1";
$route['projects/delete_machinery/(:any)']	= "projects/delete_machinery/$1";

$route['projects/add_procurement_technology/(:any)']		= "projects/add_procurement_technology/$1";
$route['projects/update_procurement_technology/(:any)']	= "projects/update_procurement_technology/$1";
$route['projects/delete_procurement_technology/(:any)']	= "projects/delete_procurement_technology/$1";

$route['projects/add_procurement_services/(:any)']		= "projects/add_procurement_services/$1";
$route['projects/update_procurement_services/(:any)']	= "projects/update_procurement_services/$1";
$route['projects/delete_procurement_services/(:any)']	= "projects/delete_procurement_services/$1";

$route['projects/add_project_files/(:any)']		= "projects/add_project_files/$1";
$route['projects/update_project_files/(:any)']	= "projects/update_project_files/$1";
$route['projects/delete_project_files/(:any)']	= "projects/delete_project_files/$1";

$route['projects/update_project_location/(:any)']   = "projects/update_project_location/$1";

$route['projects/add_financial/(:any)']	= "projects/add_financial/$1";

$route['projects/add_legal/(:any)']	= "projects/add_legal/$1";
$route['projects/add_procurement_process/(:any)']	= "projects/add_procurement_process/$1";

$route['projects/load_tab/(:any)']	= "projects/load_tab/$1";

$route['projects/form_load/(:any)']	= "projects/form_load/$1";
$route['projects/edit_fundamentals/(:any)']	= "projects/edit_fundamentals/$1";
$route['projects/edit_financial/(:any)']	= "projects/edit_financial/$1";
$route['projects/edit_regulatory/(:any)']	= "projects/edit_regulatory/$1";
$route['projects/edit_participants/(:any)']	= "projects/edit_participants/$1";
$route['projects/edit_procurement/(:any)']	= "projects/edit_procurement/$1";
$route['projects/edit_files/(:any)']	= "projects/edit_files/$1";

$route['projects/add_comment/(:any)']	= "projects/add_comment/$1";
$route['projects/delete_comment/(:num)']	= "projects/delete_comment/$1";

$route['projects/upload_projectphoto/(:any)']	= "projects/upload_projectphoto/$1";
$route['projects/topexperts/(:any)']	= "projects/topexperts/$1";
$route['projects/smeexperts/(:any)']	= "projects/smeexperts/$1";

$route['projects/update_orgExpert/(:any)']	= "projects/update_orgExpert/$1";

$route['projects/discussions/create/(:num)'] = "projects/create_discussion/$1";
// An alias for projects/discussions/(:num)/(:num)
// Introduced as a workaround to the typo in notification emails
$route['projects/discussion/(:num)/(:num)'] = "projects/discussion/$1/$2";
$route['projects/discussions/(:num)/(:num)'] = "projects/discussion/$1/$2";
$route['projects/discussions/(:num)'] = "projects/discussions/$1";

$route['projects/(:any)']			= "projects/view/$1";

//
$route['expertise/list']         = "expertise/index";
$route['expertise/list/(:num)']  = "expertise/view/$1";
$route['expertise/(:num)/rate']  = "expertise/rate/$1";
$route['expertise/(:num)/ratings']  = "expertise/ratings/$1";
$route['expertise/(:num)']       = "expertise/view/$1";
//$route['expertise']			        = "expertise/index";

$route['companies/list']            = "expertadvert/index";
$route['companies/(:num)']          = "expertise/view/$1";
$route['companies']                 = "expertadvert/index";
$route['expertadvert/list']         = "expertadvert/index";

$route['jobs/list'] = "jobs/index";
$route['jobs'] = "jobs/index";

// Reroute old forum to new forums
$route['forum']                  = "forums/index";
$route['forum/(:any)']           = "forums/index";

$route['forums/(:num)']          = "forums/show/$1";
$route['forums/list']            = "forums/index";
$route['forums']                 = "forums/index";
$route['forums/projects/(:num)'] = "forums/projects/$1";
$route['forums/experts/(:num)']  = "forums/experts/$1";
$route['forums/projects']        = "forums/index";
$route['forums/experts']         = "forums/index";

// Permalinks
$route['plink/(:num)/(:any)'] = "plink/index/$1/$2";

$route['terms'] = "pages/index/terms";
$route['privacy'] = "pages/index/privacy";
$route['howto'] = "pages/index/howto";
$route['help'] = "pages/index/howto";
$route['sitemap.xml'] = "pages/index/sitemap";

// Public project profiles
$route['p/(:any)'] = "publicprofiles/projects/$1";

// GViP Brazil
$route['brazil/faq'] = "pages/index/brazilfaq";