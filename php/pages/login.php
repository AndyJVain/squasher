<!--
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
  Purpose: This file displays the login page.
           If a valid username and password combination is submitted, the user will be redirected to the home view.
           A redirect to reporter account creation is also accesible.
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/login.css">

    <title>Squasher - Login</title>
</head>

<body>
    <div class="container">
        <div class="center rounded light-gray">
            <div class="rounded dark-gray">
                <img src="../../statics/homepage-icon.png" class="img-fluid">
            </div>
            <div class="form light-gray">
                <form method="post">
                    <div class="form-group">
                        <label for="username">Login</label>
                        <input type="username" class="form-control" id="username" aria-describedby="Username" placeholder="Username" name="username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" aria-describedby="Password" placeholder="Password" name="password">
                    </div>
                    <input type="submit" class="btn btn-primary btn-block blue" value="Login">
                </form>
                <p class="center-text"><a href="create-reporter.php">Create</a> a new account</p>

                <?php
                //Creates a new session if one does not already exist
                if (isset($_SESSION['username'])) {
                    session_destroy();
                }
                session_start();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    include '../connection.php';
                    $conn = connect();
                    if (!$conn) {
                        print "<br> connection failed:";
                        exit;
                    }
                    include '../clean-input.php';

                    //Fetch the input user data from POST
                    $username = clean($_POST["username"]);
                    $password = clean($_POST["password"]);

                    //Hash password for security reasons
                    $hashedPassword = hash("sha256", $password);

                    //Prepare and execute query to authenticate user
                    $queryString = "SELECT COUNT(username) FROM squasher_user WHERE username = '$username' and password = '$hashedPassword'";
                    $query = oci_parse($conn, $queryString);
                    oci_execute($query);
                    $row = oci_fetch_array($query, OCI_BOTH);

                    //Authenticate user
                    if ($row[0] == 0) {
                        //If user cannot be authenticated, do not grant them a session and raise an error
                        echo '<p class="center-text error-message">Incorrect Username or Password</p>';
                    } else {
                        //Else user has been authenticated so create a username variable in their session
                        $_SESSION['username'] = $username;

                        //Also create a role variable in their session
                        $query = oci_parse($conn, "select role from squasher_user where username = '$username'");
                        oci_execute($query);
                        $row = oci_fetch_array($query, OCI_BOTH);
                        $_SESSION['role'] = $row[0];

                        OCILogoff($conn);
                        header("location: home.php");
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
