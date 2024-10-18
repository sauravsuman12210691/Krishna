<?php
// Database connection settings
include "connection.php";

// Initialize variables to hold form data and error messages
$firstName = $lastName = $email = $password = "";
$firstNameErr = $lastNameErr = $emailErr = $passwordErr = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty(trim($_POST["firstName"]))) {
        $firstNameErr = "First name is required.";
    } else {
        $firstName = trim($_POST["firstName"]);
    }

    // Validate last name
    if (empty(trim($_POST["lastName"]))) {
        $lastNameErr = "Last name is required.";
    } else {
        $lastName = trim($_POST["lastName"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $emailErr = "Email is required.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $passwordErr = "Password is required.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Proceed if there are no errors
    if (empty($firstNameErr) && empty($lastNameErr) && empty($emailErr) && empty($passwordErr)) {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $stmt = $con->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword); // 'ssss' means four string parameters

        // Execute the statement
        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            echo "Error: " . $stmt->error; // Display error if the insert fails
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$con->close();

// Display error messages if any
if (!empty($firstNameErr)) {
    echo $firstNameErr;
}
if (!empty($lastNameErr)) {
    echo $lastNameErr;
}
if (!empty($emailErr)) {
    echo $emailErr;
}
if (!empty($passwordErr)) {
    echo $passwordErr;
}
?>
