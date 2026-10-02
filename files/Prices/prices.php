<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Price Settings</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">prices</a></div>
                            </div><br>

                        </div>
                        <div class="section-header">
                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="price-tab" data-toggle="tab" href="#price" role="tab" aria-controls="price" aria-selected="true">Price Setting</a></li>
                                <li class="nav-item"><a class="nav-link" id="campus-tab" data-toggle="tab" href="#campus" role="tab" aria-controls="campus" aria-selected="false">Tolerance By campus</a></li>
                                <li class="nav-item"><a class="nav-link" id="sponsor-tab" data-toggle="tab" href="#sponsor" role="tab" aria-controls="sponsor" aria-selected="false">Tolerance by sponsor</a></li>
                            </ul>
                        </div>
                        <div class="tab-content" id="myTab3Content">
                            <div class="tab-pane fade show active" id="price" role="tabpanel" aria-labelledby="price-tab">
                                <?php include("setting_list.php"); ?>
                            </div>
                            <div class="tab-pane fade" id="campus" role="tabpanel" aria-labelledby="campus-tab">
                                <?php include("tolerance_by_campus.php"); ?>
                            </div>
                            <div class="tab-pane fade" id="sponsor" role="tabpanel" aria-labelledby="sponsor-tab">
                                <?php include("tolerance_by_sponsor.php"); ?>
                            </div>
                        </div>
                    </section>
                </div>
                
                <!--view price modal-->
                    <div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">View</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Campus</th>
                                                <th id="camp"></th>
                                            </tr>
                                            <tr>
                                                <th>Program type</th>
                                                <th id="prg_type"></th>
                                            </tr>
                                            <tr>
                                                <th>Specialization</th>
                                                <th id="splz"></th>
                                            </tr>
                                            <tr>
                                                <th>Level</th>
                                                <th id="level"></th>
                                            </tr>
                                            <tr>
                                                <th>Intake</th>
                                                <th id="intk"></th>
                                            </tr>
                                            <tr>
                                                <th>Amount to pay</th>
                                                <th id="amnt"></th>
                                            </tr>
                                            <tr>
                                                <th>Tolerance balance</th>
                                                <th id="tlbal"></th>
                                            </tr>
                                            <tr>
                                                <th>Tolerance expiration date</th>
                                                <th id="tlexp"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--end view price modal-->
                
                <!--update price modal-->
                <form action="update_form" method="POST" id="update_form">
                    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body row">
                                    <input type="hidden" name="m_to_pay_id" id="m_to_pay_id">
                                    <input type="hidden" name="action" value="update_price">
                                    <div class="form-group col-12">
                                        <label>Campus</label><br>
                                        <select class="form-control select2" style="width:100%" name="camp_id" id="e_camp_id">
                                            <?php
                                                $sql_camp=$conn->prepare("SELECT * FROM tbl_campus where camp_active=1 ORDER BY camp_full_name ASC");
                                                $sql_camp->execute();
                                                while($camp=$sql_camp->fetch()){
                                            ?>
                                            <option value="<?php echo $camp['camp_id']; ?>"><?php echo $camp['camp_full_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                        <span id="spinner-0"></span>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Program Type</label><br>
                                        <select class="form-control select2" style="width:100%" name="prg_type_Id" id="e_prg_type_Id" required></select>
                                        <span id="spinner-1"></span>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Specialization</label><br>
                                        <select class="form-control select2" style="width:100%" name="splz_id" id="e_splz_id" required></select>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Intake</label><br>
                                        <select class="form-control select2" style="width:100%" name="intake_id" id="e_intake_id" required>
                                            <?php
                                                $sql_intakes=$conn->prepare("SELECT intake_id,intake_month FROM tbl_intake WHERE status=1");
                                                $sql_intakes->execute();
                                                while($intake=$sql_intakes->fetch()){
                                            ?>
                                            <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Level</label><br>
                                        <select class="form-control select2" style="width:100%" name="level_id" id="e_level_id" required></select>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Amount to pay</label><br>
                                        <input type="number" class="form-control" name="amount" id="amount" required>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Tolerance Balance</label><br>
                                        <input type="number" class="form-control" name="tolerance_balance" id="tolerance_balance" min="1" required>
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Tolerance Expiration Date</label><br>
                                        <input type="date" class="form-control" name="tolerance_expiration_date" min="<?php echo date('Y-m-d'); ?>" id="tolerance_expiration_date" required>
                                    </div>
                                </div>
                                <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary"><span id="spinner-"></span>&nbsp;<span id="indicator-">Save changes</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end update price modal-->
                
                
                <!--update sponsor tolerance modal-->
                <form action="update_form_s" method="POST" id="update_form_s">
                    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_s">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body row">
                                    <input type="hidden" name="spon_tol_id" id="spon_tol_id">
                                    <input type="hidden" name="action" value="update_s_tolerance">
                                    <div class="form-group col-12">
                                        <label>Sponsor</label><br>
                                        <select class="form-control select2" style="width:100%" name="spon_id" id="spon_id" required>
                                            <?php
                                                $sql_spon=$conn->prepare("SELECT * FROM tbl_sponsor where status=1 ORDER BY spon_full_name ASC");
                                                $sql_spon->execute();
                                                while($spon=$sql_spon->fetch()){
                                            ?>
                                                <option value="<?php echo $spon['spon_id']; ?>"><?php echo $spon['spon_full_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group  col-12">
                                        <label>Tolerance Balance</label><br>
                                        <input type="number" class="form-control" name="tolerance_balance" id="s_tolerance_balance" min="1" required>
                                    </div>
                                    <div class="form-group  col-12">
                                        <label>Tolerance Expiration Date</label><br>
                                        <input type="date" class="form-control" name="tolerance_expiration_date" id="s_tolerance_expiration_date" min="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary"><span id="spinner-s"></span>&nbsp;<span id="indicator-s">Save changes</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end update sponsor tolerance modal-->
                
                
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#price_table').DataTable({     
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
    });
    //load program types
     $("#camp_id").change(function () {
            var formdata = {
                camp_id: $("#camp_id").val(),
                action: "load_program_types"
            };
            
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Campus/campus_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').fadeOut('fast');
                    $("#prg_type_Id").empty();
                   if(data.length>0){
                        $("#prg_type_Id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#prg_type_Id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +"</option>");
                        });
                        $("#prg").attr('hidden',false);
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

    //load update program types
     $("#e_camp_id").change(function () {
            var formdata = {
                camp_id: $("#e_camp_id").val(),
                action: "load_program_types"
            };
            $('#spinner-0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Campus/campus_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-0').fadeOut('fast');
                    $("#e_prg_type_Id").empty();
                    $("#e_splz_id").empty();
                    $("#e_level_id").empty();
                   if(data.length>0){
                        $("#e_prg_type_Id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_prg_type_Id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        
    //load specs
     $("#prg_type_Id").change(function () {
            var formdata = {
                prg_type: $("#prg_type_Id").val(),
                action: "load-specs"
            };
             $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner1').fadeOut('fast');
                    $("#splz_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                        $("#spec").attr('hidden',false);
                   }
                 },
                error:function(error){
                    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
    //load updating specs
     $("#e_prg_type_Id").change(function () {
            var formdata = {
                prg_type: $("#e_prg_type_Id").val(),
                action: "load-specs"
            };
             $('#spinner-1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-1').fadeOut('fast');
                    $("#e_splz_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-1').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
    //load levels
     $("#prg_type_Id").change(function () {
            var formdata = {
                type: $("#prg_type_Id").val(),
                action: "load_levels"
            };
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $("#level_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                        $("#lev").attr('hidden',false);
                        $("#intake").attr('hidden',false);
                        $("#amt").attr('hidden',false);
                        $("#tb").attr('hidden',false);
                        $("#ted").attr('hidden',false);
                        $("#btn").attr('hidden',false);
                   }
                 },
                error:function(error){
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
    //load updating levels
     $("#e_prg_type_Id").change(function () {
            var formdata = {
                type: $("#e_prg_type_Id").val(),
                action: "load_levels"
            };
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $("#e_level_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#e_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    pop_wrong("Something went wrong");
                }
                 
            });
        });

    //register price
    $("#save_price").submit(function(e){
            e.preventDefault();
        var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Prices/price_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_price')[0].reset();
                        pop_up_success(data.message);
                        $('#price_table').load(location.href + " #price_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });

    //view
        $(document).on('click','.view',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_price_detailed'
                    };
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Prices/price_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    $('#viewModal').modal('show');
                    $('#camp').html(data.camp_full_name);
                    $('#prg_type').html(data.prg_type_full_name);
                    $('#splz').html(data.splz_full_name);
                    $('#level').html(data.level_full_name);
                    $('#intk').html(data.intake_month);
                    $('#amnt').html(data.amount);
                    $('#tlbal').html(data.tolerance_balance);
                    $('#tlexp').html(data.tolerance_expiration_date);
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
    //pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_price'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Prices/price_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $('#updateModal').modal('show');

                    var selectElement1 = document.getElementById('e_camp_id');
                    var selectElement2 = document.getElementById('e_prg_type_Id');
                    var selectElement3 = document.getElementById('e_splz_id');
                    var selectElement4 = document.getElementById('e_level_id');
                    var selectElement5 = document.getElementById('e_intake_id');
                    
                    $("#e_prg_type_Id").empty();
                    $("#e_splz_id").empty();
                    $("#e_level_id").empty();

                    $.each(data[1], function (index, value) {
                            $("#e_prg_type_Id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +"</option>");
                        });
                    $.each(data[2], function (index, value) {
                            $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                    $.each(data[3], function (index, value) {
                            $("#e_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });

                    // // Set selected values
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].camp_id + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].prg_type_Id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].splz_id + '"]');
                    var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].level_id + '"]');
                    var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].intake_id + '"]');
                    selectedOption1.selected = true;
                    selectedOption2.selected = true;
                    selectedOption3.selected = true;
                    selectedOption4.selected = true;
                    selectedOption5.selected = true;
                    
                    selectElement1.prepend(selectedOption1);
                    selectElement2.prepend(selectedOption2);
                    selectElement3.prepend(selectedOption3);
                    selectElement4.prepend(selectedOption4);
                    selectElement5.prepend(selectedOption5);
                    
                    $("#m_to_pay_id").val(data[0].m_to_pay_id);
                    $("#amount").val(data[0].amount);
                    $("#tolerance_balance").val(data[0].tolerance_balance);
                    $("#tolerance_expiration_date").val(data[0].tolerance_expiration_date);

				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
    //update price
    $("#update_form").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner-').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator-').html("Saving...");
            $.ajax({
                url: "/files/Prices/price_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner-').fadeOut('fast');
                    $('#indicator-').html("Save changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#price_table').load(location.href + " #price_table");
                        pop_up_success(data.message);
                        
                        $("#updateModal").modal('hide');
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner-').fadeOut('fast');
                    $('#indicator-').html("Save changes");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
///////////////////////////////////////////////////////////////////////////////////////
    //register campus tolerance
    $("#save_campus_tolerance").submit(function(e){
            e.preventDefault();
        var formData = new FormData(this);
            $('#spinner_c').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_c').html("Saving...");
            $.ajax({
                url: "/files/Prices/price_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner_c').fadeOut('fast');
                    $('#indicator_c').html("Save");
                    if(data.status==200){
                        $('#save_campus_tolerance')[0].reset();
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner_c').fadeOut('fast');
                    $('#indicator_c').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
        });

////////////////////////////////////////////////////////////////////////////
    //register sponsor tolerance
    $("#save_sponsor_tolerance").submit(function(e){
            e.preventDefault();
        var formData = new FormData(this);
            $('#spinner_s').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_s').html("Saving...");
            $.ajax({
                url: "/files/Prices/price_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner_s').fadeOut('fast');
                    $('#indicator_s').html("Save");
                    if(data.status==200){
                        $('#save_sponsor_tolerance')[0].reset();
                        pop_up_success(data.message);
                        $('#sponsor_tolerance_table').load(location.href + " #sponsor_tolerance_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner_s').fadeOut('fast');
                    $('#indicator_s').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
        });
        
    //pre-update View sponsor tolerance
        $(document).on('click','.edit_s',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_sponsor_tolerance'
                    };
            $('#spinner4s_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Prices/price_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4s_'+data_id).fadeOut('fast');
                    $('#updateModal_s').modal('show');

                    var selectElement1 = document.getElementById('spon_id');
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data.spon_id + '"]');
                    selectedOption1.selected = true;
                    selectElement1.prepend(selectedOption1);
                    
                    $("#spon_tol_id").val(data.spon_tol_id);
                    $("#s_tolerance_balance").val(data.tolerance_balance);
                    $("#s_tolerance_expiration_date").val(data.tolerance_expiration_date);

				},
				error:function(error){
				    $('#spinner4s_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
    //update sponsor tolerance
    $("#update_form_s").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner-s').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator-s').html("Saving...");
            $.ajax({
                url: "/files/Prices/price_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner-s').fadeOut('fast');
                    $('#indicator-s').html("Save changes");
                    if(data.status==200){
                        $('#update_form_s')[0].reset();
                        $('#sponsor_tolerance_table').load(location.href + " #sponsor_tolerance_table");
                        pop_up_success(data.message);
                        
                        $("#updateModal_s").modal('hide');
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner-s').fadeOut('fast');
                    $('#indicator-s').html("Save changes");
                    pop_wrong("Something went wrong!");
                    
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
    
   function pop_up_success(feedback) {
    iziToast.success({
    title: 'info',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>