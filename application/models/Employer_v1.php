<?php
class Employer_v1 extends CI_Model {
    public $status; 
    public $roles;
    
    function __construct(){
        // Call the Model constructor
        parent::__construct();        
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
    }    
    
    // Verify email is registered
    public function isDuplicate($email)
    {   $id = $this->input->post('id');
        $this->db->get_where('employers', array('emailAddress' => $email), 1);
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;         
    }
    // Verify username is registered
    public function isUsernameDuplicate($username)
    {   $id = $this->input->post('id');
        $this->db->get_where('employers', array('username' => $username), 1);
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;         
    }
    // getting registered mployer
    public function validateUsernameLogin($d){
            $pass = md5($d['password']);
            $username = $d['username'];

            $query=$this->db->query("SELECT * FROM employers WHERE username='".$username."'");
            if($query->num_rows()==1)
            {
                //run query
                $sql=$this->db->query("SELECT * FROM employers WHERE username='".$username."' AND password='".$pass."'");
                if($sql->num_rows()==1)
                {
                    return "exists";

                }else{
                    return "wrongPassword";
                }
            }
            else{
                return "wrongUsername";
            }
    }
  // validate login creds
    // getting registered mployer
    public function validateForLogin($d){
            $pass = md5($d['password']);
            $email = $d['email'];

            $query=$this->db->query("SELECT * FROM employers WHERE emailAddress='".$email."'");
            if($query->num_rows()==1)
            {
                //run query
                $sql=$this->db->query("SELECT * FROM employers WHERE emailAddress='".$email."' AND password='".$pass."'");
                if($sql->num_rows()==1)
                {
                    return "exists";

                }else{
                    return "wrongPassword";
                }
            }
            else{
                return "wrongEmail";
            }
    }
  //  Change mployer password
    public function employerChangePassword($d){
            $user = $d['employerId'];
            $currentPass = md5($d['oldPassword']);
            $newPass = md5($d['newPassword']);
            // verification and account creation
            $query=$this->db->query("SELECT * FROM employers WHERE employerId='".$user."'");
            if($query->num_rows()==1)
            {
                //run query
                $sql=$this->db->query("SELECT * FROM employers WHERE employerId='".$user."' AND password='".$currentPass."'");
                if($sql->num_rows()==1)
                {
                    $q=$this->db->query('UPDATE employers SET password="'.$newPass.'" WHERE employerId="'.$user.'" AND password="'.$currentPass.'"');            
                    if ($q) {
                        # code...
                        return "updated";

                    }else{
                        return FALSE;
                    }
                }else{
                    return "wrongPassword";
                }
            }
            else{
                return "notFound";
            }
    }

  //  Change mployer password
    public function employerChangeEmail($d){
            $user = $d['employerId'];
            $currentEmail = $d['oldEmail'];
            $newEmail = $d['newEmail'];
            // verification and account creation
            $query=$this->db->query("SELECT * FROM employers WHERE employerId='".$user."'");
            if($query->num_rows()==1)
            {
                //run query
                $sql=$this->db->query("SELECT * FROM employers WHERE employerId='".$user."' AND emailAddress='".$currentEmail."'");
                if($sql->num_rows()==1)
                {
                    $q=$this->db->query('UPDATE employers SET emailAddress="'.$newEmail.'" WHERE employerId="'.$user.'" AND emailAddress="'.$currentEmail.'"');            
                    if ($q) {
                        # code...
                        return "updated";

                    }else{
                        return FALSE;
                    }
                }else{
                    return "wrongEmail";
                }
            }
            else{
                return "notFound";
            }
    }

  // registering employers employersBusinessDetails_post
    public function employerUpdateProfile($d)
    {  
            $date = date('Y-m-d H:i:s');
            $string = array(
                'firstName'=>$d['firstName'],
                'lastName'=>$d['lastName'],
                'mobileNumber'=>$d['mobileNumber'],
                'about'=>$d['about'],
            );
            // verification and account creation
            $query=$this->db->query("SELECT * FROM employers WHERE employerId='".$d['employerId']."'");
            if($query->num_rows()==1)
            {
                $this->db->where('employerId',$d['employerId']);
                //run query
                $q = $this->db->update('employers',$string);             
                if ($q) {
                    # code...
                    return "updated";

                }else{
                    return FALSE;
                }
                
            }
            else
            {
                return "notFound";
            }
    }
    public function employerUpdateBusiness($d)
    {  
            $date = date('Y-m-d H:i:s');
            $string = array(
                'businessName'=>$d['businessName'],
                'businessLocation'=>$d['businessLocation'],
                'industry'=>$d['industry'],
                'businessRegNo'=>$d['businessRegNo'],
            );
            // verification and account creation
            $query=$this->db->query("SELECT * FROM employers WHERE employerId='".$d['employerId']."'");
            if($query->num_rows()==1)
            {
                $this->db->where('employerId',$d['employerId']);
                //run query
                $q = $this->db->update('employers',$string);             
                if ($q) {
                    # code...
                    return "updated";

                }else{
                    return FALSE;
                }
                
            }
            else
            {
                return "notFound";
            }
    }
// INSERT INTO `employers` (``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `gender`, `about`) VALUES
// (1, 'Markez', 'Ochieng''', 'Onyango', 'marqochieng13@gmail.com', '0716357527', '25d55ad283aa400af464c76d713c07ad', 'active', '2018-08-30 15:44:49', '2018-08-12 22:12:06', 'verified', 'Markez', 'Vinarq Technologies', 'Nairobi', 'LO000FT', 'engineering', 'on', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.');
  // registering employers
    public function insertEmployer($d)
    {  
            $pass = md5($d['password']);
            $date = date('Y-m-d H:i:s');
            $string = array(
                'employerId'=>'',
                'username'=>$d['username'],
                'emailAddress'=>$d['email'],
                'password'=>$pass,
                'firstName'=>'',
                'middleName'=>'',
                'businessName'=>'',
                'businessLocation'=>'',
                'industry'=>'',
                'businessRegNo'=>'',
                'lastName'=>'',
                'mobileNumber'=>'',
                'status'=>'active',
                'createOn'=>$date,
                'lastLogin'=>$date,
                'verificationStatus'=>'verified',
                'gender'=>'',
                'about'=>'',
            );
            // verification and account creation
            $query=$this->db->query("SELECT * FROM employers WHERE emailAddress='".$d['email']."'");
            if($query->num_rows()==1)
            {
                return "exists";
            }
            else
            {
                // verification and account creation
                $quer=$this->db->query("SELECT * FROM employers WHERE username='".$d['username']."'");
                if($quer->num_rows()==1)
                {
                    return "usernameExists";
                }
                else
                {
                    $q = $this->db->insert('employers',$string);             
                    if ($q) {
                        # code...
                        return "registered";

                    }else{
                        return FALSE;
                    }
                }
            }
    }

    // getting registered mployer
    public function getRegisteredEmployerWithUsername($d){
            $pass = md5($d['password']);
            $username = $d['username'];

            $this->db->where('password',$pass);
            $this->db->where('username',$username);
            //run query
            $this->db->select('employerId, firstName, lastName, emailAddress, mobileNumber, status, username, businessName, businessLocation, businessRegNo, industry, about, dp');
            $q=$this->db->get('employers')->result();
            return $q;
    }
    // getting registered mployer
    public function getRegisteredEmployer($d){
            $pass = md5($d['password']);
            $email = $d['email'];

            $this->db->where('password',$pass);
            $this->db->where('emailAddress',$email);
            //run query
            $this->db->select('employerId, firstName, lastName, emailAddress, mobileNumber, status, username, businessName, businessLocation, businessRegNo, industry, about, dp');
            $q=$this->db->get('employers')->result();
            return $q;
    }
    // getting mployer
    public function getEmployer($d){
            $this->db->where('employerId',$d['employerId']);
            //run query
            $q=$this->db->get('employers')->result();
            return $q;
    }
    //get all employers
    public function allEmployers(){
        $this->db->select('employerId, firstName, lastName, emailAddress, mobileNumber, status, username, businessName, businessLocation, businessRegNo, industry, about, dp');
        $items = $this->db->get('employers')->result_array();
        // $items = $this->db->query('SELECT * FROM employers')->result_array();
        return $items;
    }
    // 
    public function getActiveEmployers(){
        $status = 'active';
        $this->db->where('status',$status);
        $this->db->select('employerId, firstName, lastName, emailAddress, mobileNumber, status, username, businessName, businessLocation, businessRegNo, industry, about, dp');
        $items = $this->db->get('employers')->result_array();
        return $items;
    } 
    // 
    public function getSuspendedEmployers(){
        $status = 'suspended';
        $this->db->where('status',$status);
        $this->db->select('employerId, firstName, lastName, emailAddress, mobileNumber, status, username, businessName, businessLocation, businessRegNo, industry, about, dp');
        $items = $this->db->get('employers')->result_array();
        return $items;
    } 
}