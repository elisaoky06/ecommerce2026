<?php
require "db.php";
$result = $conn->query("SELECT * FROM rides ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ride Hailing App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="top-bar">
        <h1>🚗 Rides</h1>
        <a href="create.php" class="btn">+ Request Ride</a>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <p>No rides yet. Request one to get started.</p>
    <?php endif; ?>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="ride-card">
            <h3><?php echo htmlspecialchars($row['pickup_location']); ?> → <?php echo htmlspecialchars($row['dropoff_location']); ?></h3>
            <p><strong>Passenger:</strong> <?php echo htmlspecialchars($row['passenger_name']); ?></p>
            <p><strong>Driver:</strong> <?php echo $row['driver_name'] ? htmlspecialchars($row['driver_name']) : '—'; ?></p>
            <p><strong>Fare:</strong> GH₵<?php echo number_format($row['fare'], 2); ?></p>
            <span class="status <?php echo $row['status']; ?>"><?php echo str_replace('_', ' ', $row['status']); ?></span>
            <div class="actions">
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="link">Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" class="link" onclick="return confirm('Delete this ride?');">Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
<?php $conn->close(); ?>