<?php
    include ('../../../meet/con.php');
    include ('./functions.php');
    $connection=$conn;
    
    class Category{
        private $connect;
        public function __construct() {
    		global $connection;
    		$this->connect=$connection;
    	  }
        function save_category(){
            $item_id=$_POST['item_id'];
            $catg_name=$_POST['catg_name'];
            $specifications=$_POST['specifications'];
    
            $stmt = $this->connect->prepare("SELECT * FROM tbl_categories WHERE item_id = ? AND catg_name = ?");
            $stmt->execute([$item_id, $catg_name]);
            if($stmt->rowCount()>0){
                sendFeedback(400, "Data already exists!");
            } else {
                $stmt = $this->connect->prepare("INSERT INTO tbl_categories(item_id, catg_name, specifications) VALUES(?, ?, ?)");
                if($stmt->execute([$item_id, $catg_name, $specifications])){
                    sendFeedback(200, "Data saved successfully!");
                } else {
                    sendFeedback(400, "Failed to save data!");
                }
            }
        }
    	
        function view_category(){
        	$catg_id = $_POST['catg_id'];
            $stmt = $this->connect->prepare("SELECT c.* , i.iclass_id, i.brand_id FROM tbl_categories c INNER JOIN tbl_items i ON c.item_id = i.item_id WHERE c.catg_id = ?");
            $stmt->execute([$catg_id]);
            $data = $stmt->fetch();
            
            $brand_id = $data['brand_id']==NULL?0:$data['brand_id'];
            
            $stmt2 = $this->connect->prepare("SELECT * FROM tbl_items WHERE iclass_id = ? AND brand_id = ? AND status = 1");
            $stmt2->execute([$data['iclass_id'], $brand_id]);
            $data2 = $stmt2->fetchAll();
            
            $info=array();
            array_push($info, $data, $data2);
            $jsonData = json_encode($info);
            echo $jsonData; 
        }
        
        function view_extended(){
        	$catg_id = $_POST['catg_id'];
            $stmt = $this->connect->prepare("SELECT 
                                                    c.catg_name,
                                                    c.specifications,
                                                    cls.iclass_name, 
                                                    cls.iclass_code, 
                                                    c.catg_name , 
                                                    i.item_name, 
                                                    b.brand_name
                                                FROM tbl_categories c 
                                                    INNER JOIN tbl_items i ON c.item_id = i.item_id
                                                    INNER JOIN tbl_item_class cls ON i.iclass_id = cls.iclass_id
                                                    LEFT JOIN tbl_brands b ON i.brand_id = b.brand_id
                                                WHERE c.catg_id = ?");
            $stmt->execute([$catg_id]);
            $data = $stmt->fetch();
            $jsonData = json_encode($data);
            echo $jsonData; 
        }
        
        function update_category(){
            $catg_id = $_POST['catg_id'];
            $item_id=$_POST['item_id'];
            $catg_name=$_POST['catg_name'];
            $specifications=$_POST['specifications'];
    
            $stmt = $this->connect->prepare("SELECT * FROM tbl_categories WHERE item_id = ? AND catg_name = ?");
            $stmt->execute([$item_id, $catg_name]);
    
            if($stmt->rowCount()>0){
                sendFeedback(400, "Data already exists!");   
            } else {
                $stmt = $this->connect->prepare(" UPDATE tbl_categories SET item_id=?, catg_name=?, specifications = ? WHERE catg_id = ?");
                if($stmt->execute([$item_id, $catg_name, $specifications, $catg_id])){
                    sendFeedback(200, "data updated successfully!");   
                } else {
                    sendFeedback(400, "Failed to update data!");   
                }
            }
        }
    
    	function manage_category(){
            $catg_id = $_POST['catg_id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_categories WHERE catg_id = ?");
            $stmt->execute([$catg_id]);
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $status = 2;
            }
            else{
                $status = 1;
            }
            $stmt2 = $this->connect->prepare("UPDATE tbl_categories SET status = ? WHERE catg_id = ?");  
            
            if($stmt2->execute([$status, $catg_id])){
                sendFeedback(200, "operation done successfully!"); 
            } else {
                sendFeedback(400, "operation failed!"); 
            }
        }
    }	
	$category=new Category();
    $action = $_POST['action'];
	switch($action){
	    case 'register':
	        $category->save_category();
	        break;
	    case 'update':
	        $category->update_category();
	        break;
	    case 'manage':
	        $category->manage_category();
	        break;
	    case 'view':
	        $category->view_category();
	        break;
	    case 'view_extended':
	        $category->view_extended();
	        break;
	}

?>

