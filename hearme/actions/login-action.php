<?php
// actions/login-action.php
require_once __DIR__ . '/../Controller/UserController.php';
$ctrl = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = $ctrl->login($_POST);
    if ($res['success']) {
        session_start();
        $role = $_SESSION['user']['role'] ?? 'user';
        if ($role === 'admin') header('Location: ../View/BackOffice/admin-dashboard.php');
        else header('Location: ../View/BackOffice/user-dashboard.php');
        exit;
    } else {
        $err = urlencode(json_encode($res['errors']));
        header('Location: ../View/FrontOffice/login.php?errors=' . $err); exit;
    }
}
header('Location: ../View/FrontOffice/login.php'); exit;
