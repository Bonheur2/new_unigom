<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="getedata"){
  $br_id=$_POST['dataId'];
  $info=array();
  $query1=$conn->prepare("SELECT a.borrow_details_id,b.dept_id,c.dept_full_name,
    d.fname,d.lname,d.phone,d.reg_no 
    FROM tbl_register_program_ug b
    INNER JOIN borrowdetails a ON a.borrow_id =b.reg_no
    INNER JOIN tbl_department c ON b.dept_id=c.dept_id
    INNER JOIN tbl_admission d ON b.reg_no=d.reg_no
    WHERE a.borrow_details_id='".$br_id."' ");
    $query1->execute();
    $data1=$query1->fetch();
    
    $sql = $conn->prepare("SELECT borrowdetails.borrow_details_id,tbl_department.dept_full_name,
    tbl_staff.staff_family_name,
    tbl_staff.staff_first_name,
    tbl_staff.PhoneNumber,
    tbl_staff.staff_id,
    tbl_staff.Department
    FROM tbl_staff 
    INNER JOIN borrowdetails ON  tbl_staff.id=borrowdetails.borrow_id
    INNER JOIN tbl_department ON tbl_staff.Department=tbl_department.dept_id
    WHERE borrowdetails.borrow_details_id='".$br_id."'");
    $sql->execute();
    $data2=$sql->fetch();
    
    
            array_push($info,$data1, $data2);
            echo json_encode($info);
           
    
}




?>