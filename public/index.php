<?php
session_start(); // Start the session

// Check if user_id session is set
if (isset($_SESSION["userId"]) && $_SESSION["userId"] != NULL) {
  header("Location: auctions.php");
  exit();
}else{
  header("Location: ../index.php");
  exit();
}
?>
