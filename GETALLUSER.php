<?php
require_once('DB.php');

class GETALLUSER extends DB{
    public $sql,
            $stmt;
    
    
    protected function sql(){
        $this->sql = 'SELECT * FROM user_info';
    }
    
    public function runQuery(){
        $this->sql();
        $this->stmt = $this->con->query($this->sql);
       echo '<div id="pre_activation" class="table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  
                </tr>
                </thead>
                <tbody>';
        $check = 4;
        $loop = 1;
        echo '<tr>';
        $total = $this->stmt->rowCount();
        
        while($row = $this->stmt->fetchObject()){
            if($loop == $check){
                echo '</tr><tr>';
                $check += 3;
            }
            $sql = 'SELECT * FROM userprofilepics WHERE Users_id = '.$row->id;
            $stmt = $this->con->query($sql);
            $rowNew = $stmt->fetchObject();
            if(empty($rowNew->File)){
                $file = '../users/uploads/47199326-profile-pictures.png';
            }else{
                $file = '../users/uploads/users/'.$rowNew->File;
            }
             ?>
                        <td><a href="viewUser?userId=<?php echo $row->id; ?>">
                             <div class="box box-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-aqua-active">
                              <h3 class="widget-user-username"><?php echo $row->Name; ?></h3>
                              <!-- <h5 class="widget-user-desc">Founder &amp; CEO</h5> -->
                            </div>
                            <div class="widget-user-image">
                              <img class="img-circle" src="<?php echo $file; ?>" alt="User Avatar">
                            </div>
                            <div class="box-footer">
                              <div class="row">
                                <div class="col-sm-4 border-right">
                                  <div class="description-block">
                                    <h5 class="description-header"><small><?php echo DATE('M, d Y', strtotime($row->Date_reg)); ?></small></h5>
                                    <span class="description-text text-muted"><small>Date Reg</small></span>
                                  </div>
                                  <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                  <div class="description-block">
                                    <h5 class="description-header"><small><?php echo $row->btc_address; ?></small></h5>
                                    <span class="description-text text-muted"><small>BTC</small></span>
                                  </div>
                                  <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                  <div class="description-block">
                                    <h5 class="description-header"><small><?php echo $row->Phone_no; ?></small></h5>
                                    <span class="description-text text-muted"><small>Phone No</small></span>
                                  </div>
                                  <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                              </div>
                              <!-- /.row -->
                            </div>
                          </div>
                         </a></td>
                        
                        <?php
                        $loop++;
                    }
        if(is_int($total/3)){
            
        }else{
            $total += 1;
            if(is_int($total/3)){
                 echo '<td></td>';
            }else{
                 echo '<td></td>';
                 echo '<td></td>';
            }
           
        }
                    echo '</tr>';
        
        echo '  </tbody>
               
              </table>';
    return;
    }
}