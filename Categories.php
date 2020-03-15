<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

$_SESSION['trackURL'] = $_SERVER['PHP_SELF'];  //for 

confirmLogin();

if (isset($_POST['submit'])) {
    $Category = $_POST['categorytitle'];
    $Admin = $_SESSION['userName'];
    date_default_timezone_set('Asia/Dhaka');
    $CurrentTime = time();
    $DateTime = strftime('%B %d, %Y %H:%M:%S', $CurrentTime);

    if (strlen($Category) < 3) {
        $_SESSION['ErrorMessage'] = 'Category title should be greter than two characters.';
        Redirect_to('Categories.php');
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $Category)) {
        $_SESSION['ErrorMessage'] = 'Category title should be characters only.';
        Redirect_to('Categories.php');
    } elseif (strlen($Category) > 49) {
        $_SESSION['ErrorMessage'] = 'Category title should be less than 50 characters.';
        Redirect_to('Categories.php');
    } else {
        $sql = "INSERT INTO category(title,author,datetime)";
        $sql .= "VALUES(:categoryName,:adminName,:dateTime)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':categoryName', $Category);
        $stmt->bindValue(':adminName', $Admin);
        $stmt->bindValue(':dateTime', $DateTime);
        $Execute = $stmt->execute();

        if ($Execute) {
            $_SESSION['SuccessMessage'] = 'Category with id ' . $ConnectingDB->lastInsertId() . ' added successfully.';
            Redirect_to('Categories.php');
        } else {
            $_SESSION['ErrorMessage'] = 'Something went wrong, please try again.';
            Redirect_to('Categories.php');
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
                    <li class="nav-item">
                        <a href="MyProfile.php" class="nav-link"> <i class="fas fa-user text-success"></i> My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="Dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="Posts.php" class="nav-link">Posts</a>
                    </li>
                    <li class="nav-item active">
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
                    <h1><i class="fas fa-edit" style="color: #27aae1;"></i> Manage Categories</h1>
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
                <form action="Categories.php" method="post" class="mt-3 pb-4">
                    <div class="card bg-secondary text-light">
                        <div class="card-header">
                            <h1>Add New Category</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title"><span class="fieldinfo"> Category Title: </span></label>
                                <input type="text" id="title" name="categorytitle" placeholrder="Type title here" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button class="btn btn-success btn-block" name="submit"><i class="fas fa-check"></i> Publish</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <h2 class="my-3">Existing Categories:</h2>
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Category Name</th>
                            <th>Date & Time</th>
                            <th>Creator Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    $sql = "SELECT * FROM category ORDER BY id desc";
                    $stmt = $ConnectingDB->query($sql);
                    $serial = 0;

                    while ($rows = $stmt->fetch()) {
                        $id = $rows['id'];
                        $datetime = $rows['datetime'];
                        $categoryName = $rows['title'];
                        $author = $rows['author'];
                        $serial++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($serial); ?></td>
                                <td class="text-justify"><?php echo htmlentities($categoryName); ?></td>
                                <td class="text-justify"><?php echo htmlentities($datetime); ?></td>
                                <td class="text-justify"><?php echo htmlentities($author); ?></td>
                                <td><a href="deletecategory.php?id=<?php echo htmlentities($id); ?>" class="btn btn-danger">Delete</a></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </section>

    <?php include('templete/footer.php'); ?>

</html>