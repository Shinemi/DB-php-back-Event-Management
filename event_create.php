<?php
session_start();
include "./header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: events.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $date = htmlspecialchars(trim($_POST['date']));
    $place = htmlspecialchars(trim($_POST['place']));

    $isFormOk = true;

    if (empty($title) || empty($description) || empty($date) || empty($place)) {
        $message = "Tous les champs doivent être renseignés";
        $isFormOk = false;
    }

    if ($isFormOk) {
        require_once "./config/connect.php";

        $req = "INSERT INTO events (title_event, description_event, date_event, place_event, fk_id_user)
                VALUES (:title, :description, :date, :place, :idUser)";

        $data = $db->prepare($req);
        $data->execute([
            'title' => $title,
            'description' => $description,
            'date' => $date,
            'place' => $place,
            'idUser' => $_SESSION['idUser']
        ]);

        header('Location: events.php');
        exit;
    }
}
?>

<h1>Créer un événement</h1>

<?php if (!empty($message)): ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form action="#" method="POST">
    <label for="title">Titre :</label>
    <input type="text" id="title" name="title">
    <br>
    <label for="description">Description :</label>
    <textarea id="description" name="description"></textarea>
    <br>
    <label for="date">Date :</label>
    <input type="date" id="date" name="date">
    <br>
    <label for="place">Lieu :</label>
    <input type="text" id="place" name="place">
    <br>
    <button>Créer</button>
</form>

<a href="./events.php">Retour aux evenements</a>
</body>
</html>