<?php
session_start();

if (!isset($_SESSION['idUser'])) {
    header('Location: signin.php');
    exit;
}

require_once "./config/connect.php";
include "./header.php";


$req = "SELECT e.title_event, e.description_event, e.date_event, e.place_event, e.id_event
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
            <a href="event_unbook.php?id=<?= $inscription['id_event'] ?>"><button>Annuler mon inscription</button></a>
        </article>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>