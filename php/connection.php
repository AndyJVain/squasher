<?php
function connect() {
  include 'config.php';
  return oci_connect($database_username, $database_password, $database_link);
}
?>
