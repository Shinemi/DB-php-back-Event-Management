<?php
include "./header.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('Location: events.php');
    exit;
}

require_once "./config/connect.php";

$req = "SELECT e.title_event, COUNT(ehu.fk_id_user) AS nb_participants
        FROM events e
        LEFT JOIN events_has_users ehu ON ehu.fk_id_event = e.id_event
        GROUP BY e.id_event
        ORDER BY nb_participants DESC";

$data = $db->prepare($req);
$data->execute();
$popularity = $data->fetchAll();
?>


<h1>Popularité des événements</h1>

<?php if (count($popularity) === 0): ?>
    <p>Aucun événement pour l'instant.</p>
<?php else: ?>
    <ul>
        <?php foreach ($popularity as $event): ?>
            <li><?= $event['title_event'] ?> - <?= $event['nb_participants'] ?> participant(s)</li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="./events.php">Retour aux evenements</a>
</body>
</html>