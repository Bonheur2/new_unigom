   <style>
        .svg-container{
          position: absolute;
          top: 0;
          right: 0;
        }
        
        /* Add this CSS for the spinner */
        #spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
        
        /* Update this CSS to hide the spinner by default */
        .spinner-border {
            display: none;
        }
        
    </style>
    <?php include ('../../meet/con.php');?>
    <?php
    
    //requesting subclass and level
    
        $classId =$_REQUEST['classId'];
        $levelId =$_REQUEST['levelId'];
        // $intake =$_REQUEST['intake'];
        $levelTermId =$_REQUEST['term_id'];
        $prgType =$_REQUEST['type'];
        $prgMode =$_REQUEST['mode'];
        
        //other variables
        
        $allClassModules =array();
        $allDays =array();
        $allHour  =array();
        $allRooms =array();
        $usedRooms =array();
        $allClassModHours =array();
        $classDays ;
        $roomDays;
        $moduleLenghth =0;
        $classSize =0;
        $theOverall ="";
        
        $subClassId =$conn->prepare("select *from tbl_specialization where splz_id ='".$classId."'");
        $subClassId->execute();
        $theOverall =$subClassId->fetch();
        // while(){
            $classModule =$conn->prepare("select *from tbl_modules where splz_id ='".$theOverall['splz_id']."' and level_id ='".$levelId."'
            and term_id ='".$levelTermId."' order by module_credits desc");
            $classModule->execute();
            while($theClassModules =$classModule->fetch()){
                array_push($allClassModules, $theClassModules['mod_id']);
                array_push($allClassModHours, $theClassModules['module_credits']);
            }
        // }
        
        // echo "Module Selected is ".$module;
        function getModuleHoursWeek($moduleName){
            global $conn;
            $moduleHours =$conn->prepare("select *from tbl_modules where module_name ='".$moduleName."'");
            $moduleHours->execute();
            while($theModule = $moduleHours->fetch()){
                $moduleLenghth =   $theModule['mod_week_hours'];
            }
            return $moduleLenghth;
        }
        
        function getClassSize($classId){
            global $conn;
            $subClassId =$conn->prepare("select *from tbl_specialization where splz_full_name ='".$classId."'");
            $subClassId->execute();
                while($theClass = $subClassId->fetch()){
                    $classSize =   $theClass['splz_id'];
                }
                return $classSize;
            } 
            
            //a function to insert a module into the schedule table

        function insertModule($type, $mode, $hourId, $subId, $levelId, $term, $moduleCode, $modHours, $room, $day, $status){
            global $conn;
            
            // insert
            
            $assignModule =$conn->prepare("insert into tbl_t_schedule(program_type, program_mode, hour_id, sub_class_id, level_id, term_id, class_module_id, class_mod_week_hours, room_id, learning_day_id, schedule_status)
            values('".$type."', '".$mode."','".$hourId."', '".$subId."', '".$levelId."', '".$term."', '".$moduleCode."', '".$modHours."', '".$room."', '".$day."', '".$status."')");
            $assignModule->execute();
        }

        //required functions
        //return hour id
        
        function returnHourId($hour){
            global $conn;
            $hourId =$conn->prepare("select *from tbl_t_hours where hour_label ='".$hour."'");
            $hourId->execute();
            $theId =$hourId->fetch();
            
            return $theId['hour_id'];
        }
        
        // return subclass id
        
        function returnSubClassId($subClass){
            global $conn;
            $subId =$conn->prepare("select *from tbl_specialization where splz_id ='".$subClass."'");
            $subId->execute();
            $theSub=$subId->fetch();
            
            //return sub class id
            return $theSub['splz_id'];
        }
        
        // return module id
        function returnModId($module, $classId, $levelId){
            global $conn;
            $modCode =$conn->prepare("select *from tbl_modules where mod_id ='".$module."' and splz_id ='".$classId."' and level_id='".$levelId."'");
            $modCode->execute();
            $theMod =$modCode->fetch();
            
            //return module id
            return $theMod['mod_id'];
        }
        
        //module hours
        function returnModuleHours($module, $classId, $levelId){
            global $conn;
            $modHours =$conn->prepare("select *from tbl_modules where mod_id ='".$module."' and splz_id ='".$classId."' and level_id='".$levelId."'");
            $modHours->execute();
            $theModHour =$modHours->fetch();
            
            //return hours for a module
            return $theModHour['module_credits'];
        }
        
        //return each mpodule hours
        function countModHours($moduleId){
            global $conn;
            $mod =$conn->prepare("select *from tbl_modules where mod_id ='".$moduleId."'");
            $mod->execute();
            $theMod =$mod->fetch();
            
            //return hours for a module
            return $theMod['module_credits'];
        }
        
        //return room id
        
        // 9: return room id
        function returnRoomId($room, $classSize){
            global $conn;
            
                $roomId =$conn->prepare("select *from tbl_block_rooms where room_code ='".$room."' and room_size >='".$classSize."'");
                $roomId->execute();
                
                if(($roomId->rowCount())>0){
                    
                    $theRoom =$roomId->fetch();
                    //return room id
                    return $theRoom['room_id'];
                }
                else{
                    return 0;
                }
        }
        
        //return learning day id
        
        function returnDayId($day){
            global $conn;
            $dayId =$conn->prepare("select *from tbl_t_days where day_name ='".$day."'");
            $dayId->execute();
            $theDay =$dayId->fetch();
            
            return $theDay['day_id'];
        }
        
        //available hours and rooms
        function checkAvailability($hour, $module, $day){
            global $conn;
            $availabilityStatus;
            $dayTaken =$conn->prepare("select *from tbl_t_schedule where learning_day_id ='".$day."' and hour_id ='".$hour."'");
            $dayTaken->execute();
            while($theDay  =$dayTaken->fetch()){
                $newDay =$theDay['learning_day_id'];
                $newhour =$theDay['hour_id'];
                $newModule =$theDay['class_module_id'];
                $newRoom =$theDay['room_id'];
                if(!(empty($newDay)) && !(empty($newhour)) && !(empty($newModule))){
                    
                    
                    $lastClass =$conn->prepare("select max(sub_class_id) as class from tbl_t_schedule limit 1");
                    $lastClass->execute();
                    $theClass =$lastClass->fetch();
                    
                    $roomId =$conn->prepare("select * from tbl_t_schedule where sub_class_id ='".$theClass['class']."' limit 1");
                    $roomId->execute();
                    $theRoom =$roomId->fetch(); 
                    
                    $availabilityStatus =$theRoom['room_id'];
                }
            }
            return $availabilityStatus;
        }
        
        
        //on class change
        function onClassChange($classId){
            global $conn;
            $status;
            $class =$conn->prepare("select *from tbl_t_schedule where sub_class_id ='".$classId."'");
            $class->execute();
            if($class->rowCount()==0){
                $status =1;
            }
        }
        //module frequency
        function checkModuleFrequency($classId, $moduleId){
            $frequency =0;
            global $conn;
            $modFreq =$conn->prepare("select count(class_module_id) as module from tbl_t_schedule where sub_class_id ='".$classId."' and class_module_id ='".$moduleId."'");
            $modFreq->execute();
            $theFreq =$modFreq->fetch();
            
            //return module frequency
            return $frequency =$theFreq['module'];
        }
        
        /**
         * a function to reassign
         * an elem if it is assigned 
         * not equally with its credits
         * 
         * 
         */
         
         function findNextModule($class, $modules, $index, $levelId, $intake){
            global $conn;
            $index=count($modules);
            $modFrequency =checkModuleFrequency($class,returnModId($modules[$index], $class, $levelId));
            $numHours =returnModuleHours($modules[$index],$class, $levelId);
            if($index ==0 || $modFrequency < $numHours){
                return $moduleToAssign =returnModId($modules[$index], $class, $levelId);
            }
            findNextModule($class, $modules, $index-1, $levelId, $intake); 
            
         }
         
        //  unset all modules whose occurences
        // exceeds
         function unsetAModule($classId, $levelId, $termId, &$classModulesCollection){
            global $conn;
            for($index =0; $index<count($classModulesCollection); $index++){
                $modHours =$conn->prepare("select *from tbl_modules where mod_id ='".$classModulesCollection[$index]."' and splz_id ='".$classId."' and level_id='".$levelId."' 
                and term_id ='".$termId."'");
                $modHours->execute();
                $theModHour =$modHours->fetch();
                
                $modFreq =$conn->prepare("select count(class_module_id) as module from tbl_t_schedule where sub_class_id ='".$classId."' and class_module_id ='".$theModHour['mod_id']."' and level_id='".$levelId."' 
                and term_id ='".$termId."'");
                $modFreq->execute();
                $theFreq =$modFreq->fetch();
                
                if($theFreq['module'] >= $theModHour['module_credits']) {
                    // echo "Function unsetted ".$classModulesCollection[$index]."<br>";
                    unset($classModulesCollection[$index]);
                    // print_r($classModulesCollection);
                    // echo "<br>";
                    sort($classModulesCollection);
                }               
            }

         }
         
        /**
         * used rooms
         * 
         */
        function banUsedRooms(){
            global $conn;
            $usedRoom =$conn->prepare("select *from tbl_t_schedule");
            $usedRoom->execute();
            while($TheRoom =$usedRoom->fetch()){
              array_push($usedRooms, $TheRoom['room_id']); 
            }
            
        }
         
         /**
        * check for time table existance
        */ 
        
        function isClassAvailable($classId, $levelId, $term){
            global $conn;
            $status =0;
            
            //check clas existance
            
            $class =$conn->prepare("select *from tbl_t_schedule where sub_class_id ='".$classId."' and level_id ='".$levelId."' and term_id ='".$term."'");
            $class->execute();
            if($class->rowCount()>0){
                
                //changing flag value
                $status =1;
                echo '<script>
                confirm(" Time Table available");
                </script>';
                // header('Location:registry/new_class.php');
                //header('Location:dean/student_supervisor');                
            }
            return $status;
        }
        
            $LearningDay =$conn->prepare("select *from tbl_t_days");
            $LearningDay->execute();
             while($theDay =$LearningDay->fetch()){
                 array_push($allDays,$theDay['day_name']);
             }
             
            $hour =$conn->prepare("select *from tbl_t_hours");
            $hour->execute();
            while($theHour =$hour->fetch()){
                array_push($allHour, $theHour['hour_label']);
            } 
                
        $subClassId =$conn->prepare("select *from tbl_t_sub_class where sub_class_name ='".$classId."'");
        $subClassId->execute();
        while($theSubClass =$subClassId->fetch()){
            $LearningDay =$conn->prepare("select *from tbl_t_days");
            $LearningDay->execute();
            while($theDay =$LearningDay->fetch()){
                $hour =$conn->prepare("select *from tbl_t_hours");
                $hour->execute();
                while($theHour =$hour->fetch()){
                    $classDays[$classId][$theDay['day_name']][] =$theHour['actual_hour'];
                }
            }
        }
 
        // roomm matrix with days and hours
        // $roomAvailabl=$conn->prepare("select *from tbl_block_rooms where room_id not in(select room_id from tbl_t_schedule) and block_id IN(select block_id from tbl_program_block where fact_id like '%".$theOverall['fac_id']."%' )");
        // $roomAvailabl->execute();
        // if($roomAvailabl->rowCount()<1){
            $roomAvailabl=$conn->prepare("select *from tbl_block_rooms where  block_id IN(select block_id from tbl_program_block where fact_id like '%".$theOverall['fac_id']."%')");
            $roomAvailabl->execute();            
        // }
        while($theRoom =$roomAvailabl->fetch()){
            // echo "Faculty ".$theOverall['fac_id'];
            // echo "<br>";
            
            //push into rooms array
            array_push($allRooms, $theRoom['room_code']);
            
            $LearningDay =$conn->prepare("select *from tbl_t_days");
            $LearningDay->execute();
            while($theDay =$LearningDay->fetch()){
                $hour =$conn->prepare("select *from tbl_t_hours");
                $hour->execute();
                while($theHour =$hour->fetch()){
                    $roomDays[$theRoom['room_name']][$theDay['day_name']][] =$theHour['actual_hour'];
                }
            }            
        }  
        
        // flags to terminate loops when needed
        $class_courses_index = 0; // starting index of class_courses array
        $rooms_index = 0; // starting index of rooms array
        $class_rooms = array();
        $indexesused=array();
        $hoursUsed =array();
        $uniqueValues =array();
        $remaining_hours = 0;
        $availabilityStatus =0;
        $ind=0;
        $myModule2 =0;
        $myModule=0;
        
        // sort($allRooms);
            
        //filter used hours
        function upodateValue(&$arr, $var){
           if($var<1){
                return 0;
            } 
            if (!in_array($var, $arr)) {
                array_push($arr, $var);
                return $var;
            }
            else{
                $var = rand(1, 10);
                return updateValues($arr, $var); 
            }
        }
        
        // compare with module leaders
        function updateValue(&$arr, $var, $class, $term, $day, $moduleId, $recursionCount = 0) {
            global $conn;
        
            if ($var < 1) {
                return 0;
            }
        
            if (!in_array($var, $arr)) {
                $modLeader = $conn->prepare("SELECT * FROM tbl_module_leader WHERE module_id 
                IN (SELECT module_id FROM tbl_modules WHERE mod_id = :module AND splz_id = :class and term_id = :term)");
        
                $modLeader->bindParam(':module', $moduleId, PDO::PARAM_INT);
                $modLeader->bindParam(':class', $class, PDO::PARAM_INT);
                $modLeader->bindParam(':term', $term, PDO::PARAM_INT);
                $modLeader->execute();
        
                if ($modLeader->rowCount()) {
                    $theLeader = $modLeader->fetch();
        
                    $moduleDay = $conn->prepare("SELECT * FROM tbl_t_schedule WHERE class_module_id 
                    IN (SELECT mod_id FROM tbl_modules WHERE module_id 
                    IN (SELECT module_id FROM tbl_module_leader WHERE staff_id = :staff_id)) AND learning_day_id = :day AND hour_id = :hour AND term_id = :term");
        
                    $moduleDay->bindParam(':staff_id', $theLeader['staff_id'], PDO::PARAM_STR);
                    $moduleDay->bindParam(':hour', $var, PDO::PARAM_INT);
                    $moduleDay->bindParam(':term', $term, PDO::PARAM_INT);
                    $moduleDay->bindParam(':day', $day, PDO::PARAM_INT);
                    $moduleDay->execute();
        
                    if ($moduleDay->rowCount()) {
                        $var = rand(1, 10);
                        $day =rand(1, 5);
        
                        // Check the recursion count
                        if ($recursionCount < 40) {
                            $recursionCount++;
                            return updateValue($arr, $var, $class, $term, $day, $moduleId, $recursionCount);
                        } else {
                            return -1;
                        }
                    } else {
                        array_push($arr, $var);
                        return $var;
                    }
                } else {
                    array_push($arr, $var);
                    return $var;
                }
            } else {
                $var = rand(1, 10);
                $day =rand(1, 5);
        
                // Check the recursion count
                if ($recursionCount < 40) { 
                    $recursionCount++;
                    return updateValue($arr, $var, $class, $term, $day, $moduleId, $recursionCount);
                } else {
                    return -1;
                }
            }
        }
        
        // hour contents
        function hourRoom($arrayOfRooms, $classModules, $term, $day, $hour, $room){
            global $conn;
            $counter =count($classModules);
            $randNum =rand(0, $counter-1);
            
            if(count($arrayOfRooms) ==0){
                return 1;
            }
            
            $hourRoom =$conn->prepare("select *from tbl_t_schedule where term_id ='".$term."' and learning_day_id ='".$day."' and hour_id ='".$hour."' and room_id ='".$room."' group by hour_id");
            $hourRoom->execute();
            if(($hourRoom->rowCount())<=0){
                $theHour =$hourRoom->fetch();
                // echo "returned romm".$room ."<br>";
                return $room;
            }
            else{
                $module =$classModules[$randNum];
                
                $room =$arrayOfRooms[$module];
                
                $roomId =$conn->prepare("select *from tbl_block_rooms where room_code ='".$room."'");
                $roomId->execute();
                $theRoom =$roomId->fetch();
                
                return hourRoom($arrayOfRooms, $classModules, $term, $day, $hour, $theRoom['room_id']);
            }
        } 
        
            
        for($i=0; $i<count($allClassModules); $i++ ){
            $indexToUse =rand(0,10);
            $exists =array_search($indexToUse, $indexesused);
            if($exists){
                continue;
            }
            else{
                array_push($indexesused, $indexToUse);                         
            }
        }
        $indexesused =array_unique($indexesused);
        foreach($indexesused as $index){
            array_push($uniqueValues, $index);
        }
         
        foreach($allClassModules as $class){
            $class_rooms[$class] = $allRooms[$rooms_index];
            $rooms_index++;
            if ($rooms_index == count($allRooms)) {
                $rooms_index = 0;
            }
        }
            
            
            /**
             * term id
             */
            $termId =$conn->prepare("select max(term_id) as term from tbl_modules where level_id ='".$levelId."'
            and splz_id =(select splz_id from tbl_specialization where splz_id ='".$classId."')");
            $termId->execute();
            $theTerm =$termId->fetch();
            $term =$theTerm['term'];
            
            
            //check if time table exists
            $status =isClassAvailable($classId=returnSubClassId($classId), $levelId, $levelTermId);
            // echo "Game Changer <br>";
            if(count($allClassModules)==0){
                echo '<script>
                confirm("No courses assigned")
                </script>';
                echo'<meta http-equiv="refresh"'.'content="1;URL=time_table_haub.php">';
            }            
            else if($status){
                 //echo'<meta http-equiv="refresh"'.'content="1;URL=time_table_haub.php">';
            }
            
            else{
                try{
                    sort($uniqueValues);
                    // print_r($uniqueValues);
                    // echo "<br>"; 
                    // print_r($allClassModules);
                    // echo "<br>"; 
                    // print_r($allRooms);
                    // echo "<br>";
                    sort($allHour);
                    // print_r($allHour);
                    // echo "<br>";
                    
                    for ($index =0; $index < count($allDays); $index++) {
                        for($j = 0; $j < 8+$remaining_hours; $j+=2) {
                            
                            $modHours =countModHours($allClassModules[$class_courses_index]);
                            
                            // else{
                            if ($ind == count($uniqueValues)) {
                                $ind = 0;
                            }                                                                                            
                            // echo "Inner loop ".$j. " Less Hours! Day ".$allDays[$index]." Index ".$ind." =>  ".$uniqueValues[$ind]."course index ".$class_courses_index."<br>";
                            
                            $myClass =returnSubClassId($classId);
                             
                            // class size
                            $theSize =$conn->prepare("select count(module_id) as sizes from tbl_markby_module where module_id ='".$allClassModules[$class_courses_index]."' and splz_id='".$myClass."'");
                            $theSize->execute();
                            
                            if(($theSize->rowCount())>0){
                                $theSizes =$theSize->fetch();
                                $registeredStudents =$theSizes['sizes']; 
                            }
                            else{
                                $registeredStudents =10;
                            }
                            
                            
                            
                            // remove exhuasted modules 
                            unsetAModule($myClass, $levelId, $levelTermId, $allClassModules);
                            if ($class_courses_index == count($allClassModules)) {
                                $class_courses_index = 0;
                            }
                            
                            // break the loop once there are
                            // no more modules
                            if(count($allClassModules)==0){
                                break;
                            }                                                                            
                            
                            $myModule =returnModId($allClassModules[$class_courses_index], $myClass, $levelId); 
                            $modHours =returnModuleHours($allClassModules[$class_courses_index], $myClass, $levelId);
                            
                            if($val =returnRoomId($class_rooms[$allClassModules[$class_courses_index]], $registeredStudents)==0){
                                $room =returnRoomId($class_rooms[$allClassModules[0]],0);
                            }
                            else{
                                $room =returnRoomId($class_rooms[$allClassModules[$class_courses_index]], $registeredStudents);
                            }
                            
                            $day =returnDayId($allDays[$index]);
                            
                            $isused =updateValue($hoursUsed,returnHourId($allHour[$uniqueValues[$ind]]), $myClass, $levelTermId, $day, $myModule);
                            $hour = $isused;
                            
                            $finalRoom =hourRoom($class_rooms, $allClassModules, $levelTermId, $day, $hour, $room);
                            
                            insertModule($prgType, $prgMode, $hour, $myClass, $levelId, $levelTermId, $myModule, $modHours, $finalRoom, $day,1);
    
                                                    
                            // if(($uniqueValues[$ind]+1)>10){
                            //     $isHourUsed =updateValue($hoursUsed,returnHourId($allHour[rand(1,9)]), $class, $term, $day, $moduleId);
                            //     $hour2 = $isHourUsed;
                            // }
                            // else{
                            
                            // }
                            $class2 =returnSubClassId($classId);
                                                    
                            unsetAModule($class2, $levelId, $levelTermId, $allClassModules);
                            if ($class_courses_index == count($allClassModules)) {
                                $class_courses_index = 0;
                            }
                            
                            // break the loop
                            // once module queue is empty
                            if(count($allClassModules)==0){
                                break;
                            }                                                                                                    
                                                    
                            $myModule2 =returnModId($allClassModules[$class_courses_index], $class2, $levelId); 
                                                    
                            // echo "Hour Two ".$hour2."<br>";
                            $modHours2 =returnModuleHours($allClassModules[$class_courses_index], $class2, $levelId);
                            
                            if($val =returnRoomId($class_rooms[$allClassModules[$class_courses_index]], $registeredStudents)==0){
                                $room2 =returnRoomId( $class_rooms[$allClassModules[0]], 0);
                            }
                            else{
                                $room2 =returnRoomId($class_rooms[$allClassModules[$class_courses_index]], $registeredStudents);
                            }
                            $day2 =returnDayId($allDays[$index]);
                            
                            if($hour==11){
                                $isUsed =updateValue($hoursUsed,($hour+0), $class2, $levelTermId, $day, $myModule2);  
                            }
                            else{
                                $isUsed =updateValue($hoursUsed,($hour+1), $class2, $levelTermId, $day, $myModule2);
                            }
                                
                            $hour2 = $isUsed;
                            $finalRoom2 =hourRoom($class_rooms, $allClassModules, $levelTermId, $day, $hour2, $room2);
                                                    
                            //insert
                            insertModule($prgType, $prgMode, $hour2, $class2, $levelId, $levelTermId, $myModule2, $modHours2, $finalRoom2, $day,1);
                                                
                            $class_courses_index++;
                            $ind++;
                                                    
                            if ($class_courses_index == count($allClassModules)) {
                                $class_courses_index = 0;
                            }
                        }
                        $hoursUsed =array();
                        $remaining_hours = max($remaining_hours,0);
                    }
                }
                catch(Exception $exc){
                        echo "Exception caught: " . $exc->getMessage() . "<br>";
                        echo "Stack trace: " . $exc->getTraceAsString() . "<br>";
                }
            }
    ?>