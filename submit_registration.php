<?php
// Database connection settings
include "connection.php";

// Create table if it doesn't exist
$createTableQuery = "
    CREATE TABLE IF NOT EXISTS users (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstName VARCHAR(255) NOT NULL,
        lastName VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

// Execute the query to create the table if it doesn't exist
if ($con->query($createTableQuery) === TRUE) {
    // Table creation success, no need to show this message to users.
} else {
    die("Error creating table: " . $con->error);
}

// Initialize variables to hold form data and error messages
$firstName = $lastName = $email = $password = "";
$firstNameErr = $lastNameErr = $emailErr = $passwordErr = "";
$successMsg = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty(trim($_POST["firstName"]))) {
        $firstNameErr = "First name is required.";
    } else {
        $firstName = htmlspecialchars(trim($_POST["firstName"]));
    }

    // Validate last name
    if (empty(trim($_POST["lastName"]))) {
        $lastNameErr = "Last name is required.";
    } else {
        $lastName = htmlspecialchars(trim($_POST["lastName"]));
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $emailErr = "Email is required.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format.";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $passwordErr = "Password is required.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $passwordErr = "Password must be at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Proceed if there are no errors
    if (empty($firstNameErr) && empty($lastNameErr) && empty($emailErr) && empty($passwordErr)) {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $stmt = $con->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to login page with success message
            header("Location: login.php?registration=success");
            exit; // Make sure the script stops after redirect
        } else {
            if ($con->errno == 1062) { // Duplicate entry error for unique fields like email
                $emailErr = "An account with this email already exists.";
            } else {
                echo "Error: " . $stmt->error; // Display any other error
            }
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$con->close();
?>