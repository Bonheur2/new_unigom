<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="view_salary"){
$staff_id=$_POST['staff_id'];
$query1=$conn->prepare("SELECT i.family_name,i.first_name,i.email,
                        i.phone,s.*,sp.status,sd.staff_dept_full_name,st.staff_type_full_name,
                        ac.acad_grad_full_name
                        FROM tbl_staff_post sp
                        INNER JOIN tbl_staff_salary s ON sp.staff_id=s.staff_Id 
                        INNER JOIN tbl_staff_info i ON sp.staff_id=i.staff_id 
                        INNER JOIN tbl_staff_dept sd ON sp.department=sd.staff_dept_id
                        INNER JOIN tbl_staff_type st ON sp.is_acadmic=st.staff_type_id
                        INNER JOIN tbl_acad_grade ac ON sp.acad_grad_id=ac.acad_grad_id
                        WHERE sp.status=1 
                        AND s.salary_Id='".$staff_id."'");
$query1->execute();
$rows=$query1->fetch();
echo json_encode($rows);
}
else if($action=="edit_salary"){
$staff_id=$_POST['staff_id'];
$query2=$conn->prepare("SELECT i.family_name,i.first_name,s.*
FROM tbl_staff_salary s INNER JOIN tbl_staff_info i
ON s.staff_Id=i.staff_id WHERE s.salary_Id='".$staff_id."'");
$query2->execute();
$rows=$query2->fetch();
echo json_encode($rows);
}
else if($action=="confirm_edit"){
    $salary_id=$_POST['salary_id'];
    $salary=$_POST['salary'];
    $query3=$conn->prepare("SELECT staff_Id FROM tbl_staff_salary WHERE salary_id='".$salary_id."'");
    $query3->execute();
    $staff_id=$query3->fetch();
    $id=$staff_id['staff_Id'];
    $query4=$conn->prepare("UPDATE tbl_staff_salary SET gross_salary='".$salary."' WHERE salary_Id='".$salary_id."'");
    $query5=$conn->prepare("UPDATE  tbl_staff_post SET basic_salary='".$salary."' WHERE staff_id='".$id."'");
    if($query4->execute() && $query5->execute()){
        $data = array("status"=>"200","message" => "Updated Successfully!!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
    echo $jsonData;  
    }
    else{
        $data = array("status"=>"500","message" => "Action failed!!");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
    echo $jsonData;  
    }
    
}
?>