<?php
require_once('DB.php');

class myAccurateSelect extends DB{
    public $stmt,
            $queryStrings,
            $sql;
    
    public function setValues($param){
        $this->queryStrings = $param;
    }
    
    protected function sql(){
        $this->sql = $this->queryStrings;   
    }
    
    public function runQuery(){
        $this->sql();
        $this->stmt = $this->con->query($this->sql);
    }
    
    public function fetchMyValues(){
        if($this->stmt->rowCount() > 0){
            $row = $this->stmt->fetchObject();
        }else{
            $row = 'Empty';
        }
        
        return $row;
    }
}

/*
$get = new myAccurateSelect();
$get->setValues('SELECT * FROM pre_activation_link_hash_ids');
$get->runQuery();
while($row = $get->fetchMyValues()){
     echo $row->Hash_id.'<br />';
}
   */
