<?php
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="Student_Marks_'.date('Y-M-d_h:i', time()).'.csv"');
require_once('../../meet/con.php');

$intakeid = $_REQUEST['intakeid'];
$levelid = $_REQUEST['levelid'];
$splzid = $_REQUEST['splzid'];
$prgmodeid = $_REQUEST['prgmodeid'];
$prgid = $_REQUEST['prgid'];
$campid = $_REQUEST['campid'];

$modules = explode(',', $_REQUEST['module']);
?>
 
<table border="1" style="width: 100%; margin: 0 auto; padding: 10px; table-layout: fixed;">
    <thead>
        <tr>
            <th>No</th>
            <th>Names</th>
            <th>Reg No</th>
            <?php
            foreach ($modules as $module) {
                $m = 1;
                $MD = $conn->prepare("SELECT modules.module_code,
                                            modules.module_name,
                                            tbl_modules.cat, tbl_modules.exam,
                                            tbl_modules.module_id
                                        FROM tbl_modules
                                            INNER JOIN modules ON tbl_modules.mod_id=modules.module_id 
                                        WHERE tbl_modules.module_id=:module");
                $MD->bindParam(':module', $module, PDO::PARAM_INT);
                $MD->execute();
                $MN = $MD->fetch();
                ?>
                <th>
                    <?php echo $MN['module_name'] . ' CAT[' . $MN['cat'] . ']/EXAM[' . $MN['exam'] . ']'; ?>
                </th>
                <?php
                $m++;
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch and output student data here
        // Example: Replace the following loop with your query and fetch code
        for ($i = 1; $i <= 10; $i++) { // Example loop, replace with your actual data retrieval
            echo '<tr>';
            echo '<td>' . $i . '</td>';
            echo '<td>Student Name ' . $i . '</td>';
            echo '<td>Reg No ' . $i . '</td>';
            foreach ($modules as $module) {
                echo '<td>Data for Module ' . $module . '</td>';
            }
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
