<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: user_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e3f2fd; /* Light sky blue */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .dashboard-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
            position: relative;
        }
        h2 {
            color: #0288d1; /* Sky blue */
            margin-bottom: 15px;
        }
        .user-info {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }
        .btn {
            background-color: #0288d1;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            display: block;
            text-decoration: none;
            text-align: center;
        }
        .btn:hover {
            background-color: #0277bd;
        }
        .logout {
            background-color: #d32f2f;
        }
        .logout:hover {
            background-color: #b71c1c;
        }
        .inbox {
            background-color: #ff9800;
        }
        .inbox:hover {
            background-color: #f57c00;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo $_SESSION["user_name"]; ?>! 👋</h2>
        <p class="user-info">Manage your properties easily with the options below.</p>

        <a href="add_property.php" class="btn">➕ Add Property</a>
        <a href="view_properties.php" class="btn">📋 View Properties</a>
        <a href="search_property.php" class="btn">🔍 Search Property</a>
        <a href="mywishlist.php" class="btn"> ❤ Wish List</a>
        <a href="user_logout.php" class="btn logout">🚪 Logout</a>
    </div>
</body>
</html>
