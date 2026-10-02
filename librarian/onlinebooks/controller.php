<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="searchAutho"){
    $input=$_POST['author'];
    $stmt = $conn->prepare("SELECT * FROM `authors` WHERE author_name like '".$input."%' ");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
}

else if($action=="save_author"){
$author_name=$_POST['author_name'];
         
                $stmt = $conn->prepare("INSERT INTO authors(author_name) 
                          	VALUES('".$author_name."')");
                          	
                        if($stmt->execute()){
                            $data = array("status"=>"200","message" => "Author saved successfully!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        } else {
                            $data = array("status"=>"500","message" => "Failed to save Aurhor!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        }
               
}
else if($action=="searchAuthoSecondary"){
    $input = $_POST['sec_author'];
  $stmt = $conn->prepare("SELECT  * FROM second_authors WHERE names like '".$input."%' ");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;  
}
else if($action=="save_second_author"){
    
        $author_name2=$_POST['author_name_second'];
            $stmt = $conn->prepare("INSERT INTO second_authors(names) 
                          	VALUES('".$author_name2."')");
                          	
                        if($stmt->execute()){
                            $data = array("status"=>"200","message" => "Second Author saved successfully!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        } else {
                            $data = array("status"=>"500","message" => "Failed to save Second Author!");
                            $jsonData = json_encode($data);
                            header('Content-Type: application/json');
                            echo $jsonData; 
                        }
        
}

?>