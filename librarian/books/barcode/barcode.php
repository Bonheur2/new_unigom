<html>
<head>
<style>
p.inline {display: inline-block; 
    height:32px;
    margin-right:3px;
    margin-top:16.5px;
}
span { font-size: 13px;}

</style>
<style type="text/css" media="print">

    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>
</head>
<body onload="window.print();">
	<div style="margin-left:3%;margin-top:-4%;margin-right:0px;">
		<?php
		include 'barcode128.php';
		$product_id = $_POST['product_id'];
	

		foreach($_POST['product_id'] as $value ){
			echo "<p class='inline'>".bar128(stripcslashes($value))."</p>&nbsp&nbsp";
		}

		?>
	</div>
</body>

</html>