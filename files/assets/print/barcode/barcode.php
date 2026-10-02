<?php
    include ('../../../../meet/con.php');
?>
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
		$category = $_REQUEST['cat'];
		
        $getAssets = $conn->prepare('SELECT * FROM tbl_assets WHERE catg_id = ? AND prt = 0 AND status = 1 LIMIT 24');
        $getAssets->execute([$category]);
        $assetCount = $getAssets->rowCount();
	    $gerAbbrev=$conn->prepare("SELECT itm.item_abbrev FROM tbl_items itm INNER JOIN 
    	  tbl_categories ctg ON ctg.item_id=itm.item_id WHERE ctg.catg_id='".$category."'");
    	$gerAbbrev->execute();
	    $data_abbrev=$gerAbbrev->fetch();
	    $abbrev=$data_abbrev['item_abbrev'];
        if($assetCount > 0){
             while($asset = $getAssets->fetch()){
                echo "<p class='inline'>".bar128($asset['prefix'], stripcslashes($asset['asset_no']),$abbrev)."</p>&nbsp&nbsp";
                $stmt = $conn->prepare('UPDATE tbl_assets SET prt = 1 WHERE asset_id = ?');
                $stmt->execute([$asset['asset_id']]);
            }
        } else{
            
        }
		?>
	</div>
</body>

</html>