<?php
// db_connection.php

$servername = "localhost"; // Your database server (often localhost)
$username = "genti_app_user"; // !!! REPLACE WITH YOUR DATABASE USERNAME !!!
$password = "genti_app_user"; // njejt me username se jom shum kreativ
$dbname = "genti_production_db";     // !!! REPLACE WITH YOUR DATABASE NAME (e.g., genti_production_db) !!!

// Create connection using MySQLi (Procedural or Object-Oriented)
// Using Object-Oriented for better practice
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // This will be caught by our custom error handler if enabled, but good to have a fallback
    die("Lidhja me bazën e të dhënave dështoi: " . $conn->connect_error);
}

// Function to sanitize input (basic, for demonstration, prepared statements are better for DB)
// This is for general input cleaning, not a primary SQL Injection defense.
function sanitize_input($data) {
    // Remove whitespace from the beginning and end of string
    $data = trim($data);
    // Remove backslashes
    $data = stripslashes($data);
    // Convert special characters to HTML entities to prevent XSS (Cross-Site Scripting)
    $data = htmlspecialchars($data);
    return $data;
}

// Example function to insert data (using prepared statements as required)
function insertContactMessage($name, $email, $phone, $message) {
    global $conn; // Access the global connection object

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");

    if ($stmt === false) {
        // Handle error in preparing statement
        // trigger_error is used here for demonstration, custom error handler will catch this
        trigger_error("Nuk mund të përgatitej deklarata e bazës së të dhënave: " . $conn->error, E_USER_ERROR);
        return false;
    }

    // Bind parameters
    // 'ssss' indicates four string parameters
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    // Execute the statement
    $success = $stmt->execute();

    if (!$success) {
        // Handle error in execution
        trigger_error("Ekzekutimi i deklaratës së bazës së të dhënave dështoi: " . $stmt->error, E_USER_ERROR);
    }

    // Close the statement
    $stmt->close();

    return $success;
}

// Function to fetch data from a table (example for blogs or gallery)
function fetchData($tableName) {
    global $conn;
    $data = [];
    $sql = "SELECT * FROM " . $tableName; // For this simple example, not using prepared for table name.
                                         // For dynamic table names based on user input, you would need
                                         // a whitelist or more advanced validation.

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    } else {
        trigger_error("Nuk mund të merrte të dhëna nga tabela '$tableName': " . $conn->error, E_USER_ERROR);
    }
    return $data;
}

?>