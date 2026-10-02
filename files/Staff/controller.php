<?php
include ('../../meet/con.php');
$connection=$conn;
class Staff{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
	function sendFeedback($s, $m){
        $data = array("status"=>$s,"message" => $m);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
	}
	
	//BEGIN STAFF RANK
    function save_rank(){
    	$full_name = $_POST['acad_grad_full_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_acad_grade WHERE acad_grad_full_name='".$full_name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"rank already exists!");
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_acad_grade(acad_grad_full_name) 
                  	VALUES('".$full_name."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"rank saved successfully!");
            } else {
                $this->sendFeedback(401,"Failed to save rank!");
            }
        }

	}
	
    function view_rank(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_acad_grade WHERE acad_grad_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }

    function update_rank(){
        $id = $_POST['acad_grad_id'];    
        $full_name = $_POST['acad_grad_full_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_acad_grade WHERE acad_grad_full_name='".$full_name."' AND acad_grad_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"rank already exists!");
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_acad_grade SET acad_grad_full_name='".$full_name."' WHERE acad_grad_id='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200, "rank updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update rank!");
            }
        }
    }
    
    function delete_rank(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_acad_grade WHERE acad_grad_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_acad_grade SET status='".$status."' WHERE acad_grad_id='".$id."'");
        if($stmt2->execute()){
            $this->sendFeedback(200,"operation done successfully");
        } else {
            $this->sendFeedback(401,"operation failed!");
            }
    }
    //END STAFF RANK
    
    //BEGIN STAFF PLACEMENT TYPE
    function save_placement_type(){
    	$name = $_POST['staff_place_type_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_place_type WHERE staff_place_type_name='".$name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_staff_place_type(staff_place_type_name) 
                  	VALUES('".$name."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"type saved successfully!");
            } else {
                $this->sendFeedback(401,"Failed to save type!");
            }
        }
	}
	
    function view_placement_type(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_place_type WHERE type_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
 
    function update_placement_type(){
        $id = $_POST['type_id'];    
        $name = $_POST['staff_place_type_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_place_type WHERE staff_place_type_name='".$name."' AND type_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_staff_place_type SET staff_place_type_name='".$name."' WHERE type_id='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200, "type updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update type!");
            }
        }
    }
    
    function delete_placement_type(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_place_type WHERE type_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_staff_place_type SET status='".$status."' WHERE type_id='".$id."'");
        if($stmt2->execute()){
            $this->sendFeedback(200,"operation done successfully");
        } else {
            $this->sendFeedback(401,"operation failed!");
            }
    }
    //END STAFF PLACEMENT TYPE
    
	//BEGIN STAFF PROMOTION TYPES
    function save_promotion_type(){
    	$name = $_POST['promo_type_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_promotion_type WHERE promo_type_name='".$name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_staff_promotion_type(promo_type_name) 
                  	VALUES('".$name."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"type saved successfully!");
            } else {
                $this->sendFeedback(401,"Failed to save type!");
            }
        }

	}
	
    function view_promotion_type(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_promotion_type WHERE promo_type_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
 
    function update_promotion_type(){
        $id = $_POST['promo_type_id'];    
        $name = $_POST['promo_type_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_promotion_type WHERE promo_type_name='".$name."' AND promo_type_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_staff_promotion_type SET promo_type_name='".$name."' WHERE promo_type_id='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200, "type updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update type!");
            }
        }
    }
    
    function delete_promotion_type(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_promotion_type WHERE promo_type_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_staff_promotion_type SET status='".$status."' WHERE promo_type_id='".$id."'");
        if($stmt2->execute()){
            $this->sendFeedback(200,"operation done successfully");
        } else {
            $this->sendFeedback(401,"operation failed!");
            }
    }
    
    //END PROMOTION TYPES
    
    //BEGIN STAFF TYPES
    function save_staff_type(){
    	$name = $_POST['staff_type_full_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_type WHERE staff_type_full_name='".$name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_staff_type(staff_type_full_name) 
                  	VALUES('".$name."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"type saved successfully!");
            } else {
                $this->sendFeedback(401,"Failed to save type!");
            }
        }

	}
	
    function view_staff_type(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_type WHERE staff_type_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }

    function update_staff_type(){
        $id = $_POST['staff_type_id'];    
        $name = $_POST['staff_type_full_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_type WHERE staff_type_full_name='".$name."' AND staff_type_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_staff_type SET staff_type_full_name='".$name."' WHERE staff_type_id='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200, "type updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update type!");
            }
        }
    }
    
    function delete_staff_type(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_type WHERE staff_type_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_staff_type SET status='".$status."' WHERE staff_type_id='".$id."'");
        if($stmt2->execute()){
            $this->sendFeedback(200,"operation done successfully");
        } else {
            $this->sendFeedback(401,"operation failed!");
            }
    }
    //END STAFF TYPES
    
    //BEGIN STAFF DEPARTMENTS
    function save_staff_department(){
    	$fullname = $_POST['staff_dept_full_name'];
        $shortname = $_POST['staff_dept_short_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_dept WHERE staff_dept_full_name='".$fullname."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"Department already exists!");
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_staff_dept(staff_dept_full_name, staff_dept_short_name) 
                  	VALUES('".$fullname."','".$shortname."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"Department saved successfully!");
            } else {
                $this->sendFeedback(401,"Failed to save department!");
            }
        }

	}
	
    function view_staff_department(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_dept WHERE staff_dept_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }

    function update_staff_department(){
        $id = $_POST['staff_dept_id'];    
        $fullname = $_POST['staff_dept_full_name'];
        $shortname = $_POST['staff_dept_short_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_dept WHERE staff_dept_full_name='".$fullname."' AND staff_dept_short_name='".$shortname."' AND staff_dept_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"Department already exists!");
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_staff_dept SET staff_dept_full_name	='".$fullname."', staff_dept_short_name	='".$shortname."' WHERE staff_dept_id='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200, "Department updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update department!");
            }
        }
    }
    
    function delete_staff_department(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_staff_dept WHERE staff_dept_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_staff_dept SET status='".$status."' WHERE staff_dept_id='".$id."'");
        if($stmt2->execute()){
            $this->sendFeedback(200,"operation done successfully");
        } else {
            $this->sendFeedback(401,"operation failed!");
            }
    }
    //END STAFF DEPARTMENT
    
    //BEGIN STAFF POST
    function save_staff_post(){
        $typeId = $_POST['staff_type_id'];
    	$fullname = $_POST['staff_post_full_name'];
        $shortname = $_POST['staff_post_short_name'];
        $stmt = $this->connect->prepare("SELECT * FROM staff_post WHERE staff_type_id='".$typeId."' AND staff_post_full_name='".$fullname."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"post already exists!");
        } else {
            $stmt = $this->connect->prepare("INSERT INTO staff_post(staff_type_id, staff_post_full_name, staff_post_short_name) 
                  	VALUES('".$typeId."', '".$fullname."','".$shortname."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"post saved successfully!");
            } else {
                $this->sendFeedback(401,"Failed to save post!");
            }
        }

	}
	
    function view_staff_post(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM staff_post WHERE staff_post_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }

    function update_staff_post(){
        $id = $_POST['staff_post_id'];  
        $typeId = $_POST['staff_type_id'];  
        $fullname = $_POST['staff_post_full_name'];
        $shortname = $_POST['staff_post_short_name'];
        $stmt = $this->connect->prepare("SELECT * FROM staff_post WHERE staff_type_id='".$typeId."' AND staff_post_full_name='".$fullname."' AND staff_post_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"post already exists!");
        } else {
        $stmt = $this->connect->prepare("UPDATE staff_post SET staff_post_id='".$typeId."', staff_post_full_name='".$fullname."', staff_post_short_name	='".$shortname."' WHERE staff_post_id='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200, "post updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update post!");
            }
        }
    }
    
    function delete_staff_post(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM staff_post WHERE staff_post_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE staff_post SET status='".$status."' WHERE staff_post_id='".$id."'");
        if($stmt2->execute()){
            $this->sendFeedback(200,"operation done successfully");
        } else {
            $this->sendFeedback(401,"operation failed!");
            }
    }
    //END STAFF POST
    
    //BEGIN STAFF LEAVE TYPES
    function save_leave_type(){
    	$fullname = $_POST['leave_full_name'];
        $allowedDays = $_POST['Allowed_days'];
        if (empty($allowedDays)) {
            $days = 7;
        }
        $stmt = $this->connect->prepare("SELECT * FROM tbl_leave_type WHERE leave_full_name='".$fullname."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_leave_type(leave_full_name, Allowed_days) 
                  	VALUES('".$fullname."','".$days."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"type saved successfully!");
            } else {
                $this->sendFeedback(401,"Failed to save type!");
            }
        }

	}
	
    function view_leave_type(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_leave_type WHERE leave_type_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }

    function update_leave_type(){
        $id = $_POST['leave_type_id'];    
        $fullname = $_POST['leave_full_name'];
        $days = $_POST['Allowed_days'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_leave_type WHERE leave_full_name='".$fullname."' AND Allowed_days='".$days."' AND leave_type_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"type already exists!");
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_leave_type SET leave_full_name	='".$fullname."', Allowed_days	='".$days."' WHERE leave_type_id='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200, "type updated successfully!");
        } else {
            $this->sendFeedback(401,"Failed to update type!");
            }
        }
    }
    
    function delete_leave_type(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_leave_type WHERE leave_type_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_leave_type SET status='".$status."' WHERE leave_type_id='".$id."'");
        if($stmt2->execute()){
            $this->sendFeedback(200,"operation done successfully");
        } else {
            $this->sendFeedback(401,"operation failed!");
            }
    }
    function update_post(){
        $postId=$_POST['staff_post_id_edit'];
        $full_name=$_POST['post_full_edit'];
        $short_name=$_POST['post_short_edit'];
        $post=$_POST['staff_type_edit'];
        $stmt=$this->connect->prepare("UPDATE staff_post SET staff_type_id='".$post."',staff_post_full_name='".$full_name."',staff_post_short_name='".$short_name."' WHERE staff_post_id='".$postId."'");
        $result=$stmt->execute();
        if($result){
            $data=array("status"=>200,"message"=>"Updated Successfully !");
        }
        else{
           $data=array("status"=>500,"message"=>"Failed !");  
        }
        echo json_encode($data);
    }
    //END STAFF LEAVE TYPES
}	
		$staff=new Staff();
	    $action = $_POST['action'];
		switch($action){
		    //academic ranks
		    case 'save_rank':
		        $staff->save_rank();
		        break;
		    case 'update_rank':
		        $staff->update_rank();
		        break;
		    case 'delete_rank':
		        $staff->delete_rank();
		        break;
		    case 'view_rank':
		        $staff->view_rank();
		        break;
		        
		    //placement types
		    case 'save_placement_type':
		        $staff->save_placement_type();
		        break;
		    case 'update_placement_type':
		        $staff->update_placement_type();
		        break;
		    case 'delete_placement_type':
		        $staff->delete_placement_type();
		        break;
		    case 'view_placement_type':
		        $staff->view_placement_type();
		        break;
		        
		    //promotion types
		    case 'save_promotion_type':
		        $staff->save_promotion_type();
		        break;
		    case 'update_promotion_type':
		        $staff->update_promotion_type();
		        break;
		    case 'delete_promotion_type':
		        $staff->delete_promotion_type();
		        break;
		    case 'view_promotion_type':
		        $staff->view_promotion_type();
		        break;
		        
		    //staff types
		    case 'save_staff_type':
		        $staff->save_staff_type();
		        break;
		    case 'update_staff_type':
		        $staff->update_staff_type();
		        break;
		    case 'delete_staff_type':
		        $staff->delete_staff_type();
		        break;
		    case 'view_staff_type':
		        $staff->view_staff_type();
		        break;
		        
		    //staff department
		    case 'save_staff_department':
		        $staff->save_staff_department();
		        break;
		    case 'update_staff_department':
		        $staff->update_staff_department();
		        break;
		    case 'delete_staff_department':
		        $staff->delete_staff_department();
		        break;
		    case 'view_staff_department':
		        $staff->view_staff_department();
		        break;
		        
		   //staff post
		    case 'save_staff_post':
		        $staff->save_staff_post();
		        break;
		    case 'update_staff_post':
		        $staff->update_staff_post();
		        break;
		    case 'delete_staff_post':
		        $staff->delete_staff_post();
		        break;
		    case 'view_staff_post':
		        $staff->view_staff_post();
		        break;
		        
		    //staff leave type
		    case 'save_leave_type':
		        $staff->save_leave_type();
		        break;
		    case 'update_leave_type':
		        $staff->update_leave_type();
		        break;
		    case 'delete_leave_type':
		        $staff->delete_leave_type();
		        break;
		    case 'view_leave_type':
		        $staff->view_leave_type();
		        break;
		    case 'update_post':
		        $staff->update_post();
		        break;
		}

	?>

