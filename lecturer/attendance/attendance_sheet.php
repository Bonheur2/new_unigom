<?php
/**
 * 
 * Author @macintosh
 */
 ?>
<?php include('../../meet/con.php');?>
    
<?php
    
    // request vars
    $id =$_REQUEST['identity'];
    $class =$_REQUEST['spec'];
    $month ='%'.$_REQUEST['month'].'%';
    $name ='%'.$_REQUEST['name'].'%';
    $hasAttended =0;
    
    // find out the program type and program mode of incoming spec

    $specName =$conn->prepare("select *from tbl_specialization where splz_id =:splz_id");
    $specName->bindParam(':splz_id',$class);
    $specName->execute();
    $theSpec =$specName->fetch();   
    
    $attendanceDetails=$conn->prepare("SELECT * from tbl_class_attendance where specialization =:spec_id and date like :date_id and attendance_lecturer =:lecturer_id and attendance_session like :session_id group by specialization");
    $attendanceDetails->bindParam(':date_id', $month, PDO::PARAM_STR);
    $attendanceDetails->bindParam(':spec_id', $class, PDO::PARAM_INT);
    $attendanceDetails->bindParam(':lecturer_id', $id, PDO::PARAM_STR);
    $attendanceDetails->bindParam(':session_id', $name, PDO::PARAM_STR);
    $attendanceDetails->execute(); 
    $theDetails =$attendanceDetails->fetch();
    
    $module=$conn->prepare("SELECT * from modules where module_id =:module_id");
    $module->bindParam(':module_id', $theDetails['module_id'], PDO::PARAM_INT);
    $module->execute(); 
    $theModule =$module->fetch();    
    
    $moduleTerm=$conn->prepare("SELECT * from tbl_modules where splz_id =:splz_id and mod_id =:module_id and level_id =:level");
    $moduleTerm->bindParam(':module_id', $theDetails['module_id'], PDO::PARAM_STR);
    $moduleTerm->bindParam(':splz_id', $theDetails['specialization'], PDO::PARAM_STR);
    $moduleTerm->bindParam(':level', $theDetails['level'], PDO::PARAM_STR);
    $moduleTerm->execute(); 
    $theTerm =$moduleTerm->fetch();       

    $list=$conn->prepare("SELECT * from tbl_class_attendance where specialization =:spec and date like :date and attendance_lecturer =:lecturer and attendance_session like :session");
    $list->bindParam(':date', $month, PDO::PARAM_STR);
    $list->bindParam(':spec', $class, PDO::PARAM_INT);
    $list->bindParam(':lecturer', $id, PDO::PARAM_STR);
    $list->bindParam(':session', $name, PDO::PARAM_STR);
    $list->execute();
                                        
    $programType =$conn->prepare("select prg_type_full_name from tbl_program_type where prg_type_id =:prg_type_id");
    $programType->bindParam(':prg_type_id',$theDetails['program_type']);
    $programType->execute();
    $theType =$programType->fetch();
                        
    $theTypeName =$theType['prg_type_full_name'];
    $type  =$theDept['prg_type'];
    
    $prograMode =$conn->prepare("select prg_mode_full_name from tbl_program_mode where prg_mode_id =:prg_mode_id");
    $prograMode->bindParam(':prg_mode_id',$theDetails['program_mode']);
    $prograMode->execute();
    $theMode =$prograMode->fetch();
                                        
    $theModeName =$theMode['prg_mode_full_name'];    
                                        ?>
                        <div class="col-20">
                            
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-sm-12 table-responsive">
                                        <div class="svg-container">
                                            <a href ="attendance/download_attendance_list.php?class=<?php echo $_REQUEST['spec'];?>&month=<?php echo $_REQUEST['month'];?>&name=<?php echo $_REQUEST['name'];?>&identity=<?php echo $_REQUEST['identity'];?>"  target="_blank" title="Download PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="red" class="bi bi-cloud-download" viewBox="0 0 16 16">
                                                    <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                                                    <path d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                                                </svg>
                                            </a>
                                        </div>                                        
                                        <table class="table table-striped table-hover" id="my-table" >
                            				
                                    			<thead>
                                    				<tr> 
                                    					<th colspan="3"><?php echo $theSpec['splz_full_name']." Level: ".$theDetails['level']." Term: ".$theTerm['term_id']." Module: ".$theModule['module_name']." Program-Type: ".$theTypeName." Program-Mode: ".$theModeName." Date ".$theDetails['date'];?></th>
                                    				</tr>
                                    				
                                    			</thead>									        
                            			
                            			<tr>
                                    		<th>Reg Number</th>
                                    		<th>Attendance Status</th>
                                    		<th>Additional Info</th>
                                    	</tr>
                                        <tbody>
                                            
            								    <?php
            									  while($theList =$list->fetch()){
            								           ?>
            								           <tr>
            								               <td><?php echo $theList['student_reg_number'];?></td>
            								           
                    									    
                    									        <?php
                    									        if($theList['attendance_status'] ==1){
                    									            $hasAttended ="Attended";
                    									            ?>
                    									            <td><i class ="fa fa-check"></i></td>
                    									            <?php
                    									        }
                    									        else{
                    									            $hasAttended ="Not attended";
                    									            ?>
                    									            <td><i class="fa fa-times"></i></td>
                    									            <?php
                    									        }
                    									        if($theList['additional_info'] !="NULL"){
                            									        ?>
                            									    <td>
                            									        <input type="text" class="form-control" name="comment" id="comment" value="<?php echo $theList['additional_info'];?>" readonly>
                            									    </td>
                            									    <?
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
                                </div>
                            </div>
        <!--            </div>-->
        <!--        </div>-->
        <!--    </section>-->
        <!--</div>    -->