<?php
require_once "../meet/bind.php";
$action=$_POST['action'];
if($action=="load_data"){
    $dep=$_POST['dep'];
    ?>
    <ul class="list-unstyled list-unstyled-border">
     <?php
        $i=0;
        $st5=$conn->prepare("SELECT * FROM books WHERE department='".$dep."' ");
        $st5->execute();
        while($data_st5=$st5->fetch()){
            $i++;
            $book_id=$data_st5['book_id'];
            $st6=$conn->prepare("SELECT COUNT(id) AS copies FROM  book_copies WHERE book_id='".$book_id."'");
            $st6->execute();
            $data_st6=$st6->fetch();
            
            $st7=$conn->prepare("SELECT COUNT(br.borrow_details_id) AS borrcopies FROM  borrowdetails br
            INNER JOIN book_copies bc ON  br.book_id=bc.id INNER JOIN books bk ON bk.book_id=bc.book_id WHERE bc.book_id='".$book_id."' 
            AND br.borrow_status='pending' ");
            $st7->execute();
            $data_st7=$st7->fetch();
            
             $st8=$conn->prepare("SELECT COUNT(br.borrow_details_id) AS lostcopies FROM  borrowdetails br
            INNER JOIN book_copies bc ON  br.book_id=bc.id INNER JOIN books bk ON bk.book_id=bc.book_id WHERE bc.book_id='".$book_id."' 
            AND br.borrow_status='lost' ");
            $st8->execute();
            $data_st8=$st8->fetch();
            ?>
         <li class="media">
        <div class="media-body">
            <div class="float-right"><div class="font-weight-600 text-muted text-small"><?php echo $data_st6['copies'] ?> Copy(s)</div></div>
            <div class="media-title"><span class="badge badge-primary"><?php echo $i ?></span> &nbsp;<?php echo $data_st5['title']; ?> </div>
            <div class="mt-1">
            <div class="budget-price">
                <div class="budget-price-square bg-primary" data-width="43%"></div>
                <div class="budget-price-label"><?php echo $data_st7['borrcopies']; ?> Borrowed</div>
            </div>
            <div class="budget-price">
                <div class="budget-price-square bg-danger" data-width="43%"></div>
                <div class="budget-price-label"><?php echo $data_st8['lostcopies']; ?> Losted</div>
            </div>
            </div>
        </div>
        </li>
        <?php }
        ?>
                               
                               
   </ul>
<?php }
?>