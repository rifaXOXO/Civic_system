<?php
session_start();
include('dbconnect.php');

// Only citizens can submit missing person reports
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
    $person_name = $_POST['person_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $case_status = 'Pending'; // default status
    $last_location = $_POST['last_location'];
    $last_time_seen = $_POST['last_time_seen'];
    $attire = $_POST['attire'];

    // INSERT query without user_id (since it's for admin only)
    $sql = "INSERT INTO missing_person 
        (report_id, person_name, age, gender, case_status, last_location, last_time_seen, attire)
        VALUES ($report_id, '$person_name', $age, '$gender', '$case_status', '$last_location', '$last_time_seen', '$attire')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Missing Person report submitted successfully!";
    } else {
        $success_message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Missing Person Report</title>
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
            content: "👤 ";
        }
        
        form {
            display: flex;
            flex-direction: column;
        }
        
        label {
            color: #6B4A6E;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        select {
            padding: 12px 15px;
            border: 2px solid #E6D5F5;
            border-radius: 10px;
            font-size: 15px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background-color: #FEFAFF;
            color: #5A3A5E;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="datetime-local"]:focus,
        select:focus {
            outline: none;
            border-color: #C9B3E6;
            box-shadow: 0 0 0 3px rgba(201, 179, 230, 0.2);
            background-color: white;
        }
        
        select {
            cursor: pointer;
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
        
        .back-link {
            text-align: center;
            margin-top: 30px;
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
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .success-message {
            background: linear-gradient(135deg, #D4F4DD 0%, #B8E6C3 100%);
            color: #2D5F3C;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(45, 95, 60, 0.2);
        }
        
        .error-message {
            background: linear-gradient(135deg, #FFD6E8 0%, #FFC4D6 100%);
            color: #8B2E4A;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(139, 46, 74, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Missing Person Report</h2>

        <?php if ($success_message && strpos($success_message, 'Error') === false): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
            <div class="back-link">
                <a href="citizen_dashboard.php">← Back to Dashboard</a>
            </div>
        <?php elseif ($success_message): ?>
            <div class="error-message"><?php echo $success_message; ?></div>
            <form method="POST">
                <div class="form-group">
                    <label>Person Name:</label>
                    <input type="text" name="person_name" required>
                </div>
                <div class="form-group">
                    <label>Age:</label>
                    <input type="number" name="age" required>
                </div>
                <div class="form-group">
                    <label>Gender:</label>
                    <select name="gender" required>
                        <option value="" disabled selected>Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Last Location:</label>
                    <input type="text" name="last_location" required>
                </div>
                <div class="form-group">
                    <label>Last Time Seen:</label>
                    <input type="datetime-local" name="last_time_seen" required>
                </div>
                <div class="form-group">
                    <label>Attire:</label>
                    <input type="text" name="attire" required>
                </div>
                <input type="submit" value="Submit">
            </form>
            <div class="back-link">
                <a href="citizen_dashboard.php">← Back to Dashboard</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Person Name:</label>
                    <input type="text" name="person_name" required>
                </div>
                <div class="form-group">
                    <label>Age:</label>
                    <input type="number" name="age" required>
                </div>
                <div class="form-group">
                    <label>Gender:</label>
                    <select name="gender" required>
                        <option value="" disabled selected>Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Last Location:</label>
                    <input type="text" name="last_location" required>
                </div>
                <div class="form-group">
                    <label>Last Time Seen:</label>
                    <input type="datetime-local" name="last_time_seen" required>
                </div>
                <div class="form-group">
                    <label>Attire:</label>
                    <input type="text" name="attire" required>
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
