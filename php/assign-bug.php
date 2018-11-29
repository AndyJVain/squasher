<!--
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
  Purpose: This file holds the logic for moving bugs to the next state. Whenever the state of a bug is changed, an email is sent to the reporter.
 -->

<html>
<body>

<?php
include 'session.php';

//Fetch bug identifying information from POST
$bug_id = $_POST['bug_id'];
$state = $_POST['state'];

//Fetch user information from session
$role = $_SESSION['role'];
$username = $_SESSION['username'];

/*
  Function Name: emailReporter
  Arguments: bug_id (type == mixed)
  Purpose: Uses the bug id and retrieves the associated email address from the database. An email is then sent.
  Returns: Null
*/
function emailReporter($bug_id)
{
    include 'config.php';
    $msg = "A bug associated with your Squasher Account has been updated. Sign in at squasher.tk to view it.";

    $headers = $sender_email;

    $getEmailQuery = "select * from squasher_user where username = (select reporter_username from squasher_reports where bug_id = $bug_id)";

    $conn = connect();
    $query = oci_parse($conn, $getEmailQuery);
    oci_execute($query);

    $row = oci_fetch_array($query, OCI_BOTH);

    // send email
    mail($row['EMAIL'], "Squasher - Bug Update", $msg, $headers);
}

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
    //Get the number of bugs assigned to the least busy tester
    $getMin = "select MIN(NUM_ASSIGNED) as MINIMUM from squasher_user where ROLE = 'TESTER' ";
    $query = oci_parse($conn, $getMin);
    oci_execute($query);
    $row_min = oci_fetch_array($query, OCI_BOTH);

    $minAssigned = $row_min['MINIMUM'];

    //Choose a tester that has the minimum number of bugs assigned to them
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

    //Handles the state changes for Developer
    if ($role == "DEVELOPER") {
        emailReporter($bug_id);
        $assignee = getLeastWorkedTester();

        //Assign bug to least busy tester
        $queryState = "update squasher_reports set state = 'PENDING FIX VERIFICATION', ASSIGNED = '$assignee' where bug_id = $bug_id";
        $query = oci_parse($conn, $queryState);
        oci_execute($query);
    } elseif ($role == "TESTER") { //Handles the state changes for Tester

        //Handles the state changes for Bugs Pending Verification
        if ($state == "PENDING BUG VERIFICATION") {
            //If the bug is not verified then send it to the FAILED state
            if (isset($_POST['not_verified'])) {
                $queryState = "update squasher_reports set state = 'BUG VERIFICATION FAILED',ASSIGNED = 'failed' where bug_id = $bug_id";
                emailReporter($bug_id);
                $query = oci_parse($conn, $queryState);
                oci_execute($query);
            } elseif (isset($_POST['verified'])) { //If the bug is verified then send it to the manager at state PENDING DEVELOPER ASSIGNMENT
                $queryState = "update squasher_reports set state = 'PENDING DEVELOPER ASSIGNMENT', ASSIGNED = 'manager' where bug_id = $bug_id";
                emailReporter($bug_id);
                $query = oci_parse($conn, $queryState);
                oci_execute($query);
            }
        } elseif ($state == "PENDING FIX VERIFICATION") { //Handles the state changes for Fix Pending Verification
            //If the fix is not verified, send it back to the manager at state PENDING DEVELOPER ASSIGNMENT
            if (isset($_POST['not_verified'])) {
                $queryState = "update squasher_reports set state = 'PENDING DEVELOPER ASSIGNMENT', ASSIGNED = 'manager' where bug_id = $bug_id";
                emailReporter($bug_id);
                $query = oci_parse($conn, $queryState);
                oci_execute($query);
            } elseif (isset($_POST['verified'])) { //If the fix is verified, then send it to state DONE
                $queryState = "update squasher_reports set state = 'DONE', ASSIGNED = 'done' where bug_id = $bug_id";
                emailReporter($bug_id);
                $query = oci_parse($conn, $queryState);
                oci_execute($query);
            }
        }
    } elseif ($role == "MANAGER") { //Handles the state changes and developer assignment for Manager
        //Fetch chosen developer from POST
        $assigned_developer = $_POST["assigned_developer"];

        //Assign to developer and update state
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
