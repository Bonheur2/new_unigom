<?php
    include('../../meet/con.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    function isWeekend($date) {
        return (date('N', strtotime($date)) >= 6);
    }
    
    function isNotWeekend($date) {
        return (date('N', strtotime($date)) < 6);
    } 
    
    function hasLectureConflict($conn, $staff_id, $date, $hour_id, $acadCycleId) {
        $stmt = $conn->prepare("SELECT * FROM tbl_t_schedule t JOIN tbl_module_leader l ON t.staff_id = l.staff_id WHERE l.staff_id = ? AND t.s_date = ? AND t.hour_id = ? AND t.acad_cycle_id = ?");
        $stmt->execute([$staff_id, $date, $hour_id, $acadCycleId]);
    
        return $stmt->rowCount() > 0;
    }
    
    function getAvailableRooms($conn, $date, $hour_id, $class_size) {
        $stmt = $conn->prepare("
            SELECT room_id, room_size 
            FROM tbl_block_rooms 
            WHERE room_size >= ? 
            AND room_id NOT IN(SELECT room_id FROM tbl_t_schedule WHERE s_date = ? AND hour_id = ?)
            ORDER BY room_size ASC
        "); 
        $stmt->execute([$class_size, $date, $hour_id]);
        return $stmt->fetchAll();
    }
    
    
    function getLectures($conn, $modules) {
        $modules = implode(',', $modules);
        $sql = $conn->prepare("
            SELECT 
                tm.module_id,
                ml.staff_id
            FROM tbl_modules tm 
            LEFT JOIN tbl_module_leader ml ON tm.module_id = ml.module_id
            WHERE tm.module_id IN ($modules)
        ");
        $sql->execute();
        return $sql->fetchAll();
    }
    
    function getSessionTimes($conn, $prg_mode) {
        $stmt = $conn->prepare("SELECT * FROM tbl_t_hours WHERE prg_mode_id = ?");
        $stmt->execute([$prg_mode]);
        return $stmt->fetchAll();
    }
    
    function getScheduledModules($conn, $currentDate, $splz_id, $level_id, $prg_mode, $moduleId, $hourId, $acadCycleId){
        $stmt = $conn->prepare("SELECT module_id FROM tbl_t_schedule WHERE s_date = ? AND splz_id = ? AND level_id = ? AND prg_mode = ? AND (module_id = ? OR hour_id = ?) AND acad_cycle_id = ?");
        $stmt->execute([$currentDate, $splz_id, $level_id, $prg_mode, $moduleId, $hourId, $acadCycleId]);
        return $stmt->rowCount() > 0;
    }
    
    function getModuleScheduleCount($conn, $splz_id, $level_id, $prg_mode, $term_id, $acadCycleId) {
        $stmt = $conn->prepare("SELECT module_id, COUNT(*) as count FROM tbl_t_schedule WHERE splz_id = ? AND level_id = ? AND prg_mode = ? AND term_id = ? AND acad_cycle_id = ? GROUP BY module_id");
        $stmt->execute([$splz_id, $level_id, $prg_mode, $term_id, $acadCycleId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $counts = [];
        foreach ($results as $result) {
            $counts[$result['module_id']] = $result['count'];
        }
        return $counts;
    }
    
    function getClassSize($conn, $splzId, $levelId, $prg_mode, $moduleId){
        $stmt = $conn->prepare("SELECT DISTINCT(mm.reg_no) FROM tbl_markby_module mm INNER JOIN tbl_register_program_ug ug ON mm.reg_no = ug.reg_no WHERE ug.splz_id = ? AND ug.level_id = ? AND ug.prg_mode_id = ? AND mm.module_id = ? AND mm.status = 1 AND mm.enrolled = 1 AND ug.reg_active = 1");
        $stmt->execute([$splzId, $levelId, $prg_mode, $moduleId]);
        return $stmt->rowCount();
    }
    
    function getAcadCycle($conn){
        $stmt = $conn->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status = 1 ORDER BY acad_cycle_id DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch()['acad_cycle_id'];
    }
    
    // Function to add weeks to a date
    function addWeeks($date, $weeks) {
        $nextMonday = strtotime('next Monday', strtotime($date));
        $resultDate = strtotime("+$weeks weeks", $nextMonday);
        return date('Y-m-d', $resultDate);
    }

    $prg_mode = $_POST['prg_mode'];
    $splz_id = $_POST['splz_id'];
    $level_id = $_POST['level_id'];
    $term_id = $_POST['term_id'];
    // $intakeId = $_POST['intake_id'];
    $acadCycleId = getAcadCycle($conn);
    
    $modcount = $_POST['modules'];
    $include_exam = $_POST['include_exam'];
    $weeks = $_POST['weeks'];
    
    $startDate = $_POST['start_date'];
    $currentDate = $startDate;
    $modules = array();
    foreach($_POST['selectedModules'] as $module){
        array_push($modules, $module);
    }
    
    asort($modules);
    $modules = array_values($modules);
    
    $conn->beginTransaction();
    try {
        $lectures = getLectures($conn, $modules);
        $sessionTimes = getSessionTimes($conn, $prg_mode);
    
        $moduleIndex = 0;
        $msettled = 0;
        $inserted = 0;
        $totalModules = count($lectures);

        // Loop to generate timetable

        $moduleScheduleCounts = getModuleScheduleCount($conn, $splz_id, $level_id, $prg_mode, $term_id, $acadCycleId);
        
        if($prg_mode == 1){
            $targetScheduleCount = 5 * $weeks;
        }else if($prg_mode == 2){
            $targetScheduleCount = 7 * $weeks/$totalModules;
        }else{
            $targetScheduleCount = 2 * $weeks/$totalModules;
        }
        
        while ($moduleIndex < $totalModules) {
            for ($week = 1; $week <= $weeks; $week++) {
                for ($day = 0; $day < 7; $day++) {
        
                    $currentDate = date('Y-m-d', strtotime("$startDate + " . (($week - 1) * 7 + $day) . " days"));
                    
                    if($prg_mode == 1){
                        if (isWeekend($currentDate)) {
                            continue;
                        }
                    } if($prg_mode == 3){
                        if (isNotWeekend($currentDate)) {
                            continue;
                        }
                    }
        
                    $timestamp = strtotime($currentDate);
                    $dayOfWeek = date('l', $timestamp);
        
                    // Shuffle the session times for randomness
                    shuffle($sessionTimes);
        
                    foreach ($sessionTimes as $sessionTime) {
                        $lecture = $lectures[$moduleIndex];
        
                        $hourId = $sessionTime['hour_id'];
                        $lectureId = $lecture['staff_id'];
                        $moduleId = $lecture['module_id'];
        
                        // Check if the module has already reached the target schedule count
                        if (isset($moduleScheduleCounts[$moduleId]) && $moduleScheduleCounts[$moduleId] >= $targetScheduleCount) {
                            continue;
                        }
        
                        $moduleScheduled = getScheduledModules($conn, $currentDate, $splz_id, $level_id, $prg_mode, $moduleId, $hourId, $acadCycleId);
        
                        $class_size = getClassSize($conn, $splz_id, $level_id, $prg_mode, $moduleId);
                        // Get available rooms based on class size
                        $availableRooms = getAvailableRooms($conn, $currentDate, $hourId, $class_size);
                        $roomId = $availableRooms[0]['room_id'];
        
                        // Check for conflicts
                        if (!hasLectureConflict($conn, $lectureId, $currentDate, $hourId, $acadCycleId) && !$moduleScheduled) {
                            $inserted++;
                            $schedule_data=[
                                'splz_id'=>$splz_id,
                                'level_id'=>$level_id,
                                'term_id'=>$term_id,
                                'acad_cycle_id'=>$acadCycleId,
                                'module_id'=>$moduleId,
                                'staff_id'=>$lectureId,
                                'prg_mode'=>$prg_mode,
                                'day'=>$dayOfWeek,
                                's_date'=>$currentDate,
                                'hour_id'=>$hourId,
                                'room_id'=>$roomId,
                                'stype'=>1
                                ];
                            
                            $stmt = $conn->prepare("INSERT INTO tbl_t_schedule (splz_id,level_id,term_id,acad_cycle_id,module_id,staff_id,prg_mode,day,s_date,hour_id,room_id,stype) VALUES 
                            (:splz_id,:level_id,:term_id,:acad_cycle_id,:module_id,:staff_id,:prg_mode,:day,:s_date,:hour_id,:room_id,:stype)");
                            $stmt->execute($schedule_data);
        
                            // Increment the in-memory count
                            if (!isset($moduleScheduleCounts[$moduleId])) {
                                $moduleScheduleCounts[$moduleId] = 0;
                            }
                            $moduleScheduleCounts[$moduleId]++;
                        }
                    }
                }
            }
        
            $moduleIndex++;
            $msettled++;
        
            if(strtoupper($include_exam) == 'YES'){
                // add exam week
                if($msettled == $modcount && $inserted>0){
                    $msettled = 0;
                    
                    $timestamp = strtotime($currentDate);
                    $timestamp += 86400;
                    $nextDate = date('Y-m-d', $timestamp);

                    $stmt = $conn->prepare("INSERT INTO tbl_t_schedule (splz_id, level_id, term_id, prg_mode, s_date,  acad_cycle_id, hour_id, stype) VALUES (?, ?, ?, ?, ?, ?,  1, 2)");
                    $stmt->execute([$splz_id, $level_id, $term_id, $prg_mode, $nextDate,  $acadCycleId]);
                    $startDate = addWeeks($currentDate, 1); // Move to the next teaching cycle
                }
            }
        }

        $conn->commit();
        echo json_encode(['status' => 200, 'message'=> 'Time table created successfully!']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['status' => 400, 'message'=> 'Something went wrong, retry!']);
    }
?>
