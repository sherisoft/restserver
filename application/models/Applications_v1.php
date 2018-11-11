<?php
class Applications_v1 extends CI_Model {
    public $status; 
    public $roles;
    
    function __construct(){
        // Call the Model constructor
        parent::__construct();        
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
    }    
      // registering employers
    public function insertApplication($d)
    {  
            $date = date('Y-m-d H:i:s');
            $string = array(
                'id'=>'',
                'userId'=>$d['userId'],
                'jobId'=>$d['jobId'],
                'appliedOn'=>$date,
                'status'=>'active',
                'comment'=>$d['comment'],
                'statusChangedOn'=>$date,
            );
            // verification and account creation
            $query=$this->db->query("SELECT * FROM applications WHERE userId='".$d['userId']."' AND jobId='".$d['jobId']."'");
            if($query->num_rows()==1)
            {
                return "exists";
            }
            else
            {
                $sql=$this->db->query("SELECT * FROM jobs WHERE jobId='".$d['jobId']."'");
                if($sql->num_rows()==1)
                {
                    $quer=$this->db->query("SELECT * FROM users WHERE userId='".$d['userId']."'");
                    $check=$quer->row_array();
                    if($quer->num_rows()==1)
                    {
                        if ($check['status'] == "closed") {
                            # code...
                            return "closed";
                        }
                        elseif ($check['status'] == "suspended") {
                            # code...
                            return "suspended";
                        }
                        else{
                            $q = $this->db->insert('applications',$string);             
                            if ($q) {
                                # code...
                                return "created";

                            }else{
                                return FALSE;
                            }
                        }

                    }
                    else
                    {
                        return "userNotFound";
                    }
                }
                else
                {
                    return "jobNotFound";
                }
            }
    }
    // approve application
    public function approveApplications($data){
        $jobId = $data['jobId'];
        $userId = $data['userId'];
        $status = "successful";
        $string = array('status' => $status,);
        // verification and account creation
        $query=$this->db->query("SELECT * FROM applications WHERE userId='".$data['userId']."' AND jobId='".$data['jobId']."'");
        if($query->num_rows()==1)
        {
            $this->db->where('userId',$data['userId']);
            $this->db->where('jobId',$data['jobId']);
            $q = $this->db->update('applications',$string);             
            if ($q) {
                # code...
                return "updated";

            }else{
                return FALSE;
            }
        }
        else
        {
            return "NotFound";
        }
    }
    // approve application
    public function rejectApplications($data){
        $jobId = $data['jobId'];
        $userId = $data['userId'];
        $status = "unsuccessful";
        $string = array('status' => $status,);
        // verification and account creation
        $query=$this->db->query("SELECT * FROM applications WHERE userId='".$data['userId']."' AND jobId='".$data['jobId']."'");
        if($query->num_rows()==1)
        {
            $this->db->where('userId',$data['userId']);
            $this->db->where('jobId',$data['jobId']);
            $q = $this->db->update('applications',$string);             
            if ($q) {
                # code...
                return "updated";

            }else{
                return FALSE;
            }
        }
        else
        {
            return "NotFound";
        }
    }
    // 
    public function getJobApplicants($id){
        $items = $this->db->query('SELECT * FROM applications WHERE jobId ="'.$id.'" ');
        if ($items) {
            # code...
            $data['applicantsdata'] = $items->result();
            return $data;
        }else{
            return False;   
        }
    }
    // 
    public function getActiveApplications($id){
        $status = "active";
        $items = $this->db->query('SELECT * FROM applications WHERE jobId ="'.$id.'" AND status ="'.$status.'" ');
        if ($items) {
            # code...
            $data['applicantsdata'] = $items->result();
            return $data;
        }else{
            return False;   
        }
    }
    // 
    public function getSuccessfulApplicants($id){
        $status = "successful";
        $items = $this->db->query('SELECT * FROM applications WHERE jobId ="'.$id.'" AND status ="'.$status.'" ');
        if ($items) {
            # code...
            $data['applicantsdata'] = $items->result();
            return $data;
        }else{
            return False;   
        }
    }
    // 
    public function getUnsuccessfulApplicants($id){
        $status = "unsuccessful";
        $items = $this->db->query('SELECT * FROM applications WHERE jobId ="'.$id.'" AND status ="'.$status.'" ');
        if ($items) {
            # code...
            $data['applicantsdata'] = $items->result();
            return $data;
        }else{
            return False;   
        }
    }
    // 
    public function deleteJobApplicants($id){
        $quer=$this->db->query('SELECT * FROM applications WHERE jobId ="'.$id['jobId'].'" AND userId ="'.$id['userId'].'"');
        if($quer->num_rows()==1)
        {
            $this->db->where('jobId', $id['jobId']);
            $this->db->where('userId', $id['userId']);
            $items = $this->db->delete('applications');
            if ($items) {
                # code...
                return "itemDeleted";
            }else{
                return False;   
            }
        }else{
            return "itemNotFound";
        }
    }
    // 
    public function singleApplicantApplications($id){
        $items = $this->db->query('SELECT * FROM applications WHERE userId ="'.$id.'" ');
        if ($items) {
            # code...
            $data['applicantsdata'] = $items->result();
            return $data;
        }else{
            return False;   
        }
}
}