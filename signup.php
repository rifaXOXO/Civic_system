<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbconnect.php');
session_start(); // start session to store user info

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if user already exists
    $checkQuery = "SELECT * FROM user WHERE email='$email'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // User already exists, redirect to login
        echo "<script>
                alert('You already have an account. Redirecting to login page.');
                window.location.href='login.php';
              </script>";
        exit();
    }

    // Count existing users to assign role
    $countQuery = "SELECT COUNT(*) AS total FROM user";
    $result = $conn->query($countQuery);
    $row = $result->fetch_assoc();

    // First 5 users become admin
    $role = ($row['total'] < 5) ? 'admin' : 'citizen';

    // Insert user into database
    $sql = "INSERT INTO user (user_name, email, phone_no, role)
            VALUES ('$name', '$email', '$phone', '$role')";

    if ($conn->query($sql)) {
        // Get the newly inserted user's ID
        $user_id = $conn->insert_id;

        // Set session variables to log in immediately
        $_SESSION['user_id']   = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['role']      = $role;

        // Redirect directly to the appropriate dashboard
        if ($role == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: citizen_dashboard.php");
        }
        exit();
    } else {
        echo "Signup failed: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFD6E8 0%, #E6D5F5 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            max-width: 450px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            color: #8B5A8E;
            font-size: 32px;
            margin-bottom: 30px;
            font-weight: 600;
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
        input[type="email"] {
            padding: 14px 15px;
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
        input[type="email"]:focus {
            outline: none;
            border-color: #C9B3E6;
            box-shadow: 0 0 0 3px rgba(201, 179, 230, 0.2);
            background-color: white;
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
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #E6D5F5;
        }
        
        .login-link a {
            text-decoration: none;
            color: #A87CA8;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-link a:hover {
            color: #8B5A8E;
            text-decoration: underline;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Signup</h2>
        <form method="POST">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" required>
            </div>
            
            <input type="submit" value="Signup">
        </form>
        
        <div class="login-link">
            <a href="login.php">Already have an account? Login</a>
        </div>
    </div>
</body>
</html>
