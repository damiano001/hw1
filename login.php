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


if (isset($_POST['login'])) {

  $username = $_POST['username'];
  $password = $_POST['password'];


  $query = "SELECT * FROM users WHERE username = ? AND password = ?";

  #Prevenzione SQL injection
  $stmt = mysqli_prepare($conn, $query);   
  mysqli_stmt_bind_param($stmt, "ss", $username, $password); 
  mysqli_stmt_execute($stmt);  

  $result = mysqli_stmt_get_result($stmt);
  if (mysqli_num_rows($result) == 1) {    
    $_SESSION['username'] = $username;
    header('Location: home.php');
  } else {    
    $error =  "Invalid username or password";
  } 
  mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
  <link rel="stylesheet"  href="style.css">
</head>
<body>
  <h2>Welcome back</h2>
  <form method="post">
  <img src="https://st2.depositphotos.com/5142301/7567/v/950/depositphotos_75676827-stock-illustration-abstract-green-leaf-sphere-logo.jpg">
    <label>Username:</label>
    <input type="text" name="username" required>
    <br><br>
    <label>Password:</label>
    <input type="password" name="password" required>
    <br><br>
    <input type="submit" name="login" value="Login">
    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    
  </form>

<!-- Stampa errori -->
  <?php if(isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
  <?php endif; ?>


</body>
</html>