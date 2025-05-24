<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/admin/includes/dbconnection.php");

// Fetch property types for dropdown
$propertyTypeQuery = "SELECT DISTINCT property_type FROM addproperty";
$propertyTypeResult = mysqli_query($con, $propertyTypeQuery);

// Handle Search Filters
$whereClause = "1=1"; // Default condition
$parameters = [];

if (!empty($_GET['property_type'])) {
    $property_type = mysqli_real_escape_string($con, $_GET['property_type']);
    $whereClause .= " AND property_type = '$property_type'";
}

if (!empty($_GET['location'])) {
    $location = mysqli_real_escape_string($con, $_GET['location']);
    $whereClause .= " AND location LIKE '%$location%'";
}

// Fetch filtered properties
$query = "SELECT * FROM addproperty WHERE $whereClause ORDER BY created_at DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Properties</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0d0d0d;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 95%;
            max-width: 1200px;
            margin: auto;
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 255, 255, 0.1);
        }

        h2 {
            color: #00e5ff;
            text-align: center;
            font-size: 28px;
            padding: 15px;
            border-bottom: 2px solid #00e5ff;
        }

        .search-form {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            box-shadow: 0px 2px 10px rgba(0, 255, 255, 0.1);
        }

        .search-form input,
        .search-form select {
            padding: 10px;
            width: 180px;
            border-radius: 5px;
            border: 1px solid #00e5ff;
            font-size: 14px;
            background: #111;
            color: #fff;
        }

        .search-form button {
            background: #00e5ff;
            color: #000;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .search-form button:hover {
            background: #00b8d4;
        }

        .property-list {
            width: 100%;
            margin-top: 20px;
        }

        .property-card {
            background: #222;
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 5px 10px rgba(0, 255, 255, 0.05);
            transition: 0.3s ease-in-out;
            cursor: pointer;
        }

        .property-card:hover {
            transform: scale(1.02);
            box-shadow: 0px 5px 15px rgba(0, 255, 255, 0.2);
        }

        .property-card img {
            width: 100%;
            max-height: 250px;
            object-fit: contain;
            border-radius: 5px;
            background-color: #000;
            margin-top: 10px;
        }

        .property-card h3 {
            color: #00e5ff;
            text-align: center;
            font-size: 20px;
        }

        .property-card p {
            margin: 5px 0;
            color: #bbb;
            font-size: 14px;
            text-align: center;
        }

        .wishlist-button {
            display: block;
            width: 100%;
            background: #00c853;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        .wishlist-button:hover {
            background: #00a843;
        }

        .no-properties {
            font-size: 18px;
            color: red;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>View All Properties</h2>

    <!-- Search Form -->
    <form method="GET" action="view_properties.php" class="search-form">
        <select name="property_type">
            <option value="">All Property Types</option>
            <?php while ($typeRow = mysqli_fetch_assoc($propertyTypeResult)): ?>
                <option value="<?php echo $typeRow['property_type']; ?>" 
                    <?php if (isset($_GET['property_type']) && $_GET['property_type'] == $typeRow['property_type']) echo 'selected'; ?> >
                    <?php echo htmlspecialchars($typeRow['property_type']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <input type="text" name="location" placeholder="Location" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
      
        <button type="submit">Search</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="property-list">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="property-card" onclick="window.location.href='view_properties.php?property_id=<?php echo urlencode($row['property_id']); ?>'">
                    <h3>Property ID: <?php echo htmlspecialchars($row['property_id']); ?></h3>
                    <p><strong>Owner:</strong> <?php echo htmlspecialchars($row['owner_name']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($row['property_type']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                    <p><strong>Land Area:</strong> <?php echo htmlspecialchars($row['land_area']); ?> sq. ft</p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($row['price_range']); ?></p>

                    <?php 
                        $imagePath = !empty($row['images']) && file_exists($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/uploads/" . $row['images']) 
                                     ? "/jaga/landrecordsys/uploads/" . htmlspecialchars($row['images']) 
                                     : "/jaga/landrecordsys/uploads/default.jpg"; 
                    ?>
                    <img src="<?php echo $imagePath; ?>" alt="Property Image">

                    <button class="wishlist-button" onclick="event.stopPropagation(); addToWishlist('<?php echo htmlspecialchars($row['property_id']); ?>')">Add to Wishlist</button>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-properties">No properties found.</p>
    <?php endif; ?>
</div>

<script>
function addToWishlist(propertyId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_wishlist.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
        }
    };

    xhr.send("property_id=" + encodeURIComponent(propertyId));
}
</script>

</body>
</html>
