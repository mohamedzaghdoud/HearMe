<?php
// actions/register-action.php
require_once __DIR__ . '/../Controller/UserController.php';
$ctrl = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = $ctrl->register($_POST);
    if ($res['success']) {
        header('Location: ../View/FrontOffice/register-validation.php'); exit;
    } else {
        $err = urlencode(json_encode($res['errors']));
        header('Location: ../View/FrontOffice/register.php?errors=' . $err); exit;
    }
}
header('Location: ../View/FrontOffice/register.php'); exit;
