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

#Gestione query
$username = $_SESSION['username'];
$query = "SELECT id, username, post_content, post_image, created_at FROM posts WHERE username = '{$username}' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$posts = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $post = [
            'id' => $row['id'],
            'username' => $row['username'],
            'post_content' => $row['post_content'],
            'created_at' => $row['created_at']
        ];

        
        if (!empty($row['post_image'])) {            
                $post['post_image'] =  $row['post_image'];             
        }

        $posts[] = $post;
    }
}

mysqli_close($conn);


header('Content-Type: application/json');
echo json_encode($posts);
?>
