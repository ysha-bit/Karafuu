<?php

session_start();
include('../conn/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Query to retrieve the number of users
$sqlUsers = "SELECT COUNT(*) as count FROM users ";
$resultUsers = mysqli_query($conn, $sqlUsers);
$numberOfUsers = 0;
if ($resultUsers) {
    $rowUsers = mysqli_fetch_assoc($resultUsers);
    $numberOfUsers = $rowUsers['count'];
}

$current_year = date("Y");
// Query to retrieve the number of farmers
$sqlFarmers = "SELECT COUNT(*) as count FROM farmers WHERE date = '$current_year'";
$resultFarmers = mysqli_query($conn, $sqlFarmers);
$numberOfFarmers = 0;
if ($resultFarmers) {
    $rowFarmers = mysqli_fetch_assoc($resultFarmers);
    $numberOfFarmers = $rowFarmers['count'];
}

// Query to retrieve the number of clove trees
$sqlCloveTrees = "SELECT SUM(amount_of_miche) as miche_count FROM farmers WHERE date = '$current_year'";
$resultCloveTrees = mysqli_query($conn, $sqlCloveTrees);
$numberOfCloveTrees = 0;
if ($resultCloveTrees) {
    $rowCloveTrees = mysqli_fetch_assoc($resultCloveTrees);
    $numberOfCloveTrees = $rowCloveTrees['miche_count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Clove Trees Management</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
        }


        .small-box {
            /* position: relative;
            display: block; */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 0.25rem;
            padding: 20px;
            color: #fff;
            
        }

        .small-box .icon {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 3rem;
        }

        .small-box .inner {
            padding: 5px 10px;
        }

        .small-box h3 {
            font-size: 2rem;
            margin: 0;
        }

        .small-box p {
            font-size: 1rem;
            margin: 0;
        }
        .small-box a {
            color: #fff;
            text-decoration:none;
        }


        .bg-users {
            background-color: #17a2b8;
        }

        .bg-farmers {
            background-color: #28a745;
        }

        .bg-clove-trees {
            background-color: #ffc107;
        }
        #container{
            margin-left: 180px;
        }
        #title{
            margin-left: 180px;  
        }
    </style>
</head>

<body>
    <?php include('../pages/includes/navbar.php'); ?>

    <div class="row">
        <?php include('../pages/includes/sidebar.php'); ?>
        <div class="container">
            <div class="row content-wrapper ">
                <div id="title">
                <h3 class="mb-4"> <i class="fa fa-home" style="font-size:36px"></i> Dashboard</h3>
                </div>
                

                <div class="" id="container">
                     <!-- Number of Users Card -->
                <div class=" col-md-3">
                    <div class="small-box bg-users">
                        <div class="inner">
                            <h3><?php echo $numberOfUsers; ?></h3>
                            <p>Number of Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="./add_user.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- Number of Farmers Card -->
                <div class=" col-md-3">
                    <div class="small-box bg-farmers">
                        <div class="inner">
                            <h3><?php echo $numberOfFarmers; ?></h3>
                            <p>Number of Farmers</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="./farmers_lists.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- Number of Clove Trees Card -->
                <div class="col-md-3">
                    <div class="small-box bg-clove-trees">
                        <div class="inner">
                            <h3><?php echo $numberOfCloveTrees; ?></h3>
                            <p>Number of Clove Trees</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="./farmers_lists.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                </div>
               
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS for interactive components -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
