<?php
    include ('../../../meet/con.php');
    include ('./functions.php');
    $connection=$conn;
    class iClass{
        private $connect;
        public function __construct() {
            global $connection;
            $this->connect=$connection;
        }
        function save_iClass(){
            $iclass_name = $_POST['iclass_name'];
            $iclass_code = $_POST['iclass_code'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_item_class WHERE iclass_name = ? OR iclass_code = ?");
            $stmt->execute([$iclass_name, $iclass_code]);
            if($stmt->rowCount()>0){
                sendFeedback(400, "Class already exists!"); 
                return; 
            } else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_item_class(iclass_name, iclass_code) VALUES (?, ?)");
                if($stmt->execute([$iclass_name, $iclass_code])){
                    sendFeedback(200, "Class saved successfully!"); 
                    return;
                }else{
                    sendFeedback(400, "Failed to save class!"); 
                    return; 
                }
            }

        }
        function view_iClass(){
            $iclass_id = $_POST['iclass_id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_item_class WHERE iclass_id = ?");
            $stmt->execute([$iclass_id]);

            $data=$stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData; 
        } 
         
        function update_iClass(){
            $iclass_id = $_POST['iclass_id'];    
            $iclass_name = $_POST['iclass_name'];
            $iclass_code = $_POST['iclass_code'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_item_class WHERE (iclass_name = ? OR iclass_code = ?) AND iclass_id != ?");
            $stmt->execute([$iclass_name, $iclass_code, $iclass_id]);
            if($stmt->rowCount() > 0){
                sendFeedback(400, "Class already exists!"); 
                return;  
            } else {
                $stmt = $this->connect->prepare("UPDATE tbl_item_class SET iclass_name = ?, iclass_code = ? WHERE iclass_id = ?");
                if($stmt->execute([$iclass_name, $iclass_code, $iclass_id])){
                    sendFeedback(200, "Class updated successfully!"); 
                    return;  
                } else {
                    sendFeedback(400, "Failed to update class!"); 
                    return; 
                }
            }
        }
        
    	function manage_iClass(){
            $iclass_id = $_POST['iclass_id'];

            $stmt = $this->connect->prepare("SELECT * FROM tbl_item_class WHERE iclass_id = ?");
            $stmt->execute([$iclass_id]);
            $prevData=$stmt->fetch();
            if($prevData['status'] == 1){
                $status = 2;
            }
            else{
                $status = 1;
            }
            $stmt2 = $this->connect->prepare("UPDATE tbl_item_class SET status = ? WHERE iclass_id = ?");
            if($stmt2->execute([$status, $iclass_id])){
                sendFeedback(200, "Operation done successfully!"); 
            } else {
                sendFeedback(400, "Operation failed!"); 
            }
        }
        
        function load_items(){
            $iclass_id = $_POST['iclass'];
            $brand = $_POST['brand'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_items WHERE iclass_id = ? AND brand_id = ? AND status = 1");
            $stmt->execute([$iclass_id, $brand]);

            $data = $stmt->fetchAll();
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
    }	
    $iClass=new iClass();
    $action = $_POST['action'];
    switch($action){
        case 'register':
            $iClass->save_iClass();
            break;
        case 'view':
            $iClass->view_iClass();
            break;
        case 'update':
            $iClass->update_iClass();
            break;
        case 'manage':
            $iClass->manage_iClass();
            break;
        case 'load_items':
            $iClass->load_items();
            break;
    }
?>