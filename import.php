<?php
class import extends mysqli
{
    private $exc_success = false;
    private $resultData = array();
    private $headingData = array('Employee Name', 'Employee Code', 'Department', 'Date of birth', 'Joining Date');
    public function __construct()
    {
        parent::__construct('localhost', 'root', '', 'db_employee');
        if ($this->connect_error) {
            echo "Fail db connection : " . $this->connect_error;
        }
    }
    public function importFile($file)
    {
        $heading = true;
        $headigArray = array();
        $totalrows = 0;
        $diffHeadings = array();
        $rowcount = file($file, FILE_SKIP_EMPTY_LINES);
        if (!empty($rowcount)) {
            $headigArray = preg_replace('/[^a-zA-Z0-9_ -]/s', '', explode(",", $rowcount[0])); //for remove special characters
            unset($rowcount[0]);
            $totalrows = count($rowcount);
            $diffHeadings = array_diff($headigArray, $this->headingData);
        }
        if (empty($diffHeadings) && $totalrows <= 20) {
            $file = fopen($file, 'r');
            while ($row = fgetcsv($file)) {
                if ($heading) {
                    $headigArray = $row;
                    $heading = false;
                } else {
                    $value = "'" . implode("','", $row) . "'";
                    $query = "INSERT INTO tbl_employee (employeeCode,employeeName,employeeDepartment,employeeDob,employeeJoinDate) VALUES (" . $value . ")";
                    if ($this->query($query)) {
                        $this->exc_success = true;
                    } else {
                        $this->exc_success = false;
                    }
                }
            }
            if ($this->exc_success) {
                echo "Successfully Imported";
            } else {
                echo "Something went wrong, please check the uploaded file!";
            }
        } else {
            if (!empty($diffHeadings)) {
                echo "Invalid headings : " . implode(",", $diffHeadings);
            } else {
                echo "Invalid file, please upload a valid file with maximum 20 rows";
            }
        }
    }
    public function getEmployeeDetails()
    {
        $query = "SELECT * FROM tbl_employee";
        $employeeData = $this->query($query);
        if ($employeeData->num_rows > 0) {
            while ($row = $employeeData->fetch_array(MYSQLI_ASSOC)) {
                $this->resultData[] = $row;
            }
        }
        return  $this->resultData;
    }
}
