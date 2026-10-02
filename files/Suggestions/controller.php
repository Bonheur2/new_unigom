<?php
    include ('../../meet/con.php');
    $connection = $conn;
    class Suggestion{
        private $connect;
        public function __construct() {
    		global $connection;
    		$this->connect = $connection;
    	  }
        function send_suggestion(){
        	$suggestion = $_POST['suggestion'];
            $stmt0 = $this->connect->prepare("SELECT * FROM tbl_suggestions WHERE suggestion = ?");
            $stmt0->execute([$suggestion]);
            if($stmt0->rowCount()==0){
                $stmt = $this->connect->prepare("INSERT INTO tbl_suggestions(suggestion)VALUES(?)");
                $stmt->execute([$suggestion]);
            }
            $data = array("status" => 200, "message" => "Thank you for your suggestion!");
            $jsonData = json_encode($data);
            echo $jsonData; 
    	}
    }	
    
	$suggestion = new Suggestion();
    $action = $_POST['action'];
	switch($action){
	    case 'send_suggestion':
	        $suggestion->send_suggestion();
	        break;
	}
?>