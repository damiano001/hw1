<?php
require_once 'dbconfig.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

#Gestione pubblicazione di un post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post'])) {
    
    $postContent = $_POST['post_content'];
    $postImage = '';
    #Gestione gif/immagine 
    if (!empty($_POST['selected_gif_url'])) {
        $postImage = $_POST['selected_gif_url'];

    } elseif (!empty($_FILES['post_image']['name'])) {        
        $uploadDir = "uploads/";
        $uploadFile = $uploadDir . basename($_FILES['post_image']['name']);
        if (move_uploaded_file($_FILES['post_image']['tmp_name'], $uploadFile)) {            
            $postImage = $uploadFile;
        }
    }
    
    #Connessione al database
    $host = $dbconfig['host'];
    $username =  $dbconfig['user'];
    $password = $dbconfig['password'];
    $name = $dbconfig['name'];
    
    $conn = mysqli_connect($host,$username,$password,$name);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    
    #Gestione query
    $username = $_SESSION['username'];      
    $postContent = mysqli_real_escape_string($conn, $postContent);
    $postImage = mysqli_real_escape_string($conn, $postImage);    
    
    $query = "INSERT INTO posts (username, post_content, post_image) VALUES ('$username', '$postContent', '$postImage')";

    if (mysqli_query($conn, $query)) {        
        header("Location: home.php");
        exit();
    } 

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Home</title>
  <link rel="stylesheet" href="home.css">
  <link rel="stylesheet" href="giphy.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script src="home.js" defer></script>
  <script src="giphy.js"defer></script>  
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
  
  <div class="post-form">
  <form method="post" enctype="multipart/form-data">
    <textarea name="post_content" placeholder="What's on your mind?"></textarea>
    <input type="file" name="post_image" id="post-image-input" accept="image/*,video/gif">
    <input type="hidden" name="selected_gif_url" id="selected-gif-url">
    <label for="post-image-input"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/Picture_icon_BLACK.svg/1156px-Picture_icon_BLACK.svg.png"  style="height: 35px; width:40px; margin-right: 10px; cursor: pointer" ></label>
    <img class="giphy-icon" src="https://cdn.icon-icons.com/icons2/2699/PNG/512/giphy_logo_icon_168175.png" style="height: 38px; width:87px; margin-right: 10px; cursor:pointer" onclick="openGiphyModal()">

    <button type="submit" name="post">Post</button>

     <!-- Modale GIPHY -->
     <div class="giphy-modal">
      <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="gifs-container"></div>
      </div>
    </div>
  </form>
</div>

  
  <div class="feed-container">
    <div class="feed">
      
    </div>
  </div>
</div>

</body>
</html>