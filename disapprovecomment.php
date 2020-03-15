<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

$_SESSION['trackURL'] = $_SERVER['PHP_SELF'];

confirmLogin();

$urlId = $_GET['id'];
$admin = $_SESSION['adminName'];

if (isset($urlId)) {

    $sql = "UPDATE comments SET status='OFF', approvedby='$admin' WHERE id='$urlId'";
    $Execute = $ConnectingDB->query($sql);

    if ($Execute) {

        $_SESSION['SuccessMessage'] = 'Comment dis-approved successfully.';
        Redirect_to('comments.php');
    } else {

        $_SESSION['ErrorMessage'] = 'Something went wrong, please try again leter.';
        Redirect_to('comments.php');
    }
}
