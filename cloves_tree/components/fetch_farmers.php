<?php
// Include your database connection
include('../conn/config.php');

if (isset($_POST['shehia'])) {
    $shehia = $_POST['shehia'];

    // Query to retrieve farmer names based on the selected Shehia
    $query_farmers = "SELECT firstname, lastname FROM farmers WHERE shehia = '$shehia'";
    $result_farmers = mysqli_query($conn, $query_farmers);

    if ($result_farmers) {
        if (mysqli_num_rows($result_farmers) > 0) {
            echo '<option value="">Select Farmer</option>';
            while ($row = mysqli_fetch_assoc($result_farmers)) {
                echo '<option value="' . $row['firstname'] . ' ' . $row['lastname'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
            }
        } else {
            echo '<option value="">No Farmers found</option>';
        }
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
}
?>
