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
    $item_name = $_POST['item_name'];
    $item_description = $_POST['item_description'];
    $report_type = $_POST['report_type'];
    $item_status = 'Pending';

    $sql = "INSERT INTO lost_and_found (report_id, item_name, item_description, report_type, item_status)
            VALUES ($report_id, '$item_name', '$item_description', '$report_type', '$item_status')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Lost & Found report submitted successfully!";
    } else {
        $success_message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Lost & Found Report</title>
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
            content: "🔍 ";
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
        textarea {
            padding: 12px 15px;
            border: 2px solid #E6D5F5;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #FEFAFF;
            color: #5A3A5E;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #C9B3E6;
            box-shadow: 0 0 0 3px rgba(201, 179, 230, 0.2);
            background-color: white;
        }
        
        input[type="text"]::placeholder,
        textarea::placeholder {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Lost & Found Report</h2>
        
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
                    <label>Item Name:</label>
                    <input type="text" name="item_name" required>
                </div>
                
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="item_description" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Report Type:</label>
                    <input type="text" name="report_type" placeholder="Lost or Found" required>
                </div>
                
                <input type="submit" value="Submit">
            </form>
            
            <div class="back-link">
                <a href="citizen_dashboard.php">← Back to Dashboard</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Item Name:</label>
                    <input type="text" name="item_name" required>
                </div>
                
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="item_description" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Report Type:</label>
                    <input type="text" name="report_type" placeholder="Lost or Found" required>
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