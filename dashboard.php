<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: connexion.php");
    exit;
}

$email    = $_SESSION["email"]    ?? "";
$username = $_SESSION["username"] ?? "";
$admin    = $_SESSION["admin"]    ?? 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Louis-Notes - Dashboard</title>
</head>
<body>
    <h1>Bienvenue sur Louis-Notes 👋</h1>

    <p>Email : <?= htmlspecialchars($email) ?></p>

    <?php if ($username !== ""): ?>
        <p>Nom d'utilisateur : <?= htmlspecialchars($username) ?></p>
    <?php endif; ?>

    <?php if ((int)$admin === 1): ?>
        <p><strong>Vous êtes administrateur 🔧</strong></p>
        <!-- Ici tu pourras mettre ton panneau admin -->
    <?php else: ?>
        <p>Vous êtes utilisateur normal.</p>
    <?php endif; ?>

    <a href="logout.php">Se déconnecter</a>
</body>
</html>
