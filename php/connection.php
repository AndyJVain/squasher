<?php
include 'config.php';
function connect() {
  return oci_connect($database_username, $database_password, $database_link);
}
?>
