<?php

include ('../../meet/con.php');
$connection=$conn;
class Document{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_document(){
        $doc_name = $_POST['docu'];
        $prg_type = $_POST['prg_type'];
        $file_name = $_POST['file_name'];
        $file_type = $_POST['file_type'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_document_type WHERE document_name='".$doc_name."' AND prg_type='".$prg_type."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Document already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_document_type(prg_type,document_name,file_type,file_name) 
                  	VALUES('".$prg_type."','".$doc_name."','".$file_type."','".$file_name."')");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Document saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to save document!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }

	}
    function change_status(){
        $doc_id = $_POST['did'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_document_type WHERE doc_id='".$doc_id."'");
        $stmt->execute();
        $prev_data=$stmt->fetch();
        if($prev_data['status']==1){
            $status=2;
        } else {
            $status=1;
        }
            $stmt2 = $this->connect->prepare("UPDATE tbl_document_type SET status='".$status."' WHERE doc_id='".$doc_id."'");
            if($stmt2->execute()){
                $data = array("status"=>"200","message" => "operation done successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "operation failed!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }

	}
	function update_doc(){
	    $doc_id=$_POST[id];
	    $stt=$this->connect->prepare("SELECT * FROM tbl_document_type WHERE doc_id ='".$doc_id."'");
	    $stt->execute();
	    $result=$stt->fetch();
	    echo json_encode($result);
	}
	
	function Change_doc(){
	    $doc_id=$_POST['id_doc'];
	    $prg_type=$_POST['prg_type_edit'];
	    $doc_name=$_POST['document_name_edit'];
	    $file_name=$_POST['file_name_edit'];
	    $file_type=$_POST['file_type_edit'];
	    $stt=$this->connect->prepare("UPDATE  tbl_document_type SET prg_type='".$prg_type."',document_name='".$doc_name."',file_type='".$file_type."',	file_name='".$file_name."' WHERE doc_id='".$doc_id."'");
	    $result=$stt->execute();
	    if($result){
	        $data=array("status"=>200,"message"=>"Updated Successfully !");
	        echo json_encode($data);
	    }
	    else{
	      $data=array("status"=>200,"message"=>"Updated Successfully !");
	      echo json_encode($data);
	    }
	}
}	
		$doc=new Document();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $doc->save_document();
		        break;
		    case 'changestatus':
		        $doc->change_status();
		        break;
		   case 'Update_doc':
		       $doc->update_doc();
		       break;
		   case 'update':
		       $doc->Change_doc();
		       break;
		}

	?>

