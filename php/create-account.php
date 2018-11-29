<html>
<body>

<?php
$username = $_POST["username"];
$password = $_POST["password"];
$email = $_POST["email"];

$queryString = "insert into squasher_user values ('$username', '$email', '$password', 'REPORTER')";

$binderVariable = 'Connor';

include '../connection.php';
$conn = connect();
if (!$conn) {
    print "<br> connection failed:";
    exit;
}

$query = oci_parse($conn, $queryString);
oci_bind_by_name($query, ':title', $binderVariable);
oci_execute($query);

OCILogoff($conn);

header("Location: pages/login.php");
?>

</body>
</html>
