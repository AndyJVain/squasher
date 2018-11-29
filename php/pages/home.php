<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/home.css">

    <title>Squasher - Home</title>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-light">
            <div class="navbar-brand black-text">Squasher - My Bugs</div>
            <ul class="nav navbar-nav navbar-right pull-right">
                <?php
                include '../session.php';

                if ($_SESSION['role'] == 'MANAGER') {
                    echo '<div class="menu-dropdown-container">
                        <button class="btn dropdown-toggle light-gray" type="button" data-toggle="dropdown">Menu</button>
                        <ul class="dropdown-menu menu-list">
                            <li><a href="create-internal.php">Create Internal Account</a></li>
                            <li><a href="../logout.php">Log Out</a></li>
                        </ul>
                    </div>';
                } else {
                    echo '<li><a class="dark-gray-text" href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>';
                }
                ?>
            </ul>
        </nav>
        <div class="header">
            <?php
            if ($_SESSION['role'] == 'REPORTER') {
                echo '<div class="left">
                </div>
                <div class="right">
                    <a type="button" class="btn btn-primary btn-lg blue" href="new-bug.php">Report New Bug</a>
                </div>';
            } elseif ($_SESSION['role'] == 'MANAGER') {
                echo '<div class="left">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle blue" type="button" data-toggle="dropdown">Filter by status
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="../pages/home.php?filter=PENDING BUG VERIFICATION">Pending Bug Verification</a></li>
                            <li><a href="../pages/home.php?filter=PENDING DEVELOPER ASSIGNMENT">Pending Developer Assignment</a></li>
                            <li><a href="../pages/home.php?filter=IN DEVELOPMENT">In Development</a></li>
                            <li><a href="../pages/home.php?filter=PENDING FIX VERIFICATION">Pending Fix Verification</a></li>
                            <li><a href="../pages/home.php?filter=BUG VERIFICATION FAILED">Bug Verification Failed</a></li>
                            <li><a href="../pages/home.php?filter=DONE">Done</a></li>
                            <li><a href="../pages/home.php?filter=ALL">All</a></li>
                        </ul>
                    </div>
                </div>
                <div class="right">
                    <p class="role-label">',$_SESSION['role'],'</p>
                </div>';
            } else {
                echo '<div class="left">
                </div>
                <div class="right">
                    <p class="role-label">',$_SESSION['role'],'</p>
                </div>';
            }
            ?>
        </div>
        <div class="bug-table">
            <?php
            $conn=oci_connect('psanchez', 'a47k7S4QOi', '//dbserver.engr.scu.edu/db11g');
            if (!$conn) {
                print "<br> connection failed:";
                exit;
            }

            $username = $_SESSION['username'];

            if (isset($_GET['filter'])) {   // Query for filtered bug state
                if ($_GET['filter'] == "ALL") {
                    $query = oci_parse($conn, "select PRODUCT, TITLE, BUG_ID, STATE, REPORT_DATE from squasher_reports");
                    oci_execute($query);
                } else {
                    $filter = $_GET['filter'];
                    $query = oci_parse($conn, "select PRODUCT, TITLE, BUG_ID, STATE, REPORT_DATE from squasher_reports where STATE = '$filter'");
                    oci_execute($query);
                }
            } else {
                if ($_SESSION['role'] == "REPORTER") {
                    $query = oci_parse($conn, "select PRODUCT, TITLE, BUG_ID, STATE, REPORT_DATE from squasher_reports where REPORTER_USERNAME = '$username'");
                    oci_execute($query);
                } elseif ($_SESSION['role'] == "MANAGER") {
                    $query = oci_parse($conn, "select PRODUCT, TITLE, BUG_ID, STATE, REPORT_DATE from squasher_reports where STATE = 'PENDING DEVELOPER ASSIGNMENT'");
                    oci_execute($query);
                } else { //Dev, Tester
                    $query = oci_parse($conn, "select PRODUCT, TITLE, BUG_ID, STATE, REPORT_DATE from squasher_reports where ASSIGNED = '$username'");
                    oci_execute($query);
                }
            }

            $row = oci_fetch_array($query, OCI_BOTH)) != false
            if ($row == false) {
                echo '<p class="no-bugs blue-text">No Assigned Bugs</p>';
            } else {
                do {
                    echo '
                    <div class="bug-report rounded light-gray">
              						<div class="report-left">
              							<p class="service dark-gray-text">',$row[0],'</p>
              							<p class="title blue-text"><a href="../pages/bug-report.php?bug_id=',$row[2],'&state=',$row[3],'">',$row[2],': ',$row[1],'</p></a>
              							<p class="date dark-gray-text">Submitted on ',$row[4],'</p>
              						</div>
              						<div class="report-right">
              							<p class="status dark-gray-text">Current status: ',$row[3],'</p>
            						</div>
                    </div>';
                } while (($row = oci_fetch_array($query, OCI_BOTH)) != false);
            }

            OCILogoff($conn);
            ?>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
