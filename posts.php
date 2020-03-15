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
    <title>Posts</title>
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
            <a href="index.html" class="navbar-brand">MEINJAM.COM</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="MyProfile.php" class="nav-link">
                            <i class="fas fa-user text-success"></i> My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="Dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item active">
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
                        <a href="Logout.php" class="nav-link">
                            <i class="fas fa-user-times text-danger"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div style="height: 6px; background: #27aae1;"></div>

    <!-- Header -->
    <header class="bg-dark text-white py-3 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h1>
                        <i class="fas fa-blog" style="color: #27aae1;"></i> Blog Posts
                    </h1>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="add_new_post.php" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Add new post
                    </a>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="Categories.php" class="btn btn-info btn-block">
                        <i class="fas fa-folder-plus"></i> Add new category
                    </a>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="admins.php" class="btn btn-warning btn-block">
                        <i class="fas fa-user-plus"></i> Add new admin
                    </a>
                </div>
                <div class="col-lg-3 mb-2">
                    <a href="comments.php" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Approve comments
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
            </div>
        </div>
    </div>

    <!-- Main Area -->
    <section class="py-2 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date & Time</th>
                                <th>Author</th>
                                <th>Banner</th>
                                <th>Comments</th>
                                <th>Action</th>
                                <th>Live Preview</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM posts ORDER BY id desc";
                            $stmt = $ConnectingDB->query($sql);
                            $serial = 0;
                            while ($rows = $stmt->fetch()) :
                                $id = $rows['id'];
                                $title = $rows['title'];
                                $category = $rows['category'];
                                $datetime = $rows['datetime'];
                                $admin = $rows['author'];
                                $image = $rows['image'];
                                $posttext = $rows['post'];
                                $serial++;
                            ?>
                                <tr>
                                    <td><?php echo $serial; ?></td>
                                    <td>
                                        <?php
                                        if (strlen($title) > 20) {
                                            $title = substr($title, 0, 15) . '...';
                                        }
                                        echo $title;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($category) > 8) {
                                            $category = substr($category, 0, 8) . '...';
                                        }
                                        echo $category;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($datetime) > 11) {
                                            $datetime = substr($datetime, 0, 11) . '...';
                                        }
                                        echo $datetime;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (strlen($admin) > 6) {
                                            $admin = substr($admin, 0, 6) . '...';
                                        }
                                        echo $admin;
                                        ?>
                                    </td>
                                    <td><img src="uploads/<?php echo $image ?>" alt="image" width="120px;"></td>
                                    <td>
                                        <?php $approvedComments = approvedCommentList($id);
                                        if ($approvedComments > 0) : ?>
                                            <span class="badge badge-success">
                                                <?php echo htmlentities($approvedComments) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php $disApprovedComments = disApprovedCommentList($id);
                                        if ($disApprovedComments > 0) : ?>
                                            <span class="badge badge-danger">
                                                <?php echo htmlentities($disApprovedComments) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="editpost.php?id=<?php echo $id; ?>"><span class="btn btn-secondary">Edit</span></a>
                                        <a href="deletepost.php?id=<?php echo $id; ?>"><span class="btn btn-danger">Delete</span></a>
                                    </td>
                                    <td>
                                        <a href="fullpost.php?id=<?php echo $id; ?>" target="_blank"><span class="btn btn-primary">See live</span></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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