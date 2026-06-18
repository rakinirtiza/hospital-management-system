<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/common/base.css">
    <link rel="stylesheet" href="../../css/admin/resource_h.css">
</head>

<body class="bg-color">

    <?php

    $beds = [
        ["type" => "General", "total" => 50, "available" => 20],
        ["type" => "ICU", "total" => 10, "available" => 2],
        ["type" => "VIP", "total" => 5, "available" => 1]
    ];

    $rooms = [
        ["room_no" => "101", "type" => "Single", "status" => "Available"],
        ["room_no" => "102", "type" => "Single", "status" => "Occupied"],
        ["room_no" => "201", "type" => "Double", "status" => "Available"],
        ["room_no" => "202", "type" => "Double", "status" => "Occupied"]
    ];

    $resources = [
        ["name" => "Ventilator", "total" => 10, "available" => 4],
        ["name" => "ECG Machine", "total" => 5, "available" => 2],
        ["name" => "X-Ray Machine", "total" => 3, "available" => 1]
    ];
    ?>

    <header> <?php include 'admin_header.php'; ?> </header>

    <main class="main-section">
        <h1>Hospital Resources</h1>
        <br>
        <h2>Beds Availability</h2>
        <table>
            <tr>
                <th>Type</th>
                <th>Total</th>
                <th>Available</th>
            </tr>
            <?php foreach ($beds as $bed): ?>
                <tr>
                    <td><?php echo $bed['type']; ?></td>
                    <td><?php echo $bed['total']; ?></td>
                    <td><?php echo $bed['available']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Rooms Status</h2>
        <table>
            <tr>
                <th>Room No</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
            <?php foreach ($rooms as $room): ?>
                <tr>
                    <td><?php echo $room['room_no']; ?></td>
                    <td><?php echo $room['type']; ?></td>
                    <td><?php echo $room['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Equipment / Resources</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Total</th>
                <th>Available</th>
            </tr>
            <?php foreach ($resources as $res): ?>
                <tr>
                    <td><?php echo $res['name']; ?></td>
                    <td><?php echo $res['total']; ?></td>
                    <td><?php echo $res['available']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>

    <footer> <?php include 'admin_footer.php'; ?> </footer>

</body>

</html>