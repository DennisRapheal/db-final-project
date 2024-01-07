<?php
// Check if the review data was received
session_start();
require_once('connection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the review from the POST data
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $insert_review = "INSERT INTO contact_message (username, phone_number ,gmail, message) VALUES (:name, :phoneNumber, :email, :message)";
    if($user_id != ''){
    //insert into database
        try {
            $stmt = $conn->prepare($insert_review);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':phoneNumber', $phoneNumber);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            $stmt->execute();
            //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header("Location: index.html");
            exit();
        } catch(PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } else {
        echo "Error: You haven't login yet.";
    }
} else {
    // If the review data was not received, send an error response
    echo "Error: Review data not received.";
}
?>
