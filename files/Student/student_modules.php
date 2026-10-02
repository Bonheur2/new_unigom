<!-- Start app main Content -->
<div class="main-content">
    <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
    <section class="section">
        <div class="section-header">
            <h3>Student - Modules</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Student information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <form id="load_modules" action="load_modules" method="POST">
                                    <div class="card-body pb-0 row">
                                        <div class="form-group  col-12 col-sm-4 col-lg-4" style="position: relative;">
                                            <label>Student ID</label><br>
                                            <input type="text" class="form-control" name="stu" id="stu">
                                            <button type="button" class="btn btn-primary btn-sm" id="search" style="position: absolute; top: 50%; right: 7%;"><span id="spinner0"><i class="fa fa-search"></i></span></button>
                                        </div>
                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="lev" hidden>
                                            <label>Level</label><br>
                                            <select class="form-control select2" style="width:100%" name="level" id="level">
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-sm-4 col-lg-4" style="padding-top:30px;" id="btn" hidden>
                                            <button type="submit" class="btn btn-sm btn-primary" id="btn"><span id="spinner20"></span>&nbsp;<span id="indicator20">Load modules</span></button>
                                        </div> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12" id="mod" hidden>
                    <div class="card" id="sample-login">
                        <div class="card-header">
                            <div class="col-9 col-md-10">
                                <h4>Registered Modules</h4>
                            </div>
                        </div>
                        <div class="card-body" id="modules"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--update modal-->
    <form action="excempt_form" method="POST" id="excempt_form">
        <div class="modal fade" tabindex="-1" role="dialog" id="excemptModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add marks /100 </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="mark_id">
                        <div class="form-group">
                            <label>Marks</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        &nbsp;<i class="fas fa-clipboard"></i>&nbsp;
                                    </div>
                                </div>
                                <input type="number" min="0" max="100" class="form-control" id="marks" placeholder="marks goes here" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm"><span id="spinner4"></span>&nbsp;<span id="indicator2">Save</span></button>
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
        
        //load levels
        $("#search").on('click', function (e) {
            var formdata = {
                stu: $("#stu").val(),
                action: 'load_levels'
            };
            $("#lev").attr('hidden', true);
            $("#btn").attr('hidden', true);
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').html('<i class="fa fa-search"></i>');
                    if(data.length>0){
                        $("#level").empty();
                        $("#level").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#level").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                        $("#lev").removeAttr('hidden');
                        $("#btn").removeAttr('hidden');
                    }else{
                        pop_wrong("No data found!");
                    }
                 },
                error:function(error){
                    $('#spinner0').html('<i class="fa fa-search"></i>');
                    pop_wrong("Something went wrong");
                }
            });
        });
        //load modules
        $("#load_modules").submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);
            $('#modules').html("");
            $('#btn').attr('disabled', true);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/load_modules.php",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#spinner20').fadeOut('fast');
                    $('#mod').removeAttr('hidden');
                    $('#btn').removeAttr('disabled');
                    $('#modules').html(data);
                 },
                error:function(error){
                    $('#spinner20').fadeOut('fast');
                    $('#btn').removeAttr('disabled');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
              
        // enroll module
        $(document).on('click','.enroll',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'enroll'
                    };
            swal({
                title: "Confirm",
                text: "This action will modify module enrollement. It can't be undone!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Marks/mark_controller.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $('#spinner3_'+data_id).fadeOut('fast');
                            if(data.status==500){
                                pop_wrong(data.message);
                            }
                            else if(data.status==200){
                                pop_up_success(data.message);
                                $("#load_modules").trigger('submit');
                            }
        				},
        				error:function(error){
        				    $('#spinner3_'+data_id).fadeOut('fast');
                            pop_wrong("Something went wrong!");
        				}
                    });
                }
               else {
                    swal("operation Cancelled!!");
                }
            });
        });
        
        // excemption modal
        $(document).on('click','.excempt',function () {
            var data_id = $(this).data('id');
            $("#mark_id").val(data_id);
            $("#excemptModal").modal('show');
        });
        // excempt module
        $("#excempt_form").submit(function (e) {
            e.preventDefault();
            var data_id = $("#mark_id").val();
            var marks = $("#marks").val();
            var getData= {
                    id: data_id,
                    marks: marks,
                    action:'excempt'
                    };
            swal({
                title: "Confirm",
                text: "This action will modify module enrollement. It can't be undone!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Marks/mark_controller.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $('#spinner4').fadeOut('fast');
                            if(data.status==500){
                                pop_wrong(data.message);
                            }
                            else if(data.status==200){
                                pop_up_success(data.message);
                                $("#load_modules").trigger('submit');
                            }
        				},
        				error:function(error){
        				    $('#spinner4').fadeOut('fast');
                            pop_wrong("Something went wrong!");
        				}
                    });
                }
               else {
                    swal("operation Cancelled!!");
                }
            });
        });
    });
</script>