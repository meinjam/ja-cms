<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

$_SESSION['trackURL'] = $_SERVER['PHP_SELF'];

confirmLogin();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Manage Comments</title>
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
                    <li class="nav-item ">
                        <a href="MyProfile.php" class="nav-link">
                            <i class="fas fa-user text-success"></i> My Profile
                        </a>
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
                    <li class="nav-item active">
                        <a href="Comments.php" class="nav-link">Comments</a>
                    </li>
                    <li class="nav-item">
                        <a href="Blog.php?page=1" class="nav-link">Live Blog</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="Logout.php" class="nav-link">
                            <i class="fas fa-user-times text-danger"></i> Logout</a>
                    </li>
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
                        <i class="fas fa-comments" style="color: #27aae1;"></i> Manage Comments
                    </h1>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <section class="container py-2 mb-4">
         <div class="row"> <!-- style="min-height: 30px;" -->
            <div class="col-lg-12"> <!-- style="min-height: 400px;" -->
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <h2 class="my-3">Dis-Approved Comments</h2>
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Date & Time</th>
                            <th>Comment</th>
                            <th>Action</th>
                            <th>Delete</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <?php
                    $sql = "SELECT * FROM comments WHERE status='OFF' ORDER BY id desc";
                    $stmt = $ConnectingDB->query($sql);
                    $serial = 0;

                    while ($rows = $stmt->fetch()) {
                        $id = $rows['id'];
                        $datetime = $rows['datetime'];
                        $commenterName = $rows['name'];
                        $comment = $rows['comment'];
                        $postId = $rows['post_id'];
                        $serial++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($serial); ?></td>
                                <td class="text-justify"><?php echo htmlentities($commenterName); ?></td>
                                <td class="text-justify"><?php echo htmlentities($datetime); ?></td>
                                <td class="text-justify"><?php echo htmlentities($comment); ?></td>
                                <td><a href="approvecomment.php?id=<?php echo htmlentities($id); ?>" class="btn btn-success">Approve</a></td>
                                <td><a href="deletecomment.php?id=<?php echo htmlentities($id); ?>" class="btn btn-danger">Delete</a></td>
                                <td style="min-width: 140px;"><a class="btn btn-primary" href="fullpost.php?id=<?php echo htmlentities($postId); ?>" target="_blank">Live Preview</a></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
                <!-- approved comments -->
                <h2>Approved Comments</h2>
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Date & Time</th>
                            <th>Comment</th>
                            <th>Action</th>
                            <th>Delete</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <?php
                    $sql = "SELECT * FROM comments WHERE status='ON' ORDER BY id desc";
                    $stmt = $ConnectingDB->query($sql);
                    $serial = 0;

                    while ($rows = $stmt->fetch()) {
                        $id = $rows['id'];
                        $datetime = $rows['datetime'];
                        $commenterName = $rows['name'];
                        $comment = $rows['comment'];
                        $postId = $rows['post_id'];
                        $serial++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($serial); ?></td>
                                <td class="text-justify"><?php echo htmlentities($commenterName); ?></td>
                                <td class="text-justify"><?php echo htmlentities($datetime); ?></td>
                                <td class="text-justify"><?php echo htmlentities($comment); ?></td>
                                <td><a href="disapprovecomment.php?id=<?php echo htmlentities($id); ?>" class="btn btn-warning">Disapprove</a></td>
                                <td><a href="deletecomment.php?id=<?php echo htmlentities($id); ?>" class="btn btn-danger">Delete</a></td>
                                <td style="min-width: 140px;"><a class="btn btn-primary" href="fullpost.php?id=<?php echo htmlentities($postId); ?>" target="_blank">Live Preview</a></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
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