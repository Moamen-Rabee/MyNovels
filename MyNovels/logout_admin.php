<?php
ob_start();
session_start();
session_unset();
session_destroy();
header("Location: admin.php");
ob_end_flush();
?>