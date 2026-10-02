<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="uplaod_receipt"){
   
    $borrWdId=$_POST['book_borrowd_id'];
    $amunt_clreared=$_POST['amunt_clreared'];
    $file = $_FILES['image_file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $destination = '../circulation/fines/';
    $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    
    $file_path = $destination . $file_name_without_extension . '.' . $file_extension;
    $file_to_save=$file_name_without_extension . '.' . $file_extension;
    
     $stmt_sum = $conn->prepare("SELECT SUM(amount) AS last_paid FROM borrowdetails WHERE borrow_details_id = '".$borrWdId."' ");
     $stmt_sum->execute();
     $data_sum=$stmt_sum->fetch();
     $last_paid=$data_sum['last_paid'];
     $paid=$last_paid+$amunt_clreared;
     $stmt2 = $conn->prepare("UPDATE borrowdetails SET cleared_image='".$file_name."',cleared=1,amount='".$paid."' WHERE borrow_details_id = '".$borrWdId."' ");   
    if ($stmt2->execute()) {
    $data = array("status" => "200", "message" => "Fine cleared successfully!");
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;
} else {
    $data = array("status" => "500", "message" => "Failed to remove fine!");
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;
} 
}
else if($action=="return_losted"){
    $browid=$_POST['borroWId'];
    $borrow_status="returned";
    $todaynow = date('Y-m-d');
            $stmt = $conn->prepare("SELECT * FROM borrowdetails WHERE borrow_details_id='".$browid."'");
            $stmt->execute();
            $data_st=$stmt->fetch();
            $row_dat=$stmt->rowCount();
            if($row_dat>0){
            $copyid=$data_st['book_id']; 
            $stmt1 = $conn->prepare("UPDATE book_copies SET status ='Available' WHERE id='".$copyid."' ");
            $stmt1->execute();
            $stmt2 = $conn->prepare("UPDATE borrowdetails
                                                    SET borrow_status ='returned',actual_returned='".$todaynow."'
                                                    WHERE borrow_details_id = '".$browid."' ");
           
                        if($stmt2->execute()){
                            $data = array("status"=>"200","message" => "Book returned successfully!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        } else {
                            $data = array("status"=>"500","message" => "Failed to return book!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        }       
                    
            } 
    
    
}
?>