<?php
session_start();
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $selectedRestaurant = isset($_POST['selected_restaurant']) ? $_POST['selected_restaurant'] : null;
    $user_order = isset($_POST['user_order']) ? $_POST['user_order'] : '';
    $order_address = isset($_POST['order_address']) ? $_POST['order_address'] : '';
    $delivery_fee = isset($_POST['delivery_fee']) ? $_POST['delivery_fee'] : '';
    $user_id = $_SESSION['user_id'];
    $dsn = "pgsql:host=db-finalproject.cm8ih0pvjx1c.us-east-1.rds.amazonaws.com;dbname=db-finalproject;user=postgres;password=ufpi6vd5eBSEy99uumcX";

    // Check if a restaurant was selected
    if ($selectedRestaurant && $user_order != '') {
        try {
            $pdo = new PDO($dsn);
            // Prepare the INSERT statement
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, user_order, restaurant_id, order_address, delivery_fee, status, courier_id) VALUES (:user_id, :user_order, :restaurant_id, :order_address, :delivery_fee, 'pending', NULL)");
            //generate order id 
            // Bind parameters to the statement
            $stmt->bindParam(':restaurant_id', $selectedRestaurant, PDO::PARAM_INT);
            $stmt->bindParam(':user_order', $user_order, PDO::PARAM_STR);
            $stmt->bindParam(':order_address', $order_address, PDO::PARAM_STR);
            $stmt->bindParam(':delivery_fee', $delivery_fee, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            //$stmt->bindParam(':delivery_fee', $delivery_fee, PDO::PARAM_INT);
            // Execute the statement
            $stmt->execute();

            $restaurantname_stmt = $pdo->prepare("SELECT restaurant_name FROM restaurant WHERE restaurant.restaurant_id = :restaurant_id");
            $restaurantname_stmt->bindParam(':restaurant_id', $selectedRestaurant, PDO::PARAM_INT);
            $restaurantname_stmt->execute();
            $restaurantName = $restaurantname_stmt->fetch(PDO::FETCH_ASSOC)['restaurant_name'];

            // Show success message
            echo "<!DOCTYPE html>";
            echo "<html lang='en'>";
            echo "<head>";
            echo "<meta name='viewport' content='width=device-width, initial-scale=1.0' />";
            echo "<meta charset='UTF-8'>";
            echo "<title>U-DBer Ordering System</title>";
            echo "<link rel='stylesheet' href='/css/send.css'>";
            echo "<link rel='preconnect' href='https://fonts.googleapis.com%27%3E/";
            echo "<link rel='preconnect' href='https://fonts.gstatic.com/' crossorigin>";
            echo "<link href='https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Dancing+Script&family=Poppins&display=swap' rel='stylesheet'>";
            echo "<link href='https://fonts.googleapis.com/css2?family=Rubik+Doodle+Shadow&display=swap' rel='stylesheet'>";
            echo "</head>";
            
            echo "<body>";
                echo "<div class='header'>";
                    echo "<div class='logo'> U-DBer </div>";
                    echo "<div class='auth'>";
                        echo "<a href='index.html' class='previous_page'> Back to MENU </a>";
                    echo "</div>";
                echo "</div>";
                echo "<div class='restaurant-container'>";
                    echo "<div class='order_res'> Restaurant :  " . htmlspecialchars($restaurantName) . "</div>";
                    echo "<div class='content'>Orders : " . htmlspecialchars($user_order) . "    <br> <div class='adjust'>has been successfully submitted.</div></div>";
                echo "</div>";
            echo "</body>";
            echo "</html>";
        } catch (PDOException $e) {
            // Handle any errors
            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p>Please select a restaurant to order from.</p>";
    }
} else {
    echo "<p>Form was not submitted properly.</p>";
}
?>