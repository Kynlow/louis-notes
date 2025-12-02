<?php
session_start();
require_once 'config.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $errors[] = "Veuillez entrer votre email et votre mot de passe.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(["email" => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {

            $_SESSION["user_id"]  = $user["id"];
            $_SESSION["email"]    = $user["email"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["admin"]    = $user["admin"];

            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
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
		<link rel="stylesheet" href="styles.css" />
		<title>Louis-Notes - Connexion</title>
	</head>
	<body>
		<img
			src="assets/logo-eden.png"
			alt="logo-eden"
			class="logo"
			height="110px"
		/>
		<main>
			<img
				src="assets/background-login.png"
				alt="background"
				class="bg-effect"
			/>
			<div class="content">
				<h1>CONNEXION</h1>

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
					<button type="submit">Se connecter</button>
				</form>

				<div class="login">
					<p>Pas encore de compte ?</p>
					<a href="inscription.php"
						><p class="lien">S'inscrire ici</p></a
					>
				</div>
			</div>
		</main>
	</body>
</html>
