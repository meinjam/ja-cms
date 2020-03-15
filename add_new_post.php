<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

confirmLogin();

if (isset($_POST['submit'])) {
    $PostTitle = $_POST['PostTitle'];
    $Category = $_POST['Category'];
    $Image = $_FILES['Image']['name'];
    $Target = 'uploads/' . basename($_FILES['Image']['name']);
    $PostText = $_POST['Post'];
    $Admin = $_SESSION['userName'];
    date_default_timezone_set('Asia/Dhaka');
    $CurrentDate = time();
    $date = strftime('%B %d, %Y', $CurrentDate);
    $currentTime = date('m/d/Y H:i:s');
    $time = date('h:i A', strtotime($currentTime));
    $DateTime = $date . " " . $time;

    if (empty($PostTitle)) {
        $_SESSION['ErrorMessage'] = 'Post title can not be empty..';
        Redirect_to('add_new_post.php');
    } elseif (strlen($PostTitle) <= 5) {
        $_SESSION['ErrorMessage'] = 'Post title should be greter than 5 characters.';
        Redirect_to('add_new_post.php');
    } elseif (strlen($PostText) > 9999) {
        $_SESSION['ErrorMessage'] = 'Post discription should be less than 10000 characters.';
        Redirect_to('add_new_post.php');
    } elseif (empty($Image)) {
        $_SESSION['ErrorMessage'] = 'Please select an image.';
        Redirect_to('add_new_post.php');
    } else {
        $sql = "INSERT INTO posts(datetime,title,category,author,image,post)";
        $sql .= "VALUES(:dateTime,:postTitle,:categoryName,:adminName,:imageName,:postDis)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':dateTime', $DateTime);
        $stmt->bindValue(':postTitle', $PostTitle);
        $stmt->bindValue(':categoryName', $Category);
        $stmt->bindValue(':adminName', $Admin);
        $stmt->bindValue(':imageName', $Image);
        $stmt->bindValue(':postDis', $PostText);
        $Execute = $stmt->execute();
        move_uploaded_file($_FILES['Image']['tmp_name'], $Target);

        if ($Execute) {
            $_SESSION['SuccessMessage'] = 'Post added successfully.';
            Redirect_to('posts.php');
        } else {
            $_SESSION['ErrorMessage'] = 'Something went wrong, please try again.';
            Redirect_to('add_new_post.php');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include('templete/header.php'); ?>

<header class="bg-dark text-white py-3">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1><i class="fas fa-edit" style="color: #27aae1;"></i> Add Posts </h1>
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
            <form action="add_new_post.php" method="post" enctype="multipart/form-data">
                <div class="card bg-secondary text-light">
                    <div class="card-body bg-dark">
                        <div class="form-group">
                            <label for="title"><span class="fieldinfo"> Post Title: </span></label>
                            <input type="text" id="title" name="PostTitle" placeholrder="Type title here" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="Categoryt"><span class="fieldinfo"> Chose Category: </span></label>
                            <select class="form-control" id="Categoryt" name="Category">
                                <?php
                                $sql = "SELECT id,title FROM category";
                                $stmt = $ConnectingDB->query($sql);
                                while ($rows = $stmt->fetch()) :
                                    $id = $rows['id'];
                                    $title = $rows['title'];
                                ?>
                                    <option> <?php echo $title; ?> </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image"><span class="fieldinfo"> Select Image: </span></label>
                            <div class="custom-file">
                                <input type="file" name="Image" id="image" class="custom-file-input">
                                <label for="image" class="custom-file-label"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="post"><span class="fieldinfo"> Post: </span></label>
                            <textarea name="Post" id="post" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <button class="btn btn-success btn-block" name="submit"><i class="fas fa-check"></i> Add post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include('templete/footer.php'); ?>

</html>