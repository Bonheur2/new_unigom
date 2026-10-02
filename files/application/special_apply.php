<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12" style="margin:auto;">
                    <?php
                    // Check for active academic year
                    $select_academic_year="SELECT * FROM tbl_acad_cycle WHERE status=1";
                    $cselect_academic_year=$conn->prepare($select_academic_year);
                    $cselect_academic_year->execute();
                    $row_cselect_academic_year=$cselect_academic_year->fetch(PDO::FETCH_ASSOC);
                    
                    if($row_cselect_academic_year){
                        // Academic year exists - show application form
                    ?>
                    <form id="apply" action="apply" method="POST">
                        <input type="hidden" name="action" value="apply">
                        <div class="card">
                            <div class="card-header row" style="display:flex; justify-content:center">
                                <button type="button" id="section-1-indicator"
                                    class="btn btn-primary col-12 col-md-4 col-lg-4" style="margin-bottom:10px;"><span
                                        class="badge badge-transparent">1</span> &nbsp;Main information</button>
                                <button type="button" id="section-2-indicator"
                                    class="btn btn-light col-12 col-md-4 col-lg-4" style="margin-bottom:10px;"><span
                                        class="badge badge-transparent">2</span> &nbsp;Supplementary data</button>
                            </div>
                            <!--personal details start-->
                            <div class="card-body row" id="section-1">
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Surname (Family name)
                                        <code><b><span id="lname_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="lname" id="lname"
                                            placeholder="e.g. GASANA" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Middle name <code>(optional)</code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="mname" id="mname"
                                            placeholder="e.g. John">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>First name <code><b><span id="fname_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="fname" id="fname"
                                            placeholder="e.g. Peter" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>National ID/ Passport <code><b><span id="nid_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-id-card"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="nid" id="nid"
                                            placeholder="e.g. 1 1990 800 xxxxxxxxxx" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Gender <code><b><span id="gender_star"></span></b></code></label>
                                    <div class="input-group">
                                        <!--<div class="input-group-prepend">-->
                                        <!--    <div class="input-group-text">-->
                                        <!--        <i class="fas fa-users"></i>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <select name="gender" id="gender" class="form-control select2"
                                            placeholder="choose one" required>
                                            <option value="" disabled selected hidden>Choose One...</option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Date of birth</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input type="date" class="form-control" name="dob" id="dob"
                                            value="<?php echo date('Y-m-d', strtotime('-25 years')); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Phone number <code><b><span id="phone_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control phone-number" name="phone" id="phone"
                                            placeholder="e.g. +232724....." required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>E-mail <code><b><span id="email_star"></span></b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                        </div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="e.g. annet@example.com" required>
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="button" class="btn btn-success btn-sm" onclick="goToSection2()"><span
                                            id="spinner2"></span>&nbsp; <span id="indicator2">Next</span></button>
                                </div>
                            </div>
                            <!--personal details end-->
                            <!--address start-->
                            <div class="card-body row" id="section-2" hidden>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Nationality <code><b>*</b></code></label>
                                    <select name="nationality" id="nationality" class="form-control select2">
                                        <?php
                                        $sql_nat=$conn->prepare("SELECT * FROM tbl_nationality");
                                        $sql_nat->execute();
                                        $i=1;
                                        while($nat=$sql_nat->fetch()){
                                    ?>
                                        <option value="<?php echo $nat['nat_id']; ?>"
                                            <?php echo $nat['nat_id'] == 221?'selected':''; ?>>
                                            <?php echo $nat['nationality']; ?> </option>
                                        <?php } ?>
                                        <option value="0">Others</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Country of residence<code><b>*</b></code></label>
                                    <select name="country" id="country" class="form-control select2" required>
                                        <?php
                                        $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                        $sql_cntr->execute();
                                        $i=1;
                                        while($cntr=$sql_cntr->fetch()){
                                    ?>
                                        <option value="<?php echo $cntr['cntr_id']; ?>"
                                            <?php if($cntr['cntr_id']==167) echo 'selected' ?>>
                                            <?php echo $cntr['cntr_name']." [ ".$cntr['cntr_code']." ]"; ?>
                                        </option>
                                        <?php } ?>
                                    </select>&nbsp;<span id="spinner_cntr"></span>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Father's name <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="father_name" class="form-control"
                                            placeholder="father's full names" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Mother's name <code><b>*</b></code></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="mother_name" class="form-control"
                                            placeholder="mother's full names" required>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Father's Phone (if any)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="parent_phone" class="form-control"
                                            placeholder="e.g. +2327245....">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-sm-6 col-lg-6">
                                    <label>Mother's Phone (if any)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="ref_phone" class="form-control"
                                            placeholder="e.g. +232724....">
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span
                                            id="spinner"></span>&nbsp; <span id="indicator">Submit</span></button>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right:10px;"
                                        onclick="goBackToSection1()"><span id="spinner3"></span>&nbsp; <span
                                            id="indicator3">Back</span></button>
                                </div>
                            </div>
                            <!--address end-->
                        </div>

                    </form>

                    <!-- Special Application Notice -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="alert alert-info" role="alert">
                                <h5 class="alert-heading">
                                    <i class="fas fa-info-circle"></i> Special Application
                                </h5>
                                <p class="mb-2">
                                    <strong>Academic Year:</strong>
                                    <?php echo htmlspecialchars($row_cselect_academic_year['acad_year']); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php
                    }
                    else{
                        // No academic year found
                    ?>

                    <div class="card">
                        <div class="card-body text-center">
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle"></i> Application Portal Unavailable
                                </h4>
                                <hr>
                                <p class="mb-3">No active academic year is currently set. Please contact the
                                    administration for more information.</p>

                                <div class="mt-4">
                                    <p class="mb-2">
                                        <i class="fas fa-phone"></i>
                                        <strong>Need Help?</strong> Contact our admissions office for assistance.
                                    </p>
                                    <button type="button" class="btn btn-outline-warning" onclick="location.reload()">
                                        <i class="fas fa-refresh"></i> Refresh Page
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {
    $("#apply").submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Processing...");
        $("#sBtn").attr('disabled', true);
        $.ajax({
            url: "/files/application/application_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(data) {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Submit");
                if (data.status == 200) {
                    var mail = $("#email").val().trim();
                    $("#apply")[0].reset();
                    pop_up_success(data.message);
                    setTimeout(function() {
                        window.location.href = "../../verify_email?em=" + mail;
                    }, 2000);

                }
                if (data.status == 401) {
                    pop_wrong(data.message);
                    $("#sBtn").attr('disabled', false);
                }
                if (data.status == 500) {
                    pop_wrong(data.message);
                    $("#sBtn").attr('disabled', false);
                }
            },
            error: function() {
                $('#spinner').fadeOut('fast');
                $("#sBtn").attr('disabled', false);
                $('#indicator').html("Submit");
                pop_wrong("Something went wrong!");
            }
        });
    });
});

function pop_wrong(feedback) {
    iziToast.warning({
        title: 'Info',
        message: feedback,
        position: 'topCenter'
    });
}

function pop_up_success(feedback) {
    iziToast.success({
        title: 'Info:',
        message: feedback,
        position: 'topCenter'
    });
}

function pop_wrong_verify(feedback) {
    iziToast.warning({
        title: 'Info',
        message: feedback,
        position: 'topCenter'
    });
}

function goToSection2() {
    var section1Valid = validateSection1();
    if (section1Valid) {
        $("#fname_star").html("");
        $("#lname_star").html("");
        $("#nid_star").html("");
        $("#gender_star").html("");
        $("#phone_star").html("");
        $("#email_star").html("");
        $('#spinner2').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("loading...");
        setTimeout(function() {
            $('#spinner2').fadeOut('fast');
            $('#indicator2').html("Next");
            $("#section-1-indicator").removeClass('btn-primary');
            $("#section-1-indicator").addClass('btn-light');
            $("#section-2-indicator").removeClass('btn-light');
            $("#section-2-indicator").addClass('btn-primary');
            $('#section-1').attr('hidden', true);
            $('#section-2').attr('hidden', false);
        }, 1000);
    }
}

function goBackToSection1() {
    $('#spinner3').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $('#indicator3').html("loading...");
    setTimeout(function() {
        $('#spinner3').fadeOut('fast');
        $('#indicator3').html("back");
        $("#section-2-indicator").removeClass('btn-primary');
        $("#section-2-indicator").addClass('btn-light');
        $("#section-1-indicator").removeClass('btn-light');
        $("#section-1-indicator").addClass('btn-primary');
        $('#section-2').attr('hidden', true);
        $('#section-1').attr('hidden', false);
    }, 2000);

}

function validateSection1() {
    var fname = document.getElementById('fname').value.trim();
    var lname = document.getElementById('lname').value.trim();
    var nid = document.getElementById('nid').value.trim();
    var gender = document.getElementById('gender').value.trim();
    var phone = document.getElementById('phone').value.trim();
    var email = document.getElementById('email').value.trim();

    if (fname === '' || lname === '' || nid === '' || gender === '' || phone === '' || email === '') {
        pop_wrong_verify("Fill all missing fields");
        fname === '' ? $("#fname_star").html("*") : $("#fname_star").html("");
        lname === '' ? $("#lname_star").html("*") : $("#lname_star").html("");
        nid === '' ? $("#nid_star").html("*") : $("#nid_star").html("");
        gender === '' ? $("#gender_star").html("*") : $("#gender_star").html("");
        phone === '' ? $("#phone_star").html("*") : $("#phone_star").html("");
        email === '' ? $("#email_star").html("*") : $("#email_star").html("");
        return false;
    }

    return true;
}
</script>