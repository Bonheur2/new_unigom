<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="getData"){
    $book_id=$_POST['book_id'];
    $query=$conn->prepare("SELECT * FROM books WHERE book_id='".$book_id."'");
    $query->execute();
    $data=$query->fetch();
    echo json_encode($data);
}
else if($action=="confirm"){
    $book_id=$_POST['e_bookId'];
    $title=$_POST['e_title'];
    $class=$_POST['e_class'];
    $isbn=$_POST['e_isbn'];
    $price=$_POST['e_price'];
    $other_info=$_POST['e_other_info'];
    $section=$_POST['e_section'];
    $material_type=$_POST['e_material_type'];
    $auth=$_POST['e_author1'];
    $sec_author=$_POST['e_author2'];
    $publisher1=$_POST['e_pubisher1'];
    $publisher2=$_POST['e_pubisher2'];
    $dep=$_POST['e_dep'];
    $publ_date=$_POST['e_date_publish'];
    $version=$_POST['e_vers_publish'];
    $language=$_POST['e_lang_publish'];
    $org_language=$_POST['e_org_lang'];
    $status=$_POST['e_status'];
    $lease=$_POST['e_lease'];
    $b_locations=$_POST['b_locations'];
    $query=$conn->prepare("UPDATE books SET title='".$title."',publication_date='".$publ_date."',department='".$dep."',author='".$auth."',
    	publisher='".$publisher1."',isbn='".$isbn."',location='".$b_locations."' ,price='".$price."',other_info='".$other_info."',date_of_publication='".$publ_date."',
    	version='".$version."',	language_publication='".$language."',orginal_language='".$org_language."',status='".$status."',
    	lease='".$lease."',material_type='".$material_type."',section='".$section."',secondauthor='".$sec_author."',secondpublisher='".$publisher2."',book_class='".$class."' WHERE
    	book_id='".$book_id."'");
    	
    if($query->execute()){
        $data = array("status" => "200", "message" => "Book Updated Successfully!");
         echo json_encode($data);   
    }
    else{
    $data = array("status" => "500", "message" => "Action Fail Try Again !");
         echo json_encode($data);       
    }
    
}
?>