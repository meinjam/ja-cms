<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

$_SESSION['trackURL'] = $_SERVER['PHP_SELF'];

confirmLogin();

$urlId = $_GET['id'];

if (isset($urlId)) {

    $sql = "DELETE FROM category WHERE id = '$urlId'";
    $Execute = $ConnectingDB->query($sql);

    if ($Execute) {

        $_SESSION['SuccessMessage'] = 'Category deleted successfully.';
        Redirect_to('Categories.php');
    } else {

        $_SESSION['ErrorMessage'] = 'Something went wrong, please try again leter.';
        Redirect_to('Categories.php');
    }
}
