<html>
<body>

<?php
include 'session.php';

$bug_id = $_POST['bug_id'];
$state = $_POST['state'];

// echo $bug_id;
// echo $state;

$role = $_SESSION['role'];
$username = $_SESSION['username'];

function emailReporter($bug_id){
    $msg = "A bug associated with your Squasher Account has been updated. Sign in at squasher.tk to view it.";

    $headers = "From: donotreply@squasher.com";

    $getEmailQuery = "select * from squasher_user where username = (select reporter_username from squasher_reports where bug_id = $bug_id)";

    $conn = oci_connect('psanchez', 'a47k7S4QOi', '//dbserver.engr.scu.edu/db11g');
    $query = oci_parse($conn, $getEmailQuery);
    oci_execute($query);

    $row = oci_fetch_array($query, OCI_BOTH);

    // send email
    mail($row['EMAIL'], "Squasher - Bug Update", $msg, $headers);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = oci_connect('psanchez', 'a47k7S4QOi', '//dbserver.engr.scu.edu/db11g');
    if (!$conn) {
        print "<br> connection failed:";
        exit;
    }

    if ($role == "DEVELOPER") {
        $queryState = "update squasher_reports set state = 'PENDING FIX VERIFICATION' where bug_id = $bug_id";
        $queryAssigned = "update squasher_reports set ASSIGNED = 'tester' where bug_id = $bug_id";

        emailReporter($bug_id);

        $query = oci_parse($conn, $queryState);
        oci_execute($query);

        $query = oci_parse($conn, $queryAssigned);
        oci_execute($query);
    }

    elseif($role == "TESTER"){
        if ($state == "PENDING BUG VERIFICATION") {
          if (isset($_POST['not_verified'])) {
              $queryState = "update squasher_reports set state = 'BUG VERIFICATION FAILED' where bug_id = $bug_id";
              $queryAssigned = "update squasher_reports set ASSIGNED = 'failed' where bug_id = $bug_id";

              emailReporter($bug_id);

              $query = oci_parse($conn, $queryState);
              oci_execute($query);

              $query = oci_parse($conn, $queryAssigned);
              oci_execute($query);
          }
          elseif (isset($_POST['verified'])) {
              $queryState = "update squasher_reports set state = 'PENDING DEVELOPER ASSIGNMENT' where bug_id = $bug_id";
              $queryAssigned = "update squasher_reports set ASSIGNED = 'andyj' where bug_id = $bug_id";

              emailReporter($bug_id);

              $query = oci_parse($conn, $queryState);
              oci_execute($query);

              $query = oci_parse($conn, $queryAssigned);
              oci_execute($query);
          }

        } elseif ($state == "PENDING FIX VERIFICATION") {
            if (isset($_POST['not_verified'])) {
                $queryState = "update squasher_reports set state = 'PENDING DEVELOPER ASSIGNMENT' where bug_id = $bug_id";
                $queryAssigned = "update squasher_reports set ASSIGNED = 'andyj' where bug_id = $bug_id";

                emailReporter($bug_id);

                $query = oci_parse($conn, $queryState);
                oci_execute($query);

                $query = oci_parse($conn, $queryAssigned);
                oci_execute($query);

            }
            elseif (isset($_POST['verified'])) {
                $queryState = "update squasher_reports set state = 'DONE' where bug_id = $bug_id";
                $queryAssigned = "update squasher_reports set ASSIGNED = 'done' where bug_id = $bug_id";

                emailReporter($bug_id);

                $query = oci_parse($conn, $queryState);
                oci_execute($query);

                $query = oci_parse($conn, $queryAssigned);
                oci_execute($query);
            }
        }
    }

    elseif($role == "MANAGER"){
        $assigned_developer = $_POST["assigned_developer"];

        $queryState = "update squasher_reports set state = 'IN DEVELOPMENT' where bug_id = $bug_id";
        $queryAssigned = "update squasher_reports set ASSIGNED = '$assigned_developer' where bug_id = $bug_id";

        emailReporter($bug_id);

        $query = oci_parse($conn, $queryState);
        oci_execute($query);

        $query = oci_parse($conn, $queryAssigned);
        oci_execute($query);
    }

    OCILogoff($conn);

    header("location: ../php/pages/home.php");
}
?>

</body>
</html>
