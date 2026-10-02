
<?php 
    include "meet/bind.php"; 
    include'infrom.php'; 
    include'bar.php';
    include'subbar.php';
    
    function decryptURL($em) {
        $key = "zjsDDASF#gashcs%";
        $data = base64_decode($em);
        $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = substr($data, openssl_cipher_iv_length('aes-256-cbc'));
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
    $queryString = $_SERVER['QUERY_STRING'];
    $em = substr($queryString, 10);
    $email=decryptURL($em);
    $checkAccount = $conn->prepare("SELECT * FROM tbl_users WHERE email='".$email."'");
    $checkAccount->execute();
    if($checkAccount->rowCount()>0){
        $activate = $conn->prepare("UPDATE tbl_users SET status=1 WHERE email = '".$email."'");
        if($activate->execute()){
            $message = "Email verified successfully!";    
        }
        else{
            $message = "Something went wrong!";    
        }
    }
    else{
        $message = "Invalid Account!";
    }
?>
        <!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
               <div class="section-body">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="hero align-items-center bg-light text-black">
                                <div class="hero-inner text-center">
                                    <h3><?php  echo $message ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
  <?php  include'comb/orgin.php'; ?>       
   <?php  include'comb/coda.php'; ?>     
     
  <script>


$(document).ready(function(){
   
$("#container1").addClass("disable-div");
    setTimeout(function(){
        location.href = 'auth';
     }, 10000);
});
 </script>