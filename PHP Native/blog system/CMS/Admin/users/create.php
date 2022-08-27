<?php
require '../helper/DB_connection.php';
require '../helper/functions.php';

// select roles from DB to display in form 
$selectQuery = "SELECT * FROM `user_roles` ";

$select_op = doQuery($selectQuery);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // fetch user date
    $firstName = clean($_POST['first_name']);
    $lastName = clean($_POST['last_name']);

    $fullName = $firstName . ' ' . $lastName;

    $email = clean($_POST['email']);
    $password = clean($_POST['password']);
    $conf_password = clean($_POST['conf_pass']);
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

    validate($password, ['required', 'min', 'max'], $minlenth =  8, $maxlenth = 50);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['passError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }

    validate($conf_password , ['required', 'conf_pass'] ,null, null , null , $pass = $password );
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['confPassError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }

    validate($phone, ['required', 'phone']);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['phoneError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }

    validate($imgExten, ['required', 'fileExtention'], null, null , null ,null , $realExtention = ['png', 'jpeg', 'jpg']);
    if (isset($_SESSION['errorMassage'])) {
        $_SESSION['imageError'] = $_SESSION['errorMassage'];
        $errorFlag = true;

        unset($_SESSION['errorMassage']);
    }

    validate($imgSize, ['fileSize'],null , null , $fileSize = 5 * 1048576);
    if (isset($_SESSION['errorMassage'])) {
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

            // encreption password

            $password =  md5($password);

            //    insert date in database
            $insertQuery = "insert into users (name , email , password , phone , image , role_id ) values ('$fullName' , '$email' , '$password' , '$phone' , '$finalImgName' , '$role_type')";

            $insert_op = doQuery($insertQuery);

            if ($insert_op) {
                $massage = ["Success" => "The user was insert successfully"];
            } else {
                $massage = ["Field" => "The user was not insert !!! please try again"];
            }
        } else {
            $massage = ["Field" => "The image has not been uploaded !!! please try again"];
        }
       
    }else{
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
                        <h3 class="text-center font-weight-light my-4">Create Account</h3>
                        <p class='errorMassage'>
                            <?php
                            // display validation error massage

                            displayMassage('dbMassage', ' ');

                            ?>
                        </p>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputFirstName">First Name</label>
                                        <input class="form-control py-4" id="inputFirstName" type="text" name="first_name" placeholder="Enter first name" />
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
                                        <input class="form-control py-4" id="inputLastName" type="text" name="last_name" placeholder="Enter last name" />
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
                                <input class="form-control py-4" id="inputEmailAddress" type="email" name="email" aria-describedby="emailHelp" placeholder="Enter email address" />
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('emailError', ' ');

                                    ?>
                                </p>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputPassword">Password</label>
                                        <input class="form-control py-4" id="inputPassword" type="password" name="password" placeholder="Enter password" />
                                        <p class='errorMassage'>
                                            <?php
                                            // display validation error massage

                                            displayMassage('passError', ' ');

                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                        <input class="form-control py-4" id="inputConfirmPassword" type="password" name="conf_pass" placeholder="Confirm password" />
                                        <p class='errorMassage'>
                                            <?php
                                            // display validation error massage

                                            displayMassage('confPassError', ' ');

                                            ?>
                                        </p>
                                    </div>
                                </div>

                            </div>


                            <div class="form-group">
                                <label class="small mb-1" for="phone">Phone</label>
                                <input class="form-control py-4" id="phone" type="text" name="phone" placeholder="Phone number" />
                                <p class='errorMassage'>
                                    <?php
                                    // display validation error massage

                                    displayMassage('phoneError', ' ');

                                    ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="small mb-1" for="image">Profile Image</label>
                                <input class="form-control py-1" id="image" type="file" name="image" />

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
                                    while ($rolesData = mysqli_fetch_assoc($select_op)) {
                                    ?>

                                        <option value=<?php echo $rolesData['id'] ?> selected> <?php echo $rolesData['title'] ?> </option>

                                    <?php
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



                            <div class="form-group mt-4 mb-0"><button class="btn btn-primary btn-block" type="submit">Create Account</button></div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <div class="small"><a href="login.html">Have an account? Go to login</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require '../layouts/footer.php';

?>