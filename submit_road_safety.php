<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'citizen') {
    echo "Access denied!";
    exit();
}

if (!isset($_GET['report_id'])) {
    echo "No report selected!";
    exit();
}

$report_id = $_GET['report_id'];
$success_message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $incident_type = $_POST['incident_type'];
    $risk_rate = $_POST['risk_rate'];

    $sql = "INSERT INTO road_safety (report_id, incident_type, risk_rate)
            VALUES ($report_id, '$incident_type', '$risk_rate')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Road Safety report submitted successfully!";
    } else {
        $success_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Road Safety Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFD6E8 0%, #E6D5F5 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            color: #8B5A8E;
            font-size: 28px;
            margin-bottom: 30px;
        }
        
        h2::before {
            content: "🚗 ";
        }
        
        .success-message {
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            padding: 20px 25px;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 25px;
            border: 2px solid #D9B3E6;
            box-shadow: 0 4px 15px rgba(166, 142, 200, 0.3);
            line-height: 1.6;
        }
        
        .success-message::before {
            content: "✅ ";
            font-size: 20px;
        }
        
        .error-message {
            background: linear-gradient(135deg, #FFD6E8 0%, #FFB8D8 100%);
            color: #8B3A5E;
            padding: 20px 25px;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 25px;
            border: 2px solid #FFB3D9;
            box-shadow: 0 4px 15px rgba(255, 142, 200, 0.3);
            line-height: 1.6;
        }
        
        .error-message::before {
            content: "⚠️ ";
            font-size: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        
        label {
            color: #6B4A6E;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="number"] {
            padding: 12px 15px;
            border: 2px solid #E6D5F5;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #FEFAFF;
            color: #5A3A5E;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #C9B3E6;
            box-shadow: 0 0 0 3px rgba(201, 179, 230, 0.2);
            background-color: white;
        }
        
        input[type="text"]::placeholder,
        input[type="number"]::placeholder {
            color: #B8A3D9;
        }
        
        input[type="submit"] {
            padding: 15px 30px;
            background: linear-gradient(135deg, #E6B8E8 0%, #C9B3E6 100%);
            color: #5A3A5E;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(166, 142, 200, 0.3);
            margin-top: 10px;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(166, 142, 200, 0.5);
            background: linear-gradient(135deg, #D9A5DB 0%, #B8A3D9 100%);
        }
        
        input[type="submit"]:active {
            transform: translateY(0);
        }
        
        .back-link {
            text-align: center;
            margin-top: 10px;
        }
        
        .back-link a {
            text-decoration: none;
            color: #A87CA8;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .back-link a:hover {
            background-color: rgba(230, 184, 232, 0.2);
            color: #8B5A8E;
        }
        
        .info-text {
            font-size: 13px;
            color: #A87CA8;
            margin-top: 5px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Road Safety Report</h2>
        
        <?php if ($success_message && strpos($success_message, 'Error') === false): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
            <div class="back-link">
                <a href="citizen_dashboard.php">← Back to Dashboard</a>
            </div>
        <?php elseif ($success_message): ?>
            <div class="error-message">
                <?php echo $success_message; ?>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label>Incident Type:</label>
                    <input type="text" name="incident_type" required>
                </div>
                
                <div class="form-group">
                    <label>Risk Rate (1-10):</label>
                    <input type="number" name="risk_rate" min="1" max="10" required>
                    <span class="info-text">Rate the severity: 1 = Low Risk, 10 = High Risk</span>
                </div>
                
                <input type="submit" value="Submit">
            </form>
            
            <div class="back-link">
                <a href="citizen_dashboard.php">← Back to Dashboard</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Incident Type:</label>
                    <input type="text" name="incident_type" required>
                </div>
                
                <div class="form-group">
                    <label>Risk Rate (1-10):</label>
                    <input type="number" name="risk_rate" min="1" max="10" required>
                    <span class="info-text">Rate the severity: 1 = Low Risk, 10 = High Risk</span>
                </div>
                
                <input type="submit" value="Submit">
            </form>
            
            <div class="back-link">
                <a href="citizen_dashboard.php">← Back to Dashboard</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>