<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/admin/includes/dbconnection.php");

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to add properties to your wishlist.";
    exit;
}

$user_id = $_SESSION['user_id'];
$property_id = isset($_POST['property_id']) ? trim($_POST['property_id']) : '';

if (empty($property_id)) {
    echo "Invalid property.";
    exit;
}

// Check if already in wishlist
$checkQuery = "SELECT * FROM wishlist_properties WHERE user_id = ? AND property_id = ?";
$stmt = mysqli_prepare($con, $checkQuery);
mysqli_stmt_bind_param($stmt, "is", $user_id, $property_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "This property is already in your wishlist.";
} else {
    // Insert into wishlist
    $insertQuery = "INSERT INTO wishlist_properties (user_id, property_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($con, $insertQuery);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $property_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Property added successfully to wishlist";
    } else {
        echo "Failed to add property to wishlist.";
    }
}

mysqli_close($con);
?>
