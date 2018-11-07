<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login.css">

    <title>Squasher - Login</title>
</head>

<body>
    <div class="container">
        <div class="center rounded light-gray">
            <div class="rounded dark-gray">
                <img src="../statics/Code_Bug-512.png" class="img-fluid">
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
                <p class="create"><a href="../html/create.html">Create</a> a new account</p>
            </div>
        </div>
    </div>


    <?php
       session_destroy();
       session_start();

       if($_SERVER["REQUEST_METHOD"] == "POST") {
          // username and password sent from form

          $conn=oci_connect( 'psanchez','a47k7S4QOi', '//dbserver.engr.scu.edu/db11g' );
          if(!$conn) {
              print "<br> connection failed:";
              exit;
          }

          $username = $_POST["username"];
          $password = $_POST["password"];

          $queryString = "SELECT COUNT(username) FROM squasher_user WHERE username = '$username' and password = '$password'";
          $query = oci_parse($conn, $queryString);

	        oci_execute($query);

	      $row = oci_fetch_array($query, OCI_BOTH);

        if($row[0] == 0) {
           $error = "Your Login Name or Password is invalid";
           print_r($error);
        }else {
          //verified user
          $_SESSION['username'] = $username;

          header("location: ../php/home.php");
        }
       }
    ?>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
