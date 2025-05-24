<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/admin/includes/dbconnection.php");

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

// Fetch messages where the user is the receiver
$query = "SELECT m.id, m.message, m.timestamp, u.username AS sender_name, p.property_type, p.location 
          FROM messages m
          JOIN users u ON m.sender_id = u.id
          JOIN addproperty p ON m.property_id = p.id
          WHERE m.receiver_id = '$user_id'
          ORDER BY m.timestamp DESC";

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .message-card {
            background: #fff;
            margin: 10px auto;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .message-card h3 {
            color: #0288d1;
        }
        .message-card p {
            margin: 5px 0;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Messages</h2>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="message-card">
                <h3>From: <?php echo htmlspecialchars($row['sender_name']); ?></h3>
                <p><strong>Property:</strong> <?php echo htmlspecialchars($row['property_type']) . " in " . htmlspecialchars($row['location']); ?></p>
                <p><strong>Message:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                <p><em>Sent at: <?php echo $row['timestamp']; ?></em></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>
</div>

</body>
</html>
