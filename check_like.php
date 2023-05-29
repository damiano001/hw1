<?php
require_once 'dbconfig.php';

session_start();


if (!isset($_SESSION['username'])) {
  http_response_code(401); // Unauthorized
  die();
}

$postId = json_decode(file_get_contents('php://input'))->postId;


$conn = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


$query = "SELECT COUNT(*) FROM likes WHERE post_id = '$postId' AND username = '".$_SESSION['username']."'";
$result = mysqli_query($conn, $query);
$count = mysqli_fetch_array($result)[0];


mysqli_close($conn);


header('Content-Type: application/json');
echo json_encode(['liked' => $count > 0]);
