<?php
include ('../../meet/con.php');

    $old_book_code=$_POST['old_code'];
    $system_code=$_POST['new_code'];
    $check=$conn->prepare("SELECT old_copy_code FROM book_copies WHERE old_copy_code='".$old_book_code."'");
    $check->execute();
    $rows=$check->rowCount();
    if($rows>0){
       $data = array("status" => "400", "message" => "Already Taken !");
         echo json_encode($data);     
    }
    else{
       $query=$conn->prepare("UPDATE book_copies SET old_copy_code='".$old_book_code."' WHERE
    	book_code_number='".$system_code."'");
    	
    if($query->execute()){
        $data = array("status" => "200", "message" => "Old Code Added Successfully!");
         echo json_encode($data);   
    }
    else{
      $data = array("status" => "500", "message" => "Action Fail Try Again !");
         echo json_encode($data);     
    }  
    }
 

?>