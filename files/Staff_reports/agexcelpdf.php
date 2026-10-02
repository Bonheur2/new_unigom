<?php
$action=$_REQUEST['action'];

if($action=="byage")
{
 $age_id=$_REQUEST['age'];
 if($age_id==1){
     
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=All Staff  By Age.xls");  
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
                                                  <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">All Staff Report Age Range : 18-28 </p> 
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
              $now=date('Y');
              $from=intval($now)-28;
              $to=intval($now)-18;
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
            INNER JOIN tbl_staff_post q ON a.staff_id=q.staff_id WHERE q.status=1 AND DATE_FORMAT(a.dob,'%Y')>='".$from."' AND DATE_FORMAT(a.dob,'%Y')<='".$to."'");
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
else if($age_id==2){
  
     
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=All Staff  By Age.xls");  
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
                                                  <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">All Staff Report Age Range : 29-39 </p> 
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
              $now=date('Y');
              $from=intval($now)-39;
              $to=intval($now)-29;
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
            INNER JOIN tbl_staff_post q ON a.staff_id=q.staff_id WHERE q.status=1 AND DATE_FORMAT(a.dob,'%Y')>='".$from."' AND DATE_FORMAT(a.dob,'%Y')<='".$to."'");
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
else if($age_id==3){
  
  
     
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=All Staff  By Age.xls");  
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
                                                  <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">All Staff Report Age Range : 40-50 </p> 
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
              $now=date('Y');
              $from=intval($now)-50;
              $to=intval($now)-40;
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
            INNER JOIN tbl_staff_post q ON a.staff_id=q.staff_id WHERE q.status=1 AND DATE_FORMAT(a.dob,'%Y')>='".$from."' AND DATE_FORMAT(a.dob,'%Y')<='".$to."'");
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
else if($age_id==4){
    
  
  
     
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=All Staff  By Age.xls");  
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
                                                  <p class="mt-0" style="font-size:24px; color:#7a58ad;font-family:Georgia;">All Staff Report Age Range : 50 Above</p> 
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
              $now=date('Y');
               $from=intval($now)-51;
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
            INNER JOIN tbl_staff_post q ON a.staff_id=q.staff_id WHERE q.status=1 AND DATE_FORMAT(a.dob,'%Y')<='".$from."' ");
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


} ?>

