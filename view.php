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

// Fetch expenses
$sql = "SELECT * FROM expenses ORDER BY month, member_name";
$result = $conn->query($sql);

// Get total grocery per member
$total_sql = "SELECT member_name, SUM(groceries) as total_groceries FROM expenses GROUP BY member_name";
$total_result = $conn->query($total_sql);
$groceries_summary = [];
while ($row = $total_result->fetch_assoc()) {
    $groceries_summary[$row['member_name']] = $row['total_groceries'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khatabook - RentTracker</title>
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
            align-items: center;
            background: linear-gradient(135deg, #0d0d2b 0%, #1a1a4d 100%);
            overflow-y: auto;
            padding: 40px;
            color: #fff;
        }

        .container {
            width: 90%;
            max-width: 1000px;
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

        h2, h3 {
            color: #00ffff;
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.7);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background: linear-gradient(45deg, #00ffff, #00b7ff);
            color: #0d0d2b;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }

        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.03);
        }

        tr:hover {
            background: rgba(0, 255, 255, 0.1);
            transition: background 0.3s ease;
        }

        a {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
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
            top: 0;
            left: 0;
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
        <h2>Monthly Khatabook</h2>
        <table>
            <tr>
                <th>Month</th>
                <th>Member</th>
                <th>Rent (₹)</th>
                <th>Electricity (₹)</th>
                <th>Groceries (₹)</th>
                <th>Date Submitted</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['month']) ?></td>
                <td><?= htmlspecialchars($row['member_name']) ?></td>
                <td><?= number_format($row['rent'], 2) ?></td>
                <td><?= number_format($row['electricity'], 2) ?></td>
                <td><?= number_format($row['groceries'], 2) ?></td>
                <td><?= htmlspecialchars($row['submitted_at']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>Total Grocery Contribution</h3>
        <table>
            <tr>
                <th>Member</th>
                <th>Total Groceries (₹)</th>
            </tr>
            <?php foreach ($groceries_summary as $member => $total): ?>
            <tr>
                <td><?= htmlspecialchars($member) ?></td>
                <td><?= number_format($total, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <a href="main.php">⬅️ Back to Dashboard</a>
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