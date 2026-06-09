<?php
include 'db_config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gmail    = trim($_POST['gmail'] ?? '');
    $contract = trim($_POST['contract'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    
    // Validate
    if (empty($gmail) || empty($contract) || empty($address)) {
        $error = "All fields are required!";
    } elseif (!filter_var($gmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address!";
    } elseif (strlen($contract) < 3) {
        $error = "Contract number must be at least 3 characters!";
    } elseif (strlen($address) < 5) {
        $error = "Address must be at least 5 characters!";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO bookings (gmail, contract_number, address) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $gmail, $contract, $address);
        
        if ($stmt->execute()) {
            $success = "✓ Booking saved successfully! Thank you.";
            $_POST = []; // Clear form
        } else {
            if (strpos($stmt->error, 'Duplicate') !== false) {
                $error = "This contract number already exists!";
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            padding: 1rem;
        }
        .container { 
            background: #fff; 
            padding: 2.5rem; 
            border-radius: 12px; 
            box-shadow: 0 10px 40px rgba(0,0,0,.2); 
            width: 100%; 
            max-width: 550px; 
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        h1 { 
            font-size: 2rem; 
            color: #333; 
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }
        .message {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-weight: 600;
            display: none;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
            display: block;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
            display: block;
        }
        .form-group { 
            margin-bottom: 1.3rem; 
        }
        label { 
            display: block; 
            margin-bottom: 0.4rem; 
            font-weight: 600; 
            color: #333;
            font-size: 0.95rem;
        }
        input { 
            width: 100%; 
            padding: 0.75rem 1rem; 
            border: 2px solid #e0e0e0; 
            border-radius: 6px; 
            font: inherit; 
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        button { 
            width: 100%; 
            padding: 0.9rem; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; 
            border: none; 
            border-radius: 6px; 
            font-size: 1rem; 
            font-weight: 600;
            cursor: pointer; 
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 0.5rem;
        }
        button:hover { 
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        button:active {
            transform: translateY(0);
        }
        .form-description {
            color: #999;
            font-size: 0.85rem;
            margin-top: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚗</div>
            <h1>Car Rental Service</h1>
            <p class="subtitle">Complete your booking information</p>
        </div>

        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="contact.php">
            <div class="form-group">
                <label for="gmail">Gmail Address</label>
                <input 
                    type="email" 
                    id="gmail" 
                    name="gmail" 
                    placeholder="your.email@gmail.com" 
                    value="<?php echo htmlspecialchars($_POST['gmail'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="contract">Contract Number</label>
                <input 
                    type="text" 
                    id="contract" 
                    name="contract" 
                    placeholder="e.g., CNT-2024-001234" 
                    value="<?php echo htmlspecialchars($_POST['contract'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input 
                    type="text" 
                    id="address" 
                    name="address" 
                    placeholder="Your pickup/delivery address" 
                    value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>"
                    required
                >
            </div>

            <button type="submit">Submit Booking Information</button>
            <p class="form-description">Rakibul  Islam Rasel</p>
        </form>
    </div>
</body>
</html>
