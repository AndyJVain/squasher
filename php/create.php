<html>
<body>

<?php
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $queryString = "insert into squasher_user values ('$username', '$email', '$password', 'REPORTER')";

  $binderVariable = 'Connor';

  echo($queryString);

  $conn=oci_connect( 'psanchez','lmaogogo', '//dbserver.engr.scu.edu/db11g' );
  if(!$conn) {
      print "<br> connection failed:";
      exit;
  }

  $query = oci_parse($conn, $queryString);
  oci_bind_by_name($query, ':title', $binderVariable);
  oci_execute($query);
  OCILogoff($conn);

  echo($binderVariable);

?>

<a type="button" class="btn btn-secondary white" href="html/login.html">Return to Login</a>

</body>
</html>
