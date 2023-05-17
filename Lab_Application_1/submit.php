<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students";

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        gender ENUM('Male', 'Female') NOT NULL
    )";
    $conn->exec($sql);

    // Validate and insert form data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullname = $_POST["fullname"];
        $email = $_POST["email"];
        $gender = $_POST["gender"];

        $sql = "INSERT INTO students (full_name, email, gender) VALUES (:fullname, :email, :gender)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);

        if ($stmt->execute()) {
            echo "New record created successfully<br><br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->errorInfo()[2] . "<br><br>";
        }
    }

    // Display registered students
    $sql = "SELECT id, full_name, email, gender FROM students";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<h2>Registered Students</h2>";
        echo "<table><tr><th>ID</th><th>Full Name</th><th>Email</th><th>Gender</th></tr>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>" . $row["id"]. "</td><td>" . $row["full_name"]. "</td><td>" . $row["email"]. "</td><td>" . $row["gender"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No students registered yet.";
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$conn = null;
?>
