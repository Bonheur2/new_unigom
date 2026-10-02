<?php
include ('../../meet/con.php');
$action = $_POST['action'];
if($action=="loodbook_losted"){
$query = $conn->prepare("SELECT a.book_id, a.title,a.book_code, a.price,b.*, c.*
                        FROM book_copies b
                        INNER JOIN books a ON b.book_id = a.book_id
                        INNER JOIN borrowdetails c ON b.id = c.book_id
                        WHERE  c.borrow_status = 'lost'");
$query->execute();
$getdata = $query->fetchAll();
$data = array();
foreach ($getdata as $rows) {
    $student = $rows['borrow_id'];
    $query2 = $conn->prepare("SELECT a.fname, a.lname,a.reg_no,c.dept_full_name FROM tbl_register_program_ug b
                             INNER JOIN tbl_admission a ON b.reg_no = a.reg_no 
                             INNER JOIN  tbl_department c ON b.dept_id=c.dept_id WHERE a.reg_no ='". $student."'");
    $query2->execute();
    $row = $query2->fetch();
    $query3 = $conn->prepare("SELECT a.staff_family_name, a.staff_first_name,a.staff_id,a.staff_id,c.dept_full_name FROM  tbl_staff a
                             INNER JOIN tbl_department c ON a.Department = c.dept_id WHERE a.id ='".$student."'");
    $query3->execute();
    $row1 = $query3->fetch();
    $data[] = array(
        "fname" => $row['fname'],
        "lname" => $row['lname'],
        "reg_no"=>$row['reg_no'],
        "level_id" => $row['level_id'],
        "department"=>$row['dept_full_name'],
        "title" => $rows['title'],
        "return_date"=>$rows['date_return'],
        "issue_date"=>$rows['issue_date'],
        "price"=>$rows['price'],
        "book_code"=>$rows['bar_code_copy'],
        "staffname"=>$row1['staff_family_name'],
        "stafflname"=>$row1['staff_first_name'],
        "depstaff"=>$row1['dept_full_name'],
        "staffid"=>$row1['staff_id']
    );
}

echo json_encode($data);

}

?>