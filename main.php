<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Prevent session hijacking by checking login time
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 3600) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

include 'db.php';

$members = ['Aayush', 'Kunal', 'Purushottam', 'Prashant', 'Gulshan', 'Chandan'];
$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Expense Tracker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Orbitron', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0d0d2b 0%, #1a1a4d 100%);
            overflow: hidden;
            color: #fff;
        }

        .container {
            position: relative;
            width: 600px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(0, 255, 255, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #00ffff;
            text-align: center;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.7);
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            padding: 12px 25px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }

        a:hover {
            background: linear-gradient(45deg, #00ffff, #00b7ff);
            color: #0d0d2b;
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.7);
        }

        /* Floating particles effect */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(0, 255, 255, 0.7);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        @keyframes float {
            0% { transform: translateY(0); opacity: 0.7; }
            50% { opacity: 0.3; }
            100% { transform: translateY(-100vh); opacity: 0; }
        }
    </style>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h2>
        <div class="nav-links">
            <a href="submit.php">➕ Add Expenses</a>
            <a href="view.php">📖 View Khatabook</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>

    <!-- Floating particles -->
    <div class="particles">
        <?php for ($i = 0; $i < 20; $i++) {
            $size = rand(2, 6);
            $left = rand(0, 100);
            $delay = rand(0, 15);
            echo "<div class='particle' style='width: {$size}px; height: {$size}px; left: {$left}%; animation-delay: {$delay}s;'></div>";
        } ?>
    </div>
</body>
</html>