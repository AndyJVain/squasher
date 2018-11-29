<!--
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
 -->

<?php
/*
  Function Name: connect
  Arguments: none
  Purpose: Establishes a connection to the database.
  Returns: oci connection to the database (type == a connection resource with parameters of strings)
*/
function connect()
{
    include 'config.php';
    return oci_connect($database_username, $database_password, $database_link);
}
?>
