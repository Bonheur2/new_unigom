        <?php include ('../../meet/con.php');
        if($conn){
            echo "One " .$_REQUEST['level'];
        }
        ?>
        <!-- Start app main Content -->
        <!--<div class="main-content">-->
        <!--    <section class="section">-->
        <!--        <div class="section-body">-->
        <!--            <div class="row">-->
                        <div class="col-20">
                            <div class="svg-container">
                                <a href ="tables/download_time_table.php?class=<?php echo $_REQUEST['classId'];?>&levelId=<?php echo $_REQUEST['levelId'];?>&termId=<?php echo $_REQUEST['term_id'];?>"  target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="red" class="bi bi-cloud-download" viewBox="0 0 16 16">
                                        <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                                        <path d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                                                                     
                                    <div class="table-responsive">
                                        <table class="table" id="my-time-table" border="1">
                            				<center>
                                    			<thead>
                                    				<tr> 
                                    					<th colspan="12">MIS Time Table <?php echo $theOverall['splz_full_name'];?></th>
                                    				</tr>
                                    			</thead>									        
                            			</center>
                                        <tbody
                                            <?php
                                            try{
                                            $Querry =$conn->prepare("select *from tbl_markby_module where reg_no ='".$_REQUEST['identification']."' and module_id IN(select mod_id from tbl_modules where splz_id ='".$_REQUEST['specialization']."')");
                                            $Querry->execute;
                                            
                                            // loop
                                            while($theQuerry =$Querry->fetch()){
                                                ?>
                                                <tr>
                								    <td>Hours/Day</td>
                								    <?php 
                									  $workDay =$conn->prepare("select *from tbl_t_days");
                									  $workDay->execute();
                									    
                									  while($thisWorkHour=$workDay->fetch()){
                								           ?>
                    									    <td id="not-sortable">
                        									    <?php
                        										    echo $thisWorkHour['day_name'];
                    										    ?>
                										    </td>
                										    <?php
                									     }
                									     ?>
    									                <?php
                									     $hours =$conn->prepare("select *from tbl_t_hours");
                									     $hours->execute();
                									     while($theHours =$hours->fetch()){
                									         ?>
                									         <tr>
                									           <td><?php echo $theHours['hour_label'];?> </td>
                									          
                									               <?php
                									               $days =$conn->prepare("select *from tbl_t_days");
                									               $days->execute();
                									               
                									               while($theDays =$days->fetch()){
                									                   $moduleDay =$conn->prepare("select *from tbl_t_schedule where sub_class_id ='".$_REQUEST['specialization']."'
                									                   and learning_day_id ='".$theDays['day_id']."' and hour_id ='".$theHours['hour_id']."' and level_id ='".$_REQUEST['level']."' 
                									                   and class_module_id ='".$theQuerry['module_id']."'");
                									                   $moduleDay->execute();
                									                   
                									                   if($moduleDay->rowCount()>0){
                    									                   $theModule =$moduleDay->fetch();
                    									                   
                    									                   // module 
                    									                   $moduleCode =$conn->prepare("select *from tbl_modules where splz_id ='".$theModule['sub_class_id']."'
                                                    					    and level_id ='".$_REQUEST['level']."' and mod_id='".$theModule['class_module_id']."'");
                    									                   $moduleCode->execute();
                    									                   $theModuleCode =$moduleCode->fetch();
                    									                   
                    									                   //module code
                    									                   $thisModCode =$conn->prepare("select *from modules where module_id ='".$theModule['class_module_id']."'");
                    									                   $thisModCode->execute();
                    									                   $theCode =$thisModCode->fetch();            									                   
                    									                   
                    									                   //room name
                    									                   $roomName =$conn->prepare("select *from tbl_block_rooms where room_id ='".$theModule['room_id']."'");
                    									                   $roomName->execute();
                    									                   $theRoomName =$roomName->fetch();									                   
                    									                   ?>
                    									                   <td>
                    									                   <?php
                        									                   if($moduleDay->rowCount()==1){
                        									                       if($theModule['class_module_id'] ==0){
                        									                          echo ""; 
                        									                       }
                        									                       else{
                        									                           echo $theCode['module_name']." ".$theCode['module_code']." ". "[".$theModuleCode['module_credits']."]"."<br><br>".$theRoomName['room_code'];
                        									                           // echo "Module Id ". $theModule['class_module_id']."<br> Week Hours ".$theModule['class_mod_week_hours']."<br> Room Id ".$theModule['room_id'];
                        									                       }
                        									                  
                        									                   }
                        									                   else if($moduleDay->rowCount()==2){
                        									                    echo "Module Id ". $theModule['class_module_id']."<br> Room Id ".$theModule['room_id'];
                        									                    $theModule =$moduleDay->fetch();
                        									                    echo "<br><br>";
                        									                    echo "Module Id ". $theModule['class_module_id']."<br> Room Id ".$theModule['room_id'];
                        									                   }
                    
                        									                   ?>
                        									                   </td>
                    									                   <?php
                    									                   //} 
                									                    }
                									                   
                									               }
                									               ?>
                									          
                									         </tr>
                									         <?php
                									     }
                                                    }
                                            }catch(Exception $exc){
                                                echo "Exception caught: " . $exc->getMessage() . "<br>";
                                                echo "Stack trace: " . $exc->getTraceAsString() . "<br>";
                                            }
            									?>    
                                            </tbody>
                                          </table>
                                    </div>
                                </div>
                            </div>
                        </div>
        <!--            </div>-->
        <!--        </div>-->
        <!--    </section>-->
        <!--</div>    -->