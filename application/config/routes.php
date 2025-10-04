<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//--- LOGIN
$route['authentication'] = 'main/login';
//--- REGISTRATION
$route['registration'] = 'main/registration';

//--- DASHBOARDS
//ADMIN Dashboard
$route['admin/dashboard'] = 'AdminDashboard_controller/admin_dashboard';
//USER Dashboard
$route['users/dashboard'] = 'UsersDashboard_controller/users_dashboard';

//--- USER ROUTES ---//
//--- DATATABLE of USER in MSRF
$route['users/list/tickets/msrf'] = 'UsersMSRF_controller/service_form_msrf_list';
//--- TICKET CREATION of USER for MSRF
$route['users/create/tickets/msrf'] = 'UsersMSRF_controller/users_creation_tickets_msrf';
//clickable link to the details page, msrf
$route['users/details/concern/msrf/(:any)'] = 'UsersMSRF_controller/service_form_msrf_details/$1';


//--- DATATABLE of USER in TRACC CONCERN
$route['users/list/tickets/tracc_concern'] = 'UsersTraccCon_controller/service_form_tracc_concern_list';
//--- TICKET CREATION of USER for TRACC CONCERN
$route['users/create/tickets/tracc_concern'] = 'UsersTraccCon_controller/user_creation_tickets_tracc_concern';
//clickable link to the details page, tracc concern
$route['users/details/concern/tracc_concern/(:any)'] = 'UsersTraccCon_controller/tracc_concern_form_details/$1';

//--- DATATABLE of USER in TRACC REQUEST FORM
$route['users/list/tickets/tracc_request'] = 'UsersTraccReq_controller/service_form_tracc_request_list';
//--- TICKET CREATION of USER for TRACC REQUEST
$route['users/create/tickets/tracc_request'] = 'UsersTraccReq_controller/user_creation_tickets_tracc_request';
//clickable link to the details page, tracc request
$route['users/details/concern/tracc_request/(:any)'] = 'UsersTraccReq_controller/tracc_request_form_details/$1';


//--- FORM CREATION of USER for TRACC REQUEST FORM PDF
$route['users/create/tickets/trf_customer_request_form_tms'] = 'UsersTraccReq_controller/user_creation_tickets_customer_request_forms_tms';
//--- 
$route['users/create/tickets/trf_customer_shipping_setup'] = 'UsersTraccReq_controller/user_creation_tickets_customer_shipping_setup';
//--- 
$route['users/create/tickets/trf_employee_request_form'] = 'UsersTraccReq_controller/user_creation_tickets_employee_request_form';
//---
$route['users/create/tickets/trf_item_request_form'] = 'UsersTraccReq_controller/user_creation_tickets_item_request_form';
//---
$route['users/create/tickets/trf_supplier_request_form_tms'] = 'UsersTraccReq_controller/user_creation_tickets_supplier_request_form_tms';

//--- DETAILS FORM of USER for TRACC REQUEST FORM PDF
$route['users/details/concern/customer_req_form/(:any)'] = 'UsersTraccReq_controller/customer_request_form_rf_details/$1';

$route['users/details/concern/customer_req_ship_setup/(:any)'] = 'UsersTraccReq_controller/customer_request_form_ss_details/$1';

$route['users/details/concern/customer_req_item_req/(:any)'] = 'UsersTraccReq_controller/customer_request_form_ir_details/$1';

$route['users/details/concern/customer_req_employee_req/(:any)'] = 'UsersTraccReq_controller/customer_request_form_er_details/$1';

$route['users/details/concern/customer_req_supplier_req/(:any)'] = 'UsersTraccReq_controller/customer_request_form_sr_details/$1';


//--- ADMIN ROUTES ---//
//--- DATATABLE of ADMIN for MSRF
$route['admin/list/ticket/msrf'] = 'AdminMSRF_controller/admin_list_tickets';

//--- DATATABLE of ADMIN TRACC CONCERN
$route['admin/list/ticket/tracc_concern'] = 'AdminTraccCon_controller/admin_list_tracc_concern';

//--- DATATABLE of ADMIN for TRACC REQUEST
$route['admin/list/ticket/tracc_request'] = 'AdminTraccReq_controller/admin_list_tracc_request';
//--- TICKET CREATION of ADMIN for TRACC REQUEST
$route['admin/create/tickets/tracc_request'] = 'AdminTraccReq_controller/admin_creation_tickets_tracc_request';

//--- DATATABLE of ADMIN for EMPLOYEES/USERS
$route['admin/users'] = 'AdminUsers_controller/admin_users';
//--- DATATABLE of ADMIN for DEPARTMENTS
$route['admin/team'] = 'AdminDept_controller/admin_team';
//--- DATATABLE of ADMIN for MSRF Closed Ticket
$route['admin/list/ticket/msrf_closed'] = 'AdminMSRF_controller/admin_closed_tickets';
//--- DATATABLE of ADMIN for Tracc Concern Closed Ticket
$route['admin/list/ticket/tracc_concerns_closed'] = 'AdminTraccCon_controller/admin_closed_tickets';
//--- DATATABLE of ADMIN for Tracc Request Closed Ticket
$route['admin/list/ticket/tracc_request_closed'] = 'AdminTraccReq_controller/admin_closed_tickets';

//--- DATATABLE OF ADMIN FOR REJECTED TICKETS
//--- MSRF Rejected
$route['admin/list/ticket/msrf_rejected'] = 'AdminMSRF_controller/admin_rejected_tickets';
//--- Tracc Concern Rejected
$route['admin/list/ticket/tracc_concerns_rejected'] = 'AdminTraccCon_controller/admin_rejected_tickets';
//--- Tracc Request Rejected 
$route['admin/list/ticket/tracc_request_rejected'] = 'AdminTraccReq_controller/admin_rejected_tickets';

//--- ADMIN for PRINT REPORTS
$route['admin/print'] = 'AdminGenerateReport_controller/admin_print_report';

//
$route['admin/cutoff'] = 'AdminCutoff_controller/admin_cutoff';

$route['admin/set_cutoff'] = 'AdminCutoff_controller/set_cutoff';

$route['admin/bypass'] = 'AdminCutoff_controller/bypass';

$route['admin/schedule_cutoff'] = 'AdminCutoff_controller/schedule_cutoff';

$route['admin/edit_schedule_cutoff/(:any)'] = 'AdminCutoff_controller/edit_schedule/$1';

$route['admin/delete_schedule_cutoff/(:any)'] = 'AdminCutoff_controller/delete_schedule/$1';

//--- PDF REPORTS VIEWING ADMIN ---//
$route['admin/customer_request_form_pdf'] = 'AdminTraccReq_controller/customer_request_form_pdf_view';
$route['admin/customer_shipping_setup_pdf'] = 'AdminTraccReq_controller/customer_shipping_setup_pdf_view';
$route['admin/employee_request_form_pdf'] = 'AdminTraccReq_controller/employee_request_form_pdf_view';
$route['admin/item_request_form_pdf'] = 'AdminTraccReq_controller/item_request_form_pdf_view';
$route['admin/supplier_request_form_pdf'] = 'AdminTraccReq_controller/supplier_request_form_pdf_view';


//--- TICKET APPROVAL for MSRF and TRACC CONCERN and TRACC REQUEST
$route['admin/approved/(:any)/(:any)'] = 'main/admin_approval_list/$1/$2';

$route['admin/list/closed_tickets/(:any)/(:any)'] = 'main/get_closed_tickets/$1/$2';

$route['admin/list/rejected_tickets/(:any)/(:any)'] = 'main/get_rejected_tickets/$1/$2';


//--- Admin CREATION of EMPLOYEE
$route['admin/add/employee'] = 'AdminUsers_controller/admin_list_employee';
//--- Admin UPDATING of EMPLOYEE
$route['admin/update/employee/(:any)'] = 'AdminUsers_controller/list_update_employee/$1';
//--- Admin DELETING of EMPLOYEE
$route['admin/delete/employee/(:any)'] = 'AdminUsers_controller/employee_delete/$1';


//--- Admin CREATION of DEPARTMENTS
$route['admin/add/department'] = 'AdminDept_controller/admin_list_department';
//--- Admin UPDATING of DEPARTMENTS
$route['admin/update/department/(:num)'] = 'AdminDept_controller/list_update_department/$1';
//--- Admin DELETING of DEPARTMENTS
$route['admin/delete/department/(:any)'] = 'AdminDept_controller/department_delete/$1';

//
$route['Main/download_file/(:any)'] = 'Main/download_file/$1';

//
$route['users/details/concern/customer_req/update/(:any)'] = 'UsersTraccReq_controller/update_customer_request/$1';

$route['users/details/concern/customer_req_employee_req/update/(:any)'] = 'UsersTraccReq_controller/update_employee_request/$1';

$route['users/details/concern/customer_req_supplier_req/update/(:any)'] = 'UsersTraccReq_controller/update_supplier_request/$1';

$route['users/details/concern/customer_req_shipping_setup/update/(:any)'] = 'UsersTraccReq_controller/update_shipping_setup/$1';

$route['users/details/concern/customer_req_item_req/update/(:any)'] = 'UsersTraccReq_controller/update_item_request/$1';


// --- Admin CREATION OF TICKETS ---//
//--- DATATABLE of ADMIN in MSRF
$route['admin/list/creation_tickets/msrf'] = 'AdminMSRF_controller/service_form_msrf_list';
//--- TICKET CREATION of ADMIN for MSRF
$route['admin/create/tickets/msrf'] = 'AdminMSRF_controller/admin_creation_tickets_msrf';
//clickable link to the details page, msrf
$route['admin/details/concern/msrf/(:any)'] = 'AdminMSRF_controller/admin_msrf_details/$1';

//--- DATATABLE of ADMIN in TRACC CONCERN
$route['admin/list/creation_tickets/tracc_concern'] = 'AdminTraccCon_controller/tracc_concern_list';
//--- TICKET CREATION of ADMIN for TRACC CONCERN
$route['admin/create/tickets/tracc_concern'] = 'AdminTraccCon_controller/admin_creation_tickets_tracc_concern';
//clickable link to the details page, TRACC CONCERN
$route['admin/details/concern/tracc_concern/(:any)'] = 'AdminTraccCon_controller/admin_tracc_concern_details/$1';

//--- DATATABLE of ADMIN in TRACC REQUEST
$route['admin/list/creation_tickets/tracc_request'] = 'AdminTraccReq_controller/tracc_request_list';
//--- TICKET CREATION of ADMIN for TRACC CONCERN
$route['admin/create/tickets/tracc_request'] = 'AdminTraccReq_controller/admin_creation_tickets_tracc_request';
//clickable link to the details page, TRACC CONCERN
$route['admin/details/concern/tracc_request/(:any)'] = 'AdminTraccReq_controller/admin_tracc_request_details/$1';



$route['admin/create/tickets/tracc_request/customer_request'] = 'AdminTraccReq_controller/admin_customer_request_form';

$route['admin/create/tickets/tracc_request/shipping_setup'] = 'AdminTraccReq_controller/admin_shipping_setup_form';

$route['admin/create/tickets/tracc_request/employee_request'] = 'AdminTraccReq_controller/admin_employee_request_form';

$route['admin/create/tickets/tracc_request/item_request'] = 'AdminTraccReq_controller/admin_item_request_form';

$route['admin/create/tickets/tracc_request/supplier_request'] = 'AdminTraccReq_controller/admin_supplier_request_form';



//--- System LOGOUT
$route['logout'] = 'main/logout';

$route['default_controller'] = 'main/login';
$route['404_override'] = 'main/login';
$route['translate_uri_dashes'] = FALSE;