<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

if (isset($_SESSION['userId'])) {
    Redirect_to('dashboard.php');
}

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {

        $_SESSION['ErrorMessage'] = 'All fields must be filled out.';
        Redirect_to('login.php');
    } else {

        $tableInformation = loginCheck($username, $password);

        if ($tableInformation) {

            $_SESSION['userId'] = $tableInformation['id'];
            $_SESSION['userName'] = $tableInformation['username'];
            $_SESSION['adminName'] = $tableInformation['aname'];

            $_SESSION['SuccessMessage'] = 'Welcome back ' . $_SESSION['adminName'] . '!';
            if (isset($_SESSION['trackURL'])) {
                Redirect_to(($_SESSION['trackURL']));
            } else {
                Redirect_to('dashboard.php');
            }
        } else {

            $_SESSION['ErrorMessage'] = 'Incorrect username or password.';
            Redirect_to('login.php');
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
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a href="index.html" class="navbar-brand"><i class="fas fa-asterisk"></i> MEINJAM.COM</a>
        </div>
    </nav>
    <div style="height: 6px; background: #27aae1;"></div>

    <!-- Header -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
        </div>
    </header>

    <!-- New section -->
    <section class="container py-3 mb-4">
        <div class="row">
            <div class="offset-sm-3 col-sm-6" style="min-height: 400px;">
                <br>
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <div class="card bg-secondary text-light">
                    <div class="card-header">
                        <h4>Welcome Back!</h4>
                    </div>
                    <div class="card-body bg-dark">
                        <form action="login.php" method="post">
                            <div class="form-group">
                                <label for="username"><span class="fieldinfo">Username:</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-secondary"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="username" id="username">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password"><span class="fieldinfo">Password:</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-secondary"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>
                            <input type="submit" name="submit" class="btn btn-secondary btn-block" value="Login">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Start -->
    <footer class="bg-dark text-white pt-3">
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