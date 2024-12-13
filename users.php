
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

    // Delete user if requested
    if (isset($_GET['delete'])) {
        $matric = $_GET['delete'];
        $deleteQuery = "DELETE FROM users WHERE matric = :matric";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bindParam(':matric', $matric);
        $stmt->execute();
        header("Location: users.php");
        exit();
    }

    // Fetch all users
    $query = "SELECT matric, name, role FROM users";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <a href="logout.php" class="logout-link">Logout</a>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Users List</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Level</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['matric']); ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <a href="update_users.php?matric=<?php echo $user['matric']; ?>">Update</a>
                    <a href="users.php?delete=<?php echo $user['matric']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
