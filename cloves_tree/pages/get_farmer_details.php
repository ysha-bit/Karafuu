<?php
include('../conn/config.php');

$response = array('success' => false);

if (isset($_POST['zanzibar_id'])) {
    $zanzibar_id = mysqli_real_escape_string($conn, $_POST['zanzibar_id']);

    $sql = "SELECT * FROM farmers WHERE zanzibar_id = '$zanzibar_id' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $response = array(
            'success' => true,
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'phone' => $row['phone'],
            'shehia' => $row['shehia'],
            'wilaya' => $row['wilaya']
        );
    }
}

echo json_encode($response);
?>
