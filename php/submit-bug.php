<!--
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
  Purpose: This file submits a bug report.
-->

<html>
<body>

<?php
include 'session.php';
include 'clean-input.php';

//Fetch relevant information from POST and SESSION variables
$product = $_POST["product"];
$title = clean($_POST["title"]);
$bugType = $_POST["bug-type"];
$rep = $_POST["rep"];
$description = clean($_POST["description"]);
$reporterUsername = $_SESSION['username'];


//Next state such that bug is always sent to tester
$defaultState = "PENDING BUG VERIFICATION";

/*
  Function Name: getLeastWorkedTester
  Arguments: none
  Purpose: Queries the database and returns the tester with the fewest assigned bugs.
  Returns: Username of chosen tester with fewest assigned bugs (type == string)
*/
function getLeastWorkedTester()
{
    $conn = connect();
    if (!$conn) {
        print "<br> connection failed:";
        exit;
    }

    $getMin = "select MIN(NUM_ASSIGNED) as MINIMUM from squasher_user where ROLE = 'TESTER' ";
    $query = oci_parse($conn, $getMin);
    oci_execute($query);
    $row_min = oci_fetch_array($query, OCI_BOTH);

    $minAssigned = $row_min['MINIMUM'];

    $getUsername = "select username as ASSIGNEE from squasher_user where ROLE = 'TESTER' and NUM_ASSIGNED = $minAssigned and ROWNUM <= 1";
    $query = oci_parse($conn, $getUsername);
    oci_execute($query);
    $row_assignee = oci_fetch_array($query, OCI_BOTH);

    $assignee = $row_assignee['ASSIGNEE'];
    return $assignee;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connection.php';
    $conn = connect();
    if (!$conn) {
        print "<br> connection failed:";
        exit;
    }

    //Get current ReportNumber
    $getReportNumberQuery = "select MAX(REPORT_NUMBER) from SQUASHER_COUNTER";
    $query = oci_parse($conn, $getReportNumberQuery);
    oci_execute($query);
    $row_reportNumber = oci_fetch_array($query, OCI_BOTH);
    $reportNumber = $row_reportNumber[0];

    //Update Report Number
    $updateReportNumberQuery = "delete squasher_counter where report_number < ($reportNumber+1)";
    $query = oci_parse($conn, $updateReportNumberQuery);
    oci_execute($query);
    $updateReportNumberQuery = "insert into squasher_counter values($reportNumber+1)";
    $query = oci_parse($conn, $updateReportNumberQuery);
    oci_execute($query);

    //Get SYSDATE
    $getDateQuery = "select SYSDATE from DUAL";
    $query = oci_parse($conn, $getDateQuery);
    oci_execute($query);
    $row_date = oci_fetch_array($query, OCI_BOTH);
    $sysDate = $row_date[0];

    //Run the tester assignment algorithm
    $newAssigned = getLeastWorkedTester($reportNumber);

    //Commit new report to database
    $newReportQuery = "insert into SQUASHER_REPORTS values('$reportNumber','$product','$title','$bugType','$rep','$newAssigned','$defaultState','$reporterUsername','$sysDate','$description')";
    echo($newReportQuery);
    $query = oci_parse($conn, $newReportQuery);
    oci_execute($query);

    OCILogoff($conn);
    header("Location: pages/home.php");
}
?>

</body>
</html>
