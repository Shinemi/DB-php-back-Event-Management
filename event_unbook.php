<?php
session_start();

if (!isset($_SESSION['idUser'])) {
    header('Location: signin.php');
    exit;
}

require_once "./config/connect.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: profile.php');
    exit;
}

$idEvent = $_GET['id'];

$req = "DELETE FROM events_has_users WHERE fk_id_event = :idEvent AND fk_id_user = :idUser";
$data = $db->prepare($req);
$data->execute([
    'idEvent' => $idEvent,
    'idUser' => $_SESSION['idUser']
]);

header('Location: profile.php');
exit;