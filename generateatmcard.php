<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "atm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: signin.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$account_number = $_GET['account_number'];

// Check if the account already has a card
$card_check_sql = "SELECT * FROM Card WHERE account_number = $account_number";
$card_check_result = $conn->query($card_check_sql);
$card_exists = $card_check_result->num_rows > 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$card_exists) {
    // Generate a unique card number
    do {
        $card_number = str_pad(rand(0, pow(10, 16)-1), 16, '0', STR_PAD_LEFT);
        $check_sql = "SELECT * FROM Card WHERE card_number = '$card_number'";
        $check_result = $conn->query($check_sql);
    } while ($check_result->num_rows > 0);

    $pin = $_POST['pin'];

    $sql = "INSERT INTO Card (card_number, account_number, pin) VALUES ('$card_number', $account_number, $pin)";

    if ($conn->query($sql) === TRUE) {
        $message = "ATM Card created successfully. Your Card Number is: " . $card_number;
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate ATM Card</title>
    <style>
        .share-tech-mono-regular {
            font-family: "Share Tech Mono", monospace;
            font-weight: 400;
            font-style: normal;
        }

        body {
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        header {
            background: #203a43;
            padding: 20px;
            text-align: left;
            font-size: 1.5em;
        }

        footer {
            background: #203a43;
            padding: 20px;
            text-align: center;
            font-size: 1em;
            margin-top: auto;
        }

        .form-container {
            margin-left: 40%;
            margin-top: 8%;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 300px;
            animation: fadeIn 1s;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input[type="password"],
        input[type="submit"] {
            padding: 10px;
            border: none;
            border-radius: 5px;
        }

        input[type="submit"] {
            background: #17a2b8;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #138496;
        }

        .error-message {
            color: #ff4d4d;
            margin-top: 10px;
        }

        a {
            color: #17a2b8;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }

        img {
            width: 50px;
            height: 50px;
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
</head>
<body>
    <header class="share-tech-mono-regular">
        <div class="material-symbols-outlined">
            <img src="logo.png" alt="logo">
        </div>
        ATM Management System
    </header>

    <div class="form-container">
        <h2 class="share-tech-mono-regular">Generate ATM Card</h2>
        <a href="userlog.php" class="share-tech-mono-regular">Back to Dashboard</a>
        <?php if ($card_exists): ?>
            <p class="share-tech-mono-regular">This account already has an ATM card.</p>
        <?php else: ?>
            <?php if (!empty($message)) { echo "<p class='share-tech-mono-regular'>$message</p>"; } ?>
            <form method="post" action="">
                <label for="pin" class="share-tech-mono-regular">PIN:</label>
                <input type="password" id="pin" name="pin" required><br><br>
                <input type="submit" value="Generate ATM Card" class="share-tech-mono-regular">
            </form>
        <?php endif; ?>
    </div>

    <footer class="share-tech-mono-regular">
        &copy; <?php echo date("Y"); ?> ATM Management System. All rights reserved.
    </footer>
</body>
</html>

<?php
$conn->close();
?>