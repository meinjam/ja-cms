<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

confirmLogin();

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $admin = $_SESSION['userName'];
    date_default_timezone_set('Asia/Dhaka');
    $CurrentTime = time();
    $DateTime = strftime('%B %d, %Y %H:%M:%S', $CurrentTime);

    if (empty($username) || empty($password) || empty($name)) {
        $_SESSION['ErrorMessage'] = 'All fields must be filled out.';
        Redirect_to('admins.php');
    } elseif (strlen($password) < 4) {
        $_SESSION['ErrorMessage'] = 'Password should be greter than 3 characters.';
        Redirect_to('admins.php');
    } elseif ($password !== $confirmpassword) {
        $_SESSION['ErrorMessage'] = 'Password and Confirm Password does not match.';
        Redirect_to('admins.php');
    } elseif (checkUsernameExistsOrNot($username)) {
        $_SESSION['ErrorMessage'] = 'Username exists. Try another one!';
        Redirect_to('admins.php');
    } else {
        $sql = "INSERT INTO admins(datetime,username,password,aname,addedby)";
        $sql .= "VALUES(:dateTime,:username,:password,:aname,:admin)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':dateTime', $DateTime);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':aname', $name);
        $stmt->bindValue(':admin', $admin);
        $Execute = $stmt->execute();

        if ($Execute) {
            $_SESSION['SuccessMessage'] = "New admin with the name of " . $name . " added successfully.";
            Redirect_to('admins.php');
        } else {
            $_SESSION['ErrorMessage'] = 'Something went wrong, please try again.';
            Redirect_to('admins.php');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://kit.fontawesome.com/c04ce94384.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Navbar Start -->

    <div style="height: 6px; background: #27aae1;"></div>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a href="index.html" class="navbar-brand">MEINJAM.COM</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="MyProfile.php" class="nav-link"> <i class="fas fa-user text-success"></i> My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="Dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="Posts.php" class="nav-link">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a href="Categories.php" class="nav-link">Categories</a>
                    </li>
                    <li class="nav-item active">
                        <a href="Admins.php" class="nav-link">Manage Admins</a>
                    </li>
                    <li class="nav-item">
                        <a href="Comments.php" class="nav-link">Comments</a>
                    </li>
                    <li class="nav-item">
                        <a href="Blog.php?page=1" class="nav-link">Live Blog</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="Logout.php" class="nav-link"> <i class="fas fa-user-times text-danger"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div style="height: 6px; background: #27aae1;"></div>

    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1><i class="fas fa-user" style="color: #27aae1;"></i> Manage Admins</h1>
                </div>
            </div>
        </div>
    </header>

    <section class="container py-2 mb-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <form action="admins.php" method="post" class="pb-3">
                    <div class="card bg-secondary text-light">
                        <div class="card-header">
                            <h1>Add New Admin</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="username"><span class="fieldinfo"> Username: </span></label>
                                <input class="form-control" type="text" name="username" id="username">
                            </div>
                            <div class="form-group">
                                <label for="name"><span class="fieldinfo"> Name: </span></label>
                                <input class="form-control" type="text" name="name" id="name">
                                <!-- <small class="text-danger text-muted">Optional</small> -->
                            </div>
                            <div class="form-group">
                                <label for="password"><span class="fieldinfo"> Password: </span></label>
                                <input class="form-control" type="password" name="password" id="password">
                            </div>
                            <div class="form-group">
                                <label for="confirmpassword"><span class="fieldinfo">Confirm Password: </span></label>
                                <input class="form-control" type="password" name="confirmpassword" id="confirmpassword">
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button class="btn btn-success btn-block" name="submit"><i class="fas fa-check"></i> Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <h2 class="my-3">Existing Admins:</h2>
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Username</th>
                            <th>Date & Time</th>
                            <th>Admin Name</th>
                            <th>Added By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    $sql = "SELECT * FROM admins ORDER BY id desc";
                    $stmt = $ConnectingDB->query($sql);
                    $serial = 0;

                    while ($rows = $stmt->fetch()) {
                        $id = $rows['id'];
                        $datetime = $rows['datetime'];
                        $username = $rows['username'];
                        $aname = $rows['aname'];
                        $addedby = $rows['addedby'];
                        $serial++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($serial); ?></td>
                                <td class="text-justify"><?php echo htmlentities($username); ?></td>
                                <td class="text-justify"><?php echo htmlentities($datetime); ?></td>
                                <td class="text-justify"><?php echo htmlentities($aname); ?></td>
                                <td class="text-justify"><?php echo htmlentities($addedby); ?></td>
                                <td><a href="deleteadmins.php?id=<?php echo htmlentities($id); ?>" class="btn btn-danger">Delete</a></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </section>

    <?php include('templete/footer.php'); ?>

</html>