<?php
require_once('connection.php');
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $restaurantName = isset($_POST['restaurantName']) ? $_POST['restaurantName'] : '';
    $kind = isset($_POST['kind']) ? $_POST['kind'] : '';
    $region = isset($_POST['region']) ? $_POST['region'] : '';
    $rating = isset($_POST['rating']) ? $_POST['rating'] : '';

    try {
        // Prepare the SELECT statement based on the input
        if($restaurantName != '') {
            $stmt = $conn->prepare("SELECT * FROM restaurant 
                                        WHERE restaurant_name = :restaurantName; ");
            $stmt->bindParam(':restaurantName', $restaurantName);
        }else if($kind != '' && $region != '' && $rating != '') {
            // Assuming kind, region, and rating are provided, adjust if they're optional
            $stmt = $conn->prepare("SELECT * FROM restaurant 
                                        WHERE kind = :kind AND region = :region AND rating >= :rating; ");
            $stmt->bindParam(':kind', $kind);
            $stmt->bindParam(':region', $region);
            $stmt->bindParam(':rating', $rating);
        }else if($kind != '' && $region != '') {
            $stmt = $conn->prepare("SELECT * FROM restaurant 
                                        WHERE kind = :kind AND region = :region; ");
            $stmt->bindParam(':kind', $kind);
            $stmt->bindParam(':region', $region);
        }else if($kind != '' && $rating != '') {
            $stmt = $conn->prepare("SELECT * FROM restaurant 
                                        WHERE kind = :kind AND rating >= :rating; ");
            $stmt->bindParam(':kind', $kind);
            $stmt->bindParam(':rating', $rating);
        }else if($region != '' && $rating != '') {
            $stmt = $conn->prepare("SELECT * FROM restaurant 
                                        WHERE region = :region AND rating >= :rating; ");
            $stmt->bindParam(':region', $region);
            $stmt->bindParam(':rating', $rating);
        }else if($kind != '') {
            $stmt = $conn->prepare("SELECT * FROM restaurant 
                                        WHERE kind = :kind; ");
            $stmt->bindParam(':kind', $kind);
        }else if($region != '') {
            $stmt = $conn->prepare("SELECT * FROM restaurant 
                                        WHERE region = :region; ");
            $stmt->bindParam(':region', $region);
        }else if($rating != '') {
            $stmt = $conn->prepare("SELECT * FROM restaurant 
                                        WHERE rating >= :rating; ");
            $stmt->bindParam(':rating', $rating);
        }else{
            $stmt = $conn->prepare("SELECT * FROM restaurant; ");
        }
        // Execute the statement
        $stmt->execute();

        // Fetch all rows that match the query
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

       // Start the HTML output
       echo "<!DOCTYPE html>";

       echo "<html lang='en'>";  
       echo "<head>";
       echo "<meta name='viewport' content='width=device-width, initial-scale=1.0' />";
       echo "<meta charset='UTF-8'>";
       echo "<title>U-DBer Ordering System</title>";
       echo "<link rel='stylesheet' href='css/search_new.css'>";
       echo "<link rel='preconnect' href='https://fonts.googleapis.com'>";
       echo "<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>";
       echo "<link href='https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Dancing+Script&family=Rubik+Doodle+Shadow&family=Rubik+Scribble&display=swap' rel='stylesheet'>";
       echo "</head>";
       
       echo "<body>";

       echo "<header class='header_section'>";
       echo "<div class='header_top'>";
           echo "<div class='container-fluid'>";
               echo "<div class='contact_nav'>";
                   echo "<a href=''>";
                       echo "<i class='fa fa-phone' aria-hidden='true'></i>";
                       echo "<span>";
                       echo "Call : +886 1234567890";
                       echo "</span>";
                   echo "</a>";
                   echo "<a href=''>";
                       echo "<i class='fa fa-envelope' aria-hidden='true'></i>";
                       echo "<span>";
                       echo "Email : udber@gmail.com";
                       echo "</span>";
                   echo "</a>";
               echo "</div>";
           echo "</div>";
       echo "</div>";
       echo "<div class='header_bottom'>";
           echo "<div class='container-fluid'>";
               echo "<nav class='navbar navbar-expand-lg custom_nav-container '>";
                   echo "<a class='navbar-brand' href='index.html'>";
                       echo "<span>";
                       echo "U-Der";
                       echo "</span>";
                   echo "</a>";
               echo "</nav>";
           echo "</div>";
       echo "</div>";
   echo "</header>";

       echo "<div class='restaurant-container'>";
           echo "<div class='title'>Restaurant Filter</div>";
           if ($result) {
               echo "<form action='submit_order.php' method='post'>"; // 開始表單
               echo "<table class='restaurant-filter-table'>";
               echo "<thead>";
               echo "<tr>";
               echo "<th></th>"; // 空欄位用於勾選框
               echo "<th>Restaurant Name</th>";
               echo "<th>Open Hour</th>";
               echo "<th>Close Hour</th>";
               echo "<th>Phone Number</th>";
               echo "<th>Kind</th>";
               echo "<th>Region</th>";
               echo "<th>Rating</th>";
               echo "</tr>";
               echo "</thead>";
               echo "<tbody>";
               foreach ($result as $row) {
                   echo "<tr>";
                   echo "<td><input type='checkbox' name='selected_restaurant' value='" . htmlspecialchars($row['restaurant_id'], ENT_QUOTES, 'UTF-8') . "'></td>";
                   echo "<td>" . htmlspecialchars($row['restaurant_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                   echo "<td>" . htmlspecialchars($row['open_hour'], ENT_QUOTES, 'UTF-8') . "</td>";
                   echo "<td>" . htmlspecialchars($row['close_hour'], ENT_QUOTES, 'UTF-8') . "</td>";
                   echo "<td>" . htmlspecialchars($row['phone_number'], ENT_QUOTES, 'UTF-8') . "</td>";
                   echo "<td>" . htmlspecialchars($row['kind'], ENT_QUOTES, 'UTF-8') . "</td>";
                   echo "<td>" . htmlspecialchars($row['region'], ENT_QUOTES, 'UTF-8') . "</td>";
                   echo "<td>" . htmlspecialchars($row['rating'], ENT_QUOTES, 'UTF-8') . "</td>";
                   echo "</tr>";
               }
               echo "</tbody>";
               echo "</table>";
               echo "<label for='order'>Order : </label>";
               echo "<input type='text' id='user_order' name='user_order'>";

               echo "<label for='order_address'>Order Address : </label>";
               echo "<input type='text' id='order_address' name='order_address'>";

               echo "<label for='delivery_fee'>Delivery_fee : </label>";
               echo "<input type='text' id='delivery_fee' name='delivery_fee'>";
               
               echo "<input type='submit' value='Submit'>"; // 提交按鈕
               echo "</form>"; // 結束表單
           } else {
               echo "<p>No restaurants found.</p>";
           }
       echo "</div>";
       echo "</body>";
       echo "</html>";

    } catch (PDOException $e) {
        // Handle any errors gracefully
        echo "<p>Error connecting to the database: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    }
}
?>