<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidate_id = filter_var($_POST['candidate_id'], FILTER_VALIDATE_INT);
    $user_id = $_SESSION['user_id'];
    $pdo = new PDO('mysql:host=localhost;dbname=voting_system', 'root', '');

    // Check if the user has already voted
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id AND voted = 1");
    $stmt->execute(['user_id' => $user_id]);
    if ($stmt->rowCount() > 0) {
        echo "You have already voted.";
        exit();
    }

    // Record the vote
    $stmt = $pdo->prepare("INSERT INTO votes (user_id, candidate_id) VALUES (:user_id, :candidate_id)");
    $stmt->execute(['user_id' => $user_id, 'candidate_id' => $candidate_id]);

    // Update candidate votes count
    $stmt = $pdo->prepare("UPDATE candidates SET votes = votes + 1 WHERE id = :candidate_id");
    $stmt->execute(['candidate_id' => $candidate_id]);

    // Mark the user as voted
    $stmt = $pdo->prepare("UPDATE users SET voted = 1 WHERE id = :user_id");
    $stmt->execute(['user_id' => $user_id]);

    echo "Thank you for voting!";
}
?>
