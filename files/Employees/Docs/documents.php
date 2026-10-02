<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Application documents</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Documents</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>New Document</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse hide" id="mycard-collapse">
                                    <div class="card-body">
                                        <form id="save_document" action="save_document" method="POST">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Program Type</label>
                                                    <select class="form-control" name="prg_type" id="prg_type">
                                                        <?php
                                                            $sql_prg=$conn->prepare("SELECT tbl_program_type.*, tbl_campus.camp_full_name
                                                            FROM tbl_program_type INNER JOIN tbl_campus ON tbl_campus.camp_id=tbl_program_type.campus_id ");
                                                            $sql_prg->execute();
                                                            $i=1;
                                                            while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." [".$progs_faculty['camp_full_name']."]"; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Document Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-pencil"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="document_name" placeholder="Full name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>File Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                       <input type="text" class="form-control" id="file_name" placeholder="File name" required >
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>File type</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-file"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <select name="file_type" class="form-control" id="file_type" required>
                                                            <option value="image">Image</option>
                                                            <option vallue="pdf">PDF</option>
                                                        </select>
                                                            
                                                    </div>
                                                </div>
                                            <div class="form-group col-12 col-sm-3 col-lg-3">
                                                <label>&nbsp;</label>
                                                <div class="input-group">
                                                    <button type="submit" class="btn btn-primary form-control"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <h4>Required Documents</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="docs_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Document</th>
                                                    <th scope="col">Doc Type</th>
                                                    <th scope="col">Program Type</th>
                                                    <th>Campus</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT tbl_document_type.*,
                                                                        tbl_program_type.prg_type_full_name,
                                                                        tbl_campus.camp_full_name 
                                                                        FROM tbl_document_type 
                                                                        INNER JOIN tbl_program_type ON 
                                                                        tbl_document_type.prg_type=tbl_program_type.prg_type_id
                                                                        INNER JOIN tbl_campus ON tbl_campus.camp_id=tbl_program_type.campus_id
                                                                        WHERE tbl_program_type.status=1 ORDER BY tbl_document_type.prg_type ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($doc=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $doc['document_name']; ?></td>
                                                    <td><?php echo $doc['file_type']; ?></td>
                                                    <td><?php echo $doc['prg_type_full_name']; ?></td>
                                                    <td><?php echo $doc['camp_full_name']; ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <button type="button" data-id="<?php echo $doc['doc_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $doc['doc_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $doc['doc_id']; ?>" <?php echo $doc['status']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $doc['doc_id']; ?>"></span>&nbsp;
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
        </div>
           <!--edit modal-->
                <form action="update_form" method="POST" id="update_form">
                    <div class="modal fade" role="dialog" id="updateModal">
                        <div class="modal-dialog modal-lg center" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><span id="fu_name"></span></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body row">
                                    <input type="hidden" name="id_doc" id="id_doc">
                                    <input type="hidden" name="action" value="update">
                                   <div class="card-body pb-0 row">
                                    <div class="form-group col-12 col-sm-3 col-lg-3">
                                        <label>Program Type</label>
                                        <select class="form-control" name="prg_type_edit" id="prg_type_edit">
                                            <?php
                                                $sql_prg=$conn->prepare("SELECT tbl_program_type.*, tbl_campus.camp_full_name
                                                FROM tbl_program_type INNER JOIN tbl_campus ON tbl_campus.camp_id=tbl_program_type.campus_id ");
                                                $sql_prg->execute();
                                                $i=1;
                                                while($progs_faculty=$sql_prg->fetch()){
                                                    ?>
                                            <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." [".$progs_faculty['camp_full_name']."]"; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-sm-4 col-lg-4">
                                        <label>Document Name</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    &nbsp;<i class="fas fa-pencil"></i>&nbsp;
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" id="document_name_edit"  name="document_name_edit">
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-4 col-lg-4">
                                        <label>File Name</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" id="file_name_edit"  name="file_name_edit">
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-4 col-lg-4">
                                        <label>File type</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    &nbsp;<i class="fas fa-file"></i>&nbsp;
                                                </div>
                                            </div>
                                            <select name="file_type_edit" class="form-control" id="file_type_edit">
                                                <option value="image">Image</option>
                                                <option vallue="pdf">PDF</option>
                                            </select>
                                                
                                        </div>
                                    </div>
                                </div>
                                    
                                     </div>
                                <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm" id="uBtn"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end update modal-->
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
document.getElementById('file_name').addEventListener('keydown', function(e) {
    if (e.key === ' ') {
      e.preventDefault();
    }
  });
    $('#docs_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
//save document
    $("#save_document").submit(function(e){
            e.preventDefault();
    
        var formData = {
            docu:$("#document_name").val(),
            prg_type:$("#prg_type").val(),
            file_name:$("#file_name").val(),
            file_type:$("#file_type").val(),
            action:'register'
                };
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "../files/Docs/document_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_document')[0].reset();
                        pop_up_success(data.message);
                        $('#docs_table').load(location.href + " #docs_table");
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
      
        // disable doc
       $(document).on('click', '.del', function() {
            var data_id = $(this).data('id');
            var getData= {
                    did: data_id,
                    action:'changestatus'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this level's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../files/Docs/document_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message); 
                    }
                    else if(data.status==200){
                      pop_up_success(data.message); 
                    //   $('#docs_table').load(location.href + " #docs_table");
                    window.location.reload();
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong");
				}
            });
            }
           else {
                swal("Operation cancelled!!");
            }
        });
        });
    
   $(document).on('click', '.edit', function() {
       var id=$(this).data('id');
       $('#spinner4_'+id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
       var formData={
           id:id,
           action:"Update_doc"
       }
        $.ajax({
                type: "POST",
                url: "../files/Docs/document_controller.php",
                data: formData,
                dataType:"json",
                success:function(data){
                $("#id_doc").val(id);
                $('#spinner4_'+id).fadeOut('fast'); 
                 var selectPrgtype = $("#prg_type_edit");
                 selectPrgtype.val(data.prg_type);
                 selectPrgtype.prepend(selectPrgtype.find("option[value='" + data.prg_type + "']"));
                 $("#document_name_edit").val(data.document_name);
                 $("#file_name_edit").val(data.file_name);
                 var selectfileType = $("#file_type_edit");
                 selectfileType.val(data.file_type);
                 selectfileType.prepend(selectfileType.find("option[value='" + data.file_type + "']"));
                
                },
				error:function(error){
				    $('#spinner4_'+id).fadeOut('fast');
                    pop_wrong("Something went wrong");
				}
            });
       $("#updateModal").modal('show');
   });
        
   // submit edit form 
        
    $("#update_form").submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $("#spinner2").html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
     $.ajax({
      url: "../files/Docs/document_controller.php",
      type: "POST",
      data: formData,
      dataType: "JSON",
      contentType: false,
      processData: false,
      success: function (data) {
       $("#spinner2").fadeOut('fast');
       if(data.status==200) {
         pop_up_success(data.message);  
         $("#updateModal").modal('hide');
          $('#docs_table').load(location.href + " #docs_table"); 
       }  
       if(data.status==500){
         pop_wrong(data.message);    
       }
      },
      error: function () {
       $("#spinner2").fadeOut('fast');
        showErrorToast("Something Went Wrong !!");
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