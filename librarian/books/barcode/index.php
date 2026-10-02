<?php
include ('../../../meet/con.php');
?>

       <html>
	<head>

	</head>
<body>
  <div class="main-content" id="books">
            <section class="section">
              </section>  
<div class="container" style="text-align:center">
  <div class="card">
  	<h2 class="text-center">BARCODE GENERATE</h2>
  	<form class="form-horizontal" method="post" action="books/barcode/barcode" target="_blank">
  	<div class="row">
  	  
  	    <?php
  	    $i=1;
  	    function generate_token() {
            $chars = "0123456789";
            $tok = substr(str_shuffle($chars), 0, 7);
             return $tok;
                }
  	    while($i<25){
  	        $pass = generate_token();
  	        $y=date('y');
            $m=date('m');
  	     ?> 
  	     <div class="col-lg-4 col-md-4">
  	       <div class="form-group">
      <label class="control-label col-sm-12 col-md-12 col-lg-12" for="product_id">BarCode No: <?php echo $i ?></label>
      <div class="col-sm-10">
         <input autocomplete="OFF" type="text" class="form-control" id="product_id" name="product_id[]" value="<?php echo $pass; ?>" readonly>
      </div>
    </div> 
  	    </div>
  	 <?php $i++; } ?>
  	    
    </div>
    <div class="form-group"> 
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3">
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="">
        <button type="submit" class="btn btn-info">Generate</button>
      </div>  
        </div>
    </div>
    
      
    </div>
  </form>
  </div>
</div>
</div>
</body>
</html>
