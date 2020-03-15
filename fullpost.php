<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

$idForComments = $_GET['id'];

if (isset($_POST['submit'])) {
    $name = $_POST['commenterName'];
    $email = $_POST['commenterEmail'];
    $comment = $_POST['comment'];
    date_default_timezone_set('Asia/Dhaka');
    $CurrentTime = time();
    $DateTime = strftime('%B %d, %Y %H:%M:%S', $CurrentTime);

    if (empty($name) || empty($email) || empty($comment)) {
        $_SESSION['ErrorMessage'] = 'all fields must be filled out.';
        Redirect_to("fullpost.php?id=$idForComments");
    } elseif (strlen($comment) > 500) {
        $_SESSION['ErrorMessage'] = 'Comment length should be less than 500 characters.';
        Redirect_to("fullpost.php?id=$idForComments");
    } else {
        $sql = "INSERT INTO comments(datetime,name,email,comment,approvedby,status,post_id)";
        $sql .= "VALUES(:dateTime,:name,:email,:comment,'Pending','OFF',:postId)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':dateTime', $DateTime);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':comment', $comment);
        $stmt->bindValue(':postId', $idForComments);
        $Execute = $stmt->execute();
        // var_dump($Execute);

        if ($Execute) {
            $_SESSION['SuccessMessage'] = 'Comment submitted successfully.';
            Redirect_to("fullpost.php?id=$idForComments");
        } else {
            $_SESSION['ErrorMessage'] = 'Something went wrong, please try again.';
            Redirect_to("fullpost.php?id=$idForComments");
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
    <link rel="stylesheet" href="img/favicon.png" />
    <script src="https://kit.fontawesome.com/c04ce94384.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Navbar Start -->

    <div style="height: 6px; background: #27aae1;"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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

    <!-- Main area start -->
    <div class="container">
        <div class="row">
            <div class="col-md-8 my-4">
                <h2>The Complete Responsive CMS Blog System</h2>
                <h1 class="lead mb-4">the blog system made with PHP by INJAM</h1>
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <?php
                //for sesrch
                if (isset($_GET['sraechbtn'])) {
                    $search = $_GET['search'];
                    $sql = "SELECT * FROM posts WHERE datetime LIKE :search OR title LIKE :search OR category LIKE :search OR post LIKE :search";
                    $stmt = $ConnectingDB->prepare($sql);
                    $stmt->bindValue(':search', '%' . $search . '%');
                    $stmt->execute();
                    //search end
                } else {
                    $urlId = $_GET['id'];
                    if (!isset($urlId)) {
                        $_SESSION['ErrorMessage'] = 'Bad request!!!';
                        Redirect_to('blog.php?page=1');
                    }
                    $sql = "SELECT * FROM posts WHERE id = '$urlId' ";
                    $stmt = $ConnectingDB->query($sql);
                    $Result = $stmt->rowCount();
                    if ($Result != 1) {
                        $_SESSION['ErrorMessage'] = 'Bad request!!!';
                        Redirect_to('blog.php?page=1');
                    }
                }

                while ($rows = $stmt->fetch()) :
                    $id = $rows['id'];
                    $datetime = $rows['datetime'];
                    $title = $rows['title'];
                    $category = $rows['category'];
                    $admin = $rows['author'];
                    $image = $rows['image'];
                    $posttext = $rows['post'];
                ?>
                    <div class="card mb-4">
                        <img src="uploads/<?php echo htmlentities($image); ?>" alt="image" class="img-fluid card-img-top">
                        <div class="card-body">
                            <h2 class="card-title"><?php echo  htmlentities($title); ?></h2>
                            <small class="text-muted">Written by <?php echo  htmlentities($admin); ?> on <?php echo  htmlentities($datetime); ?></small>
                            <span style="float:right;" class="badge badge-dark text-light">
                                Comments <?php echo approvedCommentList($id); ?>
                            </span>
                            <hr>
                            <p class="card-text text-justify">
                                <?php echo  htmlentities($posttext); ?>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
                <!-- Comment start -->
                <div class="mb-3">
                    <span class="fieldinfo">Comments</span>
                </div>
                <?php
                $sql = "SELECT * FROM comments WHERE post_id='$idForComments' AND status='ON'";
                $stmt = $ConnectingDB->query($sql);
                while ($rows = $stmt->fetch()) :
                    $datetime = $rows['datetime'];
                    $name = $rows['name'];
                    $comment = $rows['comment'];
                ?>
                    <div>
                        <div class="media commentblock p-3 rounded mb-3">
                            <img class="d-block img-fluid align-self-start pr-2" src="img/user.jpg" alt="user" width="120">
                            <div class="media-body ml-2">
                                <h6 class="lead"><?php echo $name; ?></h6>
                                <p class="small"><?php echo $datetime; ?></p>
                                <p class="text-justify"><?php echo $comment; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <div>
                    <form action="fullpost.php?id=<?php echo $idForComments; ?>" method="post">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="fieldinfo">Share your thoughts about this post</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input class="form-control" type="text" name="commenterName" placeholder="enter your name" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input class="form-control" type="email" name="commenterEmail" placeholder="enter your email" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                        </div>
                                        <textarea name="comment" class="form-control" placeholder="comment" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-secondary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Comment end -->
            </div>
            <!-- main area end  -->

            <!-- side area start -->
            <div class="col-md-4">
                <div class="card mt-4">
                    <img src="img/blog1.jpg" class="card-img-top d-block img-fluid" alt="blog image">
                    <div class="card-body">
                        <p class="text-justify">Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam eum praesentium quasi, harum dolore rem, odio libero tempora adipisci doloribus et iusto officiis vero soluta commodi omnis minus nemo? Minus, deserunt natus! Quia praesentium inventore debitis maiores ea dignissimos natus possimus, ipsa provident, recusandae enim veritatis voluptatibus nulla. Eius, temporibus.</p>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header bg-dark text-light">
                        <h2 class="lead">Sign Up!</h2>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success btn-block text-center text-white" name="button">Join The Forum</button>
                        <a href="login.php" class="btn btn-danger btn-block text-center text-white my-3"> Log In</a>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="" placeholder="Enter your email">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary">Subscribe now</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-4">
                    <div class="card-header bg-primary text-light">
                        <h2 class="lead">Categories</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $sql = "SELECT * FROM category ORDER BY id desc";
                        $stmt = $ConnectingDB->query($sql);
                        while ($rows = $stmt->fetch()) :
                            $id = $rows['id'];
                            $categoryName = $rows['title'];
                        ?>
                            <a href="blog.php?category=<?php echo htmlentities($categoryName); ?>"><span class="heading"><?php echo htmlentities($categoryName); ?></span></a><br>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h2 class="lead">Recent Posts</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
                        $stmt = $ConnectingDB->query($sql);
                        while ($rows = $stmt->fetch()) {
                            $id = $rows['id'];
                            $title = $rows['title'];
                            $datetime = $rows['datetime'];
                            $image = $rows['image'];
                        ?>
                            <div class="media">
                                <img src="uploads/<?php echo htmlentities($image); ?>" alt="post" class="img-fluid d-block align-self-start" width="90">
                                <div class="media-body ml-2">
                                    <a href="fullpost.php?id=<?php echo htmlentities($id); ?>">
                                        <h6 class="lead"><?php echo htmlentities($title); ?></h6>
                                    </a>
                                    <p class="small"><?php echo htmlentities($datetime); ?></p>
                                </div>
                            </div>
                            <hr>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- side area end -->
        </div>
    </div>

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