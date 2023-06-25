<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $user_id = $_SESSION['user_id'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($currentPassword, $hashedPassword)) {
        // Hash the new password
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newHashedPassword, $user_id);

        if ($stmt->execute()) {
            // Password change successful
            $response = array('message' => 'Password change successful');
            echo json_encode($response);
        } else {
            // Password change failed
            $response = array('message' => 'Password change failed');
            echo json_encode($response);
        }

        $stmt->close();
    } else {
        // Incorrect current password
        $response = array('message' => 'Incorrect current password');
        echo json_encode($response);
    }
}
?>
