<?php
    include ('../../../meet/con.php');
    include ('./functions.php');
    $connection=$conn;
    class Brand{
        private $connect;
        public function __construct() {
            global $connection;
            $this->connect=$connection;
        }
        function save_brand(){
            $brand_name = $_POST['brand_name'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_brands WHERE brand_name = ?");
            $stmt->execute([$brand_name]);
            if($stmt->rowCount()>0){
                sendFeedback(400, "Brand already exists!"); 
                return; 
            } else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_brands(brand_name) VALUES (?)");
                if($stmt->execute([$brand_name])){
                    sendFeedback(200, "Brand saved successfully!"); 
                    return;
                }else{
                    sendFeedback(400, "Failed to save brand!"); 
                    return; 
                }
            }

        }
        function view_brand(){
            $brand_id = $_POST['brand_id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_brands WHERE brand_id = ?");
            $stmt->execute([$brand_id]);

            $data=$stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
        
        function update_brand(){
            $brand_id = $_POST['brand_id'];    
            $brand_name = $_POST['brand_name'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_brands WHERE brand_name = ?");
            $stmt->execute([$brand_name]);
            if($stmt->rowCount() > 0){
                sendFeedback(400, "Brand already exists!"); 
                return;  
            } else {
                $stmt = $this->connect->prepare("UPDATE tbl_brands SET brand_name = ? WHERE brand_id = ?");
                if($stmt->execute([$brand_name, $brand_id])){
                    sendFeedback(200, "Brand updated successfully!"); 
                    return;  
                } else {
                    sendFeedback(400, "Failed to update brand!"); 
                    return; 
                }
            }
        }
        
    	function manage_brand(){
            $brand_id = $_POST['brand_id'];

            $stmt = $this->connect->prepare("SELECT * FROM tbl_brands WHERE brand_id = ?");
            $stmt->execute([$brand_id]);
            $prevData=$stmt->fetch();
            if($prevData['status'] == 1){
                $status = 2;
            }
            else{
                $status = 1;
            }
            $stmt2 = $this->connect->prepare("UPDATE tbl_brands SET status = ? WHERE brand_id = ?");
            if($stmt2->execute([$status, $brand_id])){
                sendFeedback(200, "Operation done successfully!"); 
            } else {
                sendFeedback(400, "Operation failed!"); 
            }
        }
    }	
    $brand=new Brand();
    $action = $_POST['action'];
    switch($action){
        case 'register':
            $brand->save_brand();
            break;
        case 'view':
            $brand->view_brand();
            break;
        case 'update':
            $brand->update_brand();
            break;
        case 'manage':
            $brand->manage_brand();
            break;
    }
?>

