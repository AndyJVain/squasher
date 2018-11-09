<html>
<body>

<?php

include 'session.php';

$bug_id = $_POST["bug_id"];
$state = $_POST["state"];
$role = $_SESSION['role'];
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = oci_connect('psanchez', 'a47k7S4QOi', '//dbserver.engr.scu.edu/db11g');
    if (!$conn) {
        print "<br> connection failed:";
        exit;
    }

    if ($role == "DEVELOPER") {

        $queryState = "update squasher_reports set state = 'PENDING FIX VERIFICATION' where bug_id = $_GET['bug_id']";
        $queryAssigned = "update squasher_reports set ASSIGNED = assigner where bug_id = $_GET['bug_id']";

        $query = oci_parse($conn, $queryState);
        oci_execute($query);

        $query = oci_parse($conn, $queryAssigned);
        oci_execute($query);
    }
    elseif($role == "TESTER"){
        if ($state == "PENDING BUG VERIFICATION") {
          if (isset($_POST['not_verified'])) {
              # Not Verified-button was clicked
              $queryState = "update squasher_reports set state = 'BUG VERIFICATION FAILED' where bug_id = $_GET['bug_id']";
              $query = oci_parse($conn, $queryState);
              oci_execute($query);
          }
          elseif (isset($_POST['verified'])) {
              # Verified-button was clicked
              $queryState = "update squasher_reports set state = 'PENDING DEVELOPER ASSIGNMENT' where bug_id = $_GET['bug_id']";
              $queryAssigned = "update squasher_reports set ASSIGNED = andyj where bug_id = $_GET['bug_id']";

              $query = oci_parse($conn, $queryState);
              oci_execute($query);

              $query = oci_parse($conn, $queryAssigned);
              oci_execute($query);
          }

        } elseif ($state == "PENDING FIX VERIFICATION") {
            if (isset($_POST['not_verified'])) {
                # Not Verified-button was clicked
                $queryState = "update squasher_reports set state = 'PENDING DEVELOPER ASSIGNMENT' where bug_id = $_GET['bug_id']";
                $queryAssigned = "update squasher_reports set ASSIGNED = andyj where bug_id = $_GET['bug_id']";

                $query = oci_parse($conn, $queryState);
                oci_execute($query);

                $query = oci_parse($conn, $queryAssigned);
                oci_execute($query);

            }
            elseif (isset($_POST['verified'])) {
                # Verified-button was clicked
                # Verified-button was clicked
                $queryState = "update squasher_reports set state = 'DONE' where bug_id = $_GET['bug_id']";
                $queryAssigned = "update squasher_reports set ASSIGNED = done where bug_id = $_GET['bug_id']";

                $query = oci_parse($conn, $queryState);
                oci_execute($query);

                $query = oci_parse($conn, $queryAssigned);
                oci_execute($query);
            }
        }
    }
    elseif($role == "MANAGER"){
        $assigned_developer = $_POST["assigned_developer"];

        $queryState = "update squasher_reports set state = 'PENDING FIX DEVELOPMENT' where bug_id = $_GET['bug_id']";
        $queryAssigned = "update squasher_reports set ASSIGNED = $assigned_developer where bug_id = $_GET['bug_id']";

        $query = oci_parse($conn, $queryState);
        oci_execute($query);

        $query = oci_parse($conn, $queryAssigned);
        oci_execute($query);
    }

    OCILogoff($conn);
}
?>

</body>
</html>
