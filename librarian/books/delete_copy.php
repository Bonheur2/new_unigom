<?php
include ('../../meet/con.php');
$id=$_POST['id'];
$val="True";
 if ($val) {
     $Query=$conn->prepare("SELECT id FROM book_copies WHERE book_code_number='".$id."'");
     $Query->execute();
     $d2=$Query->fetch();
     $d3=$d2['id'];
     $check=$conn->prepare("SELECT book_id FROM  borrowdetails WHERE book_id='".$d3."'");
     $check->execute();
     $rows=$check->rowCount();
     if($rows>0){
     $data=array("status"=>"400","message"=>"Copy Already Borrowed!!");    
     }
     else{
      $query2=$conn->prepare("DELETE FROM  book_copies  WHERE 	book_code_number='".$id."'");
     
     if($query2->execute()){
       $data=array("status"=>"200","message"=>"Book removed successfully!!");   
     }
     else {
       $data=array("status"=>"500","message"=>"Action failed!!");  
 }  
     }
     
    
    }  

 echo json_encode($data);
?>