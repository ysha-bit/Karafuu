<?php
// Include your database connection
include('../conn/config.php');

if (isset($_POST['wilaya'])) {
    $wilaya = $_POST['wilaya'];

    // Query to retrieve Shehia names based on the selected Wilaya
    $query_shehia = "SELECT DISTINCT shehia FROM farmers WHERE wilaya = '$wilaya'";
    $result_shehia = mysqli_query($conn, $query_shehia);

    if ($result_shehia) {
        if (mysqli_num_rows($result_shehia) > 0) {
            echo '<option value="">Select Shehia</option>';
            while ($row = mysqli_fetch_assoc($result_shehia)) {
                echo '<option value="' . $row['shehia'] . '">' . $row['shehia'] . '</option>';
            }
        } else {
            echo '<option value="">No Shehia found</option>';
        }
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
}
?>
