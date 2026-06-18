<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price List</title>
</head>

<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "my_db";

    // Connect to database
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "CREATE TABLE IF NOT EXISTS PriceList (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        pname VARCHAR(30) NOT NULL,
        bprice DECIMAL(10,2) NOT NULL,
        sprice DECIMAL(10,2) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $sql);

    $pname = $bprice = $sprice = "";
    $pnameErr = $bpriceErr = $spriceErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["pname"])) {
            $pnameErr = "*Product name is required";
        } else {
            $pname = test_input($_POST["pname"]);
        }

        if (empty($_POST["bprice"])) {
            $bpriceErr = "*Buying price is required";
        } else {
            $bprice = test_input($_POST["bprice"]);
        }

        if (empty($_POST["sprice"])) {
            $spriceErr = "*Selling price is required";
        } else {
            $sprice = test_input($_POST["sprice"]);
        }

        if ($pname && $bprice && $sprice) {
            $sql = "INSERT INTO PriceList (pname, bprice, sprice) VALUES ('$pname', '$bprice', '$sprice')";
            mysqli_query($conn, $sql);
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>

    <h2>Add Product</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="pname">Name</label>
            <input type="text" name="pname" id="pname" required>
        </div>
        <div>
            <label for="bprice">Buying Price</label>
            <input type="number" name="bprice" id="bprice" required>
        </div>
        <div>
            <label for="sprice">Selling Price</label>
            <input type="number" name="sprice" id="sprice" required>
        </div>
        <hr>
        <div>
            <label><input type="checkbox"> Display</label>
        </div>
        <hr>
        <button type="submit">SAVE</button>
    </form>

    <h2>DISPLAY</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Profit</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM PriceList";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $profit = $row['sprice'] - $row['bprice'];
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['pname']) . "</td>";
                    echo "<td>" . number_format($profit, 2) . "</td>";
                    echo "</tr>";
                }
            } 
            ?>
        </tbody>
    </table>
</body>

</html>