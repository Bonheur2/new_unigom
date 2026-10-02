<?php
$action=$_REQUEST['action'];
if($action=="general"){
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Staff General Report.xls");  
require_once '../../meet/con.php'; 
 ?>
   <div class="row">
  
                                <?php
                                $campus=$conn->prepare("SELECT full_name,short_name,email,phone,po_box,website FROM  tbl_university");
                                $campus->execute();
                                $datacampus=$campus->fetch();
                                $campusfullname=$datacampus['full_name'];
                                $campusemail=$datacampus['email'];
                                $campusphone=$datacampus['phone'];
                                $campusaddress=$datacampus['po_box'];
                                $campuswebsite=$datacampus['website'];
                                
                                ?>
                               <div class="card col-lg-12">
                               <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    
                                                </div>
                                                <div class="col-lg-3">
                                                 <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;"><?php echo $campusfullname ?></p> 
                                                  <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">Staff General  Report</p> 
                                                </div>
                                            </div>
                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
  <table  class="table mb-none" border="1">
            <thead>
               <th>N0</th>
                  <th>Staff ID</th>
                  <th>Name</th>
				  <th>Phone Number</th>
				  <th>National ID/Passport</th>
				  <th>Country</th>
				  <th>Province</th>
				  <th>District</th>
				  <th>Sector</th>
				  <th>Cell</th>
				  <th>Village</th>				  
                
                 <th>Department</th>
				 <th>Post</th>
				 <th>Academic rank</th>
                
				  <th>Probation period</th>
				  
				  <th>Nationality</th>
				  <th>Sex</th>
				   <th>Martial Status</th>
				  <th>Date of Birth</th>
				  <th>Starting date</th>
				  
				  <th>Bank</th>
				  <th>Account Number</th>
				  
				  <th> RSSB</th>
				  <th> Basic Salary</th>
				  <th> Mother Name</th>
				  <th> Father Name</th>
				  <th> Contract Type</th>
				  <th>Employee Type</th>
				  <th>Status</th>
                    
                </tr>
            </thead>
            <tbody>
            <?php 
             
            $query=$conn->prepare("SELECT a.*, b.cntr_name,c.provincename,d.namedistrict,e.namesector,f.nameCell,g.VillageName,m.nationality,n.bank_name,q.status
            FROM tbl_staff_info a
            INNER JOIN  tbl_country b ON a.country=b.cntr_id
            INNER JOIN provinces c ON a.province=c.provincecode
            INNER JOIN districts d ON a.district=d.districtcode
            INNER JOIN sectors e ON a.sector=e.sectorcode 
            INNER JOIN cells f ON a.cell=f.codecell
            INNER JOIN  villages g ON a.village=g.CodeVillage
            INNER JOIN tbl_nationality m ON a.nationality=m.nat_id
            INNER JOIN  tbl_bank n ON a.bank=n.bank_id
            INNER JOIN tbl_staff_post q ON a.staff_id=q.staff_id");
            $query->execute();
             $i=1;
             
             while( $data=$query->fetch()){
               $staff_id=$data['staff_id'];
                $status = $data['status'];
               $query2=$conn->prepare("SELECT h.*,j.staff_dept_short_name,k.staff_post_full_name,l.acad_grad_full_name,o.contr_name,p.staff_type_full_name
               FROM tbl_staff_post h 
               INNER JOIN tbl_staff_dept j ON h.department=j.staff_dept_id 
               INNER JOIN  staff_post k ON h.post=k.staff_post_id
               INNER JOIN tbl_acad_grade l ON h.acad_grad_id=l.acad_grad_id 
               INNER JOIN tbl_contract_type o ON h.contract=o.contr_type_id
               INNER JOIN  tbl_staff_type p ON h.is_acadmic=p.staff_type_id
               WHERE h.staff_id='".$staff_id."' ");
               $query2->execute();
               $data2=$query2->fetch();
               
                ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $data['staff_id'] ?></td>
                <td><?php echo $data['family_name'].' ' .$data['first_name']; ?></td>
                <td><?php echo $data['phone'] ?></td>
                <td><?php echo $data['nid'] ?></td>
                <td><?php echo $data['cntr_name'] ?></td>
                <td><?php echo $data['provincename'] ?></td>
                <td><?php echo $data['namedistrict'] ?></td>
                <td><?php echo $data['namesector'] ?></td>
                <td><?php echo $data['nameCell'] ?></td>
                <td><?php echo $data['VillageName'] ?></td>
                <td><?php echo $data2['staff_dept_short_name'] ?></td>
                <td><?php echo $data2['staff_post_full_name'] ?></td>
                <td><?php echo $data2['acad_grad_full_name'] ?></td>
                <td><?php echo $data2['probation_period'].'  Months ' ?></td>
                <td><?php echo $data['nationality'] ?></td>
                <td><?php echo $data['gender'] ?></td>
                <?php
                if($data['marital_status']=="M"){
                        ?>
                <td><?php echo "Married"; ?></td>   
                     
                  <?php 
                  }
                  else if($data['marital_status']=="S"){
                      ?>
                      
                <td><?php echo "Single"; ?></td>     
                <?php 
                }
                else if($data['marital_status']=="W"){
                 ?>
                <td><?php echo "Widowed"; ?></td>   
                
                <?php  }
                else if($data['marital_status']=="D"){
                    ?>
                <td><?php echo "Divorced"; ?></td>
               <?php }  ?>
                <td><?php echo $data['dob'] ?></td>
                <td><?php echo $data2['join_date'] ?></td>
                <td><?php echo $data['bank_name'] ?></td>
                <td><?php echo $data['acc_no'] ?></td>
                <td><?php echo $data['rssb'] ?></td>
                <td><?php echo $data2['basic_salary'] ?></td>
                <td><?php echo $data['mother_name'] ?></td>
                <td><?php echo $data['father_name'] ?></td>
                <td><?php echo $data2['contr_name'] ?></td>
               <td><?php echo $data2['staff_type_full_name'] ?></td>
                <?php
                 
                    if ($status === 1) {
                        echo "<td>Active</td>";
                    } else {
                        echo "<td>Inactive</td>";
                    }
                
                ?>
               </tr>
         <?php
                } 
                ?>
            </tbody>
         </table>  
    
<?php     
}
if($action=="allstaff")
{
   
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=All Staff  Report.xls");  
require_once '../../meet/con.php'; 
 ?>
   <div class="row">
  
                                <?php
                                $campus=$conn->prepare("SELECT full_name,short_name,email,phone,po_box,website FROM  tbl_university");
                                $campus->execute();
                                $datacampus=$campus->fetch();
                                $campusfullname=$datacampus['full_name'];
                                $campusemail=$datacampus['email'];
                                $campusphone=$datacampus['phone'];
                                $campusaddress=$datacampus['po_box'];
                                $campuswebsite=$datacampus['website'];
                                
                                ?>
                               <div class="card col-lg-12">
                               <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    
                                                </div>
                                                <div class="col-lg-3">
                                                 <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;"><?php echo $campusfullname ?></p> 
                                                  <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">All Staff Report</p> 
                                                </div>
                                            </div>
                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
  <table  class="table mb-none" border="1">
            <thead>
               <th>N0</th>
                  <th>Staff ID</th>
                  <th>Name</th>
				  <th>Phone Number</th>
				  <th>National ID/Passport</th>
				  <th>Country</th>
				  <th>Province</th>
				  <th>District</th>
				  <th>Sector</th>
				  <th>Cell</th>
				  <th>Village</th>				  
                
                 <th>Department</th>
				 <th>Post</th>
				 <th>Academic rank</th>
                
				  <th>Probation period</th>
				  
				  <th>Nationality</th>
				  <th>Sex</th>
				   <th>Martial Status</th>
				  <th>Date of Birth</th>
				  <th>Starting date</th>
				  
				  <th>Bank</th>
				  <th>Account Number</th>
				  
				  <th> RSSB</th>
				  <th> Basic Salary</th>
				  <th> Mother Name</th>
				  <th> Father Name</th>
				  <th> Contract Type</th>
				  <th>Employee Type</th>
				  
                    
                </tr>
            </thead>
            <tbody>
            <?php 
             
            $query=$conn->prepare("SELECT a.*, b.cntr_name,c.provincename,d.namedistrict,e.namesector,f.nameCell,g.VillageName,m.nationality,n.bank_name
            FROM tbl_staff_info a
            INNER JOIN  tbl_country b ON a.country=b.cntr_id
            INNER JOIN provinces c ON a.province=c.provincecode
            INNER JOIN districts d ON a.district=d.districtcode
            INNER JOIN sectors e ON a.sector=e.sectorcode 
            INNER JOIN cells f ON a.cell=f.codecell
            INNER JOIN  villages g ON a.village=g.CodeVillage
            INNER JOIN tbl_nationality m ON a.nationality=m.nat_id
            INNER JOIN  tbl_bank n ON a.bank=n.bank_id
            INNER JOIN tbl_staff_post q ON a.staff_id=q.staff_id WHERE q.status=1");
            $query->execute();
             $i=1;
             
             while( $data=$query->fetch()){
               $staff_id=$data['staff_id'];
               $query2=$conn->prepare("SELECT h.*,j.staff_dept_short_name,k.staff_post_full_name,l.acad_grad_full_name,o.contr_name,p.staff_type_full_name
               FROM tbl_staff_post h 
               INNER JOIN tbl_staff_dept j ON h.department=j.staff_dept_id 
               INNER JOIN  staff_post k ON h.post=k.staff_post_id
               INNER JOIN tbl_acad_grade l ON h.acad_grad_id=l.acad_grad_id 
               INNER JOIN tbl_contract_type o ON h.contract=o.contr_type_id
               INNER JOIN  tbl_staff_type p ON h.is_acadmic=p.staff_type_id
               WHERE h.staff_id='".$staff_id."' ");
               $query2->execute();
               $data2=$query2->fetch();
                ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $data['staff_id'] ?></td>
                <td><?php echo $data['family_name'].' ' .$data['first_name']; ?></td>
                <td><?php echo $data['phone'] ?></td>
                <td><?php echo $data['nid'] ?></td>
                <td><?php echo $data['cntr_name'] ?></td>
                <td><?php echo $data['provincename'] ?></td>
                <td><?php echo $data['namedistrict'] ?></td>
                <td><?php echo $data['namesector'] ?></td>
                <td><?php echo $data['nameCell'] ?></td>
                <td><?php echo $data['VillageName'] ?></td>
                <td><?php echo $data2['staff_dept_short_name'] ?></td>
                <td><?php echo $data2['staff_post_full_name'] ?></td>
                <td><?php echo $data2['acad_grad_full_name'] ?></td>
                <td><?php echo $data2['probation_period'].'  Months ' ?></td>
                <td><?php echo $data['nationality'] ?></td>
                <td><?php echo $data['gender'] ?></td>
                <?php
                if($data['marital_status']=="M"){
                        ?>
                <td><?php echo "Married"; ?></td>   
                     
                  <?php 
                  }
                  else if($data['marital_status']=="S"){
                      ?>
                      
                <td><?php echo "Single"; ?></td>     
                <?php 
                }
                else if($data['marital_status']=="W"){
                 ?>
                <td><?php echo "Widowed"; ?></td>   
                
                <?php  }
                else if($data['marital_status']=="D"){
                    ?>
                <td><?php echo "Divorced"; ?></td>
               <?php }  ?>
                <td><?php echo $data['dob'] ?></td>
                <td><?php echo $data2['join_date'] ?></td>
                <td><?php echo $data['bank_name'] ?></td>
                <td><?php echo $data['acc_no'] ?></td>
                <td><?php echo $data['rssb'] ?></td>
                <td><?php echo $data2['basic_salary'] ?></td>
                <td><?php echo $data['mother_name'] ?></td>
                <td><?php echo $data['father_name'] ?></td>
                <td><?php echo $data2['contr_name'] ?></td>
                <td><?php echo $data2['staff_type_full_name'] ?></td>
               </tr>
                    <?php
                } 
                ?>
            </tbody>
         </table>  
    
<?php }
else if($action=="bygender"){
    $gender=$_REQUEST['gender'];
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Staff  Report By Gender.xls");  
require_once '../../meet/con.php'; 
    ?>
 <div class="row">
  
                                <?php
                                $campus=$conn->prepare("SELECT full_name,short_name,email,phone,po_box,website FROM  tbl_university");
                                $campus->execute();
                                $datacampus=$campus->fetch();
                                $campusfullname=$datacampus['full_name'];
                                $campusemail=$datacampus['email'];
                                $campusphone=$datacampus['phone'];
                                $campusaddress=$datacampus['po_box'];
                                $campuswebsite=$datacampus['website'];
                                
                                ?>
                               <div class="card col-lg-12">
                               <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    
                                                </div>
                                                <div class="col-lg-3">
                                                 <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;"><?php echo $campusfullname ?></p> 
                                                 <?php 
                                                 if($gender=="M"){
                                                     ?>
                                                   <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">Staff Report By Gender : Male</p>      
                                                 <?php }
                                                 else{
                                                     ?>
                                                 <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">Staff Report By Gender : Female</p>     
                                                <?php  }
                                                 ?>
                                                
                                                 
                                                </div>
                                            </div>
                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
  <table  class="table mb-none" border="1">
            <thead>
               <th>N0</th>
                  <th>Staff ID</th>
                  <th>Name</th>
				  <th>Phone Number</th>
				  <th>National ID/Passport</th>
				  <th>Country</th>
				  <th>Province</th>
				  <th>District</th>
				  <th>Sector</th>
				  <th>Cell</th>
				  <th>Village</th>				  
                
                 <th>Department</th>
				 <th>Post</th>
				 <th>Academic rank</th>
                
				  <th>Probation period</th>
				  
				  <th>Nationality</th>
				  <th>Sex</th>
				   <th>Martial Status</th>
				  <th>Date of Birth</th>
				  <th>Starting date</th>
				  
				  <th>Bank</th>
				  <th>Account Number</th>
				  
				  <th> RSSB</th>
				  <th> Basic Salary</th>
				  <th> Mother Name</th>
				  <th> Father Name</th>
				  <th> Contract Type</th>
				  <th>Employee Type</th>
				  
                    
                </tr>
            </thead>
            <tbody>
            <?php 
             
            $query=$conn->prepare("SELECT a.*, b.cntr_name,c.provincename,d.namedistrict,e.namesector,f.nameCell,g.VillageName,m.nationality,n.bank_name
            FROM tbl_staff_info a
            INNER JOIN  tbl_country b ON a.country=b.cntr_id
            INNER JOIN provinces c ON a.province=c.provincecode
            INNER JOIN districts d ON a.district=d.districtcode
            INNER JOIN sectors e ON a.sector=e.sectorcode 
            INNER JOIN cells f ON a.cell=f.codecell
            INNER JOIN  villages g ON a.village=g.CodeVillage
            INNER JOIN tbl_nationality m ON a.nationality=m.nat_id
            INNER JOIN  tbl_bank n ON a.bank=n.bank_id
            INNER JOIN tbl_staff_post q ON a.staff_id=q.staff_id WHERE q.status=1 AND a.gender='".$gender."'");
            $query->execute();
             $i=1;
             
             while( $data=$query->fetch()){
               $staff_id=$data['staff_id'];
               $query2=$conn->prepare("SELECT h.*,j.staff_dept_short_name,k.staff_post_full_name,l.acad_grad_full_name,o.contr_name,p.staff_type_full_name
               FROM tbl_staff_post h 
               INNER JOIN tbl_staff_dept j ON h.department=j.staff_dept_id 
               INNER JOIN  staff_post k ON h.post=k.staff_post_id
               INNER JOIN tbl_acad_grade l ON h.acad_grad_id=l.acad_grad_id 
               INNER JOIN tbl_contract_type o ON h.contract=o.contr_type_id
               INNER JOIN  tbl_staff_type p ON h.is_acadmic=p.staff_type_id
               WHERE h.staff_id='".$staff_id."' ");
               $query2->execute();
               $data2=$query2->fetch();
                ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $data['staff_id'] ?></td>
                <td><?php echo $data['family_name'].' ' .$data['first_name']; ?></td>
                <td><?php echo $data['phone'] ?></td>
                <td><?php echo $data['nid'] ?></td>
                <td><?php echo $data['cntr_name'] ?></td>
                <td><?php echo $data['provincename'] ?></td>
                <td><?php echo $data['namedistrict'] ?></td>
                <td><?php echo $data['namesector'] ?></td>
                <td><?php echo $data['nameCell'] ?></td>
                <td><?php echo $data['VillageName'] ?></td>
                <td><?php echo $data2['staff_dept_short_name'] ?></td>
                <td><?php echo $data2['staff_post_full_name'] ?></td>
                <td><?php echo $data2['acad_grad_full_name'] ?></td>
                <td><?php echo $data2['probation_period'].'  Months ' ?></td>
                <td><?php echo $data['nationality'] ?></td>
                <td><?php echo $data['gender'] ?></td>
                <?php
                if($data['marital_status']=="M"){
                        ?>
                <td><?php echo "Married"; ?></td>   
                     
                  <?php 
                  }
                  else if($data['marital_status']=="S"){
                      ?>
                      
                <td><?php echo "Single"; ?></td>     
                <?php 
                }
                else if($data['marital_status']=="W"){
                 ?>
                <td><?php echo "Widowed"; ?></td>   
                
                <?php  }
                else if($data['marital_status']=="D"){
                    ?>
                <td><?php echo "Divorced"; ?></td>
               <?php }  ?>
                <td><?php echo $data['dob'] ?></td>
                <td><?php echo $data2['join_date'] ?></td>
                <td><?php echo $data['bank_name'] ?></td>
                <td><?php echo $data['acc_no'] ?></td>
                <td><?php echo $data['rssb'] ?></td>
                <td><?php echo $data2['basic_salary'] ?></td>
                <td><?php echo $data['mother_name'] ?></td>
                <td><?php echo $data['father_name'] ?></td>
                <td><?php echo $data2['contr_name'] ?></td>
                <td><?php echo $data2['staff_type_full_name'] ?></td>
               </tr>
                    <?php
                } 
                ?>
            </tbody>
         </table>  
        
<?php }
else if($action=="byposition"){
    
$position=$_REQUEST['position'];
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Staff  Report By Position.xls");  
require_once '../../meet/con.php'; 
    ?>
 <div class="row">
  
                                <?php
                                $campus=$conn->prepare("SELECT full_name,short_name,email,phone,po_box,website FROM  tbl_university");
                                $campus->execute();
                                $datacampus=$campus->fetch();
                                $campusfullname=$datacampus['full_name'];
                                $campusemail=$datacampus['email'];
                                $campusphone=$datacampus['phone'];
                                $campusaddress=$datacampus['po_box'];
                                $campuswebsite=$datacampus['website'];
                               $position=$conn->prepare("SELECT staff_type_full_name FROM tbl_staff_type WHERE staff_type_id ='".$position."'");
                               $position->execute();
                               $datap=$position->fetch();
                            
                                ?>
                               <div class="card col-lg-12">
                               <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    
                                                </div>
                                                <div class="col-lg-3">
                                                 <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;"><?php echo $campusfullname ?></p> 
                                                 <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">Staff Report By Position : <?php echo $datap['staff_type_full_name']; ?> </p>     
                                                
                                                
                                                 
                                                </div>
                                            </div>
                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
  <table  class="table mb-none" border="1">
            <thead>
               <th>N0</th>
                  <th>Staff ID</th>
                  <th>Name</th>
				  <th>Phone Number</th>
				  <th>National ID/Passport</th>
				  <th>Country</th>
				  <th>Province</th>
				  <th>District</th>
				  <th>Sector</th>
				  <th>Cell</th>
				  <th>Village</th>				  
                
                 <th>Department</th>
				 <th>Post</th>
				 <th>Academic rank</th>
                
				  <th>Probation period</th>
				  
				  <th>Nationality</th>
				  <th>Sex</th>
				   <th>Martial Status</th>
				  <th>Date of Birth</th>
				  <th>Starting date</th>
				  
				  <th>Bank</th>
				  <th>Account Number</th>
				  
				  <th> RSSB</th>
				  <th> Basic Salary</th>
				  <th> Mother Name</th>
				  <th> Father Name</th>
				  <th> Contract Type</th>
				  <th>Employee Type</th>
				  
                    
                </tr>
            </thead>
            <tbody>
            <?php 
            $position=$_REQUEST['position'];
            $query=$conn->prepare("SELECT a.*, b.cntr_name,c.provincename,d.namedistrict,e.namesector,f.nameCell,g.VillageName,m.nationality,n.bank_name
            FROM tbl_staff_info a
            INNER JOIN  tbl_country b ON a.country=b.cntr_id
            INNER JOIN provinces c ON a.province=c.provincecode
            INNER JOIN districts d ON a.district=d.districtcode
            INNER JOIN sectors e ON a.sector=e.sectorcode 
            INNER JOIN cells f ON a.cell=f.codecell
            INNER JOIN  villages g ON a.village=g.CodeVillage
            INNER JOIN tbl_nationality m ON a.nationality=m.nat_id
            INNER JOIN  tbl_bank n ON a.bank=n.bank_id
            INNER JOIN tbl_staff_post ON a.staff_id=tbl_staff_post.staff_id WHERE tbl_staff_post.status=1 AND tbl_staff_post.is_acadmic='".$position."'");
            $query->execute();
             $i=1;
             
             while( $data=$query->fetch()){
               $staff_id=$data['staff_id'];
               $query2=$conn->prepare("SELECT h.*,j.staff_dept_short_name,k.staff_post_full_name,l.acad_grad_full_name,o.contr_name,p.staff_type_full_name
               FROM tbl_staff_post h 
               INNER JOIN tbl_staff_dept j ON h.department=j.staff_dept_id 
               INNER JOIN  staff_post k ON h.post=k.staff_post_id
               INNER JOIN tbl_acad_grade l ON h.acad_grad_id=l.acad_grad_id 
               INNER JOIN tbl_contract_type o ON h.contract=o.contr_type_id
               INNER JOIN  tbl_staff_type p ON h.is_acadmic=p.staff_type_id
               WHERE h.staff_id='".$staff_id."' ");
               $query2->execute();
               $data2=$query2->fetch();
                ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $data['staff_id'] ?></td>
                <td><?php echo $data['family_name'].' ' .$data['first_name']; ?></td>
                <td><?php echo $data['phone'] ?></td>
                <td><?php echo $data['nid'] ?></td>
                <td><?php echo $data['cntr_name'] ?></td>
                <td><?php echo $data['provincename'] ?></td>
                <td><?php echo $data['namedistrict'] ?></td>
                <td><?php echo $data['namesector'] ?></td>
                <td><?php echo $data['nameCell'] ?></td>
                <td><?php echo $data['VillageName'] ?></td>
                <td><?php echo $data2['staff_dept_short_name'] ?></td>
                <td><?php echo $data2['staff_post_full_name'] ?></td>
                <td><?php echo $data2['acad_grad_full_name'] ?></td>
                <td><?php echo $data2['probation_period'].'  Months ' ?></td>
                <td><?php echo $data['nationality'] ?></td>
                <td><?php echo $data['gender'] ?></td>
                <?php
                if($data['marital_status']=="M"){
                        ?>
                <td><?php echo "Married"; ?></td>   
                     
                  <?php 
                  }
                  else if($data['marital_status']=="S"){
                      ?>
                      
                <td><?php echo "Single"; ?></td>     
                <?php 
                }
                else if($data['marital_status']=="W"){
                 ?>
                <td><?php echo "Widowed"; ?></td>   
                
                <?php  }
                else if($data['marital_status']=="D"){
                    ?>
                <td><?php echo "Divorced"; ?></td>
               <?php }  ?>
                <td><?php echo $data['dob'] ?></td>
                <td><?php echo $data2['join_date'] ?></td>
                <td><?php echo $data['bank_name'] ?></td>
                <td><?php echo $data['acc_no'] ?></td>
                <td><?php echo $data['rssb'] ?></td>
                <td><?php echo $data2['basic_salary'] ?></td>
                <td><?php echo $data['mother_name'] ?></td>
                <td><?php echo $data['father_name'] ?></td>
                <td><?php echo $data2['contr_name'] ?></td>
                <td><?php echo $data2['staff_type_full_name'] ?></td>
               </tr>
                    <?php
                } 
                ?>
            </tbody>
         </table>  
        
<?php }
else if($action=="bydepartment"){
   
    
$department=$_REQUEST['department'];
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Staff  Report By Department.xls");  
require_once '../../meet/con.php'; 
    ?>
 <div class="row">
  
                                <?php
                                $campus=$conn->prepare("SELECT full_name,short_name,email,phone,po_box,website FROM  tbl_university");
                                $campus->execute();
                                $datacampus=$campus->fetch();
                                $campusfullname=$datacampus['full_name'];
                                $campusemail=$datacampus['email'];
                                $campusphone=$datacampus['phone'];
                                $campusaddress=$datacampus['po_box'];
                                $campuswebsite=$datacampus['website'];
                               $position=$conn->prepare("SELECT staff_dept_full_name FROM tbl_staff_dept WHERE staff_dept_id  ='".$department."'");
                               $position->execute();
                               $datap=$position->fetch();
                            
                                ?>
                               <div class="card col-lg-12">
                               <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    
                                                </div>
                                                <div class="col-lg-3">
                                                 <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;"><?php echo $campusfullname ?></p> 
                                                 <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">Staff Report By Department : <?php echo $datap['staff_dept_full_name']; ?> </p>     
                                                
                                                
                                                 
                                                </div>
                                            </div>
                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
  <table  class="table mb-none" border="1">
            <thead>
               <th>N0</th>
                  <th>Staff ID</th>
                  <th>Name</th>
				  <th>Phone Number</th>
				  <th>National ID/Passport</th>
				  <th>Country</th>
				  <th>Province</th>
				  <th>District</th>
				  <th>Sector</th>
				  <th>Cell</th>
				  <th>Village</th>				  
                
                 <th>Department</th>
				 <th>Post</th>
				 <th>Academic rank</th>
                
				  <th>Probation period</th>
				  
				  <th>Nationality</th>
				  <th>Sex</th>
				   <th>Martial Status</th>
				  <th>Date of Birth</th>
				  <th>Starting date</th>
				  
				  <th>Bank</th>
				  <th>Account Number</th>
				  
				  <th> RSSB</th>
				  <th> Basic Salary</th>
				  <th> Mother Name</th>
				  <th> Father Name</th>
				  <th> Contract Type</th>
				  <th>Employee Type</th>
				  
                    
                </tr>
            </thead>
            <tbody>
            <?php 
            $department=$_REQUEST['department'];
            $query=$conn->prepare("SELECT a.*, b.cntr_name,c.provincename,d.namedistrict,e.namesector,f.nameCell,g.VillageName,m.nationality,n.bank_name
            FROM tbl_staff_info a
            INNER JOIN  tbl_country b ON a.country=b.cntr_id
            INNER JOIN provinces c ON a.province=c.provincecode
            INNER JOIN districts d ON a.district=d.districtcode
            INNER JOIN sectors e ON a.sector=e.sectorcode 
            INNER JOIN cells f ON a.cell=f.codecell
            INNER JOIN  villages g ON a.village=g.CodeVillage
            INNER JOIN tbl_nationality m ON a.nationality=m.nat_id
            INNER JOIN  tbl_bank n ON a.bank=n.bank_id
            INNER JOIN tbl_staff_post ON a.staff_id=tbl_staff_post.staff_id WHERE tbl_staff_post.status=1 AND tbl_staff_post.department='".$department."'");
            $query->execute();
             $i=1;
             
             while( $data=$query->fetch()){
               $staff_id=$data['staff_id'];
               $query2=$conn->prepare("SELECT h.*,j.staff_dept_short_name,k.staff_post_full_name,l.acad_grad_full_name,o.contr_name,p.staff_type_full_name
               FROM tbl_staff_post h 
               INNER JOIN tbl_staff_dept j ON h.department=j.staff_dept_id 
               INNER JOIN  staff_post k ON h.post=k.staff_post_id
               INNER JOIN tbl_acad_grade l ON h.acad_grad_id=l.acad_grad_id 
               INNER JOIN tbl_contract_type o ON h.contract=o.contr_type_id
               INNER JOIN  tbl_staff_type p ON h.is_acadmic=p.staff_type_id
               WHERE h.staff_id='".$staff_id."' ");
               $query2->execute();
               $data2=$query2->fetch();
                ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $data['staff_id'] ?></td>
                <td><?php echo $data['family_name'].' ' .$data['first_name']; ?></td>
                <td><?php echo $data['phone'] ?></td>
                <td><?php echo $data['nid'] ?></td>
                <td><?php echo $data['cntr_name'] ?></td>
                <td><?php echo $data['provincename'] ?></td>
                <td><?php echo $data['namedistrict'] ?></td>
                <td><?php echo $data['namesector'] ?></td>
                <td><?php echo $data['nameCell'] ?></td>
                <td><?php echo $data['VillageName'] ?></td>
                <td><?php echo $data2['staff_dept_short_name'] ?></td>
                <td><?php echo $data2['staff_post_full_name'] ?></td>
                <td><?php echo $data2['acad_grad_full_name'] ?></td>
                <td><?php echo $data2['probation_period'].'  Months ' ?></td>
                <td><?php echo $data['nationality'] ?></td>
                <td><?php echo $data['gender'] ?></td>
                <?php
                if($data['marital_status']=="M"){
                        ?>
                <td><?php echo "Married"; ?></td>   
                     
                  <?php 
                  }
                  else if($data['marital_status']=="S"){
                      ?>
                      
                <td><?php echo "Single"; ?></td>     
                <?php 
                }
                else if($data['marital_status']=="W"){
                 ?>
                <td><?php echo "Widowed"; ?></td>   
                
                <?php  }
                else if($data['marital_status']=="D"){
                    ?>
                <td><?php echo "Divorced"; ?></td>
               <?php }  ?>
                <td><?php echo $data['dob'] ?></td>
                <td><?php echo $data2['join_date'] ?></td>
                <td><?php echo $data['bank_name'] ?></td>
                <td><?php echo $data['acc_no'] ?></td>
                <td><?php echo $data['rssb'] ?></td>
                <td><?php echo $data2['basic_salary'] ?></td>
                <td><?php echo $data['mother_name'] ?></td>
                <td><?php echo $data['father_name'] ?></td>
                <td><?php echo $data2['contr_name'] ?></td>
                <td><?php echo $data2['staff_type_full_name'] ?></td>
               </tr>
                    <?php
                } 
                ?>
            </tbody>
         </table>  
        
<?php  
}
else if($action=="bystatus"){
 
   
    
$status=$_REQUEST['status'];
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Staff  Report By Status.xls");  
require_once '../../meet/con.php'; 
    ?>
 <div class="row">
  
                                <?php
                                $campus=$conn->prepare("SELECT full_name,short_name,email,phone,po_box,website FROM  tbl_university");
                                $campus->execute();
                                $datacampus=$campus->fetch();
                                $campusfullname=$datacampus['full_name'];
                                $campusemail=$datacampus['email'];
                                $campusphone=$datacampus['phone'];
                                $campusaddress=$datacampus['po_box'];
                                $campuswebsite=$datacampus['website'];
                               $position=$conn->prepare("SELECT staff_dept_full_name FROM tbl_staff_dept WHERE staff_dept_id  ='".$department."'");
                               $position->execute();
                               $datap=$position->fetch();
                            
                                ?>
                               <div class="card col-lg-12">
                               <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    
                                                </div>
                                                <div class="col-lg-3">
                                                 <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;"><?php echo $campusfullname ?></p> 
                                                 <?php
                                                 if($status==1){
                                                 ?>
                                                <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">Staff Report By Status : Active </p>     

                                                <?php  }
                                                else{
                                                    ?>
                                                <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">Staff Report By Status : Inactive </p>     

                                                <?php }
                                                 ?>
                                                
                                                
                                                 
                                                </div>
                                            </div>
                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
  <table  class="table mb-none" border="1">
            <thead>
               <th>N0</th>
                  <th>Staff ID</th>
                  <th>Name</th>
				  <th>Phone Number</th>
				  <th>National ID/Passport</th>
				  <th>Country</th>
				  <th>Province</th>
				  <th>District</th>
				  <th>Sector</th>
				  <th>Cell</th>
				  <th>Village</th>				  
                
                 <th>Department</th>
				 <th>Post</th>
				 <th>Academic rank</th>
                
				  <th>Probation period</th>
				  
				  <th>Nationality</th>
				  <th>Sex</th>
				   <th>Martial Status</th>
				  <th>Date of Birth</th>
				  <th>Starting date</th>
				  
				  <th>Bank</th>
				  <th>Account Number</th>
				  
				  <th> RSSB</th>
				  <th> Basic Salary</th>
				  <th> Mother Name</th>
				  <th> Father Name</th>
				  <th> Contract Type</th>
				  <th>Employee Type</th>
				  
                    
                </tr>
            </thead>
            <tbody>
            <?php 
           $status=$_REQUEST['status'];
            $query=$conn->prepare("SELECT a.*, b.cntr_name,c.provincename,d.namedistrict,e.namesector,f.nameCell,g.VillageName,m.nationality,n.bank_name
            FROM tbl_staff_info a
            INNER JOIN  tbl_country b ON a.country=b.cntr_id
            INNER JOIN provinces c ON a.province=c.provincecode
            INNER JOIN districts d ON a.district=d.districtcode
            INNER JOIN sectors e ON a.sector=e.sectorcode 
            INNER JOIN cells f ON a.cell=f.codecell
            INNER JOIN  villages g ON a.village=g.CodeVillage
            INNER JOIN tbl_nationality m ON a.nationality=m.nat_id
            INNER JOIN  tbl_bank n ON a.bank=n.bank_id
            INNER JOIN tbl_staff_post ON a.staff_id=tbl_staff_post.staff_id WHERE  tbl_staff_post.status='".$status."'");
            $query->execute();
             $i=1;
             
             while( $data=$query->fetch()){
               $staff_id=$data['staff_id'];
               $query2=$conn->prepare("SELECT h.*,j.staff_dept_short_name,k.staff_post_full_name,l.acad_grad_full_name,o.contr_name,p.staff_type_full_name
               FROM tbl_staff_post h 
               INNER JOIN tbl_staff_dept j ON h.department=j.staff_dept_id 
               INNER JOIN  staff_post k ON h.post=k.staff_post_id
               INNER JOIN tbl_acad_grade l ON h.acad_grad_id=l.acad_grad_id 
               INNER JOIN tbl_contract_type o ON h.contract=o.contr_type_id
               INNER JOIN  tbl_staff_type p ON h.is_acadmic=p.staff_type_id
               WHERE h.staff_id='".$staff_id."' ");
               $query2->execute();
               $data2=$query2->fetch();
                ?>
                <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $data['staff_id'] ?></td>
                <td><?php echo $data['family_name'].' ' .$data['first_name']; ?></td>
                <td><?php echo $data['phone'] ?></td>
                <td><?php echo $data['nid'] ?></td>
                <td><?php echo $data['cntr_name'] ?></td>
                <td><?php echo $data['provincename'] ?></td>
                <td><?php echo $data['namedistrict'] ?></td>
                <td><?php echo $data['namesector'] ?></td>
                <td><?php echo $data['nameCell'] ?></td>
                <td><?php echo $data['VillageName'] ?></td>
                <td><?php echo $data2['staff_dept_short_name'] ?></td>
                <td><?php echo $data2['staff_post_full_name'] ?></td>
                <td><?php echo $data2['acad_grad_full_name'] ?></td>
                <td><?php echo $data2['probation_period'].'  Months ' ?></td>
                <td><?php echo $data['nationality'] ?></td>
                <td><?php echo $data['gender'] ?></td>
                <?php
                if($data['marital_status']=="M"){
                        ?>
                <td><?php echo "Married"; ?></td>   
                     
                  <?php 
                  }
                  else if($data['marital_status']=="S"){
                      ?>
                      
                <td><?php echo "Single"; ?></td>     
                <?php 
                }
                else if($data['marital_status']=="W"){
                 ?>
                <td><?php echo "Widowed"; ?></td>   
                
                <?php  }
                else if($data['marital_status']=="D"){
                    ?>
                <td><?php echo "Divorced"; ?></td>
               <?php }  ?>
                <td><?php echo $data['dob'] ?></td>
                <td><?php echo $data2['join_date'] ?></td>
                <td><?php echo $data['bank_name'] ?></td>
                <td><?php echo $data['acc_no'] ?></td>
                <td><?php echo $data['rssb'] ?></td>
                <td><?php echo $data2['basic_salary'] ?></td>
                <td><?php echo $data['mother_name'] ?></td>
                <td><?php echo $data['father_name'] ?></td>
                <td><?php echo $data2['contr_name'] ?></td>
                <td><?php echo $data2['staff_type_full_name'] ?></td>
               </tr>
                    <?php
                } 
                ?>
            </tbody>
         </table>  
        
<?php  
  
}
?>
