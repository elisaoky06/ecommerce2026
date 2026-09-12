<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $passenger_name = trim($_POST["passenger_name"]);
    $pickup_location = trim($_POST["pickup_location"]);
    $dropoff_location = trim($_POST["dropoff_location"]);
    $driver_name = trim($_POST["driver_name"]);
    $fare = floatval($_POST["fare"]);
    $status = $_POST["status"];

    $stmt = $conn->prepare("INSERT INTO rides (passenger_name, pickup_location, dropoff_location, driver_name, fare, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssds", $passenger_name, $pickup_location, $dropoff_location, $driver_name, $fare, $status);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Ride</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Request a Ride</h2>
    <form method="POST" action="create.php">
        <label>Passenger Name</label>
        <input type="text" name="passenger_name" required>

        <label>Pickup Location</label>
        <input type="text" name="pickup_location" required>

        <label>Dropoff Location</label>
        <input type="text" name="dropoff_location" required>

        <label>Driver Name (optional)</label>
        <input type="text" name="driver_name">

        <label>Fare (GH₵)</label>
        <input type="number" step="0.01" name="fare" value="0.00">

        <label>Status</label>
        <select name="status">
            <option value="requested">Requested</option>
            <option value="ongoing">Ongoing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <button type="submit">Save Ride</button>
    </form>
    <p><a href="index.php" class="link">← Back to Rides</a></p>
</div>
</body>
</html>