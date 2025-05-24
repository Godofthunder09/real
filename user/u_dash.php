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
    <title>Futuristic User Dashboard</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background: radial-gradient(circle, #0f172a, #000);
        height: 100vh;
        display: flex;
        color: white;
    }

    /* Sidebar */
    .dashboard-container {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        padding: 20px;
        width: 260px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 5px 0px 15px rgba(0, 255, 255, 0.3);
    }

    h2 {
        color: #00eaff;
        margin-bottom: 15px;
        text-shadow: none;
    }

    .btn {
        background: rgba(0, 255, 255, 0.2);
        color: #00eaff;
        border: 1px solid #00eaff;
        padding: 12px;
        width: 100%;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        margin: 10px 0;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease-in-out;
        box-shadow: none;
    }

    .btn:hover {
        background: #00eaff;
        color: #0f172a;
    }

    .logout {
        background: rgba(255, 0, 0, 0.2);
        border: 1px solid #ff0000;
        color: #ff4d4d;
    }

    .logout:hover {
        background: #ff0000;
        color: #0f172a;
    }

    /* Right Content */
    .content {
        margin-left: 260px;
        padding: 20px;
        flex: 1;
        width: calc(100% - 260px);
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    #dashboard-content {
        width: 100%;
    }

    .dashboard-box {
        background: rgba(0, 255, 255, 0.1);
        padding: 40px;
        border-radius: 10px;
        box-shadow: none;
        width: 100%;
    }

    .dashboard-box h2 {
        font-size: 24px;
        text-shadow: none;
    }
</style>

</head>
<body>

    <!-- Sidebar -->
    <div class="dashboard-container">
        <h2>Welcome, <?php echo $_SESSION["user_name"]; ?>! 👋</h2>
        <p>Manage your properties easily.</p>

        <button class="btn" onclick="loadPage('add_property.php')">➕ Add Property</button>
        <button class="btn" onclick="loadPage('new_view.php')">📋 View Properties</button>
        <button class="btn" onclick="loadPage('search_property.php')">🔍 Search Property</button>
        <button class="btn" onclick="loadPage('wishlist.php')">❤ Wish List</button>
        <a href="user_logout.php" class="btn logout">🚪 Logout</a>
    </div>

    <!-- Right-Side Content -->
    <div class="content" id="dashboard-content">
        <div class="dashboard-box">
            <h2>User Dashboard</h2>
            <p>Select an option from the left menu.</p>
        </div>
    </div>

    <script>
        function loadPage(page) {
            fetch(page)
            .then(response => response.text())
            .then(data => {
                document.getElementById("dashboard-content").innerHTML = data; 
            })
            .catch(error => console.log("Error loading page: " + error));
        }
    </script>

</body>
</html>
