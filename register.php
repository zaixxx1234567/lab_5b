<?php
// Database configuration
$host = 'localhost';
$dbname = 'lab_5b';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $matric = $_POST['matric'];
        $name = $_POST['name'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing
        $role = $_POST['role'];

        // Check if matric already exists
        $checkQuery = "SELECT matric FROM users WHERE matric = :matric";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':matric', $matric);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            echo "Error: Matric number already exists.";
        } else {
            // Insert data into the users table
            $sql = "INSERT INTO users (matric, name, password, role) VALUES (:matric, :name, :password, :role)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':matric', $matric);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            // Redirect to the display_users.php page after successful registration
            header("Location: login.php");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<h1>Registration Form</h1> 
    <form method="POST" action="">
        <label for="matric">Matric:</label>
        <input type="text" id="matric" name="matric" maxlength="10" required><br><br>
        
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" maxlength="100" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="">Please select</option>
            <option value="lecturer">Lecturer</option>
            <option value="student">Student</option>
        </select><br><br>
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>
