<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Applications</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Applicants</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Pending Verifications</h4>
                                        
                                    </div>
                                    <div class="card-body">
                                        <div id="notification" class="alert" style="display: none;"></div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="applications">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Tracking Number</th>
                                                        <th>Applicant names</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th>Date created</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        // Get all duplicate codes first
                                                        $dupCodes = $conn->prepare("SELECT `code` FROM `tbl_applicants` GROUP BY `code` HAVING COUNT(*) > 1");
                                                        $dupCodes->execute();
                                                        $i=1;
                                                        
                                                        while($codeRow = $dupCodes->fetch()) {
                                                            $code = $codeRow['code'];
                                                            
                                                            // For each duplicate code, get all records and order by createdAt
                                                            $sql = $conn->prepare("SELECT * FROM `tbl_applicants` 
                                                                WHERE `code` = ? 
                                                                ORDER BY `createdAt` ASC");
                                                            $sql->execute([$code]);
                                                            
                                                            $records = $sql->fetchAll();
                                                            $count = count($records);
                                                            
                                                            // First record (oldest) keeps original code
                                                            echo "<tr class='table-primary'>";
                                                            echo "<td>{$i}</td>";
                                                            echo "<td>{$records[0]['code']} <span class='badge badge-success'>Original</span></td>";
                                                            echo "<td>{$records[0]['fname']} {$records[0]['lname']}</td>";
                                                            echo "<td>{$records[0]['phone']}</td>";
                                                            echo "<td>{$records[0]['email']}</td>";
                                                            echo "<td>" . date('Y-m-d H:i:s', strtotime($records[0]['createdAt'] . '+2 hours')) . "</td>";
                                                            echo "<td>-</td>";
                                                            echo "</tr>";
                                                            
                                                            // Newer records need new codes
                                                            for($j=1; $j<$count; $j++) {
                                                                $app = $records[$j];
                                                                echo "<tr class='table-warning'>";
                                                                echo "<td>{$i}-{$j}</td>";
                                                                echo "<td>{$app['code']} <span class='badge badge-danger'>Duplicate</span></td>";
                                                                echo "<td>{$app['fname']} {$app['lname']}</td>";
                                                                echo "<td>{$app['phone']}</td>";
                                                                echo "<td>{$app['email']}</td>";
                                                                echo "<td>" . date('Y-m-d H:i:s', strtotime($app['createdAt'] . '+2 hours')) . "</td>";
                                                                echo "<td><button class='btn btn-sm btn-primary fix-duplicate' data-id='{$app['applicant_id']}'>Fix Code</button></td>";
                                                                echo "</tr>";
                                                            }
                                                            $i++;
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </section>
        </div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $('#applications').DataTable({     
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "iDisplayLength": 25
        });
        
        // Handle fix duplicate code button click
        $(document).on('click', '.fix-duplicate', function() {
            const applicantId = $(this).data('id');
            const btn = $(this);
            
            btn.prop('disabled', true).text('Processing...');
            
            $.ajax({
                url: '../files/application/fix_duplicate_code.php',
                type: 'POST',
                data: {
                    applicant_id: applicantId
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        showNotification('success', response.message);
                        // Update the row with new code
                        const row = btn.closest('tr');
                        row.find('td:nth-child(2)').html(
                            response.new_code + ' <span class="badge badge-success">Fixed</span>'
                        );
                        btn.removeClass('btn-primary').addClass('btn-success')
                           .prop('disabled', true).text('Fixed');
                    } else {
                        showNotification('error', response.message);
                        btn.prop('disabled', false).text('Fix Code');
                    }
                },
                error: function() {
                    showNotification('error', 'An error occurred while processing your request.');
                    btn.prop('disabled', false).text('Fix Code');
                }
            });
        });
        
        function showNotification(type, message) {
            const notification = $('#notification');
            notification.removeClass('alert-success alert-danger')
                       .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                       .text(message)
                       .slideDown();
                       
            setTimeout(function() {
                notification.slideUp();
            }, 5000);
        }
    });
</script>