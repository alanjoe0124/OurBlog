<?php
class Session {
 
    private $mysqliExt;

    public function __construct($mysqliExt){
        $this->mysqliExt=$mysqliExt;
    }
    
    public function user_session_check($loginNoNeed=0){ // $loginNoNeed=0, default:view page need login
        session_start();
        $userSession=$_SESSION['userEmail'];
        if(!empty($userSession)){
            return $userSession;
        }else{
            if($loginNoNeed==1){
                return NULL;
            }else{
                exit("login please.");
            }
        }
    }
    
    public function session_set($email){
        session_start();
        $mysqliExt=$this->mysqliExt;
        $_SESSION['userEmail'] = $email;
        $expire = time() + 3600;
        setcookie(session_name(), session_id(), $expire, "/OurBlog");
    }
    
}
?>
