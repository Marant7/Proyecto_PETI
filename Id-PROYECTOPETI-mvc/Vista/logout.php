<?php
session_start();
session_destroy();
header('Location: /PETI_proyecto/login.php');
exit();
?>
