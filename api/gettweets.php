<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Prepare and execute the SQL statement to fetch tweets
    $stmt = $conn->prepare("SELECT t.id, t.content, t.date_tweeted, u.firstname, u.lastname FROM tweets t JOIN users u ON t.user_id = u.id ORDER BY t.date_tweeted DESC");
    $stmt->execute();
    $stmt->bind_result($tweet_id, $content, $date_tweeted, $firstname, $lastname);

    $tweets = array();

    while ($stmt->fetch()) {
        $tweet = array(
            'id' => $tweet_id,
            'content' => $content,
            'date_tweeted' => $date_tweeted,
            'firstname' => $firstname,
            'lastname' => $lastname
        );
        $tweets[] = $tweet;
    }

    // Return the fetched tweets
    echo json_encode($tweets);

    $stmt->close();
}
?>
