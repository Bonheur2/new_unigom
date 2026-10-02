<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Applicant Problems</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Applicant Problems</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card" id="problems-card">
                        <div class="card-header">
                            <h4>Reported Problems</h4>
                        </div>
                        <div class="card-body pb-0">
                            <?php
                                $sql = $conn->prepare("
                                    SELECT p.*, 
                                           CONCAT(a.fname, ' ', a.lname) AS full_name,
                                           a.email, a.phone
                                    FROM tbl_applicant_prob p
                                    LEFT JOIN tbl_applicants a ON a.code = p.app_code
                                    ORDER BY p.recordedAt DESC
                                ");
                                $sql->execute();
                                $all_problems = $sql->fetchAll();
                                $pending = array_filter($all_problems, function($r) { return $r['status'] == 0; });
                                $solved  = array_filter($all_problems, function($r) { return $r['status'] == 1; });
                            ?>

                            <!-- Tab Nav -->
                            <ul class="nav nav-tabs" id="problemTabs" role="tablist" style="border-bottom:2px solid #1f6feb;">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-pending-link" data-toggle="tab" href="#tab-pending" role="tab"
                                       style="color:#1a3c6e; font-weight:600; font-size:13px; border:1.5px solid #1f6feb; border-bottom:none; background:#fff; border-radius:4px 4px 0 0;">
                                        <i class="fas fa-clock"></i>&nbsp;Pending
                                        <span style="background:#ffc107; color:#333; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:bold; margin-left:4px;"><?php echo count($pending); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-solved-link" data-toggle="tab" href="#tab-solved" role="tab"
                                       style="color:#555; font-weight:600; font-size:13px; border:1px solid #c7d7f9; border-bottom:none; background:#f4f7fc; border-radius:4px 4px 0 0;">
                                        <i class="fas fa-check-circle"></i>&nbsp;Solved
                                        <span style="background:#28a745; color:#fff; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:bold; margin-left:4px;"><?php echo count($solved); ?></span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content mt-3" id="problemTabsContent">

                                <!-- PENDING TAB -->
                                <div class="tab-pane fade show active" id="tab-pending" role="tabpanel">
                                    <?php if (empty($pending)): ?>
                                        <div class="text-center text-muted py-5">
                                            <i class="fas fa-check-circle fa-3x mb-2 text-success"></i>
                                            <p>No pending problems. All clear!</p>
                                        </div>
                                    <?php else: ?>
                                        <div style="background-color:#f0f4ff; border:1.5px solid #1f6feb; border-radius:6px; overflow:hidden; margin-bottom:16px;">
                                            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                                                <thead>
                                                    <tr style="background:#1f6feb; color:#fff;">
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2; width:40px;">#</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2;">App Code</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2;">Full Name</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2;">Problem</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2; text-align:center;">Status</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2; text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; foreach ($pending as $prob): ?>
                                                    <tr style="background-color:<?php echo $i % 2 === 0 ? '#e8f0fe' : '#f0f4ff'; ?>;">
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; color:#555; text-align:center;"><?php echo $i++; ?></td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; color:#1a3c6e; font-weight:600;">
                                                            <?php echo htmlspecialchars($prob['app_code']); ?>
                                                        </td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; color:#1a3c6e; font-weight:600;">
                                                            <i class="fas fa-user-circle" style="color:#ffc107;"></i>&nbsp;
                                                            <?php echo htmlspecialchars($prob['full_name']); ?>
                                                        </td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; color:#555; max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                                            <?php echo htmlspecialchars($prob['problem_info']); ?>
                                                        </td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; text-align:center;">
                                                            <span style="background:#fff3cd; color:#856404; padding:3px 10px; border-radius:4px; font-size:11px; font-weight:bold;">Pending</span>
                                                        </td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; text-align:center;">
                                                            <button type="button"
                                                                onclick="openProblemModal(
                                                                    '<?php echo $prob['id']; ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['full_name'])); ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['email'])); ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['phone'] ?? '')); ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['app_code'])); ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['url_info'])); ?>',
                                                                    '<?php echo addslashes(str_replace(["\r\n", "\r", "\n"], "\\n", htmlspecialchars($prob['problem_info']))); ?>',
                                                                    '<?php echo addslashes(str_replace(["\r\n", "\r", "\n"], "\\n", htmlspecialchars($prob['problem_answer'] ?? ''))); ?>',
                                                                    '<?php echo $prob['recordedAt']; ?>',
                                                                    'pending'
                                                                )"
                                                                style="background:#1f6feb; color:#fff; border:none; border-radius:4px; padding:5px 14px; font-size:12px; font-weight:bold; cursor:pointer;">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- SOLVED TAB -->
                                <div class="tab-pane fade" id="tab-solved" role="tabpanel">
                                    <?php if (empty($solved)): ?>
                                        <div class="text-center text-muted py-5">
                                            <i class="fas fa-inbox fa-3x mb-2"></i>
                                            <p>No solved problems yet.</p>
                                        </div>
                                    <?php else: ?>
                                        <div style="background-color:#f0f4ff; border:1.5px solid #1f6feb; border-radius:6px; overflow:hidden; margin-bottom:16px;">
                                            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                                                <thead>
                                                    <tr style="background:#1f6feb; color:#fff;">
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2; width:40px;">#</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2;">App Code</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2;">Full Name</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2;">Problem</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2; text-align:center;">Status</th>
                                                        <th style="padding:10px 14px; border:1px solid #4a90e2; text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; foreach ($solved as $prob): ?>
                                                    <tr style="background-color:<?php echo $i % 2 === 0 ? '#e8f0fe' : '#f0f4ff'; ?>;">
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; color:#555; text-align:center;"><?php echo $i++; ?></td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; color:#1a3c6e; font-weight:600;">
                                                            <?php echo htmlspecialchars($prob['app_code']); ?>
                                                        </td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; color:#1a3c6e; font-weight:600;">
                                                            <i class="fas fa-user-circle" style="color:#28a745;"></i>&nbsp;
                                                            <?php echo htmlspecialchars($prob['full_name']); ?>
                                                        </td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; color:#555; max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                                            <?php echo htmlspecialchars($prob['problem_info']); ?>
                                                        </td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; text-align:center;">
                                                            <span style="background:#d4edda; color:#155724; padding:3px 10px; border-radius:4px; font-size:11px; font-weight:bold;">Solved</span>
                                                        </td>
                                                        <td style="padding:9px 14px; border:1px solid #c7d7f9; text-align:center;">
                                                            <button type="button"
                                                                onclick="openProblemModal(
                                                                    '<?php echo $prob['id']; ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['full_name'])); ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['email'])); ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['phone'] ?? '')); ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['app_code'])); ?>',
                                                                    '<?php echo addslashes(htmlspecialchars($prob['url_info'])); ?>',
                                                                    '<?php echo addslashes(str_replace(["\r\n", "\r", "\n"], "\\n", htmlspecialchars($prob['problem_info']))); ?>',
                                                                    '<?php echo addslashes(str_replace(["\r\n", "\r", "\n"], "\\n", htmlspecialchars($prob['problem_answer'] ?? ''))); ?>',
                                                                    '<?php echo $prob['recordedAt']; ?>',
                                                                    'solved'
                                                                )"
                                                                style="background:#1f6feb; color:#fff; border:none; border-radius:4px; padding:5px 14px; font-size:12px; font-weight:bold; cursor:pointer;">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div><!-- end tab-content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- ===== PROBLEM DETAIL MODAL ===== -->
<div id="problemModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:8px; width:100%; max-width:580px; margin:30px auto; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.25); position:relative;">

        <!-- Modal Header -->
        <div style="background:linear-gradient(135deg,#1f6feb,#0d47a1); padding:14px 20px; display:flex; justify-content:space-between; align-items:center;">
            <p style="margin:0; font-size:14px; font-weight:bold; color:#fff;">
                <i class="fas fa-exclamation-circle"></i>&nbsp; Problem Details
            </p>
            <span onclick="closeProblemModal()" style="color:#fff; cursor:pointer; font-size:20px; line-height:1; font-weight:bold;">&times;</span>
        </div>

        <!-- Modal Body -->
        <div style="padding:16px 20px; background:#f0f4ff; max-height:75vh; overflow-y:auto;">

            <!-- Applicant Info Box -->
            <div style="background:#ffffff; border:1px solid #1f6feb; border-radius:4px; padding:10px 14px; margin-bottom:12px;">
                <p style="margin:0 0 6px; font-size:13px; font-weight:bold; color:#1a3c6e;">
                    <i class="fas fa-user-circle"></i>&nbsp; Applicant Info
                </p>
                <p style="margin:0 0 3px; font-size:13px; color:#555555;">
                    <strong>Name:</strong> <span id="modal_name"></span>
                </p>
                <p style="margin:0 0 3px; font-size:13px; color:#555555;">
                    <strong>App Code:</strong> <span id="modal_appcode" style="color:#1f6feb; font-weight:600;"></span>
                </p>
                <p style="margin:0 0 3px; font-size:13px; color:#555555;">
                    <strong>Phone:</strong> <span id="modal_phone"></span>
                </p>
                <p style="margin:0 0 3px; font-size:13px; color:#555555;">
                    <strong>Email:</strong> <span id="modal_email"></span>
                </p>
                <p style="margin:0 0 3px; font-size:13px; color:#555555;">
                    <strong>Reported On:</strong> <span id="modal_date"></span>
                </p>
                
                
                <p style="margin:0; font-size:13px; color:#555555;" id="modal_url_row">
                    <strong>Source Page:</strong>
                    <span id="modal_url"></span>
                </p>
            </div>

            <!-- Problem Box -->
            <div style="background:#ffffff; border:1px solid #c7d7f9; border-radius:4px; padding:10px 14px; margin-bottom:12px;">
                <p style="margin:0 0 6px; font-size:13px; font-weight:bold; color:#1a3c6e;">
                    <i class="fas fa-exclamation-circle" style="color:#ffc107;"></i>&nbsp; Problem Reported
                </p>
                <div id="modal_problem" style="font-size:13px; color:#555555; line-height:1.7; max-height:130px; overflow-y:auto; white-space:pre-wrap;"></div>
            </div>

            <!-- Answer Box -->
            <div style="background:#ffffff; border:1px solid #c7d7f9; border-radius:4px; padding:10px 14px; margin-bottom:4px;">
                <p style="margin:0 0 6px; font-size:13px; font-weight:bold; color:#1a3c6e;">
                    <i class="fas fa-reply" style="color:#28a745;"></i>&nbsp; Your Answer
                </p>
                <textarea id="modal_answer" rows="4" placeholder="Type your answer here..."
                    style="width:100%; border:1px solid #c7d7f9; border-radius:4px; padding:8px 10px; font-size:13px; color:#555555; resize:none; outline:none; box-sizing:border-box;"></textarea>
            </div>

        </div>

        <!-- Modal Footer -->
        <div style="background:#e8f0fe; padding:10px 20px; display:flex; justify-content:space-between; align-items:center; border-top:1px solid #c7d7f9;">
            <div id="modal_reopen_btn"></div>
            <div style="display:flex; gap:8px;">
                <button onclick="closeProblemModal()"
                    style="background:#6c757d; color:#fff; border:none; border-radius:4px; padding:7px 16px; font-size:13px; font-weight:bold; cursor:pointer;">
                    <i class="fas fa-times"></i> Close
                </button>
                <button id="modal_answer_only_btn" onclick="saveAnswerOnly()"
                    style="background:#17a2b8; color:#fff; border:none; border-radius:4px; padding:7px 16px; font-size:13px; font-weight:bold; cursor:pointer;">
                    <span id="modal_spin_answer"></span>
                    <i class="fas fa-save"></i> Save Answer
                </button>
                <button id="modal_save_btn" onclick="saveFromModal()"
                    style="color:#fff; border:none; border-radius:4px; padding:7px 16px; font-size:13px; font-weight:bold; cursor:pointer;">
                    <span id="modal_spin"></span>
                    <i class="fas fa-check"></i> <span id="modal_save_label">Mark as Solved</span>
                </button>
            </div>
        </div>

    </div>
</div>


<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
var currentProbId   = null;
var currentProbType = null;

function openProblemModal(id, name, email, phone, appcode, url, problem, answer, date, type) {
    currentProbId   = id;
    currentProbType = type;

    document.getElementById('modal_name').innerText    = name;
    document.getElementById('modal_appcode').innerText = appcode;
    document.getElementById('modal_phone').innerText   = phone || 'N/A';
    document.getElementById('modal_email').innerText   = email;
    document.getElementById('modal_date').innerText    = date;
    document.getElementById('modal_problem').innerText = problem.replace(/\\n/g, '\n');
    document.getElementById('modal_answer').value      = answer.replace(/\\n/g, '\n');

    // URL row
    if (url && url.trim() !== '') {
        document.getElementById('modal_url').href      = url;
        document.getElementById('modal_url').innerText = url;
        document.getElementById('modal_url_row').style.display = '';
    } else {
        document.getElementById('modal_url_row').style.display = 'none';
    }

    // Buttons depending on pending/solved
    var reopenDiv  = document.getElementById('modal_reopen_btn');
    var saveBtn    = document.getElementById('modal_save_btn');
    var saveLabel  = document.getElementById('modal_save_label');

    if (type === 'pending') {
        reopenDiv.innerHTML        = '';
        saveLabel.innerText        = 'Mark as Solved';
        saveBtn.style.background   = '#28a745';
    } else {
        reopenDiv.innerHTML = '<button onclick="reopenFromModal()" style="background:#ffc107; color:#333; border:none; border-radius:4px; padding:7px 16px; font-size:13px; font-weight:bold; cursor:pointer;"><span id="spin_reopen_modal"></span><i class="fas fa-undo"></i> Re-open</button>';
        saveLabel.innerText      = 'Update Answer';
        saveBtn.style.background = '#1f6feb';
    }

    document.getElementById('problemModal').style.display = 'flex';
}

function closeProblemModal() {
    document.getElementById('problemModal').style.display = 'none';
    currentProbId   = null;
    currentProbType = null;
}

// Close on backdrop click
document.getElementById('problemModal').addEventListener('click', function(e) {
    if (e.target === this) closeProblemModal();
});

function saveAnswerOnly() {
    var answer = document.getElementById('modal_answer').value.trim();
    if (answer === '') {
        pop_wrong('Please type an answer before saving.');
        return;
    }
    $('#modal_spin_answer').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.ajax({
        type: "POST",
        url: "/files/applicants/prob_controller.php",
        data: { action: 'answer', id: currentProbId, answer: answer, status: currentProbType === 'solved' ? 1 : 0 },
        dataType: "json",
        success: function(data) {
            $('#modal_spin_answer').fadeOut('fast');
            if (data.status == 200) {
                pop_up_success('Answer saved successfully.');
            } else {
                pop_wrong(data.message);
            }
        },
        error: function() {
            $('#modal_spin_answer').fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
}

function saveFromModal() {
    var answer = document.getElementById('modal_answer').value.trim();
    if (answer === '') {
        pop_wrong('Please type an answer before saving.');
        return;
    }
    $('#modal_spin').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.ajax({
        type: "POST",
        url: "/files/applicants/prob_controller.php",
        data: { action: 'answer', id: currentProbId, answer: answer, status: 1 },
        dataType: "json",
        success: function(data) {
            $('#modal_spin').fadeOut('fast');
            if (data.status == 200) {
                pop_up_success(data.message);
                closeProblemModal();
                reloadCard();
            } else {
                pop_wrong(data.message);
            }
        },
        error: function() {
            $('#modal_spin').fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
}

function reopenFromModal() {
    swal({
        title: "Re-open this problem?",
        text: "It will be moved back to Pending.",
        icon: "warning",
        buttons: true,
        dangerMode: false,
    }).then(function(confirm) {
        if (confirm) {
            $('#spin_reopen_modal').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/applicants/prob_controller.php",
                data: { action: 'reopen', id: currentProbId },
                dataType: "json",
                success: function(data) {
                    $('#spin_reopen_modal').fadeOut('fast');
                    if (data.status == 200) {
                        pop_up_success(data.message);
                        closeProblemModal();
                        reloadCard();
                    } else {
                        pop_wrong(data.message);
                    }
                },
                error: function() {
                    $('#spin_reopen_modal').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
            });
        }
    });
}

function reloadCard() {
    $('#problems-card').load(location.href + " #problems-card");
}

function pop_wrong(feedback) {
    iziToast.warning({ title: 'Wrong', message: feedback, position: 'topCenter' });
}
function pop_up_success(feedback) {
    iziToast.success({ title: 'Info:', message: feedback, position: 'topCenter' });
}
</script>