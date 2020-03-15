<?php

require_once('includes/functions.php');
require_once('includes/sessions.php');

$_SESSION['userId'] = null;
$_SESSION['userName'] = null;
$_SESSION['adminName'] = null;

session_destroy();

Redirect_to('login.php');
