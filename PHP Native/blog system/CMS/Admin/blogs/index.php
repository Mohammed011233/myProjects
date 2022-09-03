<?php
require '../helper/DB_connection.php';
require '../helper/functions.php';


################### Select Data Section  #####################

$selectQuery = "SELECT blogs.* , category.title as cat_title , users.first_name , users.last_name 
                FROM `blogs` inner join category on blogs.cat_id = category.id
                inner join users on blogs.addedby = users.id";

$select_op = doQuery($selectQuery);








require '../layouts/header.php';

require '../layouts/nav.php';

require '../layouts/sidenav.php';

?>


<!-- strat design -->


<main>


    <div class="container-fluid">
        <h3 class="mt-4">Blogs </h3>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">
                <?php
                // displayMassage('delete_operation' , '');

                displayMassage('dbMassage', 'show blogs');
                

                ?>
            </li>
        </ol>

        <div>
            <a class="btn btn-primary stylebtn" href="create.php"> Create </a>
        </div>


        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                Blogs Data
            </div>



            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Publish date</th>
                                <th>category</th>
                                <th>Blog Writer</th>
                                <th>Blog Image</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Publish date</th>
                                <th>category</th>
                                <th>Blog Writer</th>
                                <th>Blog Image</th>
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
                                    
                                    <td><?php echo $rowData['title'] ;?></td>
                                    <td><?php echo substr($rowData['content'] , 0 , 15) ;?></td>
                                    <td><?php echo date('d / M / Y' , $rowData['pu_date']) ; ?></td>
                                    <td><?php echo $rowData['cat_title'] ;?></td>
                                    <td><?php echo $rowData['first_name'] . ' ' . $rowData['last_name'] ;?></td>

                                    <td><img class="userImg" src="uploads/<?php echo $rowData['image'] ;?>" alt="blog image"></td>

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