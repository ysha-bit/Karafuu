<?php

session_start();
// Include your database connection
include('../conn/config.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$wilaya = $shehia  = $farmers = $number_of_trees = $age_of_cloves =  '';
$progress_errors = array('wilaya' => '', 'shehia' => '', 'farmers' => '', 'number_of_trees' => '', 'age_of_cloves' => '');

$status = $description =  '';
$comment_errors = array('status' => '', 'description' => '');


if (isset($_POST['submit'])) {
    // Validate and sanitize inputs
    if (empty($_POST['wilaya'])) {
        $errors['wilaya'] = "Amount of cloves trees is required";
    } else {
           
    }
    if (empty($_POST['shehia'])) {
        $errors['shehia'] = "Amount of cloves trees is required";
    } else {
        
       
    }
    if (empty($_POST['farmers'])) {
        $errors['farmers'] = "Farmer Name is required";
    } else {
        
       
    }
    if (empty($_POST['number_of_trees'])) {
        $errors['number_of_trees'] = "Insert number of trees";
    } else {
        
       
    }
    if (empty($_POST['age_of_cloves'])) {
        $errors['age_of_cloves'] = "Insert age of trees";
    } else {
        
       
    }

    

    if(!array_filter($progress_errors)){
        $shehia = mysqli_real_escape_string($conn, $_POST['shehia']);
        $wilaya = mysqli_real_escape_string($conn, $_POST['wilaya']);
        $farmers = mysqli_real_escape_string($conn, $_POST['farmers']);
        $number_of_trees = mysqli_real_escape_string($conn, $_POST['number_of_trees']);
        $age_of_cloves = mysqli_real_escape_string($conn, $_POST['age_of_cloves']);

        $date = date('Y/m/d H:i:s');

        $progress_date = mysqli_real_escape_string($conn, $date);
       
       

        $sql = "INSERT INTO treeprogress (wilaya,	shehia, farmer, number_tree, growth_stage, progress_date) VALUES('$shehia', '$wilaya', '$farmers', '$number_of_trees', '$age_of_cloves','$progress_date')";

        $result = mysqli_query($conn, $sql);

        if(!$result){
            echo "Query Error:".mysqli_error($conn);
        }

    }else{
        echo "There is an error is form!";
    }

}

if (isset($_POST['description'])) {
    // Validate and sanitize inputs
    if (empty($_POST['status'])) {
        $errors['status'] = "Status is required";
    } else {
           
    }
    if (empty($_POST['comment'])) {
        $errors['comment'] = "Amount of cloves trees is required";
    } else {
        
       
    }
    

    

    if(!array_filter($comment_errors)){
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $date = date('Y/m/d H:i:s');

        $comments_date = mysqli_real_escape_string($conn, $date);
        

        $sql = "INSERT INTO comments(description, status_of_trees, date) VALUES('$description', '$status', '$comments_date' )";

        $result = mysqli_query($conn, $sql);

        if(!$result){
            echo "Query Error:".mysqli_error($conn);
        }

    }else{
        echo "There is an error is form!";
    }

}




// Query to retrieve distinct Wilaya names
$query_wilaya = "SELECT DISTINCT wilaya FROM farmers";
$result_wilaya = mysqli_query($conn, $query_wilaya);
if (!$result_wilaya) {
    die("Query failed: " . mysqli_error($conn));
}

// Query to retrieve distinct Shehia names
$query_shehia = "SELECT DISTINCT shehia FROM farmers";
$result_shehia = mysqli_query($conn, $query_shehia);
if (!$result_shehia) {
    die("Query failed: " . mysqli_error($conn));
}

// Query to retrieve farmers' names
$query_farmers = "SELECT firstname, lastname FROM farmers";
$result_farmers = mysqli_query($conn, $query_farmers);
if (!$result_farmers) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta and CSS links -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Officer Interface</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        
        .content-wrapper {
            margin-top: 20px;
            margin: 2%;
        }

        #first-card .card {
            margin-bottom: 20px;
            border-top: 5px solid #59b300 !important;
            background-color: #ffffff;
            border-radius: 5px;
        }

        #first-card .card-header {
            font-weight: bold;
        }

        #first-card {
            margin-top: 40px;
            margin-left: 12%;
            background-color: #f2f2f2;
        }

        btn-success{
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            
        }
        
    </style>
</head>
<body>
    <?php include('../components/includes/navbar.php'); ?>
    
    <div class="card col-md-9" id="first-card">
        <div class="content-wrapper">
            <!-- Cloves Tree Progress Section -->
            <div class="card" id="cloveProgress">
                <div class="card-header" id="header">
                    Record Cloves Tree Progress
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <!-- Wilaya Select Field -->
                        <div class="form-group">
                            <label for="wilaya">Wilaya</label>
                            <select class="form-control" id="wilaya" name="wilaya" onchange="updateShehia()">
                                <option value="">Select Wilaya</option>
                                <?php
                                while ($row = mysqli_fetch_assoc($result_wilaya)) {
                                    echo '<option value="' . $row['wilaya'] . '">' . $row['wilaya'] . '</option>';
                                }
                                ?>
                            </select>
                            <div class="text-danger"> <?php echo $progress_errors['wilaya'] ?></div>
                        </div>

                        <!-- Shehia Select Field -->
                        <div class="form-group">
                            <label for="shehia">Shehia</label>
                            <select class="form-control" id="shehia" name="shehia" onchange="updateFarmers()">
                                <option value="">Select Shehia</option>
                            </select>
                            <div class="text-danger"> <?php echo $progress_errors['shehia'] ?></div>
                        </div>

                        <!-- Farmers Select Field -->
                        <div class="form-group">
                            <label for="farmers">Farmers</label>
                            <select class="form-control" id="farmers" name="farmers">
                                <option value="">Select Farmer</option>
                            </select>
                            <div class="text-danger"> <?php echo $progress_errors['farmers'] ?></div>
                        </div>

                        <!-- Other Form Fields -->
                        <div class="form-group">
                            <label for="numOfTrees">Number of cloves trees</label>
                            <input type="number" class="form-control" id="numOfTrees" name="number_of_trees" placeholder="Enter Number of trees">
                            <div class="text-danger"> <?php echo $progress_errors['number_of_trees'] ?></div>
                        </div>
                        <div class="form-group">
                            <label for="ageOfCloves">Age of cloves</label>
                            <input type="number" class="form-control" id="ageOfCloves" name="age_of_cloves" placeholder="Enter age">
                            <div class="text-danger"> <?php echo $progress_errors['age_of_cloves'] ?></div>
                        </div>

                        <!-- Submit Button -->
                        <input type="submit" class="btn btn-success" id="btn-success" name="submit" value="Submit">
                    </form>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card" id="comments">
                <div class="card-header">Make Comments</div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="status">Status of Tree</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Select Status</option>
                                <option value="healthy">Healthy</option>
                                <option value="normal">Normal</option>
                                <option value="infected">Infected</option>
                                <option value="worse">Worse</option>
                            </select>
                            <div class="text-danger"> <?php echo $comment_errors['status'] ?></div>
                        </div>
                        <div class="form-group">
                            <label for="commentSection">Your Comment</label>
                            <textarea class="form-control" id="commentSection" name="description" rows="3"></textarea>
                            <div class="text-danger"> <?php echo $comment_errors['description'] ?></div>
                        </div>
                        <input type="submit" class="btn btn-success" id="btn-success" name="comment" value="Comment">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JQuery and Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function updateShehia() {
            var wilaya = $('#wilaya').val();
            $.ajax({
                url: 'fetch_shehia.php',
                type: 'POST',
                data: {wilaya: wilaya},
                success: function(data) {
                    $('#shehia').html(data);
                }
            });
        }

        function updateFarmers() {
            var shehia = $('#shehia').val();
            $.ajax({
                url: 'fetch_farmers.php',
                type: 'POST',
                data: {shehia: shehia},
                success: function(data) {
                    $('#farmers').html(data);
                }
            });
        }
    </script>
    <?php include('../components/includes/footer.php'); ?>
</body>
</html>
