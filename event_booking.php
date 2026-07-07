<?php
session_start();

if (!isset($_SESSION['idUser'])) {
    header('Location: signin.php');
    exit;
}

require_once "./config/connect.php";
include "./header.php";

$idEvent = $_GET['id'] ?? null;

$req = "INSERT INTO events_has_users (
    booking_date_event_user,
    fk_id_user,
    fk_id_event
)
VALUES (NOW(), :idUser, :idEvent)";

$data = $db->prepare($req);

$data->execute([
    'idUser' => $_SESSION['idUser'],
    'idEvent' => $idEvent
]);


?>


<h1>inscription confirmée</h1>
<a href="./events.php">Retour aux evenement</a>

<?php 
    $req= 'SELECT SELECT e.title_event, e.description_event, e.date_event, e.place_event, i.statut_inscription
	FROM inscriptions i 
	INNER JOIN events e ON i.fk_id_event = id_event
	WHERE i.fk_id_user = 1'

?>