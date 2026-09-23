<?php
require "db.php";

$sql = "CREATE TABLE IF NOT EXISTS rides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    passenger_name VARCHAR(255) NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    dropoff_location VARCHAR(255) NOT NULL,
    driver_name VARCHAR(255),
    fare DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('requested', 'ongoing', 'completed', 'cancelled') DEFAULT 'requested',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Setup complete!</h2>";
    echo "<p><a href='index.php'>Go to Ride App</a></p>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();