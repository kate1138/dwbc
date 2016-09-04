<?php
  require_once 'include/global.inc.php';
?>
<html>
<title>DWBC - Log Out</title>
<body>
  <?php include 'include/header.php'; ?>
  So long!<br><br>
  <?php $UT->user_log_out();?>
  Return to <a href="index.php">Home</a>.<br>
</body>

</html>
