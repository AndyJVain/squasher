<!--
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
 -->

<?php
/*
  Function Name: connect
  Arguments: none
  Purpose: Returns an oci connection to the database.
*/
function connect()
{
    include 'config.php';
    return oci_connect($database_username, $database_password, $database_link);
}
?>
