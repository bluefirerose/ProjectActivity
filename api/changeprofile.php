<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $user_id = $_SESSION['user_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, birthdate = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $firstname, $lastname, $email, $birthdate, $user_id);

    if ($stmt->execute()) {
        // Profile update successful
        $response = array('message' => 'Profile update successful');
        echo json_encode($response);
    } else {
        // Profile update failed
        $response = array('message' => 'Profile update failed');
        echo json_encode($response);
    }

    $stmt->close();
}
?>
