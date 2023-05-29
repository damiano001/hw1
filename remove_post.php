<?php
require_once 'dbconfig.php';

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    #Prendo attributi dal body della richiesta post
    $postData = json_decode(file_get_contents('php://input'), true);
    $postId = $postData['postId'];
    
    $session_username = $_SESSION['username'];
    


    #Connessione al database
    $conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    
    #Gestione query
    $query = "DELETE FROM posts WHERE id = '$postId' AND username = '$session_username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_affected_rows($conn) > 0) {        
        $response = array('status' => 'success', 'message' => 'Post removed successfully');
        echo json_encode($response);

    } else {        
        $response = array('status' => 'error', 'message' => 'Failed to remove post');
        echo json_encode($response);
    }

    mysqli_close($conn);
}
?>
