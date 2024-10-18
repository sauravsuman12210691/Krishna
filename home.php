<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krishna</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    
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
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <!-- Show logout button when logged in -->
                <form class="d-flex" action="logout.php" method="POST">
                    
                    <button class="btn btn-outline-success" type="submit">Logout</button>
                </form>
            <?php else: ?>
                <!-- Show login button when not logged in -->
                <li><a href="Login.php">Login</a></li>
            <?php endif; ?>
                
            </div>
        </div>
    </nav>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>