<?php
require '../helper/includes.php';




################### Select Data Section  #####################
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $_SESSION['id'] = $id;

    $selectQuery = "SELECT blogs.* ,  users.first_name , users.last_name 
                FROM `blogs` inner join users on blogs.addedby = users.id WHERE blogs.id = $id";

    $select_op = doQuery($selectQuery);



    if (mysqli_num_rows($select_op) == 0) {
        $dbMassage['filed'] = ' ** The id that ' . $id . ' is not correct';
        $_SESSION['dbMassage'] = $dbMassage;

        header('location: index.php');
        exit();
    } else {
        $dataRow = mysqli_fetch_assoc($select_op);

        $title = $dataRow['title'];
        $content = $dataRow['content'];
        $pubDate = $dataRow['pu_date'];
        $category = $dataRow['cat_id'];
        $_SESSION['oldImage'] = $dataRow['image'];
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // fetch user date
    $title    = clean($_POST['title']);
    $content  = clean($_POST['content']);
    $category = clean($_POST['category']);
    $pubDate  = clean($_POST['pu_date']);


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
        }else{
            $pubDate =  strtotime($pubDate);
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
        $oldImg = true;
        unset($_SESSION['errorMassage']);
    }



    if (!$errorFlag) {


        if ($oldImg) {
            $finalImgName = $_SESSION['oldImage'];
        } else {

            // generate final name to file 
            $finalImgName = uniqid() . '.' . $imgExten;



            // tranfar the file to uploads folder 
            $uploadPath = "uploads/" . $finalImgName;

            if (move_uploaded_file($imgTmpPath, $uploadPath)) {

                removeFile($_SESSION['oldImage']);
            } else {
                $massage = ["Field" => "The image has not been uploaded !!! please try again"];
            }
        }

        $userID = $_SESSION['user']['id'] ;
        
        $id = $_SESSION['id'];

        $query = "UPDATE `blogs`
                  SET `title`='$title',`content`='$content',`image`='$finalImgName',`pu_date`= $pubDate ,`cat_id`= $category ,`addedby`=  $userID  WHERE blogs.id = $id ";


        //doQuery function for excute query 
        $db_opration =  doQuery($query);

        $dbMassage = [];
        if ($db_opration) {
            $dbMassage['success'] = ' ** the role Updated';
            $_SESSION['dbMassage'] = $dbMassage;

            unset($_SESSION['oldImage']);
            unset($_SESSION['id']);

            header('location: index.php');

            exit();
        } else {
            $dbMassage['filed'] = ' ** the role did not update try agian';
        }
        //    session for database massage 
        $_SESSION['dbMassage'] = $dbMassage;
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
                        <h3 class="text-center font-weight-light my-4">Update Blog</h3>
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
                                <input class="form-control py-4" id="inputFirstName" type="text" name="title" value="<?php echo $title; ?>" />
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('titleError', ' ');

                                    ?>
                                </p>
                            </div>



                            <div class="form-group">
                                <label class="small mb-1" for="inputLastName">Blog Content</label>
                                <textarea class="form-control py-4" id="inputLastName" name="content"> <?php echo $content; ?> </textarea>
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

                                    $selectCategorys = "SELECT * FROM category ";
                                    $select_op = doQuery($selectCategorys);

                                    while ($catsData = mysqli_fetch_assoc($select_op)) {
                                    ?>

                                        <option value=<?php echo $catsData['id'];
                                                        if ($category == $catsData['id']) {
                                                            echo ' selected';
                                                        } ?>>
                                            <?php echo $catsData['title'] ?> </option>

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
                                <img class="userImg" src="uploads/<?php echo $_SESSION['oldImage']; ?>" alt="">

                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('imageError', ' ');

                                    ?>
                                </p>
                            </div>

                            <div class="form-group mt-4 mb-0"> <button class="btn btn-primary btn-block" type="submit">Update Blog</button> </div>
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