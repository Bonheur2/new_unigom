<?php
include ('../../meet/con.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection=$conn;
class Fee{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_fee(){
    	$name = $_POST['name'];
    	$description = $_POST['description'];
    	$known_price = $_POST['known_price'];
    	if(isset($_POST['amount'])){
    	    $amount=$_POST['amount'];
    	    $insertQuery="INSERT INTO fee_category(name,description,known_price,amount) VALUES ('".$name."','".$description."','".$known_price."','".$amount."')";
    	}
    	else{
    	    $insertQuery="INSERT INTO fee_category(name,description,known_price) VALUES ('".$name."','".$description."','".$known_price."')";
    	}
        $stmt = $this->connect->prepare("SELECT * FROM fee_category WHERE name='".$name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "category already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare($insertQuery);
            
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "category saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $data = array("status"=>"500","message" => "Failed to save description!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }

	}
    function view_fee(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM fee_category WHERE id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
            
    function update_fee(){
        $id = $_POST['fee_id'];    
    	$name = $_POST['name'];
    	$description = $_POST['description'];
    	$known_price = $_POST['e_known_price'];
        $stmt = $this->connect->prepare("SELECT * FROM fee_category WHERE name='".$name."' AND id!='".$id."'");
        $stmt->execute();
        
    	if(isset($_POST['amount'])){
    	    $amount=$_POST['amount'];
    	    $updateQuery="UPDATE fee_category set name='".$name."', description='".$description."', known_price='".$known_price."', amount='".$amount."' WHERE id='".$id."'";
    	}
    	else{
    	    $updateQuery="UPDATE fee_category set name='".$name."', description='".$description."', known_price='".$known_price."',amount=NULL WHERE id='".$id."'";
    	}
    	
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "category already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare($updateQuery);
        if($stmt->execute()){
                $data = array("status"=>"200","message" => "category updated successfully");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to update category");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
    }

    
    	function manage_fee(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM fee_category WHERE id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $status=2;
                $select_tbl="SELECT * FROM tbl_fee_category WHERE camp_id='".$prevData['camp_id']."' AND prg_type_id='".$prevData['prg_type_id']."' AND fac_id='".$prevData['fac_id']."' AND dept_id='".$prevData['dept_id']."' AND splz_id='".$prevData['splz_id']."'
                 AND level_id='".$prevData['level_id']."'";
                $cselect_tbl=$this->connect->prepare($select_tbl);
                $cselect_tbl->execute();
                $row_cselect_tbl=$cselect_tbl->fetch(PDO::FETCH_ASSOC);
                $new_amount=$row_cselect_tbl['amount']-$prevData['amount'];
                
                $update_tbl="UPDATE tbl_fee_category SET amount='$new_amount' WHERE camp_id='".$prevData['camp_id']."' AND prg_type_id='".$prevData['prg_type_id']."' AND fac_id='".$prevData['fac_id']."' AND dept_id='".$prevData['dept_id']."' AND splz_id='".$prevData['splz_id']."'
                 AND level_id='".$prevData['level_id']."'";
                $cupdate_tbl=$this->connect->prepare($update_tbl);
                $cupdate_tbl->execute();
                
                $stmt2 = $this->connect->prepare("UPDATE fee_category SET status='".$status."' WHERE id='".$id."'");
                if($stmt2->execute()){
                    $data = array("status"=>"200","message" => "operation done successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
            } else {
                    $data = array("status"=>"500","message" => "operation failed");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
                
            }
            else{
                $status=1;
                $select_tbl="SELECT * FROM tbl_fee_category WHERE camp_id='".$prevData['camp_id']."' AND prg_type_id='".$prevData['prg_type_id']."' AND fac_id='".$prevData['fac_id']."' AND dept_id='".$prevData['dept_id']."' AND splz_id='".$prevData['splz_id']."'
                 AND level_id='".$prevData['level_id']."'";
                $cselect_tbl=$this->connect->prepare($select_tbl);
                $cselect_tbl->execute();
                $row_cselect_tbl=$cselect_tbl->fetch(PDO::FETCH_ASSOC);
                $new_amount=$row_cselect_tbl['amount']+$prevData['amount'];
                
                $update_tbl="UPDATE tbl_fee_category SET amount='$new_amount' WHERE camp_id='".$prevData['camp_id']."' AND prg_type_id='".$prevData['prg_type_id']."' AND fac_id='".$prevData['fac_id']."' AND dept_id='".$prevData['dept_id']."' AND splz_id='".$prevData['splz_id']."'
                 AND level_id='".$prevData['level_id']."'";
                $cupdate_tbl=$this->connect->prepare($update_tbl);
                $cupdate_tbl->execute();
                
                $stmt2 = $this->connect->prepare("UPDATE fee_category SET status='".$status."' WHERE id='".$id."'");
                if($stmt2->execute()){
                    $data = array("status"=>"200","message" => "operation done successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                    } else {
                    $data = array("status"=>"500","message" => "operation failed");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                   }
                
                
            }
            
            }
            function manage_fee_category(){
                $camp_id=$_POST['camp_id'];
                $prg_type_id=$_POST['prg_type_id'];
                $fac_id=$_POST['fac_id'];
                $dept_id=$_POST['dept_id'];
                $splz_id=$_POST['splz_id'];
                $level_id=$_POST['level_id'];
                $name=strtoupper($_POST['name']);
                $amount=$_POST['amount'];
                
                $select_before="SELECT * FROM fee_category WHERE camp_id='$camp_id' AND prg_type_id='$prg_type_id' AND fac_id='$fac_id' AND dept_id='$dept_id' AND splz_id='$splz_id' AND level_id='$level_id' AND name='$name' AND amount='$amount'";
                $cselect_before=$this->connect->prepare($select_before);
                $cselect_before->execute();
                $row_cselect_before=$cselect_before->fetch();
                if($row_cselect_before){
                    $data = array("status"=>"401","message" => "Fee category already exists!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
                else{
                    $select_second="SELECT * FROM tbl_fee_category WHERE camp_id='$camp_id' AND prg_type_id='$prg_type_id' AND fac_id='$fac_id' AND dept_id='$dept_id' AND splz_id='$splz_id' AND level_id='$level_id'";
                    $cselect_second=$this->connect->prepare($select_second);
                    $cselect_second->execute();
                    $row_cselect_second=$cselect_second->fetch();
                    if($row_cselect_second){
                        $new_amount=$row_cselect_second['amount']+$amount;
                        $update_second="UPDATE tbl_fee_category SET amount='$new_amount' where camp_id='$camp_id' AND prg_type_id='$prg_type_id' AND fac_id='$fac_id' AND dept_id='$dept_id' AND splz_id='$splz_id' AND level_id='$level_id' ";
                        $cupdate_second=$this->connect->prepare($update_second);
                        $cupdate_second->execute();
                        if($cupdate_second){
                            $insert_data=[
                                'camp_id'=>$camp_id,
                                'prg_type_id'=>$prg_type_id,
                                'fac_id'=>$fac_id,
                                'dept_id'=>$dept_id,
                                'splz_id'=>$splz_id,
                                'level_id'=>$level_id,
                                'name'=>$name,
                                'amount'=>$amount
                                ];
                            $insert_second="INSERT INTO fee_category(`camp_id`, `prg_type_id`, `fac_id`, `dept_id`,`splz_id`, `level_id`, `name`, `amount`) VALUES 
                            (:camp_id,:prg_type_id,:fac_id,:dept_id,:splz_id,:level_id,:name,:amount)";
                            $cinsert_second=$this->connect->prepare($insert_second);
                            $cinsert_second->execute($insert_data);
                            if($cinsert_second){
                                $data = array("status"=>"200","message" => "Fee Category Registered successfully");
                                $jsonData = json_encode($data);
                                header('Content-Type: application/json');
                                echo $jsonData;
                            }
                            else{
                                $data = array("status"=>"500","message" => "Register Fee Category Registered failed");
                                $jsonData = json_encode($data);
                                header('Content-Type: application/json');
                                echo $jsonData;
                                
                            }
                            
                        }
                    }
                    else{
                        $insert_data=[
                                'camp_id'=>$camp_id,
                                'prg_type_id'=>$prg_type_id,
                                'fac_id'=>$fac_id,
                                'dept_id'=>$dept_id,
                                'splz_id'=>$splz_id,
                                'level_id'=>$level_id,
                                'name'=>$name,
                                'amount'=>$amount
                                ];
                            $insert_first="INSERT INTO fee_category(`camp_id`, `prg_type_id`, `fac_id`, `dept_id`,`splz_id`, `level_id`, `name`, `amount`) VALUES 
                            (:camp_id,:prg_type_id,:fac_id,:dept_id,:splz_id,:level_id,:name,:amount)";
                            $cinsert_first=$this->connect->prepare($insert_first);
                            $cinsert_first->execute($insert_data);
                
                            
                            $insert_fir="INSERT INTO tbl_fee_category(`camp_id`, `prg_type_id`, `fac_id`, `dept_id`,`splz_id`, `level_id`, `name`, `amount`) VALUES 
                            (:camp_id,:prg_type_id,:fac_id,:dept_id,:splz_id,:level_id,:name,:amount)";
                            $cinsert_fir=$this->connect->prepare($insert_fir);
                            $cinsert_fir->execute($insert_data);
                            
                            if($cinsert_fir){
                                $data = array("status"=>"200","message" => "Fee Category Registered successfully");
                                $jsonData = json_encode($data);
                                header('Content-Type: application/json');
                                echo $jsonData;
                            }
                            else{
                                $data = array("status"=>"500","message" => "Register Fee Category Registered failed");
                                $jsonData = json_encode($data);
                                header('Content-Type: application/json');
                                echo $jsonData;
                                
                            }
                    }
                }
                
            }
            public function view_category_spec() {
        $id = $_POST['id'];
        
        $info = array();
        // Fetch category data
        $select_fee = "SELECT * FROM fee_category WHERE id = '".$id."'";
        $cselect_fee = $this->connect->prepare($select_fee);
        $cselect_fee->execute();
        $row_cselect_fee = $cselect_fee->fetch();
   
        //compus 
        $select_campus="SELECT * FROM tbl_campus WHERE camp_id='".$row_cselect_fee['camp_id']."'";
        $cselect_campus=$this->connect->prepare($select_campus);
        $cselect_campus->execute();
        $row_cselect_campus=$cselect_campus->fetchAll();
        //program types
        $stmt1 = $this->connect->prepare("SELECT * FROM tbl_program_type where campus_id='".$row_cselect_fee['camp_id']."'");
        $stmt1->execute();
        $prgs = $stmt1->fetchAll();
        //school
        $select_school="SELECT * FROM tbl_faculty WHERE prg_type='".$row_cselect_fee['prg_type_id']."'";
        $cselect_school=$this->connect->prepare($select_school);
        $cselect_school->execute();
        $row_cselect_school=$cselect_school->fetchAll();
        //department
        $select_depart="SELECT * FROM tbl_department WHERE fac_id='".$row_cselect_fee['fac_id']."' AND prg_type='".$row_cselect_fee['prg_type_id']."'";
        $cselect_depart=$this->connect->prepare($select_depart);
        $cselect_depart->execute();
        $row_cselect_departl=$cselect_depart->fetchAll();
        //specialization
        $select_spec="SELECT * FROM tbl_specialization WHERE prg_type='".$row_cselect_fee['prg_type_id']."' AND fac_id='".$row_cselect_fee['fac_id']."' AND dept_id='".$row_cselect_fee['dept_id']."'";
        $cselect_spec=$this->connect->prepare($select_spec);
        $cselect_spec->execute();
        $row_cselect_spec=$cselect_spec->fetchAll();
        //level
        $select_level="SELECT * FROM tbl_level WHERE prg_type='".$row_cselect_fee['prg_type_id']."'";
        $cselect_level=$this->connect->prepare($select_level);
        $cselect_level->execute();
        $row_cselect_level=$cselect_level->fetchAll();
        
        $select_tb="SELECT * FROM tbl_fee_category WHERE camp_id='".$row_cselect_fee['camp_id']."' AND prg_type_id='".$row_cselect_fee['prg_type_id']."' AND fac_id='".$row_cselect_fee['fac_id']."' AND dept_id='".$row_cselect_fee['dept_id']."' AND splz_id='".$row_cselect_fee['splz_id']."' AND level_id='".$row_cselect_fee['level_id']."'";
        $cselect_tb=$this->connect->prepare($select_tb);
        $cselect_tb->execute();
        $row_cselect_tb=$cselect_tb->fetchAll();
        
        
        
        
        array_push($info, $row_cselect_fee, $row_cselect_campus, $prgs, $row_cselect_school, $row_cselect_departl,$row_cselect_spec,$row_cselect_level,$row_cselect_tb);
        $jsonData = json_encode($info);
        header('Content-Type: application/json');
        echo $jsonData; 
    
}

// Additional methods to fetch dropdown data
private function fetchProgramTypes($prg_id) {
    $query = "SELECT * FROM tbl_program_type WHERE prg_type_id = :prg_id";
    $stmt = $this->connect->prepare($query);
    $stmt->execute(['prg_id' => $prg_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

private function fetchSpecializations($splz_id) {
    $query = "SELECT * FROM tbl_specialization WHERE splz_id = :splz_id";
    $stmt = $this->connect->prepare($query);
    $stmt->execute(['splz_id' => $splz_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

private function fetchLevels($level_id) {
    $query = "SELECT * FROM tbl_level WHERE level_id = :level_id";
    $stmt = $this->connect->prepare($query);
    $stmt->execute(['level_id' => $level_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

private function fetchCompus($campus_id) {
    $query = "SELECT * FROM tbl_campus WHERE camp_id = :campus_id";
    $stmt = $this->connect->prepare($query);
    $stmt->execute(['campus_id' => $campus_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function fee_update_spec() {
    // Get POST data
    $tbl_id=$_POST['tbl_id'];
    $id = $_POST['fee_id'];
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $camp_id = $_POST['camp_id'];
    $prg_type_id = $_POST['prg_type_id'];
    $fac_id = $_POST['fac_id'];
    $dept_id = $_POST['dept_id'];
    $splz_id = $_POST['splz_id'];
    $level_id = $_POST['level_id'];

    try {
        // Check if any changes were made
        $select_before = "SELECT * FROM fee_category 
                          WHERE camp_id = :camp_id 
                          AND prg_type_id = :prg_type_id 
                          AND fac_id = :fac_id 
                          AND dept_id = :dept_id 
                          AND splz_id = :splz_id 
                          AND level_id = :level_id 
                          AND name = :name 
                          AND amount = :amount 
                          AND id = :id";
        $stmt_before = $this->connect->prepare($select_before);
        $stmt_before->execute([
            ':camp_id' => $camp_id,
            ':prg_type_id' => $prg_type_id,
            ':fac_id' => $fac_id,
            ':dept_id' => $dept_id,
            ':splz_id' => $splz_id,
            ':level_id' => $level_id,
            ':name' => $name,
            ':amount' => $amount,
            ':id' => $id
        ]);

        if ($stmt_before->fetch()) {
            // No changes detected
            $data = ["status" => "401", "message" => "No changes made!"];
        } else {
            // Check for duplicate entry
            $select_duplicate = "SELECT * FROM fee_category 
                                 WHERE camp_id = :camp_id 
                                 AND prg_type_id = :prg_type_id 
                                 AND fac_id = :fac_id 
                                 AND dept_id = :dept_id 
                                 AND splz_id = :splz_id 
                                 AND level_id = :level_id 
                                 AND name = :name 
                                 AND id != :id";
            $stmt_duplicate = $this->connect->prepare($select_duplicate);
            $stmt_duplicate->execute([
                ':camp_id' => $camp_id,
                ':prg_type_id' => $prg_type_id,
                ':fac_id' => $fac_id,
                ':dept_id' => $dept_id,
                ':splz_id' => $splz_id,
                ':level_id' => $level_id,
                ':name' => $name,
                ':id' => $id
            ]);

            if ($stmt_duplicate->fetch()) {
                // Duplicate entry found
                $data = ["status" => "401", "message" => "Fee category already exists!"];
            } else {
                    $select_second="SELECT * FROM tbl_fee_category WHERE  id='".$tbl_id."'";
                    $cselect_second=$this->connect->prepare($select_second);
                    $cselect_second->execute();
                    $row_cselect_second=$cselect_second->fetch();
                    
                    $select_before="SELECT * FROM fee_category WHERE id='$id'";
                    $cselect_before=$this->connect->prepare($select_before);
                    $cselect_before->execute();
                    $row_cselect_before=$cselect_before->fetch();
                    
                    $exit_amount=$row_cselect_second['amount']-$row_cselect_before['amount'];
                    $new_amount=$exit_amount+$amount;
                    //
                    $update_fee1 = "UPDATE tbl_fee_category 
                               SET  amount = :amount 
                               WHERE id = :id";
                $stmt_update1 = $this->connect->prepare($update_fee1);
                $updated1 = $stmt_update1->execute([
                    ':amount' => $new_amount,
                    ':id' => $tbl_id
                ]);
                // Proceed with the update
                $update_fee = "UPDATE fee_category 
                               SET name = :name, 
                                   camp_id = :camp_id, 
                                   prg_type_id = :prg_type_id, 
                                   fac_id = :fac_id, 
                                   dept_id = :dept_id, 
                                   splz_id = :splz_id, 
                                   level_id = :level_id, 
                                   amount = :amount 
                               WHERE id = :id";
                $stmt_update = $this->connect->prepare($update_fee);
                $updated = $stmt_update->execute([
                    ':name' => $name,
                    ':camp_id' => $camp_id,
                    ':prg_type_id' => $prg_type_id,
                    ':fac_id' => $fac_id,
                    ':dept_id' => $dept_id,
                    ':splz_id' => $splz_id,
                    ':level_id' => $level_id,
                    ':amount' => $amount,
                    ':id' => $id
                ]);

                if ($updated) {
                    $data = ["status" => "200", "message" => "Fee category updated successfully!"];
                } else {
                    $data = ["status" => "500", "message" => "Update failed. Please try again."];
                }
            }
        }
    } catch (PDOException $e) {
        $data = ["status" => "500", "message" => "Database error: " . $e->getMessage()];
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($data);
}

function manage_fee_spec(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM fee_category WHERE id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $status=2;
            }
            else{
                $status=1;
            }
            $stmt2 = $this->connect->prepare("UPDATE fee_category SET status='".$status."' WHERE id='".$id."'");
            if($stmt2->execute()){
                
                $select_name="SELECT * FROM tbl_fee_category WHERE name='".$prevData['name']."'";
                $cselect_name=$this->connect->prepare($select_name);
                $cselect_name->execute();
                $row_cselect_name=$cselect_name->fetch(PDO::FETCH_ASSOC);
                
                $stmt3=$this->connect->prepare("UPDATE tbl_fee_category SET status='".$status."' WHERE id='".$row_cselect_name['id']."'");
                if($stmt3->execute()){
                    $data = array("status"=>"200","message" => "operation done successfully");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
                else
                {
                    $data = array("status"=>"500","message" => "operation failed");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData; 
                }
                
                     
            }
}
function download_data(){
    $camp_id=$_POST['camp_id'];
    $prg_type_id=$_POST['prg_type_id'];
    $fac_id=$_POST['fac_id'];
    $dept_id=$_POST['dept_id'];
    $splz_id=$_POST['splz_id'];
    $level_id=$_POST['level_id'];
    $name=$_POST['name'];
    $amount=$_POST['amount'];
    
    // $campus_id = $_POST['campus_id'];
    // $program_type_id = $_POST['program_type_id'];
    // $program_id = $_POST['program_id'];
    // $level_id = $_POST['level_id'];
    // $amount = $_POST['amount'];
    // $name = $_POST['name'];
    
    // Return the data as a JSON object
    echo json_encode([
        'status' => 200,
        'camp_id' => $camp_id,
        'prg_type_id' => $prg_type_id,
        'fac_id' => $fac_id,
        'dept_id' => $dept_id,
        'splz_id'=>$splz_id,
        'level_id' => $level_id,
        'amount' => $amount,
        'name' => $name
    ]);
    exit;
}
function upload_csv(){
    $csvData = json_decode($_POST['csv_data'], true);
    $batchSize = 100; // Number of rows to insert in each batch
    $rows = [];
    $placeholders = [];
    $insertCount = 0;

    try {
        $this->connect->beginTransaction();

        foreach ($csvData as $row) {
            $data = str_getcsv($row); // Parse CSV row
            
            // Check if row has at least 6 columns (based on required fields: name, campus_id, prg_id, splz_id, level_id, amount)
            if (count($data) < 8) {
                continue; // Skip rows with missing columns
            }

            // Prepare data for insertion
            $camp_id=$data[0];
            $prg_type_id=$data[1];
            $fac_id=$data[2];
            $dept_id=$data[3];
            $splz_id=$data[4];
            $level_id=$data[5];
            $name=$data[6];
            $amount=$data[7];
            

            // Add data to the batch
            $rows = array_merge($rows, [$camp_id, $prg_type_id, $fac_id, $dept_id,$splz_id, $level_id, $name, $amount]);
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?)";
            $insertCount++;

            // Insert batch if it reaches the defined size
            if ($insertCount % $batchSize === 0) {
                $stmt = $this->connect->prepare("INSERT INTO fee_category (camp_id, prg_type_id, fac_id, dept_id,splz_id, level_id, name, amount) VALUES " . implode(", ", $placeholders));
                $stmt->execute($rows);

                // Reset for the next batch
                $rows = [];
                $placeholders = [];
            }
        }

        // Insert any remaining rows that didn’t reach the batch size
        if (!empty($rows)) {
            $stmt = $this->connect->prepare("INSERT INTO fee_category (camp_id, prg_type_id, fac_id, dept_id,splz_id, level_id, name, amount) VALUES " . implode(", ", $placeholders));
            $stmt->execute($rows);
        }

        $this->connect->commit();
        echo json_encode(['status' => 200, 'message' => 'Data inserted successfully.']);
    } catch (Exception $e) {
        $this->connect->rollBack();
        echo json_encode(['status' => 500, 'message' => 'Error inserting data: ' . $e->getMessage()]);
    }
    exit;
}
function manage_fee_category_specific(){
                $camp_id=$_POST['camp_id'];
                $prg_type_id=$_POST['prg_type_id'];
                $fac_id=$_POST['fac_id'];
                $dept_id=$_POST['dept_id'];
                $splz_id=$_POST['splz_id'];
                $level_id=$_POST['level_id'];
                $name=strtoupper($_POST['name']);
                $amount=$_POST['amount'];
                $specific_status=1;
                
                $select_before="SELECT * FROM fee_category WHERE camp_id='$camp_id' AND prg_type_id='$prg_type_id' AND fac_id='$fac_id' AND dept_id='$dept_id' AND splz_id='$splz_id' 
                AND level_id='$level_id' AND name='$name' AND amount='$amount' ";
                $cselect_before=$this->connect->prepare($select_before);
                $cselect_before->execute();
                $row_cselect_before=$cselect_before->fetch();
                if($row_cselect_before){
                    $data = array("status"=>"401","message" => "Fee category already exists!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
                else{
                    $insert_data=[
                                'camp_id'=>$camp_id,
                                'prg_type_id'=>$prg_type_id,
                                'fac_id'=>$fac_id,
                                'dept_id'=>$dept_id,
                                'splz_id'=>$splz_id,
                                'level_id'=>$level_id,
                                'name'=>$name,
                                'amount'=>$amount,
                                'specific_status'=>$specific_status
                                ];
                            $insert_first="INSERT INTO fee_category(`camp_id`, `prg_type_id`, `fac_id`, `dept_id`,`splz_id`, `level_id`, `name`, `amount`,`specific_status`) VALUES 
                            (:camp_id,:prg_type_id,:fac_id,:dept_id,:splz_id,:level_id,:name,:amount,:specific_status)";
                            $cinsert_first=$this->connect->prepare($insert_first);
                            $cinsert_first->execute($insert_data);
                
                            
                            $insert_fir="INSERT INTO tbl_fee_category(`camp_id`, `prg_type_id`, `fac_id`, `dept_id`,`splz_id`, `level_id`, `name`, `amount`,`specific_status`) VALUES 
                            (:camp_id,:prg_type_id,:fac_id,:dept_id,:splz_id,:level_id,:name,:amount,:specific_status)";
                            $cinsert_fir=$this->connect->prepare($insert_fir);
                            $cinsert_fir->execute($insert_data);
                            
                            if($cinsert_fir){
                                $data = array("status"=>"200","message" => "Fee Category Registered successfully");
                                $jsonData = json_encode($data);
                                header('Content-Type: application/json');
                                echo $jsonData;
                            }
                            else{
                                $data = array("status"=>"500","message" => "Register Fee Category Registered failed");
                                $jsonData = json_encode($data);
                                header('Content-Type: application/json');
                                echo $jsonData;
                                
                            }
                }
}
function update_fee_spe(){
    
    $camp_id=$_POST['camp_id'];
    $prg_type_id=$_POST['prg_type_id'];
    $fac_id=$_POST['fac_id'];
    $dept_id=$_POST['dept_id'];
    $splz_id=$_POST['splz_id'];
    $level_id=$_POST['level_id'];
    $name=$_POST['name'];
    $amount=$_POST['amount'];
    $id=$_POST['fee_id'];
    $tbl_id=$_POST['tbl_id'];
    
    $select_data=[
        'camp_id'=>$camp_id,
        'prg_type_id'=>$prg_type_id,
        'fac_id'=>$fac_id,
        'dept_id'=>$dept_id,
        'splz_id'=>$splz_id,
        'level_id'=>$level_id,
        'name'=>$name,
        'amount'=>$amount,
        'id'=>$id
        ];
    
    $select_before="SELECT * FROM fee_category WHERE camp_id=:camp_id AND prg_type_id=:prg_type_id AND fac_id=:fac_id AND dept_id=:dept_id AND splz_id=:splz_id AND level_id=:level_id AND name=:name
    AND amount=:amount AND specific_status= 1 AND id=:id";
    $cselect_before=$this->connect->prepare($select_before);
    $cselect_before->execute($select_data);
    $row_cselect_before=$cselect_before->fetch(PDO::FETCH_ASSOC);
    if($row_cselect_before){
        
        echo json_encode(["status" => "401", "message" => "Fee category already exists!"]);
        exit;
    }
    else{
    $update_data=[
        'camp_id'=>$camp_id,
        'prg_type_id'=>$prg_type_id,
        'fac_id'=>$fac_id,
        'dept_id'=>$dept_id,
        'splz_id'=>$splz_id,
        'level_id'=>$level_id,
        'name'=>$name,
        'amount'=>$amount,
        'id'=>$id
        ];
        $update_data1=[
        'camp_id'=>$camp_id,
        'prg_type_id'=>$prg_type_id,
        'fac_id'=>$fac_id,
        'dept_id'=>$dept_id,
        'splz_id'=>$splz_id,
        'level_id'=>$level_id,
        'name'=>$name,
        'amount'=>$amount,
        'id'=>$tbl_id
        ];
        $update_fee="UPDATE fee_category SET camp_id=:camp_id,prg_type_id=:prg_type_id,fac_id=:fac_id,dept_id=:dept_id,splz_id=:splz_id,level_id=:level_id,name=:name,amount=:amount WHERE specific_status= 1
        AND id=:id";
        $cupdate_fee=$this->connect->prepare($update_fee);
        $cupdate_fee->execute($update_data);
        if($cupdate_fee){
        $update_tbl_fee="UPDATE tbl_fee_category SET camp_id=:camp_id,prg_type_id=:prg_type_id,fac_id=:fac_id,dept_id=:dept_id,splz_id=:splz_id,level_id=:level_id,name=:name,amount=:amount WHERE specific_status= 1
        AND id=:id";
        $cupdate_tbl_fee=$this->connect->prepare($update_tbl_fee);
        $cupdate_tbl_fee->execute($update_data1);
        if($cupdate_tbl_fee){
            echo json_encode(["status" => "200", "message" => "Fee category updated successfully!"]); 
        }
        else
        {
            echo json_encode(["status" => "500", "message" => "Update failed. Please try again."]);
        }
        }
        
        
        
        }
        
    
    
}

        
        
}	
		$fee=new fee();
	    $action = $_POST['action'];
		switch($action){
		    case 'register':
		        $fee->save_fee();
		        break;
		    case 'update':
		        $fee->update_fee();
		        break;
		    case 'view':
		        $fee->view_fee();
		        break;
		    case 'delete':
		        $fee->manage_fee();
		        break;
		    case 'register_fees':
		        $fee->manage_fee_category();
		        break;
		    case 'register_fees_spec':
		        $fee->manage_fee_category_specific();
		        break;
		    case 'view_category':
		        $fee->view_category_spec();
		        break;
		    case 'update_fee_spec':
		        $fee->fee_update_spec();
		        break;
		    case 'update_fee_spe':
		        $fee->update_fee_spe();
		        break;      
		    case 'delete_spec':
		        $fee->manage_fee_spec();
		        break;
		    case 'download_form_data':
		        $fee->download_data();
		        break; 
		    case 'upload_csv_data':
		        $fee->upload_csv();
		        break;       
		}

	?>

