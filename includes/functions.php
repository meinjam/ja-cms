<?php

require_once('includes/db.php');

function Redirect_to($New_Location)
{
    header("Location:" . $New_Location);
    exit;
}

function checkUsernameExistsOrNot($username)
{
    global $ConnectingDB;
    $sql = "SELECT username FROM admins WHERE username=:username ";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $Result = $stmt->rowcount();
    if ($Result == 1) {
        return true;
    } else {
        return false;
    }
}

function loginCheck($username, $password)
{
    global $ConnectingDB;
    $sql = "SELECT * FROM admins WHERE username=:username AND password=:password LIMIT 1";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password);
    $stmt->execute();
    $Result = $stmt->rowcount();
    if ($Result == 1) {
        return $tableInformation = $stmt->fetch();
    } else {
        return null;
    }
}

function confirmLogin()
{
    if (isset($_SESSION['userId'])) {

        return true;
    } else {

        $_SESSION['ErrorMessage'] = 'Login required.';
        Redirect_to('login.php');
    }
}


//////////////////////////////////////
function totalPosts()
{
    global $ConnectingDB;
    $sql = "SELECT COUNT(*) FROM posts";
    $stmt = $ConnectingDB->query($sql);
    $rows = $stmt->fetch();
    $totalPosts = array_shift($rows);
    echo $totalPosts;
}

function totalCategories()
{
    global $ConnectingDB;
    $sql = "SELECT COUNT(*) FROM category";
    $stmt = $ConnectingDB->query($sql);
    $rows = $stmt->fetch();
    $totalCategories = array_shift($rows);
    echo $totalCategories;
}

function totalAdmins()
{
    global $ConnectingDB;
    $sql = "SELECT COUNT(*) FROM admins";
    $stmt = $ConnectingDB->query($sql);
    $rows = $stmt->fetch();
    $totalAdmins = array_shift($rows);
    echo $totalAdmins;
}

function totalComments()
{
    global $ConnectingDB;
    $sql = "SELECT COUNT(*) FROM comments";
    $stmt = $ConnectingDB->query($sql);
    $rows = $stmt->fetch();
    $totalComments = array_shift($rows);
    echo $totalComments;
}
//////////////////////////////////////


//////////////////////////////////////
function approvedCommentList($id)
{
    global $ConnectingDB;
    $sql1 = "SELECT COUNT(*) FROM comments WHERE post_id='$id' AND status='ON'";
    $stmt1 = $ConnectingDB->query($sql1);
    $rows1 = $stmt1->fetch();
    $approvedComments = array_shift($rows1);
    return $approvedComments;
}

function disApprovedCommentList($id)
{
    global $ConnectingDB;
    $sql1 = "SELECT COUNT(*) FROM comments WHERE post_id='$id' AND status='OFF'";
    $stmt1 = $ConnectingDB->query($sql1);
    $rows1 = $stmt1->fetch();
    $disApprovedComments = array_shift($rows1);
    return $disApprovedComments;
}
//////////////////////////////////////
