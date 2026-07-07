<?php
include "./header.php";
session_start();
require_once "./config/connect.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: events.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: events.php');
    exit;
}

$idEvent = $_GET['id'];

$reqEvent = "SELECT title_event, fk_id_user FROM events WHERE id_event = :id";
$dataEvent = $db->prepare($reqEvent);
$dataEvent->execute(['id' => $idEvent]);
$event = $dataEvent->fetch();

if (!$event || $event['fk_id_user'] != $_SESSION['idUser']) {
    header('Location: events.php');
    exit;
}

$reqParticipants = "SELECT firstname_user, lastname_user, email_user
                     FROM users u
                     INNER JOIN events_has_users ehu ON ehu.fk_id_user = u.id_user
                     WHERE ehu.fk_id_event = :id";
$dataParticipants = $db->prepare($reqParticipants);
$dataParticipants->execute(['id' => $idEvent]);
$participants = $dataParticipants->fetchAll();
?>

<h1>Participants de "<?= $event['title_event'] ?>"</h1>

<?php if (count($participants) === 0): ?>
    <p>Aucun participant inscrit pour l'instant.</p>
    <?php else: ?>
        <?php if (count($participants) === 0): ?>
        <p>Aucun participant inscrit pour l'instant.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($participants as $participant): ?>
                <li><?= $participant['firstname_user'] ?> <?= $participant['lastname_user'] ?> - <?= $participant['email_user'] ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>

<a href="./events.php">Retour aux evenements</a>
</body>
</html>