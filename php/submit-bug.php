<html>
<body>

<?php
include 'session.php';

$product = $_POST["product"];
$title = htmlspecialchars($_POST["title"]);
$bugType = $_POST["bug-type"];
$rep = $_POST["rep"];
$description = htmlspecialchars($_POST["description"]);

substr_replace($title,"\'","&#39;");
substr_replace($description,"\'","&#39;");

$reporterUsername = $_SESSION['username'];

//by default, will assign to tester (pedro)
//Will setup a trigger to handle auto-assignment on DB side
$defaultAssigned = 'assigner';

$defaultState = "PENDING BUG VERIFICATION";

function getLeastWorkedTester($bug_id){
  $conn = oci_connect('psanchez', 'a47k7S4QOi', '//dbserver.engr.scu.edu/db11g');
  if (!$conn) {
      print "<br> connection failed:";
      exit;
  }

  $getMin = "select MIN(NUM_ASSIGNED) as MINIMUM from squasher_user where ROLE = 'TESTER' ";
  $query = oci_parse($conn, $getMin);
  oci_execute($query);
  $row_min = oci_fetch_array($query, OCI_BOTH);

  $minAssigned = $row_min['MINIMUM'];

  $getUsername = "select username as ASSIGNEE from squasher_user where ROLE = 'TESTER' and USERNAME != 'assigner' and NUM_ASSIGNED = $minAssigned and ROWNUM <= 1";
  $query = oci_parse($conn, $getUsername);
  oci_execute($query);
  $row_assignee = oci_fetch_array($query, OCI_BOTH);

  $assignee = $row_assignee['ASSIGNEE'];

  return $assignee;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = oci_connect('psanchez', 'a47k7S4QOi', '//dbserver.engr.scu.edu/db11g');
    if (!$conn) {
        print "<br> connection failed:";
        exit;
    }



    $getReportNumberQuery = "select MAX(REPORT_NUMBER) from SQUASHER_COUNTER";
    $getDateQuery = "select SYSDATE from DUAL";

    //Get current ReportNumber
    $query = oci_parse($conn, $getReportNumberQuery);
    oci_execute($query);
    $row_reportNumber = oci_fetch_array($query, OCI_BOTH);
    $reportNumber = $row_reportNumber[0];

    //potential issue with timing attack
    //empty reportNumber table
    $updateReportNumberQuery = "delete squasher_counter where report_number < ($reportNumber+1)";
    $query = oci_parse($conn, $updateReportNumberQuery);
    oci_execute($query);

    //update ReportNumber
    $updateReportNumberQuery = "insert into squasher_counter values($reportNumber+1)";
    $query = oci_parse($conn, $updateReportNumberQuery);
    oci_execute($query);

    //Get SYSDATE
    $query = oci_parse($conn, $getDateQuery);
    oci_execute($query);
    $row_date = oci_fetch_array($query, OCI_BOTH);
    $sysDate = $row_date[0];

    //get the tester that this should be assigned to`
    $newAssigned = getLeastWorkedTester($reportNumber);

    //setup query for new report
    $newReportQuery = "insert into SQUASHER_REPORTS values('$reportNumber','$product','$title','$bugType','$rep','$newAssigned','$defaultState','$reporterUsername','$sysDate','$description')";
    echo($newReportQuery);
    $query = oci_parse($conn, $newReportQuery);
    oci_execute($query);

    OCILogoff($conn);

    print_r($newReportQuery);
    print_r(htmlspecialchars($title));


    //header("Location: pages/home.php");
}
?>

</body>
</html>
