<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM Management System</title>
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
        img{
            width: 50px;
            height: 50px;
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').on('submit', function() {
                $('.form-container').fadeOut(300, function() {
                    $('.form-container').fadeIn(300);
                });
            });
        });
    </script>
</head>
<body>
    <header class="share-tech-mono-regular">
        <div class="material-symbols-outlined">
            <img src="logo.png" alt="logo">
        </div>
        ATM Management System
    </header>
    
    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "atm";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    session_start();

    $backLink = $_SERVER['PHP_SELF']; // Dynamic link to the current file
    $error_message = ""; // Initialize the error message variable

    // Function to display the login form with ATM selection
    function display_login_form($conn, $error_message) {
        echo '<div id="login-form" class="form-container">
                <h2 class="share-tech-mono-regular">ATM Login</h2>
                <form method="POST">
                    <label for="card_number" class="share-tech-mono-regular">Card Number:</label>
                    <input type="text" id="card_number" name="card_number" required><br>
                    <label for="pin" class="share-tech-mono-regular">PIN:</label>
                    <input type="password" id="pin" name="pin" required><br>
                    <label for="atm_id" class="share-tech-mono-regular">Select ATM:</label>
                    <select id="atm_id" name="atm_id" required>
                        <option value="">Select ATM</option>';
        
        // Fetching ATMs from database
        $sql = "SELECT * FROM ATM";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['atm_id'] . "'>" . $row['atm_name'] . "</option>";
            }
        }
        
        echo '</select><br>
                    <input type="submit" name="login" value="Login" class="share-tech-mono-regular">
                    <div class="error-message">' . $error_message . '</div>
                </form>
            </div>';
    }

    // Function to display balance check and withdrawal options
    function display_options() {
        echo '<div id="options" class="form-container">
                <h2>Select an Option</h2>
                <form method="POST">
                    <input type="submit" name="check_balance" value="Check Balance">
                    <input type="submit" name="withdraw" value="Withdraw">
                </form>
            </div>';
    }

    // Function to display balance
    function display_balance($conn, $card_number, $backLink) {
        echo '<div id="balance-display" class="form-container">';
        $sql = "SELECT Account.balance 
                FROM Account 
                JOIN Card ON Account.account_number = Card.account_number 
                WHERE Card.card_number = '$card_number'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<h2>Balance: $" . $row['balance'] . "</h2>";
        } else {
            echo "<h2>No account found.</h2>";
        }
        echo "<br><a href='$backLink'>Back</a>";
        echo '</div>';
    }

    // Function to display withdrawal form
    function display_withdraw_form($conn, $backLink) {
        echo '<div id="withdraw-form" class="form-container">
                <h2>Withdraw Money</h2>
                <form method="POST">
                    <label for="amount">Enter Amount:</label>
                    <input type="number" id="amount" name="amount" required><br>
                    <input type="submit" name="perform_withdrawal" value="Withdraw">
                </form>
              </div>';
    }

    // Function to perform withdrawal
    function perform_withdrawal($conn, $card_number, $amount, $atm_id, $backLink) {
        echo '<div id="transaction-status" class="form-container">';
        $sql = "SELECT Account.balance, Account.account_number 
                FROM Account 
                JOIN Card ON Account.account_number = Card.account_number 
                WHERE Card.card_number = '$card_number'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['balance'] >= $amount) {
                $new_balance = $row['balance'] - $amount;
                $account_number = $row['account_number'];
                $conn->begin_transaction();
                try {
                    $update_sql = "UPDATE Account SET balance = '$new_balance' WHERE account_number = '$account_number'";
                    $conn->query($update_sql);

                    $status = 'Success';
                    $datetime = date('Y-m-d H:i:s');
                    $d_amount = $amount;
                    $insert_sql = "INSERT INTO Transaction (status, card_number, transaction_datetime, atm_id, d_amount) 
                                VALUES ('$status', '$card_number', '$datetime', '$atm_id', '$d_amount')";
                    $conn->query($insert_sql);
                    $transaction_id = $conn->insert_id;

                    $conn->commit();

                    echo "<h2>Transaction Successful</h2>";
                    echo "<p>Transaction ID: $transaction_id<br>";
                    echo "Status: $status<br>";
                    echo "DateTime: $datetime<br>";
                    echo "Withdrawn Amount: $d_amount</p>";
                    
                } catch (Exception $e) {
                    $conn->rollback();
                    echo "<h2>Transaction failed: " . $e->getMessage() . "</h2>";
                }
            } else {
                echo "<h2>Insufficient balance.</h2>";
            }
        } else {
            echo "<h2>No account found.</h2>";
        }
        echo "<br><a href='$backLink'>Back</a>";
        echo '</div>';
    }
    
    // Handle login
    if (isset($_POST['login'])) {
        $card_number = $_POST['card_number'];
        $pin = $_POST['pin'];
        $atm_id = $_POST['atm_id'];
        
        $sql = "SELECT * FROM Card WHERE card_number = '$card_number' AND pin = '$pin'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $_SESSION['card_number'] = $card_number;
            $_SESSION['atm_id'] = $atm_id;
            display_options();
        } else {
            $error_message = "Invalid card number or PIN.";
            display_login_form($conn, $error_message);
        }
    } elseif (isset($_POST['check_balance'])) {
        $card_number = $_SESSION['card_number'];
        display_balance($conn, $card_number, $backLink);
    } elseif (isset($_POST['withdraw'])) {
        display_withdraw_form($conn, $backLink);
    } elseif (isset($_POST['perform_withdrawal'])) {
        $card_number = $_SESSION['card_number'];
        $amount = $_POST['amount'];
        $atm_id = $_SESSION['atm_id'];
        perform_withdrawal($conn, $card_number, $amount, $atm_id, $backLink);
    } else {
        display_login_form($conn, $error_message);
    }
    
    $conn->close();
    ?>
    
    <footer class="share-tech-mono-regular">
        &copy; <?php echo date("Y"); ?> ATM Management System. All rights reserved.
    </footer>
</body>
</html>