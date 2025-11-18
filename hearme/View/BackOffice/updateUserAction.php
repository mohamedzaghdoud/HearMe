<?php
require_once __DIR__ . '/../../Controller/UserController.php';

$controller = new UserController();
$result = $controller->adminUpdateUser($_POST);

if ($result['success']) {
    header("Location: admin-users.php?updated=1");
    exit;
}

$errors = http_build_query(['errors' => $result['errors']]);
header("Location: updateUser.php?id=".$_POST['id']."&$errors");
exit;
