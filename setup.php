<?php
// Database Setup Script
include 'db_config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>";

// Test if database and table are ready
$result = $conn->query("SELECT COUNT(*) as count FROM bookings");
if ($result) {
    echo "<p class='success'>✓ Database 'contract' is ready!</p>";
    echo "<p class='success'>✓ Table 'bookings' created successfully!</p>";
    echo "<p>Your database is now set up. You can start using the car rental form.</p>";
} else {
    echo "<p class='error'>✗ Error: " . $conn->error . "</p>";
}

$conn->close();
echo "</body></html>";
?>
