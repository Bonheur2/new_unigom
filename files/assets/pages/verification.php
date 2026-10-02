<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo $title; ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#"><?php echo $title; ?></a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Asset Classes</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="categories">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Class</th>
                                            <th scope="col">Count</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql=$conn->prepare("SELECT * FROM tbl_item_class WHERE status = 1 ORDER BY iclass_name ASC");
                                            $sql->execute();
                                            $i=1;
                                            while($class = $sql->fetch()){
                                                $sql2 = $conn->prepare("SELECT COUNT(asset_id) AS assets FROM tbl_assets WHERE iclass_id = ?");
                                                $sql2->execute([$class['iclass_id']]);
                                                $assets = $sql2->fetch();
                                         ?>
                                        <tr>
                                            <th scope="row"><?php echo $i++; ?></th>
                                            <td><?php echo $class['iclass_name']; ?></td>
                                            <td><?php echo number_format($assets['assets']); ?></td>
                                            <th>  
                                                <div class="buttons" style="display:flex; flex-direction:row;">
                                                    <button type="button" data-id="<?php echo $class['iclass_id']; ?>" class="btn btn-icon btn-primary btn-sm" onclick="OpenPopupCenter('/files/assets/print/verification_list?cls=<?php echo $class['iclass_id']; ?>', 'Asset List', 800, 600);"><i class="fa fa-download"></i> List</button>
                                                    <a href="#" class="btn btn-icon btn-secondary btn-sm"><i class="fa fa-check"></i> Scan</a>
                                                    <a href="#" class="btn btn-light btn-sm mx-2"><i class="far fa-file"></i> Report</a>
                                                </div>
                                            </th>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!--generate modal-->
    <form action="#" method="POST" id="generate_form">
        <div class="modal fade" tabindex="-1" role="dialog" id="generateModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Generating <span id="catg_name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="generate">
                        <input type="hidden" id="catg_id" name="catg_id">
                        <div class="card-body pb-0 row">
                            <div class="form-group col-12">
                                <label>Number of assets</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-copy"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="number" class="form-control" name="count" placeholder="write here" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="edbtn"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end generate modal-->
    
    <!--generate modal-->
        <div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card-body pb-0 row">
                            <table class="table table-hover table-sm" border="1">
                                <tbody>
                                    <tr>
                                        <th>Classification:</th>
                                        <th id="classview"></th>
                                    </tr>
                                    <tr>
                                        <th>Item:</th>
                                        <th id="itemview"></th>
                                    </tr>
                                    <tr>
                                        <th>Brand:</th>
                                        <th id="brandview"></th>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <th id="catgview"></th>
                                    </tr>
                                    <tr>
                                        <th>Specifications:</th>
                                        <th id="specview"></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <!--end generate modal-->
</div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    } 
</script>
<script>
    $(document).ready(function(){
        $('#categories').DataTable({     
            paging: false
        });
           
        //save category
        $("#generate_form").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/assets/src/Asset.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");

                    if(data.status==200){
                        $('#categories').load(location.href + " #categories");
                        // $('#generate_form')[0].reset();
                        $('#generateModal').modal('hide');
                        pop_up_success(data.message);
                        
                    }
                    if(data.status==400){
                        pop_info(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_info("Something went wrong!");
                }
            });
        });
        
        //generate Modal
        $(document).on('click','.mult',function () {
            var data_id = $(this).data('id');
            var catg_name = $(this).data('target');
            $("#catg_id").val(data_id);
            $("#catg_name").html(catg_name);
            $('#generateModal').modal('show');
        });

        //view modal
        $(document).on('click','.view',function () {
            var data_id = $(this).data('id');
            var getData= {
                    catg_id: data_id,
                    action:'view_extended'
                };
            $('#spinner5_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/assets/src/Category.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner5_'+data_id).fadeOut('fast');
                    $("#classview").html(data.iclass_name+" - "+data.iclass_code);
                    $("#itemview").html(data.item_name);
                    $("#brandview").html(data.brand_name);
                    $("#catgview").html(data.catg_name);
                    $("#specview").html(data.specifications);

                    $('#viewModal').modal('show');
    			},
    			error:function(error){
    			    $('#spinner5_'+data_id).fadeOut('fast');
                    pop_info("Something went wrong!");
    			}
            });
        });
    });
    function pop_info(feedback) {
        iziToast.info({
            title: 'Info',
            message: feedback,
            position: 'topCenter'
        });
    }
    
    function pop_up_success(feedback) {
        iziToast.success({
            title: 'info',
            message: feedback,
            position: 'topCenter'
        });
    }
</script>