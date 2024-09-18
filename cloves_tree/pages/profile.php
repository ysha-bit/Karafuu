<?php
session_start();
include('../conn/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch officer details
$itStaff_id = $_SESSION['user_id'];
$query = "SELECT firstname, lastname, email, phone, zanzibar_id FROM users WHERE user_id = '$itStaff_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $itStaff = mysqli_fetch_assoc($result);
} else {
    die("Failed to retrieve officer data: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }

        .breadcrumb {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-body {
            padding: 1.5rem;
        }

        .avatar {
            position: relative;
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .avatar .icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #007bff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            border: 3px solid #fff;
        }

        .progress {
            height: 5px;
        }

        .progress-bar {
            background-color: #007bff;
        }

        .list-group-item {
            padding: 1rem 1.5rem;
        }

        .list-group-item i {
            margin-right: 1rem;
        }

        .breadcrumb a {
            text-decoration: none;
            color: #007bff;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        .container {
            padding-top: 3rem;
        }

        .section {
            margin-left: 300px;
            margin-top: 50px;
        }

        .user-info-card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-top: 5px solid #66ccff !important;
        }

        .user-info-card .row {
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .user-info-card .row:last-child {
            border-bottom: none;
        }

        .user-info-card .col-sm-3 {
            font-weight: 600;
            color: #007bff;
        }

        .user-info-card .col-sm-9 {
            color: #555;
        }

        .user-info-card .col-sm-9 p {
            margin-bottom: 0;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
        }
    </style>
</head>
<body>
    <?php include('../pages/includes/navbar.php'); ?>
    <div class="row">
        <?php include('../pages/includes/sidebar.php'); ?>
        <section>
            <div class="section col-md-9">
                <div class="row">
                    
                    <div class="col-lg-8">
                        <div class="user-info-card">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Full Name</p>
                                </div>
                                <div class="col-sm-9">
                                <span><?php echo $itStaff['firstname']; ?></span>  <span><?php echo $itStaff['lastname']; ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email</p>
                                </div>
                                <div class="col-sm-9">
                                <span><?php echo $itStaff['email']; ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Phone</p>
                                </div>
                                <div class="col-sm-9">
                                <span><?php echo $itStaff['phone']; ?></span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Zanzibar ID</p>
                                </div>
                                <div class="col-sm-9">
                                <span><?php echo $itStaff['zanzibar_id']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- FontAwesome for user icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
