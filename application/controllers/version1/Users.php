<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is a user controller for the G-Ajiri application
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Vinarq Technologies
 * @license         MIT 
 */
class Users extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['isRegistered_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['isUsernameRegistered_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['usersUpdate_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['usersCreate_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['usersChangePassword_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['usersValidateLogin_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['usernameLogin_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['usersChangeEmail_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['createUserExperience_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['updateUserExperience_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['getActiveUsers_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getSuspendedUsers_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getUsers_get']['limit'] = 100;
        $this->methods['getUserExperience_get']['limit'] = 100; // 100 requests per hour per user/key
        $this->load->model('Version_one');
        $this->load->model('Experience_v1');

    }
    // check if user exists in the database
    public function isUsernameRegistered_post(){
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('username', 'username', 'required'); 
            if ($this->form_validation->run() == FALSE) {   
                    // $this->some_model->update_user( ... );
                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                
            }else{ 
                $username = $this->input->post('username');
                $ressult = $this->Version_one->isUsernameDuplicate($username);

                if($ressult == TRUE){
                        // $this->some_model->update_user( ... );
                        $message = [
                            'status'=>0,
                            'errorDetails'=> NULL,
                            'data' => 'User exists',
                        ];

                        $this->set_response($message, REST_Controller::HTTP_OK);
                        
                }
                else{
                        $message = [
                            'status'=>2,
                            'errorDetails' => 'Username does not exists',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_OK);
                                         
                };              
            }
    }
    // check if user exists in the database
    public function isRegistered_post(){
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('email', 'email', 'required|valid_email'); 
            if ($this->form_validation->run() == FALSE) {   
                    // $this->some_model->update_user( ... );
                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                
            }else{ 
                $email = $this->input->post('email');
                $ressult = $this->Version_one->isDuplicate($email);

                if($ressult == TRUE){
                        // $this->some_model->update_user( ... );
                        $message = [
                            'status'=>0,
                            'errorDetails'=> NULL,
                            'data' => 'User exists',
                        ];

                        $this->set_response($message, REST_Controller::HTTP_OK);
                        
                }
                else{
                        $message = [
                            'status'=>2,
                            'errorDetails' => 'User does not exists',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_OK);
                                         
                };              
            }
    }

    // registering a user
    public function usersCreate_post()
    {
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('username', 'username', 'required');
            $this->form_validation->set_rules('email', 'email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'password', 'required|min_length[8]');
            $this->form_validation->set_rules('confirmPassword', 'confirmPassword', 'required|matches[password]');    
                       
            if ($this->form_validation->run() == FALSE) {   

                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }else{     
                    $d = [
                        'username' => $this->input->post('username'),
                        'email' => $this->input->post('email'),
                        'password' => $this->input->post('password')
                    ];
                    $ressult = $this->Version_one->insertUser($d);
                    if($ressult == "registered"){
                            $res = $this->Version_one->getRegisteredUser($d);
                            $data = json_decode(json_encode($res),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'data' => $data
                                ];
                            // $message = array_merge($status,$data);
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                            
                    }else if($ressult == 'exists'){

                            $message = [
                                'status'=>2,
                                'errorDetails' => 'User email already exists',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else if($ressult == 'usernameExists'){

                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Username already exists',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }
                    else{
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Failed to register',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    };              
                }           
    }

  // registering a user
    public function usersUpdate_post()
    {
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('userId', 'userId', 'required');
            $this->form_validation->set_rules('firstName', 'firstName', 'required');
            $this->form_validation->set_rules('gender', 'gender', 'required');
            $this->form_validation->set_rules('lastName', 'lastName', 'required');
            $this->form_validation->set_rules('mobileNumber', 'mobileNumber', 'required');    
            $this->form_validation->set_rules('dob', 'dob', 'required');
            $this->form_validation->set_rules('idNumber', 'idNumber', 'required');
            $this->form_validation->set_rules('residentArea', 'residentArea', 'required');     
            if ($this->form_validation->run() == FALSE) {   

                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }else{     
                    $d = [
                        'userId' => $this->input->post('userId'),
                        'firstName' => $this->input->post('firstName'),
                        'gender' => $this->input->post('gender'),
                        'lastName' => $this->input->post('lastName'),
                        'mobileNumber' => $this->input->post('mobileNumber'),
                        'dob' => $this->input->post('dob'),
                        'idNumber' => $this->input->post('idNumber'),
                        'residentArea' => $this->input->post('residentArea'), 
                    ];
                    $ressult = $this->Version_one->userUpdateProfile($d);
                    if($ressult == "updated"){
                            $getUser = $this->Version_one->getUser($d);
                            $data_response = json_decode(json_encode($getUser),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'data' => $data_response                                    
                            ];
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                            
                    }else if($ressult == 'notFound'){

                        $message = [
                            'status'=>2,
                            'errorDetails' => 'User does not exists',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }
                    else{
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Failed to register',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    };              
                }           
    } 

    // users to change password
    public function usersChangePassword_post(){
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('userId', 'userId', 'required');
            $this->form_validation->set_rules('oldPassword', 'oldPassword', 'required');
            $this->form_validation->set_rules('newPassword', 'newPassword', 'required|min_length[8]');
            $this->form_validation->set_rules('confirmNewPassword', 'confirmNewPassword', 'required|matches[newPassword]'); 
            if ($this->form_validation->run() == FALSE) {   
                    // $this->some_model->update_user( ... );
                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                
            }else{ 
                    $d = [
                        'userId' => $this->input->post('userId'),
                        'oldPassword' => $this->input->post('oldPassword'),
                        'newPassword' => $this->input->post('newPassword'),
                    ];
                    $ressult = $this->Version_one->userChangePassword($d);
                    if($ressult == "updated"){
                        $message = [
                            'status'=>0,
                            'errorDetails'=> NULL,
                            'data' => "Password updated successfully"                                    
                        ];
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                            
                    }else if($ressult == 'wrongPassword'){

                        $message = [
                            'status'=>2,
                            'errorDetails' => 'Incorrect current password',
                            'data'=>NULL,
                        ];
                        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else if($ressult == 'notFound'){
                        $message = [
                            'status'=>2,
                            'errorDetails' => 'User does not exists',
                            'data'=>NULL,
                        ];
                        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else{

                        $message = [
                            'status'=>3,
                            'errorDetails' => 'Failed to update password',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    };            
            }
    }
    // users to change email
    public function usersChangeEmail_post(){
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('userId', 'userId', 'required');
            $this->form_validation->set_rules('oldEmail', 'oldEmail', 'required|valid_email');
            $this->form_validation->set_rules('newEmail', 'newEmail', 'required|min_length[8]|valid_email');
            $this->form_validation->set_rules('confirmNewEmail', 'confirmNewEmail', 'required|matches[newEmail]|valid_email'); 
            if ($this->form_validation->run() == FALSE) {   
                    // $this->some_model->update_user( ... );
                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                
            }else{ 
                    $d = [
                        'userId' => $this->input->post('userId'),
                        'oldEmail' => $this->input->post('oldEmail'),
                        'newEmail' => $this->input->post('newEmail'),
                    ];
                    $ressult = $this->Version_one->userChangeEmail($d);
                    if($ressult == "updated"){
                        $message = [
                            'status'=>0,
                            'errorDetails'=> NULL,
                            'data' => "Email updated successfully"                                    
                        ];
                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                            
                    }else if($ressult == 'wrongEmail'){

                        $message = [
                            'status'=>2,
                            'errorDetails' => 'Incorrect current email',
                            'data'=>NULL,
                        ];
                        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else if($ressult == 'notFound'){
                        $message = [
                            'status'=>2,
                            'errorDetails' => 'User does not exists',
                            'data'=>NULL,
                        ];
                        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else{

                        $message = [
                            'status'=>3,
                            'errorDetails' => 'Failed to update email',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    };            
            }
    }
    // Check login credentials
    public function usernameLogin_post(){
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('username', 'username', 'required'); 
            $this->form_validation->set_rules('password', 'password', 'required'); 
            if ($this->form_validation->run() == FALSE) {   
                    // $this->some_model->update_user( ... );
                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                
            }else{ 
                    $d = [
                        'username' => $this->input->post('username'),
                        'password' => $this->input->post('password')
                    ];
                    $result = $this->Version_one->validateUsernameLogin($d);
                    if ($result == "exists") {
                                # code...
                            $res_ = $this->Version_one->getRegisteredWithUsername($d);
                            $data = json_decode(json_encode($res_),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'data' => $data
                                ];
                            // $message = array_merge($status,$data);
                            $this->set_response($message, REST_Controller::HTTP_OK);
                        }else if ($result == "wrongPassword") {
                            # code...
                            $message = [
                                'status'=>2,
                                'errorDetails' => 'User password is incorrect',
                                'data'=>NULL,
                            ];
                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                        }else if ($result == "wrongUsername") {
                            # code...
                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Username does not exist',
                                'data'=>NULL,
                            ];
                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);

                        }else{
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Failed to validate user login credentials',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }
            }
    }
    // Check login credentials
    public function usersValidateLogin_post(){
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('email', 'email', 'required|valid_email'); 
            $this->form_validation->set_rules('password', 'password', 'required'); 
            if ($this->form_validation->run() == FALSE) {   
                    // $this->some_model->update_user( ... );
                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                
            }else{ 
                    $d = [
                        'email' => $this->input->post('email'),
                        'password' => $this->input->post('password')
                    ];
                    $result = $this->Version_one->validateForLogin($d);
                    if ($result == "exists") {
                                # code...
                            $res_ = $this->Version_one->getRegisteredUser($d);
                            $data = json_decode(json_encode($res_),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'data' => $data
                                ];
                            // $message = array_merge($status,$data);
                            $this->set_response($message, REST_Controller::HTTP_OK);
                        }else if ($result == "wrongPassword") {
                            # code...
                            $message = [
                                'status'=>2,
                                'errorDetails' => 'User password is incorrect',
                                'data'=>NULL,
                            ];
                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                        }else if ($result == "wrongEmail") {
                            # code...
                            $message = [
                                'status'=>2,
                                'errorDetails' => 'User with email address not found',
                                'data'=>NULL,
                            ];
                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);

                        }else{
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Failed to validate user login credentials',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }
            }
    }
    // getting all the users
    public function getUsers_get(){
            # code...
            $response = $this->Version_one->allUsers();
            $data = json_decode(json_encode($response),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'jobSeekers' => $data
                      ];
            // $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    }
  // getting all active users
    public function getActiveUsers_get(){
            # code...
            $response = $this->Version_one->getActiveUsers();
            $data = json_decode(json_encode($response),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'jobSeekers' => $data
                      ];
            // $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    } 
  // getting all suspended users
    public function getSuspendedUsers_get(){
            # code...
            $response = $this->Version_one->getSuspendedUsers();
            $data = json_decode(json_encode($response),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'jobSeekers' => $data
                      ];
            // $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    }

    // creating user experience
    public function createUserExperience_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('institutionName', 'institutionName', 'required');
        $this->form_validation->set_rules('grade', 'grade', 'required');
        $this->form_validation->set_rules('yearOfCompletion', 'yearOfCompletion', 'required');
        $this->form_validation->set_rules('jobOne', 'jobOne', 'required');
        $this->form_validation->set_rules('jobOneDuration', 'jobOneDuration', 'required');  
        $this->form_validation->set_rules('jobTwo', 'jobTwo', 'required');
        $this->form_validation->set_rules('jobTwoDuration', 'jobTwoDuration', 'required');
        $this->form_validation->set_rules('jobThree', 'jobThree', 'required');
        $this->form_validation->set_rules('jobThreeDuration', 'jobThreeDuration', 'required');
        $this->form_validation->set_rules('firstReferee', 'firstReferee', 'required');   
        $this->form_validation->set_rules('secondReferee', 'secondReferee', 'required');
        $this->form_validation->set_rules('firstRefereePhone', 'firstRefereePhone', 'required');
        $this->form_validation->set_rules('secondRefereePhone', 'secondRefereePhone', 'required');
        if ($this->form_validation->run() == FALSE) {   

                $message = [
                    'status'=>1,
                    'errorDetails' => validation_errors(),
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }else{     
                $d = [
                    'userId' => $this->uri->segment(4),
                    'institutionName' => $this->input->post('institutionName'),
                    'grade' => $this->input->post('grade'),
                    'yearOfCompletion' => $this->input->post('yearOfCompletion'),
                    'jobOne' => $this->input->post('jobOne'),
                    'jobOneDuration' => $this->input->post('jobOneDuration'),
                    'jobTwo' => $this->input->post('jobTwo'),
                    'jobTwoDuration' => $this->input->post('jobTwoDuration'),
                    'jobThree' => $this->input->post('jobThree'),
                    'jobThreeDuration' => $this->input->post('jobThreeDuration'),
                    'firstReferee' => $this->input->post('firstReferee'),
                    'secondReferee' => $this->input->post('secondReferee'),
                    'firstRefereePhone' => $this->input->post('firstRefereePhone'),
                    'secondRefereePhone' => $this->input->post('secondRefereePhone'),
                ];
                $ressult = $this->Experience_v1->insertUserExperience($d);
                if($ressult == "created"){
                        $res = $this->Experience_v1->getUserExperience($d);
                        $data_response = json_decode(json_encode($res),true);
                        $message = [
                            'status'=>0,
                            'errorDetails'=> NULL,
                            'data' => $data_response                                    
                        ];
                        $this->set_response($message, REST_Controller::HTTP_CREATED); 
                        
                }else if($ressult == 'exists'){

                    $message = [
                        'status'=>2,
                        'errorDetails' => 'Experience already created for this user.',
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                }else if($ressult == 'NotFound'){

                    $message = [
                        'status'=>2,
                        'errorDetails' => 'User does not exist.',
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                }
                else{
                        $message = [
                            'status'=>3,
                            'errorDetails' => 'Failed to creat experience.',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                };              
            }     
    }
    // updating user experience
    public function updateUserExperience_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('institutionName', 'institutionName', 'required');
        $this->form_validation->set_rules('grade', 'grade', 'required');
        $this->form_validation->set_rules('yearOfCompletion', 'yearOfCompletion', 'required');
        $this->form_validation->set_rules('jobOne', 'jobOne', 'required');
        $this->form_validation->set_rules('jobOneDuration', 'jobOneDuration', 'required');  
        $this->form_validation->set_rules('jobTwo', 'jobTwo', 'required');
        $this->form_validation->set_rules('jobTwoDuration', 'jobTwoDuration', 'required');
        $this->form_validation->set_rules('jobThree', 'jobThree', 'required');
        $this->form_validation->set_rules('jobThreeDuration', 'jobThreeDuration', 'required');
        $this->form_validation->set_rules('firstReferee', 'firstReferee', 'required');   
        $this->form_validation->set_rules('secondReferee', 'secondReferee', 'required');
        $this->form_validation->set_rules('firstRefereePhone', 'firstRefereePhone', 'required');
        $this->form_validation->set_rules('secondRefereePhone', 'secondRefereePhone', 'required');
        if ($this->form_validation->run() == FALSE) {   

                $message = [
                    'status'=>1,
                    'errorDetails' => validation_errors(),
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }else{     
                $d = [
                    'userId' => $this->uri->segment(4),
                    'institutionName' => $this->input->post('institutionName'),
                    'grade' => $this->input->post('grade'),
                    'yearOfCompletion' => $this->input->post('yearOfCompletion'),
                    'jobOne' => $this->input->post('jobOne'),
                    'jobOneDuration' => $this->input->post('jobOneDuration'),
                    'jobTwo' => $this->input->post('jobTwo'),
                    'jobTwoDuration' => $this->input->post('jobTwoDuration'),
                    'jobThree' => $this->input->post('jobThree'),
                    'jobThreeDuration' => $this->input->post('jobThreeDuration'),
                    'firstReferee' => $this->input->post('firstReferee'),
                    'secondReferee' => $this->input->post('secondReferee'),
                    'firstRefereePhone' => $this->input->post('firstRefereePhone'),
                    'secondRefereePhone' => $this->input->post('secondRefereePhone'),
                ];
                $ressult = $this->Experience_v1->updateUserExperience($d);
                if($ressult == "updated"){
                        $res = $this->Experience_v1->getUserExperience($d);
                        $data_response = json_decode(json_encode($res),true);
                        $message = [
                            'status'=>0,
                            'errorDetails'=> NULL,
                            'data' => $data_response                                    
                        ];
                        $this->set_response($message, REST_Controller::HTTP_CREATED); 
                        
                }else if($ressult == 'NotFound'){

                    $message = [
                        'status'=>2,
                        'errorDetails' => 'User experience was not found.',
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                }
                else{
                        $message = [
                            'status'=>3,
                            'errorDetails' => 'Failed to creat experience.',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                };              
            }
    }
    // get user experience
    public function getUserExperience_get(){
        $d = [
            'userId' => $this->uri->segment(4),
        ];
        # code...
        $response = $this->Experience_v1->getUserExperience($d);
        $data = json_decode(json_encode($response),true);
        $status = ['status'=>0,
                    'errorDetails'=> NULL,
                    'data' => $data
                  ];
        $message = array_merge($status,$data);
        $this->set_response($status, REST_Controller::HTTP_OK);
    }
}