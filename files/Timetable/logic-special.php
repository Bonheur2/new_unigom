<?php
    include('../../meet/con.php');
    
    function getAvailableRooms($conn, $class_size) {
        $stmt = $conn->prepare("
            SELECT room_id, room_size 
            FROM tbl_block_rooms 
            WHERE room_size >= ? ORDER BY room_size ASC
        "); 
        $stmt->execute([$class_size]);
        return $stmt->fetchAll();
    }
    
    function checkOccurence($conn, $start_date, $splz_id, $level_id, $prg_mode, $moduleId){
        $stmt = $conn->prepare("SELECT module_id FROM tbl_special_schedule WHERE start_date = ? AND splz_id = ? AND level_id = ? AND prg_mode = ? AND module_id = ?");
        $stmt->execute([$start_date, $splz_id, $level_id, $prg_mode, $moduleId, $hourId]);
        return $stmt->rowCount() > 0;
    }
    
    function getClassSize($conn, $splzId, $levelId, $prg_mode, $moduleId){
        $stmt = $conn->prepare("SELECT DISTINCT(mm.reg_no) 
                                        FROM tbl_markby_module mm 
                                            INNER JOIN tbl_register_program_ug ug ON mm.reg_no = ug.reg_no 
                                            WHERE ug.splz_id = ? AND 
                                            ug.level_id = ? AND 
                                            ug.prg_mode_id = ? AND 
                                            mm.module_id = ? AND 
                                            mm.status = 1 AND 
                                            mm.enrolled = 1 AND 
                                            (mm.marks IS NULL OR mm.marks <50) AND
                                            ug.reg_active = 1");
        $stmt->execute([$splzId, $levelId, $prg_mode, $moduleId]);
        return $stmt->rowCount();
    }
    
    function getAcadCycle($conn){
        $stmt = $conn->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status = 1 ORDER BY acad_cycle_id DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch()['acad_cycle_id'];
    }

    $prg_mode = $_POST['prg_mode'];
    $splz_id = $_POST['splz_id'];
    $level_id = $_POST['level_id'];
    $term_id = $_POST['term_id'];
    $intakeId = $_POST['intake_id'];
    $acadCycleId = getAcadCycle($conn);
    
    $conn->beginTransaction();
    
    $s_code = rand(1000000,9999999);
    try {
        foreach($_POST['selectedModules'] as $module){
            $start_date = $_POST['start_date_'.$module];
            $end_date = $_POST['end_date_'.$module];
            $comment = $_POST['comment_'.$module];
            $stype = $_POST['stype_'.$module];
            
            $class_size = getClassSize($conn, $splz_id, $level_id, $prg_mode, $module);
            $availableRooms = getAvailableRooms($conn, $class_size);
            $roomId = $availableRooms[0]['room_id'];

            $stmt = $conn->prepare("INSERT INTO tbl_special_schedule (s_code, splz_id, level_id, module_id, intake_id, acad_cycle_id, prg_mode, start_date, end_date, room_id, comment, stype) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
            $stmt->execute([$s_code, $splz_id, $level_id, $module, $intakeId, $acadCycleId, $prg_mode, $start_date, $end_date, $roomId, $comment]);

        }

        $conn->commit();
        
        $comment_1  = $_POST['comment_1'];
        $comment_2  = $_POST['comment_2'];
        $comment_3  = $_POST['comment_3'];
        
        $stmt2 = $conn->prepare("INSERT INTO tbl_schedule_comments (s_code, comment_1, comment_2, comment_3) VALUES (?, ?, ?, ?)");
        $stmt2->execute([$s_code, $comment_1, $comment_2, $comment_3]);
        
        echo json_encode(['status' => 200, 'message'=> 'Time table created successfully!']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['status' => 400, 'message'=> 'Something went wrong, retry!']);
    }
?>
