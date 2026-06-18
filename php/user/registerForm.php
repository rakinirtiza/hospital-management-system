<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Care Hospital/Register</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/register_form.css">
</head>

<body class="bg-color">
    <div class="form-validation">
        <?php
        include '../db_connect.php';

        $fnameErr = $lnameErr = $phoneErr = $emailErr = $dobErr = $genderErr = $addressErr = $passwordErr = $confirmPasswordErr = "";

        $fname = $lname = $phone = $email = $dob = $gender = $address = $password = $confirmPassword = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["fname"])) {
                $fnameErr = "*First name is required";
            } else {
                $fname = test_input($_POST["fname"]);
                if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
                    $fnameErr = "*Only letters and white space allowed";
                }
            }

            if (empty($_POST["lname"])) {
                $lnameErr = "*Last name is required";
            } else {
                $lname = test_input($_POST["lname"]);
                if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
                    $lnameErr = "*Only letters and white space allowed";
                }
            }

            if (empty($_POST["phone"])) {
                $phoneErr = "*Phone number is required";
            } else {
                $phone = test_input($_POST["phone"]);
                if (!preg_match("/^[0-9]{11}$/", $phone)) {
                    $phoneErr = "*Invalid phone number";
                }
            }

            if (empty($_POST["email"])) {
                $emailErr = "*Email is required";
            } else {
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "*Invalid email format";
                }
            }

            if (empty($_POST["dob"])) {
                $dobErr = "*Date of Birth is required";
            } else {
                $dob = test_input($_POST["dob"]);
            }

            if (empty($_POST["gender"])) {
                $genderErr = "*Gender is required";
            } else {
                $gender = test_input($_POST["gender"]);
            }

            if (empty($_POST["address"])) {
                $addressErr = "*Address is required";
            } else {
                $address = test_input($_POST["address"]);
            }

            if (empty($_POST["password"])) {
                $passwordErr = "*Password is required";
            } else {
                $password = test_input($_POST["password"]);
                if (strlen($password) < 6) {
                    $passwordErr = "*Password must be at least 6 characters long";
                }
            }

            if (empty($_POST["confirm_password"])) {
                $confirmPasswordErr = "*Please confirm your password";
            } else {
                $confirmPassword = test_input($_POST["confirm_password"]);
                if ($password !== $confirmPassword) {
                    $confirmPasswordErr = "*Passwords do not match";
                }
            }

            if (
                empty($fnameErr) && empty($lnameErr) && empty($phoneErr) && empty($emailErr) && 
                empty($dobErr) && empty($genderErr) && empty($addressErr) && empty($passwordErr) && empty($confirmPasswordErr)
            ) {
                try {
                    $stmt = $conn->prepare("INSERT INTO users_info (user_fname, user_lname, user_phone, user_email, user_dob, user_gender, user_address, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssss", $fname, $lname, $phone, $email, $dob, $gender, $address, $password);

                    $stmt->execute();

                    $fname = $lname = $phone = $email = $dob = $gender = $address = $password = $confirmPassword = "";

                    echo "
                    <script>
                    alert('Registration successful! Please login.');
                    window.location.href = 'loginForm.php';
                    </script> 
                    ";

                    $stmt->close();
                } catch (mysqli_sql_exception $e) {
                    if (strpos($e->getMessage(), 'user_phone') !== false) {
                        $msg = "Phone number already exists!";
                    } elseif (strpos($e->getMessage(), 'user_email') !== false) {
                        $msg = "Email already exists!";
                    } else {
                        $msg = "Duplicate entry!";
                    }
                    echo "<script>alert('$msg');</script>";
                }
            }
        }

        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $conn->close();

        ?>
    </div>

    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section">
        <h2>Fill Out the Form Below to Register</h2>
        <form class="form-contain" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" placeholder="Enter your first name">
                <span class="error"><?php echo $fnameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>" placeholder="Enter your last name">
                <span class="error"><?php echo $lnameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="phone">Phone No</label>
                <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" placeholder="Enter your phone number">
                <span class="error"><?php echo $phoneErr; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?php echo $email; ?>" placeholder="you@email.com">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?php echo $dob; ?>">
                <span class="error"><?php echo $dobErr; ?></span>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender">
                    <option value="" disabled <?php if ($gender == "") {
                                                    echo "selected";
                                                } ?>>Select your gender</option>
                    <option value="male" <?php if ($gender == "male") {
                                                echo "selected";
                                            } ?>>Male</option>
                    <option value="female" <?php if ($gender == "female") {
                                                echo "selected";
                                            } ?>>Female</option>
                    <option value="other" <?php if ($gender == "other") {
                                                echo "selected";
                                            } ?>>Other</option>
                </select>
                <span class="error"><?php echo $genderErr; ?></span>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" id="address" placeholder="House no, Road no, Thana, District"><?php echo $address; ?></textarea>
                <span class="error"><?php echo $addressErr; ?></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" value="<?php echo $password; ?>">
                <span class="error"><?php echo $passwordErr; ?></span>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" value="<?php echo $confirmPassword; ?>">
                <span class="error"><?php echo $confirmPasswordErr; ?></span>
            </div>

            <input type="submit" value="Submit" name="submit">
        </form>
    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer>
</body>

</html>