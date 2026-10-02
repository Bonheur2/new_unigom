<?php include'infrom.php'; ?>
<?php include'bar.php'; ?>
<?php  include'subbar.php'; ?>
<style>
    textarea {
        width: 100%;
        height: 150px;
        padding: 10px;
        border-radius: 4px;
        font-size: 16px;
        margin-bottom: 20px;
        border: 1px solid grey;
    }
    textarea:focus{
        border: 1px solid grey;
        box-shadow: none;
    }
</style>

<?php
    // Pagination logic
    $limit = 5;  // Number of request per page
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $start = ($page - 1) * $limit;
    
    // Fetch total number of requests
    $stmt = $conn->prepare("SELECT id FROM tbl_requests WHERE stu = ?");
    $stmt->execute([$identification]);
    $totalRequests = $stmt->rowCount();
    $totalPages = ceil($totalRequests / $limit);
    
    // Fetch requests for the current page
    $result = $conn->prepare("SELECT * FROM tbl_requests WHERE stu = ? ORDER BY updatedAt DESC LIMIT $start, $limit");
    $result->execute([$identification]);
?>
<div class="main-content">
    <section class="section">
       <div class="section-body">
           <div class="row">
                <div class="col-12" style="margin:auto;">
                    <form id="send_request" action="send_request" method="POST">
                        <input type="hidden" name="action" value="send_request">
                        <input type="hidden" name="stu" value="<?php echo $identification; ?>">
                        <div class="card">
                            <div class="card-header">
                                <h4>Request Application</h4>
                                <div class="card-header-action">
                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="collapse hide" id="mycard-collapse">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Choose office</label>
                                        <select class="form-control select2" style="width:100%;" name="dedicatedTo" required>
                                            <?php
                                                $sql=$conn->prepare("SELECT * FROM tbl_user_roles WHERE role_id IN (1, 2, 3)");
                                                $sql->execute();
                                                while($role=$sql->fetch()){
                                            ?>
                                            <option value="<?php echo $role['role_id']; ?>"><?php echo $role['role']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="title" placeholder="Type your request title" minlength="5" maxlength="50">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="request" id="request" placeholder="Type your request in details" minlength="20" maxlength="255"></textarea>
                                        <small id="characterCount" class="form-text text-muted">0/255 characters</small>
                                    </div>
                                    <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">submit</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section">
       <div class="section-body">
           <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body row">
                            <div class="table-responsive">
                                <?php if ($result->rowCount() > 0): ?>
                                    <ul class="list-group">
                                        <?php while($row = $result->fetch()): ?>
                                            <li class="list-group-item" style="box-shadow: 0 0px 0px rgba(255, 255, 255); !important;">
                                                <div class="">
                                                    <h6><?php echo htmlspecialchars($row['title']); ?> <p><i><?php echo date('M j, Y, g:i a', strtotime($row['updatedAt'])); ?></i></p></h6>
                                                    <p><?php echo htmlspecialchars($row['request']); ?></p>
                                                    <?php if($row['response'] != NULL || $row['response_file'] != NULL){ ?>
                                                    <div style="border: 1px solid grey; padding: 5px; border-radius: 5px;">
                                                    <?php if($row['response'] != NULL){ ?>
                                                    <p><?php echo htmlspecialchars($row['response']); ?></p>
                                                    <?php }if($row['response_file'] != NULL){ ?>
                                                    <a href="<?php echo $row['response_file']; ?>" target="_blank">download file</a>
                                                    <?php } ?> 
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>No request found.</p>
                                <?php endif; ?>
                            
                                <!-- Pagination -->
                                <nav aria-label="Page navigation text-right">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="<?php if($page <= 1) echo '#'; else echo "edu?mis=request&page=".($page - 1); ?>">Previous</a>
                                        </li>
                                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                                                <a class="page-link" href="edu?mis=request&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php if($page >= $totalPages) echo 'disabled'; ?>">
                                            <a class="page-link" href="<?php if($page >= $totalPages) echo '#'; else echo "edu?mis=request&page=".($page + 1); ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php  include'comb/orgin.php'; ?>       
<?php  include'comb/coda.php'; ?>  
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('request');
        const characterCount = document.getElementById('characterCount');

        textarea.addEventListener('input', function () {
            const currentLength = textarea.value.length;
            characterCount.textContent = `${currentLength}/255 characters`;
        });
    });
</script>
<script>
    $(document).ready(function(){
        $("#send_request").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Sending...");
            $("#sBtn").attr('disabled',true);
            $.ajax({
                url: "/files/Requests/controller.php", 
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Submit");
                    $("#sBtn").attr('disabled', false);
                    if(data.status==200){
                        $("#send_request")[0].reset();
                        pop_up_success(data.message);
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $("#sBtn").attr('disabled',false);
                    $('#indicator').html("Submit");
                    pop_wrong_("Something went wrong!");
                }
            });
        });
    });

   function pop_wrong_(feedback) {
        iziToast.warning({
            title: 'info',
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
</script>