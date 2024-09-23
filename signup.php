<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; //
$dbname = "atm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sign-up form submission
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $pswd = $_POST['pswd'];

    // Generate unique customer_id
    $result = $conn->query("SELECT MAX(customer_id) AS max_id FROM Customer");
    $row = $result->fetch_assoc();
    $customer_id = $row['max_id'] + 1;

    $sql = "INSERT INTO Customer (customer_id, customer_name, customer_address, phone_number, pswd) 
            VALUES ($customer_id, '$name', '$address', '$phone', $pswd)";

    if ($conn->query($sql) === TRUE) {
        $message = "New record created successfully. Your Customer ID is: " . $customer_id;
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
    <title>Sign Up</title>
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
        Sign Up
    </header>

    <div class="form-container share-tech-mono-regular">
        <h2>Sign Up</h2>
        <?php if (!empty($message)) { echo "<p class='error-message'>$message</p>"; } ?>
        <form method="post" action="">
            Name: <input type="text" name="name" required><br><br>
            Address: <input type="text" name="address"><br><br>
            Phone Number: <input type="text" name="phone"><br><br>
            Password: <input type="password" name="pswd" required><br><br>
            <input type="submit" value="Sign Up">
        </form>
        <a href="home.php">Back to Home</a>
    </div>

    <footer class="share-tech-mono-regular">
        &copy; <?php echo date("Y"); ?> Bank Website. All rights reserved.
    </footer>
</body>
</html>

<?php
$conn->close();
?>