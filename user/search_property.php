<?php
include($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/admin/includes/dbconnection.php");

$searchResult = null;
$errorMessage = "";

// Ensure the database connection is working
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $property_id = trim($_POST['property_id']);

    if (empty($property_id)) {
        $errorMessage = "Please enter a valid Property ID.";
    } else {
        // Prepare the SQL query
        $query = "SELECT * FROM addproperty WHERE property_id = ?";
        $stmt = mysqli_prepare($con, $query);

        if (!$stmt) {
            die("Query preparation failed: " . mysqli_error($con));
        }

        // Bind and execute query
        mysqli_stmt_bind_param($stmt, "s", $property_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $searchResult = mysqli_fetch_assoc($result);
        } else {
            $errorMessage = "No property found with this Property ID.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Property</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0d0d0d;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
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
        .search-box {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-box input {
            padding: 10px;
            width: 70%;
            border-radius: 5px;
            border: 1px solid #00e5ff;
            background-color: #111;
            color: #fff;
            font-size: 14px;
        }
        .search-box input[type="submit"] {
            width: 20%;
            background: #00e5ff;
            color: black;
            border: none;
            cursor: pointer;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            margin-left: 10px;
        }
        .search-box input[type="submit"]:hover {
            background: #00b8d4;
        }
        .error-msg {
            color: red;
            font-size: 16px;
            margin-top: 10px;
            text-align: center;
        }
        .property-card {
            background: #222;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 5px 10px rgba(0, 255, 255, 0.1);
            margin-top: 20px;
            text-align: left;
        }
        .property-card img {
            width: 100%;
            max-height: 250px;
            object-fit: contain;
            border-radius: 5px;
            margin-top: 10px;
        }
        .property-card p {
            color: #bbb;
            font-size: 14px;
        }
        .image-upload {
            text-align: center;
            margin-top: 20px;
        }
        .back-to-home {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            background: #00e5ff;
            color: black;
            text-align: center;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
        }
        .back-to-home:hover {
            background: #00b8d4;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Search Property</h2>

    <form method="POST" action="search_property.php" class="search-box">
        <input type="text" name="property_id" placeholder="Enter Property ID" required>
        <input type="submit" value="Search">
    </form>

    <?php if (!empty($errorMessage)): ?>
        <p class="error-msg"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>

    <?php if ($searchResult): ?>
        <div class="property-card">
            <h3>Property Details</h3>
            <p><strong>Property ID:</strong> <?php echo htmlspecialchars($searchResult['property_id']); ?></p>
            <p><strong>Owner Name:</strong> <?php echo htmlspecialchars($searchResult['owner_name']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($searchResult['phone']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($searchResult['email']); ?></p>
            <p><strong>Property Type:</strong> <?php echo htmlspecialchars($searchResult['property_type']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($searchResult['location']); ?></p>
            <p><strong>Land Area:</strong> <?php echo htmlspecialchars($searchResult['land_area']); ?> sq. ft</p>
            <p><strong>Price Range:</strong> <?php echo htmlspecialchars($searchResult['price_range']); ?></p>

            <label><strong>Property Image:</strong></label>
            <div class="image-upload">
                <?php 
                $imageFile = htmlspecialchars($searchResult['images']); 
                $imagePath = "/jaga/landrecordsys/uploads/" . $imageFile;
                $fullPath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;

                if (!empty($imageFile) && file_exists($fullPath)) {
                    echo '<img src="'.$imagePath.'" alt="Property Image">';
                } else {
                    echo '<p style="color:red;">Image not found. Displaying default image.</p>';
                    echo '<img src="/jaga/landrecordsys/uploads/default.png" alt="Default Image">';
                }
                ?>
            </div>
        </div>
    <?php endif; ?>

    <a href="u_dash.php" class="back-to-home">Back to Home</a>
</div>

</body>
</html>
