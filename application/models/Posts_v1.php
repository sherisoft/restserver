<?php
class Posts_v1 extends CI_Model {
    public $status; 
    public $roles;
    
    function __construct(){
        // Call the Model constructor
        parent::__construct();        
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
    }    

// upload banner image
    public function uploadJobPostBanner($data){
        // verify job
        $query=$this->db->query("SELECT * FROM jobs WHERE jobId='".$data['jobId']."'");
        if($query->num_rows()==1)
        {
            $string = array(
                'banner' => $data['image'],
            );
            // banner creation
            $this->db->where('jobId',$data['jobId']);
            //run query
            $q = $this->db->update('jobs',$string);             
            if ($q) {
                # code...
                return "success";

            }else{
                return "failed";
            }
            
        }
        else
        {
            return "notFound";
        }
    }
// upload banner image
    public function userAvatar($data){
        // verify job
        $query=$this->db->query("SELECT * FROM users WHERE userId='".$data['userId']."'");
        if($query->num_rows()==1)
        {
            $string = array(
                'dp' => $data['image'],
            );
            // banner creation
            $this->db->where('userId',$data['userId']);
            //run query
            $q = $this->db->update('users',$string);             
            if ($q) {
                # code...
                return "success";

            }else{
                return "failed";
            }
            
        }
        else
        {
            return "notFound";
        }   
    }
// upload banner image
    public function EmployerAvatar($data){
        // verify job
        $query=$this->db->query("SELECT * FROM employers WHERE employerId='".$data['employerId']."'");
        if($query->num_rows()==1)
        {
            $string = array(
                'dp' => $data['image'],
            );
            // banner creation
            $this->db->where('employerId',$data['employerId']);
            //run query
            $q = $this->db->update('employers',$string);             
            if ($q) {
                # code...
                return "success";

            }else{
                return "failed";
            }
            
        }
        else
        {
            return "notFound";
        }    
    }
// upload banner image
    public function UserCV($data){
        // verify job
        $query=$this->db->query("SELECT * FROM users WHERE userId='".$data['userId']."'");
        if($query->num_rows()==1)
        {
            $string = array(
                'cv' => $data['cv'],
            );
            // banner creation
            $this->db->where('userId',$data['userId']);
            //run query
            $q = $this->db->update('users',$string);             
            if ($q) {
                # code...
                return "success";

            }else{
                return "failed";
            }
            
        }
        else
        {
            return "notFound";
        }     
    }
  // updating a job
    public function updateJob($d)
    {  
            $date = date('Y-m-d H:i:s');
            $string = array(
                'companyName' => $d['companyName'],
                'position' => $d['position'],
                'totalAvailable' => $d['totalAvailable'],
                'salaryFrom' => $d['salaryFrom'],
                'openFrom' => $d['openFrom'],
                'closedOn' => $d['closedOn'],
                'category' => $d['category'],
                'employerId' => $d['employerId'],
                'about' => $d['about'],
            );
            // verification and account creation
            $query=$this->db->query("SELECT * FROM jobs WHERE jobId='".$d['jobId']."'");
            if($query->num_rows()==1)
            {
                $sql=$this->db->query("SELECT * FROM employers WHERE employerId='".$d['employerId']."'");
                if($sql->num_rows()==1)
                {
                    // verification and account creation
                    $this->db->where('jobId',$d['jobId']);
                    //run query
                    $q = $this->db->update('jobs',$string);             
                    if ($q) {
                        # code...
                        return "updated";

                    }else{
                        return FALSE;
                    }
                }
                else{
                        return "employerNotFound";
                }
                
            }
            else
            {
                return "notFound";
            }
    }
  // createing a post
    public function insertJob($d)
    {  
            $date = date('Y-m-d H:i:s');
            $data = array(
                'jobId' => '',
                'companyName' => $d['companyName'],
                'position' => $d['position'],
                'totalAvailable' => $d['totalAvailable'],
                'salaryFrom' => $d['salaryFrom'],
                'salaryUpTo' => $d['salaryFrom'],
                'openFrom' => $d['openFrom'],
                'closedOn' => $d['closedOn'], 
                'status' => 'open',
                'createdOn' => $date,
                'category' => $d['category'],
                'employerId' => $d['employerId'],
                'about' => $d['about'],
            );

            $sql=$this->db->query("SELECT * FROM employers WHERE employerId='".$d['employerId']."'");
            if($sql->num_rows()==1)
            {
                // verification and account creation
                $q = $this->db->insert('jobs',$data);
                $insert_id = $this->db->insert_id();        
                if ($q) {
                    # code...
                    return array(
                            'status' => "success",
                            'jobId' => $insert_id,
                        );

                }else{
                    return FALSE;
                }
            }
            else{
                    return array(
                            'status' => "employerNotFound",
                            'jobId' => NULL,
                        );
            }
    }

    // getting mployer
    public function getJob($d){
        $this->db->where('jobId',$d['jobId']);
        //run query
        $this->db->select('jobId, companyName, position, totalAvailable, salaryFrom, openFrom, closedOn, status, createdOn, category, employerId, about, banner');
        $q=$this->db->get('jobs')->result();
        return $q;
    }
    //get all employers
    public function allJobs(){
        $this->db->select('jobId, companyName, position, totalAvailable, salaryFrom, openFrom, closedOn, status, createdOn, category, employerId, about, banner');
        $items = $this->db->get('jobs')->result_array();
        return $items;
    }
    // 
    public function getOpenJobs(){
        $status = 'open';
        $this->db->where('status',$status);
        $this->db->select('jobId, companyName, position, totalAvailable, salaryFrom, openFrom, closedOn, status, createdOn, category, employerId, about, banner');
        $items = $this->db->get('jobs')->result_array();
        return $items;
    } 
    // 
    public function getClosedJobs(){
        $status = 'closed';
        $this->db->where('status',$status);
        $this->db->select('jobId, companyName, position, totalAvailable, salaryFrom, openFrom, closedOn, status, createdOn, category, employerId, about, banner');
        $items = $this->db->get('jobs')->result_array();
        return $items;
    } 
    // 
    public function getSuspendedJobs(){
        $status = 'suspended';
        $this->db->where('status',$status);
        $this->db->select('jobId, companyName, position, totalAvailable, salaryFrom, openFrom, closedOn, status, createdOn, category, employerId, about, banner');
        $items = $this->db->get('jobs')->result_array();
        return $items;
    } 
}