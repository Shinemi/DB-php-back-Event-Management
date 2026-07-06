<?php
session_start();
require_once "./config/connect.php";

$req = "SELECT e.title_event, e.description_event, e.date_event, e.place_event
        FROM events e
        INNER JOIN events_has_users ehu ON ehu.fk_id_event = e.id_event
        WHERE ehu.fk_id_user = :idUser
        ORDER BY e.date_event ASC";

$data = $db->prepare($req);
$data->execute([
    'idUser' => $_SESSION['idUser']
]);
$mesInscriptions = $data->fetchAll();
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

<h1>bienvenue <?=$_SESSION['firstname'] ?></h1>
<a href="./events.php">consulter les events</a>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
    <a href="users_list.php"><button>Liste des utilisateurs</button></a>
<?php endif; ?>


<h2>Mes inscriptions</h2>

<?php if (count($mesInscriptions) === 0): ?>
    <p>Tu n'es inscrit à aucun événement pour l'instant.</p>
<?php else: ?>
    <?php foreach ($mesInscriptions as $inscription): ?>
        <article>
            <h3><?= $inscription['title_event'] ?></h3>
            <p><?= $inscription['date_event'] ?></p>
            <p><?= $inscription['place_event'] ?></p>
            <p><?= $inscription['description_event'] ?></p>
        </article>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>