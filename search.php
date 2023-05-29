<?php
require_once 'dbconfig.php';

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];

    
    #Connessione al database
    $host = $dbconfig['host'];
    $username =  $dbconfig['user'];
    $password = $dbconfig['password'];
    $name = $dbconfig['name'];

    $conn = mysqli_connect($host,$username,$password,$name); 

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    #Query di ricerca
    $searchPattern = "%" . $searchQuery . "%";
    $query = "SELECT * FROM users WHERE username LIKE '$searchPattern'";
    $result = mysqli_query($conn, $query);
    
    $searchResults = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    }

    
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Search</title>
    <link rel="stylesheet" href="search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
   <header class="header"> 
   <div class="logo">
        <img src="https://st2.depositphotos.com/5142301/7567/v/950/depositphotos_75676827-stock-illustration-abstract-green-leaf-sphere-logo.jpg">
        <span class="network-name" style="font-size: 24px; margin-left: 30px;position: absolute; margin-top: 5px;">Social Network</span>
   </div> 
  
  
  <div class="profile-icons">
    <a href="home.php"><i class="fas fa-home"></i></a> 
    <a href="search.php"><i class="fas fa-search"></i></a>
    <a href="profile.php"><i class="fas fa-user"></i></a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</header>
<div class="user-search">
    <h1>User Search</h1>
    <form action="search.php" method="GET" >
        <input type="text" name="search" placeholder="Enter username" required>
        <button type="submit">Search</button>
    </form>
</div>
    <?php if (isset($searchResults)) : ?>
        <h2>Search Results:</h2>
        <?php if (empty($searchResults)) : ?>
            <p >No users found with the given username.</p>
        <?php else : ?>
            <ul >
                <?php foreach ($searchResults as $user) : ?>
                    <li >
                    <?php echo $user['username']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
