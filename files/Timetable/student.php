<?php
include ('../../meet/con.php');

$stu = $_POST['stu'];
$splz = $_POST['splz'];
$level = $_POST['lev'];
$prg_mode = $_POST['prg'];
$semester = $_POST['sem'];
$exams = 0;
$today = date('Y-m-d');
function getTimetable($conn, $splz, $level, $prg_mode, $semester, $acadCycleId) {
    $stmt = $conn->prepare("
        SELECT 
            s.schedule_id,
            s.s_date, 
            s.hour_id,
            m.module_name,
            r.room_name,
            us.first_name,
            us.family_name,
            s.day, 
            s.stype,
            h.hour_lower_limit, 
            h.hour_upper_limit
        FROM tbl_t_schedule s
            LEFT JOIN tbl_t_hours h ON s.hour_id = h.hour_id
            LEFT JOIN tbl_modules tm ON s.module_id = tm.module_id
            LEFT JOIN modules m ON tm.mod_id = m.module_id
            LEFT JOIN tbl_block_rooms r ON s.room_id = r.room_id
            LEFT JOIN tbl_users us ON s.staff_id = us.Identification
        WHERE s.splz_id = ? AND s.level_id = ? AND s.prg_mode = ? AND s.term_id = ? AND s_date >= '".$today."' AND acad_cycle_id = ?
        ORDER BY s.s_date, s.hour_id ASC
    ");
    $stmt->execute([$splz, $level, $prg_mode, $semester, $acadCycleId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAcadCycle($conn){
    $stmt = $conn->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status = 1 ORDER BY acad_cycle_id DESC LIMIT 1");
    $stmt->execute();
    return $stmt->fetch()['acad_cycle_id'];
}

$acadCycleId = getAcadCycle($conn);
$timetables = getTimetable($conn, $splz, $level, $prg_mode, $semester, $acadCycleId);

function getWeekDates($startDate) {
    $start = new DateTime($startDate);
    $weekDates = [];
    for ($i = 0; $i < 7; $i++) {
        $weekDates[] = $start->format('Y-m-d');
        $start->modify('+1 day');
    }
    return $weekDates;
}

$startDate = isset($timetables[0]['s_date']) ? $timetables[0]['s_date'] : null;
$weekDates = $startDate ? getWeekDates($startDate) : [];
?>


<div class="card-body" style="border: 2px solid #0a7033; width: 98%; margin: auto; margin-bottom: 20px; border-radius: 5px; padding: 0;">
    <div class="form-group">
        <div class="table-responsive">
            <table class="table table-hover table-sm" border="1">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Day</th>
                        <th scope="col">Period</th>
                        <th scope="col">Module</th>
                        <th scope="col">Room</th>
                        <th scope="col">Lecturer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timetables as $timetable){ ?>
                    <?php if($timetable['stype'] != 2){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($timetable['s_date']); ?></td>
                            <td><?php echo htmlspecialchars($timetable['day']); ?></td>
                            <td><?php echo htmlspecialchars($timetable['hour_lower_limit']) . ' - ' . htmlspecialchars($timetable['hour_upper_limit']); ?></td>
                            <td><?php echo htmlspecialchars($timetable['module_name']); ?></td>
                            <td><?php echo htmlspecialchars($timetable['room_name']); ?></td>
                            <td><?php echo htmlspecialchars($timetable['first_name'].' '.$timetable['family_name']); ?></td>
                        </tr>
                    <?php }else{  $exams++; ?>
                        <tr>
                            <td colspan="6" style="background-color: #0a7033; color: #FFF; text-align: center;"><b>Exam Week</b></td>
                        </tr>
                    <?php if($exams == 2){break;} } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>