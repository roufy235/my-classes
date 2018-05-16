<?php
require_once('DB.php');
class REGISTER extends DB {

    protected $values = array(),
                $lastId,
               $sql = array();
    
    
    
    public function setValues($param = array()){
        $this->values = $param;
    }
   
    
    protected function checkArray(){
        if(count($this->values) == 13){
            if($this->values['Password'] == $this->values['ConfiPassword']){
                if($this->values['Secret'] == $this->values['ConfiSecret']){
                    return true;
                }else{
                    throw new Exception('<div class="alert alert-danger text-center">Secret pin Not match</div>');
                    return false;
                }
            }else{
                throw new Exception('<div class="alert alert-danger text-center">Password not match</div>');
                return false;
            }
        }else{
            return false;
        }
    }
    
    protected function ifAllValueExists(){
        if(!in_array(null, $this->values)){
            return true;
        }else{
            throw new Exception('<div class="alert alert-danger text-center"><i class="fa fa-frown"></i> All fields are required</div>');
            return false;
        }
    }
    
    
    protected function regSuccessfullEmail(){
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: World Help Pay <noreply@worldhelppay.net>" . "\r\n";
        
        $to = $this->values['Email'];
        
        $subject = 'Registration Successful';
        
        $message = '<p>Hi '.$this->values['Fullname'].'!</p>
                    <p>Welcome to World Help Pay(WHP)! Thanks so much for joining us. You’re on your way to super-productivity and beyond!</p>

                    <b>[who we are; our mission/ what we help you do; how it works]</b>
                    <p>WORLD HELP PAY is not a bank, WHP does not collect your fund, WHP is not an ONLINE WONDER BANK, HYIP, investment or MLM program. WHP is a community where people help each other thereby giving everyone a level playing ground. WHP gives you a technical basic platform, which helps millions of participants worldwide to find those who NEED help, and those who are ready to PROVIDE help for FREE. All donated funds to other participants are your help given by your own good will, absolutely gratis. If you are completely confident and certain in your actions and make up your mind to participate, we kindly ask you to study carefully all warnings and instructions first. WHP is just a moderator that give a chance that participants from all over the world can connect each other, help each other and can grow together. In cases of any matter regarding the topic our online consultants and CRO over support tickets are ready to help and answer all your questions. WFH...enriching generations. Together we can enrich the world....</p>                
                    <p>World Help Pay Team</p>
                    ';
        $message = wordwrap($message, 80);
        mail($to, $subject, $message, $headers);
        
    }
    
    protected function checkPhoneNo(){
        if(is_numeric($this->values['Phone']) && strlen($this->values['Phone']) >= 11){
            //checks if phone number exists 
            //$this->sql()
            $stmt = $this->con->prepare($this->sql[3]);
            $stmt->bindParam(':phone', $phone);
            $phone = $this->values['Phone'];
            $stmt->execute();
            $row = $stmt->rowCount();
            if($row == 0){
                return true;
            }else{
                throw new Exception('<div class="alert alert-info text-center">Phone Number Already Exists</div>');
                return false;
            }
        }else{
            throw new Exception('<div class="alert alert-info text-center">Phone Number is Invalid</div>');
            return false;
        }
    }
    
    public function runQuery(){
        
        $this->MyQueryStrings();//this array contains all sql queries used on this class
        
        if($this->ifAllValueExists()){
            
            $this->checkArray(); //checks if password and secret pin matches the confi   
            $this->checkPhoneNo();//validates phone number
            $this->validateEmail();//validates email
            $this->checkIfBtcExists(); //validates btc address if exists

            if($this->usernameExist()){
                //$this->sql();

                $stmt = $this->con->prepare($this->sql[0]);
                //bind params
                $stmt->bindParam(':sponsor', $sponsor);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':country', $country);
                $stmt->bindParam(':facebook', $facebook);
                $stmt->bindParam(':secret', $secret);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':btc', $btc);
                $stmt->bindParam(':expiryDate', $expiryDate);


                $date = DATE('Y-m-d H:i:s', strtotime('+48 hours'));



                //bind values
                $sponsor = $this->values['sponsor_id'];
                $name = $this->values['Fullname'];
                $phone = $this->values['Phone'];
                $country = $this->values['Country'];
                $facebook = $this->values['Facebook'];
                $secret = md5($this->values['Secret']);
                $email = $this->values['Email'];
                $btc = $this->values['BTC Address'];
                $expiryDate = $date;

                $stmt->execute();
                $last_id = $this->con->lastInsertId();
                $this->lastId = $last_id;



                $stmt1 = $this->con->prepare($this->sql[1]);
                //bind params
                $stmt1->bindParam(':id', $id);
                $stmt1->bindParam(':username', $username);
                $stmt1->bindParam(':password', $password);

                //bind values
                $id = $last_id;
                $username = $this->values['username'];
                $password = md5($this->values['Password']);

                $stmt1->execute();


                //userprofilepics
                $stmt2 = $this->con->prepare($this->sql[2]);
                //bind params
                $stmt2->bindParam(':id', $id1);

                //bind values
                $id1 = $last_id;


                $stmt2->execute();

                //$this->regSuccessfullEmail();

            }
                
            
        }
        
    }
    public function returnLastId(){
        return $this->lastId;
    }
    protected function usernameExist(){
        //$sql = 'SELECT * FROM user_login_details WHERE Username = :username';
        $stmt = $this->con->prepare($this->sql[5]);
        $stmt->bindParam(':username', $username);
        $username = $this->values['username'];
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num == 0){
            return true;
        }else{
            throw new Exception('<div class="alert alert-info text-center">Username Already Exists</div>');
            return false;
        }
    }
    
     protected function emailExists(){
        //$sql = 'SELECT * FROM user_info WHERE Email = :email';
        $stmt = $this->con->prepare($this->sql[6]);
        $stmt->bindParam(':email', $email);
        $email = $this->values['Email'];
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num == 0){
            return true;
        }else{
            throw new Exception('<div class="alert alert-info text-center">Email Address Already Exists</div>');
            return false;
        }
    }
    
    protected function checkIfBtcExists(){
        //$this->sql();
        $stmt = $this->con->prepare($this->sql[4]);
        $stmt->bindParam(':btc_address', $btc);
        $btc = $this->values['BTC Address'];
        $stmt->execute();
        $row = $stmt->rowCount();
        if($row == 0){
            return true;
        }else{
            throw new Exception('<div class="alert alert-danger text-center">BTC Address Already Exists</div>');
                return false;
        }
    }
    
    protected function validateEmail(){
        if(filter_var($this->values['Email'], FILTER_VALIDATE_EMAIL)){
            
            $this->emailExists();
            
        }else{
            throw new Exception('<div class="alert alert-info text-center">Invalid Email Address</div>');
            return false;
        }
    }
    
    protected function MyQueryStrings(){

        $this->sql[] = 'INSERT INTO user_info(Sponsor_id, Name, Phone_no, Country, Facebook_url, Secret_pin, Email, btc_address,                             Expiry_date) VALUES(:sponsor, :name, :phone, :country, :facebook, :secret, :email, :btc, :expiryDate)';
        
        $this->sql[] = 'INSERT INTO user_login_details(Users_id, Username, Password)VALUES(:id, :username, :password)';
        
        $this->sql[] = 'INSERT INTO userprofilepics(Users_id)VALUES(:id)';
        
        $this->sql[] = 'SELECT * FROM user_info WHERE Phone_no = :phone';
        
        $this->sql[] = 'SELECT * FROM user_info WHERE btc_address = :btc_address';
        
        $this->sql[] = 'SELECT * FROM user_login_details WHERE Username = :username';
        
        $this->sql[] = 'SELECT * FROM user_info WHERE Email = :email';
    }
    
}
