<!--
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
-->

<?php

/*
  Function Name: emailReporter
  Arguments: bug_id (type == mixed)
  Purpose: Uses the bug id and retrieves the associated email address from the database. An email is then sent.
  Returns: Null
*/
function clean($string) {
   $string = str_replace('\'', '', $string); // Remove all apostrophes.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
?>
