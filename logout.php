<?php
session_start();

// svuotamento parametri sessione
$_SESSION = array();

session_destroy();

header("Location: login.php");
exit();
?>