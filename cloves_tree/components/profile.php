<?php
session_start();
include('../conn/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch officer details
$officer_id = $_SESSION['user_id'];
$query = "SELECT firstname, lastname, email, phone, zanzibar_id FROM users WHERE user_id = '$officer_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $officer = mysqli_fetch_assoc($result);
} else {
    die("Failed to retrieve officer data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .profile-card {
            margin: 50px 40px;
            max-width: 600px;
            background: white;
            border-radius: 5px;
            border-top: 10px solid #59b300 !important;
            border-bottom: 3px solid #59b300 !important; /* Matching navbar color */
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .profile-card h2 {
            margin-bottom: 30px;
            text-align: center;
        }
        .profile-info {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #59b300; /* Matching navbar color */
        }
        .profile-info:last-child {
            border-bottom: none;
        }
        .profile-info strong {
            display: inline-block;
            width: 150px;
            font-weight: 600;
        }
    </style>
</head>
<body>
<?php include('../components/includes/navbar.php'); ?>
    <div class="profile-card col-md-7">
        <h2>Officer Profile</h2>
        <div class="profile-info">
            <strong>First Name:</strong> <span><?php echo $officer['firstname']; ?></span>
        </div>
        <div class="profile-info">
            <strong>Last Name:</strong> <span><?php echo $officer['lastname']; ?></span>
        </div>
        <div class="profile-info">
            <strong>Email:</strong> <span><?php echo $officer['email']; ?></span>
        </div>
        <div class="profile-info">
            <strong>Phone:</strong> <span><?php echo $officer['phone']; ?></span>
        </div>
        <div class="profile-info">
            <strong>Zanzibar ID:</strong> <span><?php echo $officer['zanzibar_id']; ?></span>
        </div>
    </div>
    <?php include('../components/includes/footer.php'); ?>
</body>
</html>
