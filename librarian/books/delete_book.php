<?php
include ('../../meet/con.php');
$id=$_POST['id'];
$query=$conn->prepare("SELECT books.image, book_copies.book_code_number FROM books
INNER JOIN book_copies ON books.book_id=book_copies.book_id WHERE books.book_id='".$id."'");
$query->execute();
$rows=$query->fetch();
$image_delete1=$rows['image'];
if($image_delete1){
  $data=array("status"=>"404","message"=>"Book can not removed!!");   
}
else{
 $query1=$conn->prepare("SELECT image FROM books WHERE book_id='".$id."'");
$query1->execute();  
$rows1=$query1->fetch();
$image_delete2=$rows1['image'];
$ckeckImage='uploads/646dc5b02e9e2.png'; 
if($ckeckImage==$image_delete2){
   $query2=$conn->prepare("DELETE FROM books  WHERE book_id='".$id."'");
     $query2->execute();
     $data=array("status"=>"200","message"=>"Book removed successfully!!");  
}
else{
 if (unlink($image_delete2)) {
     $query2=$conn->prepare("DELETE FROM books  WHERE book_id='".$id."'");
     $query2->execute();
     $data=array("status"=>"200","message"=>"Book removed successfully!!");
    } else {
       $data=array("status"=>"500","message"=>"Action failed!!");  
 }   
}

    
}
 echo json_encode($data);
?>