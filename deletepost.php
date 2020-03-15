<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

confirmLogin();

$urlId1 = $_GET['id'];

//Read data for input fields
$sql = "SELECT * FROM posts WHERE id = '$urlId1' ";
$stmt = $ConnectingDB->query($sql);
while ($rows = $stmt->fetch()) {
    $title = $rows['title'];
    $category = $rows['category'];
    $image = $rows['image'];
    $posttext = $rows['post'];
}
//End read data for input fields

if (isset($_POST['submit'])) {

    $sql = "DELETE FROM posts WHERE id = '$urlId1'";
    $Execute = $ConnectingDB->query($sql);

    if ($Execute) {
        //for delete image
        $targetPath = "uploads/$image";
        unlink($targetPath);
        //end for delete image
        $_SESSION['SuccessMessage'] = 'Post deleted successfully.';
        Redirect_to('posts.php');
    } else {
        $_SESSION['ErrorMessage'] = 'Something went wrong, please try again.';
        Redirect_to('posts.php');
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
                <h1><i class="fas fa-trash" style="color: #27aae1;"></i> Delete Posts </h1>
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
            <form action="deletepost.php?id=<?php echo $urlId1; ?>" method="post" enctype="multipart/form-data">
                <div class="card bg-secondary text-light">
                    <div class="card-body bg-dark">
                        <div class="form-group">
                            <label for="title"><span class="fieldinfo"> Post Title: </span></label>
                            <input disabled type="text" id="title" name="PostTitle" placeholrder="Type title here" class="form-control" value="<?php echo $title ?>">
                        </div>
                        <div class="form-group">
                            <span class="fieldinfo"> Post Category: </span><?php echo $category; ?>
                        </div>
                        <div class="form-group">
                            <span class="fieldinfo"> Post image: </span>
                            <br>
                            <img src="uploads/<?php echo $image; ?>" width="270px" alt="image" class="mt-2">
                        </div>
                        <div class="form-group">
                            <label for="post"><span class="fieldinfo"> Post: </span></label>
                            <textarea disabled name="Post" id="post" rows="5" class="form-control"><?php echo $posttext; ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <button class="btn btn-danger btn-block" name="submit"><i class="fas fa-trash"></i> Delete post</button>
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