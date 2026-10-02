<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="tab-content tab-bordered" id="myTab3Content">
                                                <div class="tab-pane fade show active" id="yearwise" role="tabpanel" aria-labelledby="year-tab">
                                                    
                                                    <!--<form id="add-table" action="tables/bloc_info" method="POST">-->
                                                        <div style='padding: 20px;border-radius: 8px;border: 2px solid #B59820;width:100%;margin-top:1%;margin-bottom:1%;'>
                                                            <div class="table-responsive" style="background-color:RGB(255,255,255)">
                                                                <div class="svg-container">
                                                                    <a href ="tables/download_time_table.php?class=<?php echo $_REQUEST['viewClass'];?>&levelId=<?php echo $_REQUEST['level'];?>&termId=<?php echo $_REQUEST['term'];?>&type=<?php echo $_REQUEST['prgType'];?>&mode=<?php echo $_REQUEST['prgMode'];?>"  target="_blank" title="Download PDF">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="red" class="bi bi-cloud-download" viewBox="0 0 16 16">
                                                                            <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                                                                            <path d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                                                                        </svg>
                                                                    </a>
                                                                </div> 
                                                                <?php
                                                                
                                                                // for exclusive use
                                        //                         $nums =array();
                                        //                         $modsArr =array();
                                                                
                                        //                         for($i =1; $i<148; $i +=1){
                                        //                             $nums[] =$i;
                                        //                         }
                                                                
                                        //                         $moduleId =$conn->prepare("select *from tbl_modules");
                                    				// 			$moduleId->execute();
                                    				// 			while($theMods =$moduleId->fetch()){
                                    				// 			   $modsArr[] =$theMods['module_id'];
                                    				// 			}
                                    				// 			 for($counter =0; $counter<count($nums); $counter+=1){
                                    				// 			     $updateId =$conn->prepare("UPDATE tbl_modules SET module_id = :module where module_id = :module_id ");
                                    							     
                                    				// 			     $updateId->bindParam(':module', $nums[$counter], PDO::PARAM_INT);
                                    				// 			     $updateId->bindParam(':module_id', $modsArr[$counter], PDO::PARAM_INT);
                                    				// 			     $updateId->execute();
                                    				// 			 }
                                                                
                                                                    $spec =$conn->prepare("select *from tbl_specialization where splz_id =:splz_id");
                                                                    
                                                                    $spec->bindParam(':splz_id', $_REQUEST['viewClass'], PDO::PARAM_INT);
                                    								$spec->execute();
                                    								$theSpec =$spec->fetch();
                                    								
                                    								$prgType =$conn->prepare("select *from tbl_program_type where prg_type_id =:prgType");
                                    								
                                                                    $prgType->bindParam(':prgType', $_REQUEST['prgType'], PDO::PARAM_INT);
                                    								$prgType->execute();
                                    								$theType =$prgType->fetch(); 
                                    								$theTypeName =$theType['prg_type_full_name'];
                                    								
                                    								$prgMode =$conn->prepare("select *from tbl_program_mode where prg_mode_id =:prgMode");
                                    								
                                                                    $prgMode->bindParam(':prgMode', $_REQUEST['prgMode'], PDO::PARAM_INT);
                                    								$prgMode->execute();
                                    								$theModeName =$prgMode->fetch();
                                    								$theModeName =$theModeName['prg_mode_full_name']
                                                                ?>
                                                                <table class="table table-striped v_center" id="table-1" border="1">
                                                    				<center>
                                                            			<thead>
                                                            				<tr> 
                                                            					<th colspan="12"><?php echo $theSpec['splz_full_name']." | Level: ".$_REQUEST['level']." | Term: ".$_REQUEST['term']. " | Program-Type: ".$theTypeName." | Program-Mode: ".$theModeName;?></th>
                                                            				</tr>
                                                            			</thead>									        
                                                    			</center>
                                                                <tbody>
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
                                    									           <td><?php echo $theHours['hour_lower_limit']."-".$theHours['hour_upper_limit'];?> </td>
                                    									          
                                    									               <?php
                                    									               $days =$conn->prepare("select *from tbl_t_days");
                                    									               $days->execute();
                                    									               while($theDays =$days->fetch()){
                                    									                   $moduleDay =$conn->prepare("select *from tbl_t_schedule where program_type = :prgType and program_mode = :prgMode and sub_class_id = :sub_class
                                    									                   and learning_day_id = :day and hour_id = :hour and level_id = :level and term_id = :term and class_module_id >0");
                                    									                   
                                    									                   $moduleDay->bindParam(':prgType', $_REQUEST['prgType'], PDO::PARAM_INT);
                                    									                   $moduleDay->bindParam(':prgMode', $_REQUEST['prgMode'], PDO::PARAM_INT);
                                    									                   $moduleDay->bindParam(':sub_class', $_REQUEST['viewClass'], PDO::PARAM_INT);
                                    									                   $moduleDay->bindParam(':day', $theDays['day_id'], PDO::PARAM_INT);
                                    									                   $moduleDay->bindParam(':hour', $theHours['hour_id'], PDO::PARAM_INT);
                                    									                   $moduleDay->bindParam(':level', $_REQUEST['level'], PDO::PARAM_INT);
                                    									                   $moduleDay->bindParam(':term', $_REQUEST['term'], PDO::PARAM_INT);
                                    									                   $moduleDay->execute();
                                    									                   $theModule =$moduleDay->fetch();
                                    									                   
                                    									                   // module 
                                    									                   $moduleCode =$conn->prepare("select *from tbl_modules where splz_id = :splz_id and level_id = :level_id and term_id = :term_id and mod_id = :module");
                                    									                   
                                    									                   $moduleCode->bindParam(':splz_id', $theModule['sub_class_id'], PDO::PARAM_INT);
                                    									                   $moduleCode->bindParam(':level_id', $_REQUEST['level'], PDO::PARAM_INT);
                                    									                   $moduleCode->bindParam(':term_id', $_REQUEST['term'], PDO::PARAM_INT);
                                    									                   $moduleCode->bindParam(':module', $theModule['class_module_id'], PDO::PARAM_INT);
                                    									                   $moduleCode->execute();
                                    									                   $theModuleCode =$moduleCode->fetch();
                                    									                   
                                    									                   //module code
                                    									                   $thisModCode =$conn->prepare("select *from modules where module_id = :module_id");
                                    									                   
                                    									                   $thisModCode->bindParam(':module_id', $theModule['class_module_id'], PDO::PARAM_INT);
                                    									                   $thisModCode->execute();
                                    									                   $theCode =$thisModCode->fetch();            									                   
                                    									                   
                                    									                   //room name
                                    									                   $roomName =$conn->prepare("select *from tbl_block_rooms where room_id = :room_id");
                                    									                   
                                    									                   $roomName->bindParam(':room_id', $theModule['room_id'], PDO::PARAM_INT);
                                    									                   $roomName->execute();
                                    									                   $theRoomName =$roomName->fetch();
                                    									                   
                                    									                   // module teacher 
                                    									                   $modTeacher =$conn->prepare("select staff_id, staff_first_name, staff_family_name from tbl_staff where staff_id 
                                    									                   IN(select staff_id from  tbl_module_leader where module_id = :module_leader)");
                                    									                   
                                    									                   $modTeacher->bindParam(':module_leader', $theModuleCode['module_id'], PDO::PARAM_INT);
                                    									                   $modTeacher->execute();
                                    									                   $theTeacher =$modTeacher->fetch();
                                    									                   ?>
                                    									  
                                    									                   <td draggable="true">
                                    									                   <?php
                                        									                   if($moduleDay->rowCount()==1){
                                        									                       if($theModule['class_module_id'] ==0){
                                        									                          echo ""; 
                                        									                       }
                                        									                       else{
                                        									                           echo $theCode['module_code']." ". "[".$theModuleCode['module_credits']."]"."<br>".$theRoomName['room_code']."<br>".$theTeacher['staff_first_name']." ".$theTeacher['staff_family_name'];
                                        									                           ?>
                                        									                           <!--<i class="fa fa-pencil"></i>-->
                                        									                           <?php
                                        									                           // echo "Module Id ". $theModule['class_module_id']."<br> Week Hours ".$theModule['class_mod_week_hours']."<br> Room Id ".$theModule['room_id'];
                                        									                       }
                                        									                  
                                        									                   }
                                        									                   else if($moduleDay->rowCount()==2){
                                        									                    echo "Module Id ". $theModule['class_module_id']."<br> Room Id ".$theModule['room_id'];
                                        									                    $theModule =$moduleDay->fetch();
                                        									                    echo "<br><br>";
                                        									                    echo "Module Id ". $theModule['class_module_id']."<br> Room Id ".$theModule['room_id'];
                                        									                    ?>
                                        									                    <!--<i class="fa fa-pencil"></i>-->
                                        									                    <?php
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
                                                                    </tbody>
                                                                  </table>
                                                                  
                                                                 </div>
                                                            </div>
                                                    <!--</form>-->
                                                </div>
                                            </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-lg-12" id="bloc">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <!--javascript-->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
                <script>
                    $(document).ready(function(){
                        
                            // click the btn
                            $('#table-btn').click(function () {
                                
                                var identity=$('#identity').val();
                                var spec =$('#spec-id').val();
                                var level =$('#level-id').val();
                                var mode =$('#learn-mode').val();
                                var term =$('#term').val();
                                
                        			$(this).after('<div id="loader1" class="col-sm-1" id="loading"><img src="../../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');
                        			
                                    $.get('schedules/actual_time_table?term='+term +'&identity='+identity +'&mode='+mode +'&spec='+spec +'&level='+level, function (data){
                                        $('#btn-save').attr('hidden', true);
                                        $("#table").html(data);
                                         $('#loader1').hide();
                                    });
                            });
                            
                            $('#term').change(function(){
                                $('#btn-save').attr('hidden',false);
                            });
                            
                                    // edit on modal
                            $('#spec-id').change(function () {
                                var getData= {
                                            spec:$(this).val(),
                                            action:'load-class-levels'
                                        };
                                $('#learn-mode').attr('disabled',false);        
                                $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                                $.ajax({
                                    type: "POST",
                                    url: "../../files/Programs/program_controller.php",
                                    data: getData,
                                    dataType:"json",
                                    success:function(data){
                                        $("#level-id").empty();
                                        $('#spinner1').fadeOut('fast');
                                        if(data.length>0){
                                            $("#level-id").append("<option disabled selected>--choose one--</option>");
                                            $.each(data, function (index, value) {
                                                    $("#level-id").append("<option value='" + value.level_id + "'>" + value.level_id +"</option>");
                                                });
                                        }
                    				},
                    				error:function(error){
                    				    $('#spinner1').fadeOut('fast');
                                        pop_wrong("Something went wrong!")
                    				}
                                });
                            });
            
                            //load terms
                            $('#level-id').change(function () {
                                var getData= {
                                        level_id:$(this).val(),
                                        spec:$("#spec-id").val(),
                                        action:'load-class-terms'
                                        };
                                $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                                $.ajax({
                                    type: "POST",
                                    url: "../../files/Programs/program_controller.php",
                                    data: getData,
                                    dataType:"json",
                                    success:function(data){
                                        $("#term").empty();
                                        $('#spinner2').fadeOut('fast');
                                        if(data.length>0){
                                            $("#term").append("<option disabled selected>--choose one--</option>");
                                            $.each(data, function (index, value) {
                                                $("#term").append("<option value='" + value.term_id + "'>" + value.term_id +"</option>");
                                            });
                                        }
                    				},
                    				error:function(error){
                    				    $('#spinner2').fadeOut('fast');
                                        pop_wrong("Something went wrong!")
                    				}
                                });
                            });
                        });
    
                    function pop_wrong(feedback) {
                        iziToast.warning({
                            title: 'Error',
                            message: feedback,
                            position: 'topCenter'
                        });
                    }
                                    
                    function pop_up_success(feedback) {
                        iziToast.success({
                            title: 'Message',
                            message: feedback,
                            position: 'center'
                        });
                    }
                    // Notification  
                    function pop_up_request12(feedback) {
                        jQuery(function validation(){
                            swal("Done ", feedback, "success", {
                                button: "Ok",
                            });
                        });
                    }
</script>