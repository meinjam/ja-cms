<?php

$DSN = 'mysql:host = localhost; dbname=islamic_library';
$ConnectingDB = new PDO($DSN, 'injam', 'injam2015jsr');

if (!$ConnectingDB) {
    echo 'Connection error: ' . mysqli_connect_error();
}

require_once('includes/functions.php');
require_once('includes/sessions.php');

$fullname = $username = $email = $password = $confirmpassword = $image = '';
$errors = array('fullname' => '', 'username' => '', 'email' => '', 'password' => '', 'confirmpassword' => '', 'image' => '');

if (isset($_POST['submit'])) {

    $fullname = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $image = $_FILES['image']['name'];
    $target = 'img/' . basename($_FILES['image']['name']);
    date_default_timezone_set('Asia/Dhaka');
    $CurrentDate = time();
    $date = strftime('%B %d, %Y', $CurrentDate);
    $currentTime = date('m/d/Y H:i:s');
    $time = date('h:i A', strtotime($currentTime));
    $DateTime = $date . " " . $time;

    //check fullname
    if (empty($fullname) || empty($username) || empty($email) || empty($password) || empty($confirmpassword) || empty($image)) {
        $_SESSION['ErrorMessage'] = 'All fields must be filled out.';
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
        $errors['fullname'] = 'Name must be letter and spaces only.';
    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errors['username'] = 'Username must be characters and number only (no space).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email must be a valid email address.';
    } elseif (!preg_match('/^[\w@-]{8,20}$/', $password)) {
        $errors['password'] = 'Password must alphanumeric (@, _ and - are also allowed) and be 8 - 20 characters.';
    } elseif ($password !== $confirmpassword) {
        $errors['confirmpassword'] = 'Password and Confirm password don\'t match.';
    } else {
        $sql = "INSERT INTO admins(datetime,fullname,username,email,password,image)";
        $sql .= "VALUES(:datetime,:fullname,:username,:email,:password,:image)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':datetime', $DateTime);
        $stmt->bindValue(':fullname', $fullname);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':image', $image);
        $Execute = $stmt->execute();
        // var_dump($Execute); //for check error
        move_uploaded_file($_FILES['Image']['tmp_name'], $target);

        if ($Execute) {
            $_SESSION['SuccessMessage'] = 'Admin added successfully.';
            Redirect_to('register.php');
        } else {
            $_SESSION['ErrorMessage'] = 'Something went wrong, please try again.';
            Redirect_to('register.php');
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
    <title>Registration | CMS Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="img/favicon.png" />
    <script src="https://kit.fontawesome.com/c04ce94384.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Navbar Start -->

    <div style="height: 6px; background: #27aae1;"></div>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a href="index.html" class="navbar-brand"><i class="fas fa-asterisk"></i> MEINJAM.COM</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="blog.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="blog.php?page=1" class="nav-link">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Features</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <form class="form-inline d-none d-sm-block" action="blog.php">
                        <div class="form-group">
                            <input class="form-control mr-2" type="text" name="search" placeholder="Search here">
                            <button class="btn btn-primary" name="sraechbtn">Go</button>
                        </div>
                    </form>
                </ul>
            </div>
        </div>
    </nav>
    <div style="height: 6px; background: #27aae1;"></div>

    <!-- Header -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>
                        <i class="fas fa-user" style="color: #27aae1;"></i> Register for an admin
                    </h1>
                </div>
            </div>
        </div>
    </header>

    <!-- Registration -->
    <section class="container my-3">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <form action="register.php" method="post" enctype="multipart/form-data">
                    <div class="card text-light">
                        <div class="card-header bg-dark text-light">
                            <h3 class="py-2 text-center">Please fill the form with correct information</h3>
                        </div>
                        <div class="card-body" style="background-color: #f4f4f4;">
                            <?php
                            echo ErrorMessage();
                            echo SuccessMessage();
                            ?>
                            <div class="form-group">
                                <label for="name"><span class="fieldinfo">Full Name: </span></label>
                                <input class="form-control" type="text" name="name" id="name" value="<?php echo htmlspecialchars($fullname) ?>">
                                <small class="pl-1" style="color: #f22;"><?php echo $errors['fullname'] ?></small>
                            </div>
                            <div class="form-group">
                                <label for="username"><span class="fieldinfo"> Username: </span></label>
                                <input class="form-control" type="text" name="username" id="username" value="<?php echo htmlspecialchars($username) ?>">
                                <small class="pl-1" style="color: #f22;"><?php echo $errors['username'] ?></small>
                            </div>
                            <div class="form-group">
                                <label for="email"><span class="fieldinfo"> Email: </span></label>
                                <input class="form-control" type="text" name="email" id="email" value="<?php echo htmlspecialchars($email) ?>">
                                <small class="pl-1" style="color: #f22;"><?php echo $errors['email'] ?></small>
                            </div>
                            <div class="form-group">
                                <label for="password"><span class="fieldinfo"> Password: </span></label>
                                <input class="form-control" type="password" name="password" id="password" value="<?php echo htmlspecialchars($password) ?>">
                                <small class="pl-1" style="color: #f22;"><?php echo $errors['password'] ?></small>
                            </div>
                            <div class="form-group">
                                <label for="confirmpassword"><span class="fieldinfo">Confirm Password: </span></label>
                                <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" value="<?php echo htmlspecialchars($confirmpassword) ?>">
                                <small class="pl-1" style="color: #f22;"><?php echo $errors['confirmpassword'] ?></small>
                            </div>
                            <div class="form-group">
                                <label for="image"><span class="fieldinfo"> Profile Picture: </span></label>
                                <div class="custom-file">
                                    <input type="file" name="image" id="image" class="custom-file-input" value="<?php echo htmlspecialchars($image) ?>">
                                    <label for="image" class="custom-file-label"></label>
                                </div>
                                <small class="pl-1" style="color: #f22;"><?php echo $errors['image'] ?></small>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="blog.php?page=1" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to blog</a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button class="btn btn-success btn-block" name="submit"><i class="fas fa-check"></i> Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer Start -->
    <footer class="bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="lead text-center">
                        Themed by | Injamamul Haque | <span id="year"></span> &copy;
                        ---All right Reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script>
        $("#year").text(new Date().getFullYear());
    </script>
</body>

</html>