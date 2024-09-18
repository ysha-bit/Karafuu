<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 62px; Adjust based on navbar height
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
            border-right: 1px solid #ddd;
            color: white;
        }
        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 16px;
            color: #ccc;
            display: block;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            background-color: #007bff;
            color: white;
        }
        .sidebar a.active {
            background-color: #007bff;
            color: white;
        }
        .sidebar .sidebar-header {
            font-size: 20px;
            font-weight: bold;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .sidebar .fa {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="col-md-3">

</div>
    <div class="sidebar">
        <div class="sidebar-header">
            Admin Menu
        </div>
        <a href="../pages/index.php" class="active"><i class="fa fa-dashboard"></i> Dashboard</a>
        <a href="../pages/register_farmer.php"><i class="fa fa-users"></i> Add Farmers</a>
        <a href="../pages/farmers_lists.php"><i class="fa fa-cogs"></i> Farmers Lists</a>
        <a href="../pages/add_user.php"><i class="fa fa-bar-chart"></i> Add Users</a>
        
    </div>
</body>
</html>
