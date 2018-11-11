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
class Applications extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['createApplication_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['approveUserApplication_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['rejectUserApplication_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getAllApplicants_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getActiveApplicants_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getSuccessfulApplicants_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getUnsuccessfulApplicants_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getSingleApplicantApplications_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['deleteApplication_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->load->model('Applications_v1');

    }
    // appove applications
    public function approveUserApplication_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('userId', 'userId', 'required');
        $jobId = $this->uri->segment(4);
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
                'jobId' => $jobId,
            ];
            $ressult = $this->Applications_v1->approveApplications($d);
            if($ressult == "updated"){
                    $message = [
                        'status'=>0,
                        'errorDetails' => 'Application approved successfully',
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                    
            }else if($ressult == 'NotFound'){

                    $message = [
                        'status'=>2,
                        'errorDetails' => 'Sorry application not found.',
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
            else{
                    $message = [
                        'status'=>3,
                        'errorDetails' => 'Failed to approve',
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

    }
    // reject applications
    public function rejectUserApplication_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('userId', 'userId', 'required');
        $jobId = $this->uri->segment(4);
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
                'jobId' => $jobId,
            ];
            $ressult = $this->Applications_v1->rejectApplications($d);
            if($ressult == "updated"){
                    $message = [
                        'status'=>0,
                        'errorDetails' => 'Application rejected successfully',
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                    
            }else if($ressult == 'NotFound'){

                    $message = [
                        'status'=>2,
                        'errorDetails' => 'Sorry application not found.',
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
            else{
                    $message = [
                        'status'=>3,
                        'errorDetails' => 'Failed to approve',
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
    // registering a user
    public function createApplication_post()
    {
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('userId', 'userId', 'required');
            $this->form_validation->set_rules('comment', 'comment', 'required');
            $jobId =$this->uri->segment(4);         
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
                        'jobId' => $jobId,
                        'comment' => $this->input->post('comment')
                    ];
                    $ressult = $this->Applications_v1->insertApplication($d);
                    if($ressult == "created"){
                            $message = [
                                'status'=>0,
                                'errorDetails' => 'Application created successfully',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                            
                    }else if($ressult == 'suspended'){

                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Sorry the job has been suspended.',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else if($ressult == 'closed'){

                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Sorry the job has been closed.',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else if($ressult == 'exists'){

                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Application already exists',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else if($ressult == 'jobNotFound'){

                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Job Id does not exist',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else if($ressult == 'userNotFound'){

                            $message = [
                                'status'=>2,
                                'errorDetails' => 'User Id does not exist',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }
                    else{
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Failed to apply',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    };              
                }         
    }
    // getting all the employers
    public function getAllApplicants_get(){
            # code...
            $id =$this->uri->segment(3);
            $response = $this->Applications_v1->getJobApplicants($id);
            $data = json_decode(json_encode($response['applicantsdata']),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'applicants' => $data
                      ];
            $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    }
  // getting all active employers
    public function getActiveApplicants_get(){
            # code...
            $id =$this->uri->segment(4);
            $response = $this->Applications_v1->getActiveApplications($id);
            $data = json_decode(json_encode($response['applicantsdata']),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'applicants' => $data
                      ];
            $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    } 
  // getting all suspended employers
    public function getSuccessfulApplicants_get(){
            # code...
            $id =$this->uri->segment(4);
            $response = $this->Applications_v1->getSuccessfulApplicants($id);
            $data = json_decode(json_encode($response['applicantsdata']),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'applicants' => $data
                      ];
            $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    }
  // getting all suspended employers
    public function getUnsuccessfulApplicants_get(){
            # code...
            $id =$this->uri->segment(4);
            $response = $this->Applications_v1->getUnsuccessfulApplicants($id);
            $data = json_decode(json_encode($response['applicantsdata']),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'applicants' => $data
                      ];
            $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);
    }
  // getting all suspended employers
    public function getSingleApplicantApplications_get(){
            # code...
            $id =$this->uri->segment(4);
            $response = $this->Applications_v1->singleApplicantApplications($id);
            $data = json_decode(json_encode($response['applicantsdata']),true);
            $status = ['status'=>0,
                        'errorDetails'=> NULL,
                        'applicants' => $data
                      ];
            $message = array_merge($status,$data);
            $this->set_response($status, REST_Controller::HTTP_OK);        
    
    }
  // getting all suspended employers
    public function deleteApplication_post(){
            # code...
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('userId', 'userId', 'required');
            $jobId =$this->uri->segment(4);         
            if ($this->form_validation->run() == FALSE) {   

                    $message = [
                        'status'=>1,
                        'errorDetails' => validation_errors(),
                        'data'=>NULL,
                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }else{     
                    $data = [
                        'userId'=>$this->input->post('userId'),
                        'jobId'=>$this->uri->segment(4),
                        ];
                    $ressult = $this->Applications_v1->deleteJobApplicants($data);
                    if($ressult == "itemDeleted"){
                            $message = [
                                'status'=>0,
                                'errorDetails' => 'Application deleted successfully',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                            
                    }else if($ressult == 'itemNotFound'){

                            $message = [
                                'status'=>2,
                                'errorDetails' => 'Item does not exist',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }
                    else{
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Failed to apply',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    };              
                }         
    }
}
