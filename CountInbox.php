<?php

require_once('DB.php');

class CountInbox extends DB{
    public $table,
            $row,
            $store,
            $instance,
            $query,
            $condition = array(),
            $value = array();
    
    
    public function setValues($param, array $param1, array $param2, $param3){
        if(!empty($param) && !empty($param1) && !empty($param2) && !empty($param3)){
            if(count($param1) == count($param2)){
                $this->table = $param;
                $this->instance = $param3;
                $this->condition = $param1;
                $this->value = $param2;
            }else{
                throw new Exception('array values are not the same in numbers');
                return false;
            }
            
        }else{
            throw new Exception('Variable are empty');
            return false;
        }
        
    }
    
    protected function loopAssign(){
        $num = count($this->condition);
        $i = 1;
        foreach($this->condition as $key => $value){
            if($i == count($this->condition)){
                $this->store .= $value . ' = ' . $this->value[$key];
            }else{
                $this->store .= $value . ' = ' . $this->value[$key]. ' '.$this->instance.' ';
            }
            $i++;
        }
    }
    
    
    public function countNum(){
        $this->loopAssign();
        $sql = 'SELECT * FROM '.$this->table.' WHERE '.$this->store;
        //return $sql;
        $this->query = $this->con->query($sql);
        $this->row = $this->query->rowCount();
        //echo $sql;
        return $this->row;
    }
    
    public function getIds(){
        if($this->row > 0){
            while($row = $this->query->fetchObject()){
                $getIds[] = $row->id;
            }
            return $getIds;
        }
    }
}


/*$field = array(
    'Sponsor_id',
    'Phone_no',
);
$value = array(
    12,
    '07031192189',
);
$table = 'user_info';
$instance = 'AND';
$count = new CountInbox();
$count->setValues($table, $field, $value, $instance);
echo $count->countNum();
print_r($count->getIds());*/


