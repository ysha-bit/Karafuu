<?php 
session_start();
include('../conn/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Handle update request
if (isset($_POST['update'])) {
    $comment_id = $_POST['comment_id'];
    $status_of_trees = $_POST['status_of_trees'];
    $description = $_POST['description'];

    $update_query = "UPDATE comments SET status_of_trees = '$status_of_trees', description = '$description' WHERE comment_id = '$comment_id'";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Comment updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update comment');</script>";
    }
}

// Get the current year
$current_year = date("Y");

// Query to retrieve farmers added in the current year
$sql = "SELECT * FROM comments WHERE  YEAR(date) = '$current_year'";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
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
            <h3>Clove Tree Comments</h3>
            <div class="table-container">
                <table id="treeProgressTable" class="display">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['status_of_trees']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#editModal_<?php echo $row['comment_id']; ?>">Edit</button>
                                    
                                
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal_<?php echo $row['comment_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Comment</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="comments.php" method="POST">
                                                <input type="hidden" name="comment_id" value="<?php echo $row['comment_id']; ?>">
                                                <div class="form-group">
                                                    <label for="status_of_trees">Status of Trees</label>
                                                    <input type="text" name="status_of_trees" class="form-control" value="<?php echo htmlspecialchars($row['status_of_trees']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" class="form-control" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="update" class="btn btn-primary">Save changes</button>
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
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#treeProgressTable').DataTable();
        });
    </script>
<div>

</div class="footer">
    <?php include('../components/includes/footer.php'); ?>
</body>
</html>
