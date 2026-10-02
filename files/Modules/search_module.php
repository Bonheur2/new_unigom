<?php
include ('../../meet/con.php');


if (isset($_POST['download_excel'])) {
    // Output headers for Excel file download
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=table_data.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Start table output for Excel
    echo "<table border='1'>
        <thead>
            <tr>
                <th>Faculty</th>
                <th>Department</th>
                <th>dept_id</th>
                <th>Module name</th>
                <th>Module code</th>
                <th>Level</th>`
                <th>module_credits</th>
                <th>credited_module</th>
                <th>Term</th>
                <th>Credit</th>
                <th>CAT</th>
                <th>EXAM</th>
            </tr>
        </thead>
        <tbody>";

    // Database query logic to fetch the same data
    include 'your_database_connection_file.php'; // Replace with your database connection file
    $select = "SELECT * FROM tbl_faculty WHERE prg_type='1'";
    $cselect = $conn->prepare($select);
    $cselect->execute();

    foreach ($cselect as $row_cselect) {
        $select_depart = "SELECT * FROM tbl_department WHERE fac_id='" . $row_cselect['fac_id'] . "' AND prg_type='" . $row_cselect['prg_type'] . "'";
        $cselect_depart = $conn->prepare($select_depart);
        $cselect_depart->execute();

        foreach ($cselect_depart as $row_cselect_depart) {
            $select_module = "SELECT * FROM modules1 WHERE dept_id='" . $row_cselect_depart['dept_id'] . "' AND prg_type='" . $row_cselect_depart['prg_type'] . "'";
            $cselect_module = $conn->prepare($select_module);
            $cselect_module->execute();

            foreach ($cselect_module as $row_cselect_module) {
                $select_assigned = "SELECT * FROM tbl_modules1 WHERE mod_id='" . $row_cselect_module['module_id'] . "' AND prg_type='".$row_cselect_module['prg_type']."' AND fac_id='".$row_cselect['fac_id']."' AND dept_id='".$row_cselect_module['dept_id']."'";
                $cselect_assigned = $conn->prepare($select_assigned);
                $cselect_assigned->execute();

                foreach ($cselect_assigned as $row_cselect_assigned) {
                    echo "<tr>
                        <td>" . $row_cselect['fac_full_name'] . "</td>
                        <td>" . $row_cselect_depart['dept_full_name'] . "</td>
                        <td>" . $row_cselect_depart['dept_id'] . "</td>
                        <td>" . $row_cselect_module['module_name'] . "</td>
                        <td>" . $row_cselect_module['module_code'] . "</td>
                        <td>" . $row_cselect_assigned['level_id'] . "</td>
                        <td>" . $row_cselect_assigned['module_credits'] . "</td>
                        <td>" . $row_cselect_assigned['credited_module'] . "</td>
                        <td>" . $row_cselect_assigned['term_id'] . "</td>
                        <td>" . $row_cselect_assigned['module_credits'] . "</td>
                        <td>" . $row_cselect_assigned['cat'] . "</td>
                        <td>" . $row_cselect_assigned['exam'] . "</td>
                    </tr>";
                }
            }
        }
    }

    echo "</tbody></table>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table with Export to Excel</title>
</head>
<body>
    <form method="POST">
        <button type="submit" name="download_excel">Download as Excel</button>
    </form>

    <table border="2">
        <thead>
            <th>Faculty</th>
            <th>Department</th>
            <th>dept_id</th>
            <th>Module name</th>
            <th>Module code</th>
            <th>Level</th>
            <th>module_credits</th>
            <th>credited_module</th>
            <th>Term</th>
            <th>Credit</th>
            <th>CAT</th>
            <th>EXAM</th>
        </thead>
        <tbody>
            <?php
            // Display data in the table
            $select = "SELECT * FROM tbl_faculty WHERE prg_type='1'";
            $cselect = $conn->prepare($select);
            $cselect->execute();

            foreach ($cselect as $row_cselect) {
                $select_depart = "SELECT * FROM tbl_department WHERE fac_id='" . $row_cselect['fac_id'] . "' AND prg_type='" . $row_cselect['prg_type'] . "'";
                $cselect_depart = $conn->prepare($select_depart);
                $cselect_depart->execute();

                foreach ($cselect_depart as $row_cselect_depart) {
                    $select_module = "SELECT * FROM modules1 WHERE dept_id='" . $row_cselect_depart['dept_id'] . "' AND prg_type='" . $row_cselect_depart['prg_type'] . "'";
                    $cselect_module = $conn->prepare($select_module);
                    $cselect_module->execute();

                    foreach ($cselect_module as $row_cselect_module) {
                        $select_assigned = "SELECT * FROM tbl_modules1 WHERE mod_id='" . $row_cselect_module['module_id'] . "' AND prg_type='".$row_cselect_module['prg_type']."' AND fac_id='".$row_cselect['fac_id']."' AND dept_id='".$row_cselect_module['dept_id']."' ";
                        $cselect_assigned = $conn->prepare($select_assigned);
                        $cselect_assigned->execute();

                        foreach ($cselect_assigned as $row_cselect_assigned) {
                            echo "<tr>
                                <td>" . $row_cselect['fac_full_name'] . "</td>
                                <td>" . $row_cselect_depart['dept_full_name'] . "</td>
                                <td>" . $row_cselect_depart['dept_id'] . "</td>
                                <td>" . $row_cselect_module['module_name'] . "</td>
                                <td>" . $row_cselect_module['module_code'] . "</td>
                                <td>" . $row_cselect_assigned['level_id'] . "</td>
                                <td>" . $row_cselect_assigned['module_credits'] . "</td>
                                <td>" . $row_cselect_assigned['credited_module'] . "</td>
                                <td>" . $row_cselect_assigned['term_id'] . "</td>
                                <td>" . $row_cselect_assigned['module_credits'] . "</td>
                                <td>" . $row_cselect_assigned['cat'] . "</td>
                                <td>" . $row_cselect_assigned['exam'] . "</td>
                            </tr>";
                        }
                    }
                }
            }
            ?>
        </tbody>
    </table>
</body>
</html>
