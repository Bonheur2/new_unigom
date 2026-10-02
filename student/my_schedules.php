<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Timetable</h1>
        </div>
        <div class="section-body">
            <?php
                $acad=$conn->prepare("SELECT * FROM tbl_acad_cycle WHERE status=1 LIMIT 1");
                $acad->execute();
                $ac=$acad->fetch();
            ?>
            <div class="row">
                <div class="col-md-12" style="display:flex; flex-direction:row; justify-content:center; flex-wrap:wrap; padding:0px;">
                    <?php
                        $sql = $conn->prepare("select * from tbl_register_program_ug where reg_no ='".$_SESSION['Identification']."' and reg_active =1 ORDER BY reg_prg_id DESC LIMIT 1");
                        $sql->execute();
                        if($sql->rowCount() > 0){
                            $sql = $sql->fetch();
                    ?>
                    
                    <div class="card" style="width:97%;">
                        <div class="card-body">
                            <input type="hidden" id="stu" value="<?php echo $_SESSION['Identification']; ?>">
                            <input type="hidden" id="splz" value="<?php echo $sql['splz_id']; ?>">
                            <input type="hidden" id="level" value="<?php echo $sql['level_id']; ?>">
                            <input type="hidden" id="prg" value="<?php echo $sql['prg_mode_id']; ?>">
                            <select class="form-control select2" id="sem" style="width:100%;">
                                <option>--choose semester--</option>
                                <?php
// Get the student's semesters based on their registration number
$selectStudentSem = "SELECT * FROM tbl_student_semester WHERE reg_no = :reg_no";
$stmtStudentSem = $conn->prepare($selectStudentSem);
$stmtStudentSem->execute(['reg_no' => $_SESSION['Identification']]);

// Fetch the active academic cycle
$selectAcadCycle = "SELECT * FROM tbl_acad_cycle WHERE status = '1'";
$stmtAcadCycle = $conn->prepare($selectAcadCycle);
$stmtAcadCycle->execute();
$rowAcadCycle = $stmtAcadCycle->fetch(PDO::FETCH_ASSOC);

// Check if an academic cycle is found
if ($rowAcadCycle) {
    $acadCycleId = $rowAcadCycle['acad_cycle_id'];

    foreach ($stmtStudentSem as $rowStudentSem) {
        $semId = $rowStudentSem['sem_id'];

        // Fetch semesters based on the academic year and semester ID
        $selectSem = "
            SELECT ts.*, s.* 
            FROM tbl_semester ts
            INNER JOIN semester s ON ts.semester = s.id 
            WHERE ts.acad_year = :acad_year AND ts.sem_id = :sem_id
        ";
        $stmtSem = $conn->prepare($selectSem);
        $stmtSem->execute([
            'acad_year' => $acadCycleId,
            'sem_id' => $semId
        ]);

        // Generate the options for each semester found
        while ($sem = $stmtSem->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=\"{$sem['semester']}\">{$sem['name']}</option>";
        }
    }
}
?>
                            </select><span id="spinner5"></span>
                        </div>

                        <div class="card-body">
                            <div id ="table"></div>
                        </div>
                    </div>
                    <?php } else{ ?>
                    <p>Not applicable</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $(document).on('change', '#sem', function(){
            var getData= {
                    stu: $("#stu").val(),
                    splz: $("#splz").val(),
                    lev: $("#level").val(),
                    prg: $("#prg").val(),
                    sem: $("#sem").val()
            };
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Timetable/student.php",
                data: getData,
                success:function(data){
                    $('#spinner5').fadeOut('fast');
                    $('#table').html(data);
				},
				error:function(error){
				    $('#spinner5').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        })
    });
    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'info:',
            message: feedback,
            position: 'topCenter'
        });
    }
</script>