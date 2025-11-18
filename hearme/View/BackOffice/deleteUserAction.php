<?php
require_once __DIR__ . '/../../Controller/UserController.php';

$controller = new UserController();
$controller->delete($_GET['id']);

header("Location: admin-users.php?deleted=1");
exit;
