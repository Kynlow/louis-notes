<?php
session_start();
require_once 'config.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = ""; // volontairement vide
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $errors[] = "Veuillez remplir l'email et le mot de passe.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse email invalide.";
    }

    if (empty($errors)) {
        // Vérifier si l'email existe déjà
        $check = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $check->execute(['email' => $email]);

        if ($check->fetch()) {
            $errors[] = "Cet email est déjà utilisé.";
        } else {
            // Insérer utilisateur (admin = 0 par défaut)
            $insert = $pdo->prepare("
                INSERT INTO users (username, email, password, admin)
                VALUES (:username, :email, :password, 0)
            ");

            $insert->execute([
                ":username" => $username,
                ":email"    => $email,
                ":password" => password_hash($password, PASSWORD_DEFAULT)
            ]);

            // Connexion auto
            $userId = $pdo->lastInsertId();

            $_SESSION["user_id"]  = $userId;
            $_SESSION["email"]    = $email;
            $_SESSION["username"] = $username;
            $_SESSION["admin"]    = 0;

            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link
			href="https://fonts.googleapis.com/css2?family=Antonio:wght@100..700&family=Big+Shoulders:opsz,wght@10..72,100..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kumbh+Sans:wght@100..900&family=League+Spartan:wght@100..900&family=Unbounded:wght@200..900&display=swap"
			rel="stylesheet"
		/>
		<link rel="stylesheet" href="Inscription.css" />
		<title>Louis-Notes - Inscription</title>
	</head>
	<body>
		<img
			src="assets/logo-eden.png"
			alt="logo-eden"
			class="logo"
			height="110px"
		/>
		<main>
			<div class="content">
				<h1>Inscription</h1>

                <?php if (!empty($errors)) : ?>
                    <div class="errors">
                        <?php foreach ($errors as $e) : ?>
                            <p><?= htmlspecialchars($e) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

				<form method="POST" class="input">
					<input
						type="email"
						name="email"
						placeholder="Adresse email"
						value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
					/>
					<input
						type="password"
						name="password"
						placeholder="Mot de passe"
					/>
					<input
						type="password"
						name="confirm-password"
						placeholder="Confirmer le mot de passe"
					/>
					<button type="submit">Créer un Compte</button>
				</form>
			</div>
		</main>
	</body>
</html>
