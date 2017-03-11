<?php

class Login {

    private $email;
    private $pwd;
    private $mysqliExt;

    public function __construct($email, $pwd, $mysqliExt)
    {
        $this->email = $email;
        $this->pwd = $pwd;
        $this->mysqliExt = $mysqliExt;
    }

    public function handle($session)
    {
        $email = $this->email;
        $pwd = $this->pwd;
        $mysqliExt = $this->mysqliExt;
        // check if exist
        $sql = "select count(*) from user where email = ?";
        $para = array("s", &$email);
        $num = $mysqliExt->count($sql, $para);
        if ($num == 1)
        {
            //check pwd 
            $mysqliExt->startTrans();
            $sqd = "select pwd,session_validate from user where email=? for update";
            $data = $mysqliExt->select_execute($sqd, $para);
            foreach ($data as $res)
            {
                $emailPwd = $res['pwd'];
                $sessionValidate=$res['session_validate'];
            }
            if ($pwd == $emailPwd && $sessionValidate==NULL)
            {
                $updateAffectedRow=$session->session_set($email);
                $flag=true;
                if($updateAffectedRow==0){
                    $flag = false;   
                }
                if($flag) { 
                    $mysqliExt->commit();
                } else { 
                    $mysqliExt->rollback(); 
                } 
                    $mysqliExt->endTrans();
                header("Location:http://" . $_SERVER['SERVER_NAME'] . "/OurBlog/admin/blog_manage.php");
            }
            else if($pwd != $emailPwd)
            {
                exit("password wrong!");
            }
            else if($sessionValidate!=NULL){
                exit('account has been logged in by other, if that is not your operation, please contact to administator and change your pwd asap!');
            }
            else{
                
            }
        }
        else
        {
            exit("email doesn't exist!");
        }
    }

}

?>