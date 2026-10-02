<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="uplaod_receipt"){
   
    $borrWdId=$_POST['book_borrowd_id'];
    $amunt_clreared=intval($_POST['amunt_clreared']);
    $book_price=intval(intval($_POST['book_price']));
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
     $paid=intval($last_paid)+intval($amunt_clreared);
     if($paid>=$book_price){
      $stmt2 = $conn->prepare("UPDATE borrowdetails SET losted_image='".$file_name."',cleared=1,amount='".$paid."' WHERE borrow_details_id = '".$borrWdId."' ");   
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
     else{
        $data = array("status" => "500", "message" => "Amount paid is less than book price");
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData; 
     }
    
}
?>