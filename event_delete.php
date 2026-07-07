<?php
session_start();

if (!isset($_SESSION['idUser'])) {
    header('Location: signin.php');
    exit;
}
if ($_SESSION['role'] != 2) {
    header('Location: events.php');
    exit;
}

require_once "./config/connect.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: events.php');
    exit;
}

$idEvent = $_GET['id'];

//appartient bien a l'organisateur connecté
$reqEvent = "SELECT fk_id_user FROM events WHERE id_event = :id";
$dataEvent = $db->prepare($reqEvent);
$dataEvent->execute(['id' => $idEvent]);
$event = $dataEvent->fetch();

if (!$event || $event['fk_id_user'] != $_SESSION['idUser']) {
    header('Location: events.php');
    exit;
}

// inscriptions liées 
// puis l'event
$reqDeleteBookings = "DELETE FROM events_has_users WHERE fk_id_event = :id";
$dataDeleteBookings = $db->prepare($reqDeleteBookings);
$dataDeleteBookings->execute(['id' => $idEvent]);

$reqDeleteEvent = "DELETE FROM events WHERE id_event = :id AND fk_id_user = :idUser";
$dataDeleteEvent = $db->prepare($reqDeleteEvent);
$dataDeleteEvent->execute([
    'id' => $idEvent,
    'idUser' => $_SESSION['idUser']
]);

header('Location: events.php');
exit;