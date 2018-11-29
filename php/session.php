<!--
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
  Purpose: This file checks for a PHP session. Otherwise it makes users log in.
 -->

<?php
session_start();

if (isset($_SESSION['username'])) {
    //Allow user to access page
} else {
    // Redirect them to the login page
    header("location: ../pages/login.php");
}
?>
