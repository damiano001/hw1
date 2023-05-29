<?php
require_once 'dbconfig.php';

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $postData = json_decode(file_get_contents('php://input'), true);
    $postId = $postData['postId'];

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
    $query = "SELECT * FROM comments WHERE post_id = '$postId'";
    $result = mysqli_query($conn, $query);
    
    $comments = array();
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $comments[] = $row;
        }
    }

    
    mysqli_close($conn);
    
    echo json_encode($comments);
}
?>
