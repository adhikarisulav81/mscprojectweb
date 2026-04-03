<!-- logout -->
<?php
 session_start(); //open the session
 session_destroy(); //wipe all login data
 header("Location: index.php"); //send user to index.php
 exit;
?>