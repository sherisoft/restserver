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
$route['default_controller'] = 'rest_server';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


#API dev routes
// check if user exists url
$route['dev/v1/users/isUserRegistered'] = 'v1/users/isRegistered';
$route['dev/v1/users/register'] = 'v1/users/usersCreate';
$route['users'] = 'api/example/users';

#API production routes
// users|Job seekers urls
$route['v1/users/isUserRegistered'] = 'version1/users/isRegistered';
$route['v1/users/isUsernameRegistered'] = 'version1/users/isUsernameRegistered';
$route['v1/users/register'] = 'version1/users/usersCreate';
$route['v1/users/updateProfile'] = 'version1/users/usersUpdate';
$route['v1/users/changePassword'] = 'version1/users/usersChangePassword';
$route['v1/users/changeEmail'] = 'version1/users/usersChangeEmail';
$route['v1/users/validateLogin'] = 'version1/users/usersValidateLogin';
$route['v1/users/usernameLogin'] = 'version1/users/usernameLogin';
$route['v1/users'] = 'version1/users/getUsers';
$route['v1/users/active'] = 'version1/users/getActiveUsers';
$route['v1/users/suspended'] = 'version1/users/getSuspendedUsers';
$route['v1/users/cv/:num'] = 'version1/files/uploadCv';
$route['v1/users/avatar/:num'] = 'version1/files/uploadUserImage';

#API production routes
// Employers urls
$route['v1/employers/isEmpRegistered'] = 'version1/employers/isRegistered';
$route['v1/employers/isUsernameRegistered'] = 'version1/employers/isUsernameRegistered';
$route['v1/employers/register'] = 'version1/employers/employersCreate';
$route['v1/employers/updateProfile'] = 'version1/employers/employersUpdate';
$route['v1/employers/registerBusinessDetails'] = 'version1/employers/employersBusinessDetails';
$route['v1/employers/changePassword'] = 'version1/employers/employersChangePassword';
$route['v1/employers/changeEmail'] = 'version1/employers/employersChangeEmail';
$route['v1/employers/validateLogin'] = 'version1/employers/employersValidateLogin';
$route['v1/employers/usernameLogin'] = 'version1/employers/usernameLogin';
$route['v1/employers'] = 'version1/employers/getEmployers';
$route['v1/employers/active'] = 'version1/employers/getActiveEmployers';
$route['v1/employers/suspended'] = 'version1/employers/getSuspendedEmployers';
$route['v1/employers/approveApplication/:num'] = 'version1/applications/approveUserApplication';
$route['v1/employers/rejectApplication/:num'] = 'version1/applications/rejectUserApplication';
$route['v1/employers/avatar/:num'] = 'version1/files/uploadEmployerImage';

#API production routes
// Posts urls
$route['v1/posts/create'] = 'version1/posts/jobCreate';
$route['v1/posts/updatePost'] = 'version1/posts/jobUpdate';
$route['v1/posts'] = 'version1/posts/getPosts';
$route['v1/posts/open'] = 'version1/posts/getOpenJobs';
$route['v1/posts/closed'] = 'version1/posts/getClosedJobs';
$route['v1/posts/suspended'] = 'version1/posts/getSuspendedJobs';
$route['v1/posts/banner/:num'] = 'version1/files/uploadJobPostImage';

#API production routes
// Applications/applicants urls
$route['v1/applications/create/:num'] = 'version1/applications/createApplication';
$route['v1/applications/:num'] = 'version1/applications/getAllApplicants';
$route['v1/applications/active/:num'] = 'version1/applications/getActiveApplicants';
$route['v1/applications/successful/:num'] = 'version1/applications/getSuccessfulApplicants';
$route['v1/applications/unsuccessful/:num'] = 'version1/applications/getUnsuccessfulApplicants';
$route['v1/applications/deleteApplication/:num'] = 'version1/applications/deleteApplication';
$route['v1/applications/userApplications/:num'] = 'version1/applications/getSingleApplicantApplications';

#user experience
$route['v1/experience/createExperience/:num'] = 'version1/users/createUserExperience';
$route['v1/experience/updateExperience/:num'] = 'version1/users/updateUserExperience';
$route['v1/experience/userExperience/:num'] = 'version1/users/getUserExperience';