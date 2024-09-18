<?php
session_start();
include('../conn/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get the current year
$current_year = date("Y");

// Query to retrieve farmers added in the current year
$sql = "SELECT * FROM comments WHERE  YEAR(date) = '$current_year'";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Delete farmer
if(isset($_POST['delete'])){
    $id_delete = mysqli_real_escape_string($conn, $_POST['id_delete']);
    $sql = "DELETE FROM comments WHERE comment_id = $id_delete";

    if(mysqli_query($conn, $sql)){
        header('location:farmers_lists.php');
    } else {
        echo 'Query error: '. mysqli_error($conn);
    }
}

// Update farmer
if(isset($_POST['update'])){
    $comment_id = mysqli_real_escape_string($conn, $_POST['comment_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status_of_trees']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    
    $sql = "UPDATE comments SET status_of_trees='$status', description='$description' WHERE comment_id=$comment_id";

    if(mysqli_query($conn, $sql)){
        header('location:comments.php');
    } else {
        echo 'Query error: '. mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    
    <style>
      #thead th {
        background-color: #00308F;
        color: #fff;
        
      }
      .modal-content {
            max-height: 75vh; /* Adjust the height as needed (75vh is 75% of the viewport height) */
            overflow-y: auto; /* Enable vertical scrolling if content exceeds the max height */
        }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:../../partials/_navbar.html -->
      <?php include('./partials/navbar.php'); ?>
      <div  class="row">
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
        <?php include('./partials/sidebar.php'); ?>
       
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Comments Table</h4>
                    
                    </p>
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead id="thead">
                          <tr>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Action</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                          <tr>
                          <td> <?php echo htmlspecialchars($row['status_of_trees']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['description']); ?> </td>
                            
                            <td>
                            <button class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#editModal_<?php echo $row['comment_id']; ?>">Edit</button>
                                                
                                                <!-- Delete Form -->
                                                <form action="comments.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="id_delete" value="<?php echo $row['comment_id']; ?>">
                                                    <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger btn-sm">
                                                </form> 
                            </td>
                          </tr>
                          <!-- Edit Modal -->
                          <div class="modal fade" id="editModal_<?php echo $row['comment_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel_<?php echo $row['comment_id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel_<?php echo $row['comment_id']; ?>">Edit Farmer</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="farmers_lists.php" method="POST">
                                                                <input type="hidden" name="farmer_id" value="<?php echo $row['comment_id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="firstname">Status</label>
                                                                    <input type="text" name="status" class="form-control" value="<?php echo htmlspecialchars($row['status_of_trees']); ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="lastname">Description</label>
                                                                    <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($row['description']); ?>" required>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="update" class="btn btn-outline-primary">Save changes</button>
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
              
              
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
      
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      </div>
    
      <!-- partial -->
      
      <!-- page-body-wrapper ends -->
    </div>
    <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; eGaz Field Project  2024</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><a  href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a></span>
                </div>
            </footer>
        </div>
    </div>
</div>
<script src="assets/vendors/js/vendor.bundle.base.js"></script>
<script src="assets/js/off-canvas.js"></script>
<script src="assets/js/template.js"></script>
<script src="assets/js/settings.js"></script>
<script src="assets/js/todolist.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>