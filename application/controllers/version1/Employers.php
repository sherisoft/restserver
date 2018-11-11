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
class Employers extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['isRegistered_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['isUsernameRegistered_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['employersUpdate_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['employersCreate_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['employersChangePassword_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['employersValidateLogin_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['usernameLogin_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['employersBusinessDetails_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['employersChangeEmail_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getActiveEmployers_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getSuspendedEmployers_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getEmployers_get']['limit'] = 100;
        $this->load->model('Employer_v1');

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
                $ressult = $this->Employer_v1->isDuplicate($email);

                if($ressult == TRUE){
                        // $this->some_model->update_user( ... );
                        $message = [
                            'status'=>0,
                            'errorDetails'=> NULL,
                            'data' => 'Employer exists',
                        ];

                        $this->set_response($message, REST_Controller::HTTP_OK);
                        
                }
                else{
                        $message = [
                            'status'=>2,
                            'errorDetails' => 'Employer does not exists',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_OK);
                                         
                };              
            }
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
                $ressult = $this->Employer_v1->isUsernameDuplicate($username);

                if($ressult == TRUE){
                        // $this->some_model->update_user( ... );
                        $message = [
                            'status'=>0,
                            'errorDetails'=> NULL,
                            'data' => 'Username exists',
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
    // registering a user
    public function employersCreate_post()
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
                    $ressult = $this->Employer_v1->insertEmployer($d);
                    if($ressult == "registered"){
                            $res = $this->Employer_v1->getRegisteredEmployer($d);
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
                                'errorDetails' => 'Employer email already exists',
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
    public function employersUpdate_post()
    {
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('employerId', 'employerId', 'required');
            $this->form_validation->set_rules('firstName', 'firstName', 'required');
            $this->form_validation->set_rules('lastName', 'lastName', 'required');
            $this->form_validation->set_rules('mobileNumber', 'mobileNumber', 'required');    
            $this->form_validation->set_rules('about', 'about', 'required');    
            if ($this->form_validation->run() == FALSE) {   

                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }else{     
                    $d = [
                        'employerId' => $this->input->post('employerId'),
                        'firstName' => $this->input->post('firstName'),
                        'lastName' => $this->input->post('lastName'),
                        'mobileNumber' => $this->input->post('mobileNumber'),
                        'about' => $this->input->post('about'),
                    ];
                    $ressult = $this->Employer_v1->employerUpdateProfile($d);
                    if($ressult == "updated"){
                            $res = $this->Employer_v1->getEmployer($d);
                            $data_response = json_decode(json_encode($res),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'data' => $data_response                                    
                            ];
                            $this->set_response($message, REST_Controller::HTTP_CREATED); 
                            
                    }else if($ressult == 'notFound'){

                        $message = [
                            'status'=>2,
                            'errorDetails' => 'Employer does not exists',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
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
    public function employersBusinessDetails_post()
    {
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('employerId', 'employerId', 'required');
            $this->form_validation->set_rules('businessName', 'businessName', 'required');
            $this->form_validation->set_rules('businessLocation', 'businessLocation', 'required');
            $this->form_validation->set_rules('industry', 'industry', 'required');
            $this->form_validation->set_rules('businessRegNo', 'businessRegNo', 'required');       
            if ($this->form_validation->run() == FALSE) {   

                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }else{     
                    $d = [
                        'employerId' => $this->input->post('employerId'),
                        'businessName' => $this->input->post('businessName'),
                        'businessLocation' => $this->input->post('businessLocation'),
                        'industry' => $this->input->post('industry'),
                        'businessRegNo' => $this->input->post('businessRegNo'),
                    ];
                    $ressult = $this->Employer_v1->employerUpdateBusiness($d);
                    if($ressult == "updated"){
                            $res = $this->Employer_v1->getEmployer($d);
                            $data_response = json_decode(json_encode($res),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'data' => $data_response                                    
                            ];
                            $this->set_response($message, REST_Controller::HTTP_CREATED); 
                            
                    }else if($ressult == 'notFound'){

                        $message = [
                            'status'=>2,
                            'errorDetails' => 'Employer does not exists',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
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
    public function employersChangePassword_post(){
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('employerId', 'employerId', 'required');
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
                        'employerId' => $this->input->post('employerId'),
                        'oldPassword' => $this->input->post('oldPassword'),
                        'newPassword' => $this->input->post('newPassword'),
                    ];
                    $ressult = $this->Employer_v1->employerChangePassword($d);
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
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    }else if($ressult == 'notFound'){
                        $message = [
                            'status'=>2,
                            'errorDetails' => 'Employer does not exists',
                            'data'=>NULL,
                        ];
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
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
    public function employersChangeEmail_post(){
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('employerId', 'employerId', 'required');
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
                        'employerId' => $this->input->post('employerId'),
                        'oldEmail' => $this->input->post('oldEmail'),
                        'newEmail' => $this->input->post('newEmail'),
                    ];
                    $ressult = $this->Employer_v1->employerChangeEmail($d);
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
                            'errorDetails' => 'Employer does not exists',
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
                    $result = $this->Employer_v1->validateUsernameLogin($d);
                    if ($result == "exists") {
                                # code...
                            $res = $this->Employer_v1->getRegisteredEmployerWithUsername($d);
                            $data_response = json_decode(json_encode($res),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'data' => $data_response                                    
                            ];
                            $this->set_response($message, REST_Controller::HTTP_OK);
                        }else if ($result == "wrongPassword") {
                            # code...
                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Employer password is incorrect',
                                'data'=>NULL,
                            ];
                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                        }else if ($result == "wrongUsername") {
                            # code...
                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Employer with username does not exist.',
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
    public function employersValidateLogin_post(){
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
                    $result = $this->Employer_v1->validateForLogin($d);
                    if ($result == "exists") {
                                # code...
                            $res = $this->Employer_v1->getRegisteredEmployer($d);
                            $data_response = json_decode(json_encode($res),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'data' => $data_response                                    
                            ];
                            $this->set_response($message, REST_Controller::HTTP_OK);
                        }else if ($result == "wrongPassword") {
                            # code...
                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Employer password is incorrect',
                                'data'=>NULL,
                            ];
                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                        }else if ($result == "wrongEmail") {
                            # code...
                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Employer with email address not found',
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
    // getting all the employers
    public function getEmployers_get(){
            # code...
            $response = $this->Employer_v1->allEmployers();
            $data = json_decode(json_encode($response),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'employers' => $data
                      ];
            $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    }
  // getting all active employers
    public function getActiveEmployers_get(){
            # code...
            $response = $this->Employer_v1->getActiveEmployers();
            $data = json_decode(json_encode($response),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'employers' => $data
                      ];
            $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    } 
  // getting all suspended employers
    public function getSuspendedEmployers_get(){
            # code...
            $response = $this->Employer_v1->getSuspendedEmployers();
            $data = json_decode(json_encode($response),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'employers' => $data
                      ];
            $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    }
}
