<?php
require '../helper/includes.php';
require '../helper/checkAdmin.php';



################### Select Data Section  #####################
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $selectQuery = "SELECT * FROM `users` WHERE id = $id";

    $select_op = doQuery($selectQuery);



    if (mysqli_num_rows($select_op) == 0) {
        $dbMassage['filed'] = ' ** The id that ' . $id . ' is not correct';
        $_SESSION['dbMassage'] = $dbMassage;

        header('location: index.php');
        exit();
    } else {
        $dataRow = mysqli_fetch_assoc($select_op);
        $id = $dataRow['id'];


        $firstName = $dataRow['first_name'];
        $lastName = $dataRow['last_name'];
        $email = $dataRow['email'];
        $phone = $dataRow['phone'];
        $image = $dataRow['image'];
        $role_id = $dataRow['role_id'];

        //         echo $firstName = $dataRow['first_name'] . '<br> '. 
        //         $lastName = $dataRow['last_name']. '<br> ' . 
        //         $email = $dataRow['email']. '<br> ' .
        //         $phone = $dataRow['phone']. '<br> ' .
        //         $image = $dataRow['image']. '<br> ' . 
        //         $role_id = $dataRow['role_id']. '<br> ' .
        // '<input class="form-control py-1" id="image" type="file" name="image" value="uploads/" ' . $image .' />'   ;

        //         exit();

    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];

    $selectQuery = "SELECT * FROM `users` WHERE id = $id";

    $select_op = doQuery($selectQuery);

    $dataRow = mysqli_fetch_assoc($select_op);


    // fetch user date
    $firstName = clean($_POST['first_name']);
    $lastName = clean($_POST['last_name']);


    $email = clean($_POST['email']);

    $phone = clean($_POST['phone']);

    $image = clean($_FILES['image']['name']);
    $imageType = $_FILES['image']['type'];

    $arrImgType = explode('/', $imageType);
    $imgExten = strtolower(end($arrImgType));

    $imgSize = $_FILES['image']['size'];

    $imgTmpPath = $_FILES['image']['tmp_name'];

    $role_type = $_POST['role_type'];



    // echo $password . '<br>';
    // echo $conf_password . '<br>'; 

    // exit();

    $errorFlag = false;
    // The data validation
    validate($firstName, ['required', 'string', 'min', 'max'], $minlenth = 2, $maxlenth = 25);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['fNameError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }

    validate($lastName, ['required', 'string', 'min', 'max'], $minlenth = 2, $maxlenth = 25);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['lNameError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }

    validate($email, ['required', 'email', 'min', 'max'], $minlenth = 2, $maxlenth = 50);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['emailError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }


    validate($phone, ['required', 'phone']);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['phoneError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }

    validate($image, ['required']);
    if (!isset($_SESSION['errorMassage'])) {

        validate($imgExten, ['fileExtention'], null, null, null, null, $realExtention = ['png', 'jpeg', 'jpg']);
        if (isset($_SESSION['errorMassage'])) {
            $_SESSION['imageError'] = $_SESSION['errorMassage'];
            $errorFlag = true;

            unset($_SESSION['errorMassage']);
        }

        validate($imgSize, ['fileSize'], null, null, $fileSize = 5 * 1048576);
        if (isset($_SESSION['errorMassage'])) {
            $_SESSION['imageError'] = $_SESSION['errorMassage'];
            $errorFlag = true;

            unset($_SESSION['errorMassage']);
        }
    } else {
        $oldImg = true;
    }



    if (!$errorFlag) {

        if ($oldImg) {
            $finalImgName = $dataRow['image'];
        } else {
            // generate final name to file 
            $finalImgName = uniqid() . '.' . $imgExten;

            // tranfar the file to uploads folder 
            $uploadPath = "uploads/" . $finalImgName;

            if (move_uploaded_file($imgTmpPath, $uploadPath)) {

                removeFile($dataRow['image']);
            } else {
                $massage = ["Field" => "The image has not been uploaded !!! please try again"];
            }
        }



        $query = "UPDATE users
                    SET `first_name` = '$firstName' , last_name = '$lastName' , email = '$email' , phone = '$phone' , image = '$finalImgName' 
                    WHERE id = $id ";
        // echo $id ; 
        // exit();
        //doQuery function for excute query 
        $db_opration =  doQuery($query);

        $dbMassage = [];
        if ($db_opration) {
            $dbMassage['success'] = ' ** the role Updated';
            $_SESSION['dbMassage'] = $dbMassage;

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
                        <h3 class="text-center font-weight-light my-4">Updata user data</h3>
                        <p class='errorMassage'>
                            <?php
                            // display validation error massage

                            displayMassage('dbMassage', ' ');

                            ?>
                        </p>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">

                            <input type="number" name="id" value="<?php echo $id; ?>" hidden />

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputFirstName">First Name</label>
                                        <input class="form-control py-4" id="inputFirstName" type="text" name="first_name" value="<?php echo $firstName; ?>" />
                                        <p class='errorMassage'>
                                            <?php
                                            // display validation error massage

                                            displayMassage('fNameError', ' ');

                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputLastName">Last Name</label>
                                        <input class="form-control py-4" id="inputLastName" type="text" name="last_name" placeholder="Enter last name" value="<?php echo $lastName; ?>" />
                                        <p class='errorMassage'>
                                            <?php
                                            // display validation error massage

                                            displayMassage('lNameError', ' ');

                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                <input class="form-control py-4" id="inputEmailAddress" type="email" name="email" aria-describedby="emailHelp" placeholder="Enter email address" value="<?php echo $email; ?>" />
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('emailError', ' ');

                                    ?>
                                </p>
                            </div>




                            <div class="form-group">
                                <label class="small mb-1" for="phone">Phone</label>
                                <input class="form-control py-4" id="phone" type="text" name="phone" placeholder="Phone number" value="<?php echo $phone; ?>" />
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('phoneError', ' ');

                                    ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="small mb-1" for="image">Profile Image</label>
                                <input class="form-control py-1" id="image" type="file" name="image" value="uploads/<?php echo $image; ?>" />

                                <img class="userImg" src="uploads/<?php echo $dataRow['image']; ?>" alt="">

                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('imageError', ' ');

                                    ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="small mb-1" for="roleType">Role type</label>


                                <select class="form-control py-1" id="roleType" name="role_type">

                                    <?php
                                    // select roles from DB to display in form 
                                    $selectQuery = "SELECT * FROM `user_roles` ";

                                    $select_op = doQuery($selectQuery);

                                    while ($rolesData = mysqli_fetch_assoc($select_op)) {

                                        if ($role_id == $rolesData['id']) {
                                    ?>

                                            <option value=<?php echo $rolesData['id']; ?> selected> <?php echo $rolesData['title']; ?> </option>

                                        <?php
                                        } else {
                                        ?>
                                            <option value=<?php echo $rolesData['id']; ?>> <?php echo $rolesData['title']; ?> </option>
                                    <?php
                                        }
                                    }
                                    ?>

                                </select>
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('errorMassage', ' ');

                                    ?>
                                </p>
                            </div>



                            <div class="form-group mt-4 mb-0"><button class="btn btn-primary btn-block" type="submit">Update Account</button></div>
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