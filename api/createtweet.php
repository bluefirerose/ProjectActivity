<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO tweets (content, user_id) VALUES (?, ?)");
    $stmt->bind_param("si", $content, $user_id);

    if ($stmt->execute()) {
        // Tweet creation successful
        $response = array('message' => 'Tweet creation successful');
        echo json_encode($response);
    } else {
        // Tweet creation failed
        $response = array('message' => 'Tweet creation failed');
        echo json_encode($response);
    }

    $stmt->close();
}
?>
