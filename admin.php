<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Admin Panel</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background-color: #f0f0f0;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        h2 {
            color: #2980b9;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #bdc3c7;
            padding: 12px;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        form {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        label {
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
        }
        input[type="text"], select {
            width: calc(100% - 160px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #27ae60;
        }
        .icon {
            width: 24px;
            height: 24px;
            vertical-align: middle;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%233498db'%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3Cpath d='M4 10v7h3v-7H4zm6 0v7h3v-7h-3zM2 22h19v-3H2v3zm14-12v7h3v-7h-3zm-4.5-9L2 6v2h19V6l-9.5-5z'/%3E%3C/svg%3E" alt="Bank icon" class="icon">
        Bank Admin Panel
    </h1>

    <?php
// Database connection
$servername = "localhost"; // Change if your MySQL server is hosted elsewhere
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "atm"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute SQL query
function execute_query($sql) {
    global $conn;
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

// Function to generate random ID
function generate_random_id($table_name, $id_column) {
    global $conn;
    $id = mt_rand(1000, 9999); // Generate a random 4-digit ID
    $check_sql = "SELECT $id_column FROM $table_name WHERE $id_column = $id";
    $result = $conn->query($check_sql);
    if ($result->num_rows > 0) {
        // If ID exists, generate a new one recursively
        return generate_random_id($table_name, $id_column);
    } else {
        return $id;
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add Bank
    if (isset($_POST['add_bank'])) {
        $bank_name = $_POST['bank_name'];
        $bank_address = $_POST['bank_address'];
        $bank_id = generate_random_id('Bank', 'bank_id');
        $sql = "INSERT INTO Bank (bank_id, bank_name, bank_address) VALUES ($bank_id, '$bank_name', '$bank_address')";
        execute_query($sql);
    }

    // Remove Bank
    if (isset($_POST['remove_bank'])) {
        $bank_id = $_POST['bank_id'];
        $sql = "DELETE FROM Bank WHERE bank_id=$bank_id";
        execute_query($sql);
    }

    // Add ATM
    if (isset($_POST['add_atm'])) {
        $atm_name = $_POST['atm_name'];
        $atm_address = $_POST['atm_address'];
        $bank_id = $_POST['bank_id'];
        $atm_id = generate_random_id('ATM', 'atm_id');
        $sql = "INSERT INTO ATM (atm_id, atm_name, atm_address, bank_id) VALUES ($atm_id, '$atm_name', '$atm_address', $bank_id)";
        execute_query($sql);
    }

    // Remove ATM
    if (isset($_POST['remove_atm'])) {
        $atm_id = $_POST['atm_id'];
        $sql = "DELETE FROM ATM WHERE atm_id=$atm_id";
        execute_query($sql);
    }

    // Add Customer
    if (isset($_POST['add_customer'])) {
        $customer_name = $_POST['customer_name'];
        $customer_address = $_POST['customer_address'];
        $phone_number = $_POST['phone_number'];
        $customer_id = generate_random_id('Customer', 'customer_id');
        $sql = "INSERT INTO Customer (customer_id, customer_name, customer_address, phone_number) VALUES ($customer_id, '$customer_name', '$customer_address', '$phone_number')";
        execute_query($sql);
    }

    // Remove Customer
    if (isset($_POST['remove_customer'])) {
        $customer_id = $_POST['customer_id'];
        $sql = "DELETE FROM Customer WHERE customer_id=$customer_id";
        execute_query($sql);
    }

    // Add Account
    if (isset($_POST['add_account'])) {
        $account_number = $_POST['account_number'];
        $account_type = $_POST['account_type'];
        $balance = $_POST['balance'];
        $bank_id = $_POST['bank_id'];
        $customer_id = $_POST['customer_id'];
        $sql = "INSERT INTO Account (account_number, account_type, balance, bank_id, customer_id) VALUES ($account_number, '$account_type', $balance, $bank_id, $customer_id)";
        execute_query($sql);
    }

    // Remove Account
    if (isset($_POST['remove_account'])) {
        $account_number = $_POST['account_number'];
        $sql = "DELETE FROM Account WHERE account_number=$account_number";
        execute_query($sql);
    }

    // Add Card
    if (isset($_POST['add_card'])) {
        $card_number = $_POST['card_number'];
        $account_number = $_POST['account_number'];
        $pin = $_POST['pin'];
        $sql = "INSERT INTO Card (card_number, account_number, pin) VALUES ('$card_number', $account_number, $pin)";
        execute_query($sql);
    }

    // Remove Card
    if (isset($_POST['remove_card'])) {
        $card_number = $_POST['card_number'];
        $sql = "DELETE FROM Card WHERE card_number='$card_number'";
        execute_query($sql);
    }
}

// Function to generate dropdown options from a table
function generate_dropdown_options($table_name, $id_column, $display_column) {
    global $conn;
    $options = "";
    $sql = "SELECT $id_column, $display_column FROM $table_name";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row[$id_column] . "'>" . $row[$display_column] . "</option>";
        }
    }
    return $options;
}

// Display forms for adding/removing entries
?>

<h2>Add Bank</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Bank Name:</label>
    <input type="text" name="bank_name" required>
    <label>Bank Address:</label>
    <input type="text" name="bank_address" required>
    <input type="submit" name="add_bank" value="Add Bank">
</form>

<h2>Remove Bank</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Bank ID:</label>
    <select name="bank_id" required>
        <?php echo generate_dropdown_options('Bank', 'bank_id', 'bank_name'); ?>
    </select>
    <input type="submit" name="remove_bank" value="Remove Bank">
</form>

<h2>Add ATM</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>ATM Name:</label>
    <input type="text" name="atm_name" required>
    <label>ATM Address:</label>
    <input type="text" name="atm_address" required>
    <label>Bank ID:</label>
    <select name="bank_id" required>
        <?php echo generate_dropdown_options('Bank', 'bank_id', 'bank_name'); ?>
    </select>
    <input type="submit" name="add_atm" value="Add ATM">
</form>

<h2>Remove ATM</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>ATM ID:</label>
    <select name="atm_id" required>
        <?php echo generate_dropdown_options('ATM', 'atm_id', 'atm_name'); ?>
    </select>
    <input type="submit" name="remove_atm" value="Remove ATM">
</form>


<?php
// Close connection
$conn->close();
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label>Bank ID:</label>
        <select name="bank_id" required>
            <?php echo generate_dropdown_options('Bank', 'bank_id', 'bank_name'); ?>
        </select>
        <input type="submit" name="remove_bank" value="Remove Bank">
    </form>

    <h2>
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232980b9'%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3Cpath d='M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'/%3E%3C/svg%3E" alt="Add icon" class="icon">
        Add ATM
    </h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label>ATM Name:</label>
        <input type="text" name="atm_name" required>
        <label>ATM Address:</label>
        <input type="text" name="atm_address" required>
        <label>Bank ID:</label>
        <select name="bank_id" required>
            <?php echo generate_dropdown_options('Bank', 'bank_id', 'bank_name'); ?>
        </select>
        <input type="submit" name="add_atm" value="Add ATM">
    </form>

    <h2>
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232980b9'%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3Cpath d='M19 13H5v-2h14v2z'/%3E%3C/svg%3E" alt="Remove icon" class="icon">
        Remove ATM
    </h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label>ATM ID:</label>
        <select name="atm_id" required>
            <?php echo generate_dropdown_options('ATM', 'atm_id', 'atm_name'); ?>
        </select>
        <input type="submit" name="remove_atm" value="Remove ATM">
    </form>

    <?php
    // Close connection
    $conn->close();
    ?>
</div>

</body>
</html>