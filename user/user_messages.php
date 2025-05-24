<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/jaga/landrecordsys/admin/includes/dbconnection.php");

$user_id = $_SESSION['user_id']; // Logged-in user

$query = "SELECT m.id, u.username AS sender_name, p.property_id, m.message, m.timestamp 
          FROM messages m
          JOIN users u ON m.sender_id = u.id
          JOIN addproperty p ON m.property_id = p.id
          WHERE m.receiver_id = '$user_id' ORDER BY m.timestamp DESC";

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
</head>
<body>
    <h2>Your Messages</h2>
    <table border="1">
        <tr>
            <th>Sender</th>
            <th>Property ID</th>
            <th>Message</th>
            <th>Time</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['sender_name']; ?></td>
                <td><?php echo $row['property_id']; ?></td>
                <td><?php echo $row['message']; ?></td>
                <td><?php echo $row['timestamp']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
