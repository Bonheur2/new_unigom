<?php
include ('../../meet/con.php');

$action=$_POST['action'];
if($action=="load_gender_data"){
  $gender_id=$_POST['gender_id'];  
  $query=$conn->prepare("SELECT si.*, sp.*
FROM tbl_staff_info si
INNER JOIN tbl_staff_post sp ON sp.staff_id = si.staff_id  WHERE sp.status=1 AND si.gender='".$gender_id."'");
  $query->execute();
  $i=1;
  while($rows=$query->fetch()){
      $staff_id=$rows['staff_id'];
      $family_name=$rows['family_name'];
      $last_name=$rows['first_name'];
      $nid=$rows['nid'];
      $staff_post_full_name=$rows['staff_post_full_name'];
      $staff_dept_full_name=$rows['staff_dept_full_name'];
      echo "
        <tr class='clickable-order' data-id=$id>
            <td>$i</td>
            <td>$staff_id</td>
            <td>$family_name</td>
            <td>$last_name</td>
            <td>$nid</td>
            // <td>$staff_post_full_name</td>
            // <td>$staff_dept_full_name</td>
             <td><div class='badge badge-success'>Active</div></td>
        </tr>";
         $i++;
  }
  
}
else if($action=="load_position_data"){
  $position_id=$_POST['position_id'];  
  $query=$conn->prepare("SELECT si.*, sp.*
FROM tbl_staff_info si
INNER JOIN tbl_staff_post sp ON sp.staff_id = si.staff_id  WHERE sp.status=1 AND sp.is_acadmic='".$position_id."'");
  $query->execute();
  $i=1;
  while($rows=$query->fetch()){
      $staff_id=$rows['staff_id'];
      $family_name=$rows['family_name'];
      $last_name=$rows['first_name'];
      $nid=$rows['nid'];
      $staff_post_full_name=$rows['staff_post_full_name'];
      $staff_dept_full_name=$rows['staff_dept_full_name'];
      echo "
        <tr class='clickable-order' data-id=$id>
            <td>$i</td>
            <td>$staff_id</td>
            <td>$family_name</td>
            <td>$last_name</td>
            <td>$nid</td>
            <td>$staff_post_full_name</td>
            <td>$staff_dept_full_name</td>
             <td><div class='badge badge-success'>Active</div></td>
        </tr>";
         $i++;
  }
  
}
else if($action=="load_department_data"){
   
  $department_id=$_POST['department_id'];  
  $query=$conn->prepare("SELECT tbl_staff_info.family_name,
                                                tbl_staff_info.first_name,tbl_staff_info.nid,tbl_staff_info.staff_id,staff_post.staff_post_full_name, tbl_staff_dept.staff_dept_full_name,tbl_staff_post.status
                                                FROM tbl_staff_post
                                                INNER JOIN tbl_staff_info ON tbl_staff_post.staff_id=tbl_staff_info.staff_id
                                                INNER JOIN staff_post ON tbl_staff_post.post=staff_post.staff_post_id 
                                                INNER JOIN tbl_staff_dept ON tbl_staff_post.department=tbl_staff_dept.staff_dept_id  WHERE tbl_staff_post.status=1 AND tbl_staff_post.department='".$department_id."'");
  $query->execute();
  $i=1;
  while($rows=$query->fetch()){
      $staff_id=$rows['staff_id'];
      $family_name=$rows['family_name'];
      $last_name=$rows['first_name'];
      $nid=$rows['nid'];
      $staff_post_full_name=$rows['staff_post_full_name'];
      $staff_dept_full_name=$rows['staff_dept_full_name'];
      echo "
        <tr class='clickable-order' data-id=$id>
            <td>$i</td>
            <td>$staff_id</td>
            <td>$family_name</td>
            <td>$last_name</td>
            <td>$nid</td>
            <td>$staff_post_full_name</td>
            <td>$staff_dept_full_name</td>
             <td><div class='badge badge-success'>Active</div></td>
        </tr>";
         $i++;
  }
  }
  else if($action=="load_status_data"){
  
   
  $status_id=$_POST['status_id'];  
  $query=$conn->prepare("SELECT si.*, sp.*
FROM tbl_staff_info si
INNER JOIN tbl_staff_post sp ON sp.staff_id = si.staff_id  WHERE  sp.status='".$status_id."'");
  $query->execute();
  $i=1;
  while($rows=$query->fetch()){
      $staff_id=$rows['staff_id'];
      $family_name=$rows['family_name'];
      $last_name=$rows['first_name'];
      $nid=$rows['nid'];
      $staff_post_full_name=$rows['staff_post_full_name'];
      $staff_dept_full_name=$rows['staff_dept_full_name'];
      echo "
        <tr class='clickable-order' data-id=$id>
            <td>$i</td>
            <td>$staff_id</td>
            <td>$family_name</td>
            <td>$last_name</td>
            <td>$nid</td>
            // <td>$staff_post_full_name</td>
            // <td>$staff_dept_full_name</td>
             <td><div class='badge badge-success'>Active</div></td>
        </tr>";
         $i++;
  }
      
  }
  else if($action=="load_age_data"){
      $age_id=$_POST['age_id'];
      if($age_id==1){
          $now=date('Y');
          $from=intval($now)-28;
          $to=intval($now)-18;
          $query=$conn->prepare("SELECT tbl_staff_info.family_name,
                                                tbl_staff_info.first_name,tbl_staff_info.nid,tbl_staff_info.staff_id,staff_post.staff_post_full_name, tbl_staff_dept.staff_dept_full_name,tbl_staff_post.status
                                                FROM tbl_staff_post
                                                INNER JOIN tbl_staff_info ON tbl_staff_post.staff_id=tbl_staff_info.staff_id
                                                INNER JOIN staff_post ON tbl_staff_post.post=staff_post.staff_post_id 
                                                INNER JOIN tbl_staff_dept ON tbl_staff_post.department=tbl_staff_dept.staff_dept_id  WHERE
                                               DATE_FORMAT(tbl_staff_info.dob,'%Y')>='".$from."' AND DATE_FORMAT(tbl_staff_info.dob,'%Y')<='".$to."'
                                                AND tbl_staff_post.status=1");
  $query->execute();
  $i=1;
  while($rows=$query->fetch()){
      $staff_id=$rows['staff_id'];
      $family_name=$rows['family_name'];
      $last_name=$rows['first_name'];
      $nid=$rows['nid'];
      $staff_post_full_name=$rows['staff_post_full_name'];
      $staff_dept_full_name=$rows['staff_dept_full_name'];
      echo "
        <tr class='clickable-order' data-id=$id>
            <td>$i</td>
            <td>$staff_id</td>
            <td>$family_name</td>
            <td>$last_name</td>
            <td>$nid</td>
            <td>$staff_post_full_name</td>
            <td>$staff_dept_full_name</td>
             <td><div class='badge badge-success'>Active</div></td>
        </tr>";
         $i++;
  }
      
      }
      else if($age_id==2){
         $now=date('Y');
          $from=intval($now)-39;
          $to=intval($now)-29;
          $query=$conn->prepare("SELECT tbl_staff_info.family_name,
                                                tbl_staff_info.first_name,tbl_staff_info.nid,tbl_staff_info.staff_id,staff_post.staff_post_full_name, tbl_staff_dept.staff_dept_full_name,tbl_staff_post.status
                                                FROM tbl_staff_post
                                                INNER JOIN tbl_staff_info ON tbl_staff_post.staff_id=tbl_staff_info.staff_id
                                                INNER JOIN staff_post ON tbl_staff_post.post=staff_post.staff_post_id 
                                                INNER JOIN tbl_staff_dept ON tbl_staff_post.department=tbl_staff_dept.staff_dept_id  WHERE
                                               DATE_FORMAT(tbl_staff_info.dob,'%Y')>='".$from."' AND DATE_FORMAT(tbl_staff_info.dob,'%Y')<='".$to."'
                                                AND tbl_staff_post.status=1");
  $query->execute();
  $i=1;
  while($rows=$query->fetch()){
      $staff_id=$rows['staff_id'];
      $family_name=$rows['family_name'];
      $last_name=$rows['first_name'];
      $nid=$rows['nid'];
      $staff_post_full_name=$rows['staff_post_full_name'];
      $staff_dept_full_name=$rows['staff_dept_full_name'];
      echo "
        <tr class='clickable-order' data-id=$id>
            <td>$i</td>
            <td>$staff_id</td>
            <td>$family_name</td>
            <td>$last_name</td>
            <td>$nid</td>
            <td>$staff_post_full_name</td>
            <td>$staff_dept_full_name</td>
             <td><div class='badge badge-success'>Active</div></td>
        </tr>";
         $i++;
  }      
      }
      else if($age_id==3){
       $now=date('Y');
          $from=intval($now)-50;
          $to=intval($now)-40;
          $query=$conn->prepare("SELECT tbl_staff_info.family_name,
                                                tbl_staff_info.first_name,tbl_staff_info.nid,tbl_staff_info.staff_id,staff_post.staff_post_full_name, tbl_staff_dept.staff_dept_full_name,tbl_staff_post.status
                                                FROM tbl_staff_post
                                                INNER JOIN tbl_staff_info ON tbl_staff_post.staff_id=tbl_staff_info.staff_id
                                                INNER JOIN staff_post ON tbl_staff_post.post=staff_post.staff_post_id 
                                                INNER JOIN tbl_staff_dept ON tbl_staff_post.department=tbl_staff_dept.staff_dept_id  WHERE
                                               DATE_FORMAT(tbl_staff_info.dob,'%Y')>='".$from."' AND DATE_FORMAT(tbl_staff_info.dob,'%Y')<='".$to."'
                                                AND tbl_staff_post.status=1");
  $query->execute();
  $i=1;
  while($rows=$query->fetch()){
      $staff_id=$rows['staff_id'];
      $family_name=$rows['family_name'];
      $last_name=$rows['first_name'];
      $nid=$rows['nid'];
      $staff_post_full_name=$rows['staff_post_full_name'];
      $staff_dept_full_name=$rows['staff_dept_full_name'];
      echo "
        <tr class='clickable-order' data-id=$id>
            <td>$i</td>
            <td>$staff_id</td>
            <td>$family_name</td>
            <td>$last_name</td>
            <td>$nid</td>
            <td>$staff_post_full_name</td>
            <td>$staff_dept_full_name</td>
             <td><div class='badge badge-success'>Active</div></td>
        </tr>";
         $i++;
  }        
      }
      else if($age_id==4){
         $now=date('Y');
          $from=intval($now)-51;
          $query=$conn->prepare("SELECT tbl_staff_info.family_name,
                                                tbl_staff_info.first_name,tbl_staff_info.nid,tbl_staff_info.staff_id,staff_post.staff_post_full_name, tbl_staff_dept.staff_dept_full_name,tbl_staff_post.status
                                                FROM tbl_staff_post
                                                INNER JOIN tbl_staff_info ON tbl_staff_post.staff_id=tbl_staff_info.staff_id
                                                INNER JOIN staff_post ON tbl_staff_post.post=staff_post.staff_post_id 
                                                INNER JOIN tbl_staff_dept ON tbl_staff_post.department=tbl_staff_dept.staff_dept_id  WHERE
                                               DATE_FORMAT(tbl_staff_info.dob,'%Y')<='".$from."'  
                                                AND tbl_staff_post.status=1");
  $query->execute();
  $i=1;
  while($rows=$query->fetch()){
      $staff_id=$rows['staff_id'];
      $family_name=$rows['family_name'];
      $last_name=$rows['first_name'];
      $nid=$rows['nid'];
      $staff_post_full_name=$rows['staff_post_full_name'];
      $staff_dept_full_name=$rows['staff_dept_full_name'];
      echo "
        <tr class='clickable-order' data-id=$id>
            <td>$i</td>
            <td>$staff_id</td>
            <td>$family_name</td>
            <td>$last_name</td>
            <td>$nid</td>
            <td>$staff_post_full_name</td>
            <td>$staff_dept_full_name</td>
             <td><div class='badge badge-success'>Active</div></td>
        </tr>";
         $i++;
  }     
      }
  }
?>