<?php
session_start(); // Start the session at the beginning of the script
require_once('connection.php');
try {
    // Your database connection
    
    // Check if user_id is set in the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $history_order = "SELECT * FROM orders WHERE user_id = :user_id AND status = 'done' ORDER BY order_id DESC;";
        // Use $user_id in your query or operations
        // For example, if you want to fetch orders for this user:
        $stmt = $conn->prepare($history_order);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Your existing HTML output
        echo "<!DOCTYPE html>";

        echo "<html lang='en'>";  
        echo "<head>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0' />";
        echo "<meta charset='UTF-8'>";
        echo "<title>U-DBer Ordering System</title>";
        echo "<link rel='stylesheet' href='/oldcss/historyorders.css'>";
        echo "<link rel='preconnect' href='https://fonts.googleapis.com'>";
        echo "<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>";
        echo "<link href='https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Dancing+Script&family=Rubik+Doodle+Shadow&family=Rubik+Scribble&display=swap' rel='stylesheet'>";
        echo "</head>";

        echo "<body>";
        echo "<div class='history-restaurant-container'>";
            echo "<div class='title'>History Restaurant</div>";
            echo "<table class='restaurant-filter-table'>";
                echo "<thead>";
                    echo "<tr>";
                    echo "<th>Restaurant Name</th>";
                    echo "<th>Orders</th>";
                    echo "<th>Destination</th>";
                    echo "<th>Delivery fee</th>";
                    echo "<th>Status</th>";
                    echo "</tr>";
                echo "</thead>";

                echo "<tbody>";
                    foreach ($result as $row) {
                    echo "<tr>";
                    //query select
                    $restaurant_id = $row['restaurant_id'];
                    $restaurant_name_stmt = $pdo->prepare("SELECT restaurant_name FROM restaurant WHERE restaurant_id = :restaurant_id");
                    $restaurant_name_stmt->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
                    $restaurant_name_stmt->execute();
                    $restaurant_name = $restaurant_name_stmt->fetchColumn();

                    echo "<td>" . htmlspecialchars($restaurant_name, ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row['user_order'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row['order_address'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row['delivery_fee'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>";
                    
                    echo "</tr>";
                    }
                echo "</tbody>";
            echo "</table>";

        echo "</div>";
        echo "</body>";
        echo "</html>";

    } else {
        echo "<p>User ID not found in session.</p>";
    }
}
catch(PDOException $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>