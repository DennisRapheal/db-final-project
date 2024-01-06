<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
    $dsn = "pgsql:host=db-finalproject.cm8ih0pvjx1c.us-east-1.rds.amazonaws.com;dbname=db-finalproject;user=postgres;password=ufpi6vd5eBSEy99uumcX";

    try {
        // Create a new PDO instance
        $pdo = new PDO($dsn);

        // Set error mode
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the username is already taken
        $checkUsernameStmt = $pdo->prepare("SELECT * FROM customer WHERE username = :username");
        $checkUsernameStmt->bindParam(':username', $username);
        $checkUsernameStmt->execute();

        $existingUser = $checkUsernameStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo "Username is already existed. Please choose a different username.";
        } else {
            // Insert user information into the "customer" table
            $insertCustomerStmt = $pdo->prepare("INSERT INTO customer (username, password, phone_number) VALUES (:username, :password, :phone_number)");
            $insertCustomerStmt->bindParam(':username', $username);
            $insertCustomerStmt->bindParam(':password', $password);
            $insertCustomerStmt->bindParam(':phone_number', $phone_number);
            $insertCustomerStmt->execute();

            echo "<!DOCTYPE html>";

            echo "<html lang='en'>";
            echo "<head>";
            echo "<meta charset='utf-8'>";
            echo "<title>Sign up</title>";
            echo "</head>";
            echo "User registration successful. You can now log in.";
            echo "<button> <a href='index.html'>Back to Menu</a></button>";
            echo "<button> <a href='login.html'>go to log in </a> </button>>";
            echo "</html>";
        }
    } catch (PDOException $e) {
        // Handle any errors gracefully
        echo "<p>Error connecting to the database: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    }
}
?>