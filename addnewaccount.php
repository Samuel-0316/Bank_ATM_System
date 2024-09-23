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

// Fetch banks data
$banks_result = $conn->query("SELECT bank_id, bank_name FROM Bank");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create account form submission
    $account_type = $_POST['account_type'];
    $balance = $_POST['balance'];
    $bank_id = $_POST['bank_id'];

    // Generate unique account_number
    $result = $conn->query("SELECT MAX(account_number) AS max_acc_num FROM Account");
    $row = $result->fetch_assoc();
    $account_number = $row['max_acc_num'] + 1;

    $sql = "INSERT INTO Account (account_number, account_type, balance, bank_id, customer_id) 
            VALUES ($account_number, '$account_type', $balance, $bank_id, $customer_id)";

    if ($conn->query($sql) === TRUE) {
        $message = "Account created successfully. Your Account Number is: " . $account_number;
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
    <title>Add New Account</title>
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

        input[type="text"],
        input[type="password"],
        input[type="number"],
        input[type="submit"],
        select {
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
        <h2 class="share-tech-mono-regular">Create New Account</h2>
        <a href="userlog.php" class="share-tech-mono-regular">Back to Dashboard</a>
        <?php if (!empty($message)) { echo "<p class='share-tech-mono-regular'>$message</p>"; } ?>
        <form method="post" action="">
            <label for="account_type" class="share-tech-mono-regular">Account Type:</label>
            <input type="text" id="account_type" name="account_type" required><br><br>
            <label for="balance" class="share-tech-mono-regular">Balance:</label>
            <input type="number" step="0.01" id="balance" name="balance" required><br><br>
            <label for="bank_id" class="share-tech-mono-regular">Bank:</label>
            <select id="bank_id" name="bank_id" required>
                <option value="" disabled selected>Select Bank</option>
                <?php while ($bank = $banks_result->fetch_assoc()): ?>
                    <option value="<?php echo $bank['bank_id']; ?>"><?php echo $bank['bank_name']; ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <input type="submit" value="Create Account" class="share-tech-mono-regular">
        </form>
    </div>

    <footer class="share-tech-mono-regular">
        &copy; <?php echo date("Y"); ?> ATM Management System. All rights reserved.
    </footer>
</body>
</html>

<?php
$conn->close();
?>