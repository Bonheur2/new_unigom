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
                            <h4>Categories List</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="categories">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Class</th>
                                            <th scope="col">Item</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Count</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql=$conn->prepare("SELECT catg.*,
                                                                iclass.iclass_name,
                                                                item.item_name,
                                                                brand.brand_name
                                                                FROM tbl_categories catg
                                                                    INNER JOIN tbl_items item ON catg.item_id = item.item_id
                                                                    INNER JOIN tbl_item_class iclass ON item.iclass_id = iclass.iclass_id
                                                                    LEFT JOIN tbl_brands brand ON  item.brand_id = brand.brand_id
                                                                ORDER BY catg.status ASC");
                                            $sql->execute();
                                            $i=1;
                                            while($categ = $sql->fetch()){
                                                $sql2 = $conn->prepare("SELECT COUNT(asset_id) AS assets FROM tbl_assets WHERE catg_id = ?");
                                                $sql2->execute([$categ['catg_id']]);
                                                $assets = $sql2->fetch();
                                         ?>
                                        <tr>
                                            <th scope="row"><?php echo $i++; ?></th>
                                            <td><?php echo $categ['iclass_name']; ?></td>
                                            <td><?php echo $categ['item_name']; ?> <?php echo $categ['brand_name'] != NULL?'- '.$categ['brand_name']:''; ?></td>
                                            <td><?php echo $categ['catg_name']; ?></td>
                                            <td><?php echo number_format($assets['assets']); ?></td>
                                            <th>  
                                                <div class="buttons" style="display:flex; flex-direction:row;">
                                                    <button type="button" data-id="<?php echo $categ['catg_id']; ?>" data-target="<?php echo $categ['item_name']; ?> | <?php echo $categ['catg_name']; ?>" class="btn btn-icon btn-primary btn-sm mult"><span id="spinner4_<?php echo $categ['catg_id']; ?>"></span>&nbsp;<i class="far fa-copy"></i></button>
                                                    <button type="button" data-id="<?php echo $categ['catg_id']; ?>" class="btn btn-icon btn-secondary btn-sm view"><span id="spinner5_<?php echo $categ['catg_id']; ?>"></span>&nbsp;<i class="far fa-eye"></i></button>
                                                    <a class="btn btn-light btn-sm mx-2" href="/files/assets/print/barcode/barcode?cat=<?php echo $categ['catg_id']; ?>" target="_blank"><i class="far fa-file"></i> Export</a>
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
                            <div class="form-group col-12">
                                <label>Block</label>
                                  <select class="form-control select2" style="width:100%" id="locat" name="locat">
                                <option></option>
                                <?php
                                    $sqllocat = $conn->prepare("SELECT * FROM tbl_asset_location WHERE status = 1 ORDER BY short_name ASC");
                                    $sqllocat->execute();
                                    while($locat = $sqllocat->fetch()){
                                ?>
                                <option value="<?php echo $locat['short_name']; ?>"><?php echo $locat['short_name'].' [ '.$locat['location_name'].' ] '; ?> </option>
                                <?php } ?>
                            </select>
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