
<?php 

// include('./includes/check_login.php');
include('./conn/config.php');

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
            header("Location:login.php");
        }

    } else {
        echo "There is an error in the form!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .registration-form {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            transition: box-shadow 0.3s ease;
        }
        .registration-form:hover {
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.4);
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
        .registration-form .form-container {
            padding: 30px;
        }
        .form-group label {
            font-weight: bold;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="registration-form">
        <h2>CTMS Registration</h2>
        <div class="form-container">
            <form action="register.php" method="POST">
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
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </form>
            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
