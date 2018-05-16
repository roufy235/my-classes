<?php

class DB {

    protected  $db_servername = 'localhost',
                $db_username = 'root',
                $db_password = '',
                $db_host,
                //$db_dbname = 'new_pdo',
                $db_dbname = 'whp',
                $con;
    public function __construct() {

        try {
            
            $this->con = new PDO("mysql:host=$this->db_servername;dbname=$this->db_dbname", $this->db_username, $this->db_password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            echo 'error' . $e->getMessage();
        }
    }
    
   protected function removeIfPreActivationElapse($param, $param1, $param2){
       $time = DATE('Y-m-d').' '.DATE('H:i:s');
       $start_date = new DateTime($param);
       $since_start = $start_date->diff(new DateTime($time));
       if(abs($since_start->format('%R%d day')) == 1){
            $plus = $since_start->format('%R%h hrs') - 24;//- + - = -
        }else{
            $plus = $since_start->format('%R%h hrs');
        }
       if($plus > 0 && $since_start->format('%R%i min') > 0){
            $field = array(
                'Users_id',
                'id',
                'Users_id',
            );
           $table = array(
               'userprofilepics',
               'user_info',
               'user_login_details',
           );
           
           foreach($table as $key => $value){
               $sql = 'DELETE FROM '.$value.' WHERE '.$field[$key].' = :id';
               $stmt = $this->con->prepare($sql);
               $stmt->bindParam(':id', $delId);
               $delId = $param1;
               $stmt->execute();
           }
           //removed profile pics
           $file = 'users/uploads/users/'.$param2;
           if(file_exists($file)){
               unlink($file);
           }
           $_SESSION['success'] = '<div class="alert alert-danger">
                                    <p>Your Account have Been removed from the system due to failure to pay <b>Pre-Activation Link</b></p>
                                    </div>';
            ?>
            <script>
                window.location.href="<?php echo url(); ?>login";
            </script>
     <?php
           die();
       }
   }
    
   public function login($param, $param2){
       if(!empty($param) && !empty($param2)){
           $sql = 'SELECT * FROM user_login_details WHERE Username = :username AND Password = :password';
           $stmt = $this->con->prepare($sql);
           //bind params
           $stmt->bindParam(':username', $username);
           $stmt->bindParam(':password', $password);
           
           //bind values
           $username = $param;
           $password = md5($param2);
           
           //run query
           $stmt->execute();
           $row = $stmt->rowCount();
           if($row > 0){
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                   $id = $row['Users_id'];

                   $newSql = 'SELECT * FROM user_info WHERE id = :id';
                   $newStmt = $this->con->prepare($newSql);

                   $newStmt->bindParam(':id', $id);



                   $newStmt->execute();
                   $row1 = $newStmt->fetch(PDO::FETCH_ASSOC);
               if($row1['Ban']){
                   throw new Exception('<div class="alert alert-danger">Account Blocked Contact Our Support Team</div>');
                   return false;
               }else{
                   //get user pics
                   $getPicsSql = 'SELECT * FROM userprofilepics WHERE Users_id = :id';
                   $getPicsStmt = $this->con->prepare($getPicsSql);

                   $getPicsStmt->bindParam(':id', $id);
                   $getPicsStmt->execute();
                   $getPic = $getPicsStmt->fetch(PDO::FETCH_ASSOC);


                   //this code removes user if he/she didn't pay pre-activation
                   if($row1['pre_activation'] == 0){
                       $this->removeIfPreActivationElapse($row1['Expiry_date'], $id, $getPic['File']);
                   }

                   $_SESSION['username'] = $row['Username'];
                   $_SESSION['admin_level'] = $row['Admin_level'];
                   $_SESSION['user_id'] = $id;
                   $_SESSION['wph_logged_in'] = 1;








                   $_SESSION['name'] = $row1['Name'];
                   $_SESSION['phone'] = $row1['Phone_no'];
                   $_SESSION['email'] = $row1['Email'];
                   $_SESSION['location'] = $row1['Country'];
                   $_SESSION['user_img'] = $getPic['File'];
               } 
           }else{
               throw new Exception('<div class="alert alert-danger">Invalid Username/Password Supplied</div>');
               return false;
           }
       }else{
           throw new Exception('<div class="alert alert-danger">Username/Password Fields are Required</div>');
           return false;
       }
   }
    
    
    public function adminLogin($param, $param1){
        if(!empty($param) && !empty($param1)){
            $sql = 'SELECT * FROM admin_login  WHERE Username = :username AND Password = :password';
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $username  =$param;
            $password = md5($param1);
           
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                   $_SESSION['AdminUsername'] = $row['Username'];
                   $_SESSION['password'] = $row['Password'];
                   $_SESSION['Admin_level'] = $row['Password'];
                   $_SESSION['AdminUser_id'] = $row['id'];
                   $_SESSION['wph_logged_in_Admin'] = 1;
            }else{
                throw new Exception('<div class="alert alert-danger text-center">Invalid Username/Password Supplied</div>');
                return false;
            }
        }else{
            throw new Exception('<div class="alert alert-danger text-center">All fields are Required</div>');
            return false;
        }
    }
    
   

}
