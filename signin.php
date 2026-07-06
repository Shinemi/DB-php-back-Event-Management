<?php
    $message = "";
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = htmlspecialchars(trim($_POST['email']));
            $pass = htmlspecialchars(trim($_POST['pass']));
            // On fait les contrôles sur les données
            $isFormOk = true; // Est-ce que le formulaire est correctement rempli
            // On vérifie que les champs ne sont pas vides
            if(empty($email) || empty($pass)){
                $message = "Tous les champs doivent être renseignés";
                $isFormOk = false;
            }

            // On vérifie si le mail est bien un mail
            if($isFormOk && !filter_var($email, FILTER_VALIDATE_EMAIL)){
                $message = "Le mail n'est pas valide";
                $isFormOk = false;
            }

            // Si aucun message d'erreur
            if($isFormOk){
                require_once "./config/connect.php";
                
                // Faire une comparaison dans la BDD pour voir si mail unique
                $sql = "SELECT COUNT(email_user) AS nbEmail FROM users WHERE email_user = :email";

                $data = $db->prepare($sql);

                $data->execute([
                    'email' => $email
                ]);

                // Si mail déjà présent bdd → afficher un message
                $nbEmail = $data->fetch();

                if($nbEmail[0] < 1){
                    $message = "Connexion impossible";
                    $isFormOk = false;
                }

                // Si on arrive ici, tout est au vert, on peut tenter de connecter l'utilisateur
                if($isFormOk){
                    $sql = "SELECT pass_user AS pass, firstname_user, lastname_user , fk_id_role , id_user FROM users WHERE email_user = :email";

                    $data = $db->prepare($sql);

                    $data->execute([
                        'email' => $email
                    ]);

                    $data = $data->fetch(); // $data['pass']

                    if(password_verify($pass, $data['pass'])){
                        // On fait la connexion
                        session_start();
                        $_SESSION['firstname'] = $data['firstname_user'];
                        $_SESSION['lastname'] = $data['lastname_user'];
                        $_SESSION['email'] = $email;
                        $_SESSION['role']=$data['fk_id_role'];
                        $_SESSION['idUser'] = $data['id_user'];

                        header('Location: profile.php');
                        exit;
                    }

                    // Si on arrive ici, c'est que le mail et mdp ne correspondent pas
                    $message = "Mail ou mot de passe invalide";
                }
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    <h1>Connexion</h1>

    <?php if (!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form action="#" method="POST">
        <label for="email">Email :</label>
        <input type="text" id="email" name="email">
        <br>
        <label for="pass">Mot de passe :</label>
        <input type="password" id="pass" name="pass">
        <br>
        <button>Se connecter</button>
    </form>
    <a href="./register.php">s'inscrire</a>
</body>
</html>