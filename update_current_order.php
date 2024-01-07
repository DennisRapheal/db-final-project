<?php
session_start();
require_once('connection.php');
$courier_id = $_SESSION['user_id'];
// Assuming you have a variable $orderId with the order ID you want to update
$order_id = $_POST['selected_order']; // Replace with your actual order ID

// Assuming you have a variable $newStatus with the new status value you want to set
$newStatus = "done"; // Replace with your actual new status value

try {
    // Prepare the SQL query
    $updateQuery = "UPDATE orders SET status = :newStatus WHERE orders.order_id = :order_id; ";
    $stmt = $conn->prepare($updateQuery);

    // Bind parameters
    $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Check if the update was successful
    $rowCount = $stmt->rowCount(); // Number of affected rows


    if ($rowCount > 0) {
        header("Location: history_order.php.html");
        exit();
    } else {
        echo "The specified order ID may not exist or the status is already set to the provided value.";
    }
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>