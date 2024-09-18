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
$sql = "SELECT * FROM farmers WHERE date = '$current_year'";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
// Query to retrieve farmers added in the current year
$sql1 = "SELECT * FROM farmers ";
$result1 = mysqli_query($conn, $sql1);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}


// Delete farmer
if (isset($_POST['delete'])) {
    $id_delete = mysqli_real_escape_string($conn, $_POST['id_delete']);
    $sql = "DELETE FROM farmers WHERE farmer_id = $id_delete";

    if (mysqli_query($conn, $sql)) {
        header('location:farmers_lists.php');
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}

// Update farmer
if (isset($_POST['update'])) {
    $farmer_id = mysqli_real_escape_string($conn, $_POST['farmer_id']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $zanzibar_id = mysqli_real_escape_string($conn, $_POST['zanzibar_id']);
    $amount_of_miche = mysqli_real_escape_string($conn, $_POST['amount_of_miche']);
    $shehia = mysqli_real_escape_string($conn, $_POST['shehia']);
    $wilaya = mysqli_real_escape_string($conn, $_POST['wilaya']);
    
    $sql = "UPDATE farmers SET firstname='$firstname', lastname='$lastname', phone='$phone', zanzibar_id='$zanzibar_id', amount_of_miche='$amount_of_miche', shehia='$shehia', wilaya='$wilaya' WHERE farmer_id=$farmer_id";

    if (mysqli_query($conn, $sql)) {
        header('location:farmers_lists.php');
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}

// Add Farmer
if (isset($_POST['submit'])) {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $zanzibar_id = mysqli_real_escape_string($conn, $_POST['zanzibar_id']);
    $amount_of_miche = mysqli_real_escape_string($conn, $_POST['amount_of_miche']);
    $shehia = mysqli_real_escape_string($conn, $_POST['shehia']);
    $wilaya = mysqli_real_escape_string($conn, $_POST['wilaya']);
    $current_year = date("Y");

    $sql = "INSERT INTO farmers (firstname, lastname, phone, zanzibar_id, amount_of_miche, shehia, wilaya, date) VALUES ('$firstname', '$lastname', '$phone', '$zanzibar_id', '$amount_of_miche', '$shehia', '$wilaya', '$current_year')";

    if (mysqli_query($conn, $sql)) {
        header('location:farmers_lists.php');
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}

// Add Farmer from Modal
if (isset($_POST['action']) && $_POST['action'] === 'add_farmer') {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $zanzibar_id = mysqli_real_escape_string($conn, $_POST['zanzibar_id']);
    $amount_of_miche = mysqli_real_escape_string($conn, $_POST['amount_of_miche']);
    $shehia = mysqli_real_escape_string($conn, $_POST['shehia']);
    $wilaya = mysqli_real_escape_string($conn, $_POST['wilaya']);
    $current_year = date("Y");

    $sql = "INSERT INTO farmers (firstname, lastname, phone, zanzibar_id, amount_of_miche, shehia, wilaya, date) VALUES ('$firstname', '$lastname', '$phone', '$zanzibar_id', '$amount_of_miche', '$shehia', '$wilaya', '$current_year')";

    if (mysqli_query($conn, $sql)) {
        header('location:farmers_lists.php');
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Farmers List</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">

    <style>
        .content-wrapper {
            padding: 20px;
            margin-left: 250px;
        }
        .modal-header, .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        /* Custom table header */
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        #btn-info {
            margin-bottom:30px;
        }
    </style>
</head>
<body>
<?php include('../pages/includes/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row">
        <?php include('../pages/includes/sidebar.php'); ?>
            <div class="col-md-9 content-wrapper">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Retrieve Details Button -->
                        <button class="btn btn-info btn-sm" id="btn-info" data-toggle="modal" data-target="#retrieveModal">Retrieve EXisting Farmer</button>
                        <!-- Farmers Table -->
                        <div class="table-responsive">
                            <table id="farmerTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Phone</th>
                                        <th>Zanzibar ID</th>
                                        <th>Number 0f Trees</th>
                                        <th>Ward</th>
                                        <th>District</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                                            <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($row['zanzibar_id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['amount_of_miche']); ?></td>
                                            <td><?php echo htmlspecialchars($row['shehia']); ?></td>
                                            <td><?php echo htmlspecialchars($row['wilaya']); ?></td>
                                            <td>
                                                <!-- Edit Button -->
                                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal_<?php echo $row['farmer_id']; ?>">Edit</button>
                                                
                                                <!-- Delete Form -->
                                                <form action="farmers_lists.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="id_delete" value="<?php echo $row['farmer_id']; ?>">
                                                    <input type="submit" name="delete" value="Delete" class="btn btn-danger btn-sm">
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal_<?php echo $row['farmer_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Farmer</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="farmers_lists.php" method="POST">
                                                            <input type="hidden" name="farmer_id" value="<?php echo $row['farmer_id']; ?>">
                                                            <div class="form-group">
                                                                <label for="firstname">First Name</label>
                                                                <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="lastname">Last Name</label>
                                                                <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="phone">Phone</label>
                                                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="zanzibar_id">Zanzibar ID</label>
                                                                <input type="text" name="zanzibar_id" class="form-control" value="<?php echo htmlspecialchars($row['zanzibar_id']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="amount_of_miche">Amount of Miche</label>
                                                                <input type="text" name="amount_of_miche" class="form-control" value="<?php echo htmlspecialchars($row['amount_of_miche']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="shehia">Shehia</label>
                                                                <input type="text" name="shehia" class="form-control" value="<?php echo htmlspecialchars($row['shehia']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="wilaya">Wilaya</label>
                                                                <input type="text" name="wilaya" class="form-control" value="<?php echo htmlspecialchars($row['wilaya']); ?>" required>
                                                            </div>
                                                            <input type="hidden" name="update" value="update">
                                                            <button type="submit" class="btn btn-primary">Update</button>
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

    <!-- Retrieve Farmer Details Modal -->
    <div class="modal fade" id="retrieveModal" tabindex="-1" role="dialog" aria-labelledby="retrieveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="retrieveModalLabel">Retrieve Farmer Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="retrieveForm" action="farmers_lists.php" method="POST">
                        <div class="form-group">
                            <label for="zanzibar_id">Zanzibar ID</label>
                            <select class="form-control" id="zanzibar_id" name="zanzibar_id">
                                <option value="">Select Zanzibar ID</option>
                                <?php
                                mysqli_data_seek($result1, 0);
                                while ($row = mysqli_fetch_assoc($result1)) {
                                    $selected = (isset($_POST['zanzibar_id']) && $_POST['zanzibar_id'] === $row['zanzibar_id']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($row['zanzibar_id']) . "' $selected>" . htmlspecialchars($row['zanzibar_id']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div id="farmerDetails" style="display:none;">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" name="firstname" id="firstname" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" name="lastname" id="lastname" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" id="phone" name="phone" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Amount of Miche</label>
                                <input type="number" id="" name="amount_of_miche" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="shehia">Shehia</label>
                                <input type="text" id="shehia" name="shehia" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="wilaya">Wilaya</label>
                                <input type="text" id="wilaya" name="wilaya" class="form-control" readonly>
                            </div>
                        </div>
                        <input type="hidden" name="action" value="add_farmer">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="retrieveForm" class="btn btn-primary">Add Farmers</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#farmerTable').DataTable();

            $('#zanzibar_id').change(function() {
                var zanzibar_id = $(this).val();
                if (zanzibar_id !== "") {
                    $.ajax({
                        url: "get_farmer_details.php",
                        method: "POST",
                        data: { zanzibar_id: zanzibar_id },
                        dataType: "json",
                        success: function(data) {
                            if (data.success) {
                                $('#firstname').val(data.firstname);
                                $('#lastname').val(data.lastname);
                                $('#phone').val(data.phone);
                                $('#shehia').val(data.shehia);
                                $('#wilaya').val(data.wilaya);
                                $('#farmerDetails').show();
                            } else {
                                alert('No farmer found with the selected Zanzibar ID.');
                                $('#farmerDetails').hide();
                            }
                        },
                        error: function() {
                            alert('An error occurred while fetching the farmer details.');
                        }
                    });
                } else {
                    $('#farmerDetails').hide();
                }
            });
        });
    </script>
</body>
</html>
