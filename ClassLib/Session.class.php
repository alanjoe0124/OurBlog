<?php
class Session {
 
    private $mysqliExt;

    public function __construct($mysqliExt){
        $this->mysqliExt=$mysqliExt;
    }
    
    public function user_session_check($loginNoNeed=0){ // $loginNoNeed=0, default:view page need login
        session_start();
        $mysqliExt = $this->mysqliExt;
        // generate the session_validate code
        $sessionEmail = $_SESSION['userEmail'];
        $ip = $_SERVER["REMOTE_ADDR"];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $salt = "secret";
        $sessionVal = $sessionEmail . $ip . $agent . $salt;
        $sessionValidate = md5($sessionVal);

        if (empty($sessionEmail))// PHPSESSID not available for the session 
        {
            if($loginNoNeed==1){
                
            }else{
                exit("sorry, login please!");
            }
        }
        else
        {
            $sql = "select session_validate from user where email=?";
            $para = array("s", &$sessionEmail);
            $data = $mysqliExt->select_execute($sql, $para);
            foreach ($data as $val)
            {
                $sessionValSql = $val['session_validate'];
            }
            if ($sessionValSql == NULL) // PHPSESSID available for the session, but user hasn't logged in, require pwd to login
            {
                exit("login please!");
            }
            else
            {
                if ($sessionValidate == $sessionValSql)
                {
                    return $sessionEmail;
                }
                else
                {
                    //PHPSESSID available for the session, and user has logged in,but this request 's IP,user_agent not same 
                    exit('account has been logged in by other, if that is not your operation, please login again or contact administator to change your pwd asap!');
                }
            }
        }
    }
    
    public function session_set($email){
        session_start();
        $mysqliExt=$this->mysqliExt;
        $_SESSION['userEmail'] = $email;
        $sessionEmail = $_SESSION['userEmail'];
        $ip = $_SERVER["REMOTE_ADDR"];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $salt="secret";
        $sessionVal = $sessionEmail . $ip . $agent.$salt;
        $sessionValidate=md5($sessionVal);
        $sql="update user set session_validate=\"".$sessionValidate."\" where email=\"".$email."\"";
        $updateAffectedRow=$mysqliExt->update_execute($sql);
        $expire = time() + 3600;
        setcookie(session_name(), session_id(), $expire, "/OurBlog");
        return $updateAffectedRow;
    }
    
}
?>
