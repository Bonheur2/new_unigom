<?php
/**
 * 
 *Authoe @macintosh 
 */
include("../../meet/con.php");

$blockName =$_REQUEST['block'];
$blockShortName =$_REQUEST['bloc-short'];
$prgMode =$_REQUEST['level'];
$prgType =$_REQUEST['categ'];
$faculty =$_REQUEST['faculty'];
try{
    
    if(isset($_REQUEST['move-from']) && isset($_REQUEST['move-to']) &&isset($_REQUEST['edit-day-to']) &&isset($_REQUEST['edit-day'])){
        $record =$conn->prepare("UPDATE tbl_t_schedule set hour_id ='".$_REQUEST['move-to']."', learning_day_id='".$_REQUEST['edit-day-to']."' where sub_class_id ='".$_REQUEST['edit-spec']."' 
        and level_id='".$_REQUEST['edit-level']."' and term_id='".$_REQUEST['edit-term']."' and learning_day_id ='".$_REQUEST['edit-day']."' and hour_id='".$_REQUEST['move-from']."'");
        $record->execute();
        if($record){
            echo 1;
        }
    }
}
catch(Exception $exc){
    echo "Exception caught: " . $exc->getMessage() . "<br>";
    echo "Stack trace: \n" . $exc->getTraceAsString() . "<br>";
}
?>