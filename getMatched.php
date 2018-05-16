<?php

class getMatched extends DB{
    public $sql,
            $result,
            $tab,
            $countIt,
            $condition = array(),
            $stmt;
    
    
    
    public function sql(){
        $this->sql = 'SELECT * FROM '.$this->tab.' WHERE '.$this->condition[0].' = :pay '.$this->condition[1].' '.$this->condition[2].' = :rec';
        return $this->sql;
    }
    
    public function setFields($param = array()){
        if(count($param) == 3){
            $this->condition = $param;
        }else{
            throw new Exception('Fields array should have three values');
            return;
        }
        
    }
    
    public function setValues($param2, $param = array()){
        
        if(count($param) == 2){
            $this->tab = $param2;
            $this->sql();
            $this->stmt = $this->con->prepare($this->sql);
            $this->stmt->bindParam(':pay',$pay);
            $this->stmt->bindParam(':rec',$rec);
            $pay = $param[0];
            $rec = $param[1];
        }else{
            throw new Exception('Values array should have two values');
            return;
        }
        
        
    }
    
    protected function executeCode(){
        try{
            $this->stmt->execute();
            $this->countIt = $this->stmt->rowCount();
        }catch(Exception $e){
            echo 'Unable to run the query';
            return;
        }
    
    }
    
    public function runQuery(){
        $this->executeCode();
        if($this->countIt  > 0){
            $this->result = $this->stmt->fetchAll();
        }else{
            echo 'Not available';
        }
       
    }
    
    public function showResult(){
        return $this->result;
    }
    
}