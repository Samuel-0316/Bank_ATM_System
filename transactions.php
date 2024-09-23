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

// Fetch transactions for the account's cards
$transactions_sql = "SELECT t.transaction_id, t.status, t.card_number, t.transaction_datetime, t.atm_id, t.d_amount
                     FROM Transaction t
                     JOIN Card c ON t.card_number = c.card_number
                     WHERE c.account_number = $account_number";
$transactions_result = $conn->query($transactions_sql);
$transactions = $transactions_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
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

        .content-container {
            margin: auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 600px;
            animation: fadeIn 1s;
        }

        a {
            color: #17a2b8;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: rgba(0, 0, 0, 0.2);
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: left;
        }

        .transaction-details {
            margin: 10px 0;
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
        Transactions
    </header>

    <div class="content-container share-tech-mono-regular">
        <h2>Transactions for Account Number: <?php echo $account_number; ?></h2>
        <a href="userlog.php">Back to Dashboard</a>
        <?php if (!empty($transactions)): ?>
            <ul>
                <?php foreach ($transactions as $transaction): ?>
                    <li>
                        <div class="transaction-details">
                            <strong>Transaction ID:</strong> <?php echo $transaction['transaction_id']; ?><br>
                            <strong>Status:</strong> <?php echo $transaction['status']; ?><br>
                            <strong>Card Number:</strong> <?php echo $transaction['card_number']; ?><br>
                            <strong>Date and Time:</strong> <?php echo $transaction['transaction_datetime']; ?><br>
                            <strong>ATM ID:</strong> <?php echo $transaction['atm_id']; ?><br>
                            <strong>Amount:</strong> <?php echo $transaction['d_amount']; ?><br>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No transactions found.</p>
        <?php endif; ?>
    </div>

    <footer class="share-tech-mono-regular">
        &copy; <?php echo date("Y"); ?> Bank Website. All rights reserved.
    </footer>
</body>
</html>

<?php
$conn->close();
?>