<?php
require_once('DB.php');
class CONTACT_US extends DB{
    public $name,
            $email,
            $tab,
            $phone,
            $sql,
            $stmt,
            $message;
    
    public function setValue($param,$param1, $param2, $param3, $param4){
        if(!empty($param) && !empty($param2) && !empty($param3) && !empty($param4)){
            $this->name = $param1;
            $this->email = $param2;
            $this->message = $param3;
            $this->tab = $param;
            $this->phone = $param4;
        }else{
            throw new Exception('<div class="alert alert-info text-center">fill all boxes</div');
            return false;
        }
    }
    
    protected function validateEmail(){
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            throw new Exception('<div class="alert alert-danger text-center">invalid email</div>');
            return false;
        }
    }
    
    protected function validatePhoneNo(){
        if(is_numeric($this->phone)){
            return true;
        }else{
            throw new Exception('<div class="alert alert-danger text-center">Invalid Phone Number</div>');
            return false;
        }
    }
    
    protected function sql(){
        $this->sql = 'INSERT INTO '.$this->tab.'(Name, Email, Message, Phone_no) VALUES(:name, :email, :message, :phone)';
    }
    
    protected function bindParameters(){
        $this->validateEmail();
        $this->validatePhoneNo();
        $this->sql();
        $this->stmt = $this->con->prepare($this->sql);
        $this->stmt->bindParam(':name', $name);
        $this->stmt->bindParam(':email', $email);
        $this->stmt->bindParam(':message', $message);
        $this->stmt->bindParam(':phone', $phone);
        $name = $this->name;
        $email = $this->email;
        $message = $this->message;
        $phone = $this->phone;
    }
    
    public function runQuery(){
        $this->bindParameters();
        $this->stmt->execute();
    }
}




