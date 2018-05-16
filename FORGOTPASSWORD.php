<?php
require_once('DB.php');

class FORGOTPASSWORD extends DB{
    public $email,
            $name,
            $token,
            $stmt,
            $table,
            $shuffle,
            $myVariables = array(),
            $sql = array();
    //generate token
    public function setValue($param, $param2){
        if(!empty($param) && !empty($param2)){
            $this->email = $param;
            $this->table = $param2;
        }else{
            throw new Exception('<div class="alert alert-danger text-center">Empty Field Detected</div>');
            return false;
        }
    }
    protected function validateEmail(){
        if(filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            throw new Exception('<div class="alert alert-danger text-center">Invalid Email Address</div>');
            return false;
        }
    }
    
    protected function email(){
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: World Help Pay <noreply@worldhelppay.com>" . "\r\n";
        
        $to = $this->email;
        
        $subject = 'Password Reset';
        
        $message = 'Hi '.$this->name.', <br />
                    <p>We\'ve received a request to reset your password. </p>
                    <p>if you didn\'t make the request , just ignore this email. Otherwise you can reset your password using this link:</p>
                    <a href="'.url().'login?reset='.$this->shuffle.'">
                        <div style="color:white;background-color:red;text-align:center;">Click here to reset your password</div>
                    </a>
                    <p>Thanks</p>
                    World Help Pay Team
                    ';
        $message = wordwrap($message, 80);
        mail($to, $subject, $message, $headers);
    }
    protected function sql(){
        $this->sql[] = 'SELECT * FROM '.$this->table.' WHERE Email = :email';
        $this->sql[] = 'UPDATE '.$this->table.' SET Tokens = :tokens WHERE Email = :email';
        $this->sql[] = 'SELECT * FROM '.$this->table.' WHERE Tokens = :token';
        $this->sql[] = 'UPDATE user_login_details SET Password = :password WHERE Users_id = :id';
        $this->sql[] = 'UPDATE '.$this->table.' SET Tokens = :tokens WHERE id = :id';
    }
    public function runQuery(){
        $this->validateEmail();
        $this->sql();
        $stmt = $this->con->prepare($this->sql[0]);
        $stmt->bindParam(':email', $email);
        $email = $this->email;
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $row = $stmt->fetchObject();
            $this->name = $row->Name;
            $stmt1 = $this->con->prepare($this->sql[1]);
            $stmt1->bindParam(':tokens', $tokens);
            $stmt1->bindParam(':email', $email);
            $str = '12345abcdefghijk';
            $this->shuffle = str_shuffle(strtoupper($str));
            $tokens = $this->shuffle;
            $stmt1->execute();
            $this->email();//send mail
        }else{
            throw new Exception('<div class="alert alert-danger text-center">Email Not Available</div>');
            return false;
        }
    }
    //end
    //reset pass check toke
    public function getValue($param, $param2){
        if(!empty($param) && !empty($param2)){
            $this->token = $param;
            $this->table = $param2;
        }
    }
    public function checkIfTokenAvailable(){
        $this->sql();
        $this->stmt = $this->con->prepare($this->sql[2]);
        $this->stmt->bindParam(':token', $token);
        $token = $this->token;
        $this->stmt->execute();
        if($this->stmt->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    //end
    //change password
    public function setNewValues($param, $param1, $param2, $param3){
        if(!empty($param) && !empty($param1) && !empty($param2) && !empty($param3)){
            $this->table = $param;
            $this->myVariables[] = $param1;//password
            $this->myVariables[] = $param2;// confi password
            $this->myVariables[] = $param3;//token
        }else{
            throw new Exception('<div class="alert alert-danger text-center">Empty Fields Detected</div>');
            return false;
        }
    }
    protected function checkMatchPass(){
        if($this->myVariables[0] == $this->myVariables[1]){
            return true;
        }else{
            throw new Exception('<div class="alert alert-danger text-center">Password Not Match</div>');
            return false;
        }
    }
    public function resetRunQuery(){
        $this->checkMatchPass();
        $this->sql();
        $stmt = $this->con->prepare($this->sql[2]);
        $stmt->bindParam(':token', $token);
        $token = $this->myVariables[2];
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $row = $stmt->fetchObject();
            $id = $row->id;
            $stmt1 = $this->con->prepare($this->sql[3]);
            $stmt1->bindParam(':password', $password);
            $stmt1->bindParam(':id', $id);
            $password = md5($this->myVariables[0]);
            $stmt1->execute();
            
            //erase tokens
            $stmt2 = $this->con->prepare($this->sql[4]);
            $stmt2->bindParam(':tokens', $empty);
            $stmt2->bindParam(':id', $id);
            $empty = '';
            $stmt2->execute();
        }else{
            throw new Exception('<div class="alert alert-info text-center">Token Not Available</div>');
            return false;
        }
    }
}




