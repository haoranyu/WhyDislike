<?php
unset($_SESSION['token']);
unset($_SESSION['email']);
unset($_SESSION['user']);
unset($_SESSION["access_token"]);
header("location:".$_SERVER['HTTP_REFERER']);
?>