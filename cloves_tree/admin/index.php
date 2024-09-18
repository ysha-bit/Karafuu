<?php
session_start();
include('../conn/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$current_year = date("Y");

// Query to retrieve the number of farmers
$sqlFarmers = "SELECT COUNT(*) as count FROM farmers";
$resultFarmers = mysqli_query($conn, $sqlFarmers);
$numberOfFarmers = $resultFarmers ? mysqli_fetch_assoc($resultFarmers)['count'] : 0;

$sqlFarmers_per_year = "SELECT COUNT(*) as count1 FROM farmers WHERE date = '$current_year'";
$resultFarmers_per_year = mysqli_query($conn, $sqlFarmers_per_year);
$numberOfFarmers_per_year = $resultFarmers_per_year ? mysqli_fetch_assoc($resultFarmers_per_year)['count1'] : 0;

// Query to retrieve the number of clove trees
$sqlCloveTrees = "SELECT SUM(amount_of_miche) as miche_count FROM farmers";
$resultCloveTrees = mysqli_query($conn, $sqlCloveTrees);
$numberOfCloveTrees = $resultCloveTrees ? mysqli_fetch_assoc($resultCloveTrees)['miche_count'] : 0;

$sqlCloveTrees_per_year = "SELECT SUM(amount_of_miche) as miche_count1 FROM farmers WHERE date = '$current_year'";
$resultCloveTrees_per_year = mysqli_query($conn, $sqlCloveTrees_per_year);
$numberOfCloveTrees_per_year = $resultCloveTrees_per_year ? mysqli_fetch_assoc($resultCloveTrees_per_year)['miche_count1'] : 0;

// Query to retrieve the progress of clove trees
$sqlProgress = "SELECT SUM(number_tree) as count FROM treeprogress";
$resultProgress = mysqli_query($conn, $sqlProgress);
$numberOfProgress = $resultProgress ? mysqli_fetch_assoc($resultProgress)['count'] : 0;

$sqlProgress_per_year = "SELECT SUM(number_tree) as count FROM treeprogress WHERE YEAR(progress_date) = '$current_year' ";
$resultProgress_per_year = mysqli_query($conn, $sqlProgress);
$numberOfProgress_per_year = $resultProgress_per_year ? mysqli_fetch_assoc($resultProgress_per_year)['count'] : 0;

$numberOfDecreasedTrees = $numberOfCloveTrees_per_year - $numberOfProgress_per_year;
$decreasedBy = ($numberOfDecreasedTrees / $numberOfCloveTrees_per_year) * 100;
$decreasedBy = number_format($decreasedBy, 2);

// Query to retrieve clove trees per year for histogram
$sqlCloveTreesPerYear = "SELECT date as year, SUM(amount_of_miche) as miche_count FROM farmers GROUP BY date";
$resultCloveTreesPerYear = mysqli_query($conn, $sqlCloveTreesPerYear);
$years = $miche_counts = [];
if ($resultCloveTreesPerYear) {
    while ($row = mysqli_fetch_assoc($resultCloveTreesPerYear)) {
        $years[] = $row['year'];
        $miche_counts[] = $row['miche_count'];
    }
}

// Total clove trees for pie chart
$sqlTotalCloveTrees = "SELECT SUM(amount_of_miche) as total_miche FROM farmers";
$resultTotalCloveTrees = mysqli_query($conn, $sqlTotalCloveTrees);
$totalMiche = $resultTotalCloveTrees ? mysqli_fetch_assoc($resultTotalCloveTrees)['total_miche'] : 0;

$numberOfDecreasedTrees = $totalMiche - $numberOfProgress;
$decreasedBy = ($numberOfDecreasedTrees / $totalMiche) * 100;
$decreasedBy = number_format($decreasedBy, 2);


// Retrieve data for available trees
$sqlAvailableTrees = "SELECT 
                        YEAR(progress_date) AS year, 
                        SUM(number_tree) AS available_trees
                     FROM 
                        treeprogress
                     GROUP BY 
                        YEAR(progress_date)";

$resultAvailableTrees = mysqli_query($conn, $sqlAvailableTrees);

if (!$resultAvailableTrees) {
    die("Error executing query: " . mysqli_error($conn));
}

// Retrieve data for provided trees
$sqlProvidedTrees = "SELECT 
                        date AS year, 
                        SUM(amount_of_miche) AS tree_provided
                     FROM 
                        farmers
                     GROUP BY 
                        date";

$resultProvidedTrees = mysqli_query($conn, $sqlProvidedTrees);

if (!$resultProvidedTrees) {
    die("Error executing query: " . mysqli_error($conn));
}

// Initialize arrays to store results
$availableTreesArray = [];
$providedTreesArray = [];

// Populate available trees array
while ($row = mysqli_fetch_assoc($resultAvailableTrees)) {
    $availableTreesArray[$row['year']] = $row['available_trees'];
}

// Populate provided trees array
while ($row = mysqli_fetch_assoc($resultProvidedTrees)) {
    $providedTreesArray[$row['year']] = $row['tree_provided'];
}

$totalTreeProvided = 0;
$totalAvailableTrees = 0;
$totalTreeLost = 0;

// Populate table rows
foreach ($providedTreesArray as $year => $treeProvided) {
    $availableTrees = isset($availableTreesArray[$year]) ? $availableTreesArray[$year] : 0;
    $treeLost = $treeProvided - $availableTrees;

    // Push values to respective arrays for Chart.js
    $totalTreeProvidedArray[] = $treeProvided;
    $totalTreeLostArray[] = $treeLost;
    $totalAvailableTreesArray[] = $availableTrees;

    // Summing up the totals
    $totalTreeProvided += $treeProvided;
    $totalAvailableTrees += $availableTrees;
    $totalTreeLost += $treeLost;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags and required CSS -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ZSTC Admin</title>
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report{
            margin-bottom:20px;
            color:#00308F;
        }
        
    </style>
</head>
<body>
    <?php include('./partials/navbar.php'); ?>
    <div class="container-fluid page-body-wrapper">
        <?php include('./partials/sidebar.php'); ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <h3 class="page-title">
                        <span class="page-title-icon bg-gradient-primary text-white me-2">
                            <i class="mdi mdi-home"></i>
                        </span> Dashboard
                    </h3>
                </div>
                <div class="row">
                    <!-- Total Farmers Card -->
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.png" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Total Farmers <i class="mdi mdi-account mdi-48px float-end"></i></h4>
                                <h2 class="mb-5"><?php echo $numberOfFarmers; ?></h2>
                                <h6 class="card-text">Number of Farmers per year: <?php echo $numberOfFarmers_per_year; ?></h6>
                            </div>
                        </div>
                    </div>
                    <!-- Total Tree Provided Card -->
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.png" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Total Tree Provided <i class="mdi mdi-tree mdi-48px float-end"></i></h4>
                                <h2 class="mb-5"><?php echo $numberOfCloveTrees; ?></h2>
                                <h6 class="card-text">Number of Cloves Trees per year: <?php echo $numberOfCloveTrees_per_year; ?></h6>
                            </div>
                        </div>
                    </div>
                    <!-- Total Tree Available Card -->
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.png" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Total Tree Available <i class="mdi mdi-tree mdi-48px float-end"></i></h4>
                                <h2 class="mb-5"><?php echo $numberOfProgress_per_year; ?></h2>
                                <h6 class="card-text">Decreased by <?php echo $decreasedBy; ?>% as <?php echo $numberOfDecreasedTrees; ?> Trees</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                <div class="report">
                <h4>Generate Clove Trees Annual Report</h4>
                  <form action="generate_report.php" method="get">
                   <button type="submit" class="btn btn-outline-primary">Generate PDF Report</button>
                    </form>
                    </div>

                <!-- Charts Section -->
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <p class="card-title " style="color:#00308F;">Cloves Trees Report per Year</p>
                                </div>
                                <canvas id="sales-chart" ></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title" style="color:#00308F;">Total Cloves Trees Report</h4>
                                <canvas id="traffic-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
<!-- Histogram (Bar Chart) -->
<script>
    var ctx = document.getElementById('sales-chart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar', // Use 'bar' but with horizontal orientation
        data: {
            labels: <?php echo json_encode(array_keys($providedTreesArray)); ?>,
            datasets: [
                {
                    label: 'Total Clove Trees Provided',
                    data: <?php echo json_encode($totalTreeProvidedArray); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Light Blue
                    borderColor: 'rgba(54, 162, 235, 1)', // Dark Blue
                    borderWidth: 1
                },
                {
                    label: 'Decreased Clove Trees',
                    data: <?php echo json_encode($totalTreeLostArray); ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.7)', // Light Orange
                    borderColor: 'rgba(255, 159, 64, 1)', // Dark Orange
                    borderWidth: 1
                },
                {
                    label: 'Available Clove Trees',
                    data: <?php echo json_encode($totalAvailableTreesArray); ?>,
                    backgroundColor: 'rgba(0, 247, 24, 0.5)', // Light Teal
                    borderColor: 'rgba(0, 247, 24, 1)', // Dark Teal
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            indexAxis: 'y', // Set indexAxis to 'y' for horizontal bars
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#00308F' // Legend text color
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.x !== null) {
                                label += context.parsed.x.toLocaleString();
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Clove Trees'
                    }
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Year'
                    }
                }
            }
        }
    });
</script>

    
    <!-- Pie Chart -->
    <script>
        var ctx = document.getElementById('traffic-chart').getContext('2d');
        var trafficChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Available', 'Decreased', 'Provided'],
                datasets: [{
                    data: [<?php echo $numberOfProgress; ?>, <?php echo $numberOfDecreasedTrees; ?>, <?php echo $numberOfCloveTrees; ?>],
                    backgroundColor: ['rgba(0, 247, 24, 0.5)', 'rgba(255, 159, 64, 0.7)','rgba(54, 162, 235, 0.7)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 213, 4, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
    <script>
    console.log('Years:', <?php echo json_encode($years); ?>);
    console.log('Miche Counts:', <?php echo json_encode($miche_counts); ?>);
    console.log('Decreased Counts:', <?php echo json_encode($decreased_counts); ?>);
    console.log('Available Counts:', <?php echo json_encode($available_counts); ?>);
</script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/chart.js/chart.umd.js"></script>
    <script src="assets/vendors/datatables.net/jquery.dataTables.js"></script>
   
    <script src="assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
  
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
   
    <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="assets/js/dashboard.js"></script>

  
</body>
</html>
