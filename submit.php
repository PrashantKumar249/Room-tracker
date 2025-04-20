<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Session timeout (1 hour)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 3600) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

include 'db.php';

$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_STRING);
    $rent = filter_input(INPUT_POST, 'rent', FILTER_VALIDATE_INT);
    $electricity = filter_input(INPUT_POST, 'electricity', FILTER_VALIDATE_INT);
    $groceries = filter_input(INPUT_POST, 'groceries', FILTER_VALIDATE_INT);
    $user = $_SESSION['user'];

    // Validate inputs
    if (in_array($month, $months) && $rent !== false && $electricity !== false && $groceries !== false) {
        try {
            $stmt = $conn->prepare("INSERT INTO expenses (member_name, month, rent, electricity, groceries) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiii", $user, $month, $rent, $electricity, $groceries);
            $stmt->execute();
            $success_message = "Expense added for $user in $month";
        } catch (Exception $e) {
            $error_message = "Error adding expense: " . $e->getMessage();
        }
    } else {
        $error_message = "Invalid input data!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense - RentTracker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif; /* Updated to Poppins */
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0d0d2b 0%, #1a1a4d 100%);
            overflow: hidden;
        }

        .container {
            position: relative;
            width: 450px;
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
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            color: #fff;
            font-size: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 25px;
            color: #fff; /* Text color for input/select */
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        select:focus, input[type="number"]:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);
        }

        /* Style for dropdown options */
        select {
            background: rgba(20, 20, 40, 0.9); /* Darker background for dropdown */
            color: #00ffff; /* Cyan text for better contrast */
        }

        select option {
            background: #1a1a4d; /* Dark background for options */
            color: #fff; /* White text for options */
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 300;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #00ffff, #00b7ff);
            border: none;
            border-radius: 25px;
            color: #0d0d2b;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.7);
        }

        .message {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            animation: fadeIn 0.5s ease-in-out;
        sedation

        .success {
            color: #00ff99;
            text-shadow: 0 0 5px rgba(0, 255, 153, 0.5);
        }

        .error {
            color: #ff3366;
            text-shadow: 0 0 5px rgba(255, 51, 102, 0.5);
            animation: shake 0.5s;
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
    <div class="container">
        <h2>Add Monthly Expense</h2>
        <form method="POST">
            <div>
                <label>Select Month:</label>
                <select name="month" required>
                    <?php foreach ($months as $m): ?>
                        <option value="<?= htmlspecialchars($m) ?>"><?= htmlspecialchars($m) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <input type="number" name="rent" placeholder="Rent (₹)" min="0" required>
            </div>
            <div>
                <input type="number" name="electricity" placeholder="Electricity (₹)" min="0" required>
            </div>
            <div>
                <input type="number" name="groceries" placeholder="Groceries (₹)" min="0" required>
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
        <?php if (!empty($success_message)): ?>
            <p class="message success"><?= htmlspecialchars($success_message) ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p class="message error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
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