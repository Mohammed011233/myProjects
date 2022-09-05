<?php
require '../helper/includes.php';
require '../helper/checkAdmin.php';

################### Select Data Section  #####################

$selectQuery = "SELECT users.* , user_roles.title FROM `users` inner join user_roles on users.role_id = user_roles.id";

$select_op = doQuery($selectQuery);








require '../layouts/header.php';

require '../layouts/nav.php';

require '../layouts/sidenav.php';

?>


<!-- strat design -->


<main>


    <div class="container-fluid">
        <h3 class="mt-4">Users </h3>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">
                <?php
                // displayMassage('delete_operation' , '');

                displayMassage('id_Error', 'show users');
                ?>
            </li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                Users Data
            </div>



            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Title</th>
                                <th>Profile Image</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Title</th>
                                <th>Profile Image</th>
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
                                    <td><?php echo $rowData['first_name'] . ' ' . $rowData['last_name'] ?></td>
                                    <td><?php echo $rowData['email'] ?></td>
                                    <td><?php echo $rowData['phone'] ?></td>
                                    <td><?php echo $rowData['title'] ?></td>
                                    <td><img class="userImg" src="uploads/<?php echo $rowData['image'] ?>" alt=""></td>

                                    <td>
                                        <a class="btn btn-danger" href="delete.php?id=<?php echo $rowData['id']; ?>"> Delete </a>

                                        <!-- edit task botton -->

                                        <a class="btn btn-primary" href="edit.php?id=<?php echo $rowData['id']; ?>"> Edit </a>


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