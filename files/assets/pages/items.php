<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo $title; ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo 'edu?mis=1'; ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Item</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Item</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse">
                            <!--<div class="card-body row">-->
                                <form id="save_item" action="#" method="POST">
                                    <input type="hidden" name="action" value="register">
                                    <div class="card-body pb-0 row">
                                        <div class="form-group col-12 col-md-6 col-lg-3">
                                            <label>Item Class</label>
                                            <select  class="form-control select2" style="width:100%" name="iclass_id" required>
                                                <?php
                                                    $sql = $conn->prepare("SELECT * FROM tbl_item_class WHERE status = 1");
                                                    $sql->execute();
                                                    while($class = $sql->fetch()){
                                                        ?>
                                                <option value="<?php echo $class[ 'iclass_id']; ?>"><?php echo $class[ 'iclass_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-3">
                                            <label>Brand</label>
                                            <select  class="form-control select2" style="width:100%" name="brand_id">
                                                <option value="0">Unknown</option>
                                                <?php
                                                    $sql = $conn->prepare("SELECT * FROM tbl_brands WHERE status = 1");
                                                    $sql->execute();
                                                    while($brand = $sql->fetch()){
                                                        ?>
                                                <option value="<?php echo $brand[ 'brand_id']; ?>"><?php echo $brand[ 'brand_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-4">
                                            <label>Item Name</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="item_name" placeholder="Item name goes here" required>
                                            </div>
                                        </div>
                                         <div class="form-group col-12 col-md-6 col-lg-4">
                                            <label>Item Abbrev</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" name="item_abb" id="item_abb" placeholder="Abbreviation name goes here" pattern="[A-Za-z]{1,2}" title="Please enter 1 to 2 letters only" required style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-md-6 col-lg-2">
                                            <label>&nbsp;</label>
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary col-12 "><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <!--</div>-->
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card" id="sample-login">
                        <div class="card-header">
                            <h4>Registered Items</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="items">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Item Class</th>
                                        <th scope="col">Brand</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sql=$conn->prepare("SELECT 
                                                                    i.*,
                                                                    c.iclass_name,
                                                                    b.brand_name
                                                                FROM tbl_items i
                                                                    INNER JOIN tbl_item_class c ON i.iclass_id = c.iclass_id 
                                                                    LEFT JOIN tbl_brands b ON i.brand_id = b.brand_id 
                                                                ORDER BY i.status ASC");
                                        $sql->execute();
                                        $i=1;
                                        while($item=$sql->fetch()){
                                        ?>
                                    <tr>
                                        <td scope="row"><?php echo $i++; ?></th>
                                        <td><?php echo $item['item_name']; ?></td>
                                        <td><?php echo $item['iclass_name']; ?></td>
                                        <td><?php echo $item['brand_id'] != 0?$item['brand_name']:'unknown'; ?></td>
                                        <th>  
                                            <div class="buttons row">
                                                <button type="button" data-id="<?php echo $item['item_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $item['item_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp; edit</button>
                                                <label class="custom-switch btn btn-light btn-sm">
                                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $item['item_id']; ?>" <?php echo $item['status']==1?'checked':''; ?>>
                                                    <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $item['item_id']; ?>"></span>&nbsp;
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
                        <h5 class="modal-title">Updating <span id="it_name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="item_id" name="item_id">
                        <input type="hidden" name="action" value="update">
                        <div class="form-group">
                            <label>Item Class</label>
                                <select class="form-control select2" style="width:100%" name="iclass_id" id="iclass_id" required>
                                <?php
                                    $sql = $conn->prepare("SELECT * FROM tbl_item_class WHERE status = 1");
                                    $sql->execute();
                                    while($class = $sql->fetch()){
                                ?>
                                <option value="<?php echo $class [ 'iclass_id']; ?>"><?php echo $class [ 'iclass_name']; ?> </option>
                                <?php } ?>
                                </select>
                        </div>
                        <div class="form-group">
                            <label>Brand</label>
                            <select  class="form-control select2" style="width:100%" name="brand_id" id="brand_id">
                                <option value="0">Unknown</option>
                                <?php
                                    $sql = $conn->prepare("SELECT * FROM tbl_brands WHERE status = 1");
                                    $sql->execute();
                                    while($brand = $sql->fetch()){
                                        ?>
                                <option value="<?php echo $brand[ 'brand_id']; ?>"><?php echo $brand[ 'brand_name']; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Item Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        &nbsp;<i class="fas fa-info"></i>&nbsp;
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Item name goes here" required>
                            </div>
                        </div>
                         <div class="form-group">
                        <label>Item Abbrev</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    &nbsp;<i class="fas fa-info"></i>&nbsp;
                                </div>
                            </div>
                            <input type="text" class="form-control" name="item_abbE" id="item_abbE" placeholder="Abbreviation name goes here" pattern="[A-Za-z]{1,2}" title="Please enter 1 to 2 letters only" required style="text-transform: uppercase;">
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
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
    $('#items').DataTable({
        paging: false
    });
    //save Item
    $("#save_item").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/assets/src/Item.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_item')[0].reset();
                        pop_up_success(data.message);
                        $('#items').load(location.href + " #items");
                    }
                    if(data.status==400){
                        pop_wrong(data.message); 
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
      
        // manage Item
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                item_id: data_id,
                action:'manage'
            };
            swal({
            title: "Are you sure?",
            text: "You are about to change this item's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "/files/assets/src/Item.php",
                    data: getData,
                    dataType:"json",
                    success:function(data){
                        $('#spinner3_'+data_id).fadeOut('fast');
                        if(data.status==400){
                            pop_wrong(data.message); 
                        }
                        else if(data.status==200){
                           pop_up_success(data.message); 
                           $('#items').load(location.href + " #items");
                        }
    				},
    				error:function(error){
    				    $('#spinner3_'+data_id).fadeOut('fast');
                        pop_wrong("Something went wrong");
    				}
                });
                }
               else {
                    swal("operation cancelled!!");
                }
            });
        });
        
        //pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                item_id: data_id,
                action:'view'
            };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/assets/src/Item.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#item_id").val(data_id);
                    $("#item_name").val(data.item_name);
                    $("#item_abbE").val(data.item_abbrev);
                    var selectElement = document.getElementById('iclass_id');
                    var selectedOption = selectElement.querySelector('option[value="' + data.iclass_id + '"]');
                    if (selectedOption) {
                        selectedOption.selected = true;
                        selectElement.prepend(selectedOption);
                        var selectedOptionText = selectedOption.textContent;
                        $("#it_name").html(data.item_name+" | "+selectedOptionText);
                    }
                    
                    var selectElement2 = document.getElementById('brand_id');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data.brand_id + '"]');
                    if (selectedOption2) {
                        selectedOption2.selected = true;
                        selectElement2.prepend(selectedOption2);
                    }
                    
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //update Item
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/assets/src/Item.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#updateModal').modal('hide');
                        pop_up_success(data.message);
                        $('#items').load(location.href + " #items");
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message); 
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
    });
    
    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'Wrong',
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