<?php
require_once('DB.php');

class CONTACT extends DB{
    public $values = array(),
            $sql;
    
    
    public function setValues($param = array()){
        $this->values = $param;
    }
    
    protected function validateValues(){
        if(!in_array(null, $this->values)){
            $this->validateEmail();
        }else{
            throw new Exception('<div class="alert alert-danger text-center">Empty Field Detected</div>');
            return false;
        }
    }
    
    protected function validateEmail(){
        if(filter_var($this->values['Email'], FILTER_VALIDATE_EMAIL) == true){
            return true;
        }else{
            throw new Exception('<div class="alert alert-danger text-center">Invalid Email Address</div>');
            return false;
        }
    }
    
    protected function sql(){
        $this->sql = 'INSERT INTO contact_us(Name, Email, Message, Subject, Date_posted) VALUES(:name, :email, :message, :subject, :date_posted)';
    }

    
    public function runQuery(){
        $this->validateValues();
        $this->sql();
        $stmt = $this->con->prepare($this->sql);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':date_posted', $datePosted);
        
        $name = $this->values['Name'];
        $message = $this->values['Message'];
        $email = $this->values['Email'];
        $subject = $this->values['Subject'];
        $datePosted = $this->values['Date'];
        
        $stmt->execute();
        
    }
}

/*
$values = array(
        'Name' => 'bello rouf',
        'Email' => 'roufy235@gmail.com',
        'Message' => 'jfjf hjfhfjffnfjf fjfjfjviofnoifj ',
    );

try{
    $send = new CONTACT();
    $send->setValues($values);
    $send->runQuery();
}catch(Exception $e){
    echo $e->getMessage();
}
*/


