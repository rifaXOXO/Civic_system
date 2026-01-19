<?php
session_start();
include('dbconnect.php');
// Only citizens can submit fire hazard reports
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'citizen') {
    echo "Access denied!";
    exit();
}
// Report ID must come from report table
if (!isset($_GET['report_id'])) {
    echo "No report selected!";
    exit();
}
$report_id = (int) $_GET['report_id'];
$message = "";
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hazard_type   = $_POST['hazard_type'];
    $severity_rate = $_POST['severity_rate'];
    $fire_status   = 'Pending'; // admin will update later
    $sql = "
        INSERT INTO fire_hazard (report_id, hazard_type, severity_rate, fire_status)
        VALUES ('$report_id', '$hazard_type', '$severity_rate', '$fire_status')
    ";
    if ($conn->query($sql)) {
        $message = "Fire hazard report submitted successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Fire Hazard Report</title>
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
        
        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 25px;
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
        input[type="text"],
        input[type="number"] {
            padding: 14px 18px;
            border: 2px solid #F5D8F5;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #FFF5FF 0%, #FFF0FF 100%);
            color: #6E3A5E;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        select:focus,
        input[type="text"]:focus,
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
        
        .back {
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
        
        .back::before {
            content: "← ";
            margin-right: 5px;
        }
        
        .back:hover {
            background-color: rgba(255, 182, 217, 0.25);
            color: #A64D79;
            transform: translateX(-3px);
        }
        
        .severity-info {
            font-size: 13px;
            color: #B57BB4;
            margin-top: -18px;
            margin-bottom: 20px;
            font-style: italic;
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
    <h2>🔥 Submit Fire Hazard Report</h2>
    <p class="subtitle"></p>
    
    <?php if ($message): ?>
        <div class="msg"><?php echo $message; ?></div>
        <a class="back" href="citizen_dashboard.php">Back to Dashboard</a>
    <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label>Hazard Type</label>
                <select name="hazard_type" required>
                    <option value="" disabled selected>Select hazard type</option>
                    <option value="Structural Fire">Structural Fire</option>
                    <option value="Wild Fire">Wild Fire</option>
                    <option value="Vehicle Fire">Vehicle Fire</option>
                    <option value="Industrial Fire">Industrial Fire</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Severity Rate</label>
                <select name="severity_rate" required>
                    <option value="" disabled selected>Select severity</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Critical">Critical</option>
                </select>
                <span class="severity-info"></span>
            </div>
            
            <input type="submit" value="Submit Fire Hazard Report">
        </form>
        <a class="back" href="citizen_dashboard.php">Back to Dashboard</a>
    <?php endif; ?>
</div>
</body>
</html>