<?php

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $pass = htmlspecialchars(trim($_POST['pass']));

    $isFormOk = true;
    if (empty($firstname) || empty($lastname) || empty($email) || empty($pass)) {
        $message = 'Tous les champs doivent être renseignés';
        $isFormOk = false;
    }

    if ($isFormOk && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Le mail est invalide";
        $isFormOk = false;
    }

   if ($isFormOk) {
        $firstname = ucfirst($firstname);
        $lastname = ucfirst($lastname);
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        require_once "./config/connect.php"; 

        $sql = "SELECT email_user FROM users WHERE email_user = :email";
        $stmt = $db->prepare($sql);
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $message = "Cet email est déjà utilisé";
            $isFormOk = false;
        } else {
           $sqlInsert = "INSERT INTO users (firstname_user, lastname_user, email_user, pass_user, fk_id_role)
                        VALUES (:firstname, :lastname, :email, :pass, :fk_id_role)";
            $stmtInsert = $db->prepare($sqlInsert);
            $stmtInsert->execute([
                'firstname' => $firstname,
                'lastname'  => $lastname,
                'email'     => $email,
                'pass'      => $hash,
                'fk_id_role'   => 3 
            ]);

            $message = "Inscription réussie !";
        }

    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>

    <header>
        <nav>
            <ul>
                <li><a href="signin.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="events.php">Evenements</a></li>
            </ul>
        </nav>
    </header>
    
    <h1>Inscription</h1>

    <?php if (!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form action="#" method="POST">
        <label for="firstname">Prénom :</label>
        <input type="text" id="firstname" name="firstname">
        <br>
        <label for="lastname">Nom :</label>
        <input type="text" id="lastname" name="lastname">
        <br>
        <label for="email">Email :</label>
        <input type="text" id="email" name="email">
        <br>
        <label for="pass">Mot de passe :</label>
        <input type="password" id="pass" name="pass">
        <br>
        <button>S'inscrire</button>
    </form>

    <a href="./signin.php">se connecter</a>

</body>
</html>