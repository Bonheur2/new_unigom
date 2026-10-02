<?php
include ('../../../meet/con.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$keyword = $_POST['keyword'];
$sql = $conn->prepare("SELECT * FROM tbl_staff_info WHERE (staff_id = '".$keyword."' OR email='".$keyword."' OR 	phone='".$keyword."')  ORDER BY id DESC LIMIT 1");
$sql->execute(); 
$data=$sql->fetch();

?>
<?php if($sql->rowCount()>0){ ?>
<div class="col-lg-4 col-md-4 col-sm-12 col-12">
    <div class="card card-body">
        <img src="/files/employees/<?php echo $data['staff_image']; ?>" style=" max-width: 100%; height: auto;" alt=" <?php echo $keyword ?>"><br>
        <button type="button" class="btn btn-sm btn-light" id="uploader"><i class="fas fa-upload"></i>&nbsp;change/ upload photo</button>
    </div>
    
</div>
<div class="col-lg-8 col-md-8 col-sm-12 col-12">
    <div class="card" style="border-radius:5px; border: 2px solid green;">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <div class="article-user-details">
                        <div class="text-job"><b>Staff has</b></div>
                        <div class="user-detail-name"><a href="#"><b><?php echo $data['staff_id']; ?></b></a></div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="article-user-details">
                        <div class="text-job"><b>Names</b></div>
                        <div class="user-detail-name"><a href="#"><b><?php echo $data['family_name']." ".$data['first_name']; ?></b></a></div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($data['staff_image']!=''){ ?>
        <div class="card-footer bg-whitesmoke buttons" style="display:flex; flex-direction:row; justify-content:flex-end;">
            <button type="button" class="btn btn-sm btn-info" onclick="OpenPopupCenter('/files/Card/staff/IDFront_preview?st=<?php echo $data['staff_id']; ?>', 'Staff CARD', 800, 600);">Pre-View</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="OpenPopupCenter('/files/Card/staff/IDFront?st=<?php echo $data['staff_id']; ?>', 'Staff CARD', 800, 600);">Print Front</button>
            <button type="button" class="btn btn-sm btn-success" onclick="OpenPopupCenter('/files/Card/staff/IDBack?st=<?php echo $data['staff_id']; ?>', 'Staff CARD', 800, 600);">Print Back</button>
        </div>
        <?php } else{ ?>
        <div class="card-footer bg-whitesmoke buttons" style="display:flex; flex-direction:row; justify-content:center;">
            <p style="font-size:16px;">No print options available. upload owner's photo and try again!</p>
        </div>
        <?php } ?>
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