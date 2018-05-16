<?php
require_once('DB.php');
class INSERT extends DB{
    protected $_table,
              $_fields = array(),
              $_values = array(),
              $_arrayValueCount,
              $_assignFields,
              $_sql,
              $_lastId,
              $_moreFieldsDiv, 
              $_assignValues;
    public $assignBindParamStructure,
           $stmt,
           $assignBindParamStructureArray = array(),
           $assignBindParamVariable = array();
    
    
    public function __construct(){
        parent::__construct();
    }
    public static function howToUse(){
		$info = "<div style=\"margin:auto;border:5px solid red;width:60%;padding:10px;border-radius:10px;\">
		        <center><h1><u>HOW TO USE INSERT CLASS</u></h1></center>
				 
<pre>\$field = array(
    'Email',
    'Gender',
);</pre><br />
<pre>\$value = array(
    'ghghgh@gmail.com',
    'Male',
);</pre><br />
<u><b>NOTICE :</b></u> To insert multiple values <br />
<pre>\$value = array(
    'ghghgh@gmail.com',
    'Male',
    'ade23@gmail.com',
    'Male',
    'folasade90@gmail.com',
    'Female',
);</pre><br />
				   <div style=\"height:10px;\"></div>	
				<b> <code> \$test = new INSERT();<br />
				\$test->setValues(\$table, \$field, \$value);<br />
				 \$test->runQuery();<br /></b></code>
				 <hr />
                 <u><b>NOTICE :</b></u> Get last insert id with below function<br />
				 <code>\$test->lastId();</code>";
				  echo $info;
				 
	}
    protected function checkIfParamIsArray($param){
        if(is_array($param)){
            return true;
        }else{
            throw new Exception('"'.$param .'" is not an ARRAY value');           
        }
    }
    
    public function setValues($param, $param2, $param3){
        $this->_table = $param;
        try{
             if($this->checkIfParamIsArray($param2)){
                    $this->_fields = $param2;
                }
                if($this->checkIfParamIsArray($param3)){
                    $this->_values = $param3;
                }
            $this->checkIfArrayValuesAreEqual();
            $this->assign();
            $this->setSql();
                
        }catch(Exception $e){
            echo $e->getMessage();
            die();
        }
        
    }
    
    protected function checkIfArrayValuesAreEqual(){
        if(count($this->_fields) == count($this->_values)){
            $this->_arrayValueCount = count($this->_fields);
            $this->_moreFieldsDiv = 1;
            return true;
        }elseif(is_int(count($this->_values) / count($this->_fields))){
            $this->_arrayValueCount = count($this->_fields);
            $this->_moreFieldsDiv = count($this->_values) / count($this->_fields);
            return true;
        }else{
            throw new Exception('Field(s) array and Value(s) array are not equal');
        }
    }
    public function lastId(){
        return $this->_lastId;
    }
    
    protected function assign(){
        $i = 1;
        foreach($this->_fields AS $value){
            if($i == $this->_arrayValueCount){
                $this->_assignFields .= $value; 
                $this->assignBindParamStructure .= ':'.$value;
            }else{
                $this->_assignFields .= $value.', ';
                $this->assignBindParamStructure .= ':'.$value.', ';
            }
            $this->assignBindParamStructureArray[] .= ':'.$value;
            $this->assignBindParamVariable[] = $value;
            $i++;
            
        }
        
        $a = 1;
        foreach($this->_values AS $value){
            if($a == $this->_arrayValueCount){
                $this->_assignValues .= "'".$value."'";
            }else{
                $this->_assignValues .= "'".$value."', ";
            }
            $a++;
            
        }
    }
    
    protected function setSql(){
        //$this->_sql = 'INSERT INTO '.$this->_table.'('.$this->_assignFields.') VALUES('.$this->_assignValues.')';
        $this->_sql = 'INSERT INTO '.$this->_table.'('.$this->_assignFields.') VALUES('.$this->assignBindParamStructure.')';
        //echo $this->_sql;
        
    }
    
    
    public function checkUsername($param){
        
            $check = 'SELECT * FROM users WHERE Username = :username';
        $stmt = $this->con->prepare($check);
            $stmt->bindParam(':username', $username);
             $username = $param;
             $stmt->execute();
             if($stmt->rowCount() > 0){
                 return true;
             }else{
                 return false;
             }
            
    }

    
    
    
    
    
    public function PDOprepareStmt(){
         //$this->_sql = 'INSERT INTO '.$this->_table.'('.$this->_assignFields.') VALUES('.$this->assignBindParamStructure.')<br />';
         $this->stmt = $this->con->prepare($this->_sql);
        foreach($this->assignBindParamStructureArray AS $key => $value){
            $this->stmt->bindParam($value , ${$this->assignBindParamVariable[$key]});
        }
        
        for($i = 1; $i <= $this->_moreFieldsDiv; $i++){
       // }
        //assign param variable
        foreach($this->assignBindParamVariable AS $key => $value){
            if($i != 1){
                $key += $i;
            }
             ${$value} = $this->_values[$key];
        }
        $this->stmt->execute();
            $this->_lastId = $this->con->lastInsertId();
         }
        
    }
    
    public static function checkIfValueIsEmpty($param){
        if(empty($param)){
            return true;
        }else{
            return false;
        }
    }
    
    public function checkIfEmailExists($param, $param2, $param3){
        $sql = 'SELECT * FROM '.$param.' WHERE '.$param2.' = "'.$param3.'"';
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public function runQuery(){
       // echo $this->_sql;
        //$this->con->exec($this->_sql);
        //$this->_lastId = $this->con->lastInsertId();
        //$this->PDOprepareStmt();
        /*echo '<pre>';
        print_r($this->assignBindParamVariable);*/
       $this->PDOprepareStmt();
       // $this->stmt->execute();
    }
}
//INSERT::howToUse();
/*$test = new INSERT();
$field = array(
    'Package_name',
    'Package_price',
    'Package_id',
);
$a = str_shuffle('abcdedhgij1234567');
$value = array(
    'VETERAN',
    100000,
    $a,
   /* 'PROFESSIONAL',
    10000,
    'd',
    'PREMIUM',
    20000,
    'd',
    'ULTIMATE',
    50000,
    'd',
    'ULTIMATE',
    100000,
    'd',
    
);
$test->setValues('packages', $field, $value);
$test->runQuery();
echo 'success';*/