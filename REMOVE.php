<?php

/* * ***********************************************************************************************************************
 *   Object Structure
 *
 *
 * 				*$value = array(
 * 							'id',
 * 							'=',
 * 							 2,
 * 				);
 * 				$del = new REMOVE();
 * 				$del->setValues('bus', $value);
 * 				$del->runQuery();
 *
 *
 *
 *
 *
 *
 *
 *
 * ******************************************************************************************************************************* */
require_once('DB.php');
class REMOVE extends DB {

    protected $_table,
            $_sql,
            $_condition = array();

    public static function howToUse(){
		$info = "<div style=\"margin:auto;border:5px solid red;width:60%;padding:10px;border-radius:10px;\">
		        <center><h1><u>HOW TO USE REMOVE CLASS</u></h1></center>
				 <u><b>NOTICE :</b></u> if you want to delete only one row<br />
				 
<pre>\$condition = array(
                'fieldname' ,
	         '=',
	         'value',
);</pre><br />
				   <div style=\"height:10px;\"></div>	
				<b> <code> \$del = new REMOVE();<br />
				\$del->setValues(TABLENAME, \$con);<br />
				 \$del->runQuery();<br /></b></code>
				 <hr />
				  <div style=\"height:10px;\"></div>	
				 <u><b>NOTICE :</b></u> if you want to delete all rows<br />
				
<pre>\$condition = array(

);</pre><br />
				  <div style=\"height:10px;\"></div>	
				 <b><code>\$del = new REMOVE();<br />
				 \$del->setValues(TABLENAME, \$con);<br />
				 \$del->runQuery();<br /></b></code>
				 
		          </div>";
				  echo $info;
				 
	}
	public function __construct() {
        parent::__construct();
    }
	

    public function setValues($param, $param2 = array()) {
        $this->_table = $param;
        $this->_condition = $param2;
    }

    public function checkCondition() {
        if (empty($this->_condition)) {
            return false;
        } else {
            return true;
        }
    }

    public function countArrayValues() {
        return count($this->_condition);
    }

    public function checkNumValuesInArray() {
        if ($this->checkCondition()) {
            if ($this->countArrayValues() > 3 || $this->countArrayValues() < 3) {
                die('<div class="alert alert-danger">Array value for field condition <b>greater than</b> or <b>less than</b> three(3)</div>');
            }
        }
    }

    public function runQuery() {
        $stmt = $this->con->prepare($this->setQuery());
        $stmt->execute();
    }

    public function setQuery() {
        $this->checkNumValuesInArray();
        if ($this->checkCondition()) {
            return $this->_sql = 'DELETE FROM ' . $this->_table . ' WHERE ' . $this->_condition[0] . ' ' . $this->_condition[1] . ' ' . $this->_condition[2];
        } else {
            return $this->_sql = 'DELETE FROM ' . $this->_table;
        }
    }

}
