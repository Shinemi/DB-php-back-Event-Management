<?php
session_start();
require_once "./config/connect.php";

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

<h1>inscription confirmée</h1>
<a href="./events.php">Retour aux evenement</a>

<?php 
    $req= 'SELECT SELECT e.title_event, e.description_event, e.date_event, e.place_event, i.statut_inscription
	FROM inscriptions i 
	INNER JOIN events e ON i.fk_id_event = id_event
	WHERE i.fk_id_user = 1'

?>