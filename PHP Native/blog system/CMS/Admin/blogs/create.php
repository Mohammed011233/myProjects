<?php
require '../helper/includes.php';


// if ($_SESSION['user']['role_title'] != 'admin' && $_SESSION['user']['role_title'] != 'writer') {
//     header('location: ' . url(''));
// }
// select roles from DB to display in form 
$selectQuery = "SELECT * FROM `category` ";

$select_op = doQuery($selectQuery);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // fetch user date
    $title = clean($_POST['title']);
    $content = clean($_POST['content']);
    $category = clean($_POST['category']);
    $pubDate = clean($_POST['pu_date']);


    $blogImage = clean($_FILES['blog_image']['name']);
    $imageType = $_FILES['blog_image']['type'];

    $arrImgType = explode('/', $imageType);
    $imgExten = strtolower(end($arrImgType));

    $imgSize = $_FILES['blog_image']['size'];

    $imgTmpPath = $_FILES['blog_image']['tmp_name'];



    $errorFlag = false;
    // The data validation
    validate($title, ['required', 'string', 'min', 'max'], $minlenth = 6, $maxlenth = 32);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['titleError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }

    validate($content, ['required', 'string', 'min', 'max'], $minlenth = 15, $maxlenth = 255);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['contentError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }

    validate($category, ['required', 'id']);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['categoryError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }



    validate($pubDate, ['required']);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['dateError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    } else {
        validate($pubDate, ['date', 'next_date']);
        if (isset($_SESSION['errorMassage'])) {
            $_SESSION['dateError'] = $_SESSION['errorMassage'];
            $errorFlag = true;

            unset($_SESSION['errorMassage']);
        }
    }

    validate($blogImage, ['required']);
    if (!isset($_SESSION['errorMassage'])) {

        validate($imgExten, ['fileExtention'], null, null, null, null, $realExtention = ['png', 'jpeg', 'jpg']);
        if (isset($_SESSION['errorMassage'])) {
            $_SESSION['imageError'] = $_SESSION['errorMassage'];
            $errorFlag = true;

            unset($_SESSION['errorMassage']);
        }

        validate($imgSize, ['fileSize'], null, null, $fileSize = 10 * 1048576);
        if (isset($_SESSION['errorMassage'])) {
            $_SESSION['imageError'] = $_SESSION['errorMassage'];
            $errorFlag = true;

            unset($_SESSION['errorMassage']);
        }
    } else {
        $_SESSION['imageError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }



    if (!$errorFlag) {

        // generate final name to file 
        $finalImgName = uniqid() . '.' . $imgExten;

        // tranfar the file to uploads folder 
        $uploadPath = "uploads/" . $finalImgName;


        if (move_uploaded_file($imgTmpPath, $uploadPath)) {

            //convert publish date into timestamp
            $pubDate = strtotime($pubDate);

            $userID = $_SESSION['user']['id'] ;
            //    insert date in database
            $insertQuery = "INSERT INTO `blogs`( `title`, `content`, `image`, `pu_date`, `cat_id`, `addedby`) VALUES ('$title' , '$content' , '$finalImgName' , $pubDate , $category , $userID)";

            $insert_op = doQuery($insertQuery);

            if ($insert_op) {
                $massage = ["Success" => "The user was insert successfully"];
            } else {
                $massage = ["Field" => "The user was not insert !!! please try again"];
            }
        } else {
            $massage = ["Field" => "The image has not been uploaded !!! please try again"];
        }
    } else {
        $massage = ["field" => "thare are some errors"];
    }
    $_SESSION['dbMassage'] = $massage;
}






require '../layouts/header.php';

require '../layouts/nav.php';

require '../layouts/sidenav.php';

?>




<!-- strat design -->

<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                        <h3 class="text-center font-weight-light my-4">Create Blog</h3>
                        <p class='errorMassage'>
                            <?php
                            // display validation error massage

                            displayMassage('dbMassage', ' ');

                            ?>
                        </p>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">



                            <div class="form-group">
                                <label class="small mb-1" for="inputFirstName">Title</label>
                                <input class="form-control py-4" id="inputFirstName" type="text" name="title" placeholder="Enter blog title" />
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('titleError', ' ');

                                    ?>
                                </p>
                            </div>



                            <div class="form-group">
                                <label class="small mb-1" for="inputLastName">Blog Content</label>
                                <textarea class="form-control py-4" id="inputLastName" name="content" placeholder="Enter blog content"></textarea>
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('contentError', ' ');

                                    ?>
                                </p>
                            </div>




                            <div class="form-group">
                                <label class="small mb-1" for="roleType">Blog Category</label>


                                <select class="form-control py-1" id="roleType" name="category">

                                    <?php
                                    while ($catsData = mysqli_fetch_assoc($select_op)) {
                                    ?>

                                        <option value=<?php echo $catsData['id'] ?> selected> <?php echo $catsData['title'] ?> </option>

                                    <?php
                                    }
                                    ?>

                                </select>
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('categoryError', ' ');

                                    ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="small mb-1" for="inputEmailAddress">Publish Date</label>
                                <input class="form-control py-4" id="inputEmailAddress" type="date" name="pu_date" />
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('dateError', ' ');

                                    ?>
                                </p>
                            </div>





                            <div class="form-group">
                                <label class="small mb-1" for="image">Blog Image</label>
                                <input class="form-control py-1" id="image" type="file" name="blog_image" />

                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('imageError', ' ');

                                    ?>
                                </p>
                            </div>




                            <div class="form-group mt-4 mb-0"><button class="btn btn-primary btn-block" type="submit">Create Blog</button></div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<?php
require '../layouts/footer.php';

                              
?>