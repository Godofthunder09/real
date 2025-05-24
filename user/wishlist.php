<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/admin/includes/dbconnection.php");

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your wishlist.";
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT addproperty.* FROM wishlist_properties 
          JOIN addproperty ON wishlist_properties.property_id = addproperty.property_id
          WHERE wishlist_properties.user_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Wishlist</title>
<style>
    body {
        background-color: #0d1b2a;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #f0f0f0;
        margin: 0;
        padding: 40px 20px;
    }

    h2 {
        text-align: center;
        color: #00e5ff;
        margin-bottom: 30px;
    }

    .card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
    }

    .property-card {
        background-color: #1b263b;
        border-radius: 16px;
        overflow: hidden;
        width: 300px;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 0 15px rgba(0, 255, 255, 0.2);
        position: relative;
    }

    .property-card:hover {
        transform: scale(1.03);
        box-shadow: 0 0 20px rgba(0, 255, 255, 0.6);
    }

    .property-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background-color: #f0f0f0;
    }

    .card-content {
        padding: 15px;
    }

    .card-content h3 {
        margin: 0;
        color: #67e8f9;
        font-size: 20px;
    }

    .card-content p {
        margin: 5px 0;
        font-size: 14px;
        color: #ccc;
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ef4444;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
    }

    .remove-btn:hover {
        background: #dc2626;
    }
</style>
</head>
<body>

<h2></h2>

<div class="card-container">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
                $property_id = htmlspecialchars($row['property_id']);
                $imageFile = htmlspecialchars($row['images']);
                $imagePath = "/jaga/landrecordsys/uploads/" . $imageFile;
                $fullPath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;
                $imgSrc = (file_exists($fullPath) && !empty($imageFile)) ? $imagePath : "/jaga/landrecordsys/uploads/default.png";
            ?>
            <div class="property-card" onclick="window.location.href='property_details.php?property_id=<?php echo $property_id; ?>'">
                <img src="<?php echo $imgSrc; ?>" alt="Property Image">
                <div class="card-content">
                    <h3><?php echo htmlspecialchars($row['location']); ?></h3>
                    <p><strong>Owner:</strong> <?php echo htmlspecialchars($row['owner_name']); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($row['property_type']); ?></p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($row['price_range']); ?></p>
                </div>
                <form method="post" action="remove_wishlist.php" onClick="event.stopPropagation();">
                    <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
                    <button type="submit" class="remove-btn">Remove</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; font-size: 18px;">No properties in your wishlist.</p>
    <?php endif; ?>
</div>

</body>
</html>
