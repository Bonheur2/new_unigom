<?php include'infrom.php'; ?>
<?php include'bar.php'; ?>
<?php  include'subbar.php'; ?>
<style>
    textarea {
        width: 100%;
        height: 100px;
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
    $limit = 1;  // Number of request per page
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $start = ($page - 1) * $limit;
    
    // Fetch total number of requests
    $stmt = $conn->prepare("SELECT id FROM tbl_requests");
    $stmt->execute();
    $totalRequests = $stmt->rowCount();
    $totalPages = ceil($totalRequests / $limit);
    
    // Fetch requests for the current page
    $result = $conn->prepare("SELECT * FROM tbl_requests ORDER BY updatedAt DESC LIMIT $start, $limit");
    $result->execute();
?>
<div class="main-content">
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
                                        <form class="respond" data-id="<?php echo $row['id']; ?>" action="respond" method="POST">
                                            <input type="hidden" name="action" value="respond">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <li class="list-group-item" style="box-shadow: 0 !important;">
                                                <div class=""> 
                                                    <h6><?php echo htmlspecialchars($row['title']); ?> <p><i><?php echo date('M j, Y, g:i a', strtotime($row['updatedAt'])); ?></i></p></h6>
                                                    <p><?php echo htmlspecialchars($row['request']); ?></p>
                                                    <div class="form-group">
                                                        <textarea name="response" placeholder="Type your response in details" minlength="3" maxlength="255"><?php echo $row['response']; ?></textarea>
                                                        <p>Recommended size: <code>2MB</code></p>
                                                        <input type="file" name="response_file" class="form-control">
                                                    </div>
                                                    <?php if($row['response_file'] != NULL){ ?>
                                                    <a href="<?php echo $row['response_file']; ?>" target="_blank">download file</a>
                                                    <?php } ?>
                                                </div>
                                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner_<?php echo $row['id']; ?>"></span>&nbsp; <span id="indicator_<?php echo $row['id']; ?>">Save</span></button>
                                                </div>
                                            </li>
                                        </form>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>No request found.</p>
                                <?php endif; ?>
                            
                                <!-- Pagination -->
                                <nav aria-label="Page navigation text-right">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="<?php if($page <= 1) echo '#'; else echo "edu?mis=reqs&page=".($page - 1); ?>">Previous</a>
                                        </li>
                                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                                                <a class="page-link" href="edu?mis=reqs&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php if($page >= $totalPages) echo 'disabled'; ?>">
                                            <a class="page-link" href="<?php if($page >= $totalPages) echo '#'; else echo "edu?mis=reqs&page=".($page + 1); ?>">Next</a>
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
    $(document).ready(function(){
        $(".respond").submit(function(e){
            e.preventDefault();
            var identifier = $(this).data('id');
            var formData = new FormData(this)
            $('#spinner_'+identifier).html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_'+identifier).html("Responding...");
            $.ajax({
                url: "/files/Requests/controller.php", 
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner_'+identifier).fadeOut('fast');
                    $('#indicator_'+identifier).html("Save");
                    if(data.status==200){
                        pop_up_success(data.message);
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }
                },error: function(){
                    $('#spinner_'+identifier).fadeOut('fast');
                    $('#indicator_'+identifier).html("Save");
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