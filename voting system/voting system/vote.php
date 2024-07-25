<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=voting_system', 'root', '');

// Fetch candidates
$stmt = $pdo->query("SELECT * FROM candidates");
$candidates = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote</title>
</head>
<body>
    <h2>Vote for Your Favorite Candidate</h2>
    <form action="vote.php" method="post">
        <?php foreach ($candidates as $candidate): ?>
            <input type="radio" name="candidate_id" value="<?= $candidate['id'] ?>" required> <?= htmlspecialchars($candidate['name']) ?><br>
        <?php endforeach; ?>
        <button type="submit">Vote</button>
    </form>
</body>
</html>
