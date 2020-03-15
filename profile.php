<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

$urlName = $_GET['name'];

global $ConnectingDB;
$sql = "SELECT aname,headline,bio,image,datetime FROM admins WHERE username=:username ";
$stmt = $ConnectingDB->prepare($sql);
$stmt->bindValue(':username', $urlName);
$stmt->execute();
$Result = $stmt->rowcount();
if ($Result == 1) {
    while ($rows = $stmt->fetch()) {
        $adminName = $rows['aname'];
        $adminHeadline = $rows['headline'];
        $adminBio = $rows['bio'];
        $adminImage = $rows['image'];
        $adminJoin = $rows['datetime'];
    }
} else {
    $_SESSION['ErrorMessage'] = 'Bad request.';
    Redirect_to('blog.php?page=1');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile of - <?php echo $adminName; ?></title>
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

    <!-- Profile Section -->
    <section class="container my-5">
        <div class="row">
            <div class="col-md-3">
                <img src="img/<?php echo $adminImage; ?>" class="img img-fluid rounded-circle"  alt="profile avatar">
            </div>
            <div class="col-md-4 mt-4">
                <h6 class="badge badge-dark text-light"><?php echo $adminHeadline; ?></h6>
                <h2><?php echo $adminName; ?></h2>
                <p class="text-justify"><?php echo $adminBio; ?></p>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <p><i class="fas fa-user"></i> Member since: <?php echo $adminJoin; ?></p>
                    </div>
                </div>
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