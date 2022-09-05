<?php
require '../helper/includes.php';
require '../helper/checkAdmin.php';

################### Select Data Section  #####################

$id = $_GET['id'];

$selectQuery = "SELECT * FROM `user_roles` WHERE id = $id";

$select_op = doQuery($selectQuery);



if (mysqli_num_rows($select_op) == 0) {
    $dbMassage['filed'] = ' ** The id that '. $id .' is not correct';
    $_SESSION['dbMassage'] = $dbMassage; 

    header('location: index.php');
    exit();
    
} else {
    $dataRow = mysqli_fetch_assoc($select_op);
}




################### Update Data Section  #####################
//create role
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //fetch & clean data
    $role_title = clean($_POST['role_title']);

    // validation  data
    validate($role_title, ['required', 'string', 'min', 'max'], 3, 32);


    ##### update the role title 

    if (!isset($_SESSION['errorMassage'])) {
        $query = "UPDATE user_roles SET title = '$role_title' WHERE id = $id";

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
    }
}











require '../layouts/header.php';

require '../layouts/nav.php';

require '../layouts/sidenav.php';

?>


<!-- strat design -->


<main>
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">

                <?php
                //display database massage 

                displayMassage('dbMassage', 'Dashboard / role / Update');

                ?>

            </li>

        </ol>

        <form action="<?php echo 'edit.php?id=' . $dataRow['id']; ?>" method="POST">

            <div class="form-group">
                <label for="exampleInputEmail1">Role</label>
                <input type="text" class="form-control" id="exampleInputText1" name="role_title" value="<?php echo $dataRow['title'] ?>" placeholder="Enter role">

                <!-- call diplay error massage function -->

                <p class='errorMassage' style=" min-height: 30px;">
                    <?php
                    // display validation error massage

                    displayMassage('errorMassage', ' ');

                    ?>
                </p>
            </div>

            <button type="submit" class="btn btn-primary">Updata</button>
        </form>

    </div>






</main>

<?php
require '../layouts/footer.php';

?>