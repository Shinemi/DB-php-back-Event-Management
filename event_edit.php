<?php
session_start();
include "./header.php";
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

// On récupère l'événement et on vérifie qu'il appartient bien à l'organisateur connecté
$reqEvent = "SELECT title_event, description_event, date_event, place_event, fk_id_user FROM events WHERE id_event = :id";
$dataEvent = $db->prepare($reqEvent);
$dataEvent->execute(['id' => $idEvent]);
$event = $dataEvent->fetch();

if (!$event || $event['fk_id_user'] != $_SESSION['idUser']) {
    header('Location: events.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $date = htmlspecialchars(trim($_POST['date']));
    $place = htmlspecialchars(trim($_POST['place']));

    if (empty($title) || empty($description) || empty($date) || empty($place)) {
        $message = "Tous les champs doivent être renseignés";
    } else {
        $reqUpdate = "UPDATE events
                      SET title_event = :title,
                          description_event = :description,
                          date_event = :date,
                          place_event = :place
                      WHERE id_event = :id AND fk_id_user = :idUser";

        $dataUpdate = $db->prepare($reqUpdate);
        $dataUpdate->execute([
            'title' => $title,
            'description' => $description,
            'date' => $date,
            'place' => $place,
            'id' => $idEvent,
            'idUser' => $_SESSION['idUser']
        ]);

        header('Location: events.php');
        exit;
    }
}
?>


<h1>Modifier l'événement</h1>

<?php if (!empty($message)): ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form action="#" method="POST">
    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" value="<?= $event['title_event'] ?>">
    <br>
    <label for="description">Description :</label>
    <textarea id="description" name="description"><?= $event['description_event'] ?></textarea>
    <br>
    <label for="date">Date :</label>
    <input type="date" id="date" name="date" value="<?= $event['date_event'] ?>">
    <br>
    <label for="place">Lieu :</label>
    <input type="text" id="place" name="place" value="<?= $event['place_event'] ?>">
    <br>
    <button>Enregistrer</button>
</form>

<a href="./events.php">Retour aux evenements</a>
</body>
</html>