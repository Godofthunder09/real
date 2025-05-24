<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/admin/includes/dbconnection.php");

if (!isset($_GET['property_id'])) {
    echo "Property ID is missing.";
    exit;
}

$property_id = $_GET['property_id'];

$query = "SELECT * FROM addproperty WHERE property_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $property_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "Property not found.";
    exit;
}

$property = mysqli_fetch_assoc($result);

// Handle image
$imageFile = htmlspecialchars($property['images']);
$imagePath = "/jaga/landrecordsys/uploads/" . $imageFile;
$fullPath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;
$imgSrc = (file_exists($fullPath) && !empty($imageFile)) ? $imagePath : "/jaga/landrecordsys/uploads/default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Property Details</title>
<style>
    body {
        background-color: #0d1b2a;
        color: #f0f0f0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 40px 20px;
    }

    .container {
        max-width: 900px;
        margin: auto;
        background-color: #1b263b;
        border-radius: 20px;
        box-shadow: 0 0 30px rgba(0, 255, 255, 0.2);
        padding: 30px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .property-img {
        flex: 1 1 300px;
        max-width: 100%;
    }

    .property-img img {
        width: 100%;
        height: auto;
        border-radius: 16px;
        box-shadow: 0 0 15px rgba(0, 255, 255, 0.4);
    }

    .property-info {
        flex: 1 1 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .property-info h2 {
        color: #00e5ff;
        font-size: 28px;
        margin-bottom: 20px;
    }

    .info-group {
        margin-bottom: 12px;
    }

    .info-group strong {
        color: #67e8f9;
        display: inline-block;
        width: 140px;
    }

    .back-btn {
        margin-top: 30px;
        padding: 10px 20px;
        background-color: #00e5ff;
        color: #0d1b2a;
        font-weight: bold;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(0, 255, 255, 0.6);
        transition: background 0.3s;
    }

    .back-btn:hover {
        background-color: #0ff;
    }
</style>
</head>
<body>

<div class="container">
    <div class="property-img">
        <img src="<?php echo $imgSrc; ?>" alt="Property Image">
    </div>
    <div class="property-info">
        <h2>Property at <?php echo htmlspecialchars($property['location']); ?></h2>
        <div class="info-group"><strong>Owner:</strong> <?php echo htmlspecialchars($property['owner_name']); ?></div>
        <div class="info-group"><strong>Phone:</strong> <?php echo htmlspecialchars($property['phone']); ?></div>
        <div class="info-group"><strong>Email:</strong> <?php echo htmlspecialchars($property['email']); ?></div>
        <div class="info-group"><strong>Type:</strong> <?php echo htmlspecialchars($property['property_type']); ?></div>
        <div class="info-group"><strong>Land Area:</strong> <?php echo htmlspecialchars($property['land_area']) . " sq.m"; ?></div>
        <div class="info-group"><strong>Price Range:</strong> <?php echo htmlspecialchars($property['price_range']); ?></div>
        <div class="info-group"><strong>Created At:</strong> <?php echo htmlspecialchars($property['created_at']); ?></div>

        <button class="back-btn" onclick="window.history.back()">← Back</button>
    </div>
</div>

</body>
</html>
