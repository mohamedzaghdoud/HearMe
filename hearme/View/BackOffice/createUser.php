<?php
require_once __DIR__ . '/../../Controller/UserController.php';

$controller = new UserController();
$result = $controller->adminAddUser($_POST);

if ($result['success']) {
    header("Location: admin-users.php?success=1");
    exit;
}

$errors = http_build_query(['errors' => $result['errors']]);
header("Location: addUser.php?$errors");
exit;
