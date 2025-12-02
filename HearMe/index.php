<?php
session_start();
if (isset($_GET['admin'])) {
    header("Location: app/controllers/QuestionController.php");
} else {
    header("Location: app/controllers/TestController.php");
}
exit();
?>