<?php
session_start();
include('dbconnect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'citizen') {
    echo "Access denied!";
    exit();
}

if (!isset($_GET['report_id'])) {
    echo "No report selected!";
    exit();
}

$report_id = (int) $_GET['report_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_type    = $_POST['issue_type'];
    $severity_rate = $_POST['severity_rate'];
    $risk_score    = (int) $_POST['risk_score'];

    if ($risk_score < 1 || $risk_score > 10) {
        $message = "Risk score must be between 1 and 10.";
    } else {
        $sql = "
            INSERT INTO health_sanitation (report_id, issue_type, severity_rate, risk_score)
            VALUES ($report_id, '$issue_type', '$severity_rate', $risk_score)
        ";

        if ($conn->query($sql)) {
            $message = "Health & Sanitation report submitted successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Health & Sanitation Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFB6D9 0%, #D8B5FF 50%, #C9A0F5 100%);
            background-attachment: fixed;
            padding: 30px 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 650px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            padding: 45px;
            box-shadow: 0 15px 50px rgba(200, 100, 200, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 182, 217, 0.3);
        }
        
        h2 {
            text-align: center;
            color: #A64D79;
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .subtitle {
            text-align: center;
            color: #B57BB4;
            font-size: 14px;
            margin-bottom: 35px;
            font-style: italic;
        }
        
        form {
            display: flex;
            flex-direction: column;
        }
        
        label {
            color: #8B4D7A;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 15px;
            display: flex;
            align-items: center;
        }
        
        label::after {
            content: "*";
            color: #D89FD8;
            margin-left: 4px;
            font-size: 14px;
        }
        
        select,
        input[type="number"] {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #F5D8F5;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #FFF5FF 0%, #FFF0FF 100%);
            color: #6E3A5E;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-bottom: 25px;
        }
        
        select:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #E6A8D8;
            box-shadow: 0 0 0 4px rgba(230, 168, 216, 0.15);
            background: white;
            transform: translateY(-1px);
        }
        
        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 6L11 1' stroke='%23B57BB4' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 45px;
        }
        
        input[type="submit"] {
            padding: 16px 35px;
            background: linear-gradient(135deg, #D89FD8 0%, #C9A0F5 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(200, 100, 200, 0.35);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 15px;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(200, 100, 200, 0.5);
            background: linear-gradient(135deg, #C88FC8 0%, #B890E5 100%);
        }
        
        input[type="submit"]:active {
            transform: translateY(-1px);
        }
        
        .msg {
            text-align: center;
            font-weight: 600;
            color: #A64D79;
            margin-bottom: 25px;
            background: linear-gradient(135deg, #FFE6F5 0%, #F5D8FF 100%);
            padding: 18px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(200, 100, 200, 0.2);
            border: 2px solid rgba(200, 100, 200, 0.2);
            font-size: 16px;
        }
        
        .msg::before {
            content: "✓ ";
            font-size: 20px;
            margin-right: 5px;
        }
        
        .hint {
            font-size: 13px;
            color: #B57BB4;
            margin-top: -18px;
            margin-bottom: 20px;
            font-style: italic;
        }
        
        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px dashed #F5D8F5;
            text-decoration: none;
            color: #B57BB4;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        
        a::before {
            content: "← ";
            margin-right: 5px;
        }
        
        a:hover {
            background-color: rgba(255, 182, 217, 0.25);
            color: #A64D79;
            transform: translateX(-3px);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🩺 Health & Sanitation Report</h2>
    <p class="subtitle"></p>

    <?php if ($message): ?>
        <div class="msg"><?php echo $message; ?></div>
        <a href="citizen_dashboard.php">Back to Dashboard</a>
    <?php else: ?>
        <form method="POST">

            <label>Issue Type</label>
            <select name="issue_type" required>
                <option value="" disabled selected>Select issue</option>
				<option value="Water and Waste">Water and Waste</option>
                <option value="Food Safety and Hygiene">Food Safety and Hygiene</option>
                <option value="Public Sanitation Facilities">Public Sanitation Facilities</option>
                <option value="Vector and Disease Control">Vector and Disease Control</option>
                <option value="Environmental and Community Health">Environmental and Community Health</option>
            </select>

            <label>Severity Rate</label>
            <select name="severity_rate" required>
                <option value="" disabled selected>Select severity</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Critical">Critical</option>
            </select>

            <label>Risk Score (1–5)</label>
            <input type="number" name="risk_score" min="1" max="5" required>
            <div class="hint">Higher score means higher public health risk</div>

            <input type="submit" value="Submit Report">
        </form>
        <a href="citizen_dashboard.php">Back to Dashboard</a>
    <?php endif; ?>
</div>
</body>
</html>