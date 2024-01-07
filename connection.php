<?php
	// Initialize connection variables.
	$host = 'db-finalproject.cm8ih0pvjx1c.us-east-1.rds.amazonaws.com';
	$dbname = 'db-finalproject';
	$username = 'postgres';
	$password = 'ufpi6vd5eBSEy99uumcX';
    // Build connection
    try {
        $dsn = "pgsql:host=$host;port=5432;dbname=$dbname;";
        
        // Make a database connection
        $conn = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
?>

