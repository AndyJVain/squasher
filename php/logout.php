<!--
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
  Purpose: This file destroys the PHP session. (Logs out)
 -->

<?php
session_start();
session_destroy();
header("location: ../php/pages/login.php");
?>
