<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: ../FrontOffice/index.html');
exit;
