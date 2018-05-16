<?php
require_once('DB.php');

class getMatchedWith extends DB{
    
    public $tab,
            $user_id,
            $my_id,
            $result,
            $percentage;
    
    
    public function setValues($param, $param1, $param2){
        if(!empty($param) && !empty($param1) && !empty($param2)){
            $this->tab = $param;
            $this->user_id = $param1;
            $this->percentage = $param2;
        }
    }
    public function getPercentage(){
        $sql = 'SELECT * FROM '.$this->tab.' WHERE Users_id = :id AND Thirty_per_confirm = 1 AND Seventy_per_confirm = 1 AND GetHelp_id > 0 AND ConfirmRecPayment = 0';
        $stmt = $this->con->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $id = $this->user_id;
        $stmt->execute();
        $row = $stmt->rowCount();
        if($row > 0){
            $value = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->my_id = $value['id'];
            $per = ($this->percentage/100*$value['Amount']);//thirty percentage of the amount the user ph
             $this->result = $per;
        }
    }
    public function getId(){
        $this->getPercentage();
        return $this->my_id;
    }
    public function percentage(){
        $this->getPercentage();
        return $this->result;
    }
    
}







