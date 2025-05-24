<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/admin/includes/dbconnection.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        if (password_verify($password, $row["password"])) {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];

            header("Location: u_dash.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with this email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #1a1a2e;
            padding: 30px 25px;
            border-radius: 15px;
            width: 400px;
            text-align: center;
            box-shadow: 0 0 25px #00eaff80;
        }
        h2 {
            margin-bottom: 25px;
            color: #00eaff;
            font-size: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input {
            padding: 12px;
            border: 2px solid #00eaff;
            border-radius: 5px;
            background-color: #0f3460;
            color: white;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #ff9800;
        }
        .btn {
            background-color: #00eaff;
            color: black;
            font-weight: bold;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #ff9800;
        }
        .register-link {
            margin-top: 15px;
            display: block;
            color: #00eaff;
            text-decoration: none;
            font-size: 14px;
        }
        .register-link:hover {
            text-decoration: underline;
            color: #ff9800;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>
        
        <?php if (!empty($error)) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        
        <a href="user_register.php" class="register-link">Don't have an account? Register</a>
    </div>
</body>
</html>
