<?php
  session_start();
  $signup_result = "";
  $signup_error_message = "";
  if (isset($_SESSION['signup_result'])) {
      $signup_result = $_SESSION['signup_result'];
      if ($signup_result === 'error') {
          $signup_error_message = $_SESSION['signup_error_message'];
      }
      unset($_SESSION['signup_result']);
      unset($_SESSION['signup_error_message']);
  }
?>
