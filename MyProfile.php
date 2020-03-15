<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

confirmLogin();
//fetching admin info
$adminId = $_SESSION['userId'];
$sql = "SELECT * FROM admins WHERE id='$adminId'";
$stmt = $ConnectingDB->query($sql);
while ($rows = $stmt->fetch()) {
    $adminName = $rows['aname'];
    $adminUserName = $rows['username'];
    $adminHeadline = $rows['headline'];
    $adminBio = $rows['bio'];
    $adminImage = $rows['image'];
}
//admin end

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $headline = $_POST['headline'];
    $bio = $_POST['bio'];
    $Image = $_FILES['Image']['name'];
    $Target = 'img/' . basename($_FILES['Image']['name']);
    $Admin = $_SESSION['userName'];

    if (strlen($headline) > 25) {
        $_SESSION['ErrorMessage'] = 'Headline should be less than 12 characters.';
        Redirect_to('MyProfile.php');
    } elseif (strlen($bio) > 500) {
        $_SESSION['ErrorMessage'] = 'Bio should be less than 500 characters.';
        Redirect_to('MyProfile.php');
    } else {
        if (!empty($Image)) {
            $sql = "UPDATE admins SET
                aname='$name', headline='$headline', image='$Image', bio='$bio'
                WHERE id='$adminId'";
                move_uploaded_file($_FILES['Image']['tmp_name'], $Target);
        } else {
            $sql = "UPDATE admins SET
                aname='$name', headline='$headline', bio='$bio'
                WHERE id='$adminId'";
        }
        $Execute = $ConnectingDB->query($sql);

        if ($Execute) {
            $_SESSION['SuccessMessage'] = 'Profile updated successfully.';
            Redirect_to('MyProfile.php');
        } else {
            $_SESSION['ErrorMessage'] = 'Something went wrong, please try again.';
            Redirect_to('MyProfile.php');
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
    <title>CMS Project</title>
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
                    <li class="nav-item active">
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
                    <li class="nav-item">
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
                    <h1><i class="fas fa-user text-success"></i> @<?php echo htmlentities($adminUserName); ?> </h1>
                    <small><?php echo htmlentities($adminHeadline); ?></small>
                </div>
            </div>
        </div>
    </header>

    <section class="container py-2 mt-3 mb-5">
        <div class="row">
            <div class="offset-md-1 col-md-3 mb-3">
                <div class="card">
                    <div class="card-header bg-dark text-light">
                        <h3 class="text-center"><?php echo htmlentities($adminName); ?></h3>
                    </div>
                    <div class="card-body">
                        <img src="img/<?php echo htmlentities($adminImage); ?>" class="img-fluid block mb-3" alt="profile image">
                        <p class="text-justify"><?php echo htmlentities($adminBio); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <form action="MyProfile.php" method="post" enctype="multipart/form-data">
                    <div class="card text-light">
                        <div class="card-header bg-dark text-light">
                            <h3>Edit Profile</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input class="form-control" type="text" name="name" placeholder="Your name here">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="headline" placeholder="Headline">
                                <small class="text-muted">Add a professional headline like, 'Engineer' at ABC or 'Architect' at XYZ.</small>
                            </div>
                            <div class="form-group">
                                <textarea name="bio" placeholder="Bio" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" name="Image" placeholder="image" class="custom-file-input">
                                    <label for="Image" class="custom-file-label">Select profile picture</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button class="btn btn-success btn-block" name="submit"><i class="fas fa-check"></i> Update Profile
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </section>

    <?php include('templete/footer.php'); ?>

</html>