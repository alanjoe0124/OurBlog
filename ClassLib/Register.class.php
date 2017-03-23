<?php

class Register {

    private $email;
    private $pwd;
    private $mysql;
    
    public function __construct($email, $pwd,$dbConf)
    {
        $this->email = $email;
        $this->pwd = $pwd;
        $this->mysql=Mysql::getInstance($dbConf);
    }

    public function handle($session)
    {
        $regTime=date("Y-m-d H:i:s");
        // check if duplicate
        // $sql = "select * from user where email = :email";
        $sql=$this->mysql->select("user",array('email'=>':email'));      
        $arr['email']=$this->email;
        $bindTypeArray['email']=PDO::PARAM_STR;
        $data=$this->mysql->select_execute($sql,$arr,$bindTypeArray);
        if($data==NULL){
           $sql=$this->mysql->insert('user',array('email'=>':email','pwd'=>':pwd','reg_time'=>':reg_time'));
            $arr['email']=$this->email;
            $arr['pwd']=$this->pwd;
            $arr['reg_time']=$regTime;
            $bindTypeArray['email']=PDO::PARAM_STR;
            $bindTypeArray['pwd']=PDO::PARAM_STR;
            $bindTypeArray['reg_time']=PDO::PARAM_STR;
            $this->mysql->insert_execute($sql,$arr,$bindTypeArray);           
            $userDbId=$this->mysql->getLastInsertId();
            $session->session_set($this->email,$userDbId);
        }else{
            exit("email has been used!");
        }
        
    }
}

?>