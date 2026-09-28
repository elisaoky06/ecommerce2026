<?php
require "db.php";

$id = intval($_GET["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $passenger_name = trim($_POST["passenger_name"]);
    $pickup_location = trim($_POST["pickup_location"]);
    $dropoff_location = trim($_POST["dropoff_location"]);
    $driver_name = trim($_POST["driver_name"]);
    $fare = floatval($_POST["fare"]);
    $status = $_POST["status"];
    $post_id = intval($_POST["id"]);

    $stmt = $conn->prepare("UPDATE rides SET passenger_name=?, pickup_location=?, dropoff_location=?, driver_name=?, fare=?, status=? WHERE id=?");
    $stmt->bind_param("ssssdsi", $passenger_name, $pickup_location, $dropoff_location, $driver_name, $fare, $status, $post_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM rides WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$ride = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$ride) {
    die("Ride not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Ride</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Edit Ride</h2>
    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?php echo $ride['id']; ?>">

        <label>Passenger Name</label>
        <input type="text" name="passenger_name" value="<?php echo htmlspecialchars($ride['passenger_name']); ?>" required>

        <label>Pickup Location</label>
        <input type="text" name="pickup_location" value="<?php echo htmlspecialchars($ride['pickup_location']); ?>" required>

        <label>Dropoff Location</label>
        <input type="text" name="dropoff_location" value="<?php echo htmlspecialchars($ride['dropoff_location']); ?>" required>

        <label>Driver Name</label>
        <input type="text" name="driver_name" value="<?php echo htmlspecialchars($ride['driver_name']); ?>">

        <label>Fare (GH₵)</label>
        <input type="number" step="0.01" name="fare" value="<?php echo htmlspecialchars($ride['fare']); ?>">

        <label>Status</label>
        <select name="status">
            <option value="requested" <?php echo $ride['status']==='requested'?'selected':''; ?>>Requested</option>
            <option value="ongoing" <?php echo $ride['status']==='ongoing'?'selected':''; ?>>Ongoing</option>
            <option value="completed" <?php echo $ride['status']==='completed'?'selected':''; ?>>Completed</option>
            <option value="cancelled" <?php echo $ride['status']==='cancelled'?'selected':''; ?>>Cancelled</option>
        </select>

        <button type="submit">Update Ride</button>
    </form>
    <p><a href="index.php" class="link">← Back to Rides</a></p>
</div>
</body>
</html>