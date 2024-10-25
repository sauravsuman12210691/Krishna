<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to the login page
    header("Location: Login.php");
    exit; // Ensure no further code is executed after redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krishna - Get Your Answer Based On Bhagwat Geeta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&display=swap" />
    
    <!-- Pacifico Font -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="./Public/LOGO.jpg" alt=""/>Krishna</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            HOME
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">About</a></li>
                            <li><a class="dropdown-item" href="#">Get Started</a></li>
                            <li><a class="dropdown-item" href="#">Read Books</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" action="logout.php" method="POST">
                    <button class="btn btn-outline-success" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <img src="./Public/image.png" alt="">
        <div class="chat-box">
    <div class="chat-area">
        <span class="user-input" id="userInput"></span>

        <!-- Skeleton loader -->
        <div id="skeleton-loader" class="skeleton-loader">
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-paragraph"></div>
        </div>

        <!-- Generated output will appear here -->
        <span class="generated-output" id="Gout"></span>
    </div>

    <div class="button-group">
        <div class="input-group mb-3">
            <span class="input-group-text">
                <img id="mic-icon" src="./Public/mic.png" alt="" onclick="startVoiceRecognition()">
            </span>
            <input id="query" type="text" class="form-control" aria-label="Text input with checkbox">
            <span onclick="handle(); displayUserInput();" class="material-symbols-outlined input-group-text">
                arrow_circle_up
            </span>
        </div>
    </div>
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
   function displayUserInput() {
    var query = document.getElementById("query").value;
    var userInput = document.getElementById('userInput');

    if (query.trim() !== "") {
        // Display user input
        userInput.innerHTML = `<p>${query}</p>`;
        userInput.style.display = "block"; // Make the user input visible
    }
}

async function handle() {
    try {
        var query = document.getElementById("query").value;

        if (query.trim() === "") {
            return; // If the query is empty, don't proceed
        }

        // Show skeleton loader and hide Gout initially
        document.getElementById('skeleton-loader').style.display = "block";
        document.getElementById('Gout').style.display = "none"; // Hide output initially

        const res = await fetch("http://localhost:3000/story", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ query }) 
        });

        if (!res.ok) {
            throw new Error("Network response was not OK");
        }

        const data = await res.json();

        // Hide skeleton loader
        document.getElementById('skeleton-loader').style.display = "none";

        // Display fetched data in Gout and make it visible
        document.getElementById('Gout').innerHTML = `
        <h1>${data[0].reference}</h1>
        <br>
        <p>${data[0].solution}</p>`;
        document.getElementById('Gout').style.display = "block"; // Make the output visible

    } catch (error) {
        console.error("Error:", error);
        
        // Hide skeleton loader in case of an error
        document.getElementById('skeleton-loader').style.display = "none";

        // Display error message
        document.getElementById('Gout').innerHTML = `<p>Error fetching data. Please try again later.</p>`;
        document.getElementById('Gout').style.display = "block"; // Show the error message
    }
}



    

    // Voice recognition logic
    function startVoiceRecognition() {
        if ('webkitSpeechRecognition' in window) {
            const recognition = new webkitSpeechRecognition();
            recognition.lang = 'en-US';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            recognition.onresult = (event) => {
                const voiceQuery = event.results[0][0].transcript;
                document.getElementById('query').value = voiceQuery;
                displayUserInput();
                handle();  // Automatically send the query after recognition
            };

            recognition.onerror = (event) => {
                console.error("Voice recognition error", event);
            };

            recognition.start();
        } else {
            alert('Speech recognition not supported in this browser.');
        }
    }
    </script>
</body> 
</html>
