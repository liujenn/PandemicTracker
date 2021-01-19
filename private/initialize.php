<?php
  session_start(); // turn on sessions

  require_once('functions.php');

  define("PRIVATE_P", dirname(__FILE__));
  define("ROOT_P", dirname(PRIVATE_P));
  define("PUBLIC_P", ROOT_P . '/public');

  $conn = OpenCon();
  $errors = [];
?>