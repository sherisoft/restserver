<?php
class Experience_v1 extends CI_Model {
    public $status; 
    public $roles;
    
    function __construct(){
        // Call the Model constructor
        parent::__construct();        
        $this->status = $this->config->item('status');
        $this->roles = $this->config->item('roles');
    }    
  // registering experience
    public function insertUserExperience($d)
    {  
            $date = date('Y-m-d H:i:s');
            $string = array(
                'experinceId'=>'',
                'userId'=>$d['userId'],
                'institutionName'=>$d['institutionName'],
                'grade'=>$d['grade'],
                'yearOfCompletion'=>$d['yearOfCompletion'],
                'jobOne'=>$d['jobOne'],
                'jobOneDuration'=>$d['jobOneDuration'],
                'jobTwo'=>$d['jobTwo'],
                'jobTwoDuration'=>$d['jobTwoDuration'],
                'jobThree'=>$d['jobThree'],
                'jobThreeDuration'=>$d['jobThreeDuration'],
                'firstReferee'=>$d['firstReferee'],
                'secondReferee'=>$d['secondReferee'],
                'firstRefereePhone'=>$d['firstRefereePhone'],
                'secondRefereePhone'=>$d['secondRefereePhone'],
                'createdOn'=>$date,
                'updateOn'=>$date,
            );
            // verification and account creation
            $query=$this->db->query("SELECT * FROM experiences WHERE userId='".$d['userId']."'");
            if($query->num_rows()==1)
            {
                return "exists";
            }
            else
            {
                    $quer=$this->db->query("SELECT * FROM users WHERE userId='".$d['userId']."'");
                    $check=$quer->row_array();
                    if($quer->num_rows()==1)
                    {
                        $q = $this->db->insert('experiences',$string);             
                        if ($q) {
                            # code...
                            return "created";

                        }else{
                            return FALSE;
                        }
                    }
                    else{
                        return "NotFound";
                    }

            }
    }
  // update experience
    public function updateUserExperience($d)
    {  
            $date = date('Y-m-d H:i:s');
            $string = array(
                'userId'=>$d['userId'],
                'institutionName'=>$d['institutionName'],
                'grade'=>$d['grade'],
                'yearOfCompletion'=>$d['yearOfCompletion'],
                'jobOne'=>$d['jobOne'],
                'jobOneDuration'=>$d['jobOneDuration'],
                'jobTwo'=>$d['jobTwo'],
                'jobTwoDuration'=>$d['jobTwoDuration'],
                'jobThree'=>$d['jobThree'],
                'jobThreeDuration'=>$d['jobThreeDuration'],
                'firstReferee'=>$d['firstReferee'],
                'secondReferee'=>$d['secondReferee'],
                'firstRefereePhone'=>$d['firstRefereePhone'],
                'secondRefereePhone'=>$d['secondRefereePhone'],
                'updateOn'=>$date,
            );
            // verification and account creation
            $query=$this->db->query("SELECT * FROM experiences WHERE userId='".$d['userId']."'");
            if($query->num_rows()==1)
            {
                $this->db->where('userId',$d['userId']);
                $q = $this->db->update('experiences',$string);             
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
    // getting user experience
    public function getUserExperience($d){
        $this->db->where('userId',$d['userId']);
        //run query
        $this->db->select('userId, institutionName, grade, yearOfCompletion, jobOne, jobOneDuration, jobTwo, jobTwoDuration, jobThree, jobThreeDuration, firstReferee, secondReferee, firstRefereePhone, secondRefereePhone, updateOn');
        $q=$this->db->get('experiences')->result();
        return $q;
    }
}