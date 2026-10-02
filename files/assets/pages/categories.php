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
                            <h4>New Item Category</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse">
                            <div class="card-body">
                                <form id="save_category" action="#" method="POST">
                                    <input type="hidden" name="action" value="register">
                                    <div class="card-body pb-0 row">
                                        <div class="form-group col-12 col-md-6 col-lg-6">
                                            <label>Item Class</label>
                                            <select class="form-control select2" style="width:100%" id="iclass_id">
                                                <option></option>
                                                <?php
                                                    $sql = $conn->prepare("SELECT * FROM tbl_item_class WHERE status = 1 ORDER BY iclass_name ASC");
                                                    $sql->execute();
                                                    while($iclass = $sql->fetch()){
                                                ?>
                                                <option value="<?php echo $iclass['iclass_id']; ?>"><?php echo $iclass['iclass_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-6">
                                            <label>Brand</label>
                                            <select class="form-control select2" style="width:100%" id="brand_id">
                                                <option value="0">Unknown</option>
                                                <?php
                                                    $sql = $conn->prepare("SELECT * FROM tbl_brands WHERE status = 1 ORDER BY brand_name ASC");
                                                    $sql->execute();
                                                    while($brand = $sql->fetch()){
                                                ?>
                                                <option value="<?php echo $brand['brand_id']; ?>"><?php echo $brand['brand_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <span id="spinner0"></span>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-6" id="item" hidden>
                                            <label>Item</label>
                                            <select class="form-control select2" style="width:100%" name="item_id" id="item_id" required>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-6" id="cn" hidden>
                                            <label>Category Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="catg_name" placeholder="Name goes here" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-12" id="sn" hidden>
                                            <label>Specifications / Comment</label>
                                            <div class="input-group">
                                                <textarea class="form-control col-12" name="specifications" placeholder="Specifications goes here" maxLength="160"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12" id="dbtn" hidden>
                                            <div class="input-group" style="display:flex; flex-direction:row; justify-content:center;">
                                                <button type="submit" class="btn btn-primary"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                                            <th scope="col">Name</th>
                                            <th scope="col">Item</th>
                                            <th scope="col">Class</th>
                                            <th scope="col">Brand</th>
                                            <th scope="col">Specifications</th>
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
                                         ?>
                                        <tr>
                                            <th scope="row"><?php echo $i++; ?></th>
                                            <td><?php echo $categ['catg_name']; ?></td>
                                            <td><?php echo $categ['item_name']; ?></td>
                                            <td><?php echo $categ['iclass_name']; ?></td>
                                            <td><?php echo $categ['brand_name']; ?></td>
                                            <td><?php echo $categ['specifications']; ?></td>
                                            <th>  
                                                <div class="buttons" style="display:flex; flex-direction:row;">
                                                    <button type="button" data-id="<?php echo $categ['catg_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $categ['catg_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>edit&nbsp;</button>
                                                    <label class="custom-switch btn btn-light btn-sm">
                                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $categ['catg_id']; ?>" <?php echo $categ['status']==1?'checked':''; ?>>
                                                        <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $categ['catg_id']; ?>"></span>&nbsp;
                                                    </label>
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
    
    <!--update modal-->
    <form action="update_form" method="POST" id="update_form">
        <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Updating <span id="catg_name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="catg_id" name="catg_id">
                        <input type="hidden" name="action" value="update">
                        <div class="card-body pb-0 row">
                            <div class="form-group col-12">
                                <label>Item Class</label>
                                <select class="form-control select2" style="width:100%" id="e_iclass_id">
                                    <?php
                                        $sql = $conn->prepare("SELECT * FROM tbl_item_class WHERE status = 1 ORDER BY iclass_name ASC");
                                        $sql->execute();
                                        while($iclass = $sql->fetch()){
                                    ?>
                                    <option value="<?php echo $iclass['iclass_id']; ?>"><?php echo $iclass['iclass_name']; ?> </option>
                                    <?php } ?>
                                </select>
                                
                            </div>
                            <div class="form-group col-12" id="ebrd">
                                <label>Brand</label>
                                <select class="form-control select2" style="width:100%" id="e_brand_id" required>
                                    <option value="0">Unknown</option>
                                    <?php
                                        $sql = $conn->prepare("SELECT * FROM tbl_brands WHERE status = 1 ORDER BY brand_name ASC");
                                        $sql->execute();
                                        while($brand = $sql->fetch()){
                                    ?>
                                    <option value="<?php echo $brand['brand_id']; ?>"><?php echo $brand['brand_name']; ?> </option>
                                    <?php } ?>
                                </select>
                                &nbsp;<span id="spinner-0"></span>
                            </div>
                            <div class="form-group col-12" id="eitm">
                                <label>Item</label>
                                <select class="form-control select2" style="width:100%" name="item_id" id="e_item_id" required>
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <label>Category Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="e_catg_name" name="catg_name" placeholder="Name goes here" required>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label>Specifications / Comment</label>
                                <div class="input-group">
                                    <textarea class="form-control col-12" id="e_specifications" name="specifications" placeholder="Specifications goes here" maxLength="160"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="edbtn"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal-->
</div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $('#categories').DataTable({     
            paging: false
        });
           
        //load items
        $("#iclass_id, #brand_id").change(function () {
            var iclass_id = $("#iclass_id").val();
            var brand_id = $("#brand_id").val();
            var formdata = {
                iclass: iclass_id,
                brand: brand_id,
                action: "load_items"
            };
            
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $('#cn').attr('hidden', true);
            $('#sn').attr('hidden', true);
            $('#item').attr('hidden', true);
            $('#dbtn').attr('hidden', true);
            
            $.ajax({
                type: "POST",
                url: "/files/assets/src/Class.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').fadeOut('fast');
                    $("#item_id").empty();
                    if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#item_id").append("<option value='" + value.item_id + "'>" + value.item_name +"</option>");
                        });
                        $('#cn').removeAttr('hidden');
                        $('#sn').removeAttr('hidden');
                        $('#item').removeAttr('hidden');
                        $('#dbtn').removeAttr('hidden');
                    }else{
                        pop_info("No data found");
                    }
                },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_info("Something went wrong!");
                }
            });
        });
    
        //load updating item
        $("#e_iclass_id, #e_brand_id").change(function () {
            var iclass_id = $("#e_iclass_id").val();
            var brand_id = $("#e_brand_id").val();
            var formdata = {
                iclass: iclass_id,
                brand: brand_id,
                action: "load_items"
            };
            
            $('#spinner-0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $('#edbtn').attr('hidden', true);
            
            $.ajax({
                type: "POST",
                url: "/files/assets/src/Class.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-0').fadeOut('fast');
                    $("#e_item_id").empty();
                    if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#e_item_id").append("<option value='" + value.item_id + "'>" + value.item_name +"</option>");
                        });
                        $('#edbtn').removeAttr('hidden');
                    }else{
                        pop_info("No data found");
                    }
                },
                error:function(error){
                    $('#spinner-0').fadeOut('fast');
                    pop_info("Something went wrong!");
                }
            });
        });
        
        //save category
        $("#save_category").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/assets/src/Category.php",
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
          
        // manage category
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    catg_id: data_id,
                    action:'manage'
                };
            swal({
            title: "Are you sure?",
            text: "You are about to change this category's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/assets/src/Category.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $('#spinner3_'+data_id).fadeOut('fast');
                            if(data.status==400){
                               pop_info(data.message);
                            }
                            else if(data.status==200){
                               pop_up_success(data.message);
                               $('#categories').load(location.href + " #categories");
                            }
            			},
            			error:function(error){
            			    $('#spinner3_'+data_id).fadeOut('fast');
            			    pop_info("Something went wrong!");
            			}
                    });
                }
                else {
                    swal("Operation cancelled!!");
                }
            });
        });
            
        //pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    catg_id: data_id,
                    action:'view'
                };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/assets/src/Category.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    
                    $("#catg_id").val(data_id);
                    $("#e_catg_name").val(data[0].catg_name);
                    $("#e_specifications").val(data[0].specifications);
                    
                    var selectElement1 = document.getElementById('e_iclass_id');
                    var selectElement2 = document.getElementById('e_brand_id');
                    var selectElement3 = document.getElementById('e_item_id');
                    
                    $("#e_item_id").empty();
    
                    $.each(data[1], function (index, value) {
                        $("#e_item_id").append("<option value='" + value.item_id + "'>" + value.item_name +"</option>");
                    });
                    // Set selected values
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].iclass_id + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].brand_id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].item_id + '"]');
                    
                    if(selectedOption1){
                        selectedOption1.selected = true;
                        selectElement1.prepend(selectedOption1);
                    }
                    if(selectedOption2){
                        selectedOption2.selected = true;
                        selectElement2.prepend(selectedOption2);
                    }
                    if(selectedOption3){
                        selectedOption3.selected = true;
                        selectElement3.prepend(selectedOption3);
                    }
                    
                    var selectedOptionText = selectedOption3.textContent;
                    $("#catg_name").html(data[0].selectedOption1+' | '+selectedOptionText);
                    $('#updateModal').modal('show');
    			},
    			error:function(error){
    			    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_info("Something went wrong!");
    			}
            });
        });
            
        //update category
        $("#update_form").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/assets/src/Category.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        $('#categories').load(location.href + " #categories");
                    }
                    if(data.status==400){
                        pop_info(data.message); 
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_info("Something went wrong!")
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