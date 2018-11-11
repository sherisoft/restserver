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

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['usersCreate_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['isRegistered_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->load->model('user_model');

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

                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                
            }else{ 
                $email = $this->input->post('email');
                $ressult = $this->user_model->isDuplicate($email);

                if($ressult == TRUE){
                        // $this->some_model->update_user( ... );
                        $message = [
                            'status'=>0,
                            'errorDetails'=> NULL,
                            'data' => 'User exists',
                        ];

                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                        
                }
                else{
                        $message = [
                            'status'=>2,
                            'errorDetails' => 'User does not exists',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_CREATED);
                                         
                };              
            }
    }

    public function usersCreate_post()
    {
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('username', 'username', 'required');
            $this->form_validation->set_rules('email', 'email', 'required|valid_email|required');
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('confirmPassword', 'confirmPassword', 'required|matches[password]');    
                       
            if ($this->form_validation->run() == FALSE) {   

                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                
            }else{     
                        $d = [
                            'username' => $this->input->post('username'),
                            'email' => $this->input->post('email'),
                            'password' => $this->input->post('password')
                        ];
                        $ressult = $this->user_model->insertUser($d);
                        if($ressult == "registered"){
                                $res = $this->user_model->getRegisteredUser($d);
                                foreach($res as $item) {
                                    $message = [
                                        'status'=>0,
                                        'errorDetails'=> NULL,
                                        'data' => [
                                            'userId'=>$item->userId,
                                            'username'=>$item->username,
                                            'email'=>$item->emailAddress,
                                        ]                                    
                                    ];

                                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                                } 
                                
                        }else if($ressult == 'exists'){

                                $message = [
                                    'status'=>2,
                                    'errorDetails' => 'User already exists',
                                    'data'=>NULL,
                                ];

                                $this->set_response($message, REST_Controller::HTTP_CREATED);
                                
                        }
                        else{
                                $message = [
                                    'status'=>2,
                                    'errorDetails' => 'Failed to register',
                                    'data'=>NULL,
                                ];

                                $this->set_response($message, REST_Controller::HTTP_CREATED);
                                                 
                        };              
                    }           
            }
}
