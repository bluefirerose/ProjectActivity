<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $user_id = $_SESSION['user_id'];
    $tweet_id = $_POST['tweet_id'];

    // Prepare and execute the SQL statement to check if the tweet belongs to the user
    $stmt = $conn->prepare("SELECT id FROM tweets WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $tweet_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Delete the tweet
        $stmt = $conn->prepare("DELETE FROM tweets WHERE id = ?");
        $stmt->bind_param("i", $tweet_id);

        if ($stmt->execute()) {
            // Tweet deletion successful
            $response = array('message' => 'Tweet deletion successful');
            echo json_encode($response);
        } else {
            // Tweet deletion failed
            $response = array('message' => 'Tweet deletion failed');
            echo json_encode($response);
        }

        $stmt->close();
    } else {
        // User does not own the tweet
        $response = array('message' => 'You do not have permission to delete this tweet');
        echo json_encode($response);
    }
}
?>
