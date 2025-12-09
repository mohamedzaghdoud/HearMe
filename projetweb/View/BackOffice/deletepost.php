<?php
include '../../controller/postController.php';
$postC = new postController();
$postC->deletepost($_GET["id"]);
header('Location: postList.php');
?>


