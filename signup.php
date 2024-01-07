<?php
require_once('connection.php');
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';

    try {
        // Check if the username is already taken
        $checkUser = "SELECT * FROM dber WHERE username = :username";
        $checkUsernameStmt = $conn->prepare($checkUser);
        $checkUsernameStmt->bindParam(':username', $username);
        $checkUsernameStmt->execute();

        $existingUser = $checkUsernameStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo "Username is already existed. Please choose a different username.";
        } else {
            // Insert user information into the "user" table
            $insertUser = "INSERT INTO dber (username, password, phone_number) VALUES (:username, :password, :phone_number)";
            $insertuserStmt = $conn->prepare($insertUser);
            $insertuserStmt->bindParam(':username', $username);
            $insertuserStmt->bindParam(':password', $password);
            $insertuserStmt->bindParam(':phone_number', $phone_number);
            $insertuserStmt->execute();

            echo "<!DOCTYPE html>";
            echo "<html>";
            echo "<head>";
            echo "<meta charset='utf-8' />";
            echo "<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
            echo "<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />";
            echo "<meta name='keywords' content='' />";
            echo "<meta name='description' content='' />";
            echo "<meta name='author' content='' />";
            echo "<title>Sign Up</title>";
            echo "<link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css' />";
            echo "<link rel='stylesheet' type='text/css' href='css/bootstrap.css' />";
            echo "<link rel='stylesheet' type='text/css' href='css/font-awesome.min.css' />";
            echo "<link href='css/style.css' rel='stylesheet' />";
            echo "<link href='css/responsive.css' rel='stylesheet' />";
            echo "<link rel='preconnect' href='https://fonts.gstatic.com'>";
            echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'>";
            echo "<link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap' rel='stylesheet'>";
            echo "</head>";

            echo "<body>";
            echo "<div class='hero_area'>";
            echo "<header class='header_section'>";
            echo "<div class='header_top'>";
            echo "<div class='container-fluid'>";
            echo "<div class='contact_nav'>";
            echo "<a href=''>";
            echo "<i class='fa fa-phone' aria-hidden='true'></i>";
            echo "<span>Call : +886 1234567890</span>";
            echo "</a>";
            echo "<a href=''>";
            echo "<i class='fa fa-envelope' aria-hidden='true'></i>";
            echo "<span>Email : udber@gmail.com</span>";
            echo "</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<div class='header_bottom'>";
            echo "<div class='container-fluid'>";
            echo "<nav class='navbar navbar-expand-lg custom_nav-container '>";
            echo "<a class='navbar-brand' href='index.html'>";
            echo "<span>U-Dber</span>";
            echo "</a>";
            echo "<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>";
            echo "<span class=''> </span>";
            echo "</button>";
            echo "<div class='collapse navbar-collapse' id='navbarSupportedContent'>";
            echo "<ul class='navbar-nav '>";
            echo "<li class='nav-item active'>";
            echo "<a class='nav-link' href='signup.html'>Sign Up<span class='sr-only'>(current)</span></a>";
            echo "</li>";
            echo "<li class='nav-item'>";
            echo "<a class='nav-link' href='login.html'>Log In</a>";
            echo "</li>";
            echo "</ul>";
            echo "</div>";
            echo "</nav>";
            echo "</div>";
            echo "</div>";
            echo "</header>";
            echo "<section class='service_section layout_padding'>";
            echo "<div class='container '>";
            echo "<div class='background'>";
            echo "<div class='shape'></div>";
            echo "<div class='shape'></div>";
            echo "</div>";
            echo "<div class='signupform'>";
            echo "<h4>User registration successfully.<br>You can now log in.</h4>";
            echo "<div class='social'>";
            echo "<a href = 'index.html'><div class='go'>Back to MENU</div></a>";
            echo "<a href = 'login.html'><div class='fb'> Go to LOGIN</div><a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</section>";
            echo "</div>";
            echo "</body>";
            echo "</html>";
        }
    } catch (PDOException $e) {
        // Handle any errors gracefully
        echo "<p>Error connecting to the database: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    }
}
?>