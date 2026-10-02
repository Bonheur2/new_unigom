<?php
include ('../../meet/con.php');

$keyword = $_POST['keyword'];
//Get academic data
$sql = $conn->prepare("SELECT 
                            ad.fname, 
                            ad.lname,
                            s.splz_full_name,
                            l.level_full_name  
                        FROM tbl_register_program_ug r
                            INNER JOIN tbl_admission ad ON r.reg_no = ad.reg_no
                            INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                            INNER JOIN tbl_level l ON r.level_id = l.level_id
                        WHERE r.reg_no = '".$keyword."' AND r.reg_active=1 ORDER BY r.reg_prg_id DESC LIMIT 1");
$sql->execute(); 
$data=$sql->fetch();

//Get student Photo
$sql2 = $conn->prepare("SELECT upload_doc FROM tbl_application_doc WHERE tracking_id = '".$keyword."' AND upload_doc like '%student_docs/photo%'");
$sql2->execute(); 
if($sql2->rowCount()>0){
    $photo = $sql2->fetch();
    $stuImg = $photo['upload_doc'];
}else{
    $stuImg = "student_docs/no-image.jpg";
}
?>
<?php if($sql->rowCount()>0){ ?>
<div class="col-lg-4 col-md-4 col-sm-12 col-12">
    <div class="card card-body">
        <img src="../..<?php echo $stuImg; ?>" style=" max-width: 100%; height: auto;" alt=" <?php echo $keyword ?>"><br>
        <button type="button" class="btn btn-sm btn-light" id="uploader"><i class="fas fa-upload"></i>&nbsp;change/ upload photo</button>
    </div>
    
</div>
<div class="col-lg-5 col-md-5 col-sm-12 col-12">
    <div class="card" style="border-radius:5px; border: 2px solid green;">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="article-user-details">
                        <div class="text-job"><b>Student ID</b></div>
                        <div class="user-detail-name"><a href="#"><b><?php echo $keyword; ?></b></a></div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="article-user-details">
                        <div class="text-job"><b>Names</b></div>
                        <div class="user-detail-name"><a href="#"><b><?php echo $data['fname']." ".$data['lname']; ?></b></a></div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="article-user-details">
                        <div class="text-job"><b>Specialization</b></div>
                        <div class="user-detail-name"><a href="#"><b><?php echo $data['splz_full_name']; ?></b></a></div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="article-user-details">
                        <div class="text-job"><b>Level</b></div>
                        <div class="user-detail-name"><a href="#"><b><?php echo $data['level_full_name']; ?></b></a></div>
                    </div>
                </div>
                <div class="form-group col-md-6" style="padding: 0 0 0 10px;">
                    <div class="article-user-details">
                        <label class="custom-switch btn btn-sm btn-light">
                            <input type="checkbox" class="custom-switch-input" id="mode-switcher" checked>
                            <span class="custom-switch-indicator"></span>&nbsp;Test Mode
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <?php if($sql2->rowCount()>0){ ?>
        <div class="card-footer bg-whitesmoke buttons" style="display:flex; flex-direction:row; justify-content:flex-end; padding: 0px;" id="testing">
            <button type="button" class="btn btn-sm btn-primary" onclick="OpenPopupCenter('/files/Card/IDFrontTest?stu=<?php echo $keyword; ?>', 'Student CARD', 800, 600);">View Front</button>
            <button type="button" class="btn btn-sm btn-success" onclick="OpenPopupCenter('/files/Card/IDBack?stu=<?php echo $keyword; ?>', 'Student CARD', 800, 600);">View Back</button>
        </div>
        <div class="card-footer bg-whitesmoke buttons" style="display:flex; flex-direction:row; justify-content:flex-end; padding: 0px;" id="production" hidden>
            <button type="button" class="btn btn-sm btn-primary" onclick="OpenPopupCenter('/files/Card/IDFront?stu=<?php echo $keyword; ?>', 'Student CARD', 800, 600);">Print Front</button>
            <button type="button" class="btn btn-sm btn-success" onclick="OpenPopupCenter('/files/Card/IDBack?stu=<?php echo $keyword; ?>', 'Student CARD', 800, 600);">Print Back</button>
        </div>
        <?php } else{ ?>
        <div class="card-footer bg-whitesmoke buttons" style="display:flex; flex-direction:row; justify-content:center;">
            <p style="font-size:16px;">No print options available. upload student's photo and try again!</p>
        </div>
        <?php } ?>
    </div>
</div>
<div class="col-12 col-sm-3 col-lg-3">
    <div class="card">
        <div class="card-header">
            <h4>Printed cards</h4>
        </div>
        <div class="card-body pb-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Dates</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql=$conn->prepare("SELECT * FROM tbl_student_card WHERE reg_no = ? ORDER BY print_date DESC LIMIT 6");
                        $sql->execute([$keyword]);
                        $i=1;
                        while($card = $sql->fetch()){
                     ?>
                    <tr>
                        <th scope="row"><?php echo $i++; ?></th>
                        <td><?php echo $card['print_date']; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } else{ ?>
<div class="col-md-12 alert alert-light alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert"><span>×</span></button>
        <b>No data found for # <span class="text-danger"><?php echo $keyword; ?></span></b>
    </div>
</div>
<?php } ?>