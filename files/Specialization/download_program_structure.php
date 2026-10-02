<?php
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=Program structure.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    
    require('../../meet/con.php');
    $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
    $sql->execute();
    $data = $sql->fetch();
    $website_2='www.misnjala.edu.sl/education';

    
?>

<style>
    table tr th,td{
        padding: 0px 10px;
    }
</style>
<div class="table-responsive">
    <table>
        <tr>
            <td></td> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$data['full_name'] ?></td>
        </tr>
        <tr>
            <td></td> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$data['po_box'] ?></td>
        </tr>
        <tr>
            <td></td> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$data['phone'] ?></td>
        </tr>
        <tr>
            <td></td> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$data['email'] ?></td>
        </tr>
        <tr>
            <td></td> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$data['website'] ?></td>
        </tr>
        <tr>
            <td></td> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$website_2 ?></td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center;"><b>PROGRAM STRUCTURE</b></td>
        </tr>
    </table>
    <hr>
    
    <br>
    <table border="1">
    <center>
        <thead>
            <tr style="background-color: #87CEEB;">    
                <th>S/N</th>
                <th>CAMPUS</th>
                <th>PROGRAM TYPE</th>
                <th>SCHOOL</th>
                <th>DEPARTMENT</th>
                <th>SPECIALIZATION</th>
                <th>STUDENT NUMBER</th>
            </tr>
        </thead>									        
    </center>
    <tbody>
        <?php
        $getStudents = $conn->prepare("
            SELECT 
                c.camp_id, c.camp_full_name,
                p.prg_type_id, p.prg_type_full_name,
                s.fac_id, s.fac_full_name,
                d.dept_id, d.dept_full_name,
                sp.splz_id, sp.splz_full_name,
                COUNT(ug.reg_prg_id) AS student_count
            FROM tbl_campus c
            INNER JOIN tbl_program_type p ON c.camp_id = p.campus_id
            INNER JOIN tbl_faculty s ON p.prg_type_id = s.prg_type
            INNER JOIN tbl_department d 
                ON p.prg_type_id = d.prg_type 
                AND s.fac_id = d.fac_id
            INNER JOIN tbl_specialization sp 
                ON p.prg_type_id = sp.prg_type 
                AND s.fac_id = sp.fac_id 
                AND d.dept_id = sp.dept_id
            INNER JOIN tbl_register_program_ug ug 
                ON sp.splz_id = ug.splz_id  -- Ensure correct join condition
            GROUP BY 
                c.camp_id, c.camp_full_name,
                p.prg_type_id, p.prg_type_full_name,
                s.fac_id, s.fac_full_name,
                d.dept_id, d.dept_full_name,
                sp.splz_id, sp.splz_full_name
        ");

        try {
            $getStudents->execute();
            $i = 1;
            while ($student = $getStudents->fetch()) {
        ?>
            <tr>    
                <td><?= $i++ ?></td>
                <td><?= $student['camp_full_name'] ?></td>
                <td><?= $student['prg_type_full_name'] ?></td>
                <td><?= $student['fac_full_name'] ?></td>
                <td><?= $student['dept_full_name'] ?></td>
                <td><?= $student['splz_full_name'] ?></td>
                <td><?= $student['student_count'] ?></td> <!-- Corrected -->
            </tr>
        <?php
            }	
        } catch (PDOException $ex) {
            echo "Error: " . $ex->getMessage();
        }
        ?>
    </tbody>
</table>

</div>



<?php
    function doc_header($pdf, $conn){
//         $sql=$conn->prepare("SELECT * FROM tbl_university ORDER BY id DESC LIMIT 1");
//         $sql->execute();
//         $data=$sql->fetch();
//         /**********************Logo**************************/
// 		$pdf->Image('../..'.$data['logo'],10,10,40);
// 		$pdf->SetFont('Arial','B',15);
// 		/**********************Info-main**************************/
// 		$pdf->SetX(30);
// 		$pdf->SetFont('Arial','',13);
// 		$pdf->SetTextColor(55,24,60);
// 		$pdf->Cell(170,5,$data['full_name'],0,0,'R');
// 		$pdf->Ln(6);
// 		$pdf->SetX(30);
// 		$pdf->Cell(150,5,'',0,0,'R');
// 		$pdf->Ln(6);
// 		$pdf->SetTextColor(0,0,0);
// 		$pdf->SetFont('Arial','',8);
// 		$pdf->SetX(30);
// 		$pdf->Cell(170,5,$data['po_box'],0,0,'R');
// 		$pdf->Ln(5);
// 		/*********************Contact***************************/
// 		$pdf->SetTextColor(0,0,0);
// 		$pdf->SetFont('Arial','',8);
// 		$pdf->SetX(30);
// 		$pdf->Cell(170,5,$data['phone'],0,0,'R');
// 		$pdf->Ln(5);
// 		$pdf->SetX(30);
// 		/*********************Web&email***************************/
// 		$pdf->SetTextColor(53,75,136);
// 		$pdf->SetFont('Arial','',8);
// 		$pdf->SetX(30);
// 		$pdf->Cell(170,5,$data['email'],0,0,'R');
// 		$pdf->Ln(5);
// 		$pdf->SetX(30);
// 		$pdf->Cell(170,5,$data['website'],0,0,'R');
// 		$pdf->Ln(5);
// 	    /********************Title****************************/
// 		$pdf->SetTextColor(0,0,0);
// 		$pdf->SetFont('Arial','BU',13);
// 		$pdf->Cell(200,20,'ATTENDANCE LIST',0,0,'C');
// 		$pdf->Ln(15);
    }
?>
