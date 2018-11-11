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
class Files extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['uploadCv_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['uploadUserImage_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['uploadEmployerImage_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->methods['uploadJobPostImage_post']['limit'] = 100; // 100 requests per hour per employer/key
        $this->load->model('Posts_v1');
        $this->load->model('Employer_v1');
        $this->load->model('Version_one');

    }

    // upload cv
    public function uploadCv_post(){
        # code...
        $config['upload_path'] = "./uploads/jobs_banners";
        $config['allowed_types'] = 'doc|docx|PDF|DOC|DOCX|pdf';
        $config['max_size'] = '100000';
        $config['max_width']  = '100000';
        $config['max_height']  = '100000';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('file'))
        {
            $data = array('upload_data' => $this->upload->data());
            $userId =$this->uri->segment(4);
            $data1 = array(
                'userId' => $userId,
                'cv' => $data['upload_data']['file_name']
            );

            $result = $this->Posts_v1->UserCV($data1);
            if ($result == "failed") {
                # code...
                $message = [
                    'status'=>3,
                    'errorDetails' => 'Oops!! Internal Server Error oci_new_cursor(connection)ed',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            }else if($result == 'notFound'){
                $message = [
                    'status'=>2,
                    'errorDetails' => 'User does not exist',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }elseif ($result == "success") {
                # code...
                $getUser = $this->Version_one->getUser($data1);
                $data_response = json_decode(json_encode($getUser),true);
                $message = [
                    'status'=>0,
                    'errorDetails'=> NULL,
                    'data' => $data_response                                    
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                # code...
                $message = [
                    'status'=>3,
                    'errorDetails' => 'Oops!! Internal Server Error Occured',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        else
        {
            $message = [
                'status'=>2,
                'errorDetails' => $this->upload->display_errors(),
                'data'=>NULL,
            ];

            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    // upload user avatar
    public function uploadUserImage_post(){
        # code...
        $config['upload_path'] = "./uploads/jobs_banners";
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '100000';
        $config['max_width']  = '100000';
        $config['max_height']  = '100000';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('file'))
        {
            $data = array('upload_data' => $this->upload->data());
            $userId =$this->uri->segment(4);
            $data1 = array(
                'userId' => $userId,
                'image' => $data['upload_data']['file_name']
            );

            $result = $this->Posts_v1->userAvatar($data1);
            if ($result == "failed") {
                # code...
                $message = [
                    'status'=>3,
                    'errorDetails' => 'Oops!! Internal Server Error oci_new_cursor(connection)ed',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            }else if($result == 'notFound'){
                $message = [
                    'status'=>2,
                    'errorDetails' => 'User does not exist',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }elseif ($result == "success") {
                # code...
                $getUser = $this->Version_one->getUser($data1);
                $data_response = json_decode(json_encode($getUser),true);
                $message = [
                    'status'=>0,
                    'errorDetails'=> NULL,
                    'data' => $data_response                                    
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                # code...
                $message = [
                    'status'=>3,
                    'errorDetails' => 'Oops!! Internal Server Error Occured',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        else
        {
            $message = [
                'status'=>2,
                'errorDetails' => $this->upload->display_errors(),
                'data'=>NULL,
            ];

            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    // upload emploeyers avatar
    public function uploadEmployerImage_post(){
        # code...
        $config['upload_path'] = "./uploads/jobs_banners";
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '100000';
        $config['max_width']  = '100000';
        $config['max_height']  = '100000';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('file'))
        {
            $data = array('upload_data' => $this->upload->data());
            $employerId =$this->uri->segment(4);
            $data1 = array(
                'employerId' => $employerId,
                'image' => $data['upload_data']['file_name']
            );

            $result = $this->Posts_v1->EmployerAvatar($data1);
            if ($result == "failed") {
                # code...
                $message = [
                    'status'=>3,
                    'errorDetails' => 'Oops!! Internal Server Error oci_new_cursor(connection)ed',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            }else if($result == 'notFound'){
                $message = [
                    'status'=>2,
                    'errorDetails' => 'Employer does not exist',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }elseif ($result == "success") {
                # code...
                $getEmployer_ = $this->Employer_v1->getEmployer($data1);
                $data_response = json_decode(json_encode($getEmployer_),true);
                $message = [
                    'status'=>0,
                    'errorDetails'=> NULL,
                    'data' => $data_response                                    
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                # code...
                $message = [
                    'status'=>3,
                    'errorDetails' => 'Oops!! Internal Server Error Occured',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        else
        {
            $message = [
                'status'=>2,
                'errorDetails' => $this->upload->display_errors(),
                'data'=>NULL,
            ];

            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    // upload jobs banners
    public function uploadJobPostImage_post(){
        # code...
        $config['upload_path'] = "./uploads/jobs_banners";
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '100000';
        $config['max_width']  = '100000';
        $config['max_height']  = '100000';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('file'))
        {
            $data = array('upload_data' => $this->upload->data());
            $jobId =$this->uri->segment(4);
            $data1 = array(
                'jobId' => $jobId,
                'image' => $data['upload_data']['file_name']
            );

            $result = $this->Posts_v1->uploadJobPostBanner($data1);
            if ($result == "failed") {
                # code...
                $message = [
                    'status'=>3,
                    'errorDetails' => 'Oops!! Internal Server Error oci_new_cursor(connection)ed',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            }else if($result == 'notFound'){
                $message = [
                    'status'=>2,
                    'errorDetails' => 'Job does not exist',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }elseif ($result == "success") {
                # code...
                $getJb = $this->Posts_v1->getJob($data1);
                $data_response = json_decode(json_encode($getJb),true);
                $message = [
                    'status'=>0,
                    'errorDetails'=> NULL,
                    'data' => $data_response                                    
                ];
                $this->set_response($message, REST_Controller::HTTP_CREATED);
            }else{
                # code...
                $message = [
                    'status'=>3,
                    'errorDetails' => 'Oops!! Internal Server Error Occured',
                    'data'=>NULL,
                ];

                $this->set_response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        else
        {
            $message = [
                'status'=>2,
                'errorDetails' => $this->upload->display_errors(),
                'data'=>NULL,
            ];

            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
