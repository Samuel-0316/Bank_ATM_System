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

// Fetch user details if logged in
$customer_sql = "SELECT * FROM Customer WHERE customer_id = $customer_id";
$customer_result = $conn->query($customer_sql);
$customer = $customer_result->fetch_assoc();

// Fetch accounts for the customer
$accounts_sql = "SELECT * FROM Account WHERE customer_id = $customer_id";
$accounts_result = $conn->query($accounts_sql);
$accounts = $accounts_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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

        .account-details {
            margin: 10px 0;
        }

        button {
            margin: 5px;
            padding: 10px 15px;
            background-color: #17a2b8;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #138f9e;
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
        User Dashboard
    </header>

    <div class="content-container share-tech-mono-regular">
        <h2>Welcome, <?php echo $customer['customer_name']; ?></h2>
        <a href="home.php">Logout</a>
        <h4>Your Accounts</h4>
        <?php if (!empty($accounts)): ?>
            <ul>
                <?php foreach ($accounts as $account): ?>
                    <li>
                        <div class="account-details">
                            <strong>Account Number:</strong> <?php echo $account['account_number']; ?><br>
                            <strong>Account Type:</strong> <?php echo $account['account_type']; ?><br>
                            <strong>Balance:</strong> <?php echo $account['balance']; ?><br>
                            <?php
                            $bank_id = $account['bank_id'];
                            $bank_sql = "SELECT * FROM Bank WHERE bank_id = $bank_id";
                            $bank_result = $conn->query($bank_sql);
                            $bank = $bank_result->fetch_assoc();
                            ?>
                            <strong>Bank:</strong> <?php echo $bank['bank_name']; ?><br>
                            <strong>Bank Address:</strong> <?php echo $bank['bank_address']; ?><br>
                        </div>
                        <a href="generateatmcard.php?account_number=<?php echo $account['account_number']; ?>"><button>Generate ATM Card</button></a>
                        <a href="transactions.php?account_number=<?php echo $account['account_number']; ?>"><button>Transactions</button></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No accounts found.</p>
        <?php endif; ?>

        <a href="addnewaccount.php"><button>Add New Account</button></a>
    </div>

    <footer class="share-tech-mono-regular">
        &copy; <?php echo date("Y"); ?> Bank Website. All rights reserved.
    </footer>
</body>
</html>

<?php
$conn->close();
?>