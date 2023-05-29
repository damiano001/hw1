<?php
require_once 'dbconfig.php';

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $postData = json_decode(file_get_contents('php://input'), true);
    $postId = $postData['postId'];
    $username = $_SESSION['username']; 
    

    #Connessione al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    
    $query = "SELECT id FROM likes WHERE post_id = '$postId' AND username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {       
        $response = ['success' => false, 'message' => 'User has already liked the post'];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    #Aggiornamento database
    $query = "INSERT INTO likes (post_id, username) VALUES ('$postId', '$username')";
    mysqli_query($conn, $query);
    
    $query = "UPDATE posts SET likes = likes + 1 WHERE id = '$postId'";
    mysqli_query($conn, $query);



    mysqli_close($conn);

    
    $response = ['success' => true];
    header('Content-Type: application/json');
    echo json_encode($response);
}
