<?php
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $dsn = "pgsql:host=db-finalproject.cm8ih0pvjx1c.us-east-1.rds.amazonaws.com;dbname=db-finalproject;user=postgres;password=ufpi6vd5eBSEy99uumcX";

    try {
        // Create a new PDO instance
        $pdo = new PDO($dsn);

        // Set error mode
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SELECT statement based on the input
        $stmt = $pdo->prepare("SELECT * FROM dber WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Execute the statement
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password is correct
        if ($user) {
            // Store user data in the session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to the main page or any other desired page after successful login
            header("Location: index.html");
            exit();
        } else {
            // Display an error message for incorrect credentials
            echo "Invalid username or password";
        }

    } catch (PDOException $e) {
        // Handle any errors gracefully
        echo "<p>Error connecting to the database: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    }
}
?>
