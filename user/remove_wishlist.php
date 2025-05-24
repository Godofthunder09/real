<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/admin/includes/dbconnection.php");

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['property_id'])) {
    $user_id = $_SESSION['user_id'];
    $property_id = $_POST['property_id'];

    $query = "DELETE FROM wishlist_properties WHERE user_id = ? AND property_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $property_id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: wishlist.php");
    } else {
        echo "Error removing property from wishlist.";
    }
}
?>
