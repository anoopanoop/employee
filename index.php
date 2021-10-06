<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Import Employee Report</title>
</head>

<body>
    <div class="input-group">
        <?php
        include("import.php");
        $import = new import();

        if (isset($_POST['importfile'])) {
            $ext = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv');
            if (in_array($_FILES['csvfile']['type'], $ext)) {
                $import->importFile($_FILES['csvfile']['tmp_name']);
                unset($_POST['importfile']);
            } else {
                echo "Sorry, Invalid file format!";
            }
        }
        $employeeDetails = $import->getEmployeeDetails();
        ?>
    </div>
    <br>
    <form method="post" enctype="multipart/form-data">
        <div class="input-group">
            <input type="file" name="csvfile" class="form-control" id="customFile" />&nbsp;
            <input type="submit" name="importfile" value="import" class="btn btn-primary mt-10">
            <i>
                <h6>*Please note that the headings of CSV must be the following manner - "Employee Name,Employee Code,Department,Date of birth,Joining Date"</h6>
            </i>
        </div>
    </form>
    <br><br>
    <h5>Employee Report</h5><br><br>
    <table class="table table-striped">
        <tr>
            <thead class="thead-dark">
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Age</th>
                <th>Experience (Years)</th>
        </tr>
        <?php if (!empty($employeeDetails)) {
            $today = date('Y-m-d');
            foreach ($employeeDetails as $empVal) {
                if (isset($empVal['employeeDob']) && $empVal['employeeDob'] != '') {
                    $diff = abs(strtotime($today) - strtotime($empVal['employeeDob']));
                    $empage = floor($diff / (365 * 60 * 60 * 24));
                } else {
                    $empage = '';
                }
                if (isset($empVal['employeeJoinDate']) && $empVal['employeeJoinDate'] != '') {
                    $diff = abs(strtotime($today) - strtotime($empVal['employeeJoinDate']));
                    $empexp = floor($diff / (365 * 60 * 60 * 24));
                } else {
                    $empexp = '';
                }
                $empcode = isset($empVal['employeeCode']) && $empVal['employeeCode'] != '' ? $empVal['employeeCode'] : '';
                $empname = isset($empVal['employeeName']) && $empVal['employeeName'] != '' ? $empVal['employeeName'] : '';
                $empdepart = isset($empVal['employeeDepartment']) && $empVal['employeeDepartment'] != '' ? $empVal['employeeDepartment'] : '';
        ?>
                <tr>
                    <td><?= $empcode ?></td>
                    <td><?= $empname ?></td>
                    <td><?= $empdepart ?></td>
                    <td><?= $empage ?></td>
                    <td><?= $empexp ?></td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="5"><b>No data found</b></td>
            </tr>
        <?php } ?>

    </table>
</body>
<style>
    .input-group {

        width: 50%;
        margin: 0px auto;


    }

    .table,
    h5 {
        border-radius: 5px;
        width: 50%;
        margin: 0px auto;
        float: none;
    }

    td {
        text-align: center;
    }
</style>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</html>