<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/new-bug.css">

    <title>Squasher - New Bug Report</title>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-light">
            <div class="navbar-brand">Report New Bug</div>
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

        <div class="form rounded light-gray">
            <form action="../submit-bug.php" method="post">
                <div class="form-group blue-text">
                    <label for="select-product">Product</label>
                    <select class="form-control" id="select-product" name="product">
                        <option value="" disabled selected>Select a product</option>
                        <option>Camino</option>
                        <option>CourseAvail</option>
                        <option>eCampus</option>
                        <option>Library</option>
                        <option>SCU Website</option>
                    </select>
                </div>
                <div class="form-group blue-text">
                    <label for="set-title">Title</label>
                    <input type="text" class="form-control" id="set-title" placeholder="Enter a descriptive title for your report" name="title">
                </div>
                <div class="form-group blue-text">
                    <label for="select-bug-type">Bug Type</label>
                    <select class="form-control" id="select-type" name="bug-type">
                        <option value="" disabled selected>Select a bug type</option>
                        <option>Security</option>
                        <option>Crash/Hang/Data Loss</option>
                        <option>Power</option>
                        <option>Performance</option>
                        <option>UI/Usability</option>
                        <option>Serious Bug</option>
                        <option>Other Bug</option>
                    </select>
                </div>
                <div class="form-group blue-text">
                    <label for="select-reproducability">Reproducability</label>
                    <select class="form-control" id="select-rep" name="rep">
                        <option value="" disabled selected>Select a reproducability</option>
                        <option>Always</option>
                        <option>Sometimes</option>
                        <option>Rarely</option>
                        <option>Unable</option>
                        <option>I Did Not Try</option>
                    </select>
                </div>
                <div class="form-group blue-text">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" rows="16" name="description">Summary:&#10;&#10;&#10;Steps to Reproduce:&#10;&#10;&#10;Expected Result:&#10;&#10;&#10;Actual Result:&#10;&#10;&#10;Browser/System:&#10;</textarea>
                </div>
                <input type="submit" class="btn btn-primary btn-lg submit-btn blue" value="Submit">
            </form>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
