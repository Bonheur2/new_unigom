<?php
    // Pagination logic
    $limit = 5;  // Number of suggestions per page
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $start = ($page - 1) * $limit;
    
    // Fetch total number of suggestions
    $stmt = $conn->prepare("SELECT id FROM tbl_suggestions");
    $stmt->execute();
    $totalSuggestions = $stmt->rowCount();
    $totalPages = ceil($totalSuggestions / $limit);
    
    // Fetch suggestions for the current page
    $result = $conn->prepare("SELECT * FROM tbl_suggestions ORDER BY time_posted DESC LIMIT $start, $limit");
    $result->execute();
?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Suggestions</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">List</a></div>
            </div>
        </div>
       <div class="section-body">
           <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body row">
                            <div class="table-responsive">
                                <?php if ($result->rowCount() > 0): ?>
                                    <ul class="list-group">
                                        <?php while($row = $result->fetch()): ?>
                                            <li class="list-group-item">
                                                <div class="">
                                                    <div><?php echo htmlspecialchars($row['suggestion']); ?></div>
                                                    <div class="text-black"><b><?php echo date('M j, Y, g:i a', strtotime($row['time_posted'])); ?></b></div>
                                                </div>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>No suggestions found.</p>
                                <?php endif; ?>
                            
                                <!-- Pagination -->
                                <nav aria-label="Page navigation text-right">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="<?php if($page <= 1) echo '#'; else echo "edu?mis=sbox&page=".($page - 1); ?>">Previous</a>
                                        </li>
                                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                                                <a class="page-link" href="edu?mis=sbox&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php if($page >= $totalPages) echo 'disabled'; ?>">
                                            <a class="page-link" href="<?php if($page >= $totalPages) echo '#'; else echo "edu?mis=sbox&page=".($page + 1); ?>">Next</a>
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
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $('#suggestions').DataTable({ 
            "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 10
        });
    });
</script>