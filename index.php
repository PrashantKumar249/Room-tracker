<?php
session_start();
include 'db.php';

// Prevent session fixation
session_regenerate_id(true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Simple hardcoded login with better security
    $valid_users = [
        "Aayush" => "123",
        "Kunal" => "123",
        "Purushottam" => "123",
        "Prashant" => "123",
        "Gulshan" => "123",
        "Chandan" => "123"
    ];

    // Basic input validation
    if (!empty($username) && array_key_exists($username, $valid_users)) {
        if ($valid_users[$username] === $password) {
            $_SESSION['user'] = $username;
            $_SESSION['login_time'] = time();
            header("Location: main.php");
            exit;
        }
    }
    $error = "Invalid credentials detected!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RentTracker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif; /* Changed to Poppins */
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0d0d2b 0%, #1a1a4d 100%);
            overflow: hidden;
        }

        .login-container {
            position: relative;
            width: 400px;
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
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.7);
            font-weight: 600; /* Slightly bolder for readability */
        }

        .input-group {
            position: relative;
            margin-bottom: 30px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 25px;
            color: #fff;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 300; /* Lighter weight for placeholder */
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #00ffff, #00b7ff);
            border: none;
            border-radius: 25px;
            color: #0d0d2b;
            font-size: 16px;
            font-weight: 600; /* Bolder for readability */
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.7);
        }

        .error {
            color: #ff3366;
            text-align: center;
            margin-top: 20px;
            text-shadow: 0 0 5px rgba(255, 51, 102, 0.5);
            animation: shake 0.5s;
            font-weight: 400; /* Medium weight for readability */
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
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
    <div class="login-container">
        <h2>Member Login</h2>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="username" placeholder="Enter your name" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password (123)" required>
            </div>
            <button type="submit" class="submit-btn">Login</button>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        </form>
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