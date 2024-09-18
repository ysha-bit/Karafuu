<?php 
session_start();
include('../conn/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get the current year
$current_year = date("Y");

// Query to retrieve farmers added in the current year
$sql = "SELECT * FROM treeprogress WHERE  YEAR(progress_date) = '$current_year'";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle update request
if (isset($_POST['update'])) {
    $progress_id = $_POST['progress_id'];
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
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Delete farmer
if(isset($_POST['delete'])){
    $id_delete = mysqli_real_escape_string($conn, $_POST['id_delete']);
    $sql = "DELETE FROM treeprogress WHERE progress_id = $id_delete";

    if(mysqli_query($conn, $sql)){
        header('location:farmers_lists.php');
    } else {
        echo 'Query error: '. mysqli_error($conn);
    }
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Officers - Clove Tree Progress</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .content-wrapper {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .card {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        table thead {
            background-color: #59b300;
            color: #fff;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: relative;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include('../components/includes/navbar.php'); ?>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="card">
            <h3>Clove Tree Progress</h3>
            <div class="table-container">
                <table id="treeProgressTable" class="display">
                    <thead>
                        <tr>
                            <th>Farmer Name</th>
                            <th>District</th>
                            <th>Ward</th>
                            <th>Growth Stage</th>
                            <th>Number of Trees</th>
                            <th>Date of Record</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['farmer']); ?></td>
                                <td><?php echo htmlspecialchars($row['wilaya']); ?></td>
                                <td><?php echo htmlspecialchars($row['shehia']); ?></td>
                                <td><?php echo htmlspecialchars($row['growth_stage']); ?></td>
                                <td><?php echo htmlspecialchars($row['number_tree']); ?></td>
                                <td><?php echo htmlspecialchars($row['progress_date']); ?></td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#editModal_<?php echo $row['progress_id']; ?>">Edit</button>
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
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="update" class="btn btn-success">Update</button>
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

    <!-- Footer -->
    <div class="">
        <?php include('../components/includes/footer.php'); ?>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#treeProgressTable').DataTable();
        });
    </script>
</body>
</html>
