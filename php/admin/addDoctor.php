<?php
include '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor | Health Care Hospital</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/admin/add_doctor.css">
</head>

<body class="bg-color">

    <div class="form-validation-php">
        <?php

        $fnameErr = $lnameErr = $phoneErr = $emailErr = $dobErr = $dojErr = $genderErr = $countryErr = $degreeErr = $departmentErr = $salaryErr = $passwordErr = "";

        $fname = $lname = $phone = $email = $dob = $doj = $gender = $country = $degree = $department_id = $salary = $password = "";

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
                    $phoneErr = "*Invalid phone number (must be 11 digits)";
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
                if (strtotime($dob) > strtotime(date("Y-m-d"))) {
                    $dobErr = "*DoB cannot be in the future";
                }
                $age = (date("Y") - date("Y", strtotime($dob)));

                if ($age < 24) {
                    $dobErr = "*Minimum age must be 24 years";
                }
            }

            if (empty($_POST["doj"])) {
                $dojErr = "*Date of Joining is required";
            } else {
                $doj = test_input($_POST["doj"]);
                if (strtotime($doj) > strtotime(date("Y-m-d"))) {
                    $dojErr = "*DoJ cannot be in the future";
                }
            }

            if (empty($_POST["gender"])) {
                $genderErr = "*Gender is required";
            } else {
                $gender = test_input($_POST["gender"]);
            }

            if (empty($_POST["country"])) {
                $countryErr = "*Country is required";
            } else {
                $country = test_input($_POST["country"]);
            }

            if (empty($_POST["degree"])) {
                $degreeErr = "*Degree is required";
            } else {
                $degree = test_input($_POST["degree"]);
            }

            if (empty($_POST["department_id"])) {
                $departmentErr = "*Please select a department";
            } else {
                $department_id = test_input($_POST["department_id"]);
            }

            if (empty($_POST["salary"])) {
                $salaryErr = "*Salary is required";
            } else {
                $salary = test_input($_POST["salary"]);
                if (!is_numeric($salary)) {
                    $salaryErr = "*Salary must be a number";
                }
            }

            if (empty($_POST["password"])) {
                $passwordErr = "*Password is required";
            } else {
                $password = test_input($_POST["password"]);
                if (strlen($password) < 6) {
                    $passwordErr = "*Password must be at least 6 characters";
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
        ?>

    </div>

    <div class="db-connect">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (
                empty($fnameErr) && empty($lnameErr) && empty($phoneErr) && empty($emailErr) &&
                empty($dobErr) && empty($dojErr) && empty($genderErr) && empty($countryErr) &&
                empty($degreeErr) && empty($departmentErr) && empty($salaryErr) && empty($passwordErr)
            ) {
                try {
                    $stmt = $conn->prepare("INSERT INTO doctors_info (doctor_fname, doctor_lname, doctor_phone, doctor_email, doctor_dob, doctor_doj, doctor_gender, doctor_country, doctor_degree, department_id, doctor_salary, doctor_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    $stmt->bind_param("sssssssssiis", $fname, $lname, $phone, $email, $dob, $doj, $gender, $country, $degree, $department_id, $salary, $password);

                    $stmt->execute();

                    $fname = $lname = $phone = $email = $dob = $doj = $gender = $country = $degree = $department_id = $salary = $password = "";

                    echo "<script>alert('Doctor added Successfully');</script>";
                } catch (mysqli_sql_exception $e) {
                    if (strpos($e->getMessage(), 'doctor_phone') !== false) {
                        $msg = "Phone number already exists!";
                        echo "<script>alert('$msg');</script>";
                    } elseif (strpos($e->getMessage(), 'doctor_email') !== false) {
                        $msg = "Email already exists!";
                        echo "<script>alert('$msg');</script>";
                    } else {
                        $msg = "Duplicate entry!";
                        echo "<script>alert('$msg');</script>";
                    }
                }
            }
        }
        ?>
    </div>

    <header> <?php include 'admin_header.php'; ?> </header>

    <main class="main-section">
        <h2 class="montserrat-font section-title">Add New Doctor</h2>

        <form class="form-container" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" name="fname" value="<?php echo $fname; ?>" placeholder="Enter first name">
                <span class="error"><?php echo $fnameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" value="<?php echo $lname; ?>" placeholder="Enter last name">
                <span class="error"><?php echo $lnameErr; ?></span>
            </div>

            <div class="form-group">
                <label for="phone">Phone No</label>
                <input type="text" name="phone" value="<?php echo $phone; ?>" placeholder="Enter phone no">
                <span class="error"><?php echo $phoneErr; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="doctor@email.com">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?php echo $dob; ?>">
                <span class="error"><?php echo $dobErr; ?></span>
            </div>

            <div class="form-group">
                <label for="doj">Date of Joining</label>
                <input type="date" name="doj" id="doj" value="<?php echo $doj ? $doj : date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                <span class="error"><?php echo $dojErr; ?></span>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender">
                    <option value="" disabled <?php if (empty($gender)) echo 'selected'; ?>>Select Gender</option>
                    <option value="Male" <?php if ($gender == "Male") echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($gender == "Female") echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if ($gender == "Other") echo 'selected'; ?>>Other</option>
                </select>
                <span class="error"><?php echo $genderErr; ?></span>
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <select name="country" id="country">
                    <option value="" disabled <?php if (empty($country)) echo 'selected'; ?>>Select Country</option>
                    <option value="Bangladesh" <?php if ($country == "Bangladesh") echo 'selected'; ?>>Bangladesh</option>
                    <option value="India" <?php if ($country == "India") echo 'selected'; ?>>India</option>
                    <option value="Nepal" <?php if ($country == "Nepal") echo 'selected'; ?>>Nepal</option>
                    <option value="Bhutan" <?php if ($country == "Bhutan") echo 'selected'; ?>>Bhutan</option>
                    <option value="Pakistan" <?php if ($country == "Pakistan") echo 'selected'; ?>>Pakistan</option>
                    <option value="Sri Lanka" <?php if ($country == "Sri Lanka") echo 'selected'; ?>>Sri Lanka</option>
                    <option value="Maldives" <?php if ($country == "Maldives") echo 'selected'; ?>>Maldives</option>
                    <option value="China" <?php if ($country == "China") echo 'selected'; ?>>China</option>
                    <option value="Japan" <?php if ($country == "Japan") echo 'selected'; ?>>Japan</option>
                    <option value="South Korea" <?php if ($country == "South Korea") echo 'selected'; ?>>South Korea</option>
                </select>
                <span class="error"><?php echo $countryErr; ?></span>
            </div>

            <div class="form-group">
                <label for="degree">Degree(s)</label>
                <input type="text" name="degree" id="degree" value="<?php echo $degree; ?>" placeholder="Example: MBBS, FCPS, MD">
                <span class="error"><?php echo $degreeErr; ?></span>
            </div>

            <div class="form-group">
                <label for="department">Select Department</label>
                <select name="department_id" id="department_id">
                    <option value="" disabled selected>Select Department</option>
                    <?php
                    $query = "SELECT department_id, department_name FROM departments_info ORDER BY department_name ASC";
                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($department_id == $row['department_id']) ? 'selected' : '';
                        echo '<option value="' . $row['department_id'] . '" ' . $selected . '>' . $row['department_name'] . '</option>';
                    }

                    ?>
                </select>
                <span class="error"><?php echo $departmentErr; ?></span>
            </div>

            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="text" name="salary" value="<?php echo $salary; ?>" placeholder="Enter salary">
                <span class="error"><?php echo $salaryErr; ?></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" value="<?php echo $password; ?>" placeholder="Set a password for doctor">
                <span class="error"><?php echo $passwordErr; ?></span>
            </div>

            <input type="submit" class="save" id="save" value="Save">
        </form>

        <section class="doctor-list-section">
            <h2 class="montserrat-font section-title">All Doctors</h2>

            <?php
            if (isset($_GET['update_msg'])) {
                echo '<h4 style="color: green;">' . $_GET['update_msg'] . '</h4>';
            }
            if (isset($_GET['delete_msg'])) {
                echo '<h4 style="color: red;">' . $_GET['delete_msg'] . '</h4>';
            }
            ?>

            <table class="doctor-table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>DOJ</th>
                        <th>Degree</th>
                        <th>Department</th>
                        <th>Salary</th>
                        <th>Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT d.*, dep.department_name FROM doctors_info d JOIN departments_info dep ON d.department_id = dep.department_id ORDER BY d.doctor_id ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $serial = 1;
                        while ($row = $result->fetch_assoc()) {
                            $fullName = $row['doctor_fname'] . ' ' . $row['doctor_lname'];
                            echo "<tr>
                    <td>{$serial}</td>
                    <td>{$fullName}</td>
                    <td>{$row['doctor_phone']}</td>
                    <td>{$row['doctor_email']}</td>
                    <td>{$row['doctor_doj']}</td>
                    <td>{$row['doctor_degree']}</td>
                    <td>{$row['department_name']}</td>
                    <td>{$row['doctor_salary']}</td>
                    <td>{$row['doctor_password']}</td>
                    <td>
                        <a href='update_doctor.php?id={$row['doctor_id']}' class='edit-btn'>Update</a>
                        <a href='delete_doctor.php?id={$row['doctor_id']}' class='delete-btn'>Delete</a>
                    </td>
                </tr>";
                            $serial++;
                        }
                    } else {
                        echo "<tr><td colspan='10' style='text-align:center;'>No doctors found</td></tr>";
                    }

                    ?>
                </tbody>
            </table>

        </section>

    </main>

    <footer> <?php include 'admin_footer.php'; ?> </footer>

    <?php $conn->close(); ?>

</body>

</html>