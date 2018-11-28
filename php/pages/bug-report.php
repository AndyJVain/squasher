<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- jquery -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <!-- [/] jquery -->

    <!-- bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <!-- [/] bootstrap -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/bug-report.css">

    <title>Squasher - Bug Report</title>
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
                            <button class="btn btn-dark dropdown-toggle dark-gray" type="button" data-toggle="dropdown">Menu</button>
                            <ul class="dropdown-menu menu-list">
                                <li><a href="create-internal.php">Create Internal Account</a></li>
                                <li><a href="../logout.php">Log Out</a></li>
                            </ul>
                        </div>';
                    } else {
                        echo '<li><a class="darker-gray-text" href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>';
                    }
                    ?>
                </ul>
        </nav>

        <div class="header">
            <div class="left blue-text">
                <p>Bug Report Form</p>
            </div>
            <div class="right">
                <a type="button" class="btn btn-primary btn-lg blue" href="home.php">My Bugs</a>
            </div>
        </div>

        <?php
        $conn=oci_connect('psanchez', 'a47k7S4QOi', '//dbserver.engr.scu.edu/db11g');
        if (!$conn) {
            print "<br> connection failed:";
            exit;
        }
        $bug_id = intval($_GET['bug_id']);

        $query = oci_parse($conn, "select PRODUCT, TITLE, BUG_TYPE, REPRODUCIBILITY, DESCRIPTION, STATE, REPORT_DATE from squasher_reports where BUG_ID = '$bug_id'");

        oci_execute($query);
        $row = oci_fetch_array($query, OCI_BOTH);

        echo '<div class="report rounded light-gray">
            <div class="report-group blue-text">
                <label for="product">Product</label>
                <p>',$row[0],'</p>
            </div>
            <div class="report-group blue-text">
                <label for="title">Title</label>
                <p>',$bug_id,':',$row[1],'</p>
            </div>
            <div class="report-group blue-text">
                <label for="bug-type">Bug Type</label>
                <p>',$row[2],'</p>
            </div>
            <div class="report-group blue-text">
                <label for="reproducibility">Reproducibility</label>
                <p>',$row[3],'</p>
            </div>
            <div class="report-group blue-text">
                <label for="description">Description</label>
                <p>',$row[4],'</p>
            </div>';
          OCILogoff($conn);
        ?>
            <div class="next-state-container">
                <?php
                if ($_SESSION['role'] == 'TESTER') {
                    echo '<button type="button" class="btn btn-primary btn-lg next-state-btn blue" data-toggle="modal" data-target="#tester-modal">
                        &rarr;
                    </button>';
                } elseif ($_SESSION['role'] == 'DEVELOPER') {
                    echo '<button type="button" class="btn btn-primary btn-lg next-state-btn blue" data-toggle="modal" data-target="#developer-modal">
                        &rarr;
                    </button>';
                } elseif ($_SESSION['role'] == 'MANAGER' && $_GET['state'] == 'PENDING DEVELOPER ASSIGNMENT') {
                    echo '<button type="button" class="btn btn-primary btn-lg next-state-btn blue" data-toggle="modal" data-target="#manager-modal">
                        &rarr;
                    </button>';
                };
                ?>
            </div>
        </div>

        <!-- Tester Modal -->
        <div class="modal fade" id="tester-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="vertical-alignment-helper">
                <div class="modal-dialog vertical-align-center">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                            </button>
                             <h4 class="modal-title" id="myModalLabel">Next State</h4>
                        </div>
                        <div class="modal-footer">
                            <form action="../assign-bug.php" method="post">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <input type="submit" name="not_verified" class="btn btn-warning" value="Not Verified">
                                <input type="submit" name="verified" class="btn btn-primary blue" value="Verified">
                                <input type="hidden" name="bug_id" value="<?php echo intval($_GET['bug_id']); ?>">
                                <input type="hidden" name="state" value="<?php echo $_GET['state']; ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Developer Modal -->
        <div class="modal fade" id="developer-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="vertical-alignment-helper">
                <div class="modal-dialog vertical-align-center">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                            </button>
                             <h4 class="modal-title" id="myModalLabel">Next State</h4>
                        </div>
                        <div class="modal-footer">
                            <form action="../assign-bug.php" method="post">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <input type="submit" class="btn btn-primary blue" value="Development Complete">
                                <input type="hidden" name="bug_id" value="<?php echo intval($_GET['bug_id']); ?>">
                                <input type="hidden" name="state" value="<?php echo $_GET['state']; ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manager Modal -->
        <div class="modal fade" id="manager-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="vertical-alignment-helper">
                <div class="modal-dialog vertical-align-center">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                            </button>
                             <h4 class="modal-title" id="myModalLabel">Next State</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form rounded">
                                <form action="../assign-bug.php" method="post">
                                    <div class="form-group blue-text">
                                        <label for="select-developer">Assign to Developer</label>
                                        <select class="form-control" id="select-developer" name="assigned_developer" required>
                                            <option value="" disabled selected>Select a developer</option>
                                            <?php
                                            $conn=oci_connect('psanchez', 'a47k7S4QOi', '//dbserver.engr.scu.edu/db11g');
                                            if (!$conn) {
                                                print "<br> connection failed:";
                                                exit;
                                            }
                                            $query = oci_parse($conn, "select username from squasher_user where ROLE = 'DEVELOPER'");
                                            oci_execute($query);
                                            while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
                                                echo '<option>',$row[0],'</option>';
                                            }
                                            OCILogoff($conn);
                                            ?>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <input type="submit" class="btn btn-primary blue" value="Assign">
                                        <input type="hidden" name="bug_id" value="<?php echo intval($_GET['bug_id']); ?>">
                                        <input type="hidden" name="state" value="<?php echo $_GET['state']; ?>">
                                        <!-- <button type="button" class="btn btn-primary blue">Assign</button> -->
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
