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
        <p>Create an internal account</p>
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
                    <div class="form-group blue-text">
                        <label for="select-role">Role</label>
                        <select class="form-control" id="select-role" name="role" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="TESTER">Tester</option>
                            <option value="DEVELOPER">Developer</option>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary blue" value="Create">
                    <a type="button" class="btn btn-secondary white" href="home.php">Cancel</a>
                </form>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    include '../connection.php';
                    $conn = connect();
                    if (!$conn) {
                        print "<br> connection failed:";
                        exit;
                    }

                    $username = $_POST["username"];
                    $password = $_POST["password"];
                    $email = $_POST["email"];
                    $role = $_POST["role"];

                    $hashedPassword = hash("sha256",$password);

                    $queryString = "SELECT COUNT(username) FROM squasher_user WHERE username = '$username'";
                    $query = oci_parse($conn, $queryString);
                    oci_execute($query);

                    $row = oci_fetch_array($query, OCI_BOTH);

                    if ($row[0] != 0) {
                        echo '<p class="center-text error-message">Username Already Exists</p>';
                    } else {
                        $queryString = "insert into squasher_user values ('$username', '$email', '$hashedPassword', '$role', 0, 0)";

                        $query = oci_parse($conn, $queryString);
                        oci_execute($query);

                        OCILogoff($conn);

                        header("Location: home.php");
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
