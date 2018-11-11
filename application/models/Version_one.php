<?php
class Version_one extends CI_Model {
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
        $this->db->get_where('users', array('emailAddress' => $email), 1);
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;         
    }
    // Verify username is registered
    public function isUsernameDuplicate($username)
    {   $id = $this->input->post('id');
        $this->db->get_where('users', array('username' => $username), 1);
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;         
    }
  // validate login creds
    // getting registered user
    public function validateForLogin($d){
            $pass = md5($d['password']);
            $email = $d['email'];

            $query=$this->db->query("SELECT * FROM users WHERE emailAddress='".$email."'");
            if($query->num_rows()==1)
            {
                //run query
                $sql=$this->db->query("SELECT * FROM users WHERE emailAddress='".$email."' AND password='".$pass."'");
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
    // Username Login
    public function validateUsernameLogin($d){
            $pass = md5($d['password']);
            $username = $d['username'];

            $query=$this->db->query("SELECT * FROM users WHERE username='".$username."'");
            if($query->num_rows()==1)
            {
                //run query
                $sql=$this->db->query("SELECT * FROM users WHERE username='".$username."' AND password='".$pass."'");
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
  //  Change user password
    public function userChangePassword($d){
            $user = $d['userId'];
            $currentPass = md5($d['oldPassword']);
            $newPass = md5($d['newPassword']);
            // verification and account creation
            $query=$this->db->query("SELECT * FROM users WHERE userId='".$user."'");
            if($query->num_rows()==1)
            {
                //run query
                $sql=$this->db->query("SELECT * FROM users WHERE userId='".$user."' AND password='".$currentPass."'");
                if($sql->num_rows()==1)
                {
                    $q=$this->db->query('UPDATE users SET password="'.$newPass.'" WHERE userId="'.$user.'" AND password="'.$currentPass.'"');            
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

  //  Change user password
    public function userChangeEmail($d){
            $user = $d['userId'];
            $currentEmail = $d['oldEmail'];
            $newEmail = $d['newEmail'];
            // verification and account creation
            $query=$this->db->query("SELECT * FROM users WHERE userId='".$user."'");
            if($query->num_rows()==1)
            {
                //run query
                $sql=$this->db->query("SELECT * FROM users WHERE userId='".$user."' AND emailAddress='".$currentEmail."'");
                if($sql->num_rows()==1)
                {
                    $q=$this->db->query('UPDATE users SET emailAddress="'.$newEmail.'" WHERE userId="'.$user.'" AND emailAddress="'.$currentEmail.'"');            
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

  // registering users
    public function userUpdateProfile($d)
    {  
            $date = date('Y-m-d H:i:s');
            $string = array(
                'firstName'=>$d['firstName'],
                'lastName'=>$d['lastName'],
                'mobileNumber'=>$d['mobileNumber'],
                'dob'=>$d['dob'],
                'idNumber'=>$d['idNumber'],
                'residentArea'=>$d['residentArea'],
                'gender'=>$d['gender'],
            );
            // verification and account creation
            $query=$this->db->query("SELECT * FROM users WHERE userId='".$d['userId']."'");
            if($query->num_rows()==1)
            {
                $this->db->where('userId',$d['userId']);
                //run query
                $q = $this->db->update('users',$string);             
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

  // registering users
    public function insertUser($d)
    {  
            $pass = md5($d['password']);
            $date = date('Y-m-d H:i:s');
            $string = array(
                'userId'=>'',
                'username'=>$d['username'],
                'emailAddress'=>$d['email'],
                'password'=>$pass,
                'firstName'=>'',
                'middleName'=>'',
                'lastName'=>'',
                'mobileNumber'=>'',
                'role'=>'0',
                'status'=>'active',
                'createOn'=>$date,
                'lastLogin'=>$date,
                'verificationStatus'=>'verified',
                'cv'=>'',
                'dp'=>'',
                'dob'=>'',
                'idNumber'=>'',
                'residentArea'=>'',
                'gender'=>'',
                'about'=>'',
            );
            // verification and account creation
            $query=$this->db->query("SELECT * FROM users WHERE emailAddress='".$d['email']."'");
            if($query->num_rows()==1)
            {
                return "exists";
            }
            else
            {
                    // verification and account creation
                    $quer=$this->db->query("SELECT * FROM users WHERE username='".$d['username']."'");
                    if($quer->num_rows()==1)
                    {
                        return "usernameExists";
                    }
                    else
                    {
                        $q = $this->db->insert('users',$string);             
                        if ($q) {
                            # code...
                            return "registered";

                        }else{
                            return FALSE;
                        }
                    }
            }
    }

    // getting registered user
    public function getRegisteredWithUsername($d){
            $pass = md5($d['password']);
            $username = $d['username'];

            $this->db->where('password',$pass);
            $this->db->where('username',$username);
            //run query
            $this->db->select('userId, username, emailAddress, firstName, lastName, status, mobileNumber, cv, dp, dob, idNumber, residentArea, about, gender');
            $q=$this->db->get('users')->result();
            return $q;
    }
    // getting registered user
    public function getRegisteredUser($d){
            $pass = md5($d['password']);
            $email = $d['email'];

            $this->db->where('password',$pass);
            $this->db->where('emailAddress',$email);
            //run query
            $this->db->select('userId, username, emailAddress, firstName, lastName, status, mobileNumber, cv, dp, dob, idNumber, residentArea, about, gender');
            $q=$this->db->get('users')->result();
            return $q;
    }
    // getting user
    public function getUser($d){
            $this->db->where('userId',$d['userId']);
            //run query
            $this->db->select('userId, username, emailAddress, firstName, lastName, status, mobileNumber, cv, dp, dob, idNumber, residentArea, about, gender');
            $q=$this->db->get('users')->result_array();
            return $q;
    }
    //get all employers
    public function allUsers(){
        $this->db->select('userId, username, emailAddress, firstName, lastName, status, mobileNumber, cv, dob, dp, idNumber, residentArea, about, gender');
        $items = $this->db->get('users')->result_array();
        // $items = $this->db->query('SELECT * FROM employers')->result_array();
        return $items;
    }
    // 
    public function getActiveUsers(){
        $status = 'active';
        $this->db->where('status',$status);
        $this->db->select('userId, username, emailAddress, firstName, lastName, status, mobileNumber, cv, dob,  dp, idNumber, residentArea, about, gender');
        $items = $this->db->get('users')->result_array();
        return $items;
    } 
    // 
    public function getSuspendedUsers(){
        $status = 'suspended';
        $this->db->where('status',$status);
        $this->db->select('userId, username, emailAddress, firstName, lastName, status, mobileNumber, cv, dob,  dp, idNumber, residentArea, about, gender');
        $items = $this->db->get('users')->result_array();
        return $items;
    } 
}