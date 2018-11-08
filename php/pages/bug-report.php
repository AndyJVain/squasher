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
    <link rel="stylesheet" href="../css/bug-report.css">

    <title>Squasher - Bug Report</title>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-light">
            <div class="navbar-brand">Squasher</div>
            <ul class="nav navbar-nav navbar-right pull-right">
                <li><a class="darker-gray-text" href="login.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>
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
        include '../session.php';

        $conn=oci_connect('psanchez', 'a47k7S4QOi', '//dbserver.engr.scu.edu/db11g');
        if (!$conn) {
            print "<br> connection failed:";
            exit;
        }
        $bug_id = intval($_GET['bug_id']);
        $query = oci_parse($conn, "select PRODUCT, TITLE, BUG_TYPE, REPRODUCABILITY, DESCRIPTION, STATE, REPORT_DATE from squasher_reports where BUG_ID = '$bug_id'");

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
            <label for="reproducability">Reproducability</label>
            <p>',$row[3],'</p>
        </div>
        <div class="report-group blue-text">
            <label for="description">Description</label>
            <p>',$row[4],'</p>
        </div>'
        ?>
            <div class="next-state-container">
                <button type="button" class="btn btn-primary btn-lg next-state-btn blue" data-toggle="modal" data-target="#tester-modal">
                    &rarr;
                </button>
                <button type="button" class="btn btn-primary btn-lg blue" data-toggle="modal" data-target="#developer-modal">
                    Developer
                </button>
                <button type="button" class="btn btn-primary btn-lg blue" data-toggle="modal" data-target="#manager-modal">
                    Manager
                </button>
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
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-warning">Not Verified</button>
                            <button type="button" class="btn btn-primary blue">Verified</button>
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
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary blue">Development Complete</button>
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
                                <form action="../submit-bug.php" method="post">
                                    <div class="form-group blue-text">
                                        <label for="select-product">Assign to Developer</label>
                                        <select class="form-control" id="select-product" name="product">
                                            <option value="" disabled selected>Select a developer</option>
                                            <option>Andy Vainauskas</option>
                                            <option>Connor Carraher</option>
                                            <option>Pedro Sanchez</option>
                                        </select>
                                    </div>
                                </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary blue">Assign</button>
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
