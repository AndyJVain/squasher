<!--
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
  Purpose: This file displays the form which allows a reporter to create an internal user. Once submitted, the user is inserted into the database.
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
    <link rel="stylesheet" href="../../css/create.css">

    <title>Squasher - Create Account</title>
</head>

<body>
    <div class="container">
        <p>Create an account</p>
        <div class="center rounded light-gray">
            <div class="form light-gray">
                <form method="post">
                    <div class="form-group">
                        <label for="username" class="blue-text">Username</label>
                        <input type="username" class="form-control" id="username" aria-describedby="Username" placeholder="Enter Username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="blue-text">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Create Password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="blue-text">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" required>
                    </div>
                    <input type="submit" class="btn btn-primary blue" value="Create">
                    <a type="button" class="btn btn-secondary white" href="login.php">Cancel</a>
                </form>
                <?php
                include '../connection.php';
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $conn=connect();
                    if (!$conn) {
                        print "<br> connection failed:";
                        exit;
                    }
                    include 'clean-input.php';

                    //Fetch new account information from POST
                    $username = clean($_POST["username"]);
                    $password = clean($_POST["password"]);
                    $email = $_POST["email"];

                    //Hash password for security purposes
                    $hashedPassword = hash("sha256", $password);

                    //Prepare query to check for duplicate accounts
                    $queryString = "SELECT COUNT(username) FROM squasher_user WHERE username = '$username'";
                    $query = oci_parse($conn, $queryString);
                    oci_execute($query);
                    $row = oci_fetch_array($query, OCI_BOTH);

                    //Check for different accounts
                    if ($row[0] != 0) {
                        //If a duplicate exists, do not create the account and raise an error
                        echo '<p class="center-text error-message">Username Already Exists</p>';
                        OCILogoff($conn);
                    } else {
                        //Otherwise commit the new account to the database
                        $queryString = "insert into squasher_user values ('$username', '$email', '$hashedPassword', 'REPORTER', 0, 0)";
                        $query = oci_parse($conn, $queryString);
                        oci_execute($query);
                        OCILogoff($conn);
                        header("Location: login.php");
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
