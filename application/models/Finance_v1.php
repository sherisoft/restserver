<?php
class Finance_v1 extends CI_Model {
    public $status; 
    public $roles;
    
    function __construct(){
        // Call the Model constructor
        parent::__construct();        
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
    }    
    

    // create finance for a job posted

    public function createNewFinanceItem($data,$d){

        // calculate charges
        $percentage = 20;
        $totalSalary = $data['salaryFrom'] * $data['totalAvailable'];
        $calc = ($percentage / 100) * $totalSalary;
        $chargedAmount = number_format((float)$calc, 2, '.', '');

        $date = date('Y-m-d H:i:s');
        $string = array(
            'financeId'=>'',
            'employerId'=>$data['employerId'],
            'jobId'=>$d['jobId'],
            'creadtedOn'=>$date,
            'totalApplicants'=>$data['totalAvailable'],
            'totalSalary'=> number_format((float)$totalSalary, 2, '.', ''),
            'chargedPercentage'=>$percentage,
            'chargedAmount'=>$chargedAmount,
            'invoiceSent'=>"true",
            'invoicedOn'=>$date,
            'invoiceStatus'=>'notPaid',
            'updatedOn'=>$date,
        );
        // verification and account creation
        $query=$this->db->query("SELECT * FROM finances WHERE employerId='".$data['employerId']."' AND jobId='".$d['jobId']."'");
        if($query->num_rows()==1)
        {
            return "financeExists";
        }
        else
        {
            $q = $this->db->insert('finances',$string);             
            if ($q) {
                # code...
                return "created";

            }else{
                return FALSE;
            }
        }
    }
    // create finance for a job posted

    public function updateFinanceItem($data){
        // calculate charges
        $percentage = 20;
        $totalSalary = $data['salaryFrom'] * $data['totalAvailable'];
        $calc = ($percentage / 100) * $totalSalary;
        $chargedAmount = number_format((float)$calc, 2, '.', '');

        $date = date('Y-m-d H:i:s');
        $string = array(
            'employerId'=>$data['employerId'],
            'jobId'=>$data['jobId'],
            'totalApplicants'=>$data['totalAvailable'],
            'totalSalary'=> number_format((float)$totalSalary, 2, '.', ''),
            'chargedPercentage'=>$percentage,
            'chargedAmount'=>$chargedAmount,
            'updatedOn'=>$date,
        );
        // verification and account creation
        $query=$this->db->query("SELECT * FROM finances WHERE employerId='".$data['employerId']."' AND jobId='".$data['jobId']."'");
        if($query->num_rows()==1)
        {
            $this->db->where('employerId',$data['employerId']);
            $this->db->where('jobId',$data['jobId']);
            //run query
            $q = $this->db->update('finances',$string);             
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
    // get charges for a post
    public function getJobPostCharges($d1,$d2){
        $this->db->where('employerId',$d1['employerId']);
        $this->db->where('jobId',$d2['jobId']);
        //run query
        $this->db->select('employerId, jobId, creadtedOn, totalApplicants, totalSalary, chargedPercentage, chargedAmount, invoiceSent, invoicedOn, invoiceStatus');
        $q=$this->db->get('finances')->result();
        return $q;
    }
}