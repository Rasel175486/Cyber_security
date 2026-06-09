<?php
// Test database connection and configuration
header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .info { background: #f0f0f0; padding: 1rem; border-radius: 4px; margin: 1rem 0; }
    </style>
</head>
<body>
<h1>Car Rental Database Test</h1>";

echo "<div class='info'>";

// Test 1: Database Connection
echo "<h3>Test 1: Database Connection</h3>";
$conn = @new mysqli('localhost', 'root', '');
if ($conn->connect_error) {
    echo "<p class='error'>✗ Connection Failed: " . $conn->connect_error . "</p>";
    echo "<p class='warning'>MySQL with user 'root' and no password is not accessible.</p>";
    echo "<p>Common solutions:</p>";
    echo "<ul>";
    echo "<li>Add MySQL password to db_config.php if you set one</li>";
    echo "<li>Create a new MySQL user for web applications</li>";
    echo "<li>Check if MySQL service is running: <code>sudo service mysql status</code></li>";
    echo "</ul>";
} else {
    echo "<p class='success'>✓ Connected to MySQL Server</p>";
    
    // Test 2: Database Exists
    echo "<h3>Test 2: Database 'contract'</h3>";
    $result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'contract'");
    if ($result && $result->num_rows > 0) {
        echo "<p class='success'>✓ Database 'contract' exists</p>";
    } else {
        echo "<p class='warning'>⊙ Database 'contract' not found. Creating it...</p>";
        if ($conn->query("CREATE DATABASE contract")) {
            echo "<p class='success'>✓ Database 'contract' created successfully</p>";
        } else {
            echo "<p class='error'>✗ Error creating database: " . $conn->error . "</p>";
        }
    }
    
    // Test 3: Select Database
    if ($conn->select_db('contract')) {
        echo "<h3>Test 3: Table 'bookings'</h3>";
        $result = $conn->query("SHOW TABLES LIKE 'bookings'");
        if ($result && $result->num_rows > 0) {
            echo "<p class='success'>✓ Table 'bookings' exists</p>";
            
            // Show table structure
            $cols = $conn->query("DESCRIBE bookings");
            echo "<h4>Table Structure:</h4>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
            while ($row = $cols->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Count records
            $count = $conn->query("SELECT COUNT(*) as cnt FROM bookings");
            $row = $count->fetch_assoc();
            echo "<p>Total records in bookings: <strong>" . $row['cnt'] . "</strong></p>";
        } else {
            echo "<p class='warning'>⊙ Table 'bookings' not found. Creating it...</p>";
            $sql_create = "CREATE TABLE bookings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                gmail VARCHAR(255) NOT NULL,
                contract_number VARCHAR(100) NOT NULL UNIQUE,
                address TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_gmail (gmail),
                INDEX idx_contract (contract_number)
            )";
            if ($conn->query($sql_create)) {
                echo "<p class='success'>✓ Table 'bookings' created successfully</p>";
            } else {
                echo "<p class='error'>✗ Error creating table: " . $conn->error . "</p>";
            }
        }
    }
    
    $conn->close();
}

echo "</div>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Fill out the form at <a href='index.html'>index.html</a></li>";
echo "<li>Submit the booking information</li>";
echo "<li>Check this page again to see if data was saved</li>";
echo "</ol>";
echo "</body></html>";
?>
