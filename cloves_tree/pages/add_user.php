<?php 

session_start();
include('../conn/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Initialize variables
$firstname = $lastname = $email = $phone = $zanzibar_id = $roles = $password = $confirm_password = '';
$errors = array('firstname' => '', 'lastname' => '', 'email' => '', 'phone' => '', 'zanzibar_id' => '', 'roles' => '', 'password' => '', 'confirm_password' => '');

// Role mapping
$role_mapping = array(
    "IT Staff" => 1,
    "Farmer Officer" => 2,
    "Admin" => 3,
    "Head of Farmers Officers" => 4
);

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Validate and sanitize inputs
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = "First name is required";
    } else {
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    }

    if (empty($_POST['lastname'])) {
        $errors['lastname'] = "Last name is required";
    } else {
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    }

    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
    }

    if (empty($_POST['phone'])) {
        $errors['phone'] = "Phone number is required";
    } else {
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    }

    if (empty($_POST['zanzibar_id'])) {
        $errors['zanzibar_id'] = "Zanzibar ID is required";
    } else {
        $zanzibar_id = mysqli_real_escape_string($conn, $_POST['zanzibar_id']);
    }

    if (empty($_POST['roles'])) {
        $errors['roles'] = "Role is required";
    } else {
        $roles = $_POST['roles'];
        if (array_key_exists($roles, $role_mapping)) {
            $role_id = $role_mapping[$roles];
        } else {
            $errors['roles'] = "Invalid role selected";
        }
    }

    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required";
    } else {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
    }

    if (empty($_POST['confirm_password'])) {
        $errors['confirm_password'] = "Confirm password is required";
    } else {
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
        if ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match";
        }
    }

    // Proceed if no errors
    if (!array_filter($errors)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and execute the SQL query
        $sql = "INSERT INTO users (firstname, lastname, email, password, phone, zanzibar_id, role_id) 
                VALUES ('$firstname', '$lastname', '$email', '$hashed_password', '$phone', '$zanzibar_id', '$role_id')";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo "Query Error: " . mysqli_error($conn);
        } else {
           
        }

    } else {
        echo "There is an error in the form!";
    }
}

// Query to retrieve all users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}


if (isset($_POST['update'])) {
    $id_update = mysqli_real_escape_string($conn, $_POST['id_update']);
    $updated_firstname = mysqli_real_escape_string($conn, $_POST['update_firstname']);
    $updated_lastname = mysqli_real_escape_string($conn, $_POST['update_lastname']);
    $updated_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $updated_phone = mysqli_real_escape_string($conn, $_POST['update_phone']);
    $updated_zanzibar_id = mysqli_real_escape_string($conn, $_POST['update_zanzibar_id']);
    $updated_roles = mysqli_real_escape_string($conn, $_POST['update_roles']);
    
    // Get role_id from role name
    $updated_role_id = $role_mapping[$updated_roles];

    $sql = "UPDATE users SET 
            firstname='$updated_firstname', 
            lastname='$updated_lastname', 
            email='$updated_email', 
            phone='$updated_phone', 
            zanzibar_id='$updated_zanzibar_id', 
            role_id='$updated_role_id' 
            WHERE user_id = $id_update";

    if (mysqli_query($conn, $sql)) {
        header('Location: add_user.php');
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}



if(isset($_POST['delete'])){
    $id_delete = mysqli_real_escape_string($conn, $_POST['id_delete']);
    $sql = "DELETE FROM users WHERE user_id = $id_delete";

    if(mysqli_query($conn, $sql)){
        header('location:add_user.php');
    } else {
        echo 'Query error: '. mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">

    <style>
        .content-wrapper {
            padding: 20px;
            margin-left: 250px;
        }
       /* Custom CSS for the modal */
.modal-dialog.modal-lg {
    max-width: 90%; /* Increase modal width */
}

.modal-content {
    border-radius: 8px;
}

.modal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-footer .btn {
    margin-left: 10px;
}

        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .registration-form {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .registration-form h2 {
            margin: 0;
            text-align: center;
            background-color: #003366;
            color: #fff;
            padding: 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .form-container {
            padding: 30px;
        }
        #btn{
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include('../pages/includes/navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php include('../pages/includes/sidebar.php'); ?>
        <div class="col-md-9 content-wrapper">
            <!-- Button to open modal -->
            <button type="button" id="btn" class="btn btn-primary" data-toggle="modal" data-target="#registrationModal">
                Open Registration Form
            </button>

            <!-- Modal -->
            <!-- Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="registrationModalLabel">User Registration</h4>
            </div>
            <div class="modal-body">
                <div class="form-container">
                    <form action="add_user.php" method="POST">
                        <div class="form-group col-md-6">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
                            <div class="text-danger"><?php echo $errors['firstname']; ?></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
                            <div class="text-danger"><?php echo $errors['lastname']; ?></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            <div class="text-danger"><?php echo $errors['email']; ?></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                            <div class="text-danger"><?php echo $errors['phone']; ?></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="zanzibar_id">Zanzibar ID</label>
                            <input type="text" class="form-control" id="zanzibar_id" name="zanzibar_id" value="<?php echo htmlspecialchars($zanzibar_id); ?>" required>
                            <div class="text-danger"><?php echo $errors['zanzibar_id']; ?></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="roles" required>
                                <option value="">Select Role</option>
                                <option value="IT Staff" <?php echo ($roles == "IT Staff") ? 'selected' : ''; ?>>IT Staff</option>
                                <option value="Farmer Officer" <?php echo ($roles == "Farmer Officer") ? 'selected' : ''; ?>>Farmer Officer</option>
                                <option value="Admin" <?php echo ($roles == "Admin") ? 'selected' : ''; ?>>Admin</option>
                                <option value="Head of Farmers Officers" <?php echo ($roles == "Head of Farmers Officers") ? 'selected' : ''; ?>>Head of Farmers Officers</option>
                            </select>
                            <div class="text-danger"><?php echo $errors['roles']; ?></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="text-danger"><?php echo $errors['password']; ?></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <div class="text-danger"><?php echo $errors['confirm_password']; ?></div>
                        </div>
                        <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="submit">Register</button>
            </div>
                        
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>


            <!-- User Table -->
           <!-- User Table -->
<div class="table-responsive">
    <table id="userTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Zanzibar ID</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    
                    <td><?php echo $row['firstname']; ?></td>
                    <td><?php echo $row['lastname']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['zanzibar_id']; ?></td>
                    <td><?php 
                        $role_id = $row['role_id'];
                        $role_name = array_search($role_id, $role_mapping);
                        echo $role_name ? $role_name : 'Unknown'; 
                    ?></td>
                    <td>
                        <!-- Update Button -->
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateModal_<?php echo $row['user_id']; ?>">Edit</button>
                        
                        <!-- Delete Form -->
                        <form action="add_user.php" method="POST" style="display:inline-block;">
                            <input type="hidden" name="id_delete" value="<?php echo $row['user_id']; ?>">
                            <input type="submit" name="delete" value="Delete" class="btn btn-danger btn-sm">
                        </form>
                    </td>
                </tr>

                <!-- Update Modal -->
                <div class="modal fade" id="updateModal_<?php echo $row['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel_<?php echo $row['user_id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel_<?php echo $row['user_id']; ?>">Update User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="add_user.php" method="POST">
                                    <input type="hidden" name="id_update" value="<?php echo $row['user_id']; ?>">
                                    <div class="form-group">
                                        <label for="update_firstname_<?php echo $row['user_id']; ?>">First Name</label>
                                        <input type="text" class="form-control" id="update_firstname_<?php echo $row['user_id']; ?>" name="update_firstname" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update_lastname_<?php echo $row['user_id']; ?>">Last Name</label>
                                        <input type="text" class="form-control" id="update_lastname_<?php echo $row['user_id']; ?>" name="update_lastname" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update_email_<?php echo $row['user_id']; ?>">Email</label>
                                        <input type="email" class="form-control" id="update_email_<?php echo $row['user_id']; ?>" name="update_email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update_phone_<?php echo $row['user_id']; ?>">Phone</label>
                                        <input type="text" class="form-control" id="update_phone_<?php echo $row['user_id']; ?>" name="update_phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update_zanzibar_id_<?php echo $row['user_id']; ?>">Zanzibar ID</label>
                                        <input type="text" class="form-control" id="update_zanzibar_id_<?php echo $row['user_id']; ?>" name="update_zanzibar_id" value="<?php echo htmlspecialchars($row['zanzibar_id']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update_roles_<?php echo $row['user_id']; ?>">Role</label>
                                        <select class="form-control" id="update_roles_<?php echo $row['user_id']; ?>" name="update_roles" required>
                                            <option value="IT Staff" <?php echo ($row['role_id'] == 1) ? 'selected' : ''; ?>>IT Staff</option>
                                            <option value="Farmer Officer" <?php echo ($row['role_id'] == 2) ? 'selected' : ''; ?>>Farmer Officer</option>
                                            <option value="Admin" <?php echo ($row['role_id'] == 3) ? 'selected' : ''; ?>>Admin</option>
                                            <option value="Head of Farmers Officers" <?php echo ($row['role_id'] == 4) ? 'selected' : ''; ?>>Head of Farmers Officers</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="update">Update</button>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userTable').DataTable();
    });
</script>
</body>
</html>
