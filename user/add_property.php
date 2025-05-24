<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/jaga2/landrecordsys/admin/includes/dbconnection.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch logged-in user's details
$user_id = $_SESSION['user_id'];
$query_user = "SELECT name, email FROM users WHERE id = '$user_id'";
$result_user = mysqli_query($con, $query_user);
$user = mysqli_fetch_assoc($result_user);
$owner_name = $user['name'];
$email = $user['email'];

if (isset($_POST['submit'])) {
    $phone = $_POST['phone'];
    $property_type = $_POST['property_type'];
    $location = $_POST['location'];
    $land_area = $_POST['land_area'];
    $price_range = $_POST['price_range'];

    // Generate Unique Property ID
    $unique_id = "PROP-" . date("Ymd") . "-" . rand(1000, 9999);

    // Handle Image Upload
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/uploads/";
    $image_name = basename($_FILES["property_image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["property_image"]["tmp_name"], $target_file)) {
        $image_path = $image_name;
    } else {
        $image_path = "";
    }

    // Insert into Database
    $query = "INSERT INTO addproperty (property_id, owner_name, phone, email, property_type, location, land_area, price_range, images, user_id) 
              VALUES ('$unique_id', '$owner_name', '$phone', '$email', '$property_type', '$location', '$land_area', '$price_range', '$image_path', '$user_id')";

    if (mysqli_query($con, $query)) {
        echo "<script>
            alert('Property added successfully! Property ID: $unique_id');
            window.location.href = 'u_dash.php';
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 80vw;
            max-width: 600px;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.1);
        }

        h2 {
            color: #03a9f4;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            color: #bbb;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #444;
            border-radius: 5px;
            font-size: 16px;
            background-color: #252525;
            color: #fff;
        }

        input[readonly] {
            background-color: #333;
        }

        button {
            margin-top: 15px;
            padding: 12px;
            background: #03a9f4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0288d1;
        }

        .message {
            margin-top: 10px;
            color: green;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Property</h2>

    <?php if (!empty($message)) : ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="add_property.php" method="POST" enctype="multipart/form-data">
        <label>Owner Name:</label>
        <input type="text" name="owner_name" value="<?php echo $owner_name; ?>" readonly>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>" readonly>

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <label>Property Type:</label>
        <select name="property_type" required>
            <option value="Residential">Residential</option>
            <option value="Agricultural">Agricultural</option>
            <option value="Industrial">Industrial</option>
        </select>

        <label>Location:</label>
        <input type="text" name="location" required>

        <label>Land Area (sq. ft):</label>
        <input type="text" name="land_area" required>

        <label>Price:</label>
        <input type="text" name="price_range" required>

        <label>Upload Image:</label>
        <input type="file" name="property_image" required>

        <button type="submit" name="submit">Add Property</button>
    </form>
</div>

</body>
</html>
