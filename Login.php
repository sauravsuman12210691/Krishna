<?php
// Start the session
session_start();

// Database connection settings
include "connection.php";

// Initialize variables to hold form data and error messages
$email = $password = "";
$emailErr = $passwordErr = $loginErr = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    if (empty($emailErr) && empty($passwordErr)) {
        // Prepare the SQL query
        $stmt = $con->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email); // 's' means the parameter is a string
        $stmt->execute();
        $stmt->store_result();

        // Check if the user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Set session variables
                $_SESSION['email'] = $email; // Store user's email in session
                $_SESSION['loggedin'] = true; // Indicate that the user is logged in

                // Redirect to index.php
                header("Location: index.php");
                exit; // Ensure no further code is executed after redirection
            } else {
                $loginErr = "Invalid password.";
            }
        } else {
            $loginErr = "No user found with that email.";
        }

        $stmt->close();
    }
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .password-toggle {
            float: right;
            margin-top: -40px;
            margin-right: 10px;
            cursor: pointer;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .no-account {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .no-account a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .no-account a:hover {
            text-decoration: underline;
        }

        /* Media query for responsiveness */
        @media (max-width: 500px) {
            .container {
                width: 90%;
                padding: 20px;
            }
        }

    </style>
</head>
<body>

    <div class="container">
        <h2>Login Form</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">
            <span class="error"><?php echo $emailErr; ?></span>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="<?php echo $password; ?>">
            <span class="password-toggle" onclick="togglePasswordVisibility('password')">👁️</span>
            <span class="error"><?php echo $passwordErr; ?></span>

            <span class="error"><?php echo $loginErr; ?></span>

            <input type="submit" value="Login">
        </form>

        <div class="no-account">
            <p>Don't have an account? <a href="register.html">Register here</a></p>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.type === 'password' ? 'text' : 'password';
            field.type = type;
        }
    </script>

</body>
</html>
