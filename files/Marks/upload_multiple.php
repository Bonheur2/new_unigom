<?php
include ('../meet/con.php');
$acad_cycle_id=$_POST['u_acad_cycle_id'];
$splz_id=$_POST['u_splz'];
$module=$_POST['u_module'];
$file = $_FILES['csv_file']['tmp_name'];
$inserted=0;
$changes=0;
$valuesArray = explode(',', $module);

//get module data
$ij = 3;

foreach($valuesArray as $mod){
    $getModuleData=$conn->prepare("SELECT cat, exam FROM tbl_modules WHERE module_id='".$mod."'");
    $getModuleData->execute();
	$moduleData=$getModuleData->fetch();
	$per_marks_cat=$moduleData['cat'];
	$per_marks_exam=$moduleData['exam'];

    //handle file data
    $handle = fopen($file, 'r');
    $pos=0;
    
    $rows = [];

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        
	    if($pos<8){
            $pos++;
            continue;
	    }
	    else{
            if((int)$data[$ij]>$per_marks_cat || (int)$data[$ijc]>$per_marks_exam){
                $resp = array("status"=>"401","message" => "Some marks $data[$ij], $data[$ijc] are above maximum!");
                $jsonData = json_encode($resp);
                header('Content-Type: application/json');
                echo $jsonData;
                return false;
            } 
	    }
	}
	$ij += 2;
	fclose($handle);
}
try{
    $handle = fopen($file, 'r');
    $pos=0;
    
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if($pos<8){
            $pos++;
            continue;
        }
        else{
          $rows[] =$data;  
        }
    }

    $modMarksCounter=0;
    
    $subArrays =array();
    foreach($rows as $student){
        $reg_no=$student[1];
        
        $dest = 2*(count($valuesArray))+1;
        
        for($i = 3; $i <= $dest ; $i += 2) {
            $subArray = array_slice($student, $i, 2);
            array_push($subArrays, $subArray);
        }

        for($counter =0; $counter<count($valuesArray); $counter += 1){
            $onlyOne =$subArrays[$counter];
            
            $internalMod=$conn->prepare("SELECT cat, exam FROM tbl_modules WHERE module_id='".$valuesArray[$counter]."'");
            $internalMod->execute();
        	$internalMod=$internalMod->fetch();
            
            $cat = $onlyOne[0];
            $exam = $onlyOne[1];
            
            $Xcat = floatval($cat);
            $Xexam = floatval($exam);
            
            
            if($cat == ''){
                $Xcat = `null`;
                $cat = 0;
            }
            if($exam == ''){
                $Xexam = `null`;
                $exam = 0;
            }
            
            $marks = ($cat+$exam)*100/($internalMod['cat']+$internalMod['exam']);
            
            if($Xcat == `null` && $Xexam == `null`){
                $marks = `null`;
            }

            $update = $conn->prepare("UPDATE tbl_markby_module SET cat = ?, cat_status='yes', final_exam = ? , final_exam_status='yes', marks = ?, marks_status='yes' WHERE module_id='".$valuesArray[$counter]."' AND splz_id='".$splz_id."' AND reg_no='".$reg_no."'");

            if($update->execute([$Xcat, $Xexam, $marks])){
                $changes++;
            }
        }
        $subArrays =array();
    }
    fclose($handle);
}catch(PDOException $e){
    $data = array("status"=>"500","message" => "Something went wrong!". $e);
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;   
}    	   // }
        

if($changes>0){
    $data = array("status"=>"200","message" => "data saved successfully!");
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData; 
}
else{
    $data = array("status"=>"401","message" => "Failed to save marks!");
    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData; 
}
	
?>