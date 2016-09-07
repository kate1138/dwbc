<?php
  require_once 'include/global.inc.php';
?>
<html>
<title>DWBC - Log Out</title>
<body>
  <?php $UT->user_log_out();?>
  <?php include 'include/header.php'; ?>
  So long!<br><br>
  Return to <a href="index.php">Home</a>.<br>
</body>

</html>
