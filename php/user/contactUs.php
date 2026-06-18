<?php
session_start();
include '../db_connect.php';

$nameErr = $phoneErr = $emailErr = $messageErr = "";
$name = $phone = $email = $message = "";

if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'user') {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT user_fname, user_lname, user_phone, user_email FROM users_info WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $name = $row['user_fname'] . " " . $row['user_lname'];
        $phone = $row['user_phone'];
        $email = $row['user_email'];
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        if (empty($_POST["name"])) {
            $nameErr = "*Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $nameErr = "*Only letters and white space allowed";
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
    }

    if (empty($_POST["message"])) {
        $messageErr = "*Message is required";
    } else {
        $message = test_input($_POST["message"]);
        if (strlen($message) < 8) {
            $messageErr = "*Message is too short. Please explain about your query.";
        }
    }

    if (empty($nameErr) && empty($phoneErr) && empty($emailErr) && empty($messageErr)) {

        $stmt = $conn->prepare("INSERT INTO contact_us (full_name, phone, email, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $message);

        if ($stmt->execute()) {
            echo "<script> alert('Thank you for contacting with us! Your message has been received.'); window.location.href = 'contactUs.php'; </script>";
            if (!isset($_SESSION['user_id'])) {
                $name = "";
                $phone = "";
                $email = "";
            }
        } else {
            echo "<script>alert('Error: Unable to save your message.');</script>";
        }
        $stmt->close();
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Care Hospital/Contact Us</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/user/contact_us.css">
</head>

<body class="bg-color">

    <header> <?php include 'user_header.php'; ?> </header>

    <main class="main-section">
        <div class="text-section">
            <h1 class="section-title montserrat-font">We're Here to Help You</h1>
            <p class="section-description">Have questions or need assistance? Reach out to us anytime.</p>
        </div>

        <form class="form-contain" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>"
                    placeholder="Your Name" <?php echo isset($_SESSION['user_id']) ? "readonly" : ""; ?>>
                <span class="error"><?php echo $nameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="phone">Phone No</label>
                <input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>"
                    placeholder="Your Phone Number" <?php echo isset($_SESSION['user_id']) ? "readonly" : ""; ?>>
                <span class="error"><?php echo $phoneErr; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>"
                    placeholder="you@email.com" <?php echo isset($_SESSION['user_id']) ? "readonly" : ""; ?>>
                <span class="error"><?php echo $emailErr; ?></span>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" placeholder="Please explain about your query..."><?php echo $message; ?></textarea>
                <span class="error"><?php echo $messageErr; ?></span>
            </div>

            <input type="submit" id="submit" value="Submit">
        </form>

    </main>

    <footer> <?php include 'user_footer.php'; ?> </footer>
</body>

</html>