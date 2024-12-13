<?php
session_start(); // Start the session

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}


// Database configuration
$host = 'localhost';
$dbname = 'lab_5b';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['matric'])) {
        $matric = $_GET['matric'];

        // Fetch the user details
        $query = "SELECT * FROM users WHERE matric = :matric";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':matric', $matric);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "User not found.";
            exit();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $matric = $_POST['matric'];
        $name = $_POST['name'];
        $role = $_POST['role'];

        // Update the user details
        $updateQuery = "UPDATE users SET name = :name, role = :role WHERE matric = :matric";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':matric', $matric);
        $updateStmt->bindParam(':name', $name);
        $updateStmt->bindParam(':role', $role);
        $updateStmt->execute();

        header("Location: users.php");
        exit();
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
    <title>Update User</title>
    <a href="logout.php" class="logout-link">Logout</a>

    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <h1>Update User</h1>
    <form method="POST" action="">
        <label for="matric">Matric:</label>
        <input type="text" id="matric" name="matric" value="<?php echo htmlspecialchars($user['matric']); ?>" readonly><br><br>
        
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>
        
        <label for="role">Access Level:</label>
        <select id="role" name="role" required>
            <option value="lecturer" <?php echo $user['role'] === 'lecturer' ? 'selected' : ''; ?>>Lecturer</option>
            <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
        </select><br><br>
        
        <button type="submit">Update</button>
        <a href="users.php">Cancel</a>
    </form>
</body>
</html>
