<?php

/* * ******************************************************************************************************************
 * HOW TO 																											
 * $select  = new SELECT();																							
 * $condition = array();		the will hold the conditional statement(field_name, sign, value) for the sql query i.e 	
 * 							where field_name   rep table field name;												
 * 						  sign       rep the operator(=, >, <, >=, <=);												
 * 						  value       rep the name to select in the table;											
 * 						if you are select all just declare the array and leave the values empty;					
 * 																													
 * $fieldtoquery = array(); 																							
 * $select->setValues(tablename, $condition);																		
 * $select->SupplyFieldToDisplay($fieldtoquery);																	
 * $select->selectManyData();																						
 * 																													
 * example below																										
 * 																													



  $select = new SELECT();


  $con = array('id', '=', $_SESSION['user_id']);
  $field = array('Firstname', 'Lastname', 'Email', 'Gender');

  $select->setValues('user_info', $con);
  $select->SupplyFieldToDisplay($field);a
  $select->outPutManyRows(field); //outPut with this if your query result contains many   rows.....
  $select->outPutOneRow(field); //outPut with this if your query result only contains one row.....

 * ******************************************************************************************************************** */
require_once('DB.php');
class SELECT extends DB {
    /*
     * class properties;
     */

    protected $_table,
            $_where = array(),
            $_fieldToDisplay = array(),
            $_division,
            $_sql,
            $_outPutOneUser,
            $_store = array();
    public $newStore, //outPut with this if your query result contains many   rows.....
            $newOneStore, //outPut with this if your query result only contains one row.....
            $numRows; //numbers of result

    /*
     * this will run the parent construct...
     */
    public function __construct() {
        parent::__construct();
    }

    public static function howToUse() {
        $info = "<div style=\"margin:auto;border:5px solid red;width:60%;padding:10px;border-radius:10px;\">
		<center><h1><u>HOW TO USE SELECT CLASS</u></h1></center>
		First of all declare new OBJECT and two ARRAYS;<br />
		         one for Condition and second for Table fields<br />
				  <div style=\"height:10px;\"></div>	
				 <u>ARRAY Condition</u><br />			
<pre>\$condition = array(
		 'fieldname',
		 '=',
		 'value',
);</pre><br />
				  <u>ARRAY Fields to display</u><br />
<pre>\$fields = array(
		TABLE FIELD NAME WILL BE LISTED HERE;
);</pre><br />
				  <div style=\"height:10px;\"></div>	
				 <u><b>NOTICE:</b></u> if you don't want to sort , just declare the \$condition array to be empty.. e.g \$condition = array();<br />
				  <div style=\"height:10px;\"></div>	
				<code>
		           <b>\$name = new SELECT();<br />
				    \$name->setValues(TABLENAME , \$con);<br />
				\$name->SupplyFieldToDisplay(\$fields);<br /></b>
				 </code>
				  <div style=\"height:10px;\"></div>	
				 <u><b>OPTIONAL</b></u><br /><ul>
				<li> if you want to ORDER AND LIMIT</li>
				  <code>   <b> \$name->orderByAndLimit(FEILD YOU WANT TO ORDER IT WITH, 'DESC', 1, 5);<br /></b></code>
				  <hr />
				 <li>if you want to ORDER ONLY</li>
				 <code>  <b>  \$name->orderBy(FEILD YOU WANT TO ORDER IT WITH, 'DESC');<br /></b></code>
				 <hr />
				 <li>if you want to LIMIT ONLY</li>
				 <code>   <b> \$name->Limit(0, 5);<br /></b></code> <hr />
				 <li>if you just want select ONLY</li>
				<code>   <b>  \$name->noOrderNoLimit();<br /></b></code> <hr />
				 </ul>
				  <div style=\"height:10px;\"></div>	
				 <u><b>RESULT WITH MORE THAN ONE ROW</b></u><br />
				 <code> <b> echo  \$name->outPutManyRows(FEILD YOU WANT TO DISPLAY);<br /></b></code>
				  <div style=\"height:10px;\"></div>	
				 <u><b>RESULT WITH ONLY ONE ROW</b></u><br />
				 <code>  <b> echo  \$name->outPutOneRow(FEILD YOU WANT TO DISPLAY);<br /></b></code><br />
                   <div style=\"height:10px;\"></div>
                 <u><b>To Get Total Number of Rows in a query</b></u><br />
				 <code>  <b> echo  \$name->numRows();<br /></b></code></div>";
        echo $info;
    }
    /*
     * setValues()
     * $param value is the name of the table
     * $param2 is an array, for fields
     */

    public function setValues($param, $param2 = array()) {
        $this->_table = $param;
        $this->_where = $param2;
    }
    
    public function SupplyFieldToDisplay($param = array()) {
        $this->_fieldToDisplay = $param;
        $this->_division = count($param);
    }
    
    protected function _chectArray() {
        if (empty($this->_where)) {
            return true;
        } else {
            return false;
        }
    }
    
    protected function _countArrayValue() {
        return count($this->_where);
    }

    public function noOrderNoLimit() {
        if ($this->_chectArray()) {
            $this->_sql = 'SELECT * FROM ' . $this->_table;
        } else {
            $this->_sql = 'SELECT * FROM ' . $this->_table . ' WHERE ' . $this->_where[0] . ' ' . $this->_where[1] . ' ' . $this->_where[2];
        }
    }

    public function orderBy($param, $param2) {
        if ($this->_chectArray()) {
            $this->_sql = 'SELECT * FROM ' . $this->_table . ' ORDER BY ' . $param . ' ' . $param2;
        } else {
            $this->_sql = 'SELECT * FROM ' . $this->_table . ' WHERE ' . $this->_where[0] . ' ' . $this->_where[1] . ' ' . $this->_where[2] .  ' ORDER BY ' . $param . ' ' . $param2;
        }
    }

    public function orderByAndLimit($param, $param2, $param3, $param4) {
        if ($this->_chectArray()) {
            $this->_sql = 'SELECT * FROM ' . $this->_table . ' ORDER BY ' . $param . ' ' . $param2 . ' LIMIT ' . $param3 . ',' . $param4;
        } else {
            $this->_sql = 'SELECT * FROM ' . $this->_table . ' WHERE ' . $this->_where[0] . ' ' . $this->_where[1] . ' ' . $this->_where[2] .  ' ORDER BY' . $param . ' ' . $param2 . ' LIMIT ' . $param3 . ',' . $param4;
        }
    }

    public function Limit($param, $param2) {
        //$this->_sql = 'SELECT * FROM ' . $this->_table . ' LIMIT ' . $param . ',' . $param2;
        if ($this->_chectArray()) {
            $this->_sql = 'SELECT * FROM ' . $this->_table . ' LIMIT ' . $param . ' , ' . $param2;
        } else {
            $this->_sql = 'SELECT * FROM ' . $this->_table . ' WHERE ' . $this->_where[0] . ' ' . $this->_where[1] . ' ' . $this->_where[2] . ' LIMIT ' . $param . ' , ' . $param2;
        }
    }

    protected function _conditionNotAvailable() {
        $stmt = $this->con->prepare($this->_sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception();
            //echo $e->getMessage();
            return false;
        }
        $count = $stmt->rowCount();
        $this->numRows = $count;
        $this->_outPutOneUser = $count;
        if ($count > 0) {
            /* while($rowe = $stmt->fetchAll()){
              echo $rowe["name"].'<br />';
              } */
            $row = $stmt->fetchAll();
            //$this->openTable();
            foreach ($row AS $value) {
                //$this->openRow();
                foreach ($this->_fieldToDisplay AS $value2) {
                    //$this->openColumn();
                    $this->_store[] = $value[$value2];
                    //$this->closeColumn();
                }
                //$this->closeRow();
            }
            //$this->closeTable();
        } else {
           /* throw new Exception('<div class="alert alert-info alert-dismissable fade">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				 <strong>Empty.</strong>
			</div>');*/
            $this->_store[] = '<div class="alert alert-info alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				 <strong>Empty.</strong>
			</div>';
        }
    }
    
    protected function _conditionAvailable() {
        if ($this->_countArrayValue() < 3 || $this->_countArrayValue() > 3) {
            die('<div class="alert alert-danger">Array value for field condition <b>greater than</b> or <b>less than</b> three(3)</div>');
        }
        //$sql = 'SELECT * FROM '.$this->_table.' WHERE '.$this->_where[0].' '.$this->_where[1].' '.$this->_where[2];
        $stmt = $this->con->prepare($this->_sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
        // echo $e->getMessage();
            //echo $e->getMessage();
           // echo $this->_sql;
            echo 'An error occurred while fetching data...............';
            return false;
        }
        $count = $stmt->rowCount();
        $this->numRows = $count;
        $this->_outPutOneUser = $count;
        if ($count > 0) {
            $row = $stmt->fetchAll();
            foreach ($row AS $value) {
                foreach ($this->_fieldToDisplay AS $value2) {
                    $this->_store[] = $value[$value2];
                }
            }
        } else {
            /*throw new Exception('<div class="alert alert-info alert-dismissable fade">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				 <strong>Empty.</strong>
			</div>');*/
            $this->_store[] = '<div class="alert alert-info alert-dismissable fade">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				 <strong>Empty.</strong>
			</div>';
        }
    }

    protected function _selectManyData() {
        //parent::__construct();
        if ($this->_chectArray()) {

            $this->_conditionNotAvailable();
        } else {

            $this->_conditionAvailable();
        }
    }

    protected function _saveValuesToArray() {
        $this->_selectManyData();
        $i = 0;
        $start = 0;
        $check = 0;
        $getResult = count($this->_store) / $this->_division;
        for ($i = 0; $i <= $getResult; $i++) {
            $slice = array_slice($this->_store, $start, $this->_division);
            $checkInput = 0;
            foreach ($slice AS $value) {
                if ($check <= $this->_division) {
                    $this->_fieldToDisplay[$checkInput];
                }
                $this->newStore[$this->_fieldToDisplay[$checkInput]][] = $value;
                if ($this->_outPutOneUser == 1) {
                    $this->newOneStore[$this->_fieldToDisplay[$checkInput]] = $value;
                }
                //echo '<br />';
                //print_r($this->newStore);
                $checkInput++;
            }
            $start += $this->_division;
        }
        //return;
    }

    public function numRows() {
        if ($this->_chectArray()) {
            $this->_conditionNotAvailable();
        } else {
            $this->_conditionAvailable();
        }
        return $this->numRows;
    }

    public function outPutOneRow($param) {
        $this->_saveValuesToArray();
        return $this->newOneStore[$param];
    }

    public function outPutManyRows($param) {
        $this->_saveValuesToArray();
        return $this->newStore[$param];
    }
    
   /* public function returnBack($param){
        echo '<pre>';
       print_r($this->_store);
        if(empty($this->_store)){
            echo 'yes empty';
        }else{
            echo 'not empty';
        }
        
    }*/
}

//SELECT::howToUse();




