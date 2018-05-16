<?php
//require_once('DB.php');
require_once('DB.php');
class UPDATE extends DB{
    protected $_table,
              $_con = array(),
              $_names = array(),
              $_values = array(),
              $_sql,
              $_countArrayValues;
    
    public $sqlValues;
    
    public static function howToUse(){
        $info = "<div style=\"margin:auto;border:5px solid red;width:60%;padding:10px;border-radius:10px;\">
		        <center><h1><u>HOW TO USE UPDATE CLASS</u></h1></center>
				 
<pre>\$condition = array(
                'fieldname' ,
	         '=',
	         'value',
);</pre><br />
<pre>\$fields = array(
                'color' ,
	        
);</pre><br />
<pre>\$values = array(
                'yellow' ,
	         
);</pre><br />
				   <div style=\"height:10px;\"></div>	
				<b> <code> \$update = new UPDATE();<br />
				\$update->setValues(TABLENAME, \$fields, \$values);<br />
				\$update->setCon(\$conditions);<br />
				 \$update->runQuery();<br /></b></code>
				 
				 
		          </div>";
				  echo $info;
    }
    
    public function __construct(){
        parent::__construct();
    }
    
    public function setValues($param, $param2 = array(), $param3 = array()){
        $this->_table = $param;   
        $this->_values = $param3;
        $this->_names = $param2;
        
    }
    
    protected function checkTableIfExists(){
        if(!empty($this->_table)){
            $sql = 'SELECT * FROM '.$this->_table;
            $tableExist = $this->con->prepare($sql);
            if(!$tableExist->execute()){
                throw new Exception('Table does not exists');
            } 
            return;
        }
        throw new Exception('<b style="color:red;">First parameter is empty in setValues() function</b>');
    }
    
    public function setCon($param = array()){
        $this->_con = $param;
    }
    
    protected function checkArrayNamesIfEmpty(){
        if(empty($this->_names)){
            return true;
        }else{
            return false;
        }
    }
    protected function checkArrayValuesIfEmpty(){
        if(empty($this->_values)){
            return true;
        }else{
            return false;
        }
    }
    protected function checkArrayConIfEmpty(){
        if(empty($this->_con)){
            throw new Exception('<b style="color:red">The where clause for query statement is empty</b>');
            return true;
        }else{
            return false;
        }
    }
    protected function countConArray(){
        if(count($this->_con) == 3){
            return true;
        }else{
            throw new Exception('<b style="color:red">The Where clause values for query statement not up to three</b>, check the parameters...');
        }
    }
    
    protected function countArray(){
        if(count($this->_names) == count($this->_values)){
            $this->_countArrayValues = count($this->_names);
            return true;
        }else{
            throw new Exception('<b style="color:red;">Name(s) and Value(s) are not equal</b>, check the parameters...');
            
        }
    }
    
    protected function loopThroughforeach(){
        $this->checkArrayNamesIfEmpty();
        $this->checkArrayValuesIfEmpty();
       //if($this->countArray()){
        try{
            $this->countArray();
               $i = 1;
        foreach($this->_names AS $key => $value){
            if($i == $this->_countArrayValues){
                $this->sqlValues .= $value.' = '."'".$this->_values[$key]."'";
            }else{
                $this->sqlValues .= $value.' = '."'".$this->_values[$key]."'".', ';
            }
            $i++;
        }
        }catch(Exception $e){
            echo $e->getMessage();
            die();
        }
           
        }
       //}
    
    
    protected function sql(){
        try{
            $this->checkTableIfExists();
                 $this->loopThroughforeach();
            if($this->checkArrayConIfEmpty()){
                echo $this->_sql = "UPDATE ".$this->_table." SET ".$this->sqlValues; 
            }else{
                try{
                    $this->checkArrayConIfEmpty();
                    $this->countConArray();
                    if(!is_numeric($this->_con[2])){
                        $this->_con[2] = "'".$this->_con[2]."'";
                    }
                     $this->_sql = "UPDATE ".$this->_table." SET ".$this->sqlValues.' WHERE '.$this->_con[0].' '.
                         $this->_con[1].''.$this->_con[2];
                }catch(Exception $e){
                    echo $e->getMessage();
                    die();
                }
            }
        }catch(Exception $e){
            echo $e->getMessage();
            die();
        }
       
    }
    
    public function runQuery(){
        try{
             $this->sql();
             $stmt = $this->con->prepare($this->_sql);
           // echo $this->_sql;die();
            $stmt->execute();
        }catch(Exception $e){
            echo 'Unable to run this query=>'.$this->_sql;
        }
    }
}
//UPDATE::howToUse();