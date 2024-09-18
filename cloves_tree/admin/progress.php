<?php
session_start();
include('../conn/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Check if the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header('Location: ../login.php');
//     exit();
// }

// Get the current year
$current_year = date("Y");

// Query to retrieve farmers added in the current year
$sql = "SELECT * FROM treeprogress WHERE  YEAR(progress_date) = '$current_year'";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}


// Delete farmer
if(isset($_POST['delete'])){
    $id_delete = mysqli_real_escape_string($conn, $_POST['id_delete']);
    $sql = "DELETE FROM treeprogress WHERE progress_id = $id_delete";

    if(mysqli_query($conn, $sql)){
        header('Location: farmers_lists.php');
        exit();
    } else {
        echo 'Query error: '. mysqli_error($conn);
    }
}

// Update farmer
if (isset($_POST['update'])) {
    $progress_id = mysqli_real_escape_string($conn, $_POST['progress_id']);
    $farmer = mysqli_real_escape_string($conn, $_POST['farmer']);
    $wilaya = mysqli_real_escape_string($conn, $_POST['wilaya']);
    $shehia = mysqli_real_escape_string($conn, $_POST['shehia']);
    $growth_stage = mysqli_real_escape_string($conn, $_POST['growth_stage']);
    $number_tree = mysqli_real_escape_string($conn, $_POST['number_tree']);
    $progress_date = mysqli_real_escape_string($conn, $_POST['progress_date']);

    // Update query
    $update_query = "UPDATE treeprogress 
                     SET farmer='$farmer', wilaya='$wilaya', shehia='$shehia', growth_stage='$growth_stage', number_tree='$number_tree', progress_date='$progress_date'
                     WHERE progress_id='$progress_id'";

    if (mysqli_query($conn, $update_query)) {
        header('Location: farmers_lists.php');
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tree Progress Dashboard</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <style>
        #thead th {
            background-color: #00308F;
            color: #fff;
        }
        .modal-content {
            max-height: 75vh; /* Adjust the height as needed (75vh is 75% of the viewport height) */
            overflow-y: auto; /* Enable vertical scrolling if content exceeds the max height */
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <!-- Navbar -->
        <?php include('./partials/navbar.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            <?php include('./partials/sidebar.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Progress of the Trees Table</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead id="thead">
                                                <tr>
                                                    <th>Farmer Name</th>
                                                    <th>Ward</th>
                                                    <th>District</th>
                                                    <th>Available Trees</th>
                                                    <th>Stage/Age</th>
                                                    <th>Accessed Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['farmer']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['shehia']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['wilaya']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['number_tree']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['growth_stage']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['progress_date']); ?></td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#editModal_<?php echo $row['progress_id']; ?>">Edit</button>

                                                        <!-- Delete Form -->
                                                        <form action="progress.php" method="POST" style="display:inline;">
                                                            <input type="hidden" name="id_delete" value="<?php echo $row['progress_id']; ?>">
                                                            <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger btn-sm">
                                                        </form>
                                                    </td>
                                                </tr>
                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal_<?php echo $row['progress_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Edit Clove Tree Progress</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="progress.php" method="POST">
                                                                    <input type="hidden" name="progress_id" value="<?php echo $row['progress_id']; ?>">
                                                                    <div class="form-group">
                                                                        <label for="farmer">Farmer Name</label>
                                                                        <input type="text" name="farmer" class="form-control" value="<?php echo htmlspecialchars($row['farmer']); ?>" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="wilaya">Wilaya</label>
                                                                        <input type="text" name="wilaya" class="form-control" value="<?php echo htmlspecialchars($row['wilaya']); ?>" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="shehia">Shehia</label>
                                                                        <input type="text" name="shehia" class="form-control" value="<?php echo htmlspecialchars($row['shehia']); ?>" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="growth_stage">Growth Stage</label>
                                                                        <input type="text" name="growth_stage" class="form-control" value="<?php echo htmlspecialchars($row['growth_stage']); ?>" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="number_tree">Number of Trees</label>
                                                                        <input type="text" name="number_tree" class="form-control" value="<?php echo htmlspecialchars($row['number_tree']); ?>" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="progress_date">Date of Record</label>
                                                                        <input type="date" name="progress_date" class="form-control" value="<?php echo htmlspecialchars($row['progress_date']); ?>" required>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="update" class="btn btn-outline-primary">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- endinject -->
</body>
</html>
