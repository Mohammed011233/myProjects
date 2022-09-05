<?php
require '../helper/includes.php';
require '../helper/checkAdmin.php';
################### Insert Data Section  #####################
//create role
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //fetch & clean data
    $role_title = clean($_POST['role_title']);

    // validation  data
    validate($role_title, ['required', 'string', 'min', 'max'], 3, 32);


    ##### insert the role title 

    if (!isset($_SESSION['errorMassage'])) {
        $query = "insert into user_roles (title) values ('$role_title')";

        //doQuery function for excute query 
        $db_opration =  doQuery($query);

        $dbMassage = [];
        if ($db_opration) {
            $dbMassage['success'] = ' ** the role inserted';
        } else {
            $dbMassage['filed'] = ' ** the role did not insert try agian';
        }
        //    session for database massage 
        $_SESSION['dbMassage'] = $dbMassage;
    }
}




################### Select Data Section  #####################

$selectQuery = "SELECT * FROM `user_roles` ";

$select_op = doQuery($selectQuery);








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

                displayMassage('dbMassage', 'Dashboard / role / create');

                ?>

            </li>

        </ol>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="exampleInputEmail1">Role</label>
                <input type="text" class="form-control" id="exampleInputText1" name="role_title" placeholder="Enter role">

                <!-- call diplay error massage function -->

                <p class='errorMassage' style=" min-height: 30px;">
                    <?php
                    // display validation error massage

                    displayMassage('errorMassage', ' ');

                    ?>
                </p>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
        </form>

    </div>



<br>
<br>




    <div class="container-fluid">
        <h3 class="mt-4">Roles Table </h3>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"> 
                <?php 
                    // displayMassage('delete_operation' , '');

                    displayMassage('id_Error' , 'display roles');
                ?>
            </li>
        </ol>


        <div class="card mb-4"> 
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                Role Data
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Roles</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Roles</th>
                                <th>Action</th>

                            </tr>
                        </tfoot>
                        <tbody>


                            <?php
                            // fetch selected role data

                            $id = 0;

                            while ($rowData = mysqli_fetch_assoc($select_op)) {

                                #####
                            ?>


                                <tr>

                                    <td> <?php echo ++$id; ?> </td>
                                    <td><?php echo $rowData['title'] ?></td>
                                    <td>
                                

                                    <a class="btn btn-danger" href="delete.php?id=<?php echo $rowData['id'] ;?>"> Delete </a>

                                    <!-- edit task botton -->

                                    <a class="btn btn-primary" href="edit.php?id=<?php echo $rowData['id'] ; ?>"> Edit </a>


                                </td>

                                </tr>

                            <?php
                            }
                            #####
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>






</main>

<?php
require '../layouts/footer.php';

?>