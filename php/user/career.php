<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers - Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/career.css">
</head>

<body class="bg-color">
    <?php
    include '../db_connect.php';

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $nameErr = $emailErr = $phoneErr = $positionErr = "";
    $name = $email = $phone = $position = $message = "";
    $successMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameErr = "*Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z\s'.-]+$/", $name)) {
                $nameErr = "Only letters, spaces, and ('. -) allowed";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "*Email is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }

        if (empty($_POST["phone"])) {
            $phoneErr = "*Phone is required";
        } else {
            $phone = test_input($_POST["phone"]);
            if (!preg_match("/^[0-9]{11}$/", $phone)) {
                $phoneErr = "Phone number must be 11 digits";
            }
        }

        if (empty($_POST["position"])) {
            $positionErr = "*Position is required";
        } else {
            $position = test_input($_POST["position"]);
        }

        $message = isset($_POST["message"]) ? test_input($_POST["message"]) : "";

        if (empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($positionErr)) {
            $stmt = $conn->prepare("INSERT INTO careers_info (name, email, phone, position, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $phone, $position, $message);

            if ($stmt->execute()) {
                $successMsg = "Your application has been submitted successfully!";
                $name = $email = $phone = $position = $message = "";
            } else {
                $successMsg = "Something went wrong. Please try again.";
            }

            $stmt->close();
        }
    }
    ?>

    <header><?php include 'user_header.php'; ?></header>

    <main class="main-section">
        <section class="text-section">
            <h1 class="section-title montserrat-font">Join Our Team</h1>
            <p class="section-description roboto-font">Explore career opportunities and submit your application online.</p>
        </section>

        <section class="form-contain">
            <?php if (!empty($successMsg)) : ?>
                <div class="success-message"><?php echo $successMsg; ?></div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $name; ?>" placeholder="Your Name">
                    <span class="error"><?php echo $nameErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="Your Email">
                    <span class="error"><?php echo $emailErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="Your Phone Number">
                    <span class="error"><?php echo $phoneErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="position">Position Applying For</label>
                    <select id="position" name="position">
                        <option value="">Select Position</option>
                        <option value="Doctor" <?php if ($position == "Doctor") echo "selected"; ?>>Doctor</option>
                        <option value="Nurse" <?php if ($position == "Nurse") echo "selected"; ?>>Nurse</option>
                        <option value="Receptionist" <?php if ($position == "Receptionist") echo "selected"; ?>>Receptionist</option>
                        <option value="Lab Technician" <?php if ($position == "Lab Technician") echo "selected"; ?>>Lab Technician</option>
                        <option value="Administrative Staff" <?php if ($position == "Administrative Staff") echo "selected"; ?>>Administrative Staff</option>
                    </select>
                    <span class="error"><?php echo $positionErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="message">Additional Information <small class="small-text">(Optional)</small></label>
                    <textarea id="message" name="message" rows="4" placeholder="Add any relevant information..."><?php echo $message; ?></textarea>
                </div>

                <input type="submit" id="submit" value="Submit Application">
            </form>
        </section>
    </main>

    <footer><?php include 'user_footer.php'; ?></footer>
</body>

</html>
