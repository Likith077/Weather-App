<?php
// Allow cross-origin requests
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Connect to MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get credentials
  $data = json_decode(file_get_contents("php://input"), true);
  $username = $data['username'];
  $password = $data['password'];

  // Validate login
  $username = mysqli_real_escape_string($conn, $username);
  $password = mysqli_real_escape_string($conn, $password);

  // Hash the password (you may need to adjust this based on how passwords are stored in your database)
  $hashedPassword = md5($password);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$hashedPassword'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, login successful
    echo json_encode(array("status" => "success", "message" => "Login successful"));
  } else {
    // User not found or invalid credentials
    echo json_encode(array("status" => "error", "message" => "Invalid username or password"));
  }
}

// Close database connection
$conn->close();
?>
