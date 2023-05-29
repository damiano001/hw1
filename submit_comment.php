<?php
require_once 'dbconfig.php';

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $postData = json_decode(file_get_contents('php://input'), true);
    $postId = $postData['postId'];
    $commentContent = $postData['commentContent'];
    $session_username = $_SESSION['username'];
    

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
    $query = "INSERT INTO comments (post_id, username, comment_content) VALUES ('$postId', '$session_username', '$commentContent')";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_affected_rows($conn) > 0) {
        
        $commentId = mysqli_insert_id($conn);

        
        $authorSql = "SELECT username FROM comments WHERE id = '$commentId'";
        $authorResult = mysqli_query($conn, $authorSql);

        if ($authorResult && mysqli_num_rows($authorResult) > 0) {
            $authorRow = mysqli_fetch_assoc($authorResult);
            $authorUsername = $authorRow['username'];

            
            $response = array(
                'status' => 'success',
                'message' => 'Comment submitted successfully',
                'authorUsername' => $authorUsername
            );
            echo json_encode($response);
        } else {
            
            $response = array('status' => 'error', 'message' => 'Failed to submit comment');
            echo json_encode($response);
        }
    } else {
        
        $response = array('status' => 'error', 'message' => 'Failed to submit comment');
        echo json_encode($response);
    }

   
    mysqli_close($conn);
}
?>
