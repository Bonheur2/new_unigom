<?php
include ('../../meet/con.php');
$action = $_POST['action'];
if ($action == "search_students_clearance") {
    $student_info = $_POST['student_info'];
$query = $conn->prepare("SELECT a.book_id, a.title,a.book_code, b.*, c.*
                        FROM book_copies b
                        INNER JOIN books a ON b.book_id = a.book_id
                        INNER JOIN borrowdetails c ON b.id = c.book_id
                        WHERE c.borrow_id='".$student_info."' AND (c.borrow_status = 'pending' OR c.borrow_status='lost' OR c.borrow_status='fine added')");

$query->execute();
$getdata = $query->fetchAll();
$data = array();
foreach ($getdata as $rows) {
    $query2 = $conn->prepare("SELECT a.fname, a.lname,a.reg_no,c.dept_full_name FROM tbl_register_program_ug b
                             INNER JOIN tbl_admission a ON b.reg_no = a.reg_no 
                             INNER JOIN  tbl_department c ON b.dept_id=c.dept_id WHERE a.reg_no = :student");
    $query2->bindParam(':student', $student_info);
    $query2->execute();
    $row = $query2->fetch();
    $data[] = array(
        "fname" => $row['fname'],
        "lname" => $row['lname'],
        "reg_no" =>$row['reg_no'],
        "department"=>$row['dept_full_name'],
        "title" => $rows['title'],
        "book_code"=>$rows['book_code'],
        "book_code_number"=>$rows['book_code_number'],
        "issue_date"=>$rows['issue_date']
    );
}

echo json_encode($data);
}

?>