<?php
    include ('../../../meet/con.php');
    include ('./functions.php');
    $connection=$conn;
    class Item{
        private $connect;
        public function __construct() {
            global $connection;
            $this->connect=$connection;
        }
        function save_item(){
            $item_name = $_POST['item_name'];
            $iclass_id = $_POST['iclass_id'];
            $brand_id = $_POST['brand_id'];
            $item_abbr=$_POST['item_abb'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_items WHERE item_name = ? AND iclass_id = ? AND brand_id = ?");
            $stmt->execute([$item_name, $iclass_id, $brand_id]);
            if($stmt->rowCount()>0){
                sendFeedback(400, "item already exists!"); 
                return; 
            } else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_items(item_name, iclass_id, brand_id,item_abbrev) VALUES (?, ?, ?,?)");
                if($stmt->execute([$item_name, $iclass_id, $brand_id,$item_abbr])){
                    sendFeedback(200, "item saved successfully!"); 
                    return;
                }else{
                    sendFeedback(400, "Failed to save item!"); 
                    return; 
                }
            }

        }
        function view_item(){
            $item_id = $_POST['item_id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_items WHERE item_id = ?");
            $stmt->execute([$item_id]);

            $data=$stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
        
        function update_item(){
            $item_id = $_POST['item_id'];    
            $item_name = $_POST['item_name'];
            $iclass_id = $_POST['iclass_id'];
            $brand_id = $_POST['brand_id'];
            $abrrv=strtolower($_POST['item_abbE']);
            $stmt = $this->connect->prepare("SELECT * FROM tbl_items WHERE item_name = ? AND iclass_id = ? AND brand_id = ? AND item_abbrev= ? ");
            $stmt->execute([$item_name, $iclass_id, $brand_id,$abrrv]);
            if($stmt->rowCount() > 0){
                sendFeedback(400, "item already exists!"); 
                return;  
            } else {
                $stmt = $this->connect->prepare("UPDATE tbl_items SET item_name = ?, iclass_id = ?, brand_id = ? ,item_abbrev= ? WHERE item_id = ?");
                if($stmt->execute([$item_name, $iclass_id, $brand_id,$abrrv, $item_id])){
                    sendFeedback(200, "item updated successfully!"); 
                    return;  
                } else {
                    sendFeedback(400, "Failed to update item!"); 
                    return; 
                }
            }
        }
        
    	function manage_item(){
            $item_id = $_POST['item_id'];

            $stmt = $this->connect->prepare("SELECT * FROM tbl_items WHERE item_id = ?");
            $stmt->execute([$item_id]);
            $prevData=$stmt->fetch();
            if($prevData['status'] == 1){
                $status = 2;
            }
            else{
                $status = 1;
            }
            $stmt2 = $this->connect->prepare("UPDATE tbl_items SET status = ? WHERE item_id = ?");
            if($stmt2->execute([$status, $item_id])){
                sendFeedback(200, "Operation done successfully!"); 
            } else {
                sendFeedback(400, "Operation failed!"); 
            }
        }
    }	
    $item=new Item();
    $action = $_POST['action'];
    switch($action){
        case 'register':
            $item->save_item();
            break;
        case 'view':
            $item->view_item();
            break;
        case 'update':
            $item->update_item();
            break;
        case 'manage':
            $item->manage_item();
            break;
    }
?>

