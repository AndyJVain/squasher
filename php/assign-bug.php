<html>
<body>

<?php
include 'session.php';

$bug_id = $_POST['bug_id'];
$state = $_POST['state'];

$role = $_SESSION['role'];
$username = $_SESSION['username'];

function emailReporter($bug_id){
    $msg = "A bug associated with your Squasher Account has been updated. Sign in at squasher.tk to view it.";

    $headers = "From: donotreply@squasher.com";

    $getEmailQuery = "select * from squasher_user where username = (select reporter_username from squasher_reports where bug_id = $bug_id)";

    include '../connection.php';
    $conn = connect();
    $query = oci_parse($conn, $getEmailQuery);
    oci_execute($query);

    $row = oci_fetch_array($query, OCI_BOTH);

    // send email
    mail($row['EMAIL'], "Squasher - Bug Update", $msg, $headers);
}

function getLeastWorkedTester($bug_id){
    include '../connection.php';
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

  $getUsername = "select username as ASSIGNEE from squasher_user where ROLE = 'TESTER' and USERNAME != 'assigner' and NUM_ASSIGNED = $minAssigned and ROWNUM <= 1";
  $query = oci_parse($conn, $getUsername);
  oci_execute($query);
  $row_assignee = oci_fetch_array($query, OCI_BOTH);

  $assignee = $row_assignee['ASSIGNEE'];

  return $assignee;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include '../connection.php';
    $conn = connect();
    if (!$conn) {
        print "<br> connection failed:";
        exit;
    }

    if ($role == "DEVELOPER") {


        emailReporter($bug_id);
        $assignee = getLeastWorkedTester($bug_id);

        $queryState = "update squasher_reports set state = 'PENDING FIX VERIFICATION', ASSIGNED = '$assignee' where bug_id = $bug_id";
        $query = oci_parse($conn, $queryState);
        oci_execute($query);

    }

    elseif($role == "TESTER"){
        if ($state == "PENDING BUG VERIFICATION") {
          if (isset($_POST['not_verified'])) {
              $queryState = "update squasher_reports set state = 'BUG VERIFICATION FAILED',ASSIGNED = 'failed' where bug_id = $bug_id";

              emailReporter($bug_id);

              $query = oci_parse($conn, $queryState);
              oci_execute($query);

          }
          elseif (isset($_POST['verified'])) {
              $queryState = "update squasher_reports set state = 'PENDING DEVELOPER ASSIGNMENT', ASSIGNED = 'manager' where bug_id = $bug_id";
              emailReporter($bug_id);

              $query = oci_parse($conn, $queryState);
              oci_execute($query);

          }

        } elseif ($state == "PENDING FIX VERIFICATION") {
            if (isset($_POST['not_verified'])) {
                $queryState = "update squasher_reports set state = 'PENDING DEVELOPER ASSIGNMENT', ASSIGNED = 'manager' where bug_id = $bug_id";

                emailReporter($bug_id);

                $query = oci_parse($conn, $queryState);
                oci_execute($query);


            }
            elseif (isset($_POST['verified'])) {
                $queryState = "update squasher_reports set state = 'DONE', ASSIGNED = 'done' where bug_id = $bug_id";

                emailReporter($bug_id);

                $query = oci_parse($conn, $queryState);
                oci_execute($query);

            }
        }
    }

    elseif($role == "MANAGER"){
        $assigned_developer = $_POST["assigned_developer"];

        $queryState = "update squasher_reports set state = 'IN DEVELOPMENT', ASSIGNED = '$assigned_developer' where bug_id = $bug_id";

        emailReporter($bug_id);

        $query = oci_parse($conn, $queryState);
        oci_execute($query);

    }

    OCILogoff($conn);

    header("location: ../php/pages/home.php");
}
?>

</body>
</html>
