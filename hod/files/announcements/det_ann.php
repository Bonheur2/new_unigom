<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Announcement</h1>
        </div>
        <?php
            $id = substr($_GET['an'], 4, 1);
            $sql=$conn->prepare("SELECT an.*, r.role 
                                    FROM tbl_announcement an 
                                        INNER JOIN tbl_users u ON an.user = u.identification
                                        INNER JOIN tbl_user_roles r ON u.role_id = r.role_id
                                    WHERE an.id='".$id."'");  
            $sql->execute();
            if($sql->rowCount()>0){
                $ann=$sql->fetch();
        ?>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-7" style="margin:auto;">
                    <div class="card">
                        <div class="card-body">
                            <p><?php echo $ann['title'] ?></p>
                            <p><i><?php echo $ann['role'] ?></i></p>
                        </div>
                        <?php if($ann['file']!=''){ ?>
                        <div class="card-footer">
                            <a href="files/announcements/<?php echo $ann['file'] ?>" target="_blank">
                                Download announcement here
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </section> 
</div>