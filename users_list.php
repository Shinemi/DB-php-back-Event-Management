<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('Location: profile.php');
    exit;
}

require_once "./config/connect.php";

$req = "SELECT firstname_user, lastname_user, email_user, fk_id_role FROM users";
$data = $db->prepare($req);
$data->execute();
$users = $data->fetchAll();
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

<h1>Liste des utilisateurs</h1>

<?php if (count($users) === 0): ?>
    <p>Aucun utilisateur enregistré.</p>
<?php else: ?>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?= $user['firstname_user'] ?> <?= $user['lastname_user'] ?> - <?= $user['email_user'] ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="./profile.php">Retour au profil</a>
</body>
</html>