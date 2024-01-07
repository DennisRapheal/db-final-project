<?php
    session_start();
    require_once('connection.php');
    // Specify SQL command
    $current_order = "SELECT * FROM orders 
                INNER JOIN restaurant ON restaurant.restaurant_id = orders.restaurant_id 
                INNER JOIN dber ON dber.user_id = orders.courier_id 
                WHERE orders.user_id = :user_id and orders.status in ('pending', 'delivering'); ";
    $user_id = $_SESSION['user_id'];
    try {
        $stmt = $conn->prepare($current_order);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            echo "All orders have been done.";
        } else {
            echo "<!DOCTYPE html>";

            echo "<html lang='en'>";  
            echo "<head>";
            echo "<meta name='viewport' content='width=device-width, initial-scale=1.0' />";
            echo "<meta charset='UTF-8'>";
            echo "<title>U-DBer Ordering System</title>";
            echo "<link rel='stylesheet' href='/css/search.css'>";
            echo "<link rel='preconnect' href='https://fonts.googleapis.com'>";
            echo "<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>";
            echo "<link href='https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Dancing+Script&family=Rubik+Doodle+Shadow&family=Rubik+Scribble&display=swap' rel='stylesheet'>";
            echo "</head>";
            
            echo "<body>";
            echo "<div class='restaurant-container'>";
                echo "<div class='title'>Current Orders</div>";
                if ($result) {
                    echo "<form action='update_current_order.php' method='post'>"; // 開始表單
                    echo "<table class='restaurant-filter-table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th></th>"; // 空欄位用於勾選框
                    echo "<th>Restaurant Name</th>";
                    echo "<th>Order</th>";
                    echo "<td>Courier</td>";
                    echo "<td>Courier Phone</td>";
                    echo "<td>Status</td>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($result as $row) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='selected_order' value=" . htmlspecialchars($row['order_id'], ENT_QUOTES, 'UTF-8') . "></td>";
                        echo "<td>" . htmlspecialchars($row['restaurant_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['user_order'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone_number'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "<input type='submit' value='I get it'>"; // 提交按鈕
                    echo "</form>"; // 結束表單
                } else {
                    echo "<p>No order found.</p>";
                }
            echo "</div>";
            echo "</body>";
            echo "</html>";
        }
    } catch(PDOException $e) {
        die($e->getMessage());
    }
?>