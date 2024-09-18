<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Officer Interface</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #e6ffcc;
        }
        .navbar {
            background-color: #59b300;
        }
        .navbar-brand img {
            height: 40px;
        }
        .card {
            margin-bottom: 20px;
        }
        /* Add custom styling for the dropdown hover */
        .dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0; /* removes gap so it looks more natural */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">
            <img src="./assets/images.jpg" alt="Logo">
            Farmer Officer
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="./index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#cloveProgress">Clove Progress</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#comments">Comments</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button">
                        Activities
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="./progress.php">Progress</a>
                        <a class="dropdown-item" href="./comments.php">Comments</a>
                        <a class="dropdown-item" href="./profile.php">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../login.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
