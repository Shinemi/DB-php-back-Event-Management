<?php
session_start();
include "./header.php";
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