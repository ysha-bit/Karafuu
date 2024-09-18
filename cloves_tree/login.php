<?php
session_start();
include('./conn/config.php'); // Include the database configuration file

// Initialize variables
$zanzibar_id = $password = '';
$errors = array('zanzibar_id' => '', 'password' => '');

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Validate Zanzibar ID
    if (empty($_POST['zanzibar_id'])) {
        $errors['zanzibar_id'] = "Zanzibar ID is required";
    } else {
        $zanzibar_id = mysqli_real_escape_string($conn, $_POST['zanzibar_id']);
    }

    // Validate Password
    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required";
    } else {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
    }

    // Proceed if no errors
    if (!array_filter($errors)) {
        // Prepare the SQL query
        $sql = "SELECT * FROM users WHERE zanzibar_id = '$zanzibar_id'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['loggedin'] = true;
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_role'] = $user['role_id'];
                // $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];

                // Redirect to the appropriate page based on user role
                switch ($user['role_id']) {
                    case 1:
                        header('Location: ./pages/index.php');
                        break;
                    case 2:
                        header('Location: ./components/index.php');
                        break;
                    case 3:
                        header('Location: ./admin/index.php');
                        break;
                    case 4:
                        header('Location: ./componentsgg/index.php');
                        break;
                    default:
                        header('Location: ./pages/index.php'); // Default page for other roles
                        break;
                }
                exit();
            } else {
                $errors['password'] = "Incorrect Zanzibar ID or Password";
            }
        } else {
            $errors['zanzibar_id'] = "Incorrect Zanzibar ID or Password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .login-form {
            max-width: 600px;
            margin: 100px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .login-form h2 {
            margin-bottom: 20px;
            text-align: center;
            background-color: #003366;
            color: #fff;
            padding: 20px;
            margin: -30px -30px 20px -30px;
            border-radius: 8px 8px 0 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .form-group label {
            font-weight: bold;
        }
        .login-form .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .login-form:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }
        .signup-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>CTMS Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="zanzibar_id">Zanzibar ID</label>
                <input type="text" class="form-control" id="zanzibar_id" name="zanzibar_id" required>
                <div class="text-danger"><?php echo $errors['zanzibar_id']; ?></div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="text-danger"><?php echo $errors['password']; ?></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
            <div class="signup-link">
                <p>Don't have an account? <a href="register.php">Create one here</a>.</p>
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
