<?php
    session_start();
    require_once('connection.php');
    // Specify SQL command
    $get_order = "SELECT * FROM orders 
                INNER JOIN restaurant ON restaurant.restaurant_id = orders.restaurant_id 
                INNER JOIN dber ON dber.user_id = orders.user_id 
                WHERE orders.status = 'pending'; ";
    $courier_id = $_SESSION['user_id'];
    try {
        $stmt = $conn->prepare($get_order);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            echo "No order submitted yet.";
        } else {
            echo "<link rel='stylesheet' type='text/css' href= 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css' />";
            echo "<link rel='stylesheet' type='text/css' href= 'css/bootstrap.css' />";
            echo "<link rel='stylesheet' type='text/css' href= 'css/font-awesome.min.css' />";
            echo "<link href='css/style.css' rel='stylesheet' />";
            echo "<link href='css/responsive.css' rel='stylesheet' />";
            echo "<form id='orderForm' action='update_order.php' method='post'>";;
            echo "<table>";
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td><input type='checkbox' class='orderCheckbox' name='selected_order' value='" . htmlspecialchars($row['order_id'], ENT_QUOTES, 'UTF-8') . "'></td>";
                echo "<td>" . htmlspecialchars($row['restaurant_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['restaurant_address'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['order_address'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['phone_number'], ENT_QUOTES, 'UTF-8') . "</td>";
                //echo "<td>" . htmlspecialchars($row['delivery_fee'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<input type='submit' value='Submit'>"; // 提交按鈕
            echo "</form>";
        }
    } catch(PDOException $e) {
        die($e->getMessage());
    }
?>

<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        var selectedOrderId; // Variable to store the selected order ID

        $('.orderCheckbox').change(function () {
            var checkedCheckboxes = $('.orderCheckbox:checked');

            if (checkedCheckboxes.length === 1) {
                selectedOrderId = checkedCheckboxes.val(); // Store the selected order ID
                $('#confirmButton').show();
            } else {
                selectedOrderId = null; // Reset selected order ID
                $('#confirmButton').hide();
            }
        });

        $('#confirmButton').click(function () {
            if (selectedOrderId) {
                $.ajax({
                    url: 'update_order.php', // Your PHP script for updating the order
                    method: 'POST',
                    data: { order_id: selectedOrderId }, // Send the selected order ID
                    success: function (response) {
                        alert(response); // Show the response from the PHP script
                        window.location.href = "delivery_record.php";
                    },
                    error: function () {
                        alert('Error updating the order.');
                    }
                });
            } else {
                alert("You must choose exactly one order at a time.");
            }
        });
    });
</script>

<?php
session_start();
require_once('connection.php');
$courier_id = $_SESSION['user_id'];
// Assuming you have a variable $orderId with the order ID you want to update
$order_id = $selectedOrderId; // Replace with your actual order ID

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
        echo "Update successful!";
    } else {
        echo "The specified order ID may not exist or the status is already set to the provided value.";
    }

    $updateQuery2 = "UPDATE orders SET courier_id = :courier_id WHERE orders.order_id = :order_id; ";
    $stmt2 = $conn->prepare($updateQuery2);

    // Bind parameters
    $stmt2->bindParam(':courier_id', $courier_id, PDO::PARAM_INT);
    $stmt2->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt2->execute();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?> -->