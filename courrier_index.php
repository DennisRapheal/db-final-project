<?php
    require_once('connection.php');
    // Specify SQL command
    $get_order = "SELECT * 
                FROM order 
                    JOIN restaurant
                    ON restaurant.restaurant_id = order.restaurant_id
                    JOIN customer
                    ON customer.user_id = order.user_id;
                WHERE order.status = 'pending'; ";

    try {
        $stmt = $conn->prepare($get_order);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            echo "No order submitted yet.";
        } else {
            echo "<form id='orderForm'>";
            echo "<table>";
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td><input type='checkbox' class='orderCheckbox' name='selectedOrders[]' value='" . htmlspecialchars($row['order_id'], ENT_QUOTES, 'UTF-8') . "'></td>";
                echo "<td>" . htmlspecialchars($row['restaurant_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['restaurant_address'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['order_address'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['phone_number'], ENT_QUOTES, 'UTF-8') . "</td>";
                //echo "<td>" . htmlspecialchars($row['delivery_fee'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            echo "<button type='button' id='confirmButton' style='display:none;'>Confirm</button>";
            echo "</form>";
        }
    } catch(PDOException $e) {
        die($e->getMessage());
    }
?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                // Show a confirmation message and redirect to index.html
                alert("Confirmed the order with ID: " + selectedOrderId);
                // Use the selectedOrderId in your update logic
                window.location.href = "index.html";
            } else {
                // Show an error message
                alert("You must choose exactly one order at a time.");
            }
        });
    });
</script>

<?php
require_once('connection.php');

// Assuming you have a variable $orderId with the order ID you want to update
$orderId = $selectedOrderId; // Replace with your actual order ID

// Assuming you have a variable $newStatus with the new status value you want to set
$newStatus = 'done'; // Replace with your actual new status value

try {
    // Prepare the SQL query
    $updateQuery = "UPDATE order SET status = :newStatus WHERE order_id = :orderId";
    $stmt = $conn->prepare($updateQuery);

    // Bind parameters
    $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Check if the update was successful
    $rowCount = $stmt->rowCount(); // Number of affected rows

    if ($rowCount > 0) {
        echo "Update successful!";
    } else {
        echo "No rows were updated. The specified order ID may not exist or the status is already set to the provided value.";
    }
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>