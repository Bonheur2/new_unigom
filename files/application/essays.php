<?php
    $stmt00 = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted=2");
    $stmt00->execute();
    if($stmt00->rowCount()>0){
        echo '<script>window.location.href = "https://mis.act.ac.rw/applicant/edu?mis=1";</script>';
        exit;
    }
    
    $first_nmbr = 50;
    $other_fld_nmbr = 500;
?>
<style>
    textarea.essay-editor {
        width: 100%;
        min-height: 180px;
    }

    /* Skeleton loader */
    .skeleton-card {
        background: #fff;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }
    .skeleton-line {
        height: 14px;
        border-radius: 4px;
        margin-bottom: 12px;
        background: linear-gradient(90deg, #eee 25%, #f5f5f5 37%, #eee 63%);
        background-size: 400% 100%;
        animation: skeleton-shimmer 1.4s ease infinite;
    }
    .skeleton-line.title { width: 60%; height: 18px; }
    .skeleton-line.full { width: 100%; }
    .skeleton-line.short { width: 40%; }
    .skeleton-box {
        width: 100%;
        height: 140px;
        border-radius: 4px;
        margin-top: 8px;
        background: linear-gradient(90deg, #eee 25%, #f5f5f5 37%, #eee 63%);
        background-size: 400% 100%;
        animation: skeleton-shimmer 1.4s ease infinite;
    }

    @keyframes skeleton-shimmer {
        0% { background-position: 100% 50%; }
        100% { background-position: 0 50%; }
    }

    #essaySkeleton { display: block; }
    #essayRealContent { display: none; }
</style>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>7. Research Proposal</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">essays</a></div>
            </div>
        </div>
        <?php ResearchProposal($conn, $code); ?>
        <div class="alert alert-primary" role="alert">
            <strong>Note:</strong> Njala University requires that all responses be original and well-considered. You may use 
            <kbd>Ctrl</kbd> + <kbd>V</kbd> to paste your answer; however, please be careful to verify that the content you are 
            pasting is correct and directly corresponds to its respective question title before submitting. Please also respect 
            the required word limits: a maximum of <strong>50 words</strong> for the Proposed Research Title, and a maximum of 
            <strong>500 words</strong> for all other sections. We appreciate the time and effort you are dedicating to this 
            application, and we wish you the very best of luck!
        </div>
        <div class="section-body">
            
            <div class="row" id="profile">
                <p id="message1" style='display:none;'></p>

                <!-- Skeleton placeholder shown while page/editors are loading -->
                <div id="essaySkeleton" class="col-12">
                    <?php for($s = 0; $s < 3; $s++): ?>
                    <div class="skeleton-card">
                        <div class="skeleton-line title"></div>
                        <div class="skeleton-box"></div>
                        <div class="skeleton-line short" style="margin-top:14px;"></div>
                    </div>
                    <?php endfor; ?>
                </div>

                <div id="essayRealContent">
                <?php
                    $stmt=$conn->prepare("SELECT qn.*, ess.essay 
                                                FROM tbl_essay_questions qn LEFT JOIN tbl_applicant_essays ess ON qn.id = ess.question_id AND ess.stu='".$code."'
                                                ORDER BY qn.id ASC");
                    $stmt->execute();
                    
                    $ii = 1;
                ?>
                
                
                <?php while($qn = $stmt->fetch()){
                    $maxWords = ($ii == 1) ? $first_nmbr : $other_fld_nmbr;
                 ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><?php echo $ii++ . ". " . $qn['question']; ?></h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse-<?php echo $qn['id']; ?>" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse-<?php echo $qn['id']; ?>">
                            <div class="card-body">
                                
                                <form action="update_essays" method="POST" class="update_essay" data-id="<?php echo $qn['id']; ?>" class="row">
                                    <input type="hidden" name="action" value="update_essay">
                                    <input type="hidden" id="stucode" value="<?php echo $code; ?>">
                                    <input type="hidden" name="question_id" value="<?php echo $qn['id']; ?>">
                                    <div class="form-group">
                                        <textarea id="state1_<?php echo $qn['id']; ?>" class="form-control essay-editor" rows="8" placeholder="Type here" data-maxwords="<?php echo $maxWords; ?>"><?php echo htmlspecialchars($qn['essay'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        <small id="characterCount_<?php echo $qn['id']; ?>" class="form-text text-muted">0/<?php echo $maxWords; ?> words</small>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label style="opacity: 0;">Save changes</label><br/>
                                        <button type="submit" class="btn btn-primary" id="btn_<?php echo $qn['id']; ?>"><span id="spinner_<?php echo $qn['id']; ?>"></span>&nbsp;<span id="indicator_<?php echo $qn['id']; ?>">Save changes</span></button>
                                    </div>
                                    
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                     
                
                } ?>
                </div><!-- /#essayRealContent -->
                
            </div>
        </div>
        
        <?php ReportProblem($conn, $code, $thing); ?>
    </section>
    
         <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:flex-end;">
             <a href="https://mis.act.ac.rw/applicant/edu?mis=evalutionQtn" class="btn btn-primary"><i class="fas fa-arrow-left"></i>&nbsp; Back</a>
          </div>     
</div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script type="text/javascript">
  function initEssayEditors() {
      if (typeof tinymce === 'undefined') {
          return;
      }

      tinymce.init({
    selector: 'textarea.essay-editor',
    setup: function (editor) {
        editor.on('input', function () {
            let text = editor.getContent({ format: 'text' });
            let words = text.trim().split(/\s+/).filter(w => w.length > 0);
            let wordCount = words.length;

            let id = editor.id.replace('state1_', '');
            let maxWords = parseInt($('#' + editor.id).data('maxwords'), 10) || 500;

            $('#characterCount_' + id).text(wordCount + "/" + maxWords + " words");

            if (wordCount > maxWords) {
                pop_wrong("You have exceeded the " + maxWords + "-word limit!");

                let trimmed = words.slice(0, maxWords).join(" ");
                editor.setContent(trimmed);
            }
        });
    },
          menubar: false,
          branding: false,
          promotion: false,
          height: 220,
          plugins: 'lists link table code autoresize',
          toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | alignleft aligncenter alignright | link table | removeformat code',
          content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }'
      });
  }


$(document).ready(function () {
      initEssayEditors();

      // Show real content, hide skeleton once TinyMCE editors are ready (fallback: window load)
      var essayEditorsReady = false;
      function revealEssayContent() {
          if (essayEditorsReady) return;
          essayEditorsReady = true;
          $('#essaySkeleton').fadeOut(150, function () {
              $('#essayRealContent').fadeIn(200);
          });
      }

      if (typeof tinymce !== 'undefined') {
          var pendingEditors = $('textarea.essay-editor').length;
          if (pendingEditors === 0) {
              revealEssayContent();
          } else {
              tinymce.on('AddEditor', function (e) {
                  e.editor.on('init', function () {
                      pendingEditors--;
                      if (pendingEditors <= 0) revealEssayContent();
                  });
              });
          }
      }

      // Safety net in case TinyMCE fails to load/init
      $(window).on('load', function () {
          setTimeout(revealEssayContent, 2000);
      });

      $('.update_essay').on('submit', function (e) {
          e.preventDefault();

          var form = $(this);
          var qid  = form.data('id');

          if (typeof tinymce !== 'undefined') {
              tinymce.triggerSave();
          }

          var codestu = $('#stucode').val();
          var essay   = $('#state1_' + qid).val();

          $('#spinner_' + qid).html('<span class="spinner-border spinner-border-sm"></span>');
          $('#btn_' + qid).prop('disabled', true);

          $.ajax({
              type: 'POST',
              url: '../files/application/ajax_essay.php',
              data: {
                  codestu: codestu,
                  question_id: qid,
                  essay: essay
              },
              success: function (data) {
                  var msg = $.trim(data);
                  if (msg === 'Done') {
                      pop_up_success("Answer saved!");
                  } else if (msg === 'empty') {
                      pop_wrong("Please type an answer before saving.");
                  } else {
                      pop_wrong("Save failed. Please try again.");
                  }
              },
              error: function () {
                  pop_wrong("Server error while saving.");
              },
              complete: function () {
                  $('#spinner_' + qid).html('');
                  $('#btn_' + qid).prop('disabled', false);
              }
          });
      });
  });
  
  
      function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Error',
        message: feedback,
        position: 'topCenter'
      });
    }
</script>