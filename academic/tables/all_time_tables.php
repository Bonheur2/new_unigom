        
        <?php include ('../../meet/con.php');?>
        <!-- Start app main Content -->
        <!--<div class="main-content">-->
        <!--    <section class="section">-->
        <!--        <div class="section-body">-->
        <!--            <div class="row">-->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
                        <div class="col-20">
                            <div class="svg-container">
                                <a href ="tables/download_time_table.php?term=<?php echo $_REQUEST['term'];?>"  target="_blank" title="Download PDF">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="red" class="bi bi-cloud-download" viewBox="0 0 16 16">
                                        <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                                        <path d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                                                                     
                                    <div class="table-responsive">
                                        <table class="table table-striped v_center" id="table-1" border="1">
                            				<center>
                                    			<thead>
                                    				<tr> 
                                    					<th colspan="6">MIS Time Tables</th>
                                    				</tr>
                                    			</thead>									        
                            			    </center>
                                                    <tbody>
                        						    <?php
                        						        try{
                        									  $classTimeTable =$conn->prepare("select *from tbl_t_schedule where term_id ='".$_REQUEST['term']."' GROUP BY program_type, program_mode, sub_class_id");
                        									  $classTimeTable->execute();
                        									  $counter =0;
                        									  while($theTable =$classTimeTable->fetch()){
                        									      
                        									   //   level 
                            									  $classLevel =$conn->prepare("select level_id from tbl_t_schedule where sub_class_id ='".$theTable['sub_class_id']."'  GROUP BY level_id");
                            									  $classLevel->execute();
                            									  while($theClassLevel =$classLevel->fetch()){
                            									  
                                    								// 	  the term
                                    								$levelId =$theClassLevel['level_id'];
                                    								
                                									  $levelTerm =$conn->prepare("select * from tbl_t_schedule where sub_class_id ='".$theTable['sub_class_id']."' and level_id ='".$theClassLevel['level_id']."' GROUP BY term_id");
                                									  $levelTerm->execute();  
                                									  while($theLevelTerm =$levelTerm->fetch()){
                                									      
                                									   //   vars
                                									   
                                									      $levelTermId =$theLevelTerm['term_id'];
                                									      $intake =$theLevelTerm['intake_id'];
                                									      $counter +=1;
                                									      
                                									    //   class names
                                									    $classToPrint =$conn->prepare("select splz_full_name from tbl_specialization where splz_id ='".$theTable['sub_class_id']."'");
                                									    $classToPrint->execute(); 
                                									    $theClassToPrint =$classToPrint->fetch();
                                									    
                                									   // $the class name
                                									   $class =$theClassToPrint['splz_full_name'];
                                                                									   
                                                                        // type
                                                                        $programType =$conn->prepare("select prg_type_full_name from tbl_program_type where prg_type_id =:prg_type_id");
                                                                        $programType->bindParam(':prg_type_id',$theTable['program_type']);
                                                                        $programType->execute();
                                                                        $theType =$programType->fetch();
                                                                        
                                                                        $theTypeName =$theType['prg_type_full_name'];
                                                                        
                                                                        // mode
                                                                        $prograMode =$conn->prepare("select prg_mode_full_name from tbl_program_mode where prg_mode_id =:prg_mode_id");
                                                                        $prograMode->bindParam(':prg_mode_id',$theTable['program_mode']);
                                                                        $prograMode->execute();
                                                                        $theMode =$prograMode->fetch();
                                                                        
                                                                        $theModeName =$theMode['prg_mode_full_name'];                                									   
                                									      ?>
                                									      <tr style="background-color:lightgreen; text-align:center;">
                                									          <td><?php echo $counter;?></td>
                                									          <td colspan ="5" style="text-align:center;"> <?php echo $class ." Level: ".$levelId." Term: ".$levelTermId." Program-Type: ".$theTypeName." Program-Mode: ".$theModeName;?></td>
                                									      </tr>
                                                							<tr class="gradeX">
                                                							    
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
                                                							</tr>
                                                							<?php
                                                									     
                                                							$hours =$conn->prepare("select *from tbl_t_hours");
                                                							$hours->execute();
                                                						    while($theHours =$hours->fetch()){
                                                							    ?>
                                                							    <tr>
                                                								    <td><?php echo $theHours['hour_lower_limit'].'-'.$theHours['hour_upper_limit'];?> </td>
                                                								    
                                                									<?php
                                                									$days =$conn->prepare("select *from tbl_t_days");
                                                									$days->execute();
                                                									while($theDays =$days->fetch()){
                                                									    
                                                									$moduleDay =$conn->prepare("select *from tbl_t_schedule where sub_class_id ='".$theTable['sub_class_id']."'
                                                									and learning_day_id ='".$theDays['day_id']."' and hour_id ='".$theHours['hour_id']."' and level_id ='".$levelId."' and term_id ='".$levelTermId."' and class_module_id >0");
                                                									$moduleDay->execute();
                                                									$theModule =$moduleDay->fetch();
                                                									                   
                                                									// module 
                                                									$moduleCode =$conn->prepare("select *from tbl_modules where splz_id ='".$theModule['sub_class_id']."'
                                                                                	and level_id ='".$levelId."' and term_id ='".$levelTermId."' and mod_id='".$theModule['class_module_id']."'");
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
                                                									
                               									                   // module teacher 
                                									                $modTeacher =$conn->prepare("select staff_id, staff_first_name, staff_family_name from tbl_staff where staff_id IN(select staff_id from  tbl_module_leader where module_id ='".$theModuleCode['module_id']."')");
                                									                $modTeacher->execute();
                                									                $theTeacher =$modTeacher->fetch();                                                									
                                                									?>
                                                								<td>
                                                								<?php
                                                    							    if($moduleDay->rowCount()==1){
                                                    								    if($theModule['class_module_id'] ==0){
                                                    									   echo ""; 
                                                    									}
                                                    									else{
                                                    									    echo $theCode['module_name']." ".$theCode['module_code']." ". "[".$theModuleCode['module_credits']."]"."<br>  Room ".$theRoomName['room_code']."<br>".$theTeacher['staff_first_name']." ".$theTeacher['staff_family_name'];
                                                    									   // echo "Module Id ". $theModule['class_module_id']."<br> Week Hours ".$theModule['class_mod_week_hours']."<br> Room Id ".$theModule['room_id'];
                                                    								    }
                                                    								}
                                                    							    else if($moduleDay->rowCount()==2){
                                                    								    echo "Module Id ". $theModule['class_module_id']."<br> Room Id ".$theRoomName['room_code'];
                                                    									$theModule =$moduleDay->fetch();
                                                    									echo "<br><br>";
                                                    									echo "Module Id ". $theModule['class_module_id']."<br> Room Id ".$theRoomName['room_code'];
                                                    							    }
                                                    							    ?>
                                                    						    </td>
                                                							    <?php
                                                								//}
                                                							}
                                                							?>
                                                						</tr>
                                                						<?php
                                                					}
                                                				    ?>        									      
                                									      <?php
                                									      
                                									  }
                            								
                        									        }
                        									    }
                        									    }
                        									    catch(Exception $ex){
                                                                    echo $ex->getMessage() . "<br>";
                                                                    echo $ex->getTraceAsString();
                                                                    echo "<br>";									        
                        									    }
                        							?>
                        
                        					</tbody>
                                          </table>
                                          <button class="btn btn-primary float-right" data-toggle="modal" data-target="#editModal" title="Swapp Hours" id="div-modal" >Swapp Hours<i class="fa fa-pencil"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
        <!--            </div>-->
        <!--        </div>-->
        <!--    </section>-->
        <!--</div>    --> 
            <script src=" https://code.jquery.com/jquery-3.5.1.js"></script>
            <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script>
                $(document).ready( function () {
                    $('#1').DataTable();
                } );
            </script>
            <script>
        		$(function () {
        			$("#-1table").sortable({
        				items: 'tr:not(tr:first-child)',
        				dropOnEmpty: false,
        				start: function (G, ui) {
        					ui.item.addClass("select");
        				},
        				stop: function (G, ui) {
        					ui.item.removeClass("select");
        					$(this).find("tr").each(function (GFG) {
        						if (GFG > 0) {
        							$(this).find("td").eq(2).html(GFG);
        						}
        					});
        				}
        			});
        		});
        </script>