<?php
require_once 'dbconfig.php';

session_start();

#Connessione al database
$host = $dbconfig['host'];
$username =  $dbconfig['user'];
$password = $dbconfig['password'];
$name = $dbconfig['name'];

$conn = mysqli_connect($host,$username,$password,$name);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}



$error = array();

if (isset($_POST['register'])){ 
#Controllo username    
$username = mysqli_real_escape_string($conn, $_POST['username']);
$query = "SELECT username FROM users WHERE username = '$username'";
$res = mysqli_query($conn, $query);
if (mysqli_num_rows($res) > 0) {
    $error[] = "Username not available";
}

#Controllo password
if (strlen($_POST["password"]) < 8) {
  $error[] = "Password is too short";
} 
if (!preg_match("/[A-Z]/", $_POST["password"])) {
$error[] = "Password must contain a capital letter";
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
$error[] = "Password must contain a number";
}

#Esecuzione query (se non ci sono errori)
if (count($error) == 0){
  $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

  if (mysqli_query($conn, $query)) {
    $success_msg = "Registration successful";
  } else {
    $error =  "Registration failed: " . mysqli_error($conn);
  }
}

mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
  <link rel="stylesheet"  href="style.css">
</head>
<body>
  <h2>Welcome</h2>
  <form method="post">
  <img src="https://st2.depositphotos.com/5142301/7567/v/950/depositphotos_75676827-stock-illustration-abstract-green-leaf-sphere-logo.jpg">
    <label>Username:</label>
    <input type="text" name="username" required>
    <br><br>
    <label>Email:</label>
    <input type="email" name="email" required>
    <br><br>
    <label>Password:</label>
    <input type="password" name="password" required>
    <br><br>
    <input type="submit" name="register" value="Register">
    <p>Already have an account? <a href="login.php">Login</a></p>
  </form>

  <!-- Stampa errori -->
  <?php if(isset($error) && is_array($error) && count($error) > 0): ?>
    <div class="error"><?php echo $error[0]; ?></div>
  <?php elseif(isset($success_msg)): ?>
    <div class="success"><?php echo $success_msg; ?></div>
  <?php endif; ?>





</body>
</html>