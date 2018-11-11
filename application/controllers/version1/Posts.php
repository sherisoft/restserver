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
class Posts extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['jobCreate_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['jobUpdate_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getPosts_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getOpenJobs_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getClosedJobs_get']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['getSuspendedJobs_get']['limit'] = 100;
        $this->load->model('Posts_v1');
        $this->load->model('Finance_v1');
    }
    // registering a job
    public function jobCreate_post()
    {
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('companyName', 'companyName', 'required');
            $this->form_validation->set_rules('position', 'position', 'required');
            $this->form_validation->set_rules('totalAvailable', 'totalAvailable', 'required');
            $this->form_validation->set_rules('salaryFrom', 'salaryFrom', 'required');    
            $this->form_validation->set_rules('openFrom', 'openFrom', 'required');
            $this->form_validation->set_rules('closedOn', 'closedOn', 'required');
            $this->form_validation->set_rules('category', 'category', 'required');
            $this->form_validation->set_rules('employerId', 'employerId', 'required'); 
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
                        'companyName' => $this->input->post('companyName'),
                        'position' => $this->input->post('position'),
                        'totalAvailable' => $this->input->post('totalAvailable'),
                        'salaryFrom' => $this->input->post('salaryFrom'),
                        'openFrom' => $this->input->post('openFrom'),
                        'closedOn' => $this->input->post('closedOn'),
                        'category' => $this->input->post('category'),
                        'employerId' => $this->input->post('employerId'),
                        'about' => $this->input->post('about')
                    ];
                    $data = $this->Posts_v1->insertJob($d);
                    if($data['status'] == "success"){

                        $finResponse = $this->Finance_v1->createNewFinanceItem($d,$data);
                        if ($finResponse == "financeExists") {
                            # code...
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Finance item already exists',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                        }elseif ($finResponse == "created") {
                            $res = $this->Posts_v1->getJob($data);
                            $job_response = json_decode(json_encode($res),true);
                            # code...
                            $getF = $this->Finance_v1->getJobPostCharges($d,$data);
                            $charge_response = json_decode(json_encode($getF),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'jobData' => $job_response,
                                'chargesData' => $charge_response,                                    
                            ];
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else{
                            # code...
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Failed to create fincence item',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }

                    }else if ($data['status'] == "employerNotFound") {
                    # code...
                        $message = [
                            'status'=>3,
                            'errorDetails' => 'Employer does not exist',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }
                    else{
                        $message = [
                            'status'=>3,
                            'errorDetails' => 'Failed to create',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    };              
                }           
    }

  // registering a job
    public function jobUpdate_post()
    {
            $_POST = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_rules('jobId', 'jobId', 'required');
            $this->form_validation->set_rules('companyName', 'companyName', 'required');
            $this->form_validation->set_rules('position', 'position', 'required');
            $this->form_validation->set_rules('totalAvailable', 'totalAvailable', 'required');
            $this->form_validation->set_rules('salaryFrom', 'salaryFrom', 'required');    
            $this->form_validation->set_rules('openFrom', 'openFrom', 'required');
            $this->form_validation->set_rules('closedOn', 'closedOn', 'required');
            $this->form_validation->set_rules('category', 'category', 'required');
            $this->form_validation->set_rules('employerId', 'employerId', 'required'); 
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
                        'jobId' => $this->input->post('jobId'),
                        'companyName' => $this->input->post('companyName'),
                        'position' => $this->input->post('position'),
                        'totalAvailable' => $this->input->post('totalAvailable'),
                        'salaryFrom' => $this->input->post('salaryFrom'),
                        'openFrom' => $this->input->post('openFrom'),
                        'closedOn' => $this->input->post('closedOn'),
                        'category' => $this->input->post('category'),
                        'employerId' => $this->input->post('employerId'),
                        'about' => $this->input->post('about')
                    ];
                    $ressult = $this->Posts_v1->updateJob($d);
                    if($ressult == "updated"){
                        $finResponse = $this->Finance_v1->updateFinanceItem($d);
                        if ($finResponse == "NotFound") {
                            # code...
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Finance item was not found',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                        }elseif ($finResponse == "updated") {
                            $res = $this->Posts_v1->getJob($d);
                            $job_response = json_decode(json_encode($res),true);
                            # code...
                            $db = [
                                'jobId' => $this->input->post('jobId'),
                            ];
                            $getF = $this->Finance_v1->getJobPostCharges($d,$db);
                            $charge_response = json_decode(json_encode($getF),true);
                            $message = [
                                'status'=>0,
                                'errorDetails'=> NULL,
                                'jobData' => $job_response,
                                'chargesData' => $charge_response,                                    
                            ];
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        }else{
                            # code...
                            $message = [
                                'status'=>3,
                                'errorDetails' => 'Failed to create fincence item',
                                'data'=>NULL,
                            ];

                            $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }                          
                    }else if($ressult == 'notFound'){
                        $message = [
                            'status'=>2,
                            'errorDetails' => 'Job does not exist',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }else if ($ressult == "employerNotFound") {
                    # code...
                            $message = [
                            'status'=>3,
                            'errorDetails' => 'Employer does not exist',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }
                    else{
                        $message = [
                            'status'=>3,
                            'errorDetails' => 'Failed to create',
                            'data'=>NULL,
                        ];

                        $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    };              
                }         
    } 

    // getting all the jobs
    public function getPosts_get(){
        # code...
        $response = $this->Posts_v1->allJobs();
        $data = json_decode(json_encode($response),true);
        $status = ['status'=>0,
                    'errorDetails'=> NULL,
                    'jobs' => $data
                  ];
        $message = array_merge($status,$data);
        $this->set_response($status, REST_Controller::HTTP_OK);
    }
  // getting all open jobs
    public function getOpenJobs_get(){
        # code...
        $response = $this->Posts_v1->getOpenJobs();
        $data = json_decode(json_encode($response),true);
        $status = ['status'=>0,
                    'errorDetails'=> NULL,
                    'jobs' => $data
                  ];
        $message = array_merge($status,$data);
        $this->set_response($status, REST_Controller::HTTP_OK);
    } 
  // getting all closed jobs
    public function getClosedJobs_get(){
            # code...
        $response = $this->Posts_v1->getClosedJobs();
        $data = json_decode(json_encode($response),true);
        $status = ['status'=>0,
                    'errorDetails'=> NULL,
                    'jobs' => $data
                  ];
        $message = array_merge($status,$data);
        $this->set_response($status, REST_Controller::HTTP_OK);
    }
  // getting all suspended jobs
    public function getSuspendedJobs_get(){
        # code...
        $response = $this->Posts_v1->getSuspendedJobs();
        $data = json_decode(json_encode($response),true);
        $status = ['status'=>0,
                    'errorDetails'=> NULL,
                    'jobs' => $data
                  ];
        $message = array_merge($status,$data);
        $this->set_response($status, REST_Controller::HTTP_OK);
    }
}
