<?php
include ('../../meet/con.php');
$action = $_POST['action'];
if($action=="loodbook_delayed"){
$date = date('Y-m-d');
$query = $conn->prepare("SELECT a.book_id, a.title,a.book_code, b.*, c.*
                        FROM book_copies b
                        INNER JOIN books a ON b.book_id = a.book_id
                        INNER JOIN borrowdetails c ON b.id = c.book_id
                        WHERE DATE_FORMAT(c.date_return, '%Y-%m-%d') < :date AND c.borrow_status = 'pending'");
$query->bindParam(':date', $date);
$query->execute();

$getdata = $query->fetchAll();

$data = array();
foreach ($getdata as $rows) {
    $date1 = strtotime(date('Y-m-d'));
    $date2 = strtotime($rows['date_return']);
    $days = floor(($date1  - $date2) / (60 * 60 * 24));
    $f_st=$conn->prepare("SELECT fine FROM books_fine WHERE status=1");
    $f_st->execute();
    $st_fine=$f_st->fetch();
    $fine=$st_fine['fine'];
    $fines=$days*$fine;
    $student = $rows['borrow_id'];
    $query2 = $conn->prepare("SELECT a.fname, a.lname,a.reg_no,c.dept_full_name FROM tbl_register_program_ug b
                             INNER JOIN tbl_admission a ON b.reg_no = a.reg_no 
                             INNER JOIN  tbl_department c ON b.dept_id=c.dept_id WHERE a.reg_no = :student");
    $query2->bindParam(':student', $student);
    $query2->execute();
    $row = $query2->fetch();
    $data[] = array(
        "fname" => $row['fname'],
        "lname" => $row['lname'],
        "level_id" => $row['level_id'],
        "reg_no" =>$row['reg_no'],
        "department"=>$row['dept_full_name'],
        "title" => $rows['title'],
        "book_code"=>$rows['book_code_number'],
        "date_isse"=>$rows['issue_date'],
        "date_return"=>$rows['date_return'],
        "days"=>$days,
        "fines"=>$fines
    );
}

echo json_encode($data);

}

else if($action=="loodbook_uncleared"){
    $i=0;
 $date = date('Y-m-d');
$query = $conn->prepare("SELECT a.book_id, a.title,a.book_code, b.*, c.*
                        FROM book_copies b
                        INNER JOIN books a ON b.book_id = a.book_id
                        INNER JOIN borrowdetails c ON b.id = c.book_id
                        WHERE DATE_FORMAT(c.date_return, '%Y-%m-%d') < :date AND c.cleared=1 ");
$query->bindParam(':date', $date);
$query->execute();
while($rows=$query->fetch()){
    $i++;
    $regNo=$rows['borrow_id'];
    $paid=$rows['amount'];
    $date1 = strtotime(date('Y-m-d'));
    $date2 = strtotime($rows['date_return']);
    $days = floor(($date1  - $date2) / (60 * 60 * 24));
    $f_st=$conn->prepare("SELECT fine FROM books_fine WHERE status=1");
    $f_st->execute();
    $st_fine=$f_st->fetch();
    $fine=$st_fine['fine'];
    $fines=$days*$fine;
    if($fines>$paid){
        $remain=$fines-$paid;
    }
    else{
        $remain=0;
    }
    $query2 = $conn->prepare("SELECT a.fname, a.lname,a.reg_no,c.dept_full_name FROM tbl_register_program_ug b
                             INNER JOIN tbl_admission a ON b.reg_no = a.reg_no 
                             INNER JOIN  tbl_department c ON b.dept_id=c.dept_id WHERE a.reg_no = :student");
    $query2->bindParam(':student', $regNo);
    $query2->execute();
    $row = $query2->fetch();
    $row_count=$row->rowCount();
    if($row_count>0){
        ?>
        <tr>
            <td><?php echo $i; ?></td>
        </tr>
  <?php  }
}
}

?>