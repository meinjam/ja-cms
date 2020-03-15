<?php

require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/sessions.php');

confirmLogin();

$urlId1 = $_GET['id'];

$sql = "SELECT * FROM posts WHERE id = '$urlId1' ";
$stmt = $ConnectingDB->query($sql);
while ($rows = $stmt->fetch()) {
    $title = $rows['title'];
    $category = $rows['category'];
    $image = $rows['image'];
    $posttext = $rows['post'];
}

if (isset($_POST['submit'])) {
    $PostTitle = $_POST['PostTitle'];
    $Category = $_POST['Category'];
    $Image = $_FILES['Image']['name'];
    $Target = 'uploads/' . basename($_FILES['Image']['name']);
    $PostText = $_POST['Post'];
    $Admin = 'injam';
    date_default_timezone_set('Asia/Dhaka');
    $CurrentTime = time();
    $DateTime = strftime('%B-%d-%Y %H:%M:%S', $CurrentTime);

    if (empty($PostTitle)) {
        $_SESSION['ErrorMessage'] = 'Post title can not be empty.';
        Redirect_to("editpost.php?id=$urlId1");
    } elseif (strlen($PostTitle) <= 5) {
        $_SESSION['ErrorMessage'] = 'Post title should be greter than 5 characters.';
        Redirect_to("editpost.php?id=$urlId1");
    } elseif (strlen($PostText) > 9999) {
        $_SESSION['ErrorMessage'] = 'Post discription should be less than 10000 characters.';
        Redirect_to("editpost.php?id=$urlId1");
    } else {
        if (!empty($Image)) {
            $sql = "UPDATE posts SET
                title='$PostTitle', category='$Category', image='$Image', post='$PostText'
                WHERE id='$urlId1'";
            //for delete image
            $targetPath = "uploads/$image";
            unlink($targetPath);
            //end for delete image
        } else {
            $sql = "UPDATE posts SET
                title='$PostTitle', category='$Category', post='$PostText'
                WHERE id='$urlId1'";
        }
        $Execute = $ConnectingDB->query($sql);
        move_uploaded_file($_FILES['Image']['tmp_name'], $Target);

        if ($Execute) {
            $_SESSION['SuccessMessage'] = 'Post updated successfully.';
            Redirect_to('posts.php');
        } else {
            $_SESSION['ErrorMessage'] = 'Something went wrong, please try again.';
            Redirect_to('posts.php');
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
                <h1><i class="fas fa-edit" style="color: #27aae1;"></i> Edit Posts </h1>
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
            <form action="editpost.php?id=<?php echo $urlId1; ?>" method="post" enctype="multipart/form-data">
                <div class="card bg-secondary text-light">
                    <div class="card-body bg-dark">
                        <div class="form-group">
                            <label for="title"><span class="fieldinfo"> Post Title: </span></label>
                            <input type="text" id="title" name="PostTitle" placeholrder="Type title here" class="form-control" value="<?php echo $title ?>">
                        </div>
                        <div class="form-group">
                            <span class="fieldinfo"> Existing Category: </span><?php echo $category; ?>
                            <br>
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
                            <span class="fieldinfo"> Existing image: </span>
                            <br>
                            <img src="uploads/<?php echo $image; ?>" width="270px" alt="image" class="my-2">
                            <br>
                            <label for="image"><span class="fieldinfo"> Select Image: </span></label>
                            <div class="custom-file">
                                <input type="file" name="Image" id="image" class="custom-file-input">
                                <label for="image" class="custom-file-label"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="post"><span class="fieldinfo"> Post: </span></label>
                            <textarea name="Post" id="post" rows="5" class="form-control"><?php echo $posttext; ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <button class="btn btn-success btn-block" name="submit"><i class="fas fa-check"></i> Update post</button>
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