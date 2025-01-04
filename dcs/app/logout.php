<?php
session_start();
$_SESSION = [];
header(header: "Location: ./");
exit();
?>